<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\Transaction;
use Stripe;
//for notifications
use App\Models\EmailTemplate;
use App\Library\Globalfunction;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserSubscribeNotification;
use DB;

class SubscriptionCron extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribers payment management';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        
        
        $subscribers = Subscriber::where(['next_payment_at'=>Carbon::yesterday(),'status'=>'active'])->where('total_paid', '<', env('SUB_INS_LIM'))->get()->toArray();
        
        \Log::channel('subscription')->info($subscribers);
        
        if (isset($subscribers) && !empty($subscribers) && count($subscribers) > 0):
            \Log::channel('subscription')->info(Carbon::today());
            foreach ($subscribers as $subscriber):

                $transactions = Transaction::where(['user_id'=>$subscriber['user_id'], 'expire_at'=>Carbon::yesterday(), 'status'=>'active'])->get()->toArray();
                
                foreach ($transactions as $transaction):

                    Transaction::where('id', $transaction['id'])->update(['status' => 'expired']);
                    User::find($subscriber['user_id'])->update(['isPaid' => 'No']);

                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    \Stripe\Stripe::setApiVersion("2018-05-21");

                    try {

                        $formData['paymentIntents'] = Stripe\PaymentIntent::create([
                                    'amount' => ($subscriber['amount'] * 100),
                                    'currency' => env('CURRENCY'),
                                    'payment_method_types' => ['card'],
                                    'confirm' => true,
                                    'customer' => $subscriber['stripe_customer_id'], //stripe_customer_id
                                    'payment_method' => $subscriber['stripe_card_id'], //stripe_card_id for multipel card of one customer
                                    'off_session' => true
                        ]);
                        if ($formData['paymentIntents']->status == 'succeeded'):

                            $param = [
                                'user_id' => $subscriber['user_id'],
                                'plan_id' => $subscriber['plan_id'],
                                'amount' => $subscriber['amount'],
                                'transaction_id' => $formData['paymentIntents']->id,
                                'purchased_at' => Carbon::today(),
                                'expire_at' => Carbon::now()->addMonths(1)
                            ];

                            $res = Transaction::create($param);
                            $user = User::find($subscriber['user_id']);
                            $user->update(['isPaid' => 'Yes']);

                            Subscriber::find($subscriber['id'])->update(['next_payment_at' => Carbon::now()->addMonths(1), 'total_paid' => ($subscriber['total_paid'] + 1)]);                            

                            //send email notification
                            $arrRows = $paraKeyArr = $paraValArr = '';
                            $arrRows = Globalfunction::convertSelectedRowInArray(EmailTemplate::where('emailKey', 'user-subscription-installment')->get()->toArray());
                            $paraKeyArr = array("###greetings###", "###SITENAME###", "###price###", "###purchased_at###", "###expire_at###", "###installment_no###");
                            $paraValArr = array($user->name, env('APP_NAME'), $subscriber['amount'], Carbon::createFromFormat('Y-m-d H:i:s', Carbon::today())->format('d/m/Y'), Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->addMonths(1))->format('d/m/Y'), ($subscriber['total_paid'] + 1));
                            $subject = $arrRows[0]['subject'];
                            $html = Globalfunction::GetMailMessageBody($arrRows[0]['templates'], $paraKeyArr, $paraValArr);
                            $details = [
                                'html' => $html,
                                'subject' => $subject,
                            ];
                            Notification::send($user, new UserSubscribeNotification($details));
                            
                            \Log::channel('subscription')->info(['monthly_payment_complete' => ['user_id'=>$subscriber['user_id'],'subscriber_id'=>$subscriber['id'],'transaction_id'=>$res->id,'number'=>($subscriber['total_paid'] + 1)]]);

                        else:
                            \Log::channel('subscription')->info(['Failed payment'=>$formData['paymentIntents']]);
                        endif;
                    } catch (\Exception $e) {
                        \Log::channel('subscription')->info(['Exception Failed payment'=>$e]);
                    }

                endforeach;

            endforeach;

        endif;

        //Expire subscription
        $subscribers = Subscriber::where('total_paid', '>', (env('SUB_INS_LIM') - 1))->where('next_payment_at', Carbon::yesterday())->where(['status'=>'active'])->get()->toArray();
        if (isset($subscribers) && !empty($subscribers) && count($subscribers) > 0):
            \Log::channel('subscription')->info(Carbon::today());
            foreach ($subscribers as $subscriber):
            
                Subscriber::find($subscriber['id'])->update(['status' => 'blocked']);
                User::find($subscriber['user_id'])->update(['isPaid' => 'No']);
                
                $transactions = Transaction::where(['user_id'=>$subscriber['user_id'],'expire_at'=>Carbon::yesterday(),'status'=>'active'])->get()->toArray();
                foreach ($transactions as $transaction):
                     Transaction::where('id', $transaction['id'])->update(['status' => 'expired']);
                endforeach;                
                
                \Log::channel('subscription')->info(['blocked_subscription'=>['user_id'=>$subscriber['user_id'],'subscriber_id'=>$subscriber['id']]]);                
            endforeach;
            
        endif;

        //return 0;
    }
    

}
