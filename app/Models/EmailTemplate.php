<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Library\form_field\Fields;
use App\Library\Globalfunction;

class EmailTemplate extends Model {
    use HasFactory;
    
    protected $fillable = [
        'emailKey', 'description', 'subject', 'templates',
    ];
    
    public function getPageHTML($id,$action){
        $info = Model::where('id',$id)->first();
        
        $html='';

        $html.=Fields::textBox(array("label"=>"Subject:<span class='red'>*</span>","name"=>"subject","class"=>"","value"=>$info["subject"],"action"=>$action));
        $html .= Fields::textAreaEditor(array("label"=>"Template:<span class='red'>*</span>","name"=>"templates","class"=>"col-sm-8","value"=>(isset($info["templates"])?current(Globalfunction::get_contentFilter($info["templates"])):''),"action"=>$action,"div_id"=>'editor-div'));

//$html.=Fields::textBox(array("onlyField"=>true,"name"=>"ID","value"=>isset($info["id"])?base64_encode($info["id"]):'',"action"=>$action,"inputType"=>"hidden"));

        return $html;
    }
    
}
