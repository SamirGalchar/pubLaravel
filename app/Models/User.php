<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Library\form_field\Fields;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_image',
        'isPaid',
        'isActive',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    
    /**
     * Methods for admin
    */
    public function getProfileHtml($id,$action){
        $info = User::where('id',$id)->first();

        $html = '';
        $html.= Fields::textBox(array("label"=>"Name:<span class='red'>*</span>","name"=>"name","value"=>$info['name'] ?? '',"action"=>$action));
        $html.= Fields::textBox(array("label"=>"Email:<span class='red'>*</span>","name"=>"email","class"=>"","value"=>$info['email'] ?? '',"action"=>$action,'inputType'=>'email'));

        return $html;
    }
    public function changePassHTML($action){
        // change password html
        $action=(!empty($action))?$action:'newRec';
        
        $html='';
        $html .= Fields::password(array("label"=>"Current Password:<span class='red'>*</span>","name"=>"oldPassword","value"=>'',"action"=>$action));
        $html .= Fields::password(array("label"=>"New Password:<span class='red'>*</span>","name"=>"newPassword","value"=>'',"action"=>$action));
        $html .= Fields::password(array("label"=>"Confirm Password:<span class='red'>*</span>","name"=>"confirmPassword","value"=>'',"action"=>$action));
        $html .= Fields::textBox(array("onlyField"=>true,"name"=>"action","value"=>base64_encode("changepass-save"),"action"=>$action,"inputType"=>"hidden"));
        return $html;
    }
    public function getPageHTMLForUserEdit($id,$action){
        $info = User::where('id',$id)->first();
        $html='';
        
        $html.= Fields::hidden(array("name"=>"user_id","class"=>"","value"=>$info['id'] ?? '',"action"=>$action,'onlyField'=>true));
        $html.= Fields::textBox(array("label"=>"Name:<span class='red'>*</span>","name"=>"name","class"=>"","value"=>$info['name'] ?? '',"action"=>$action));
        $html.= Fields::textBox(array("label"=>"Email:<span class='red'>*</span>","name"=>"email","class"=>"","value"=>$info['email'] ?? '',"action"=>$action,'inputType'=>'email'));
        $html.= Fields::textBox(array("label"=>"Phone:","name"=>"phone","class"=>"","value"=>$info['phone'] ?? '',"action"=>$action,'inputType'=>'text'));
        $html.= Fields::selectBox(array("label"=>"Status :<span class='red'>*</span>","name"=>"isActive",'id'=>'isActive',"textField"=>"Select Option","fromDB"=>false,"selectBoxParam"=>array("defaultText"=>"Select Status","selectedValue"=>(isset($info["isActive"]) && !empty($info["isActive"]))?$info["isActive"]:"", "isKeyArray"=>true,"arrVal"=>['pending'=>'Pending','active'=>'Active','inactive'=>'Deactive']),"action"=>$action,'class'=>'custom-select select2-demo'));
        
        return $html;
    }
    
}
