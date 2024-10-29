<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Agendamento;
use App\Models\Avaliacao;
use Carbon\Carbon;


use Illuminate\Http\Request;

class AlunoController extends Controller
{

    // public function index() {
    //     $alunos = Aluno::pluck('alu_nome', 'id');
    //     $agendamentos = Agendamento::with('aluno')->get();
    //     return view('welcome', compact('agendamentos', 'alunos'));
        
    // }

    public function exibir() {

        $currentDate = Carbon::now();
    
        $validAlunos = Aluno::where('alu_dtvencimento', '>=', $currentDate)->get();
        $expiredAlunos = Aluno::where('alu_dtvencimento', '<', $currentDate)->get();

        return view("lista-alunos", compact('validAlunos', 'expiredAlunos'));
    }

   

    public function create() {
        return view("events.registrar-aluno");
    }

    public function store(Request $request) {

        $message = [
            'nome.required' => 'O campo Nome é obrigatório.',
            'cpf.required' => 'O campo CPF é obrigatório.',
            'celular.required' => 'O campo Celular é obrigatório.',
            'dtnasc.required' => 'O campo Data de Nascimento é obrigatório.',
            'dtnasc.date' => 'O campo Data de Nascimento deve ser uma data válida.',
            'dtvencimento.required' => 'O campo Data de Vencimento é obrigatório.',
            'dtvencimento.date' => 'O campo Data de Vencimento deve ser uma data válida.',
        ];
    

        $request->validate([
            'nome' => 'required',
            'sobrenome' => 'required',
            'cpf' => 'required',
            'celular' => 'required',
            'dtnasc' => 'required|date',
            'dtvencimento' => 'required|date'
        ], $message);
        
        $aluno = new Aluno();

        $aluno->alu_nome = $request->nome;
        $aluno->alu_sobrenome = $request->sobrenome;
        $aluno->alu_cpf = $request->cpf;
        $aluno->alu_end = $request->endereco;
        $aluno->alu_bairro = $request->bairro;
        $aluno->alu_cidade = $request->cidade;
        $aluno->alu_fone = $request->telefone;
        $aluno->alu_celular = $request->celular;
        $aluno->alu_sexo = $request->sexo;
        $aluno->alu_dtnasc = $request->dtnasc;
        $aluno->alu_dtvencimento = $request->dtvencimento;
        
        $aluno->save();

        return redirect('/')->with('msg', 'Aluno adicionado com sucesso!');
            
    }

  

    public function edit($id) {

        $aluno = Aluno::findOrFail($id);
        $avaliacao = $aluno->avaliacoes()->latest()->first();

        if (!$avaliacao) {
            // Se não existir, defina $avaliacao como null
            $avaliacao = null;
        }

        return view("events.editar-aluno", compact('aluno', 'avaliacao'));
    }

    public function update(Request $request) {

        try {
            // Encontre o aluno pelo ID
            $aluno = Aluno::findOrFail($request->id);
    
            // Atualize os campos do aluno com os dados do formulário
            $aluno->update($request->all());
    
            // Redirecione para a lista de alunos com uma mensagem de sucesso
            return redirect('lista-alunos')->with('success', 'Aluno editado com sucesso!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Caso o aluno não seja encontrado, redirecione com uma mensagem de erro
            return redirect('lista-alunos')->with('error', 'Aluno não encontrado.');
        } catch (\Exception $e) {
            // Caso ocorra algum erro inesperado, redirecione com uma mensagem de erro
            return redirect('lista-alunos')->with('error', 'Erro ao editar aluno.');
        }
    }

    public function destroy($id) {

        Aluno::findOrFail($id)->delete();

        return redirect('lista-alunos')->with('msg', 'Evento excluído com sucesso!');
    }

    public function pagou($id) {
      // Encontre o aluno pelo ID
      $aluno = Aluno::findOrFail($id);

      // Obtenha a data de vencimento atual e adicione um mês
      $novaDataVencimento = Carbon::parse($aluno->alu_dtvencimento)->addMonth();

      // Atualize a coluna alu_dtvencimento
      $aluno->alu_dtvencimento = $novaDataVencimento;
      $aluno->save();

      // Redirecione ou retorne uma resposta apropriada
      return redirect()->back()->with('success', 'Data de vencimento atualizada para o próximo mês.');
    }
}