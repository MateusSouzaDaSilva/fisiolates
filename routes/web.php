<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\auth\logoutController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AgendamentoController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

use App\Models\Aluno;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::get('/lista-alunos', [AlunoController::class, 'exibir'])->name('exibir-alunos');

    Route::get('/lista-alunos/create', [AlunoController::class, 'create'])->name('cadastrar-aluno');

    Route::get('/aluno/edit/{id}', [AlunoController::class, 'edit']);

    Route::put('/aluno/update/{id}', [AlunoController::class, 'update']);

    Route::delete('/aluno/{id}', [AlunoController::class, 'destroy']);

    Route::post('/aluno/save', [AlunoController::class, 'store'])->name('salvarAluno');

    Route::post('/aluno/{id}/pagou', [AlunoController::class, 'pagou'])->name('aluno.pagou');

    //Para o register necessitar de autenticação
    // Route::middleware(['auth'])->get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/avaliacao/{id}', [AvaliacaoController::class, 'exibirAluno']);

    Route::post('/avaliacao/salvar', [AvaliacaoController::class, 'store'])->name('salvarAvaliacao');

    Route::get('/avaliacao/edit/{alunoId}/{avaliacaoId}', [AvaliacaoController::class, 'edit'])->name('avaliacao.edit');

    Route::delete('/avaliacao/deletar/{avaliacaoId}', [AvaliacaoController::class, 'destroy']);

    Route::put('/avaliacao/update/{avaliacaoId}', [AvaliacaoController::class, 'update'])->name('avaliacao.update');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/', [AgendamentoController::class, 'index']);

    Route::post('/agendamento/save', [AgendamentoController::class, 'store']);

    Route::delete('/agendamento/delete/{id}', [AgendamentoController::class, 'destroy']);

    Route::put('/agendamento/update/{id}', [AgendamentoController::class, 'update']);

    Route::resource('agendamento', AgendamentoController::class);
});

Route::post('/logout', [logoutController::class, 'logout'])->name('logout');