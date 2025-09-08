<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_no',
        'expense_category',
        'expense_type',
        'vendor',
        'payment_method',
        'amount',
        'expense_date',
        'reference_no',
        'attachment',
        'approved_by',
        'salesperson',
        'description',
    ];
}
