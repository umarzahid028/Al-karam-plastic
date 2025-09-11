<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'return_date',
        'total_return_amount',
        'remarks',
    ];

    // Relation with Purchase
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    // Relation with Return Items
    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }
}
