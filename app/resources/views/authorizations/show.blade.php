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
            @if($authorization->external_enrollment_link)
                <p><strong>Matrícula Externa:</strong> <a href="{{ $authorization->external_enrollment_link }}" target="_blank">Ver</a></p>
            @endif
        </div>
    </div>

    <hr>

    <h4>Modalidades</h4>
    <ul class="list-group">
        @foreach($authorization->modalities as $modality)
            <li class="list-group-item">
                <strong>{{ $modality->modality->name }}</strong> — {{ $modality->quantity }} ({{ $modality->quantity_type }})
                @if($modality->unit_value)
                    | Valor Unitário: R$ {{ number_format($modality->unit_value, 2, ',', '.') }}
                @endif
                @if($modality->total_value)
                    | Total: R$ {{ number_format($modality->total_value, 2, ',', '.') }}
                @endif
            </li>
        @endforeach
    </ul>

    <hr>

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
