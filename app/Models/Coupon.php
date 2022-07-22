<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model {
    
    use HasFactory;
    
    protected $guarded = ['id'];
    
    /**
     * Get the created_at date format.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d');
    }  
    
    
    public function checkIsValid($coupon_code){
        
        $coupon = Coupon::where(['coupon_code'=>$coupon_code,'status'=>'active'])->first();
        
        if($coupon):            
            if($coupon->validity_type == "limited"):                    
                if($coupon->limit_type == "numbers" && $coupon->limit_numbers > 0):
                    return $coupon;
                elseif($coupon->limit_type == "date" && $coupon->limit_date >= Carbon::yesterday()):
                    return $coupon;
                endif;                
            elseif($coupon->validity_type == "onetime"):                                
                return $coupon;            
            elseif($coupon->validity_type == "unlimited"):                                
                return $coupon;            
            else:                
                return false;            
            endif;              
        else:
            return false;
        endif;
        
        
    }
    
}
