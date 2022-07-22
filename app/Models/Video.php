<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Library\form_field\Fields;

class Video extends Model {
    
    use HasFactory;
    
    protected $fillable = ['name','link','description','phase','free_trail','status'];
    
    /**
     * Methods for admin
    */
    public function getPageHTML($id,$action){
        $info = Video::where('id',$id)->first();
        $html = '';
        $html.= Fields::textBox(array("label"=>"Name:<span class='red'>*</span>","name"=>"name","value"=>$info['name'] ?? '',"action"=>$action));
        $html.= Fields::textArea(array("label"=>"Video Embaded Url:<span class='red'>*</span>","name"=>"link","class"=>"","value"=>$info['link'] ?? '',"action"=>$action));
        $html.= Fields::textArea(array("label"=>"Description:","name"=>"description","class"=>"","value"=>$info['description'] ?? '',"action"=>$action));
        $html .= Fields::selectBox(array("label"=>"Is it for free trail?:<span class='red'>*</span>","name"=>"free_trail",'id'=>'free_trail',"textField"=>"Select Trail","fromDB"=>false,"selectBoxParam"=>array("defaultText"=>"Select Trail","selectedValue"=>(isset($info["free_trail"]) && !empty($info["free_trail"]))?$info["free_trail"]:"", "isKeyArray"=>true,"arrVal"=>['Yes'=>'Yes','No'=>'No']),"action"=>$action,'class'=>'custom-select select2-demo'));
        $html .= Fields::selectBox(array("label"=>"Phase :<span class='red'>*</span>","name"=>"phase",'id'=>'phase',"textField"=>"Select Phase","fromDB"=>false,"selectBoxParam"=>array("defaultText"=>"Select Phase","selectedValue"=>(isset($info["phase"]) && !empty($info["phase"]))?$info["phase"]:"", "isKeyArray"=>true,"arrVal"=>['1'=>'Phase 1','2'=>'Phase 2','3'=>'Phase 3','4'=>'Phase 4']),"action"=>$action,'class'=>'custom-select select2-demo'));
        $html .= Fields::selectBox(array("label"=>"Status :<span class='red'>*</span>","name"=>"status",'id'=>'status',"textField"=>"Select Option","fromDB"=>false,"selectBoxParam"=>array("defaultText"=>"Select Status","selectedValue"=>(isset($info["status"]) && !empty($info["status"]))?$info["status"]:"", "isKeyArray"=>true,"arrVal"=>['Active'=>'Active','Deactive'=>'Deactive']),"action"=>$action,'class'=>'custom-select select2-demo'));
        return $html;
    }
    
}
