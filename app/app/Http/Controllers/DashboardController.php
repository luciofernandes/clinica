<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function financeiro()
    {
        $totalCobrado = Invoice::sum('amount');

        $totalRecebido = Invoice::where('status', 'recebido')->sum('amount');

        $totalPendente = Invoice::whereIn('status', ['pendente', 'enviado', 'pago'])->sum('amount');

        $totalNotas = Invoice::count();

        $totalPacientesComCobrança = DB::table('patients')
            ->join('authorizations', 'patients.id', '=', 'authorizations.patient_id')
            ->join('invoices', 'authorizations.id', '=', 'invoices.authorization_id')
            ->distinct('patients.id')
            ->count('patients.id');

        return view('dashboard.financeiro', compact(
            'totalCobrado',
            'totalRecebido',
            'totalPendente',
            'totalNotas',
            'totalPacientesComCobrança'
        ));
    }
}
