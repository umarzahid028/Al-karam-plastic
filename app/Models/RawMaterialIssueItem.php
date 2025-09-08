<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialIssueItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'rawpro_id',
        'qty',
        'unit',
    ];
// App\Models\RawMaterialIssueItem.php
public function issue()
{
    return $this->belongsTo(\App\Models\RawMaterialIssue::class, 'issue_id');
}


    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rawpro_id');
    }
    
}
