<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MembershipDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Membership\StoreMembershipRequest;
use App\Http\Requests\Membership\UpdateMembershipRequest;
use App\Library\Globalfunction;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    function __construct()
    {
        $this->membership = new Membership();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MembershipDataTable $dataTable)
    {
        return $dataTable->render('admin.membership.listing');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = 'add';
        $html = $this->membership->getPageHTML(0,$action);
        $formParam = array('actionUrl'=>route('admin.membership.store'),'cancelUrl'=>route('admin.membership.index'),'sbtName'=>'btnpage','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML(ucfirst($action).' Membership',$html,$formParam);
        return view('admin.membership.form',compact('htmlContent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMembershipRequest $request)
    {
        $pageContent=Globalfunction::get_contentFilter(str_replace(array("\r","\n","\r\n"),"",$request->description));
        $sort_description=Globalfunction::get_contentFilter(str_replace(array("\r","\n","\r\n"),"",$request->sort_description));

        $formData = ["name"=>$request->name,"sort_description"=>$sort_description[0],"price"=>$request->price,"description"=>$pageContent[0],'validity'=>$request->validity];

        Membership::create($formData);

        session()->flash('success',__('Plan saved successfully'));

        return redirect()->route('admin.membership.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        $html = $this->membership->getPageHTML($id,$action);
        $formParam = array('actionUrl'=>route('admin.membership.update',$id),'cancelUrl'=>route('admin.membership.index'),'sbtName'=>'btnpage','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML(ucfirst($action).' Subscription',$html,$formParam);
        return view('admin.membership.form',compact('htmlContent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMembershipRequest $request, $id)
    {
        $pageContent=Globalfunction::get_contentFilter(str_replace(array("\r","\n","\r\n"),"",$request->description));

        $formData = ["name"=>$request->name,"sort_description"=>$request->sort_description,"price"=>$request->price,"description"=>$pageContent[0],'validity'=>$request->validity];

        $page = Membership::find($id);
        $page->update($formData);

        session()->flash('success',__('Plan Updated successfully'));

        return redirect()->route('admin.membership.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Membership::find($id)->delete();
        return response()->json(['success'=>'Plan deleted successfully.']);
    }
}
