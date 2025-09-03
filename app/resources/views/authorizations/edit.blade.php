@extends('adminlte::page')

@section('title', 'Editar Autorização')
@section('js')
    <script>
        let index = 1;

        document.getElementById('add-modality-btn').addEventListener('click', function () {
            const container = document.getElementById('modalities-container');
            const firstGroup = container.querySelector('.modality-group');
            const clone = firstGroup.cloneNode(true);

            clone.setAttribute('data-index', index);

            // Atualiza os atributos name de cada input
            clone.querySelectorAll('input, select').forEach(el => {
                if (el.name) {
                    el.name = el.name.replace(/\[\d+\]/g, `[${index}]`);
                    el.value = '';
                }
            });

            container.appendChild(clone);
            index++;
        });
    </script>
@endsection
@section('content_header')
    <h1>Editar Autorização</h1>
@endsection

@section('content')
    <form action="{{ route('autorizacoes.update', $authorization->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <a href="{{ route('autorizacoes.index') }}" class="btn btn-secondary mb-3">← Voltar</a>
        </div>
        {{-- Paciente --}}
        <div class="form-group">
            <label for="patient_id">Paciente</label>
            <select name="patient_id" class="form-control" required>
                <option value="">Selecione...</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}"
                        {{ old('patient_id', $authorization->patient_id) == $patient->id ? 'selected' : '' }}>
                        {{ $patient->name }} ({{ $patient->cpf }})
                    </option>
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
            <input type="text" name="authorization_number" class="form-control" value="{{ old('authorization_number', $authorization->authorization_number) }}" >
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
            <input type="date" name="estimated_end_date" class="form-control" value="{{ old('estimated_end_date', $authorization->estimated_end_date) }}">
        </div>

        <div class="form-group">
            <label for="observation">Observação</label>
            <textarea name="observation" id="observation" class="form-control">{{ old('observation', $authorization->observation ?? '') }}</textarea>
        </div>

        {{-- Modalidades existentes --}}
        <hr>
        <h5>Modalidades</h5>
        <div id="modalities-container">
            @foreach($authorization->modalities as $index => $modality)
                <div class="modality-group border p-3 mb-2">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Modalidade</label>
                            <select name="modalities[{{ $index }}][modality_id]" class="form-control" required>
                                <option value="">Selecione...</option>
                                @foreach($modalities as $m)
                                    <option value="{{ $m->id }}" {{ $m->id == $modality->modality_id ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="modalities[{{ $index }}][quantity_type]" class="form-control" required>
                                <option value="sessões" {{ $modality->quantity_type == 'sessões' ? 'selected' : '' }}>Sessões</option>
                                <option value="sessões/semana" {{ $modality->quantity_type == 'sessões/semana' ? 'selected' : '' }}>Sessões por Semana</option>
                                <option value="horas/semana" {{ $modality->quantity_type == 'horas/semana' ? 'selected' : '' }}>Horas por Semana</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantidade</label>
                            <input type="number" name="modalities[{{ $index }}][quantity]" class="form-control" value="{{ $modality->quantity }}" required>
                        </div>
                        <div class="form-group">
                            <label>Valor Unitário</label>
                            <input type="number" name="modalities[{{ $index }}][unit_value]" step="0.01" class="form-control" value="{{ $modality->unit_value }}">
                        </div>
                        <div class="form-group">
                            <label>Valor Total</label>
                            <input type="number" name="modalities[{{ $index }}][total_value]" step="0.01" class="form-control" value="{{ $modality->total_value }}">
                        </div>
                        <div class="form-group">
                            <label for="last_session_date">Dt. Última Sessão</label>
                            <input type="date" name="modalities[{{ $index }}][last_session_date]" class="form-control"
                                   value="{{ old('last_session_date', $modality->last_session_date ? $modality->last_session_date->format('Y-m-d') : '') }}">
                        </div>
                        {{-- Matrícula ID --}}
                        <div class="form-group">
                            <label>ID da Matrícula</label>
                            <input type="text" name="modalities[{{ $index }}][matricula_id]"
                                   class="form-control"
                                   value="{{ old("modalities.$index.matricula_id", $modality->matricula_id) }}">
                        </div>
                        <div class="form-group">
                            <label>Ações</label>
                            <div style="display: block; padding: .375rem .75rem; height: calc(2.25rem + 2px)">
                            @if ($modality->matricula_id)
                                <a href="https://painel.softwarepilates.com.br/Matricula/Matricula.aspx?c={{ $modality->matricula_id }}"
                                   class="btn btn-sm btn-outline-secondary ms-2"
                                   target="_blank"
                                   title="Editar Matrícula">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @else

                                    <a href="https://painel.softwarepilates.com.br/Matricula/Matricula.aspx"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-secondary ms-2"
                                       title="Criar Matrícula">
                                        <i class="fas fa-plus"></i>
                                    </a>
                            @endif
                            @if ($modality->invoices->isEmpty())

                                <button type="button" class="btn btn-sm btn-outline-danger ms-1"
                                        title="Remover">
                                    <i class="fas fa-trash"></i>
                                </button>

                            @endif
                            </div>
                        </div>
                        {{-- ID oculto da modalidade para o update --}}
                        <input type="hidden" name="modalities[{{ $index }}][id]" value="{{ $modality->id }}">
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-secondary mb-3" id="add-modality-btn">
            + Adicionar Modalidade
        </button>
        {{-- Arquivo de Autorização --}}
        <div class="form-group">
            <label for="files[]">Anexar Novos Arquivos</label>
            <input type="file" name="files[]" class="form-control" multiple>
        </div>

        {{-- Arquivos já anexados --}}
        @if($authorization->files->count())
            <h5>Arquivos já enviados:</h5>
            <ul>
                @foreach($authorization->files as $file)
                    <li><a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">{{ $file->file_name }}</a></li>
                @endforeach
            </ul>
        @endif

        <button class="btn btn-primary">Salvar Alterações</button>
    </form>
@endsection
