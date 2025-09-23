@extends('adminlte::page')

@section('title', 'Importar Comissões')

@section('content_header')
    <h1>Importar Comissões</h1>
@stop

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('comissoes.import') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="mes">Mês</label>
            <input type="number" name="mes" class="form-control" min="1" max="12" required>
        </div>

        <div class="form-group">
            <label for="ano">Ano</label>
            <input type="number" name="ano" class="form-control" min="2000" value="{{ now()->year }}" required>
        </div>

        <div class="form-group">
            <label for="file">Arquivo CSV</label>
            <input type="file" name="file" class="form-control" required>
        </div>

        <button class="btn btn-primary mt-2">Importar</button>
    </form>
@stop
