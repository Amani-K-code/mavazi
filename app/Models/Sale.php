<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;

    protected $fillable = [
        'receipt_no',
        'customer_name',
        'child_name',
        'payment_method',
        'reference_id',
        'total_amount',
        'status',
        'user_id',
        'expiry_date'
    ];
    
    
    public function items(){
        return $this->hasMany(SaleItem::class);
    }

    public function feedback(){
        return $this->hasOne(Feedback::class);
    }


    public function getDaysToExpiryAttribute()
    {
        if(!$this->expiry_date) return null;

        $expiry = Carbon::parse($this->expiry_date);
        $now = Carbon::now();

        if($now->greaterThan($expiry))
            return 'EXPIRED';

        return $now->diffInDays($expiry) . ' days left';
    }

}