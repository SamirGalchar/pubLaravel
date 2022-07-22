<?php

namespace App\Library;

use App\Models\PropertyType;
use App\Models\Suburb;
use App\Student;
use Carbon\Carbon;
use DB;
use http\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Request;
use Plivo\RestClient;
use Gerardojbaez\Money\Money;

class Projectfunction {

	public $parentName;
	public $menuDropDown;
	public $menuHtml='';
	public $paymentData=array();
	public $balanceAmount=0;
	public $adminBalance=0;
	public $globalAds=array();

	function __construct($isInitialLoad = false){
		$this->parentName=array();
		$this->menuDropDown=array();
		$this->menuHtml='';
		$this->footerLinkHtml='';

		if($isInitialLoad){
			//$this->getLanguage();
			//$this->getadminLanguage();
			//$this->getWalletBalance();
			//$this->getAdminBalance();
			//$this->getCardDetails();
			//$this->getAdminAds();

		}
		$this->getPropertyType();
	}
	public function getAdminAds(){
		$globalAds = Globalfunction::convertSelectedRowInArray(DB::table('config_ads')->where('ID',1)->where('expireDate','>=',date('Y-m-d'))->get()->toArray());
		$globalAds = (isset($globalAds[0]) && !empty($globalAds[0])) ? $globalAds[0] : NULL;
		$this->globalAds = $globalAds;
		config()->set('custom.projectConfig.globalAds', $globalAds);
	}
	public function getWalletBalance(){
		$uid = session()->get('cust_id');

		$uid = (isset($uid) && !empty($uid)) ? $uid : 0;
		$balanceAmount = Globalfunction::convertSelectedRowInArray(DB::table('user_mst')->select('balanceAmount')->where('ID',$uid)->get()->toArray());
		$this->balanceAmount = (isset($balanceAmount[0]['balanceAmount']) && !empty($balanceAmount[0]['balanceAmount'])) ? $balanceAmount[0]['balanceAmount'] : 0;
		config()->set('custom.projectConfig.balanceAmount', $this->balanceAmount);
	}
	public function getAdminBalance(){
		$adminBalance = global_function()->convertSelectedRowInArray(DB::table('user_mst')->select('balanceAmount')->where('ID',1)->get()->toArray());
		$this->adminBalance = (isset($adminBalance[0]['balanceAmount']) && !empty($adminBalance[0]['balanceAmount'])) ? $adminBalance[0]['balanceAmount'] : 0;
		config()->set('custom.projectConfig.adminBalance', $this->adminBalance);
	}
	public function getAllParentsName($childId){
		$curRow = Globalfunction::convertSelectedRowInArray(DB::table('menu_mst')->select('parentId','menuTitle')->where('ID',$childId)->get()->toArray());
		if($curRow[0]['parentId']!=0){
			$this->getAllParentsName($curRow[0]['parentId']);
		}
		$this->parentName[]=$curRow[0]['menuTitle'];
	}
	public function generateTopMenu($menu,$level=1){
		foreach($menu as $k=>$v){
			if($level==1){
				$ulClass='sub_menu r_xs_corners bg_light vr_list tr_all tr_xs_none trf_xs_none bs_xs_none d_xs_none';
				$aClass='color_dark fs_large relative r_xs_corners';
				$i_class="icon-angle-down d_inline_m";
				$li_class="container3d relative f_xs_none m_xs_bottom_5 m_right_10";
			}
			else{
				$li_class="container3d relative";
				$ulClass='sub_menu bg_light vr_list tr_all tr_xs_none trf_xs_none bs_xs_none d_xs_none';
				$aClass='d_block color_dark relative';
				$i_class="icon-angle-right";
			}

			$this->menuHtml.='<li class="'.$li_class.'"><a href="'.(($v['record']['urlKey']=="#")?"javascript:void(0);":SITE_URL.$v['record']['urlKey']).'" class="'.$aClass.'">'.$v['record']['menuTitle'].((!empty($v['subCategories']))?'<i class="'.$i_class.'"></i>':'').'</a>';
			$level++;
			if(!empty($v['subCategories'])){
				$this->menuHtml.='<ul class="'.$ulClass.'" level='.$level.'>';
				$this->generateTopMenu($v['subCategories'],$level);
				$this->menuHtml.='</ul>';
			}
			$level--;
			$this->menuHtml.='</li>';
		}
	}
	public function generateTree($menu,$level=1){
		foreach($menu as $k=>$v){
			$ulClass='vr_list_type_4 color_dark fw_light tree_sub_menu';
			if($level==1){
				$aClass='color_dark d_inline_b';
				$i_class="icon-angle-right";
				$li_class="m_bottom_12";
				$span_class="icon_wrap_size_0 circle color_grey_light_5 d_block tr_inherit f_left";
			}
			else{
				$li_class="m_bottom_2";
				$ulClass='vr_list_type_4 color_dark fw_light tree_sub_menu';
				$aClass='color_dark d_inline_b';
				$i_class="icon-angle-right";
				$span_class="icon_wrap_size_0 circle color_grey_light_5 d_block tr_inherit f_left";
			}
			$this->treeHtml.='<li class="'.$li_class.'"><a href="'.(($v['record']['urlKey']=="#")?"javascript:void(0);":SITE_URL.$v['record']['urlKey']).'" class="'.$aClass.'"><span class="'.$span_class.'"><i class="'.$i_class.'"></i></span>'.$v['record']['menuTitle'].'</a>';
			$level++;
			if(!empty($v['subCategories'])){
				$this->treeHtml.='<ul class="'.$ulClass.'" level='.$level.'>';
				$this->generateTree($v['subCategories'],$level);
				$this->treeHtml.='</ul>';
			}
			$level--;
			$this->treeHtml.='</li>';
		}
	}
	public function getMenusDropdown($parent,$level=1){
		$res = Globalfunction::convertSelectedRowInArray(DB::table('menu_mst')->where('parentId',$parent)->orderBy('orderNo', 'ASC')->get()->toArray());
		foreach($res as $row){
			$this->menuDropDown[$row['ID']] = str_pad($row['menuTitle'], $level+strlen($row['menuTitle']), "-", STR_PAD_LEFT);
			$level++;
			$this->getMenusDropdown($row['ID'],$level);
			$level--;
		}
	}
	public function getFooterLink($menu,$level=1){
		foreach($menu as $k=>$v){
			$aClass='color_dark d_inline_b';
			$i_class="icon-angle-right";
			$li_class="m_bottom_12";
			$span_class="icon_wrap_size_0 circle color_grey_light_5 d_block tr_inherit f_left";
			$this->footerLinkHtml.='<li class="'.$li_class.'"><a href="'.(($v['record']['urlKey']=="#")?"javascript:void(0);":SITE_URL.$v['record']['urlKey']).'" class="'.$aClass.'"><span class="'.$span_class.'"><i class="'.$i_class.'"></i></span>'.$v['record']['menuTitle'].'</a></li>';
		}
	}
	public function getPageMeta($pagename){
		return Globalfunction::convertSelectedRowInArray(DB::table('page_mst')->where('pageSlug',$pagename)->get()->toArray());
	}
	public function getDuration($duration,$time,$manualLanguage=''){
		$lessonDuration = config('custom.projectConfig.lessonDuration');
		$lessonDuration_french = config('custom.projectConfig.lessonDuration_french');

		if($manualLanguage==''){
			$language = session()->get('cust_language');
			$language = (isset($language) && !empty($language))?$language:1;
		}
		else{
			$language = (isset($manualLanguage) && !empty($manualLanguage))?$manualLanguage:1;
		}

		$durationArr = (($language==2) ? $lessonDuration_french : $lessonDuration);
		$temp='';
		if(!empty($duration)){
			if($duration == '1'){ $temp = '0.5'; }
			else if($duration == '2'){ $temp = '1'; }
			else if($duration == '3'){ $temp = '1.5'; }
			else if($duration == '4'){ $temp = '2'; }
			else if($duration == '5'){ $temp = '2.5'; }
			else if($duration == '6'){ $temp = '3'; }
			else if($duration == '7'){ $temp = '3.5'; }
			else if($duration == '8'){ $temp = '4'; }
			else if($duration == '9'){ $temp = '4.5'; }
		}
		else if(!empty($time)){
			if($time == '0.5'){ $temp = $durationArr[1]; }
			else if($time == '1'){ $temp = $durationArr[2]; }
			else if($time == '1.5'){ $temp = $durationArr[3]; }
			else if($time == '2'){ $temp = $durationArr[4]; }
			else if($time == '2.5'){ $temp = $durationArr[5]; }
			else if($time == '3'){ $temp = $durationArr[6]; }
			else if($time == '3.5'){ $temp = $durationArr[7]; }
			else if($time == '4'){ $temp = $durationArr[8]; }
			else if($time == '4.5'){ $temp = $durationArr[9]; }
		}
		return $temp;
	}

	public function seperateFolderFromFile($path){
		$rt['folder'] = $rt['fileName'] = '';
		$tmpFileName = explode('/',$path);
		if(count($tmpFileName)>1){
			for($i=0;$i<count($tmpFileName)-1;$i++){
				$rt['folder'].= $tmpFileName[$i].'/';
			}
			$rt['fileName'] = $tmpFileName[count($tmpFileName)-1];
		}
		else{
			$rt['fileName'] = $path;
		}
		return $rt;

	}
	public function downloadFile($param){
		$param['filePath']= (isset($param['filePath']) && !empty($param['filePath']))?$param['filePath']:DIR_UPD;
		$param['sourceFileName']= (isset($param['sourceFileName']) && !empty($param['sourceFileName']))?$param['sourceFileName']:time();
		$param['downloadFileName']= (isset($param['downloadFileName']) && !empty($param['downloadFileName']))?$param['downloadFileName']:$param['sourceFileName'];
		$param['header']= (isset($param['header']) && !empty($param['header']))?$param['header']:'';

		/*echo '<pre>';
        print_r($param);
        exit;*/
		$rt = response()->download($param['filePath'].$param['sourceFileName'], $param['downloadFileName']);
		if(!empty($param['header'])){
			$rt = $rt->sendHeaders($param['header']);
		}
		return $rt; // always use return with function calling. Use variable or print_r for debugging purpose but it will not download file.
	}
	public function makeStripeBankPayment($param){
		$param['currency']=(isset($param['currency']) && !empty($param['currency']))?$param['currency']:'EUR';
		$param['description']=(isset($param['description']) && !empty($param['description']))?$param['description']:'BankPayment on '.date("Y-m-d H:i:s");
		try {
			\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
			\Stripe\Stripe::setApiVersion("2018-05-21");

			$makePaymentObj = array(
				"amount" => ($param['amount']*100),
				"currency" => $param['currency'],
				"destination" => $param['accId'],
				"description" => $param['description'],
			);
			$transfer = \Stripe\Transfer::create($makePaymentObj);
			$rt = array("cls"=>config('custom.constants.SUCCESS'),"msg"=>config()->get('custom.projectConfig.languageConfig.BANK_PAYMENT_DONE'),'transactionId'=>$transfer->id);
		}
		catch (Exception $e) {
			$rt =  array("cls"=>config('custom.constants.ERROR'),"msg"=>$e->getMessage());
		}
		return $rt;
	}
	public function roundUpPrice($price){
		$newPrice = $price;
		$extraAmmount = 0;

		$price = round($price,2);
		$priceLastDigit = explode('.',$price);
		$priceLastDigit[1] = (!empty($priceLastDigit[1]))?str_pad($priceLastDigit[1],2,0):00;

		if(!empty($priceLastDigit[1]) && $priceLastDigit[1] > 50){
			$newPrice = $priceLastDigit[0] + 1;
			$extraAmmount  = $newPrice - $price;
		}
		else if(!empty($priceLastDigit[1]) && $priceLastDigit[1] < 50){
			$newPrice = $priceLastDigit[0] + 0.5;
			$extraAmmount  = $newPrice - $price;
		}

		return array('roundUpPrice'=>$newPrice, 'extraAmmount'=>$extraAmmount);
	}

	public function getLanguageValue($Key='',$userLanVal='1'){
		$userLan = ($userLanVal == '2') ? "french" : "english";
		$res = Globalfunction::convertSelectedRowInArray(DB::table('front_keyword_mst')->select($userLan.' as value','keyword')->where('keyword',$Key)->get()->toArray());

		$rtValue=(!empty($res))?$res[0]['value']:'';
		return $rtValue;
	}
	public function test(){
		//session()->flush();
		$student_model = new \App\Email();

		return $student_model->getInfo(1);
		//return $student_model->viewSession_v1();

	}
	public function getCardDetails(){
		$student_model = new Student();
		$uid = session()->get('cust_id');
		$custType= session()->get('cust_type');
		if($custType==2){
			$this->paymentData = $student_model->getStudentpaymentMethodInfo($uid);
			config()->set('custom.projectConfig.paymentData',$this->paymentData);
		}
	}
	public function sendPlivoSms($param){
		// Plivo sms send
		$param['text'] = (isset($param['text']) && !empty($param['text'])) ? $param['text'] : '';
		$param['dst'] = (isset($param['dst']) && !empty($param['dst'])) ? $param['dst'] : '';

		$client = new RestClient(AUTH_ID,AUTH_TOKEN);

		$message_created = $client->messages->create(
			SMS_SENDER,
			[$param['dst']],
			$param['text']
		);
	}
	public static function indexColumn($params=array()){
		$array1 = [
			'defaultContent' => '',
			'data'           => 'DT_RowIndex',
			'name'           => 'DT_RowIndex',
			'title'          => 'No',
			'render'         => null,
			'orderable'      => false,
			'searchable'     => false,
			'exportable'     => false,
			'printable'      => true,
			'footer'         => '',
		];
		return array_merge($array1,$params);
	}

	public static function checkStep($step){
		$stepErr = false;
		$next_step = Auth::user()->next_step;
		if($step !== $next_step){
			$stepErr = true;
		}
		if($stepErr):
			switch ($next_step) {
				case 2:
					return redirect()->route('user.step-two')->send();
					break;
				case 3:
					return redirect()->route('user.step-three')->send();
					break;
				case 4:
					return redirect()->route('user.step-four')->send();
					break;
				case 5:
					return redirect()->route('user.membership')->send();
					break;
				case 6:
					return redirect()->route('user.profile')->send();
					break;
				default:
					return redirect()->route('user.profile')->send();
			}
		endif;
	}

	public static function renewLink($param){
		if(isset($param['status']) && $param['status'] == 'active'):
			if(isset($param['transaction_type']) && ($param['transaction_type'] == 'm') && isset($param['renew']) && ($param['renew'] == 0)):
				$current_month = Carbon::now()->addMonth();
				$time=strtotime($param['expire_at']);
				$month=date("m",$time);
				if(strtotime($param['expire_at']) <= strtotime($current_month)):
					$html = '<a class="roboto rosegold font14" href="'.route('user.membership-renew',$param['id']).'"><u>Renew for FREE</u></a>';

					return $html;
				endif;
			else:
				return '';
			endif;
		else:
			return '';
		endif;
	}

	public static function getPropertyType(){
		$data = PropertyType::all();
		$data = $data->toArray();
		dd($data);
	}

	public function radioWithTextBox($param){

	}

    public static function getSuburb(){
        $info = Suburb::limit(30)->get()->toArray();
        return $info;
    }

    public static function priceFormat($price){
        //$money = new Gerardojbaez\Money(1000000, 'AUD');
        $money = new Money($price, 'AUD');
        return rtrim(rtrim($money->amount(),'0'),'.');        
    }
    
    public static function phoneNumberFormat($phone){
        return preg_replace('~.*(\d{4})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{3}).*~', '$1 $2 $3', $phone);
    }
    
}
?>
