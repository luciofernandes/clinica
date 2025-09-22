<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ReceiptImport;
use Maatwebsite\Excel\Facades\Excel;

class ReceiptController extends Controller
{
    public function import(Request $request)
    {
//        $request->validate([
//            'file' => 'required|file|mimes:csv,xlsx,xls',
//        ]);
//
//        $path = $request->file('file')->getRealPath();
//        $csv = array_map('str_getcsv', file($path));
//        $delimiter = ';';
//        $headers = str_getcsv($csv[0][0], $delimiter);
//        $firstRow = str_getcsv($csv[1][0], $delimiter);
//
//        $index = array_search('DATA_PAGOREC', array_map('strtoupper', $headers));
//        $dateRaw = $firstRow[$index] ?? null;
//
//        if ($dateRaw) {
//            $parsedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dateRaw);
//            \App\Models\Receipt::whereMonth('data_pagorec', $parsedDate->month)
//                ->whereYear('data_pagorec', $parsedDate->year)
//                ->delete();
//        }
        try {
            $config = [
                'delimiter' => ';',
                'input_encoding' => 'ISO-8859-1',
            ];

            $file = $request->file('file');
            Excel::import(
                new ReceiptImport,
                $file->getRealPath(),
                null,
                \Maatwebsite\Excel\Excel::CSV,
                $config
            );
        } catch (\Exception $e) {
            var_dump($e->getMessage()); exit();
            return back()->withErrors(['file' => 'Erro ao validar o arquivo: ' . $e->getMessage()]);
        }


        return back()->with('status', 'Receipts imported successfully.');
    }

    public function showForm()
    {
        return view('receipt.import');
    }

}
