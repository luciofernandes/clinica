@extends('adminlte::page')

@section('title', 'Mesalidades')

@section('content_header')
    <h1>Mesalidades</h1>
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

    <a href="{{ route('billing.import') }}" class="btn btn-primary mb-3">Importar</a>
{{--    <form method="GET" class="mb-3">--}}
{{--        <div class="form-row">--}}
{{--            <div class="form-group mr-2">--}}
{{--                <input type="text" name="numero" value="{{ request('numero') }}" class="form-control" placeholder="Nº Autorização">--}}
{{--            </div>--}}
{{--            <div class="col-md-3">--}}
{{--                <input type="text" name="paciente" class="form-control" placeholder="Paciente"--}}
{{--                       value="{{ request('paciente') }}">--}}
{{--            </div>--}}
{{--            <div class="col-md-3">--}}
{{--                <select name="plano" class="form-control">--}}
{{--                    <option value="">-- Plano de Saúde --</option>--}}
{{--                    @foreach($healthPlans as $plan)--}}
{{--                        <option value="{{ $plan->name }}" {{ request('plano') == $plan->name ? 'selected' : '' }}>--}}
{{--                            {{ $plan->name }}--}}
{{--                        </option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}
{{--            <div class="col-md-3">--}}
{{--                <select name="status" id="status" class="form-control mr-2">--}}
{{--                    <option value="">-- Status financeiro --</option>--}}
{{--                    <option value="sem_cobranca" {{ request('status') == 'sem_cobranca' ? 'selected' : '' }}>Sem cobrança</option>--}}
{{--                    <option value="parcial" {{ request('status') == 'parcial' ? 'selected' : '' }}>Parcialmente faturada</option>--}}
{{--                    <option value="faturado_pendente" {{ request('status') == 'faturado_pendente' ? 'selected' : '' }}>Faturada pendente</option>--}}
{{--                    <option value="pago_completo" {{ request('status') == 'pago_completo' ? 'selected' : '' }}>Totalmente faturada e paga</option>--}}
{{--                    <option value="excedente" {{ request('status') == 'excedente' ? 'selected' : '' }}>Valor excedido</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--            <div class="col-md-3">--}}

{{--                <button class="btn btn-primary" type="submit">Filtrar</button>--}}
{{--                <a href="{{ route('autorizacoes.index') }}" class="btn btn-secondary">Limpar</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </form>--}}

    <table class="table table-bordered">
        <thead>
        <tr>
           <th>ano</th>
            <th>mês</th>
            <th>Valor total</th>
        </tr>
        </thead>
        <tbody>


        @forelse($billings as $billing)
            <tr>

                    <td>{{ $billing->year }}</td>
                    <td>{{ $billing->month }}</td>
                    <td>R$ {{ number_format($billing->total_amount, 2, ',', '.') }}</td>


            </tr>
        @empty
<tr>
 <td colspan="5">Nenhuma importacao encontrada.</td>
</tr>
@endforelse
</tbody>
</table>

{{ $billings->links() }}

@endsection
