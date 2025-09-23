@extends('adminlte::page')

@section('title', 'Resumo Financeiro Mensal')

@section('content_header')
    <h1>Resumo Financeiro Mensal</h1>
@stop

@section('content')
    <div class="mb-4">
        <a href="{{ route('billing.form') }}" class="btn btn-success">
            📊 Importar Faturamento
        </a>

        <a href="{{ route('receipt.form') }}" class="btn btn-primary">
            💰 Importar Contas a Receber
        </a>

        <a href="{{ route('comissoes.form') }}" class="btn btn-warning">
            🧾 Importar Comissões
        </a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Ano</th>
            <th>Mês</th>
            <th>Faturado</th>
            <th>Recebido</th>
            <th>Comissão</th>
            <th>Lucro Bruto</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($dados as $linha)
            <tr>
                <td>{{ $linha->ano }}</td>
                <td>{{ $linha->mes }}</td>
                <td>R$ {{ number_format($linha->total_faturado, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($linha->total_recebido, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($linha->total_comissao, 2, ',', '.') }}</td>
                <td>
                    R$ {{ number_format($linha->total_recebido - $linha->total_comissao, 2, ',', '.') }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
