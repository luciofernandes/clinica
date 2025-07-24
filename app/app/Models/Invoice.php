<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Invoice extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'status',
        'invoice_number',
        'amount',
        'issue_date',
        'payment_date',
        'authorization_id',
        'created_by',
        'updated_by',
    ];

    public function authorization()
    {
        return $this->belongsTo(\App\Models\Authorization::class);
    }

}
