<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'party_type',
        'ref_type',
        'invoice_no',
        'invoice_date',
        'description',
        'debit',
        'credit',
    ];

    // Optional: relation to party (supplier, customer)
    public function party()
    {
        return $this->morphTo();
    }
}
