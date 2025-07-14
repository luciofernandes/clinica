<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizationModality extends Model
{
    protected $fillable = [
        'authorization_id',
        'modality_id',
        'quantity_type',
        'quantity',
        'unit_value',
        'total_value',
        'created_by',
        'updated_by',
    ];

}
