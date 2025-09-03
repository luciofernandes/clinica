<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use League\Csv\Reader;
use Illuminate\Support\Facades\Validator;

class PatientImportController extends Controller
{
    public function index()
    {
        return view('patients.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $content = file_get_contents($file->getPathname());
        $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'WINDOWS-1252'], true);

        if ($encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
            file_put_contents($file->getPathname(), $content);
        }

        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $importados = 0;
        $ignorados = 0;
        $duplicados = 0;

        foreach ($csv->getRecords() as $record) {
            $cpf = preg_replace('/[^0-9]/', '', $record['CPF'] ?? '');
            $name = $record['NOME'] ?? null;

//            if (!$this->validaCpf($cpf) || !$name) {
//                $ignorados++;
//                continue;
//            }

//            if (Patient::where('cpf', $cpf)->exists()) {
//                $duplicados++;
//                continue;
//            }


            if (Patient::where('name', $name)->exists()) {
                $duplicados++;
                continue;
            }

            Patient::create([
                'name' => $name,
                'cpf' => $cpf,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $importados++;
        }

        return redirect()->back()->with('status', "Importação finalizada. Importados: $importados, Duplicados: $duplicados, Ignorados: $ignorados");
    }

    private function validaCpf($cpf)
    {
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }
        return true;
    }
}
