<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatePass extends Model
{
    protected $table = 'gate_passes'; // Table ka naam

    protected $fillable = [
        'gate_pass_no',
        'invoice_id',
        'user_id',
        'status',
        'is_duplicate',
        'qty',
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_id');
    }
}
