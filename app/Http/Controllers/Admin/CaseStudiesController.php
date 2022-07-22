<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\CaseStudiesDataTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Proposal;
use App\Models\CaseStudy;

class CaseStudiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CaseStudiesDataTable $dataTable) {
        return $dataTable->render('admin.case-studies.listing');
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
    public function show($id) {
        $info = CaseStudy::find($id);
        if($info):
            $info = $info->toArray();
            $user = User::find($info['user_id']);
            $info['user_name'] = ($user->name) ? $user->name : ""; 
            return view('admin.case-studies.show', compact('info'));
        else:
            return redirect()->route('admin.proposals.index');
        endif;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CaseStudy::find($id)->delete();
        return response()->json(['success'=>'Case study deleted successfully']);
    }
}
