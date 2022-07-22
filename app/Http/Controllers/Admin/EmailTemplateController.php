<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\UpdateEmailTemplateRequest;

use App\Models\EmailTemplate;
use App\DataTables\EmailTemplateDataTable;
use App\Library\Globalfunction;

class EmailTemplateController extends Controller {
    
    function __construct() {
        $this->EmailTemplate = new EmailTemplate();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EmailTemplateDataTable $dataTable) {
        return $dataTable->render('admin.email-template.listing');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $action = 'edit';
        $html = $this->EmailTemplate->getPageHTML($id,$action);
        $formParam = array('actionUrl'=>route('admin.email-template.update',$id),'cancelUrl'=>route('admin.email-template.index'),'sbtName'=>'btnpage','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML(ucfirst($action).' EmailTemplate',$html,$formParam);
        return view('admin.email-template.form',compact('htmlContent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmailTemplateRequest $request, $id) {
        
        $pageContent=Globalfunction::get_contentFilter(str_replace(array("\r","\n","\r\n"),"",$request->templates));
        
        $formData = ["subject"=>$request->subject, "templates"=>$pageContent[0]];

        $email = EmailTemplate::find($id);
        $email->update($formData);
        session()->flash('success',__('Email Tempate Updated successfully'));

        return redirect()->route('admin.email-template.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
