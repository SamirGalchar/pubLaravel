<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Subscriber;
use App\Models\User;
use Stripe;

//for notifications
use App\Models\EmailTemplate;
use App\Library\Globalfunction;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserSubscribeNotification;
use DB;

class CheckSubscription {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    
    public function handle(Request $request, Closure $next) {
        
        if(Auth::check() && Auth::user()->role=='user'):  
            
            $subscription = Subscriber::where(['user_id'=>Auth::user()->id,'status'=>'active'])->first(); 
            
            if(Auth::user()->isPaid=='Yes' && $subscription):   
                
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                \Stripe\Stripe::setApiVersion("2018-05-21");           
                $stripe = \Stripe\Subscription::retrieve($subscription->subscription_id);
                
                if($stripe->status == 'active'):
                    
                    return $next($request);
                
                else:    
                    
                    User::find(Auth::user()->id)->update(['isPaid'=>'No']);
                    Subscriber::find($subscription->id)->update(['status'=>'blocked']);
                    
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
                    
                    session()->flash('error','Your subscription has been expired.');
                    return redirect()->route('user.plans');
                    
                endif;   
                
            else:
                
                session()->flash('error','Subscribe to get a personal trainer in your pocket.');
                User::find(Auth::user()->id)->update(['isPaid'=>'No']);
                return redirect()->route('user.plans');
            
            endif;       
            
        else:
            return redirect()->route('home');
        endif;
        
        return $next($request);
        
    }

}
