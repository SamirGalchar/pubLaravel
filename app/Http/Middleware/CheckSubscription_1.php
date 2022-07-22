<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Subscriber;
use App\Models\User;

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
                
                if(strtotime($subscription->end_at) >= strtotime(Carbon::now()->toDateString())):
                    
                    return $next($request);
                
                else:    
                    
                    User::find(Auth::user()->id)->update(['isPaid'=>'No']);
                    return redirect()->route('user.plans');
                    
                endif;   
                
            else:
                
                User::find(Auth::user()->id)->update(['isPaid'=>'No']);
                return redirect()->route('user.plans');
            
            endif;       
            
        else:
            return redirect()->route('home');
        endif;
        
        return $next($request);
        
    }

}
