@extends('adminlte::page')

@section('title', 'Autorizações')

@section('content_header')
    <h1>Autorizações</h1>
@endsection
@push('scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('content')


    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <a href="{{ route('autorizacoes.create') }}" class="btn btn-primary mb-3">Nova Autorização</a>
    <form method="GET" class="mb-3">
        <div class="form-row">
            <div class="col-md-3">
                <input type="text" name="paciente" class="form-control" placeholder="Paciente"
                       value="{{ request('paciente') }}">
            </div>
            <div class="col-md-3">
                <select name="plano" class="form-control">
                    <option value="">-- Plano de Saúde --</option>
                    @foreach($healthPlans as $plan)
                        <option value="{{ $plan->name }}" {{ request('plano') == $plan->name ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" id="status" class="form-control mr-2">
                    <option value="">-- Status financeiro --</option>
                    <option value="sem_cobranca" {{ request('status') == 'sem_cobranca' ? 'selected' : '' }}>Sem cobrança</option>
                    <option value="parcial" {{ request('status') == 'parcial' ? 'selected' : '' }}>Parcialmente faturada</option>
                    <option value="faturado_pendente" {{ request('status') == 'faturado_pendente' ? 'selected' : '' }}>Faturada pendente</option>
                    <option value="pago_completo" {{ request('status') == 'pago_completo' ? 'selected' : '' }}>Totalmente faturada e paga</option>
                    <option value="excedente" {{ request('status') == 'excedente' ? 'selected' : '' }}>Valor excedido</option>
                </select>
            </div>
            <div class="col-md-3">

                <button class="btn btn-primary" type="submit">Filtrar</button>
                <a href="{{ route('autorizacoes.index') }}" class="btn btn-secondary">Limpar</a>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nº Autorização</th>
            <th>Paciente</th>
            <th>
                <a href="{{ route('autorizacoes.index', array_merge(request()->query(), ['sort' => 'plan', 'direction' => $sort === 'plan' && $direction === 'asc' ? 'desc' : 'asc'])) }}">
                    Plano @if($sort === 'plan') <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                        <i class="fas fa-sort"></i> @endif
                </a>
            </th>
            <th>
                <a href="{{ route('autorizacoes.index', array_merge(request()->query(), ['sort' => 'authorization_date', 'direction' => $sort === 'authorization_date' && $direction === 'asc' ? 'desc' : 'asc'])) }}">
                    Dt Autorização @if($sort === 'authorization_date') <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                        <i class="fas fa-sort"></i> @endif
                </a>
            </th>
            <th>
                <a href="{{ route('autorizacoes.index', array_merge(request()->query(), ['sort' => 'authorization_expiration_date', 'direction' => $sort === 'authorization_expiration_date' && $direction === 'asc' ? 'desc' : 'asc'])) }}">
                    Validade @if($sort === 'authorization_expiration_date') <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                        <i class="fas fa-sort"></i> @endif
                </a>
            </th>
            <th>
                <a href="{{ route('autorizacoes.index', array_merge(request()->query(), ['sort' => 'estimated_end_date', 'direction' => $sort === 'estimated_end_date' && $direction === 'asc' ? 'desc' : 'asc'])) }}">
                    Última Sessão @if($sort === 'estimated_end_date') <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                    @else
                        <i class="fas fa-sort"></i> @endif
                </a>
            </th>
            <th>Valor</th>
            <th>Modalidades</th>
            <th>Status da Cobrança</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>


        @forelse($authorizations as $authorization)
            <tr @if($authorization->atraso_financeiro) class="table-danger" @endif>
                <td>{{ $authorization->authorization_number }}</td>
                <td>{{ $authorization->patient->name }}</td>
                <td>{{ $authorization->healthPlan->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($authorization->authorization_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($authorization->authorization_expiration_date)->format('d/m/Y') }}</td>
                <td>{{ $authorization->estimated_end_date ? \Carbon\Carbon::parse($authorization->estimated_end_date)->format('d/m/Y') : '-' }}</td>


                {{-- Valor total das modaldiades --}}
                <td>
                    @php
                        $totalValue = 0; // Initialize the variable
                    @endphp
                    @foreach($authorization->modalities as $modality)
                        @php
                            $totalValue += $modality->total_value; // Soma o valor total de cada modalidade
                        @endphp
                    @endforeach
                        <p>R$ {{ number_format($totalValue, 2, ',', '.') }}</p>
                </td>
                <td>
                    @foreach ($authorization->modalities as $modality)
                        <div  style=" padding: .375rem .75rem; height: calc(2.25rem + 2px)">
                            {{ $modality->modality->name ?? 'Desconhecida' }}
                            @if ($modality->matricula_id)
                                <a href="https://painel.softwarepilates.com.br/Matricula/Matricula.aspx?c={{ $modality->matricula_id }}"
                                   target="_blank"
                                   title="Editar Matrícula">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @else
                                <a href="https://painel.softwarepilates.com.br/Matricula/Matricula.aspx"  target="_blank"

                                   title="Criar Matrícula">
                                    <i class="fas fa-plus"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </td>
                <td>
                @php
                    $status = $authorization->billing_status;
                    $labels = [
                        'sem_cobranca'       => ['text' => 'Sem cobrança',                 'color' => 'secondary'],
                        'parcial'            => ['text' => 'Parcialmente faturada',        'color' => 'info'],
                        'faturado_pendente'  => ['text' => 'Faturada pendente',            'color' => 'warning'],
                        'pago_completo'      => ['text' => 'Totalmente faturada e paga',   'color' => 'success'],
                        'excedente'          => ['text' => 'Valor excedido',               'color' => 'danger'],
                    ];

                    $data = $labels[$status] ?? ['text' => 'Indefinido', 'color' => 'dark'];
                @endphp

                <span class="badge badge-{{ $data['color'] }}">

        {{ $data['text'] }}
    </span>
                    @if ($authorization->atraso_financeiro)
                        <span class="badge badge-danger ml-1" title="Autorização vencida ou fora da data de sessão">
            <i class="fas fa-exclamation-circle"></i> Vencida
        </span>
                    @endif
                </td>



                <td class="text-nowrap">
     {{-- Ver --}}
     <a href="{{ route('autorizacoes.show', $authorization->id) }}"
        class="btn btn-sm btn-info"
        title="Ver">
         <i class="fas fa-eye"></i>
     </a>

     {{-- Editar --}}
     <a href="{{ route('autorizacoes.edit', $authorization->id) }}"
        class="btn btn-sm btn-warning"
        title="Editar">
         <i class="fas fa-edit"></i>
     </a>

     {{-- Excluir --}}
     <form action="{{ route('autorizacoes.destroy', $authorization->id) }}"
           method="POST"
           style="display:inline-block;"
           onsubmit="return confirm('Tem certeza que deseja excluir esta autorização?')">
         @csrf
         @method('DELETE')
         <button class="btn btn-sm btn-danger" title="Excluir">
             <i class="fas fa-trash-alt"></i>
         </button>
     </form>

     {{-- Cobrança --}}
{{--                    @if ($authorization->invoices->isEmpty())--}}
         <a href="{{ route('cobrancas.index', $authorization->id) }}"
            class="btn btn-sm btn-secondary  bg-success"
            title="Cadastrar Cobrança">
             <i class="fas fa-file-invoice-dollar"></i>
         </a>
{{--                    @else--}}
{{--                        <a href="{{ route('cobrancas.edit', $authorization->id) }}"--}}
{{--                           class="btn btn-sm btn-primary"--}}
{{--                           title="Editar Cobrança">--}}
{{--                            <i class="fas fa-file-invoice"></i>--}}
{{--                        </a>--}}
{{--                    @endif--}}
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
