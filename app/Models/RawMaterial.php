<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_code',
        'material_name',
        'purchase_price',
        'unit',
        'packing',
        'stocks',      // stocks field added
        'store_id',
    ];

    // Relation with store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'material_id');
    }
}
