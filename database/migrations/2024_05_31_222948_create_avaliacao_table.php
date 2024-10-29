<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('avaliacao', function (Blueprint $table) {
            $table->id();
            $table->string('ava_diagn_clinico')->nullable();
            $table->string('ava_queixa_principal')->nullable();
            $table->string('ava_hda')->nullable();
            $table->string('ava_hpp')->nullable();
            $table->string('ava_ex_complementar')->nullable();
            $table->string('ava_inspecao')->nullable();
            $table->string('ava_palpacao')->nullable();
            $table->string('ava_teste_articular')->nullable();
            $table->string('ava_teste_muscular')->nullable();
            $table->string('ava_medico')->nullable();
            $table->integer('ava_crm')->nullable();
            $table->string('ava_especialidade')->nullable();
            $table->string('ava_med_fone')->nullable();
            $table->unsignedBigInteger('alu_id')->nullable();
            $table->timestamps();

            $table->foreign('alu_id')->references('id')->on('alunos')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacao');
    }
};
