<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProposalsDataTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\User;
use App\Models\ProposalImages;
use App\Models\SimilarSale;
use App\Models\CaseStudy;

class ProposalsController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProposalsDataTable $dataTable) {
        return $dataTable->render('admin.proposals.listing');
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
        $info = Proposal::find($id);
        if($info):
            
            $info = $info->toArray();        
            $user = User::find($info['user_id']);
            $info['user_name'] = ($user->name) ? $user->name : ""; 
            
            $propopsalImages = ProposalImages::select('name')->where(['pro_id'=>$info['id']])->get();
            $propopsalImages = ($propopsalImages) ? $propopsalImages->toArray(): [];
            
            
            $sid = ($info['similar_sales']) ? explode(',', $info['similar_sales']) : [];
            $sSalesImages = [];    
            $i = 0;
            foreach($sid as $rows):                
                $row = SimilarSale::select('name','image_name')->where(['id'=>$rows])->get();
                $row = ($row) ? $row->toArray(): [];                
                $path = public_path('uploads/similar-sales/');
                if(isset($row[0]['image_name']) && file_exists($path.$row[0]['image_name'])):
                    $sSalesImages[$i] = $row[0]['image_name'];                
                    $i++;
                endif;
            endforeach;
            
            $csid = ($info['case_study']) ? explode(',', $info['case_study']) : [];
            $cStudyImages = [];    
            $i = 0;
            foreach($csid as $rows):                
                $row = CaseStudy::select('title','image_name')->where(['id'=>$rows])->get();
                $row = ($row) ? $row->toArray(): [];
                $path = public_path('uploads/case-studies/');
                if(isset($row[0]['image_name']) && file_exists($path.$row[0]['image_name'])):
                    $cStudyImages[$i] = $row[0]['image_name'];                
                    $i++;
                endif;                
            endforeach;
            
            return view('admin.proposals.show', compact('info', 'propopsalImages', 'sSalesImages', 'cStudyImages') );
            
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
    public function changeProposalStatus(Request $request){
        
        $id = (isset($request->id)) ? $request->id : "";
        $isActive = (isset($request->status)) ? $request->status : "";
        
        if( !empty($id) && !empty($isActive) ):
            $proposal = Proposal::find($id);
            $proposal->update(['isActive'=>$isActive]);
            echo 'true';
        else:
            echo 'false';
        endif;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Proposal::find($id)->delete();
        return response()->json(['success'=>'Proposal deleted successfully']);
    }
}
