<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;    
use App\Models\Subscriber;

//for notifications
use App\Models\EmailTemplate;
use App\Library\Globalfunction;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MailAfter48HoursNotification;
use Carbon\Carbon;

class MailAfter48HoursCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailafter48hours:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail to user after 48 hours who is not subscribe';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        
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
                Notification::send($user, new MailAfter48HoursNotification($details));        
            endforeach;            
            echo 'Emails sent successfully';            
        else:            
            echo 'User not found';                        
        endif;
        
    }
}
