@extends('adminlte::page')

@section('title', 'Resumo de Cobranças por Paciente')

@section('content_header')
    <h1>Resumo Financeiro por Paciente</h1>
@endsection

@section('content')
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Paciente</th>
            <th>Autorizações</th>
            <th>Total Cobrado (NF)</th>
            <th>Recebido</th>
            <th>Pendente</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pacientes as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->total_autorizacoes }}</td>
                <td>R$ {{ number_format($p->total_cobrado, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($p->total_recebido, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($p->total_pendente, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
