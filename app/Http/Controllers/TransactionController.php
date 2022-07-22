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
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Http\Requests\FrontEnd\subscribeNowRequest;
//for notifications
use App\Models\EmailTemplate;
use App\Library\Globalfunction;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserSubscribeNotification;
use App\Notifications\CouponReferralNotification;
use DB;
use Session;

class TransactionController extends Controller{ 
    
    public function purchasePlan(Request $request){
        
        $userId = Auth::user()->id;
        $priceId = env('PRICE_ID');
        $price = env('SUB_PRICE');
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        \Stripe\Stripe::setApiVersion("2018-05-21");
        
        if($userId && Auth::check()):
            
            //check coupon code
            if(!empty($request->coupon_code)):
                
                $coupon = Coupon::checkIsValid($request->coupon_code);
                                
                if($coupon):
                    
                    //calculate discount
                    if($coupon->discount_type == "fixed_price"):
                        $price = ($price - (float)$coupon->discount);
                    elseif($coupon->discount_type == "percentage"):    
                        $tmp = (((float)$coupon->discount / 100) * $price);
                        $price = ($price - $tmp);
                    endif;
                    
                    if($price < 0):
                        return redirect()->route('user.plans')->with('error','Something went wrong, Please try again');
                    endif;
                    
                    $request->session()->put('coupon_used_id', $coupon->id);
                    $request->session()->put('plan_price_with_coupon', $price);
                    
                    try {     
                        
                        //create product
                        $product = \Stripe\Product::create([ 
                            'name' => "Workout Videos", 
                            "images" => ["https://fit-30.online/training/front/images/payment_image.png"], 
                        ]);     
                        // Create price with subscription info and interval 
                        $checkoutPrice = \Stripe\Price::create([ 
                            'unit_amount' => round($price*100), 
                            'currency' => env('CURRENCY'), 
                            'recurring' => ['interval' => 'month'], 
                            'product' => $product->id,         
                        ]); 
                        
                        $priceId = $checkoutPrice->id;                        
                                
                    } catch (Exception $e) {  
                        session()->flash('error',$e->getMessage());
                        return redirect()->route('user.plans');                        
                    }
                
                else:
                    return redirect()->route('user.plans')->with('error','Coupon code is invalid, Please try again');
                endif;
            
            endif;
            
            
            try{
            
                $checkout_session = \Stripe\Checkout\Session::create([
                    'success_url' => 'http://127.0.0.1:8000/user/subscription/success/0',
                    'success_url' => 'http://127.0.0.1:8000/user/subscription/success/{CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('user.order-cancel'),
                    'payment_method_types' => ['card'],
                    'mode' => 'subscription',
                    'line_items' => [[
                      'price' => $priceId,
                      // For metered billing, do not pass quantity
                      'quantity' => 1,
                    ]],
                ]);
                
                if($checkout_session->id):
                    $checkout_session_id = $checkout_session->id;
                    $plans = Membership::all()->toArray();
                    \Log::channel('subscription')->info(['checkout_sesstion_created'=>['user_id'=>Auth::user()->id, 'checkout_session_id'=>$checkout_session_id,'price'=>$price,'price_id'=>$priceId ]]);
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
    
    public function subscriptionSuccess(Request $request, $session_id){
        
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
                                
                $updateCustomer = \Stripe\Customer::update(
                        $session->customer,[
                            "email"=>Auth::user()->email,
                            "name"=>Auth::user()->name,
                        ],            
                        ['metadata' => ['charset' => 'utf-8']]
                );
                
                if($request->session()->has('plan_price_with_coupon')):
                    $planPrice = $request->session()->get('plan_price_with_coupon');
                else:
                    $planPrice = $plan->price;
                endif;
                 
                $param = [
                        'user_id'=>Auth::user()->id,
                        'plan_id'=>3,
                        'stripe_customer_id'=>$session->customer,
                        "subscription_id"=>$session->subscription,
                        "checkout_session_id"=>$session_id,
                        "plan_name"=>$plan->name,
                        "amount"=>$planPrice,
                        "subscribed_at"=>Carbon::today(),
                        "end_at"=>Carbon::now()->addMonths($plan->validity),
                        "next_payment_at"=>Carbon::now()->addMonths(1),
                        "total_paid"=>1,
                        "status"=>"active",
                    ];                    
                    
                    $subscriber = Subscriber::create($param);
                    
                    if($subscriber->id):                        
                        
                        User::find(Auth::user()->id)->update(['isPaid'=>'Yes']);
                        \Log::channel('subscription')->info(['new_subscriber'=>['user_id'=>$subscriber['user_id'],'subscriber_id'=>$subscriber->id,"subscription_id"=>$session->subscription,'price'=>$planPrice,'number'=>1]]);
                        
                        //email
                        $arrRows = $paraKeyArr = $paraValArr = '';
                        $arrRows = Globalfunction::convertSelectedRowInArray(EmailTemplate::where('emailKey', 'user-subscription')->get()->toArray());
                        $paraKeyArr = array("###greetings###", "###SITENAME###", "###price###", "###purchased_at###", "###expire_at###");
                        $paraValArr = array(Auth::user()->name, env('APP_NAME'),$planPrice,Carbon::createFromFormat('Y-m-d H:i:s', Carbon::today())->format('d/m/Y'),Carbon::createFromFormat('Y-m-d H:i:s',Carbon::now()->addMonths($plan->validity))->format('d/m/Y') );
                        $subject = $arrRows[0]['subject'];
                        $html = Globalfunction::GetMailMessageBody($arrRows[0]['templates'], $paraKeyArr, $paraValArr);
                        $details = [
                            'html'=>$html,
                            'subject'=>$subject,                
                        ];
                        
                        Notification::send(Auth::user(), new UserSubscribeNotification($details));
                        
                        //coupon operation
                        if($request->session()->has('coupon_used_id')) {
                            
                            $coupon = Coupon::find($request->session()->get('coupon_used_id'));
                            CouponUser::create(['user_id'=>Auth::user()->id,'coupon_id'=>$request->session()->get('coupon_used_id')]);
                            
                            $updateData = [];

                            if($coupon):

                                //email
                                $arrRows = $paraKeyArr = $paraValArr = '';
                                $arrRows = Globalfunction::convertSelectedRowInArray(EmailTemplate::where('emailKey', 'referral-coupon-email')->get()->toArray());
                                $paraKeyArr = array("###NAME###", "###SITENAME###", "###NAME###", "###PHONE###", "###EMAIL###","###COUPON_CODE###");
                                $paraValArr = array(Auth::user()->name, env('APP_NAME'), Auth::user()->name, Auth::user()->phone, Auth::user()->email, $coupon->coupon_code);
                                $html = Globalfunction::GetMailMessageBody($arrRows[0]['templates'], $paraKeyArr, $paraValArr);
                                $subject = $arrRows[0]['subject'];

                                $details = [
                                    'html'=>$html,
                                    'subject'=>$subject,                
                                ];
                                
                                //send email to referrer
                                if(!empty($coupon->notification_email)):                                    
                                    Notification::route('mail', [$coupon->notification_email=>'Referral'])->notify(new CouponReferralNotification($details));
                                    Notification::route('mail', [env('ADMIN_EMAIL')=>'Referral'])->notify(new CouponReferralNotification($details));                                
                                else:
                                    Notification::route('mail', [env('ADMIN_EMAIL')=>'Referral'])->notify(new CouponReferralNotification($details));                                
                                endif;  
                                
                                //coupon operation
                                if($coupon->validity_type == "limited"):                    
                                    if($coupon->limit_type == "numbers"):
                                        $updateData['limit_numbers'] =  ((int)$coupon->limit_numbers - 1);
                                        if($coupon->limit_numbers <= 1):
                                            $updateData['status'] = 'inactive';
                                        endif;                    
                                        Coupon::find($request->session()->get('coupon_used_id'))->update($updateData);                                        
                                    endif;                                                        
                                endif;                
                                if($coupon->validity_type == "onetime"):
                                    $updateData['status'] = 'inactive';
                                    Coupon::find($request->session()->get('coupon_used_id'))->update($updateData);                                    
                                endif;
                                                                
                                $request->session()->forget('coupon_used_id'); 
                                
                            endif;

                        }                        

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
                    
                    //unsubscribe email
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