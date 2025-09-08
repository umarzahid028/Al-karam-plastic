<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers'; // table name

    protected $fillable = [
        'customer_code',
        'name',
        'email',
        'contact_no',
        'address',
        'opening_balance',
        'status',
    ];
    // A customer can have many invoices
    public function invoices()
    {
        return $this->hasMany(CustomerInvoice::class, 'buyer_id');
    }
}
