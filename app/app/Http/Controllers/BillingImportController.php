<?php

namespace App\Http\Controllers;

use App\Imports\BillingImport;
use App\Http\Requests\BillingImportRequest;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class BillingImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // apenas usuários autenticados
    }

    public function showForm()
    {
        abort_unless(Auth::user()?->is_admin, 403);

        return view('billing.import');
    }

    public function import(BillingImportRequest $request)
    {
        try {
//            $request->validate([
//                'file' => 'required|file|mimes:xlsx,csv,xls',
//            ]);

            $config = [
                'delimiter' => ';',
                'input_encoding' => 'ISO-8859-1',
            ];

            $file = $request->file('file');
            Excel::import(new BillingImport, $file->getRealPath(), null, \Maatwebsite\Excel\Excel::CSV, $config);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            exit();
        }


        return back()->with('success', 'Importação concluída com sucesso!');
    }
}
