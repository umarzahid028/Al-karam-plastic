<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_no',
        'issue_date',
        'issued_by',
        'issued_to',
        'approved_by',
        'remarks',
    ];

    // public function items()
    // {
    //     return $this->hasMany(RawMaterialIssueItem::class, 'issue_id');
    // }
    // App\Models\RawMaterialIssue.php
public function items()
{
    return $this->hasMany(\App\Models\RawMaterialIssueItem::class, 'issue_id');
}

}
