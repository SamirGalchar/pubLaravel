<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Video;
use App\Library\Globalfunction;
use App\DataTables\VideoDataTable;
use App\Http\Requests\Videos\StoreVideoRequest;
use App\Http\Requests\Videos\UpdateVideoRequest;

class VideoController extends Controller
{
    
    function __construct(){
        $this->video = new Video();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VideoDataTable $dataTable){
        return $dataTable->render('admin.videos.listing');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $action = 'add';
        $html = $this->video->getPageHTML(0,$action);
        $formParam = array('actionUrl'=>route('admin.videos.store'),'cancelUrl'=>route('admin.videos.index'),'sbtName'=>'btnpage','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML(ucfirst($action).' Video',$html,$formParam);
        return view('admin.videos.form',compact('htmlContent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVideoRequest $request) {
        $formData = ["name"=>$request->name,"link"=>$request->link,"description"=>$request->description,"free_trail"=>$request->free_trail,"phase"=>$request->phase,"status"=>$request->status];
        Video::create($formData);
        session()->flash('success',__('Video Saved successfully'));
        return redirect()->route('admin.videos.index');
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
        $html = $this->video->getPageHTML($id,$action);
        $formParam = array('actionUrl'=>route('admin.videos.update',$id),'cancelUrl'=>route('admin.videos.index'),'sbtName'=>'btnpage','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML(ucfirst($action).' Video',$html,$formParam);
        return view('admin.videos.form',compact('htmlContent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVideoRequest $request, $id)
    {
        $formData = ["name"=>$request->name,"link"=>$request->link,"description"=>$request->description,"free_trail"=>$request->free_trail,"phase"=>$request->phase,"status"=>$request->status];
        $video = Video::find($id);
        $video->update($formData);
        session()->flash('success',__('Video Updated successfully'));
        return redirect()->route('admin.videos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Video::find($id)->delete();
        return response()->json(['success'=>'Video deleted successfully.']);
    }
}
