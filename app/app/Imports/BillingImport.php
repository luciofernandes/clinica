<?php
namespace App\Imports;

use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BillingImport implements ToCollection, WithHeadingRow
{
    protected $importedMonth;
    protected $importedYear;
    protected $alreadyCleaned = false;
    protected $tipo;

    public function __construct(string $tipo)
    {
        $this->tipo = $tipo;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $valorPago = $this->convertToDecimal($row['valor_pago']);

            // Para tipos finalizada/cancelada só importamos registros pagos
            if (in_array($this->tipo, ['finalizada', 'cancelada'], true)) {
                if (is_null($valorPago) || $valorPago <= 0) {
                    continue;
                }
            }

            if (!$this->alreadyCleaned) {
                // Extrai a data de vencimento
                $vencimento = Carbon::createFromFormat('d/m/Y', $row['data_venc']);
                $this->importedMonth = $vencimento->month;
                $this->importedYear = $vencimento->year;

                // Remove dados do mesmo mês/ano
                Billing::whereMonth('data_venc', $this->importedMonth)
                    ->whereYear('data_venc', $this->importedYear)
                    ->where('tipo', $this->tipo)
                    ->delete();

                $this->alreadyCleaned = true;
            }
            $dataVenc = Carbon::createFromFormat('d/m/Y', $row['data_venc']);
            $mes = $dataVenc->month; // Extrai o mês
            $ano = $dataVenc->year;
            Billing::create([
                'nome'               => $row['nome'],
                'cpf'                => $row['cpf'] ?? null,
                'data_venc'          => $dataVenc,
                'data_pago'          => $row['data_pago'] ? Carbon::createFromFormat('d/m/Y', $row['data_pago']) : null,
                'data_matricula'     => $row['data_matricula'] ? Carbon::createFromFormat('d/m/Y', $row['data_matricula']) : null,
                'valor_mes'          => $this->convertToDecimal($row['valor_mes']),
                'valor_desconto'     => $this->convertToDecimal($row['valor_desconto']),
                'valor_com_desconto' => $this->convertToDecimal($row['valor_com_desconto']),
                'valor_pago'         => $valorPago,
                'modalidade'         => $row['modalidade'],
                'tipo_matricula'     => $row['tipo_matricula'],
                'forma_mensalidade'  => $row['forma_mensalidade'],
                'num_recibo'         => $row['num_recibo'],
                'obs'                => $row['obs'] ?? null,
                'tipo'               => $this->tipo,
                'mes'                => $mes,
                'ano'                => $ano,
            ]);
        }
    }

    private function convertToDecimal($value)
    {
        if (is_null($value)) {
            return null;
        }

        // Substitui vírgulas por pontos e converte para float
        return (float) str_replace(['.', ','], ['', '.'], $value);
    }
}
