<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_code',
        'supplier_id',
        'purchase_date',
        'invoice_no',
        'invoice_date',
        'payment_method',
        'total_amount',
        'status',
        'description',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
 

public function returns()
{
    return $this->hasMany(PurchaseReturn::class);
}

}
