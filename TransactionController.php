<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Stripe;
use App\Models\Membership;
use App\Models\Subscriber;
use App\Models\Transaction;
use App\Models\User;
use App\Http\Requests\FrontEnd\subscribeNowRequest;
//for notifications
use App\Models\EmailTemplate;
use App\Library\Globalfunction;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserSubscribeNotification;
use DB;

class TransactionController extends Controller{
    
    public function purchasePlan(Request $request){
        
        $userId = Auth::user()->id;
        
        if($userId && Auth::check()):
            
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            \Stripe\Stripe::setApiVersion("2018-05-21");
            
            try{
            
                $checkout_session = \Stripe\Checkout\Session::create([
                    'success_url' => 'https://cf-30workout.com/training/user/subscription/success/0',
                    'success_url' => 'https://cf-30workout.com/training/user/subscription/success/{CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('user.order-cancel'),
                    'payment_method_types' => ['card'],
                    'mode' => 'subscription',
                    'line_items' => [[
                      'price' => env('PRICE_ID'),
                      // For metered billing, do not pass quantity
                      'quantity' => 1,
                    ]],
                ]);
                
                if($checkout_session->id):
                    $checkout_session_id = $checkout_session->id;
                    $plans = Membership::all()->toArray();
                    return view('subscribe.checkout', compact('checkout_session_id', 'plans'));
                else:
                    return redirect()->route('user.plans')->with('error','Subscription not found, Please try again');
                endif;
                
            } catch (\Exception $e){
                session()->flash('error',$e->getMessage());
                return redirect()->route('user.plans');
            }
            
        else:            
            return redirect()->route('user.plans')->with('error','Subscription not found.');            
        endif;
                
        //$plan = Membership::where('id',$id)->get()->toArray();
        //return view('subscribe.checkout', compact('plan'));
    }
    
    public function subscriptionSuccess($session_id){
        
        $plan = Membership::findOrFail(3);
        
        if(!empty($session_id) && Auth::check() && $plan):
            
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            \Stripe\Stripe::setApiVersion("2018-05-21");
            
            try{
            
                $session = \Stripe\Checkout\Session::retrieve($session_id);
                $customer = \Stripe\Customer::retrieve($session->customer);
                $subscription = \Stripe\Subscription::retrieve($session->subscription);
                
                //Update Subscription
                $cancel_at = Carbon::now()->addMonths(env('SUB_INS_LIM'))->timestamp;
                $updateSubscription = \Stripe\Subscription::update(
                        $session->subscription,[
                            "cancel_at"=>$cancel_at,
                        ],            
                        ['metadata' => ['charset' => 'utf-8']]
                );
                 
                $param = [
                        'user_id'=>Auth::user()->id,
                        'plan_id'=>3,
                        'stripe_customer_id'=>$session->customer,
                        "subscription_id"=>$session->subscription,
                        "checkout_session_id"=>$session_id,
                        "plan_name"=>$plan->name,
                        "amount"=>$plan->price,
                        "subscribed_at"=>Carbon::today(),
                        "end_at"=>Carbon::now()->addMonths($plan->validity),
                        "next_payment_at"=>Carbon::now()->addMonths(1),
                        "total_paid"=>1,
                        "status"=>"active",
                    ];                    
                    
                    $subscriber = Subscriber::create($param);
                    
                    if($subscriber->id):                        
                        
                        User::find(Auth::user()->id)->update(['isPaid'=>'Yes']);
                        \Log::channel('subscription')->info(['new_subscriber'=>['user_id'=>$subscriber['user_id'],'subscriber_id'=>$subscriber->id,"subscription_id"=>$session->subscription,'number'=>1]]);

                        //email
                        $arrRows = $paraKeyArr = $paraValArr = '';
                        $arrRows = Globalfunction::convertSelectedRowInArray(EmailTemplate::where('emailKey', 'user-subscription')->get()->toArray());
                        $paraKeyArr = array("###greetings###", "###SITENAME###", "###price###", "###purchased_at###", "###expire_at###");
                        $paraValArr = array(Auth::user()->name, env('APP_NAME'),$plan->price,Carbon::createFromFormat('Y-m-d H:i:s', Carbon::today())->format('d/m/Y'),Carbon::createFromFormat('Y-m-d H:i:s',Carbon::now()->addMonths($plan->validity))->format('d/m/Y') );
                        $subject = $arrRows[0]['subject'];
                        $html = Globalfunction::GetMailMessageBody($arrRows[0]['templates'], $paraKeyArr, $paraValArr);
                        $details = [
                            'html'=>$html,
                            'subject'=>$subject,                
                        ];
                        
                        Notification::send(Auth::user(), new UserSubscribeNotification($details));

                        session()->flash('success',__('Your subscription has been completed Successfully :)'));
                        return redirect()->route('user.profile');
                        
                    endif;
                
                
            } catch (\Exception $e) {
                session()->flash('error',$e->getMessage());
                return redirect()->route('user.plans');
            }
            
        endif;
        
        return redirect()->route('user.plans')->with('error','Your subscription not complete, Please try again');
                
    }
    
    public function subscriptionCancel(Request $request){
        return view('subscribe.cancel');
    }
    
    public function unsubscribeView(Request $request){
        return view('subscribe.unsubscribe');
    }
    public function unsubscribeMembership(Request $request){
        
        if($request->uid == Auth::user()->id):
            
            $subscriber = Subscriber::where(['user_id'=>Auth::user()->id, 'status'=>'active'])->get()->toArray();
            
            if($subscriber):
                
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                \Stripe\Stripe::setApiVersion("2018-05-21");
                            
                try{
                    
                    $subscription_id = $subscriber[0]['subscription_id'];
                    $subscription = \Stripe\Subscription::retrieve($subscription_id);
                    $subscription->cancel();
                    
                    Subscriber::where(['user_id'=>Auth::user()->id, 'status'=>'active'])->update(['status'=>'blocked']);
                    User::find(Auth::user()->id)->update(['isPaid'=>'No']);
                    
                    //email
                    $arrRows = $paraKeyArr = $paraValArr = '';
                    $arrRows = Globalfunction::convertSelectedRowInArray(EmailTemplate::where('emailKey', 'user-un-subscription')->get()->toArray());
                    $paraKeyArr = array("###greetings###", "###SITENAME###");
                    $paraValArr = array( Auth::user()->name, env('APP_NAME') );
                    $subject = $arrRows[0]['subject'];
                    $html = Globalfunction::GetMailMessageBody($arrRows[0]['templates'], $paraKeyArr, $paraValArr);
                    $details = [
                        'html'=>$html,
                        'subject'=>$subject,                
                    ];

                    Notification::send(Auth::user(), new UserSubscribeNotification($details));
                    

                    session()->flash('success',__('Your subscription has been canceled Successfully'));
                    return redirect()->route('user.profile');

                } catch (\Exception $e) {
                    session()->flash('error',$e->getMessage());
                    return redirect()->route('user.unsubscribe');
                }            
                
            else:
                session()->flash('error',__('You are already unsubscribed'));
                return redirect()->route('user.unsubscribe');
            endif;
            
        else:
            session()->flash('error',__('Bad request, Please try again'));
            return redirect()->route('user.unsubscribe');
        endif;
        
    }
    
}
