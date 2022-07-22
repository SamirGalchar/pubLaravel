<?php
namespace App\Library\form_field;

use App\Library\Globalfunction;
use DB;

class Fields{

	public function __construct(){

	}
	public static function displayData($text){
		if($text['inputType']=="hidden"){
			return '';
		}
		else{
			return '<div class="form-group '.$text['frmGroupCls'].'">
					<label class="'.$text['label_class'].'" for="'.$text['name'].'"> '.$text['label'].'&nbsp;</label>
					<div class="'.$text['div_class'].'">'.$text['value'].'</div>
				</div>';
		}
	}
	public static function textBox($text){

		$text['frmGroupCls'] = (isset($text['frmGroupCls']) && !empty($text['frmGroupCls'])) ? $text['frmGroupCls'] : 'row';
		$text['label'] = (isset($text['label']) && !empty($text['label'])) ? $text['label'] : 'Enter Text Here: ';
		$text['label_class'] = (isset($text['label_class']) && !empty($text['label_class'])) ? $text['label_class'] : 'col-form-label col-lg-2 text-lg-right col-12 text-left';

		$text['div_class'] = (isset($text['div_class']) && !empty($text['div_class'])) ? $text['div_class'] : 'col-lg-5 col-12';
		$text['class'] = (isset($text['class']) && !empty($text['class'])) ? ''.trim($text['class']) : 'form-control';
		$text['inputType'] = (isset($text['inputType']) && !empty($text['inputType'])) ? $text['inputType'] : 'text';
		$text['name'] = (isset($text['name']) && !empty($text['name'])) ? $text['name'] : '';
		$text['id'] = (isset($text['id']) && !empty($text['id'])) ? $text['id'] : $text['name'];
		$text['value'] = (isset($text['value']) && !empty($text['value'])) ? $text['value'] : '';
		$text['extraAtt'] = (isset($text['extraAtt']) && !empty($text['extraAtt'])) ? $text['extraAtt'] : '';

		$text['onlyField'] = (isset($text['onlyField']) && !empty($text['onlyField'])) ? $text['onlyField'] : false;
		$text["action"]=(isset($text["action"]) && !empty($text['action']))?$text["action"]:'view';

		if($text["action"]=='view'){
			return self::displayData($text);
		}
		else{
			if($text["onlyField"]==true){
				return '<input type="'.$text['inputType'].'" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['id'].'" value="'.$text['value'].'" '.$text['extraAtt'].' />';
			}
			else{
				return '<div class="form-group '.$text['frmGroupCls'].'">
					<label class="'.$text['label_class'].'" for="'.$text['name'].'"> '.$text['label'].'&nbsp;</label>
					<div class="'.$text['div_class'].'"><input type="'.$text['inputType'].'" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['id'].'" value="'.$text['value'].'" '.$text['extraAtt'].' /></div>
				</div>';
			}
		}
	}
	public static function hidden($text) {
		$text['inputType']='hidden';
		/*$text["onlyField"]=true;
		$text["extraAtt"]='style="display:none;"';*/
		return self::textBox($text);
	}
	public static function password($text){
		$text['inputType']='password';
		return self::textBox($text,'password');
	}
	public static function textArea($text){

		$text['frmGroupCls'] = (isset($text['frmGroupCls']) && !empty($text['frmGroupCls'])) ? $text['frmGroupCls'] : 'row';
		$text['label'] = (isset($text['label']) && !empty($text['label'])) ? $text['label'] : 'Enter Text Here: ';
		$text['label_class'] = (isset($text['label_class']) && !empty($text['label_class'])) ? $text['label_class'] : 'col-form-label col-lg-2 text-lg-right col-12 text-left';

		$text['div_class'] = (isset($text['div_class']) && !empty($text['div_class'])) ? $text['div_class'] : 'col-lg-5 col-12';
		$text['class'] = (isset($text['class']) && !empty($text['class'])) ? trim($text['class']) : 'form-control';
		$text['inputType'] = (isset($text['inputType']) && !empty($text['inputType'])) ? $text['inputType'] : 'text';
		$text['name'] = (isset($text['name']) && !empty($text['name'])) ? $text['name'] : '';
		$text['id'] = (isset($text['id']) && !empty($text['id'])) ? $text['id'] : $text['name'];
		$text['value'] = (isset($text['value']) && !empty($text['value'])) ? $text['value'] : '';
		$text['extraAtt'] = (isset($text['extraAtt']) && !empty($text['extraAtt'])) ? $text['extraAtt'] : '';

		$text['onlyField'] = (isset($text['onlyField']) && !empty($text['onlyField'])) ? $text['onlyField'] : false;
		$text["action"]=(isset($text["action"]) && !empty($text['action']))?$text["action"]:'view';

		if($text["action"]=='view'){
			return self::displayData($text);
		}
		else{
			if($text["onlyField"]== true){
				return '<textarea class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['id'].'" '.$text['extraAtt'].' >'.$text['value'].'</textarea>';
			}
			else{
				return '<div class="form-group '.$text['frmGroupCls'].'">
							<label class="'.$text['label_class'].'" for="'.$text['name'].'">'.$text['label'].'&nbsp;</label>
							<div class="'.$text['div_class'].'"><textarea class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['id'].'" '.$text['extraAtt'].' >'.$text['value'].'</textarea></div>
						</div>';
			}
		}


	}
	public static function textAreaEditor($text){
		$text['frmGroupCls'] = (isset($text['frmGroupCls']) && !empty($text['frmGroupCls'])) ? $text['frmGroupCls'] : 'row';
		$text['label'] = (isset($text['label']) && !empty($text['label'])) ? $text['label'] : 'Enter Text Here: ';
		$text['label_class'] = (isset($text['label_class']) && !empty($text['label_class'])) ? $text['label_class'] : 'col-form-label col-lg-2 text-lg-right col-12 text-left';

		$text['div_class'] = (isset($text['div_class']) && !empty($text['div_class'])) ? $text['div_class'] : 'col-lg-9 col-12';
		$text['div_id'] = isset($text['div_id']) ? $text['div_id'] : '';
		$text['class'] = (isset($text['class']) && !empty($text['class'])) ? trim($text['class']) : 'ckeditor';
		$text['inputType'] = (isset($text['inputType']) && !empty($text['inputType'])) ? $text['inputType'] : 'text';
		$text['name'] = (isset($text['name']) && !empty($text['name'])) ? $text['name'] : '';
		$text['id'] = (isset($text['id']) && !empty($text['id'])) ? $text['id'] : $text['name'];
		$text['value'] = (isset($text['value']) && !empty($text['value'])) ? $text['value'] : '';
		$text['extraAtt'] = (isset($text['extraAtt']) && !empty($text['extraAtt'])) ? $text['extraAtt'] : '';

		$text['onlyField'] = (isset($text['onlyField']) && !empty($text['onlyField'])) ? $text['onlyField'] : false;
		$text["action"]=(isset($text["action"]) && !empty($text['action']))?$text["action"]:'view';

		if($text["action"]=='view'){
			return self::displayData($text);
		}
		else{
			if($text["onlyField"]== true){
				return '<textarea class="ckeditor '.$text['class'].'" name="'.$text['name'].'" id="'.$text['id'].'" '.$text['extraAtt'].' >'.$text['value'].'</textarea><div id="er_msg_'.$text['id'].'" class="disp-none"></div>';
			}
			else{
				return '<div class="form-group '.$text['frmGroupCls'].'"  id="'.$text['div_id'].'">
							<label class="'.$text['label_class'].'" for="'.$text['name'].'">'.$text['label'].'&nbsp;</label>
							<div class="'.$text['div_class'].'">
								<textarea class="ckeditor '.$text['class'].'" name="'.$text['name'].'" id="'.$text['id'].'" '.$text['extraAtt'].' >'.$text['value'].'</textarea>
								<div id="er_msg_'.$text['id'].'" class="disp-none"></div>
							</div>
						</div>';
			}
		}


	}
	public static function checkBox($chk){
		$checkBoxes='';
		$text['frmGroupCls'] = (isset($chk['frmGroupCls']) && !empty($chk['frmGroupCls'])) ? $chk['frmGroupCls'] : 'row mb-0';
		$chk['label'] = (isset($chk['label']) && !empty($chk['label'])) ? $chk['label'] : 'Select your choice';
		$chk['label_class'] = (isset($chk['label_class']) && !empty($chk['label_class'])) ? $chk['label_class'] : 'col-form-label col-lg-2 text-lg-right col-12 text-left';

		$chk['div_class'] = (isset($chk['div_class']) && !empty($chk['div_class'])) ? $chk['div_class'] : 'col-lg-5 col-12 col-form-label form-check-inline mx-2';
		$chk['checkboxArr'] = (isset($chk['checkboxArr']) && !empty($chk['checkboxArr'])) ? $chk['checkboxArr'] : array();
		$chk['class'] = (isset($chk['class']) && !empty($chk['class'])) ? $chk['class'] : 'form-group';
		$chk['extraAtt'] = (isset($chk['extraAtt']) && !empty($chk['extraAtt'])) ? ' '.$chk['extraAtt'] : '';
		$chk['onlyField'] = (isset($chk['onlyField']) && !empty($chk['onlyField'])) ? $chk['onlyField'] : false;

		$chk['errorPlacement'] = (isset($chk['errorPlacement']) && !empty($chk['errorPlacement'])) ? $chk['errorPlacement'] : 'chkBoxErrorMsg';
		for($i=0;$i<count($chk['checkboxArr']);$i++){
			$arr = $chk['checkboxArr'][$i];
			$arr['name'] = isset($arr['name']) ? $arr['name'] : 'chk[]';
			$arr['id'] = isset($arr['id']) ? $arr['id'] : (str_replace("[]",'_',$arr['name']).$i);

			$arr['checked'] = (isset($arr['isChecked']) && $arr['isChecked']==true) ? 'checked="checked"':'';
			$arr['value'] = isset($arr['value']) ? $arr['value'] : $i;
			$arr['dispText'] = isset($arr['dispText']) ? $arr['dispText'] : $arr['value'];
			$arr['lbl_class'] = isset($arr['lbl_class']) ? $arr['lbl_class'] : 'custom-control custom-checkbox ml-2';
			$arr['input_class'] = isset($arr['input_class']) ? $arr['input_class'] : 'custom-control-input';

			if($chk["action"]=='view'){
				$checkBoxes.='<label class="'.$arr['lbl_class'].'">
								<span class="custom-control-label" for="'.$arr['id'].'">'.ucwords($arr['dispText']).'</span>
							</label>';
			}
			else{
				$checkBoxes.='<label class="'.$arr['lbl_class'].'">
								<input class="'.$arr['input_class'].'" name="'.$arr['name'].'" id="'.$arr['id'].'" type="checkbox" value="'.$arr['value'].'" '.$arr['checked'].' '.$chk['extraAtt'].' />
								<span class="custom-control-label" for="'.$arr['id'].'">'.ucwords($arr['dispText']).'</span>
							</label>';
			}
		}

		if($chk['onlyField']==true){
			return $checkBoxes;
		}
		else {
			return '<div class="form-group mb-0 '.$text['frmGroupCls'].'">
						<label class="'.$chk['label_class'].'"> '.$chk['label'].'&nbsp;</label>
						<div class="'.$chk['div_class'].'">'.$checkBoxes.'</div>
					</div>
					<div class="form-group my-0 py-0 '.$text['frmGroupCls'].'">
						<label class="'.$chk['label_class'].'"></label>
						<div class="'.$chk['div_class'].' px-1"><label id="'.$chk['errorPlacement'].'"></label></div>
					</div>';
		}
	}
	public static function radio($chk){
		$checkBoxes='';
		$text['frmGroupCls'] = (isset($chk['frmGroupCls']) && !empty($chk['frmGroupCls'])) ? $chk['frmGroupCls'] : 'row mb-0';
		$chk['label'] = (isset($chk['label']) && !empty($chk['label'])) ? $chk['label'] : 'Select your choice';
		$chk['label_class'] = (isset($chk['label_class']) && !empty($chk['label_class'])) ? $chk['label_class'] : 'col-form-label col-lg-2 text-lg-right col-12 text-left';

		$chk['div_class'] = (isset($chk['div_class']) && !empty($chk['div_class'])) ? $chk['div_class'] : 'col-lg-5 col-12 col-form-label form-check-inline mx-2';
		$chk['radioArr'] = (isset($chk['radioArr']) && !empty($chk['radioArr'])) ? $chk['radioArr'] : array();
		$chk['class'] = (isset($chk['class']) && !empty($chk['class'])) ? $chk['class'] : 'form-group';
		$chk['extraAtt'] = (isset($chk['extraAtt']) && !empty($chk['extraAtt'])) ? ' '.$chk['extraAtt'] : '';
		$chk['onlyField'] = (isset($chk['onlyField']) && !empty($chk['onlyField'])) ? $chk['onlyField'] : false;
		$chk['selectedValue'] = (isset($chk['selectedValue']) && !empty($chk['selectedValue'])) ? $chk['selectedValue'] : '';

		$chk['errorPlacement'] = (isset($chk['errorPlacement']) && !empty($chk['errorPlacement'])) ? $chk['errorPlacement'] : 'chkBoxErrorMsg';

		for($i=0;$i<count($chk['radioArr']);$i++){
			$arr = $chk['radioArr'][$i];
			$arr['name'] = isset($arr['name']) ? $arr['name'] : 'rd';
			$arr['id'] = isset($arr['id']) ? $arr['id'] :  (str_replace("[]",'_',$arr['name']).$i);
			$arr['checked'] = ($chk['selectedValue'] == $arr['value']) ? 'checked="checked"':'';
			$arr['value'] = isset($arr['value']) ? $arr['value'] : $i;
			$arr['dispText'] = isset($arr['dispText']) ? $arr['dispText'] : $arr['value'];
			$arr['lbl_class'] = isset($arr['lbl_class']) ? $arr['lbl_class'] : 'custom-control custom-radio ml-2';
			$arr['input_class'] = isset($arr['input_class']) ? $arr['input_class'] : 'custom-control-input';

			if($chk["action"]=='view'){
				$checkBoxes.='<label class="'.$arr['lbl_class'].'">
								<span class="custom-control-label" for="'.$arr['id'].'">'.ucwords($arr['dispText']).'</span>
							</label>';
			}
			else{
				$checkBoxes.='<label class="'.$arr['lbl_class'].'">
								<input class="'.$arr['input_class'].'" name="'.$arr['name'].'" id="'.$arr['id'].'" type="radio" value="'.$arr['value'].'" '.$arr['checked'].' '.$chk['extraAtt'].' />
								<span class="custom-control-label" for="'.$arr['id'].'">'.ucwords($arr['dispText']).'</span>
							</label>';
			}
		}

		if($chk['onlyField']==true){
			return $checkBoxes;
		}
		else {
			return '<div class="form-group '.$text['frmGroupCls'].'">
					<label class="'.$chk['label_class'].'"> '.$chk['label'].'&nbsp;</label>
					<div class="'.$chk['div_class'].'">'.$checkBoxes.'</div>
				</div>
				<div class="form-group my-0 py-0 '.$text['frmGroupCls'].'">
					<label class="'.$chk['label_class'].' my-0 py-0"></label>
					<div class="'.$chk['div_class'].' px-1 my-0 py-0"><label id="'.$chk['errorPlacement'].'"></label></div>
				</div>';
		}
	}
	public static function fileBox($text){
		$text['frmGroupCls'] = (isset($text['frmGroupCls']) && !empty($text['frmGroupCls'])) ? $text['frmGroupCls'] : 'row';
		$text['label'] = (isset($text['label']) && !empty($text['label'])) ? $text['label'] : 'Select File: ';
		$text['label_class'] = (isset($text['label_class']) && !empty($text['label_class'])) ? $text['label_class'] : 'col-form-label col-lg-2 text-lg-right col-12 text-left';

		$text['div_class'] = (isset($text['div_class']) && !empty($text['div_class'])) ? $text['div_class'] : 'col-lg-5 col-12';
		$text['class'] = (isset($text['class']) && !empty($text['class'])) ? trim($text['class']) : 'custom-file-input';
		$text['name'] = (isset($text['name']) && !empty($text['name'])) ? $text['name'] : '';
		$text['id'] = (isset($text['id']) && !empty($text['id'])) ? $text['id'] : $text['name'];
		$text['value'] = (isset($text['value']) && !empty($text['value'])) ? $text['value'] : '';
		$text['extraAtt'] = (isset($text['extraAtt']) && !empty($text['extraAtt'])) ? $text['extraAtt'] : '';

		$text['onlyField'] = (isset($text['onlyField']) && !empty($text['onlyField'])) ? $text['onlyField'] : false;
		$text["action"]=(isset($text["action"]) && !empty($text['action']))?$text["action"]:'view';

		if($text["action"]=='view'){
			return '<div class="form-group '.$text['frmGroupCls'].'">
						<label class="'.$text['label_class'].'" for="'.$text['name'].'"> '.$text['label'].'&nbsp;</label>
						<div class="'.$text['div_class'].'">'.$text["value"].'</div>
					</div>';
		}
		else{
			if($text["onlyField"]==true){
				return '<label class="custom-file">
							<input type="file" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['id'].'" '.$text['extraAtt'].' />
							<span class="custom-file-label"></span>
						</label>';
			}
			else{
				return '<div class="form-group '.$text['frmGroupCls'].' mb-0">
							<label class="'.$text['label_class'].'" for="'.$text['name'].'"> '.$text['label'].'&nbsp;</label>
							<div class="'.$text['div_class'].'">
								<label class="custom-file">
									<input type="file" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['id'].'" '.$text['extraAtt'].' />
									<span class="custom-file-label"></span>
								</label>
							</div>
						</div>
						<div class="form-group '.$text['frmGroupCls'].'">
							<label class="'.$text['label_class'].' my-0 py-0"></label>
							<div class="'.$text['div_class'].' my-0 py-0">'.$text['value'].'</div>
						</div>';
			}
		}
	}
	public static function selectBox($text){
		$text['frmGroupCls'] = (isset($text['frmGroupCls']) && !empty($text['frmGroupCls'])) ? $text['frmGroupCls'] : 'row';
		$text['label'] = (isset($text['label']) && !empty($text['label'])) ? $text['label'] : 'Select Option: ';
		$text['label_class'] = (isset($text['label_class']) && !empty($text['label_class'])) ? $text['label_class'] : 'col-form-label col-lg-2 text-lg-right col-12 text-left';

		$text['div_class'] = (isset($text['div_class']) && !empty($text['div_class'])) ? $text['div_class'] : 'col-lg-5 col-12';
		$text['class'] = (isset($text['class']) && !empty($text['class'])) ? ''.trim($text['class']) : 'custom-select';
		$text['name'] = (isset($text['name']) && !empty($text['name'])) ? $text['name'] : '';
		$text['id'] = (isset($text['id']) && !empty($text['id'])) ? $text['id'] : $text['name'];
		$text['extraAtt'] = (isset($text['extraAtt']) && !empty($text['extraAtt'])) ? $text['extraAtt'] : '';

		//$text['tableName'] = isset($text['tableName']) ? $text['tableName'] : '';
		//$text['valueField'] = isset($text['valueField']) ? $text['valueField'] : '';
		$text['textField'] = (isset($text['textField']) && !empty($text['textField'])) ? $text['textField'] : '';

		$text['onlyField'] = (isset($text['onlyField']) && !empty($text['onlyField'])) ? $text['onlyField'] : false;
		$text['onlyOptions'] = (isset($text['onlyOptions']) && !empty($text['onlyOptions'])) ? $text['onlyOptions'] : false;
		$text["action"]=(isset($text["action"]) && !empty($text['action']))?$text["action"]:'view';

		$html='';

		if($text["action"]=='view'){
			if($text["fromDB"]){
				$qry = "select ".$text["selectBoxParam"]["textField"]." from ".DB::getTablePrefix().$text["selectBoxParam"]["tableName"]." where ".$text["selectBoxParam"]["valueField"]." = '".$text["selectBoxParam"]["selectedValue"]."'";
				$text['value']=Globalfunction::convertSelectedRowInArray(DB::select(DB::RAW($qry)));
			}
			else{
				$text['value']=($text["selectBoxParam"]["isKeyArray"])?$text["selectBoxParam"]["arrVal"][$text["selectBoxParam"]["selectedValue"]]:$text["selectBoxParam"]["selectedValue"];
			}
			return self::displayData($text);
		}
		else{
			if($text["fromDB"]){
				$text["selectBoxParam"]["defaultText"]=isset($text["selectBoxParam"]["defaultText"])?$text["selectBoxParam"]["defaultText"]:'Select Value';
				$text["selectBoxParam"]["selectedValue"]=isset($text["selectBoxParam"]["selectedValue"])?$text["selectBoxParam"]["selectedValue"]:NULL;
				$text["selectBoxParam"]["where"]=isset($text["selectBoxParam"]["where"])?$text["selectBoxParam"]["where"]:'';
				$text["selectBoxParam"]["groupBy"]=isset($text["selectBoxParam"]["groupBy"])?$text["selectBoxParam"]["groupBy"]:'';

				if(!$text['onlyOptions']){
					$html.='<select name="'.$text["name"].'" id="'.$text["id"].'" class="'.$text["class"].'" '.$text["extraAtt"].'>';
				}

				$html .= Globalfunction::generateSelectBox($text["selectBoxParam"]["tableName"],$text["selectBoxParam"]["valueField"],$text["selectBoxParam"]["textField"],$text["selectBoxParam"]["defaultText"],$text["selectBoxParam"]["selectedValue"],$text["selectBoxParam"]["where"],$text["selectBoxParam"]["groupBy"]);
				if(!$text['onlyOptions']){ $html.='</select>'; }
			}
			else{
				$text["selectBoxParam"]["defaultText"]=isset($text["selectBoxParam"]["defaultText"])?$text["selectBoxParam"]["defaultText"]:'Select Value';
				$text["selectBoxParam"]["selectedValue"]=isset($text["selectBoxParam"]["selectedValue"])?$text["selectBoxParam"]["selectedValue"]:NULL;
				$text["selectBoxParam"]["isKeyArray"]=isset($text["selectBoxParam"]["isKeyArray"])?$text["selectBoxParam"]["isKeyArray"]:false;

				if(!$text['onlyOptions']){ $html.='<select name="'.$text["name"].'" id="'.$text["id"].'" class="'.$text["class"].'" '.$text["extraAtt"].'>'; }
				$html.= Globalfunction::generateArraySelectBox($text["selectBoxParam"]["arrVal"],$text["selectBoxParam"]["defaultText"],$text["selectBoxParam"]["selectedValue"],$text["selectBoxParam"]["isKeyArray"]);
				if(!$text['onlyOptions']){ $html.='</select>'; }
			}
			if($text["onlyField"]==true){
				return $html;
			}
			else{
				return '<div class="form-group '.$text['frmGroupCls'].'">
					<label class="'.$text['label_class'].'" for="'.$text['name'].'"> '.$text['label'].'&nbsp;</label>
					<div class="'.$text['div_class'].'">'.$html.'</div>
				</div>
				<div class="clearfix"></div><div class="space-4"></div>';
			}
		}
	}
	public static function button($btn){
		$btn['frmGroupCls'] = (isset($btn['frmGroupCls']) && !empty($btn['frmGroupCls'])) ? $btn['frmGroupCls'] : 'row';
		$btn['value'] = isset($btn['value']) ? $btn['value'] : '';
		$btn['name'] = isset($btn['name']) ? $btn['name'] : 's1';
		$btn['class'] = isset($btn['class']) ? $btn['class'] : 'btn btn-primary';
		$btn['type'] = isset($btn['type']) ? $btn['type'] : 'submit';
		$btn['src'] = isset($btn['src']) ? $btn['src'] : '';
		$btn['extraAtt'] = isset($btn['extraAtt']) ? ' '.$btn['extraAtt'] : '';
		$btn['onlyField'] = isset($btn['onlyField']) ? $btn['onlyField'] : false;

		$b='<input type="'.$btn["type"].'" name="'.$btn["name"].'" class="'.$btn["class"].'" id="'.$btn["name"].'" value="'.$btn["value"].'" '.$btn['extraAtt'].' ';
		$b.=($btn["type"]=="image" && $btn["src"]!='')?'src="'.$btn["src"].'"':'';
		$b.=' />';

		if($btn['onlyField']== true){
			return $b;
		}
		else{
			return '<div class="'.$btn['frmGroupCls'].'">'.$b.'</div>';
		}
	}
	public static function label($text){
		$text['label'] = isset($text['label']) ? $text['label'] : 'Enter Label Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';

		$text['class'] = isset($text['class']) ? 'input_text '.trim($text['class']) : 'input_text';
		$text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		return '<label class="'.$text['class'].'">'.$text['label'].'</label>';
	}
	public static function link($text){
		$text['href'] = isset($text['href']) ? $text['href'] : 'Enter Link Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ? ''.trim($text['class']) : 'text-primary';
		$text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		return '<a href="'.$text['href'].'" class="'.$text['class'].'" value="'.$text['value'].'" '.$text['extraAtt'].'>'.$text['value'].'</a>';
	}
	public static function image($text){
		$text['href'] = isset($text['href']) ? $text['href'] : '';
		$text['src'] = isset($text['src']) ? $text['src'] : 'Enter Image Path Here: ';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['id'] = isset($text['id']) ? $text['id'] : '';
		$text['class'] = isset($text['class']) ? ''.trim($text['class']) : 'img-fluid';
		$text['height'] = isset($text['height']) ? ''.trim($text['height']) : 0;
		$text['width'] = isset($text['width']) ? ''.trim($text['width']) : 0;
		$text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';

		return '<a href="'.$text['href'].'" class="thickbox"><img src="'.$text['src'].'" class="'.$text['class'].'" name="'.$text['name'].'" id="'.$text['id'].'" alt="" '.($text['width'] > 0 ? 'width="'.$text['width'].'"' : '').' '.($text['height'] > 0 ? 'height="'.$text['height'].'"' : '').' '.$text['extraAtt'].'></a>';
	}


	public static function radio1($radio){
		$radio['label'] = isset($radio['label']) ? $radio['label'] : 'Select Any One: ';
		$radio['values'] = isset($radio['values']) ? $radio['values'] :array();
		$radio['value'] = isset($radio['value']) ? $radio['value'] : '';
		$radio['name'] = isset($radio['name']) ? $radio['name'] : '';
		$radio['class'] = isset($radio['class']) ? 'radio '.$radio['class'] : 'radio';
		$radio['extraAtt'] = isset($radio['extraAtt']) ? ' '.$radio['extraAtt'] : '';
		$radio['onlyField'] = isset($radio['onlyField']) ? $radio['onlyField'] : false;
		$check='';
		$radios='';
		foreach($radio['values'] as $k=>$v){
			$check=($k==$radio['value'])?'checked="checked"':'';
			$radios.='<span class="radiobadge"><input class="'.$radio['class'].'" id="'.$radio['name'].'" name="'.$radio['name'].'" type="radio" value="'.$k.'" '.$check.' '.$radio['extraAtt'].' />&nbsp;'.ucwords($v)."&nbsp;&nbsp;
			</span>";
		}
		if($radio["onlyField"]== true){
			return $radios;
		}else {
			return '<div class="flclear clearfix"></div><label>'.$radio["label"].'</label>
            	'.$radios;
		}
	}

}

?>
