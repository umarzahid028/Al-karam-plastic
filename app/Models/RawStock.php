<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'rawpro_id',
        'quantity_in',
        'quantity_out',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'rawpro_id');
    }

    public function logs()
    {
        return $this->hasMany(RawStockLog::class, 'rawpro_id', 'rawpro_id');
    }

    public function getCurrentStockAttribute()
    {
        return $this->quantity_in - $this->quantity_out;
    }
}
