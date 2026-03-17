<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    /** @use HasFactory<\Database\Factories\FeedbackFactory> */
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'rating',
        'comments'
    ];

    protected $table = 'feedback';


    /** Gets sale associated with feedback */
    public function sale():BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
 
    /** Get the user (cashier/customer) who gave the feedback */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
