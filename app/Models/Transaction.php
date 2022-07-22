<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    
    use HasFactory;
    
    protected $fillable = ['user_id','plan_id','amount','transaction_id','purchased_at','expire_at','status'];
    
}
