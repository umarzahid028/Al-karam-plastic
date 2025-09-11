<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'product_name',
        'product_group',
        'unit',
        'sale_price',
        'cost_price',
        'size',
        'packing_sqr',
        'pieces_per_bundle',
        'weight',
    ];
    public function logs()
    {
        return $this->hasMany(RawStockLog::class, 'rawpro_id', 'id');
    }
    // Product.php (model)
public function rawStocks()
{
    return $this->hasMany(RawStock::class, 'rawpro_id');
}
public function salesItems()
    {
        return $this->hasMany(SalesInvoiceItem::class, 'product_id');
    }
public function getCurrentStockAttribute()
{
    return $this->rawStocks->sum('quantity_in') - $this->rawStocks->sum('quantity_out');
}

    
}

