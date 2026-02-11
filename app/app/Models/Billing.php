<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
    'nome',
    'cpf',
    'data_venc',
    'data_pago',
    'data_matricula',
    'valor_mes',
    'valor_desconto',
    'valor_com_desconto',
    'valor_pago',
    'modalidade',
    'tipo_matricula',
    'forma_mensalidade',
    'num_recibo',
    'obs',
    'tipo',
    'mes',
    'ano',
    ];

    protected $dates = [
    'data_venc',
    'data_pago',
    'data_matricula',
    ];
}
