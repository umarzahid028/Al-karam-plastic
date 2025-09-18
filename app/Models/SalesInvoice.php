<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'salesperson_id',
        'invoice_no',
        'invoice_date',
        'total_amount',
        'paid_amount',
        'status',
        'remarks',
    ];

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
        // ya Buyer model agar alag banega
    }
    
}
