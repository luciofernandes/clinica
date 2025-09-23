<?php

namespace App\Http\Controllers;

use App\Imports\BillingImport;
use App\Http\Requests\BillingImportRequest;
use App\Models\HealthPlan;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class BillingImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // apenas usuários autenticados
    }

    public function index()
    {

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

       // $sorAuthorizationt = $request->input('sort', 'authorization_date');
        //$direction = $request->input('direction', 'desc');

        $query = \App\Models\Authorization::with(['modalities', 'invoices', 'patient', 'healthPlan']);

        // 🔍 Filtro por paciente
        if ($request->filled('paciente')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->paciente . '%');
            });
        }

        // 📅 Filtro por numero da autorização
        if ($request->filled('numero')) {
            $query->where('authorization_number', 'like', '%' . $request->numero . '%');
        }

        // 🏥 Filtro por plano de saúde
        if ($request->filled('plano')) {
            $query->whereHas('healthPlan', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->plano . '%');
            });
        }

        // ⚙️ Ordenação
        if (in_array($sort, ['authorization_date', 'authorization_expiration_date', 'estimated_end_date'])) {
            $query->orderBy($sort, $direction);
        } elseif ($sort === 'plan') {
            $query->join('health_plans', 'authorizations.health_plan_id', '=', 'health_plans.id')
                ->orderBy('health_plans.name', $direction)
                ->select('authorizations.*'); // evitar problemas de colisão
        }

        // Executa a query e carrega as relações
        $results = $query->get();

        // 🧠 Filtro por billing_status (após carregar os dados)
        $filtered = $results->filter(function ($auth) use ($statusFiltro) {
            return !$statusFiltro || $auth->billing_status === $statusFiltro;
        });

        // Paginação
        $billings = new LengthAwarePaginator(
            $filtered->forPage($currentPage, $perPage),
            $filtered->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );



        return view('billing.index', compact('billings', ));
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
