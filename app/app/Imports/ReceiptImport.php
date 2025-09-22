<?php

namespace App\Imports;


use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReceiptImport  implements ToCollection, WithHeadingRow
{
    protected $alreadyCleaned = false;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $dataPagto = Carbon::createFromFormat('d/m/Y', $row['data_pagorec']);

            $mes = $dataPagto->month; // Extrai o mês
            $ano = $dataPagto->year;

            if (!$this->alreadyCleaned) {
                // Extrai a data de pagamento
                $mesAno = $dataPagto->format('Y-m');

                // Remove dados do mesmo mês/ano
                Receipt::where('ano', $ano)
                    ->where('mes', $mes)
                    ->delete();

                $this->alreadyCleaned = true;
            }

            Receipt::create([
                'mes_ref'             => $row['mes_ref'],
                'ano_ref'             => $row['ano_ref'],
                'descricao'           => $row['descricao'],
                'data'                => $this->parseDate($row['data']),
                'data_pagorec'        => $dataPagto,
                'operacao'            => $row['operacao'],
                'deb_cred'            => $row['deb_cred'],
                'valor'               => str_replace(['.', ','], ['', '.'], $row['valor']),
                'valor_pagorec'       => str_replace(['.', ','], ['', '.'], $row['valor_pagorec']),
                'num_recibo'          => $row['num_recibo'],
                'pago'                => $row['pago'],
                'forma_pagamento'     => $row['forma_pagamento'],
                'cliente_nome'        => $row['cliente_nome'],
                'cliente_cpf'         => $row['cliente_cpf'],
                'fornecedor_nome'     => $row['fornecedor_nome'],
                'fornecedor_numdoc'   => $row['fornecedor_numdoc'],
                'nome_professor'      => $row['nome_professor'],
                'obs'                 => $row['obs'],
                'mes'                 => $mes,
                'ano'                 => $ano,
            ]);
        }

    }

    public function parseDate($value)
    {
        try {
            return Carbon::createFromFormat('d/m/Y', $value);
        } catch (\Exception $e) {
            return null;
        }
    }
    private function convertToDecimal($value)
    {
        if (is_null($value)) {
            return null;
        }

        // Substitui vírgulas por pontos e converte para float
        return (float) str_replace(',', '.', $value);
    }
}
