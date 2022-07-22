<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model{
    
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'plan_id',
        'stripe_customer_id',
        'subscription_id',
        'checkout_session_id',
        'plan_name',
        'amount',
        'subscribed_at',
        'end_at',
        'next_payment_at',
        'total_paid',
        'status'
    ];
    
}
