@extends('adminlte::page')

@section('title', 'Dashboard Financeiro')

@section('content_header')
    <h1>Dashboard Financeiro</h1>
@endsection

@section('content')
    <div class="row">

        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>R$ {{ number_format($totalCobrado, 2, ',', '.') }}</h3>
                    <p>Total Cobrado</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>R$ {{ number_format($totalRecebido, 2, ',', '.') }}</h3>
                    <p>Total Recebido</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>R$ {{ number_format($totalPendente, 2, ',', '.') }}</h3>
                    <p>Total Pendente</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalAutorizacoes}}</h3>
                    <p>Autorizações</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-medical"></i>
                </div>
            </div>
        </div>  
        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalNotas }}</h3>
                    <p>Total de Notas Fiscais</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $totalPacientesComCobrança }}</h3>
                    <p>Pacientes com Cobrança</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
