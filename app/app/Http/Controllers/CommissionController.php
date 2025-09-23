<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CommissionController extends Controller
{
    public function importarComissoes(Request $request)
    {
//        $request->validate([
//            'file' => 'required|mimes:csv,xlsx,xls',
//            'mes' => 'required|numeric|min:1|max:12',
//            'ano' => 'required|numeric|min:2000',
//        ]);


        // Apagar comissões do mesmo mês/ano para evitar duplicidade
        \App\Models\Commission::where('mes', $request->mes)->where('ano', $request->ano)->delete();

        Excel::import(new \App\Imports\CommissionsImport($request->mes, $request->ano), $request->file('file'));

        return back()->with('status', 'Comissões importadas com sucesso.');
    }

    public function showForm()
    {
        return view('commission.import');
    }
}
