<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'modality_id',
        'professor_nome',
        'qtd_matriculas',
        'qtd_sessoes',
        'valor_comissao',
        'mes',
        'ano'
    ];
}
