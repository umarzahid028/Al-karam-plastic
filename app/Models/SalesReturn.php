<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_invoice_id',
        'return_date',
        'total_return_amount',
        'remarks',
    ];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class, 'sales_return_id');
    }
}
