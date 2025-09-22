<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'mes_ref',
        'ano_ref',
        'descricao',
        'data',
        'data_pagorec',
        'operacao',
        'deb_cred',
        'valor',
        'valor_pagorec',
        'num_recibo',
        'pago',
        'forma_pagamento',
        'cliente_nome',
        'cliente_cpf',
        'fornecedor_nome',
        'fornecedor_numdoc',
        'nome_professor',
        'obs',
        'mes',
        'ano',
    ];
}
