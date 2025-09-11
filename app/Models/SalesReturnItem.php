<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_return_id',
        'sales_invoice_item_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function return()
    {
        return $this->belongsTo(SalesReturn::class, 'sales_return_id');
    }

    public function invoiceItem()
    {
        return $this->belongsTo(SalesInvoiceItem::class, 'sales_invoice_item_id');
    }
}
