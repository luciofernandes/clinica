@extends('adminlte::page')

@section('title', 'Cobrança - ' . $authorization->authorization_number)

@section('content_header')
    <h1>Cobrança da Autorização #{{ $authorization->authorization_number }}</h1>
@endsection

@section('content')
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <a href="{{ route('autorizacoes.index') }}" class="btn btn-secondary mb-3">← Voltar</a>

    {{-- Adicionar nova NF --}}
    <form method="POST" action="{{ route('cobrancas.store', $authorization->id) }}">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="invoice_number" placeholder="Número da NF" class="form-control" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="amount" placeholder="Valor" class="form-control" required>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="pendente">Pendente</option>
                    <option value="enviado">Enviado</option>
                    <option value="pago">Pago</option>
                    <option value="recebido">Recebido</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="issue_date" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary">+ Adicionar NF</button>
            </div>
        </div>
    </form>

    <hr>

    {{-- Tabela de cobranças --}}
    <table class="table table-bordered mt-4">
        <thead>
        <tr>
            <th>Nº NF</th>
            <th>Valor</th>
            <th>Status</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_number }}</td>
                <td>R$ {{ number_format($invoice->amount, 2, ',', '.') }}</td>
                <td>
                    <form method="POST" action="{{ route('cobrancas.update', $invoice->id) }}">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            @foreach(['pendente', 'enviado', 'pago', 'recebido'] as $status)
                                <option value="{{ $status }}" {{ $invoice->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') }}</td>
                <td>
                    <form method="POST" action="{{ route('cobrancas.destroy', $invoice->id) }}" onsubmit="return confirm('Excluir essa NF?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Remover</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
