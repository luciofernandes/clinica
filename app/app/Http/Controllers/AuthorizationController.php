<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authorization;

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
                'authorization_number' => 'required|unique:authorizations',
                'modalities.*.modality_id' => 'required|exists:modalities,id',
                'modalities.*.quantity_type' => 'required',
                'modalities.*.quantity' => 'required|integer',
                'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $authorization = \App\Models\Authorization::create([
                'patient_id' => $request->patient_id,
                'authorization_number' => $request->authorization_number,
                'external_enrollment_link' => $request->external_enrollment_link,
                'estimated_end_date' => $request->estimated_end_date,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'authorization_date' => $request->authorization_date,
                'authorization_expiration_date' => $request->authorization_expiration_date,
                'health_plan_id' => $request->health_plan_id ?? null,
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
    public function index()
    {
        $authorizations = \App\Models\Authorization::with('patient')->latest()->paginate(10);
        return view('authorizations.index', compact('authorizations'));
    }

    public function show($id)
    {
        $authorization = Authorization::with(['patient', 'modalities.modality', 'files', 'healthPlan'])->findOrFail($id);
        return view('authorizations.show', compact('authorization'));
    }
    public function edit($id)
    {
        $authorization = \App\Models\Authorization::with('modalities')->findOrFail($id);
        $patients = \App\Models\Patient::orderBy('name')->get();
        $modalities = \App\Models\Modality::orderBy('name')->get();

        $healthPlans = \App\Models\HealthPlan::orderBy('name')->get();
        return view('authorizations.create', compact('authorization', 'patients', 'modalities', 'healthPlans'));

    }

    public function update(Request $request, $id)
    {
        $authorization = \App\Models\Authorization::findOrFail($id);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'health_plan_id' => 'required',
            'authorization_date' => 'required|date',
            'external_enrollment_link' => 'required|url',
            'authorization_number' => 'required|unique:authorizations,authorization_number,' . $authorization->id,
            'modalities.*.modality_id' => 'required|exists:modalities,id',
            'modalities.*.quantity_type' => 'required',
            'modalities.*.quantity' => 'required|integer',
            'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $authorization->update([
            'patient_id' => $request->patient_id,
            'health_plan_id' => $request->health_plan_id,
            'authorization_number' => $request->authorization_number,
            'external_enrollment_link' => $request->external_enrollment_link,
            'estimated_end_date' => $request->estimated_end_date,
            'updated_by' => auth()->id(),
            'authorization_date' => $request->authorization_date,
            'authorization_expiration_date' => $request->authorization_expiration_date,
        ]);

        // Deleta modalidades antigas e recria
        $authorization->modalities()->delete();

        foreach ($request->modalities as $modality) {
            $authorization->modalities()->create([
                'modality_id' => $modality['modality_id'],
                'quantity_type' => $modality['quantity_type'],
                'quantity' => $modality['quantity'],
                'unit_value' => $modality['unit_value'] ?? null,
                'total_value' => $modality['total_value'] ?? null,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
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
}
