@extends('layouts.main')
@section('title', 'Editando: ' . $aluno->alu_nome . ' ' . $aluno->alu_sobrenome)
@section('content')


<div id="aluno-register-container" class="col-md-6 offset-md-3 dados">
    <h1>Edite os dados de {{ $aluno->alu_nome }} {{ $aluno->alu_sobrenome }}</h1>
    <form action="/aluno/update/{{ $aluno->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title" id="form-label">Nome do aluno:</label>
            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="alu_nome" name="alu_nome" aluno" value="{{ $aluno->alu_nome }}">

            @error('nome')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

        </div>
        <div class="form-group">
            <label for="title" id="form-label">Sobrenome do aluno:</label>
            <input type="text" class="form-control @error('sobrenome') is-invalid @enderror" id="alu_sobrenome" name="alu_sobrenome"
             e do aluno" value="{{ $aluno->alu_sobrenome }}">

            @error('sobrenome')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

        </div>
        <div class="form-group">
            <label for="title" id="form-label">CPF:</label>
            <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="alu_cpf" name="alu_cpf" luno" maxlength="14" value="{{ $aluno->alu_cpf }}">

            @error('cpf')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="title" id="form-label">Endereço:</label>
            <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="alu_end" name="alu_end"  do aluno" value="{{ $aluno->alu_end }}">

            @error('endereco')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="title" id="form-label">Bairro:</label>
            <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="alu_bairro" name="alu_bairro" o aluno" value="{{ $aluno->alu_bairro }}">

            @error('bairro')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="title" id="form-label">Cidade:</label>
            <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="alu_cidade" name="alu_cidade" o aluno" value="{{ $aluno->alu_cidade }}">

            @error('cidade')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="title" id="form-label">Telefone:</label>
            <input type="text" class="form-control" id="alu_fone" name="alu_fone"  residencial do aluno" maxlength="14" value="{{ $aluno->alu_fone }}">
        </div>
        <div class="form-group">
            <label for="title" id="form-label">Celular:</label>
            <input type="text" class="form-control @error('celular') is-invalid @enderror" id="alu_celular" name="alu_celular"  maxlength="15" value="{{ $aluno->alu_celular }}">

            @error('celular')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="title" id="form-label">Sexo:</label>
            <select name="alu_sexo" id="alu_sexo" class="form-control">
                <option value="F" {{ $aluno->alu_sexo == 'F' ? 'selected' : '' }}>Feminino</option>
                <option value="M" {{ $aluno->alu_sexo == 'M' ? 'selected' : '' }}>Masculino</option>
            </select>
        </div>
        <div class="form-group">
            <label for="title" id="form-label">Data de nascimento:</label>
            <input type="date" class="form-control @error('dtnasc') is-invalid @enderror" id="alu_dtnasc" name="alu_dtnasc" value="{{ $aluno->alu_dtnasc }}">

            @error('dtnasc')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="btn-list">    
        <input type="submit" value="Atualizar aluno" class="btn-fisio" id="send">
        <a href="/avaliacao/{{ $aluno->id }}" class="btn-fisio"> Adicionar avaliação</a>
        @if($avaliacao)
        <a href="/avaliacao/edit/{{ $aluno->id }}/{{ $avaliacao->id }}" class="btn-fisio">Exibir avaliação</a>
        @endif
        </div>
        
    </form>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var cpfInput = document.getElementById('alu_cpf');

        VMasker(cpfInput).maskPattern('999.999.999-99');
    });

    document.addEventListener('DOMContentLoaded', function() {
        var telefoneInput = document.getElementById('alu_fone');

        VMasker(telefoneInput).maskPattern('(99) 9999-9999');
    });

    document.addEventListener('DOMContentLoaded', function() {
        var celularInput = document.getElementById('alu_celular');

        VMasker(celularInput).maskPattern('(99) 99999-9999');
    });
</script>



@endsection