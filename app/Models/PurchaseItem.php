<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'raw_material_id', // <-- use the correct column name
        'quantity',
        'unit_price',
        'total_price',
        'total',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id'); // match the column
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
