@extends('adminlte::page')

@section('title', 'Autorizações')

@section('content_header')
    <h1>Autorizações</h1>
@endsection

@section('content')

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <a href="{{ route('autorizacoes.create') }}" class="btn btn-primary mb-3">Nova Autorização</a>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nº Autorização</th>
            <th>Paciente</th>
            <th>Plano</th>
            <th>Dt Autorização</th>
            <th>Validade</th>
            <th>Última Sessão</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        @forelse($authorizations as $authorization)
            <tr>
                <td>{{ $authorization->authorization_number }}</td>
                <td>{{ $authorization->patient->name }}</td>
                <td>{{ $authorization->healthPlan->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($authorization->authorization_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($authorization->authorization_expiration_date)->format('d/m/Y') }}</td>
                <td>{{ $authorization->estimated_end_date ? \Carbon\Carbon::parse($authorization->estimated_end_date)->format('d/m/Y') : '-' }}</td>
                <td>
                    <a href="{{ route('autorizacoes.show', $authorization->id) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('autorizacoes.edit', $authorization->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('autorizacoes.destroy', $authorization->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir esta autorização?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Nenhuma autorização encontrada.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $authorizations->links() }}

@endsection
