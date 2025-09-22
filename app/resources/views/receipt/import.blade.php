@extends('adminlte::page')

@section('title', 'Importar Contas a Receber')

@section('content_header')
    <h1>Importar Contas a Receber</h1>
@stop

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('receipt.import') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="file">Arquivo CSV</label>
            <input type="file" name="file" class="form-control" required>
        </div>

        <button class="btn btn-primary mt-2">Importar</button>
    </form>
@stop
