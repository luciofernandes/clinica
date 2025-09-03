@extends('adminlte::page')

@section('title', 'Editar Cobrança')
@section('js')
    <script>
        function togglePaymentDate() {
            const status = document.getElementById('status').value;
            const paymentGroup = document.getElementById('payment-date-group');
            if (status === 'pago') {
                paymentGroup.style.display = 'block';
            } else {
                paymentGroup.style.display = 'none';
                document.getElementById('payment_date').value = '';
            }
        }

        document.getElementById('status').addEventListener('change', togglePaymentDate);

        // Executar ao carregar
        togglePaymentDate();
    </script>
@endsection

@section('content_header')
    <h1>Editar Cobrança</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Erros encontrados:</strong>
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cobrancas.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Número da Nota</label>
            <input type="text" class="form-control" value="{{ $invoice->invoice_number }}" disabled>
        </div>

{{--        <div class="form-group">--}}
{{--            <label>Cliente</label>--}}
{{--            <input type="text" class="form-control" value="{{ $invoice->client->name }}" >--}}
{{--        </div>--}}

        <div class="form-group">
            <label>Valor</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $invoice->amount }}" >
        </div>

        <div class="form-group">
            <label>Status</label>

            <select name="status" class="form-control" id="status">
                <option value="pendente" {{ old('status', $invoice->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="enviado" {{ old('status', $invoice->status) == 'enviado' ? 'selected' : '' }}>Enviado</option>
                <option value="pago" {{ old('status', $invoice->status) == 'pago' ? 'selected' : '' }}>Pago</option>
            </select>
        </div>
        <div class="form-group" id="payment-date-group" style="display: none;">
            <label>Data de Pagamento</label>
            <input type="date" name="payment_date" class="form-control" id="payment_date"
                   value="{{ old('payment_date', $invoice->payment_date) }}">
        </div>
        @if($invoice->status !== 'pago' || auth()->user()->is_admin)
            <button type="submit" class="btn btn-primary">Salvar</button>
        @endif

        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
    </form>

@endsection
