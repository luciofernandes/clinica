@extends('adminlte::page')

@section('title', 'Cobrança - ' . $authorization->authorization_number)

@section('content_header')
    <h1>Cobrança da Autorização #{{ $authorization->authorization_number }}</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <a href="{{ route('autorizacoes.index') }}" class="btn btn-secondary mb-3">← Voltar</a>

    {{-- Adicionar nova NF --}}
    <form method="POST" action="{{ route('cobrancas.store', $authorization->id) }}">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control" name="invoice_number" placeholder="Número da NF">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" class="form-control" name="amount" placeholder="Valor">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="issue_date">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">+ Adicionar NF</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <label><strong>Modalidades incluídas na NF:</strong></label>
                <div class="d-flex flex-wrap">
                    @foreach ($authorization->modalities as $modality)
                        <div class="form-check me-4">
                            <input class="form-check-input" type="checkbox" name="authorization_modality_ids[]" value="{{ $modality->id }}" id="modality{{ $modality->id }}">
                            <label class="form-check-label" for="modality{{ $modality->id }}">
                                {{ $modality->modality->name }} - {{ $modality->quantity }} {{ $modality->quantity_type }}
                            </label>
                        </div>
                    @endforeach
                </div>
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
            <th>Modalidades</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_number }}</td>
                <form method="POST" action="{{ route('cobrancas.update', $invoice->id) }}">
                    @csrf
                    @method('PUT')
                <td> <input type="number" step="0.01" name="amount" class="form-control" value="{{ $invoice->amount }}" ></td>
                    <td><input type="date" name="payment_date" class="form-control" id="payment_date"
                               value="{{ old('payment_date', $invoice->payment_date) }}"></td>
                <td>

                        <select name="status" class="form-control" onchange="this.form.submit()">
                            @foreach(['pendente', 'enviado', 'pago', 'recebido'] as $status)
                                <option value="{{ $status }}" {{ $invoice->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>

                </td>
                </form>
                <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') }}</td>
                <td>
                    <div class="col-md-12">

                        <div class="d-flex flex-wrap">
                            @foreach ($authorization->modalities as $modality)
                                <div class="form-check me-4">
                                    <label class="form-check-label" for="modality{{ $modality->id }}">
                                        {{ $modality->modality->name }} - {{ $modality->quantity }} {{ $modality->quantity_type }}
                                    </label>
                                </div>
                            @endforeach

                    </div>
                </td>
                <td class="text-nowrap">
                @if($invoice->status !== 'pago')
                        <a href="{{ route('cobrancas.edit', $authorization->id) }}"
                           class="btn btn-sm btn-warning"
                           title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form method="POST" action="{{ route('cobrancas.destroy', $invoice->id) }}"       style="display:inline-block;" onsubmit="return confirm('Excluir essa NF?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Excluir">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
@endforeach
</tbody>
</table>
@endsection
