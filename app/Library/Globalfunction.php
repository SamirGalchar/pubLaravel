<?php

namespace App\Library;

use DB;
use Illuminate\Http\Request;

use Image;
use Illuminate\Support\Facades\Input;
//use PHPMailer\PHPMailer\PHPMailer;

	class Globalfunction {
		public static $siteConfig=array();
		public static $smtpConfig=array();
		public static $socialConfig=array();

		public static $paramConfig;
		public static $paramFrom;

		function __construct($isInitialLoad = false){
			if($isInitialLoad){
				//$this->getSiteConfig();
			}
			//$this->setCurrency();
		}
        public static function convertSelectedRowInArray($arrRecordSet){
			$arrRecordSet = collect($arrRecordSet)->map(function($x){ return (array) $x; })->toArray();
            return $arrRecordSet;
		}
		public static function get_geolocation($ip) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$ip_data_in = curl_exec($ch); // string
			curl_close($ch);

			$ip_data = json_decode($ip_data_in,true);
			$ip_data = str_replace('&quot;', '"', $ip_data); // for PHP 5.2 see stackoverflow.com/questions/3110487/

			if($ip_data && $ip_data['geoplugin_countryName'] != null) {
				$country = $ip_data['geoplugin_countryName'];
			}
			//print_r($ip_data);
			return $ip_data;
		}
		public static function setCurrency(){
			$session_currency = session()->get('current_currency');

			if(isset($session_currency) && !empty($session_currency)){
				//$this->_ci->session->set_userdata('current_currency',$session_currency);
				//session()->put('current_currency', $session_currency);
			}
			else{
				$ip=Request::ip();
				$geoLocation = self::get_geolocation($ip);
				//$geoLocation=$this->get_geolocation('50.93.204.64');
				//$geoLocation=$this->get_geolocation('110.33.122.75');
				$currency = $geoLocation['geoplugin_currencyCode'];
				$currency = ($currency=="AUD")?"AUD":"USD";
				session()->put('current_currency', $currency);
                session()->save();
			}
		}
		public static function adminLoginCheck($flag=false){
		    $admin_user = session()->get('admin_user');
			if(!($admin_user)){
                if($flag==true) {
                    return false;
                } else {
                    return redirect(SITE_ADM."user/login/")->send();
                }
			}
			else if($flag==true) {
				return true;
			}
		}
		public static function frontLoginCheck($flag=false){
			$cust_id = session()->get('cust_id');
			if(!($cust_id)){
				return ($flag == true ? false : redirect(SITE_URL)->send());
			}
			else if($flag==true) {
				return true;
			}
		}
		public static function messageNotification(){
			$session=session()->get("RES");
			if(isset($session) && is_array($session)){
				?>
				<div class="alert alert-<?php echo $session["cls"];?> text-<?php echo $session["cls"];?> w-100 mb-3">
					<button class="close" data-dismiss="alert" type="button">×</button>
					<strong>
						<?php
							echo $session["msg"];
							session()->forget('RES');
						?>
					</strong>
				</div>
				<?php
			}
		}
		public static function get_contentFilter($arr,$filterParam=''){
			$arrFilterPara=(isset($filterParam['filterKey']) && !empty($filterParam['filterKey']))?$filterParam['filterKey']:"stripslashes";
			$fieldSkipStrip=(isset($filterParam['skipField']) && !empty($filterParam['skipField']))?$filterParam['skipField']:NULL;

			$rtArray=array();
			$arr=(array)$arr;

			if(!isset($arrFilterPara) || empty($arrFilterPara)){
				$arrFilterPara=array("stripslashes");
			}
			else{
				$arrFilterPara=explode(",",$arrFilterPara);
			}

			if(!is_array($fieldSkipStrip) && !empty($fieldSkipStrip)){
				$fieldSkipStrip=explode(",",$fieldSkipStrip);
			}

			if(is_array($arr) && count($arr)>0){
				foreach($arr as $k=>$v){
					if((!empty($fieldSkipStrip)) && (is_array($fieldSkipStrip)) && (in_array($k,$fieldSkipStrip))){
						$rtArray[$k]=($v);
					}
					else{
						foreach($arrFilterPara as $Val){
							if($Val=="stripslashes"){
								$rtArray[$k]=stripslashes($v);
							}
							else if($Val=="empty"){
								if(trim($v)!=""){ $rtArray[$k]=($v);}
							}else if($Val=="striptags"){
								$rtArray[$k]=strip_tags($v);
							}else if($Val=="trim"){
								$rtArray[$k]=trim($v);
							}else if($Val=="none"){
								$rtArray[$k]=($v);
							}
						}
					}
				}
			}
			else{
				$rtArray=$arr;
			}
			return $rtArray;
		}
		public static function set_contentFilter($arr,$filterParam=''){
			$arrFilterPara=(isset($filterParam['filterKey']) && !empty($filterParam['filterKey']))?$filterParam['filterKey']:"trim";
			$fieldSkipStrip=(isset($filterParam['skipField']) && !empty($filterParam['skipField']))?$filterParam['skipField']:NULL;

			$rtArray=array();
			$arr=(array)$arr;

			if(!isset($arrFilterPara) || empty($arrFilterPara)){
				$arrFilterPara=array('trim');
			}
			else{
				$arrFilterPara=explode(",",$arrFilterPara);
			}

			if(!is_array($fieldSkipStrip) && !empty($fieldSkipStrip)){
				$fieldSkipStrip=explode(",",$fieldSkipStrip);
			}

			if(is_array($arr) && count($arr)>0){
				foreach($arr as $k=>$v){
					if(is_array($v) && count($v)>0){
						$rtArray[$k] = self::set_contentFilter($v,$filterParam);
					}
					else{
						if((!empty($fieldSkipStrip)) && (is_array($fieldSkipStrip)) && (in_array($k,$fieldSkipStrip))){
							$rtArray[$k]=($v);
						}
						else{
							foreach($arrFilterPara as $Val){
								if($Val=="addslashes"){
									$rtArray[$k]=addslashes($v);
								}
								else if($Val=="trim"){
									$rtArray[$k]=trim($v);
								}else if($Val=="none"){
									$rtArray[$k]=($v);
								}
							}
						}
					}
				}
			}
			else{
				$rtArray=$arr;
			}
			return $rtArray;

		}
		public static function LogOff() {
			session()->flush();
		}
		public static function formatOnlyDate($org_dt, $src_dt_sep ,$src_dt_format, $dt_format="d/m/Y", $isPrint=false){
			if($org_dt!="" && $org_dt!="0000-00-00"){
				$dt=explode($src_dt_sep,$org_dt);
				if($src_dt_format=="dmy"){
					$rtDate=date($dt_format, mktime(0, 0, 0, $dt[1], $dt[0], $dt[2]));
				}else if($src_dt_format=="mdy"){
					$rtDate=date($dt_format, mktime(0, 0, 0, $dt[0], $dt[1], $dt[2]));
				}else if($src_dt_format=="ymd"){
					$rtDate=date($dt_format, mktime(0, 0, 0, $dt[1], $dt[2], $dt[0]));
				}
			}else{
				$rtDate="";
			}
			if($isPrint == true)
				print $rtDate;
			else
				return $rtDate;
		}
		public static function formatDateTime($org_dt, $src_dt_sep ,$src_dt_format, $dt_format="d/m/Y H:i:s", $isPrint=false){
			if($org_dt!="" && $org_dt!="0000-00-00" && $org_dt!="0000-00-00 00:00:00"){
				$date=explode(" ",$org_dt);
				$dt=explode($src_dt_sep,$date[0]);
				$tm=explode(':',$date[1]);
				if(!isset($tm[2])){
					$tm[2]=0;
				}
				if($src_dt_format=="dmy"){
					$rtDate=date($dt_format, mktime($tm[0],$tm[1],$tm[2],$dt[1], $dt[0], $dt[2]));
				}
				else if($src_dt_format=="mdy"){
					$rtDate=date($dt_format, mktime($tm[0],$tm[1],$tm[2], $dt[0], $dt[1], $dt[2]));
				}
				else if($src_dt_format=="ymd"){
					$rtDate=date($dt_format, mktime($tm[0],$tm[1],$tm[2], $dt[1], $dt[2], $dt[0]));
				}
			}
			else{
				$rtDate="";
			}
			if($isPrint == true)
				print $rtDate;
			else
				return $rtDate;
		}
		public static function generateSelectBox($tableName,$valueField,$textField,$defaultText,$selectedValue=NULL,$where='',$groupBy=''){
			$content='';
			if($defaultText!=NULL){
				$content='<option value="">'.$defaultText.'</option>';
			}
            //$qry = "select ".$text["selectBoxParam"]["textField"]." from ".$text["selectBoxParam"]["tableName"]." where ".$text["selectBoxParam"]["valueField"]." = '".$text["selectBoxParam"]["selectedValue"]."'";
            //$text['value']=global_function()->convertSelectedRowInArray(DB::select(DB::RAW($qry)));

			$db = DB::table($tableName)->select($valueField, $textField);
			if(!empty($where)){
				$db = $db->whereRaw($where);
			}
			if(!empty($groupBy)){
				$db = $db->groupBy($groupBy);
			}
			$db = $db->orderBy($textField,'ASC');
			$arrRecordSet = $db->get()->toArray();
			$arrRecordSet = collect($arrRecordSet)->map(function($x){ return (array) $x; })->toArray();

			for($i=0;$i<count($arrRecordSet);$i++){
				$selected="";
				$tmpValueField = explode(".",$valueField);
				$tmpValueField = end($tmpValueField);
                $curVal=$arrRecordSet[$i][$tmpValueField];

				if(is_array($selectedValue)){
					if($selectedValue!=NULL && in_array($curVal,$selectedValue)){
						$selected=" selected=\"selected\"";
					}
				}
				else{
					if($curVal==$selectedValue && $selectedValue!=NULL){
						$selected=" selected=\"selected\"";
					}
				}

				$tmpTextField = explode(".",$textField);
				$tmpTextField = end($tmpTextField);
				$content.='<option value="'.$curVal.'"'.$selected.'>'.$arrRecordSet[$i][$tmpTextField].'</option>';
			}
			return $content;

		}
		public static function generateArraySelectBox($arrVal,$defaultText,$selectedValue=NULL,$isKeyArray=false){
			$content='';
			if($defaultText!=NULL){
				$content='<option value="">'.$defaultText.'</option>';
			}
			foreach($arrVal as $k=>$v){
				$selected="";
				$curVal=$v;
				if(is_array($selectedValue)){
					if((in_array($curVal,$selectedValue) && $selectedValue!=NULL && $isKeyArray==false) || (in_array($k,$selectedValue) && $selectedValue!=NULL && $isKeyArray==true)){
						$selected=" selected=\"selected\"";
					}
				}
				else{
					if(($curVal==$selectedValue && $selectedValue!=NULL && $isKeyArray==false) || ($k==$selectedValue && $selectedValue!=NULL && $isKeyArray==true)){
						$selected=" selected=\"selected\"";
					}
				}
				$content.='<option value="'.(($isKeyArray)?$k:$v).'"'.$selected.'>'.$v.'</option>';
			}
			return $content;
		}
		public static function getExtension($str) {
			$ext = pathinfo($str, PATHINFO_EXTENSION);
			return $ext;
		}
		public static function get_ip_address() {
			return Request::ip();
		}
		public static function limitedContent($str,$limit=100,$end = '...'){
			return str_limit($str, $limit, $end);
		}
		public static function getSiteConfig(){
		    $metaInfo = self::convertSelectedRowInArray(DB::table('config_meta')->where('ID',1)->get()->toArray());
			$tmpMetaCOnfig=array("metaConfig"=>$metaInfo[0]);

			self::$siteConfig['META_DESC'] = $tmpMetaCOnfig['metaConfig']['description'];
            self::$siteConfig['META_TITLE']=$tmpMetaCOnfig['metaConfig']['title'];
            self::$siteConfig['META_KEYWORD']=$tmpMetaCOnfig['metaConfig']['keyword'];
            self::$siteConfig['META_AUTHOR']=$tmpMetaCOnfig['metaConfig']['author'];

			$generalInfo= self::convertSelectedRowInArray(DB::table('config_genral')->where('ID',1)->get()->toArray());
			$tmpGeneralCOnfig=array("generalConfig"=>$generalInfo[0]);
			self::$siteConfig['SITE_DISPLAY_NAME']=$tmpGeneralCOnfig['generalConfig']['siteDisplayName'];
			self::$siteConfig['SKIN']=$tmpGeneralCOnfig['generalConfig']['skin'];
            self::$siteConfig['FRONT_LOGO_IMAGE']=$tmpGeneralCOnfig['generalConfig']['frontLogo'];
            self::$siteConfig['FRONT_LOGO_NAME']=$tmpGeneralCOnfig['generalConfig']['frontLogoName'];
            self::$siteConfig['FRONT_LOGO_TYPE']=$tmpGeneralCOnfig['generalConfig']['frontLogoType'];
            self::$siteConfig['ADMIN_LOGO_IMAGE']=$tmpGeneralCOnfig['generalConfig']['adminLogo'];
            self::$siteConfig['ADMIN_LOGO_NAME']=$tmpGeneralCOnfig['generalConfig']['adminLogoName'];
            self::$siteConfig['ADMIN_LOGO_TYPE']=$tmpGeneralCOnfig['generalConfig']['adminLogoType'];
            //$this->siteConfig['FAVICON']=$tmpGeneralCOnfig['generalConfig']['favicon'];

			$smtpInfo = self::convertSelectedRowInArray(DB::table('config_email')->where('ID',1)->get()->toArray());
			$tmpEmailConfig=array("emailConfig"=>$smtpInfo[0]);
            self::$siteConfig['ADM_EMAIL']=$tmpEmailConfig['emailConfig']['adminEmail'];
            self::$smtpConfig['FRM_EMAIL']=$tmpEmailConfig['emailConfig']['smtpEmail'];
            self::$smtpConfig['FRM_NM']='Admin - '.$tmpEmailConfig['emailConfig']['fromName'];

            self::$smtpConfig['EMAIL']=array(
                                            'protocol' => 'smtp',
                                            'smtp_host' =>$tmpEmailConfig['emailConfig']['smtpHost'],
                                            'smtp_port' =>$tmpEmailConfig['emailConfig']['smtpPort'],
                                            'smtp_user' =>$tmpEmailConfig['emailConfig']['smtpEmail'],
                                            'smtp_pass' =>$tmpEmailConfig['emailConfig']['smtpPass'],
                                            'mailtype'  => 'html',
                                            'charset'=>'utf-8',
                                            'validate'=>TRUE,
                                            'crlf'=>'\r\n',
                                            'newline'=>'\r\n',
                                            'protocol' => 'smtp'
                                        );


			$socialInfo=self::convertSelectedRowInArray(DB::table('config_social')->where('ID',1)->get()->toArray());
			$tmpFacebookCOnfig=array("facebook"=>unserialize($socialInfo[0]['facebook']));
			/*echo "<pre>";
			print_r($tmpFacebookCOnfig);
			exit;*/
			$tmpGoogleCOnfig=array("google"=>unserialize($socialInfo[0]['google']));
			$tmpTwitterCOnfig=array("twitter"=>unserialize($socialInfo[0]['twitter']));
			$tmpLinkedinCOnfig=array("linkedin"=>unserialize($socialInfo[0]['linkedin']));

            config()->set('services.facebook.client_id',$tmpFacebookCOnfig['facebook']['appId']);
            config()->set('services.facebook.client_secret',$tmpFacebookCOnfig['facebook']['appSecretKey']);
            //config()->set('services.facebook.redirect',$tmpFacebookCOnfig['facebook']['redirectUrl']);

            config()->set('services.google.client_id',$tmpGoogleCOnfig['google']['clientId']);
            config()->set('services.google.client_secret',$tmpGoogleCOnfig['google']['clientSecret']);
            config()->set('services.google.redirect',$tmpGoogleCOnfig['google']['redirectUrl']);

            config()->set('services.twitter.client_id',$tmpTwitterCOnfig['twitter']['apiKey']);
            config()->set('services.twitter.client_secret',$tmpTwitterCOnfig['twitter']['apiSecret']);
            config()->set('services.twitter.redirect',$tmpTwitterCOnfig['twitter']['callbackUrl']);

            config()->set('services.linkedin.client_id',$tmpLinkedinCOnfig['linkedin']['apiKey']);
            config()->set('services.linkedin.client_secret',$tmpLinkedinCOnfig['linkedin']['apiSecret']);
            config()->set('services.linkedin.redirect',$tmpLinkedinCOnfig['linkedin']['callbackUrl']);

			config(['custom.siteConfig.siteGeneralConfig' => self::$siteConfig]);
			config(['custom.siteConfig.smtpConfig' => self::$smtpConfig]);
			config(['custom.siteConfig.socialConfig' => self::$socialConfig]);
		}
		//Mail Start
        public static function getMasterEmailTemplate(){
				$templateContent=self::convertSelectedRowInArray(DB::table('email_templates')->select('templates')->where('emailKey','master_template')->get()->toArray());
                //dd($templateContent);
				//$siteLink='<a href="'.SITE_URL.'" title="'.SITE_URL.'">'.config('custom.siteConfig.SITE_DISPLAY_NAME').'</a>';
                //$paraKeyArr=array("@SITE_IMG@");
                //$paraValArr=array(SITE_IMG);

                return $templateContent[0]['templates'];//return $mailData=str_replace($paraKeyArr,$paraValArr,$templateContent[0]['templates']);
            }
        public static function GetMailMessageBody($msg, $paraKeyArr, $paraValArr){
                $mailData=str_replace($paraKeyArr,$paraValArr,$msg);
                $masterEmail=self::getMasterEmailTemplate();

                $find=array("{{%msgBody%}}");
                $replace=array($mailData);
                return $mailData=str_replace($find,$replace,$masterEmail);
            }
        public static function initMailer(){
                //require_once(DIR_FS.'app/Library/phpMailer_6.0.5/vendor/autoload.php');
                self::$smtpConfig=config('custom.siteConfig.smtpConfig');
                $host=explode("ssl://",self::$smtpConfig['EMAIL']['smtp_host']);
                $smtpSecure=(isset($host) && !empty($host) && count($host)>=2)?"ssl":"tls";
                $smtpHost=(isset($host) && !empty($host) && count($host)>=2)?$host[1]:$host[0];

                $paramConfig=array("host"=>$smtpHost,"host_port"=>self::$smtpConfig['EMAIL']['smtp_port'],"smtp_user"=>self::$smtpConfig['EMAIL']['smtp_user'],"smtp_pass"=>self::$smtpConfig['EMAIL']['smtp_pass'],"isDisplay"=>false,"debugMode"=>0,"SMTPSecure"=>$smtpSecure,"mailLanguage"=>'en');
				config(['custom.siteConfig.paramConfig' => $paramConfig]);
                self::$paramFrom=array("email"=>self::$smtpConfig['FRM_EMAIL'],"name"=>self::$smtpConfig['FRM_NM']);
            }
        /*public function sendMail($paramTo, $paramFrom, $subject, $mailData,$attachment=array()) {
                //$mailData = utf8_decode($mailData);
                $subject = utf8_decode($subject);

                //Create a new PHPMailer instance
				//$mail = new PHPMailer(true);
				//global_function()->getSiteConfig();

				try {
					//Tell PHPMailer to use SMTP
					//$mail->isMail();
					//$mail->isSMTP();

					$mail->CharSet = 'UTF-8';

					//Enable SMTP debugging
					// 0 = off (for production use)
					// 1 = client messages
					// 2 = client and server messages
					$mail->SMTPDebug = config('custom.siteConfig.paramConfig.debugMode');

					//Ask for HTML-friendly debug output
					$mail->Debugoutput = 'html';

					//Set the hostname of the mail server
					$mail->Host = config('custom.siteConfig.paramConfig.host');


					//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
					$mail->Port = config('custom.siteConfig.paramConfig.host_port');

					if (!empty(config('custom.siteConfig.paramConfig.SMTPSecure'))) {
						//Set the encryption system to use - ssl (deprecated) or tls
						$mail->SMTPSecure = config('custom.siteConfig.paramConfig.SMTPSecure');
					}
					//Whether to use SMTP authentication
					$mail->SMTPAuth = true;

					//Username to use for SMTP authentication - use full email address for gmail
					$mail->Username = config('custom.siteConfig.paramConfig.smtp_user');

					//Password to use for SMTP authentication
					$mail->Password = config('custom.siteConfig.paramConfig.smtp_pass');

					//Set who the message is to be sent from
					$mail->setFrom($paramFrom["email"], $paramFrom["name"]);

					//Set an alternative reply-to address
					$mail->addReplyTo($paramFrom["email"], $paramFrom["name"]);

					//Set who the message is to be sent to
					$mail->addAddress($paramTo["email"], $paramTo["name"]);

					//Set the subject line
					$mail->Subject = $subject;

                    $mail->isHTML(true);

					if (config('custom.siteConfig.paramConfig.mailLanguage') != 'en') {
						$mail->setLanguage(config('custom.siteConfig.paramConfig.mailLanguage'));
					}
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
					$mail->msgHTML($mailData);

					//Attach an image file

					if (count($attachment) > 0) {
						foreach ($attachment as $val) {
							$mail->addAttachment($val);
						}
					}

                    /*echo "<pre>";
                    print_r($mail);
                    exit;
					//send the message, check for errors
					if (!$mail->send()) {
						if ($this->paramConfig["isDisplay"]) {
							echo "Mailer Error: " . $mail->ErrorInfo;
						}
						return false;
					} else {
						if ($this->paramConfig["isDisplay"]) {
							echo "Message sent!";
						}
						return true;
					}
				}
				catch (Exception $e) {
                    if ($this->paramConfig["isDisplay"]) {
                        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                    }
                    return false;
				}
            }*/
       /* public function sendMailCC($paramTo, $paramFrom, $subject, $mailData,$attachment=array()) {
                //$mailData = utf8_decode($mailData);
                $subject = utf8_decode($subject);

                //Create a new PHPMailer instance
                $mail = new PHPMailer(true);
                try {
                    //Tell PHPMailer to use SMTP
                    //$mail->isMail();
                    //$mail->isSMTP();

                    $mail->CharSet = 'UTF-8';

                    //Enable SMTP debugging
                    // 0 = off (for production use)
                    // 1 = client messages
                    // 2 = client and server messages
                    $mail->SMTPDebug = config('custom.siteConfig.paramConfig.debugMode');

                    //Ask for HTML-friendly debug output
                    $mail->Debugoutput = 'html';

                    //Set the hostname of the mail server
                    $mail->Host = config('custom.siteConfig.paramConfig.host');

                    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
                    $mail->Port = config('custom.siteConfig.paramConfig.host_port');

                    if (!empty(config('custom.siteConfig.paramConfig.SMTPSecure'))) {
                        //Set the encryption system to use - ssl (deprecated) or tls
                        $mail->SMTPSecure = config('custom.siteConfig.paramConfig.SMTPSecure');
                    }
                    //Whether to use SMTP authentication
                    $mail->SMTPAuth = true;

                    //Username to use for SMTP authentication - use full email address for gmail
                    $mail->Username = config('custom.siteConfig.paramConfig.smtp_user');

                    //Password to use for SMTP authentication
                    $mail->Password = config('custom.siteConfig.paramConfig.smtp_pass');

                    //Set who the message is to be sent from
                    $mail->setFrom($paramFrom["email"], $paramFrom["name"]);

                    //Set an alternative reply-to address
                    $mail->addReplyTo($paramFrom["email"], $paramFrom["name"]);

                    //Set who the message is to be sent to
                    $mail->addAddress($paramTo[0]["email"], $paramTo[0]["name"]);

                    //Set CC who will receive this message
                    if(count($paramTo)>0){
                        for($mail_i=1;$mail_i<count($paramTo);$mail_i++){
                            $mail->addCC($paramTo[$mail_i]["email"], $paramTo[$mail_i]["name"]);
                        }
                    }

                    //Set the subject line
                    $mail->Subject = $subject;

                    if (config('custom.siteConfig.paramConfig.mailLanguage') != 'en') {
                        $mail->setLanguage(config('custom.siteConfig.paramConfig.mailLanguage'));
                    }

                    //Read an HTML message body from an external file, convert referenced images to embedded,
                    //convert HTML into a basic plain-text alternative body
                    $mail->msgHTML($mailData);


                    //Attach an image file
                    if (count($attachment) > 0) {
                        foreach ($attachment as $val) {
                            $mail->addAttachment($val);
                        }
                    }


                    //send the message, check for errors
                    if (!$mail->send()) {
                        if (config('custom.siteConfig.paramConfig.isDisplay')) {
                            echo "Mailer Error: " . $mail->ErrorInfo;
                        }
                        return false;
                    } else {
                        if (config('custom.siteConfig.paramConfig.isDisplay')) {
                            echo "Message sent!";
                        }
                        return true;
                    }
                }
                catch (Exception $e) {
					if (config('custom.siteConfig.paramConfig.isDisplay')) {
						echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
					}
					return false;
                }
            }

        public function sendMailMaildrill($paramTo, $paramFrom, $subject, $mailData) {
                require_once(DIR_LIB."MailChimp/Mandrill.php");

                $mandrill = new Mandrill('YYZjZEGMdOKqou0noKuYQQ');
                $message = array(
                    'html' => $mailData,
                    'subject' => $subject,
                    'from_email' => $paramFrom["email"],
                    'from_name' => $paramFrom["name"],
                    'to' => array(
                        array(
                            'email' => $paramTo["email"],
                            'name' =>  '',
                            'type' => 'to'
                        )
                    ),
                    'headers' => array(),
                    'important' => false,
                    'track_opens' => null,
                    'track_clicks' => null,
                    'auto_text' => null,
                    'auto_html' => null,
                    'inline_css' => null,
                    'url_strip_qs' => null,
                    'preserve_recipients' => null,
                    'view_content_link' => null,
                    'bcc_address' => '',
                    'tracking_domain' => null,
                    'signing_domain' => null,
                    'return_path_domain' => null,
                    'tags' => SITE_DISP_NAME,
                    'subaccount' => null,
                    'google_analytics_domains' => array('example.com'),
                    'google_analytics_campaign' => 'message.from_email@example.com',
                    'metadata' => array('website' => SITE_URL),
                    'recipient_metadata' => array(
                        array(
                            'rcpt' => 'recipient.email@example.com',
                            'values' => array('user_id' => 123456)
                        )
                    )
                );
                $async = false;
                $ip_pool = 'Main Pool';
                $send_at = "";
                $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);

                //send the message, check for errors mail($paramTo["email"],$subject,$mailData,$headers)
                if ($result[0]['reject_reason']!='') {
                    if($this->paramConfig["isDisplay"]){
                        //error_reporting(E_ALL ^ E_NOTICE);
                        echo "Mailer Error: ";
                    }

                    return false;
                }
                else {
                    if($this->paramConfig["isDisplay"]){
                        echo "Message sent!";
                    }
                    return true;
                }

            }
        public function simpleMail($mail_to,$mail_subject,$mail_message,$headers){
                $encoding = "iso-8859-1";

                // Preferences for Subject field
                $subject_preferences = array(
                    "input-charset" => $encoding,
                    "output-charset" => $encoding,
                    "line-length" => 76,
                    "line-break-chars" => "\r\n"
                );

                // Mail header
                $header = "Content-type: text/html; charset=".$encoding." \r\n";
                $header .= "From: ".$headers['name']." <".$headers['email']."> \r\n";
                $header .= "MIME-Version: 1.0 \r\n";
                $header .= "Content-Transfer-Encoding: 8bit \r\n";
                $header .= "Date: ".date("r (T)")." \r\n";
                $header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

                // Send mail
                $resp = mail($mail_to, $mail_subject, $mail_message, $header);
                print_r($resp);
                exit;
            }*/
		//Mail End
		public static function getCurrentMenu(){
			$url = request()->segment(2);
			$page='home';
			if(isset($url) && !empty($url)){
				if($url=="country" || $url=="subcategory" || $url=="category"){
					$page='site_module';
				}
				else if($url=="config"){
					$page='system';
				}
				else{
					$page=$url;
				}
			}
			return $page;
		}
		public static function getCaptchaHTML(){
			$error=null;
			$publickey = RECAPTCHA_PUBLIC;
			//$recaptcha=recaptcha_get_html($publickey, $error);
			$recaptcha='<div id="responsive_recaptcha" style="display:none">
									<div id="recaptcha_image"></div>
									<div class="recaptcha_only_if_incorrect_sol" style="color:#FF0000;">Incorrect please try again</div>
									<label class="solution"> <span class="recaptcha_only_if_image">Type the two words:</span> <span class="recaptcha_only_if_audio">Enter the numbers you hear:</span>
										<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
									</label>
									<div class="options">
										<a href="javascript:Recaptcha.reload()" id="icon-reload"><i class="icon-play-1"></i></a>
										<a class="recaptcha_only_if_image" href="javascript:Recaptcha.switch_type(\'audio\')" id="icon-audio"><i class="icon-headphones"></i></a>
										<a class="recaptcha_only_if_audio" href="javascript:Recaptcha.switch_type(\'image\')" id="icon-image"><i class="icon-keyboard-1"></i></a>
										<a href="javascript:Recaptcha.showhelp()" id="icon-help"><i class="icon-help-circled-1"></i></a>
									</div>
								</div>
								<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k='.$publickey.'"></script>
								<noscript>
									<iframe src="http://www.google.com/recaptcha/api/noscript?k='.$publickey.'" height="300" width="500" frameborder="0"></iframe>
									<br>
									<textarea name="recaptcha_challenge_field" rows="3" cols="40">
									</textarea>
									<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
								</noscript>';
			return $recaptcha;
		}
		public static function convertIntoSlug($str, $replace=array(), $delimiter='-') {
			setlocale(LC_ALL, 'en_US.UTF8');
			if ( !empty($replace) ) {
				$str = str_replace((array)$replace, ' ', $str);
			}
			$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
			$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
			$clean = strtolower(trim($clean, '-'));
			$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
			return $clean;
		}
        public static function fileUpload($fileControlName, $destinationPath=DIR_UPD, $otherParam=array()){
			$request = request();

			$removeChrFromFileName = (isset($otherParam['removeChrFromFileName']) && !empty($otherParam['removeChrFromFileName'])) ?  $otherParam['removeChrFromFileName'] : array(' ', '[', ']', '_');
			$allowedFileExtension = (isset($otherParam['allowedFileExtension']) && !empty($otherParam['allowedFileExtension'])) ?  $otherParam['allowedFileExtension'] : '*';
			$isMultiple = (isset($otherParam['isMultiple']) && !empty($otherParam['isMultiple'])) ?  $otherParam['isMultiple'] : false;
			$allowSize = (isset($otherParam['allowSize']) && !empty($otherParam['allowSize'])) ?  $otherParam['allowSize'] : (5*1024); // default is 5MB

			$uploadedFiles = $errorMsg = array();

			try {
				if ($request->hasFile($fileControlName)) {
					if($isMultiple){
						foreach ($request->file($fileControlName) as $file) {
							$fileSize = ($file->getSize())/1024;        // return size in byte. so now it will store in KB
							$filename = $file->getClientOriginalName();
							$extension = $file->getClientOriginalExtension();
							if($fileSize < $allowSize){
								if ($allowedFileExtension=='*' || in_array($extension, $allowedFileExtension)) {
                                    $tmpFname = time() . '_@@_' . str_replace($removeChrFromFileName, "_", $filename);
                                    if ($file->move($destinationPath, $tmpFname)) {
                                        $uploadedFiles[] = $tmpFname;
                                    }
                                } else {
                                    $errorMsg [] = $filename . ' should have ' . implode(', ', $allowedFileExtension) . ' extension.';
                                }
                            }
                            else {
                                $errorMsg [] = $filename . ' should have less then ' . $allowSize . 'KB.';
                            }
						}
					}
					else{
						$file = $request->file($fileControlName);
						$fileSize = ($file->getSize())/1024;        // return size in byte. so now it will store in KB
						$filename = $file->getClientOriginalName();
						$extension = $file->getClientOriginalExtension();
						if($fileSize < $allowSize){
							if ($allowedFileExtension=='*' || in_array($extension, $allowedFileExtension)) {
								$tmpFname = time() . '_@@_' . str_replace($removeChrFromFileName, "_", $filename);

								if ($file->move($destinationPath, $tmpFname)) {
									$uploadedFiles = $tmpFname;
								}
							}
							else {
								$errorMsg [] = $filename . ' should have ' . implode(', ', $allowedFileExtension) . ' extension.';
							}
                        }
						else {
							$errorMsg [] = $filename . ' should have less then ' . $allowSize . 'KB.';
						}

					}
				}
				else {
					$errorMsg [] = 'file is required to upload';
				}
			}
			catch (\Exception $e) {
				$errorMsg [] = $e->getMessage();
			}
			if(count($errorMsg)>0){
			    foreach($uploadedFiles as $delFile){
					@unlink($destinationPath .''.$delFile);
				}
				return array('error'=>$errorMsg,'fileName'=>'');
			}
			else{
				return array('error'=>'','fileName'=>$uploadedFiles);
            }
        }
        public static function imageUpload($fileControlName, $destinationPath=DIR_UPD, $otherParam=array()){

            $otherParam['canvasSize']=(isset($otherParam['canvasSize']) && !empty($otherParam['canvasSize']))?$otherParam['canvasSize']:array(500,500);
            $otherParam['imageSize']=(isset($otherParam['imageSize']) && !empty($otherParam['imageSize']))?$otherParam['imageSize']:array(300,300);

            $image = Input::file($fileControlName);
            $newImgName = time().'.'.$image->getClientOriginalExtension();

            $background = Image::canvas($otherParam['canvasSize'][0], $otherParam['canvasSize'][1]);

            $img = Image::make($image->getRealPath());
            $img->resize($otherParam['imageSize'][0], $otherParam['imageSize'][1], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $background->insert($img, 'center');
            $background->save($destinationPath.'///'.$newImgName);


            if(file_exists($destinationPath.'/'.$newImgName)){
                $file=array("imgNewName"=>$newImgName,"imgResponse"=>'');
            }
            else {
                $file=array("imgNewName"=>'',"imgResponse"=>'error : Image not uploaded successfully');
            }
            return $file;
        }
		public static function getAdminFormHTML($pageHeading,$pageHtml,$formParam){
			$cancelUrl = (isset($formParam['cancelUrl']) && $formParam['cancelUrl']!='') ? $formParam['cancelUrl'] : route('admin.dashboard');
			$cancelValue = (isset($formParam['cancelValue']) && $formParam['cancelValue']!='') ? $formParam['cancelValue'] : 'Cancel';

			$sbtName = (isset($formParam['sbtName']) && $formParam['sbtName']!='') ? $formParam['sbtName'] : 's1';
			$sbtId = (isset($formParam['sbtId']) && $formParam['sbtId']!='') ? $formParam['sbtId'] : $sbtName;
			$sbtValue = (isset($formParam['sbtValue']) && $formParam['sbtValue']!='') ? $formParam['sbtValue'] : 'Submit';

			$actionUrl = (isset($formParam['actionUrl']) && $formParam['actionUrl']!='') ? $formParam['actionUrl'] : route('admin.dashboard');
			$formMethod = (isset($formParam['formMethod']) && $formParam['formMethod']!='') ? $formParam['formMethod'] : 'POST';
			$formEnctype = (isset($formParam['formEnctype']) && $formParam['formEnctype']!='') ? $formParam['formEnctype'] : 'multipart/form-data';
			$formName = (isset($formParam['formName']) && $formParam['formName']!='') ? $formParam['formName'] : 'f1';
			$formId = (isset($formParam['formId']) && $formParam['formId']!='') ? $formParam['formId'] : $formName;
			$formClass = (isset($formParam['formClass']) && $formParam['formClass']!='') ? $formParam['formClass'] : 'form-horizontal frmValidate';
			$extAtt = (isset($formParam['extAtt']) && $formParam['extAtt']!='') ? $formParam['extAtt'] : '';
			$buttonHide = (isset($formParam['buttonHide']) && $formParam['buttonHide']==true) ? $formParam['buttonHide'] : false;
			$action = (isset($formParam['action']) && $formParam['action']=='edit') ? method_field('PUT') : '';

			$frmBtnContainerClass = (isset($formParam['frmBtnContainerClass']) && $formParam['frmBtnContainerClass']!='') ? $formParam['frmBtnContainerClass'] : 'py-3 col-lg-7 text-center';
			$html = '';
			$html.='<div class="container-fluid p-0">
                        <div class="card mb-4">
                            <div class="card-header"><h5 class="m-0">'.$pageHeading.'</h5></div>
                            <div class="card-body">
                                <form class="'.$formClass.'" name="'.$formName.'" id="'.$formId.'" method="'.$formMethod.'" action="'.$actionUrl.'" enctype="'.$formEnctype.'" '.$extAtt.'>'.$pageHtml. csrf_field().$action;

			                        if($buttonHide==false){
                                        $html.='<div class="form-group row py-3">
                                                    <div class="'.$frmBtnContainerClass.'">
                                                        <button name="'.$sbtName.'" id="'.$sbtId.'" class="btn btn-success" type="submit" >&nbsp;<i class="fa fa-check bigger-110"></i>&nbsp;'.$sbtValue.' </button>&nbsp; &nbsp; &nbsp;
                                                        <a href="'.$cancelUrl.'" class="btn btn-danger" >&nbsp;<i class="fa fa-times bigger-110"></i>&nbsp;'.$cancelValue.'</a>
                                                    </div>
                                                </div>';
                                    }
                                    $html.='
                                </form>
                            </div>
                        </div>
                    </div>';

			return $html;
		}
		public static function getAdminListHTML($pageHeading,$listParam){
			$addUrl = (isset($listParam['addUrl']) && $listParam['addUrl']!='') ? $listParam['addUrl'] : '';
			$frontKeywordUrl = (isset($listParam['frontKeywordUrl']) && $listParam['frontKeywordUrl']!='') ? $listParam['frontKeywordUrl'] : '';
			$adminKeywordUrl = (isset($listParam['adminKeywordUrl']) && $listParam['adminKeywordUrl']!='') ? $listParam['adminKeywordUrl'] : '';
			$searchDate  = (isset($listParam['searchDate']) && $listParam['searchDate'] == 'yes') ? $listParam['searchDate'] : 'no';
			$html = '';
			$html.='<div class="container-fluid p-0">
                        <div class="card mb-4">
                            <h3 class="header smaller lighter blue"></h3>
                            <div class="card-header"><h5 class="m-0">'.$pageHeading.'</h5></div>
                            <div class="card-body px-1 py-2">
                                <div class="table-responsive dark-gray-border" id="table-responsive">
                                    <div class="float-left my-2">';
                                        if(isset($addUrl) && !empty($addUrl)){
                                            $html.='<a href="'.$addUrl.'" class="btn btn-success mx-2"><i class="fa fa-plus-square bigger-130"></i> ADD</a>';
                                        }
                                        if(isset($frontKeywordUrl) && !empty($frontKeywordUrl)){
                                            $html.='<a href="'.$frontKeywordUrl.'" class="btn btn-success font14 mx-2">Front Keyword</a>';
                                        }
                                        if(isset($adminKeywordUrl) && !empty($adminKeywordUrl)){
                                            $html.='<a href="'.$adminKeywordUrl.'" class="btn btn-success font14 mx-2">Admin Keyword</a>';
                                        }
                                        $html.='
                                    </div>
                                    <div id="toolBar" class="float-right my-2">
                                        <div class="d-flex flex-wrap flex-sm-nowrap justify-content-sm-end justify-content-start">
                                            <div class="form-inline">
                                                <label for="txtSrc" class="form-label">Search :&nbsp;</label>
                                                <input type="text" class="form-control mx-1" name="src" id="src">
                                            </div>';
                                            if(isset($searchDate) && !empty($searchDate) && $searchDate == 'yes'){
                                                $html.='<div class="form-inline mt-2 mt-sm-0">
                                                            <input class="form-control mx-1 datepicker" name="srcDate" id="srcDate" type="text" placeholder="Search Date">
                                                        </div>';
                                            }

                                            $html.='<div class="form-inline d-flex flex-nowrap align-content-end pt-3 pt-sm-0">
                                                        <a href="javascript:src()" class="mx-1 btn btn-success"><span class="d-none d-sm-block">Search</span><span class="d-block d-sm-none"><i class="fa fa-search" aria-hidden="true"></i></span></a>
                                                        <a href="javascript:resetGrid()" class="mx-1 btn btn-danger"><span class="d-none d-sm-block">Reset</span><span class="d-block d-sm-none"><i class="fa fa-undo" aria-hidden="true"></i></span></a>
                                                    </div>
                                        </div>
                                        <div class="clearfix float-none"></div>
                                    </div>
                                    <div id="grid"></div>
                                    <div id="footer" class="mx-1">
                                        <div id="containerRowPerPage" class="float-none float-sm-left"></div>
                                        <div id="containerPagination" class="float-none float-sm-right  d-flex justify-content-center my-2 my-sm-0"></div>
                                        <div class="float-none clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
			return $html;
		}
        public static function initPDF($param=array()){
            // initial setting for TCPDF
            $param['pageType']=(isset($param['pageType']) && !empty($param['pageType']))?$param['pageType']:"P";
            $param['pageSize']=(isset($param['pageSize']) && !empty($param['pageType']))?$param['pageSize']:"A4";

            $param['marginLeft']=(isset($param['marginLeft']) && !empty($param['marginLeft']))?$param['marginLeft']:20;
            $param['marginTop']=(isset($param['marginTop']) && !empty($param['marginTop']))?$param['marginTop']:0;
            $param['marginRight']=(isset($param['marginRight']) && !empty($param['marginRight']))?$param['marginRight']:20;
            $param['keepMargins']=(isset($param['keepMargins']) && !empty($param['keepMargins']))?$param['keepMargins']:true;

            $pdf = new \TCPDF($param['pageType'], 'pt', $param['pageSize'], true, 'UTF-8');
            //$pdf = new TCPDF();

            $pdf->SetProtection(array('print', 'copy'), "", "", 0, null);

            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            $pdf->SetMargins($param['marginLeft'], $param['marginTop'], $param['marginRight'], $param['keepMargins']);

            return $pdf;
        }
	}
?>
