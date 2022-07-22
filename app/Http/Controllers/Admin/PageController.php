<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Page\StorePageRequest;
use App\Http\Requests\Page\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Library\Globalfunction;
use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    function __construct()
    {
        $this->page = new Page();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PageDataTable $dataTable)
    {
        return $dataTable->render('admin.pages.listing');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $action = 'add';
        $html = $this->page->getPageHTML(0,$action);
        $formParam = array('actionUrl'=>route('admin.pages.store'),'cancelUrl'=>route('admin.pages.index'),'sbtName'=>'btnpage','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML(ucfirst($action).' Page',$html,$formParam);
        return view('admin.pages.form',compact('htmlContent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePageRequest $request)
    {
        $pageContent=Globalfunction::get_contentFilter(str_replace(array("\r","\n","\r\n"),"",$request->long_description));

        $slug = Globalfunction::convertIntoSlug($request->title);

        $formData = ["title"=>$request->title,"heading"=>$request->heading,"short_description"=>$request->short_description,"long_description"=>$pageContent[0],'page_slug'=>$slug,"meta_title"=>$request->meta_title,"meta_author"=>$request->meta_author, "meta_keyword"=>$request->meta_keyword, "meta_description"=>$request->meta_description];

        Page::create($formData);

        session()->flash('success',__('Page Saved successfully'));

        return redirect()->route('admin.pages.index');
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
        $html = $this->page->getPageHTML($id,$action);
        $formParam = array('actionUrl'=>route('admin.pages.update',$id),'cancelUrl'=>route('admin.pages.index'),'sbtName'=>'btnpage','action'=>$action);
        $htmlContent = Globalfunction::getAdminFormHTML(ucfirst($action).' Page',$html,$formParam);
        return view('admin.pages.form',compact('htmlContent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePageRequest $request,$id)
    {
        $pageContent=Globalfunction::get_contentFilter(str_replace(array("\r","\n","\r\n"),"",$request->long_description));

        $formData = ["title"=>$request->title,"heading"=>$request->heading,"short_description"=>$request->short_description,"long_description"=>$pageContent[0],"meta_title"=>$request->meta_title,"meta_author"=>$request->meta_author, "meta_keyword"=>$request->meta_keyword, "meta_description"=>$request->meta_description];

        $page = Page::find($id);
        $page->update($formData);

        session()->flash('success',__('Page Updated successfully'));

        return redirect()->route('admin.pages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::find($id)->delete();
        return response()->json(['success'=>'Page deleted successfully.']);
    }
}
