<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Aluno;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    public function store(Request $request) {

       
        $avaliacao = new Avaliacao();


        $avaliacao->alu_id = $request->alu_id;

        $avaliacao->ava_diagn_clinico = $request->ava_diagn_clinico;
        $avaliacao->ava_queixa_principal = $request->ava_queixa_principal;
        $avaliacao->ava_hda = $request->ava_hda;
        $avaliacao->ava_hpp = $request->ava_hpp;
        $avaliacao->ava_ex_complementar = $request->ava_ex_complementar;
        $avaliacao->ava_inspecao = $request->ava_inspecao;
        $avaliacao->ava_palpacao = $request->ava_palpacao;
        $avaliacao->ava_teste_articular = $request->ava_teste_articular;
        $avaliacao->ava_teste_muscular = $request->ava_teste_muscular;
        $avaliacao->ava_medico = $request->ava_medico;
        $avaliacao->ava_crm = $request->ava_crm;
        $avaliacao->ava_especialidade = $request->ava_especialidade;
        $avaliacao->ava_med_fone = $request->ava_med_fone;

        $avaliacao->save();

        return redirect('/');
        
    }

    public function exibirAluno($id) {
        $aluno = Aluno::findOrFail($id);
    

        return view("avaliacao", ["aluno" => $aluno]);
    }

    public function edit($alunoId, $avaliacaoId)
    {
        $aluno = Aluno::find($alunoId);
        $avaliacao = Avaliacao::find($avaliacaoId);
        $avaliacoes = Avaliacao::where('alu_id', $alunoId)->get();

        return view('exibir-avaliacao', compact('aluno', 'avaliacao', 'avaliacoes', 'avaliacaoId'));
    }

    public function update(Request $request, $avaliacaoId)
    {
        $avaliacao = Avaliacao::find($avaliacaoId);
        $avaliacao->update($request->all());

        return redirect()->route('avaliacao.edit', ['alunoId' => $avaliacao->alu_id, 'avaliacaoId' => $avaliacao->id])
            ->with('success', 'Avaliação atualizada com sucesso!');
    }

    public function destroy($avaliacaoId)
    {
        
        $avaliacao = Avaliacao::find($avaliacaoId);
        if ($avaliacao) {
            $alunoId = $avaliacao->alu_id;
            $avaliacao->delete();

            return redirect()->route('avaliacao.edit', ['alunoId' => $alunoId, 'avaliacaoId' => Avaliacao::where('alu_id', $alunoId)->first()->id ?? ''])
                ->with('success', 'Avaliação excluída com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Avaliação não encontrada!');
        }
    }
}
