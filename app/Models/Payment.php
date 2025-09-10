<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'type','party_id','invoice_id','invoice_no','payment_date','amount','method','description'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // helper relations (use depending on type)
    public function saleInvoice()
    {
        return $this->belongsTo(\App\Models\SalesInvoice::class, 'invoice_id');
    }

    public function purchase()
    {
        return $this->belongsTo(\App\Models\Purchase::class, 'invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Buyer::class, 'party_id');
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\RawSupplier::class, 'party_id');
    }
}
