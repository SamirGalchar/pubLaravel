<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Membership;

class PlanController extends Controller {
    
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index(Request $request){
        $plans = Membership::all()->toArray();
        return view('plans.index', compact('plans'));
    }
    
}
