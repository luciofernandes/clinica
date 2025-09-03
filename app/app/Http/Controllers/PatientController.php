<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Patient::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('cpf', 'like', "%{$search}%");
        }

        $patients = $query->orderBy('name')->paginate(20);

        return view('patients.index', compact('patients'));
    }
    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf'  => 'required|string|unique:patients,cpf|digits:11',
        ]);

        \App\Models\Patient::create([
            'name' => $request->name,
            'cpf' => preg_replace('/[^0-9]/', '', $request->cpf),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('pacientes.index')->with('status', 'Paciente cadastrado com sucesso!');
    }

    public function delete()
    {
        // Encontra pacientes sem autorizações
        $patientsWithoutAuthorizations = \App\Models\Patient::doesntHave('authorizations')->get();

        // Deleta os pacientes encontrados
        $deletedCount = $patientsWithoutAuthorizations->each->delete();

        // Retorna uma mensagem de sucesso
        return redirect()->route('pacientes.index')->with('status', "{$deletedCount->count()} pacientes sem autorizações foram deletados com sucesso!");
    }
}
