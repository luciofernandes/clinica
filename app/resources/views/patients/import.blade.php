@extends('adminlte::page')

@section('title', 'Importar Pacientes')

@section('content_header')
    <h1>Importar Pacientes (CSV)</h1>
@endsection

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('pacientes.importar.salvar') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="csv_file">Arquivo CSV</label>
            <input type="file" name="csv_file" class="form-control" required>
        </div>

        <button class="btn btn-primary mt-2">Importar</button>
    </form>
@endsection
