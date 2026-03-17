<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    
    
    public function items(): HasMany
    {
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems(): HasMany
    {
        //Links id of sale to the sale_id column in slae_items table
        return $this->hasMany(SaleItem::class);
    }

}