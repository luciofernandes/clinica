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
        'matricula_id',
    ];
    public function modality()
    {
        return $this->belongsTo(\App\Models\Modality::class);
    }

    public function getMatriculaUrlAttribute()
    {
        return 'https://painel.softwarepilates.com.br/Matricula/Matricula.aspx?c=' . $this->matricula_id;
    }


    public function invoices()
    {
        return $this->belongsToMany(Invoice::class)
            ->withTimestamps();
    }
    public function isBilled()
    {
        return $this->invoices()->exists();
    }


}
