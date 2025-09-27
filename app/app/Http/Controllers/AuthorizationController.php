<?php

namespace App\Http\Controllers;

use App\Models\AuthorizationModality;
use App\Models\HealthPlan;
use App\Models\Modality;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Authorization;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Event\Exception;

class AuthorizationController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = \App\Models\Patient::orderBy('name')->get();
        $modalities = \App\Models\Modality::orderBy('name')->get();
        $healthPlans = \App\Models\HealthPlan::orderBy('name')->get();
        return view('authorizations.create', compact('patients', 'modalities', 'healthPlans'));

    }

    public function store(Request $request)
    {
//        try {
            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'health_plan_id' => 'required|exists:health_plans,id',
                'authorization_number' => 'nullable|unique:authorizations',
                'modalities.*.modality_id' => 'required|exists:modalities,id',
                'modalities.*.quantity_type' => 'required',
                'modalities.*.quantity' => 'required|integer',
                'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $authorization = \App\Models\Authorization::create([
                'patient_id' => $request->patient_id,
                'authorization_number' => $request->authorization_number,
                'estimated_end_date' => $request->estimated_end_date,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'authorization_date' => $request->authorization_date,
                'authorization_expiration_date' => $request->authorization_expiration_date,
                'health_plan_id' => $request->health_plan_id ?? null,
                'observation' => $request->observation, // <- aqui
            ]);

            foreach ($request->modalities as $modality) {
                $authorization->modalities()->create([
                    'modality_id' => $modality['modality_id'],
                    'quantity_type' => $modality['quantity_type'],
                    'quantity' => $modality['quantity'],
                    'unit_value' => $modality['unit_value'] ?? null,
                    'total_value' => $modality['total_value'] ?? null,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'last_session_date' => $modality['last_session_date'] ?? null,
                ]);
            }

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('authorizations', 'public');
                    $authorization->files()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }
            }
        //}
//        catch (\Exception $e) {
//            var_dump( $e->getMessage());
//            exit();
//        }

        return redirect()->route('autorizacoes.index')->with('status', 'Autorização criada com sucesso!');
    }
    public function index(Request $request)
    {
        $statusFiltro = $request->input('status');
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $sort = $request->input('sort', 'authorization_date');
        $direction = $request->input('direction', 'desc');

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
        $authorizations = new LengthAwarePaginator(
            $filtered->forPage($currentPage, $perPage),
            $filtered->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $healthPlans = HealthPlan::orderBy('name')->get();

        return view('authorizations.index', compact('authorizations', 'healthPlans', 'sort', 'direction'));
    }


    public function show($id)
    {
        $authorization = Authorization::with([
            'patient',
            'healthPlan',
            'modalities.modality',
            'invoices'
        ])->findOrFail($id);

        return view('authorizations.show', compact('authorization'));
    }
    public function edit($id)
    {
        $authorization = Authorization::with(['modalities', 'files'])->findOrFail($id);
        $patients = Patient::orderBy('name')->get();
        $modalities = Modality::orderBy('name')->get();
        $healthPlans = HealthPlan::orderBy('name')->get();

        return view('authorizations.edit', compact('authorization', 'patients', 'modalities', 'healthPlans'));
    }


    public function update(Request $request, $id)
    {
            $authorization = \App\Models\Authorization::findOrFail($id);

            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'health_plan_id' => 'required',
                'authorization_date' => 'required|date',
                'authorization_number' => 'nullable|unique:authorizations,authorization_number,' . $authorization->id,
                'modalities.*.modality_id' => 'required|exists:modalities,id',
                'modalities.*.quantity_type' => 'required',
                'modalities.*.quantity' => 'required|integer',
                'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);


            $authorization->update([
                'patient_id' => $request->patient_id,
                'health_plan_id' => $request->health_plan_id,
                'authorization_number' => $request->authorization_number,
                'estimated_end_date' => $request->estimated_end_date,
                'updated_by' => auth()->id(),
                'authorization_date' => $request->authorization_date,
                'authorization_expiration_date' => $request->authorization_expiration_date,
                'observation' => $request->observation,

            ]);


            // Pega os IDs enviados
            $receivedIds = collect($request->modalities)->pluck('id')->filter();

            // Cria ou atualiza cada modalidade
            foreach ($request->modalities as $modalityData) {
                if (!empty($modalityData['id'])) {
                    // UPDATE
                    $modality = AuthorizationModality::find($modalityData['id']);
                    if ($modality) {
                        $modality->update([
                            'modality_id' => $modalityData['modality_id'],
                            'quantity' => $modalityData['quantity'],
                            'quantity_type' => $modalityData['quantity_type'],
                            'unit_value' => $modalityData['unit_value'] ?? null,
                            'total_value' => $modalityData['total_value'] ?? null,
                            'matricula_id' => $modalityData['matricula_id'] ?? null,
                            'updated_by' => auth()->id(),
                            'last_session_date' => $modalityData['last_session_date'] ?? null,
                        ]);
                    }
                } else {
                    // CREATE
                    $authorization->modalities()->create([
                        'modality_id' => $modalityData['modality_id'],
                        'quantity' => $modalityData['quantity'],
                        'quantity_type' => $modalityData['quantity_type'],
                        'unit_value' => $modalityData['unit_value'] ?? null,
                        'total_value' => $modalityData['total_value'] ?? null,
                        'matricula_id' => $modalityData['matricula_id'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                        'last_session_date' => $modalityData['last_session_date'] ?? null,
                    ]);
                }
            }

            // Upload de novos arquivos (mantém os antigos)
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('authorizations', 'public');
                    $authorization->files()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }
            }



        return redirect()->route('autorizacoes.index')->with('status', 'Autorização atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $authorization = \App\Models\Authorization::with('files')->findOrFail($id);

        // Remove arquivos físicos
        foreach ($authorization->files as $file) {
            \Storage::disk('public')->delete($file->file_path);
        }

        $authorization->delete();

        return redirect()->route('autorizacoes.index')->with('status', 'Autorização excluída com sucesso.');
    }
    public function listarParaCobranca()
    {
        $authorizations = \App\Models\Authorization::with('patient')->orderByDesc('created_at')->paginate(20);

        return view('authorizations.cobranca_index', compact('authorizations'));
    }

    public function removeModality($authorizationId, $modalityId)
    {
        // Encontra a autorização
        $authorization = Authorization::with('modalities')->findOrFail($authorizationId);

        // Verifica se a modalidade pertence à autorização
        $modality = $authorization->modalities()->findOrFail($modalityId);

        // Remove a modalidade
        $modality->delete();

        return redirect()->route('autorizacoes.edit', $authorizationId)
            ->with('status', 'Modalidade removida com sucesso!');
    }

}
