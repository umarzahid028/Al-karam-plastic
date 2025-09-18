<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawSupplier extends Model
{
    use HasFactory;

    protected $table = 'raw_suppliers';

    protected $fillable = [
        'supplier_code',
        'company_name',
        'name',
        'email',
        'city',
        'contact_no',
        'opening_balance',
        'status',
    ];
}
