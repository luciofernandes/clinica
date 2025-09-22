@extends('adminlte::page')

@section('title', 'Gráfico Financeiro')

@section('content_header')
    <h1>Comparativo Faturado x Recebido - {{ $ano }}</h1>
@stop

@section('content')
    <form method="GET" action="{{ route('financeiro.grafico') }}" class="mb-4">
        <div class="form-group row">
            <label for="ano" class="col-sm-1 col-form-label">Ano:</label>
            <div class="col-sm-2">
                <input type="number" name="ano" id="ano" class="form-control" value="{{ $ano }}">
            </div>
            <label for="tipo_data" class="col-sm-2 col-form-label">Tipo de Data:</label>
            <div class="col-sm-3">
                <select name="tipo_data" id="tipo_data" class="form-control">
                    <option value="referencia" {{ request('tipo_data') == 'referencia' ? 'selected' : '' }}>Data de Referência</option>
                    <option value="pagamento" {{ request('tipo_data') == 'pagamento' ? 'selected' : '' }}>Data de Pagamento</option>
                </select>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <canvas id="grafico" height="100"></canvas>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('grafico').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($mesesArray),
                datasets: [
                    {
                        label: 'Faturado',
                        data: @json($faturadoArray),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    },
                    {
                        label: 'Recebido',
                        data: @json($recebidoArray),
                        backgroundColor: 'rgba(75, 192, 192, 0.7)'
                    },
                    {
                        label: 'Diferença',
                        data: @json($diferencaArray),
                        backgroundColor: @json($coresDiferenca),
                        type: 'bar',
                        stack: 'Stack 1'
                    }
                ]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: R$ ${parseFloat(context.raw).toFixed(2)}`;
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    }
                },
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value;
                            }
                        }
                    }
                }
            }
        });
    </script>
@stop
