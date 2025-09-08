<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'invoice_no',
        'invoice_date',
        'total_amount',
        'remarks',
    ];

    // Invoice belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'buyer_id');
    }

    // Invoice has many items
    public function items()
    {
        return $this->hasMany(CustomerInvoiceItem::class, 'customer_invoice_id');
    }
}
