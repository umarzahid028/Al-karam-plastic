<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawStockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'rawpro_id',
        'trans_type',
        'qty',
        'price',
        'total_amount',
        'remarks',
        'user_id',
        'trans_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'rawpro_id');
    }
}
