<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class BillingSummaryController extends Controller
{
    public function index()
    {
        $pacientes = Patient::select(
            'patients.id',
            'patients.name',
            DB::raw('COUNT(DISTINCT authorizations.id) as total_autorizacoes'),
            DB::raw('COALESCE(SUM(invoices.amount), 0) as total_cobrado'),
            DB::raw("COALESCE(SUM(CASE WHEN invoices.status = 'pago' THEN invoices.amount ELSE 0 END), 0) as total_recebido"),
            DB::raw("COALESCE(SUM(CASE WHEN invoices.status IN ('pendente','enviado') THEN invoices.amount ELSE 0 END), 0) as total_pendente")
        )
            ->join('authorizations', 'authorizations.patient_id', '=', 'patients.id')
            ->join('invoices', 'invoices.authorization_id', '=', 'authorizations.id')
            ->groupBy('patients.id', 'patients.name')
            ->orderBy('patients.name')
            ->get();

        return view('billing.summary', compact('pacientes'));
    }


}
