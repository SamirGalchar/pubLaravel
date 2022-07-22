<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

//for notifications
use App\Models\EmailTemplate;
use App\Library\Globalfunction;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RegisterUserNotification;
use App\Notifications\RegisterUserAdminNotification;
use DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = 'user/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'isActive'=>'pending',
            'password' => Hash::make($data['password']),
        ]);
        
        if(!empty($user->id)):
        
            // send mail to user
            $arrRows = $activateLink = $paraKeyArr = $paraValArr = '';
            $emailWhrKey = 'user-register';
            $activeLinkText = 'Click here to activate your account';
            $arrRows = Globalfunction::convertSelectedRowInArray(EmailTemplate::where('emailKey', $emailWhrKey)->get()->toArray());
            $activateLink = '<a href="' .route('user-activate', base64_encode($user->id)). '" title="Activate Your Account" style="text-decoration:none">' . $activeLinkText . '</a>';
            $paraKeyArr = array("###greetings###", "###siteName###", "###button###", "###userEmail###");
            $paraValArr = array(Str::ucfirst($data['name']),env('APP_NAME'), $activateLink,$data['email']);
            $html = Globalfunction::GetMailMessageBody($arrRows[0]['templates'], $paraKeyArr, $paraValArr);
            $details = [
                'html'=>$html,
                'subject'=>$arrRows[0]['subject'],                
            ];
            Notification::send($user, new RegisterUserNotification($details));

            // send mail to admin
            $arrRows = $paraKeyArr = $paraValArr = '';
            $arrRows = Globalfunction::convertSelectedRowInArray(EmailTemplate::where('emailKey', 'admin-user-register')->get()->toArray());
            $paraKeyArr = array("###SITENAME###", "###NAME###", "###PHONE###", "###EMAIL###");
            $paraValArr = array( env('APP_NAME'),$data['name'], $data['phone'], $data['email'] );
            $subject = $arrRows[0]['subject'];
            $html = Globalfunction::GetMailMessageBody($arrRows[0]['templates'], $paraKeyArr, $paraValArr);
            $details = [
                'html'=>$html,
                'subject'=>$subject,                
            ];
            Notification::route('mail', [env('ADMIN_EMAIL')=>'Admin',])->notify(new RegisterUserAdminNotification($details));
            
        endif;    

        
        session()->flash('success',__('Your account created successfully, Please check your email and activate your account'));
        
        return $user;
    }
}
