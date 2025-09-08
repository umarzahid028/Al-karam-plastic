<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_invoice_id',
        'product_id',
        'qty',
        'price',
        'total',
    ];

    // Item belongs to invoice
    public function invoice()
    {
        return $this->belongsTo(CustomerInvoice::class, 'customer_invoice_id');
    }

    // Item belongs to a product (raw material)
    public function product()
    {
        return $this->belongsTo(RawMaterial::class, 'product_id');
    }
}
