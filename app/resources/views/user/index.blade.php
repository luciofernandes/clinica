@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
    <h1>Usuários</h1>
@endsection

@section('content')

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="GET" action="{{ route('user.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nome ou email" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary">Buscar</button>
            </div>
        </div>
    </form>

    <a href="{{ route('user.create') }}" class="btn btn-success mb-2">
        <i class="fas fa-user-plus"></i> Novo Usuário
    </a>

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Data Cadastro</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                <td>
{{--                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-sm">--}}
{{--                        <i class="fas fa-edit"></i> Editar--}}
{{--                    </a>--}}
                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                            <i class="fas fa-trash"></i> Excluir
                        </button>
                    </form>
            </tr>
        @empty
            <tr>
                <td colspan="3">Nenhum usuário encontrado.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $users->appends(request()->query())->links() }}

@endsection
