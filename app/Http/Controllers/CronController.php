<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;    
use App\Models\Subscriber;

//for notifications
use App\Models\EmailTemplate;
use App\Library\Globalfunction;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MailAfter48HoursNotification;
use Carbon\Carbon;

class CronController extends Controller {
    
    public function sendSubsctiptionMailAfter48Hours(Request $request){
        
        $users = User::where('isPaid','No')->whereDate('created_at', '=', Carbon::now()->subDays(3))->get();  
        if($users):            
            foreach($users as $user):   
                //email
                $arrRows = $paraKeyArr = $paraValArr = '';
                $arrRows = Globalfunction::convertSelectedRowInArray(EmailTemplate::where('emailKey', 'subscription-mail-after-48-hours')->get()->toArray());
                $paraKeyArr = array("###greetings###", "###SITENAME###", "###name###");
                $paraValArr = array($user->name, env('APP_NAME'),$user->name);
                $subject = $arrRows[0]['subject'];
                $html = Globalfunction::GetMailMessageBody($arrRows[0]['templates'], $paraKeyArr, $paraValArr);
                $details = [
                    'html'=>$html,
                    'subject'=>$subject,                
                ];
                //Notification::send($user, new MailAfter48HoursNotification($details));      
                Notification::route('mail', ['web91.siddharth@gmail.com'=>'Admin',])->notify(new MailAfter48HoursNotification($details));
            endforeach;            
            echo 'Emails sent successfully';            
        else:            
            echo 'User not found';            
        endif;
        
        
    }
    
    
}
