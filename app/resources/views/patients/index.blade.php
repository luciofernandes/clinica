@extends('adminlte::page')

@section('title', 'Pacientes')

@section('content_header')
    <h1>Pacientes</h1>
@endsection

@section('content')

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="GET" action="{{ route('pacientes.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nome ou CPF" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary">Buscar</button>
            </div>
        </div>
    </form>

{{--    <a href="{{ route('pacientes.create') }}" class="btn btn-success mb-2">--}}
{{--        <i class="fas fa-user-plus"></i> Novo Paciente--}}
{{--    </a>--}}

    <a href="{{ route('pacientes.importar') }}" class="btn btn-primary mb-2">
        <i class="fas fa-file-upload"></i> Importar CSV
    </a>

    <form action="{{ route('pacientes.deletar') }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir os pacientes sem autorização?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mb-2">
            <i class="fas fa-trash-alt "></i> Excluir
        </button>
    </form>
    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Data Cadastro</th>
        </tr>
        </thead>
        <tbody>
        @forelse($patients as $patient)
            <tr>
                <td>{{ $patient->name }}</td>
                <td>{{ $patient->cpf }}</td>
                <td>{{ $patient->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Nenhum paciente encontrado.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $patients->appends(request()->query())->links() }}

@endsection
