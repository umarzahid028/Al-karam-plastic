<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_invoice_id',
        'product_id',
        'qty',
        'price',
        'total',
    ];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); 
    }
  
    public function items()
{
    return $this->hasMany(SalesInvoiceItem::class, 'sales_invoice_id');
}

public function returns()
{
    return $this->hasMany(SalesReturn::class, 'sales_invoice_id');
}

public function buyer()
{
    return $this->belongsTo(RawSupplier::class, 'buyer_id'); 
}

}
