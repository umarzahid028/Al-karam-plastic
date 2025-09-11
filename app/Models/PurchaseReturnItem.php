<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_return_id',
        'purchase_item_id',
        'quantity',
        'price',
        'subtotal',
    ];

    // Relation with PurchaseReturn
    public function return()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    // Relation with PurchaseItem
    public function purchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class, 'purchase_item_id');
    }
}
