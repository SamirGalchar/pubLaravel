<?php

namespace App\Models;

use App\Library\form_field\Fields;
use App\Library\Globalfunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','sort_description','price','description','validity'];

    public function getPageHTML($id,$action){
        $info = Model::where('id',$id)->first();

        $html='';

        $html.= Fields::textBox(array("label"=>"Name:<span class='red'>*</span>","name"=>"name","value"=>(isset($info["name"])?current(Globalfunction::get_contentFilter($info["name"])):''),"action"=>$action));

        $html.= Fields::textAreaEditor(array("label"=>"Sort Description:<span class='red'>*</span>","name"=>"sort_description","value"=>(isset($info["sort_description"])?$info["sort_description"]:''),"action"=>$action));

        $html.= Fields::textBox(array("label"=>"Price:<span class='red'>*</span>","name"=>"price","value"=>$info["price"] ?? '',"action"=>$action));

        $html .= Fields::textAreaEditor(array("label"=>"Description:<span class='red'>*</span>","name"=>"description","value"=>$info["description"] ?? '',"action"=>$action,"extraAtt"=>'maxlength="7000"'));

        $html.= Fields::textBox(array("label"=>"Validity:<span class='red'>*</span>","name"=>"validity","value"=>$info["validity"] ?? '',"action"=>$action));

        return $html;
    }

    public function getSortDescriptionAttribute($value){
        return str_replace(['<p>', '</p>'], '', $value);
    }

    public function getDescriptionAttribute($value){
        return str_replace(['<p>', '</p>'], '', $value);
    }

    public function getNameAttribute($value){
        return ucfirst($value);
    }
}
