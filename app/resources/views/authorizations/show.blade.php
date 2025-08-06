@extends('adminlte::page')

@section('title', 'Detalhes da Autorização')

@section('content_header')
    <h1>Detalhes da Autorização</h1>
@endsection

@section('content')

    <a href="{{ route('cobrancas.index', $authorization->id) }}" class="btn btn-warning mb-3">
        <i class="fas fa-file-invoice-dollar"></i> Criar Cobrança
    </a>

    <a href="{{ route('autorizacoes.index') }}" class="btn btn-secondary mb-3">← Voltar</a>


    <div class="card">
        <div class="card-body">
            <h5><strong>Paciente:</strong> {{ $authorization->patient->name }}</h5>
            <p><strong>Plano de Saúde:</strong> {{ $authorization->healthPlan->name ?? '-' }}</p>
            <p><strong>Nº Autorização:</strong> {{ $authorization->authorization_number }}</p>
            <p><strong>Data da Autorização:</strong> {{ \Carbon\Carbon::parse($authorization->authorization_date)->format('d/m/Y') }}</p>
            <p><strong>Validade:</strong> {{ \Carbon\Carbon::parse($authorization->authorization_expiration_date)->format('d/m/Y') }}</p>
            <p><strong>Última Sessão:</strong> {{ $authorization->estimated_end_date ? \Carbon\Carbon::parse($authorization->estimated_end_date)->format('d/m/Y') : '-' }}</p>

        </div>
    </div>

    <hr>

    <h5>Modalidades Autorizadas</h5>

    @if($authorization->modalities->isEmpty())
        <p>Nenhuma modalidade autorizada.</p>
    @else
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Modalidade</th>
                <th>Quantidade</th>
                <th>Tipo</th>
                <th>Valor Total</th>
                <th>Matricula</th>
            </tr>
            </thead>
            <tbody>
            @foreach($authorization->modalities as $modality)
                <tr>
                    <td>{{ $modality->modality->name ?? 'Modalidade desconhecida' }}</td>
                    <td>{{ $modality->quantity }}</td>
                    <td>{{ $modality->quantity_type }}</td>
                    <td>R$ {{ number_format($modality->total_value ?? 0, 2, ',', '.') }}</td>
                    <td>
                    @if ($modality->matricula_id)
                        <a href="https://painel.softwarepilates.com.br/Matricula/Matricula.aspx?c={{ $modality->matricula_id }}"
                           class="form-control btn btn-sm btn-info" target="_blank">
                            Ver/Editar Matrícula
                        </a>
                    @else
                        <a href="https://painel.softwarepilates.com.br/Matricula/Matricula.aspx"
                           class="form-control btn btn-sm btn-primary" target="_blank">
                            Criar Matrícula
                        </a>
                    @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
{{--        <ul class="list-group mb-3">--}}
{{--            @foreach($authorization->modalities as $modality)--}}
{{--                <li class="list-group-item d-flex justify-content-between align-items-center">--}}
{{--                    {{ $modality->modality->name ?? 'Modalidade desconhecida' }} —--}}
{{--                    {{ $modality->quantity }} {{ $modality->quantity_type }}--}}
{{--                    <span class="badge badge-primary">--}}
{{--                    R$ {{ number_format($modality->total_value ?? 0, 2, ',', '.') }}--}}
{{--                </span>--}}
{{--                </li>--}}
{{--            @endforeach--}}
{{--        </ul>--}}

    <h5>Cobranças</h5>

    @if($authorization->invoices->isEmpty())
        <p>Nenhuma cobrança registrada.</p>
    @else
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Nº NF</th>
                <th>Status</th>
                <th>Valor</th>
                <th>Data Pagamento</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($authorization->invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ ucfirst($invoice->status) }}</td>
                    <td>R$ {{ number_format($invoice->amount, 2, ',', '.') }}</td>
                    <td>{{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if(auth()->user()->is_admin || $invoice->status !== 'pago')
                            <a href="{{ route('cobrancas.edit', $invoice->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <h4>Arquivos Anexos</h4>
    @if($authorization->files->count())
        <ul>
            @foreach($authorization->files as $file)
                <li>
                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">{{ $file->file_name }}</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>Nenhum arquivo enviado.</p>
    @endif

@endsection
