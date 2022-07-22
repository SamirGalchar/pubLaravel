<?php

namespace App\Models;

use App\Library\Globalfunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Library\form_field\Fields;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','heading','short_description','long_description','page_slug','meta_title','meta_keyword','meta_description','meta_author'
    ];

    public function getPageHTML($id,$action){

        $info = Model::where('id',$id)->first();

        $html='';

        $html.= Fields::textBox(array("label"=>"Page Name:<span class='red'>*</span>","name"=>"short_description","class"=>"","value"=>(isset($info["short_description"])?current(Globalfunction::get_contentFilter($info["short_description"])):''),"action"=>$action));

        $html .= Fields::textBox(array("label"=>"Page Title:<span class='red'>*</span>","name"=>"title","class"=>"","value"=>(isset($info["title"])?$info["title"]:''),"action"=>$action));

        $html.= Fields::textBox(array("label"=>"Page Heading:<span class='red'>*</span>","name"=>"heading","class"=>"","value"=>(isset($info["heading"])?$info["heading"]:''),"action"=>$action));

        $html .= Fields::textAreaEditor(array("label"=>"Page Description:<span class='red'>*</span>","name"=>"long_description","class"=>"col-sm-8","value"=>(isset($info["long_description"])?current(Globalfunction::get_contentFilter($info["long_description"])):''),"action"=>$action,"div_id"=>'editor-div'));

        $html .= Fields::textBox(array("label"=>"Meta Title:","name"=>"meta_title","class"=>"","value"=>(isset($info["meta_title"])?$info["meta_title"]:''),"action"=>$action));

        $html .= Fields::textBox(array("label"=>"Meta Author:","name"=>"meta_author","class"=>"","value"=>(isset($info["meta_author"])?$info["meta_author"]:''),"action"=>$action));

        $html .= Fields::textBox(array("label"=>"Meta Keyword:","name"=>"meta_keyword","class"=>"","value"=>(isset($info["meta_keyword"])?$info["meta_keyword"]:''),"action"=>$action));

        $html .= Fields::textBox(array("label"=>"Meta Description:","name"=>"meta_description","class"=>"","value"=>(isset($info["meta_description"])?$info["meta_description"]:''),"action"=>$action));

        $html .= Fields::textBox(array("onlyField"=>true,"name"=>"ID","value"=>(isset($info["id"])?$info["id"]:''),"action"=>$action,"inputType"=>"hidden"));

        return $html;
    }
}
