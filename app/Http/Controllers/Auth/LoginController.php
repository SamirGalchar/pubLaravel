<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo(){        
        if( Auth()->user()->role == 'admin' &&  Auth()->user()->isActive == 'active' ):
            return redirect()->route('admin.dashboard');
        elseif( Auth()->user()->role == 'user' &&  Auth()->user()->isActive == 'active' ):
            return redirect()->route('user.profile');
        endif;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function login(Request $request){

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if( Auth::once( $credentials ) ) {

            if(Auth::attempt(['email'=>$request['email'], 'password'=>$request['password'],'isActive'=>'active','role'=>'user'])){
                
                if(Auth()->user()->role == 'admin'):
                    return redirect()->route('admin.dashboard');
                elseif(Auth()->user()->role == 'user'):
                    return redirect()->route('user.profile');
                endif;
                
            } else {
                
                if(Auth::once( ['email'=>$request['email'], 'password'=>$request['password'],'isActive'=>'pending'])){
                    return redirect()->route('login')->withErrors([
                        'email' => 'Please activate your account by clicking on activation link given in email',
                    ]);
                    return redirect()->route('login');
                }
                else if(Auth::once( ['email'=>$request['email'], 'password'=>$request['password'],'isActive'=>'inactive'])){
                    return redirect()->route('login')->withErrors([
                        'email' => 'Your account is deactivated. Please Contact admin!',
                    ]);
                    return redirect()->route('login');
                } else {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Email or Password is wrong!',
                    ]);
                }
                
            }

        } else {
            return redirect()->route('login')->withErrors([
                'email' => 'Email or Password is wrong!',
            ]);
        }

    }
    
}
