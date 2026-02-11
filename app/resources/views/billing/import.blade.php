@extends('adminlte::page')

@section('title', 'Importar Faturamento')

@section('content_header')
    <h1>Importar Planilha de Faturamento</h1>
@stop

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('billing.import') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="file">Arquivo CSV</label>
            <input type="file" name="file" class="form-control" required>
        </div>

        <div class="form-group mt-2">
            <label for="tipo">Tipo</label>
            <select name="tipo" id="tipo" class="form-control" required>
                <option value="">Selecione</option>
                <option value="ativa">Ativa</option>
                <option value="finalizada">Finalizada</option>
                <option value="cancelada">Cancelada</option>
            </select>
        </div>

        <button class="btn btn-primary mt-2">Importar</button>
    </form>
@stop
