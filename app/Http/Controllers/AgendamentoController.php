<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Aluno;
use App\Models\Horario;
use Carbon\Carbon;

class AgendamentoController extends Controller
{

    public function index()
    {
        $agendamentos = Agendamento::with('aluno')->get();
        $alunos = Aluno::all()->sortBy('alu_nome');
        $days = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira'];
        $hours = range(7, 20);
        return view('welcome', compact('agendamentos', 'alunos', 'days', 'hours'));
    }


    public function update(Request $request, $id)
{
    // Obtém os dados do agendamento existente
    $agendamento = Agendamento::find($id);
    if (!$agendamento) {
        return redirect()->back()->with('error', 'Evento não encontrado!');
    }

    // Verifica se o aluno já está agendado no mesmo dia (excluindo o agendamento atual)
    $existingSchedulingForStudent = Agendamento::where('alu_id', $agendamento->alu_id)
        ->where('age_day', $request->input('age_day'))
        ->where('id', '!=', $id) // Exclui o agendamento atual da verificação
        ->exists();

    // Se o aluno já estiver agendado no mesmo dia, retorne um redirecionamento com um erro
    if ($existingSchedulingForStudent) {
        return redirect()->back()->with('error', 'O aluno já está agendado neste dia.');
    }

    // Atualiza os dados do agendamento
    $agendamento->age_day = $request->input('age_day');
    $agendamento->age_time = $request->input('age_time');
    $agendamento->save();

    return redirect()->back()->with('success', 'Agendamento atualizado com sucesso!');
}


public function store(Request $request)
{
    // Verifica se o aluno já está agendado no mesmo dia e horário
    $existingSchedulingForStudent = Agendamento::where('alu_id', $request->alu_id)
        ->where('age_day', $request->age_day)
        ->where('age_time', $request->age_time)
        ->exists();

    // Se o aluno já estiver agendado no mesmo dia e horário, retorne um redirecionamento com um erro
    if ($existingSchedulingForStudent) {
        return redirect()->back()->with('error', 'O aluno já está agendado neste dia e horário.');
    }

    // Verifica se o aluno já está agendado no mesmo dia (independentemente do horário)
    $existingSchedulingForStudentSameDay = Agendamento::where('alu_id', $request->alu_id)
        ->where('age_day', $request->age_day)
        ->exists();

    // Se o aluno já estiver agendado no mesmo dia, retorne um redirecionamento com um erro
    if ($existingSchedulingForStudentSameDay) {
        return redirect()->back()->with('error', 'O aluno já está agendado neste dia.');
    }

    // Verifica se já existem 7 eventos nesta célula de tempo
    $existingSchedulingInCell = Agendamento::where('age_day', $request->age_day)
        ->where('age_time', $request->age_time)
        ->count();

    // Se já houver 7 eventos, retorne um redirecionamento com um erro
    if ($existingSchedulingInCell >= 7) {
        return redirect()->back()->with('error', 'Não é permitido mais de 7 alunos por célula.');
    }

    // Salvar o agendamento
    $agendamento = new Agendamento;
    $agendamento->alu_id = $request->alu_id;
    $agendamento->age_day = $request->age_day;
    $agendamento->age_time = $request->age_time;
    $agendamento->save();

    return redirect()->back()->with('success', 'Evento adicionado com sucesso!');
}



    public function destroy($id)
    {
        Agendamento::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Agendamento excluído!');
    }
}
