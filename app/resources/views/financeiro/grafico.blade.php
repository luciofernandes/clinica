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
                labels: @json($dados->pluck('mes')),
                datasets: [
                    {
                        label: 'Faturado',
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        data: @json($dados->pluck('faturado')),
                    },
                    {
                        label: 'Recebido',
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        data: @json($dados->pluck('recebido')),
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'R$ Valor (Reais)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mês'
                        }
                    }
                }
            }
        });
    </script>
@stop
