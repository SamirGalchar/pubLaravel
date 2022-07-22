<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Library\Projectfunction;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

use App\Http\Requests\FrontEnd\profileUpdateRequest;
use App\Http\Requests\FrontEnd\changePasswordRequest;

use App\Models\User;

class UserController extends Controller {

    function __construct() {
        $this->user = new User();
    }

    public function activateUser(Request $request){
        $userId = (!empty($request->user_id)) ? $request->user_id : $request->user_id;
        if(!empty($userId)):
            $userId = base64_decode($userId);
            $res = User::find($userId)->update(['isActive'=>'active','email_verified_at'=>Carbon::now()->toDateTimeString()]);
            if($res):
                return view('user-activate');
            endif;
        endif;
    }
    public function forgotPassword(Request $request){
        $email = (!empty($request->forGotEmail)) ? $request->forGotEmail : "";
        if(!empty($email)):
            User::where(['email'=>$email])->first();
            if(!empty($res)):
                echo "true";
            else:
                echo "false";
            endif;
        else:
            echo "false";
        endif;
        exit;
    }
    public function checkEmail(Request $request){
        $userEmail = $request->email;
        $userInfo["cnt"]=User::where(["email"=>$userEmail])->where('id', '!=', $request->id)->get()->count();
        if($userInfo["cnt"]>0){
            $resp=1;
        }else{
            $resp=0;
        }
        echo ($resp==1) ? "false" : "true";
        exit;
    }
    public function checkPhone(Request $request){
        $phone = $request->phone;
        $userInfo["cnt"]=User::where(["phone"=>$phone])->where('id', '!=', $request->id)->get()->count();
        if($userInfo["cnt"]>0){
            $resp=1;
        }else{
            $resp=0;
        }
        echo ($resp==1) ? "false" : "true";
        exit;
    }
   
     public function profile(Request $request){
        $user = User::find(Auth::user()->id)->toArray();    
        return view('user.profile', compact('user'));
    }
    
    public function profileUpdate(profileUpdateRequest $request){
        $userId =  !empty(Auth::user()->id) ? Auth::user()->id : "";
        if( !empty($userId) ):
            $formData = [
                "name"=>(!empty($request->name)) ? $request->name : "" ,                
                "email"=>(!empty($request->email)) ? $request->email : "" ,
                "phone"=>(!empty($request->phone)) ? $request->phone : "" ,                
            ];
            User::find($userId)->update($formData);
            session()->flash('success',__('Profile has been updated successfully.'));
            return redirect()->route('user.profile');
        endif;
    }    
    public function uploadUserProfilePic(Request $request){
        if(Auth::check()):            
            $userId = !empty(Auth::user()->id) ? Auth::user()->id : "";
            $path = public_path('uploads/user-profile/');
            $user_image = $request->user_image_old;
            $res = false;
            
            if(!empty($request->file('user_image'))):
                $file = $request->file('user_image');
                $user_image = (int) round(now()->format('Uu') / pow(10, 6 - 3)).'-'.$file->getClientOriginalName().'.'.$file->extension();
                $file->move($path,$user_image);
                if(!empty($request->user_image_old) && file_exists($path.$request->user_image_old)):
                    unlink($path.$request->user_image_old);                    
                endif;
                $res = User::find($userId)->update(['userImage'=>$user_image]);
            endif;
            
            if($res):
                return response()->json(['status' => 1, 'msg' => 'Your profile picture has been updated successfully','name'=>$user_image]);
            else:
                return response()->json(['status' => 0, 'msg' => 'Something went wrong, updating picture in db failed.']);
            endif;            
        endif;
        
    }
    
    public function changePassword(Request $request){
        return view('user.change-password');
    }
    public function checkOldPassword(Request $request){
        if(Hash::check($request->old_password, Auth::user()->password)):
            $userInfo=User::where('id', Auth::user()->id)->get()->toArray();
            if(count($userInfo) > 0):
                echo "true";
            else:
                echo "false";
            endif;            
        else:
            echo "false";
        endif;
        exit;
    }
    protected function updatePassword(changePasswordRequest $request){
        
        if(Auth::check()):
        
            $formData = ['password'=>Hash::make($request->password)];
            $res = User::find(Auth::user()->id)->update($formData);

            if($res):
                session()->flash('success',__('Password changed successfully.'));
                return redirect()->route('user.profile');
            else:
                session()->flash('error',__('Password not changed, please try again'));
                return redirect()->route('user.profile');
            endif;    
            
        endif;
        
    }
    
}
