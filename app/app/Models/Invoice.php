<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'authorization_id',
        'invoice_number',
        'invoice_date',
        'amount',
        'created_by',
        'updated_by',
    ];
    public function authorization()
    {
        return $this->belongsTo(\App\Models\Authorization::class);
    }

}
