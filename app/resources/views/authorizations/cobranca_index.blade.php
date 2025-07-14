@extends('adminlte::page')

@section('title', 'Criar Cobrança')

@section('content_header')
    <h1>Selecionar Autorização para Cobrança</h1>
@endsection

@section('content')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Paciente</th>
            <th>Plano</th>
            <th>Nº Autorização</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        @foreach($authorizations as $authorization)
            <tr>
                <td>{{ $authorization->patient->name }}</td>
                <td>{{ $authorization->plan }}</td>
                <td>{{ $authorization->authorization_number }}</td>
                <td>
                    <a href="{{ route('cobrancas.index', $authorization->id) }}" class="btn btn-sm btn-primary">
                        Criar Cobrança
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $authorizations->links() }}
@endsection
