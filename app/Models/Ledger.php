<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
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
        'credit'
    ];
}
