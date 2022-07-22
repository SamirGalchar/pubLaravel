<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Video;
use App\Models\Subscriber;
use Carbon\Carbon;

class VideoController extends Controller{
    
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index(Request $request){
        
        $user = Subscriber::where(['user_id'=>Auth::user()->id,'status'=>'active'])->get()->toArray();
        $carbon = new Carbon();
        $weeksDiff = $carbon->diffInWeeks($user[0]['subscribed_at']);
        $weeksDiff = $weeksDiff + 1;
        
        $videos = Video::where(['status'=>'Active','free_trail'=>'No'])
                    ->limit($weeksDiff)
                    ->orderBy('phase','asc')->orderBy('id','asc')
                    ->get()->toArray();
        
        return view('videos.index', compact('videos'));
        
    }
    
    public function freeTrail(Request $request){
        
        $videos = Video::where(['status'=>'Active','free_trail'=>'Yes'])->get()->toArray();
        return view('videos.free-trail', compact('videos'));
        
    }
    
}
