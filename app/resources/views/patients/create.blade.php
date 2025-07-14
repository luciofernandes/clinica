@extends('adminlte::page')

@section('title', 'Novo Paciente')

@section('content_header')
    <h1>Novo Paciente</h1>
@endsection

@section('content')

    <form action="{{ route('pacientes.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" name="cpf" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

@endsection
