<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Library\Globalfunction;
use App\Models\ConfigEmail;
use App\Http\Requests\Admin\UpdateConfigEmailRequest;

class ConfigEmailController extends Controller {
    
    function __construct() {
        $this->ConfigEmail = new ConfigEmail();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $html = $this->ConfigEmail->getEmailHTML($id,$action);
        $formParam = array('actionUrl'=>route('admin.configemail.update',$id),'cancelUrl'=>route('admin.dashboard'),'sbtName'=>'btnpage','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML(ucfirst($action).' Config Email',$html,$formParam);
        return view('admin.configemail.form',compact('htmlContent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConfigEmailRequest $request, $id)
    {
        
        $formData = ["fromName"=>$request->fromName,
            "smtpHost"=>$request->smtpHost,
            "smtpPort"=>$request->smtpPort,
            "smtpEmail"=>$request->smtpEmail,
            "smtpPass"=>$request->smtpPass,
            "adminEmail"=>$request->adminEmail,
            "notificationEmail"=>$request->notificationEmail];

        $row = ConfigEmail::find(1);
        $row->update($formData);

        session()->flash('success',__('Email Config Updated successfully'));

        return redirect()->route('admin.configemail.edit', 1);
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
