@extends('adminlte::page')

@section('title', 'Nova Autorização')

@section('js')
    <script>
        let index = 1;

        document.getElementById('add-modality-btn').addEventListener('click', function () {
            const container = document.getElementById('modalities-container');
            const firstGroup = container.querySelector('.modality-group');
            const clone = firstGroup.cloneNode(true);

            clone.setAttribute('data-index', index);

            clone.querySelectorAll('input, select').forEach(el => {
                if (el.name) {
                    el.name = el.name.replace(/\[\d+\]/g, `[${index}]`);
                    el.value = '';
                }
            });

            // Mostrar botão de remover nas cópias
            clone.querySelector('.remove-modality-btn').style.display = 'inline-block';

            container.appendChild(clone);
            index++;
        });

        // Remover grupo de modalidade
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-modality-btn')) {
                const group = e.target.closest('.modality-group');
                group.remove();
            }
        });

        // Validação do formulário antes de enviar
        document.querySelector('form').addEventListener('submit', function (e) {
            let isValid = true;
            const groups = document.querySelectorAll('.modality-group');

            groups.forEach(group => {
                const modality = group.querySelector('[name*="[modality_id]"]');
                const quantity = group.querySelector('[name*="[quantity]"]');

                if (!modality.value) {
                    isValid = false;
                    modality.classList.add('is-invalid');
                } else {
                    modality.classList.remove('is-invalid');
                }

                if (!quantity.value || parseInt(quantity.value) <= 0) {
                    isValid = false;
                    quantity.classList.add('is-invalid');
                } else {
                    quantity.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Preencha todos os campos obrigatórios nas modalidades.');
            }
        });
    </script>
@endsection
@section('content_header')
    <h1>Nova Autorização</h1>
@endsection


@section('content')
    <form action="{{ route('autorizacoes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Paciente --}}
        <div class="form-group">
            <label for="patient_id">Paciente</label>
            <select name="patient_id" class="form-control" required>
                <option value="">Selecione...</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->cpf }})</option>
                @endforeach
            </select>
        </div>

        {{-- Plano --}}
        <div class="form-group">
            <label for="health_plan_id">Plano de Saúde</label>
            <select name="health_plan_id" class="form-control" required>
                <option value="">Selecione...</option>
                @foreach($healthPlans as $plan)
                    <option value="{{ $plan->id }}" {{ old('health_plan_id', $authorization->health_plan_id ?? '') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }}
                    </option>
                @endforeach
            </select>
        </div>


        {{-- Nº da autorização --}}
        <div class="form-group">
            <label for="authorization_number">Nº da Autorização</label>
            <input type="text" name="authorization_number" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="authorization_date">Data da Autorização</label>
            <input type="date" name="authorization_date" class="form-control"
                   value="{{ old('authorization_date', $authorization->authorization_date ?? '') }}">
        </div>

        <div class="form-group">
            <label for="authorization_expiration_date">Validade da Autorização</label>
            <input type="date" name="authorization_expiration_date" class="form-control"
                   value="{{ old('authorization_expiration_date', $authorization->authorization_expiration_date ?? '') }}">
        </div>

        {{-- Data prevista última sessão --}}
        <div class="form-group">
            <label for="estimated_end_date">Data Prevista Última Sessão</label>
            <input type="date" name="estimated_end_date" class="form-control">
        </div>

        <div class="form-group">
            <label for="observation">Observação</label>
            <textarea name="observation" id="observation" class="form-control">{{ old('observation', $authorization->observation ?? '') }}</textarea>
        </div>

        {{-- Modalidades Dinâmicas --}}
        <hr>
        <h5>Modalidades</h5>

        <div id="modalities-container">
            {{-- Grupo de modalidade base --}}
            <div class="modality-group border p-3 mb-2" data-index="0">
                <div class="form-row">
                    <div class="form-group">
                        <label>Modalidade</label>
                        <button type="button" class="btn btn-danger btn-sm remove-modality-btn" style="display: none;">
                            Remover
                        </button>
                        <select name="modalities[0][modality_id]" class="form-control" required>
                            <option value="">Selecione...</option>
                            @foreach($modalities as $modality)
                                <option value="{{ $modality->id }}">{{ $modality->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="modalities[0][quantity_type]" class="form-control" required>
                            <option value="sessões">Sessões</option>
                            <option value="sessões/semana">Sessões por Semana</option>
                            <option value="horas/semana">Horas por Semana</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input type="number" name="modalities[0][quantity]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Valor Unitário</label>
                        <input type="number" name="modalities[0][unit_value]" step="0.01" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Valor Total</label>
                        <input type="number" name="modalities[0][total_value]" step="0.01" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="last_session_date">Data da Última Sessão</label>
                        <input type="date" name="last_session_date" class="form-control"
                               value="{{ old('last_session_date', $modality->last_session_date ? $modality->last_session_date->format('Y-m-d') : '') }}">
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-sm btn-secondary mb-3" id="add-modality-btn">
            + Adicionar Modalidade
        </button>

        {{-- Arquivo de Autorização --}}
        <div class="form-group">
            <label for="files[]">Anexar Arquivo(s)</label>
            <input type="file" name="files[]" class="form-control" multiple>
        </div>

        <button class="btn btn-primary">Salvar</button>
    </form>
@endsection
