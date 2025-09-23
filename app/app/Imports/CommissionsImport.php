<?php
namespace App\Imports;

use App\Models\Commission;
use App\Models\Modality;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class CommissionsImport implements ToCollection
{

    protected $mes;
    protected $ano;

    public function __construct($mes, $ano)
    {
        $this->mes = $mes;
        $this->ano = $ano;
    }
    public function collection(Collection $rows)
    {
        $header = true;

        foreach ($rows as $row) {
            if ($header) {
                $header = false;
                continue;
            }

            $teacherName = trim($row[0]);
            $modalityName = trim($row[1]);
            $qtdMatriculas = (int) $row[2];
            $qtdSessoes = (int) $row[3];
            $valorComissao = $this->convertToDecimal($row[4]);


            if ($valorComissao <= 0) continue;

            $modality = Modality::where('name', trim($modalityName))->first();

            if (!$modality) {
                // Se a modalidade não existir, vamos criar uma nova
                $modality = Modality::create(['name' => trim($modalityName),
                    'desciption'=>trim($modalityName),
                    'created_by'=> Auth::user()->id,
                    'updated_by'=>Auth::user()->id]);
            }

            Commission::create([
                'modality_id'    => $modality->id,
                'professor_nome' => $teacherName,
                'valor_comissao' => $valorComissao,
                'qtd_sessoes'    => $qtdSessoes,
                'qtd_matriculas' => $qtdMatriculas,
                'mes'            => $this->mes,
                'ano'            => $this->ano,
            ]);

//            $table->string('professor_nome'); // ou foreignId se tiver relação
//            $table->integer('qtd_matriculas');
//            $table->integer('qtd_sessoes');
//            $table->decimal('valor_comissao', 10, 2);
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
