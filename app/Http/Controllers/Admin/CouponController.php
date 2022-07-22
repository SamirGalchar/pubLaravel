<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Coupon;
use App\Library\Globalfunction;
use App\DataTables\CouponsDataTable;
use App\Http\Requests\Coupon\StoreCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use DB;

class CouponController extends Controller {

    function __construct(){
        $this->coupon = new Coupon();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        $search = $validity_type = "";
        
        /*$qry = "SELECT c.* FROM `".DB::getTablePrefix()."coupons` as c";
        $qry .= " LEFT JOIN `".DB::getTablePrefix()."coupon_users` as cu on cu.`coupon_id`=c.`id`";
        $qry .= " LEFT JOIN `".DB::getTablePrefix()."users` as u on cu.`user_id`=u.`id`";
        $qry .= " WHERE 1 ";
        if(isset($request->search) && $request->search != null):
            $search = $request->search;
            $qry .= "AND (u.`name` LIKE '%".$search."%' OR u.`email` LIKE '%".$search."%' OR u.`phone` LIKE '%".$search."%') ";            
        endif;             
        if(isset($request->validity_type) && $request->validity_type != null):
            $validity_type = $request->validity_type;
            $qry .= "AND c.`validity_type`='".$validity_type."'";
        endif;
        
        $qry .= " GROUP BY c.`id` ORDER BY c.id DESC ";
        
        $res = DB::select($qry);
        $coupons = collect($res)->map(function($x) { return (array) $x; })->toArray();*/
        
        $coupons = Coupon::select('coupons.*')
                    ->leftJoin('coupon_users','coupon_users.coupon_id', 'coupons.id')
                    ->leftJoin('users', 'users.id', 'coupon_users.user_id');
        
        if($request->search && $request->search != null):
            $search = $request->search;
            $coupons->where(function ($query) use ($search) {
                $query->orWhere('users.name','like','%'.$search.'%');
                $query->orWhere('users.email','like','%'.$search.'%');
                $query->orWhere('users.phone','like','%'.$search.'%');                
            });
        endif;
        
        if($request->validity_type && $request->validity_type != null):
            $validity_type = $request->validity_type;
            $coupons->where('coupons.validity_type', $validity_type);
        endif;
        
        $coupons = $coupons->groupBy('coupons.id')
                    ->orderBy('id','desc')
                    ->get()->toArray();
        
        
        return view('admin.coupons.listing', compact('coupons', 'search', 'validity_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin.coupons.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCouponRequest $request)
    {
        
        $formData = [
            "coupon_code"=>$request->coupon_code,
            "discount_type"=>$request->discount_type,
            "discount"=>$request->discount,
            "validity_type"=>$request->validity_type,
            "status"=>$request->status,
            "notification_email"=>$request->notification_email,            
        ];
        if($request->validity_type == "limited" && !empty($request->limit_type)){
            $formData['limit_type'] = $request->limit_type;
            if($request->limit_type == "date"){
                $formData['limit_date'] = $request->limit_date;
            } elseif($request->limit_type == "numbers"){
                $formData['limit_numbers'] = $request->limit_numbers;
            }
        }                
        Coupon::create($formData);
        session()->flash('success',__('Coupon Saved successfully'));
        return redirect()->route('admin.coupons.index');
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
        $coupon = Coupon::findOrFail($id)->toArray();
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCouponRequest $request, $id)
    {   
        $formData = [
            "coupon_code"=>$request->coupon_code,
            "discount_type"=>$request->discount_type,
            "discount"=>$request->discount,
            "validity_type"=>$request->validity_type,
            "status"=>$request->status,
            "notification_email"=>$request->notification_email,                
        ];
        if($request->validity_type == "limited" && !empty($request->limit_type)){
            $formData['limit_type'] = $request->limit_type;
            if($request->limit_type == "date"){
                $formData['limit_date'] = $request->limit_date;
                $formData['limit_numbers'] = NULL;
            } elseif($request->limit_type == "numbers"){
                $formData['limit_numbers'] = $request->limit_numbers;
                $formData['limit_date'] = NULL;
            }
        }        
        
        $coupon = Coupon::find($id);
        $coupon->update($formData);

        session()->flash('success',__('Coupon Updated successfully'));
        return redirect()->route('admin.coupons.index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Coupon::find($id)->delete();
        return response()->json(['success'=>'Coupon deleted successfully.']);
    }
    
    public function checkCouponCodeIsDuplicate(Request $request){
        $coupon_code = $request->coupon_code;
        $userInfo["cnt"]= Coupon::where(["coupon_code"=>$coupon_code])->where('id', '!=', $request->id)->get()->count();
        if($userInfo["cnt"]>0){
            $resp=1;
        }else{
            $resp=0;
        }
        echo ($resp==1) ? "false" : "true";
        exit;
    }
    
}
