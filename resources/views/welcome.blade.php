@extends('layouts.main')
@section('title', 'Fisiolates')
@section('content')

<div class="container container-class mt-5 agendamento">
    <h1 class="h1-class">Agenda Semanal</h1>
    <!-- Mensagens de erro e sucesso -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-class">
        <thead>
            <tr>
                <th class="th-class"></th>
                @foreach ($days as $day)
                    <th class="th-class">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($hours as $hour)
                <tr>
                    <td class="td-class">{{ sprintf('%02d:00', $hour) }}</td>
                    @foreach ($days as $day)
                        <td class="td-class">
                            @foreach ($agendamentos as $agendamento)
                                @if ($agendamento->age_day == $day && $agendamento->age_time == sprintf('%02d:00:00', $hour))
                                    <div class="event event-class" data-event-id="{{ $agendamento->id }}">
                                        {{ $agendamento->aluno->alu_nome }} {{ $agendamento->aluno->alu_sobrenome }}
                                    </div>
                                @endif
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <button type="button" class="btn-fisio" data-toggle="modal" data-target="#createEventModal">
        Criar Agendamento
    </button>
    <button type="button" class="btn-fisio-edit" data-toggle="modal" data-target="#editEventModal">
        Editar Agendamento
    </button>

    <!-- Modal de Criação -->
    <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-class">
            <div class="modal-content modal-content-class">
                <div class="modal-header">
                    <h5 class="modal-title" id="createEventModalLabel">Criar Agendamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/agendamento/save" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="day">Dia:</label>
                            <select name="age_day" id="age_day" class="form-control" required>
                                <option value="Segunda-feira">Segunda-feira</option>
                                <option value="Terça-feira">Terça-feira</option>
                                <option value="Quarta-feira">Quarta-feira</option>
                                <option value="Quinta-feira">Quinta-feira</option>
                                <option value="Sexta-feira">Sexta-feira</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="time">Hora:</label>
                            <select name="age_time" id="age_time" class="form-control" required>
                                @foreach (range(7, 20) as $hour)
                                    @if (!in_array($hour, [11, 12, 13]))
                                        <option value="{{ sprintf('%02d:00:00', $hour) }}">
                                            {{ sprintf('%02d:00', $hour) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="eventName">Nome do Aluno:</label>
                            <select name="alu_id" id="alu_id" class="form-control select2" required>
                                <option value="" disabled selected>Selecione o Aluno</option>
                                @foreach ($alunos as $aluno)
                                    <option value="{{ $aluno->id }}">{{ $aluno->alu_nome }}
                                        {{ $aluno->alu_sobrenome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-primary-class btn-add">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-class">
            <div class="modal-content modal-content-class">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Editar Agendamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm" action="/agendamento/update" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="selectedEvent">Selecionar Aluno:</label>
                            <select name="selectedEvent" id="selectedEvent" class="form-control select2" required>
                                <option value="" disabled selected>Selecione um aluno</option>
                                @foreach ($agendamentos->sortBy('aluno.alu_nome') as $agendamento)
                                    <option value="{{ $agendamento->id }}">
                                        {{ $agendamento->aluno->alu_nome }} {{ $agendamento->aluno->alu_sobrenome }}-
                                        {{ $agendamento->age_day }} - {{ $agendamento->age_time }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="day">Dia:</label>
                            <select name="age_day" id="age_day" class="form-control" required>
                                <option value="Segunda-feira">Segunda-feira</option>
                                <option value="Terça-feira">Terça-feira</option>
                                <option value="Quarta-feira">Quarta-feira</option>
                                <option value="Quinta-feira">Quinta-feira</option>
                                <option value="Sexta-feira">Sexta-feira</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="time">Hora:</label>
                            <select name="age_time" id="age_time" class="form-control" required>
                                @foreach (range(7, 20) as $hour)
                                    @if (!in_array($hour, [11, 12, 13]))
                                        <option value="{{ sprintf('%02d:00:00', $hour) }}">
                                            {{ sprintf('%02d:00', $hour) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="edit-buttons d-flex">
                            <button type="submit" class="btn btn-primary btn-primary-class">Salvar</button>
                    </form>

                    <!-- Formulário de exclusão -->
                    <form id="deleteEventForm" action="" method="post" onsubmit="return confirmarExclusao()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-primary-class">
                            <i class="bi bi-trash3-fill"></i>Excluir
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- jQuery, Bootstrap JS e Select2 JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" class="jquery-js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" class="bootstrap-js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js" class="select2-js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .select2-container--open {
        z-index: 9999;
    }
    .modal {
        z-index: 10000;
    }
</style>

<script>
    $(document).ready(function() {
        $('#selectedEvent').change(function() {
            var eventId = $(this).val();
            $('#editEventForm').attr('action', '/agendamento/' + eventId);
            $('#deleteEventForm').attr('action', '/agendamento/delete/' + eventId);
        });

        $('.select2').select2({
            placeholder: "Selecione um aluno",
            allowClear: true
        });
    });

    $('body').on('shown.bs.modal', '.modal', function() {
        $(this).find('select').each(function() {
            var dropdownParent = $(document.body);
            if ($(this).closest('.modal').length) {
                dropdownParent = $(this).closest('.modal');
            }
            $(this).select2({
                dropdownParent: dropdownParent
            });
        });
    });

    function confirmarExclusao() {
        return confirm('Tem certeza que deseja excluir este agendamento?');
    }
</script>

@endsection
