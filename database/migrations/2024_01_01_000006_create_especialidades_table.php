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
        Schema::create('especialidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('nome');
            $table->index('ativo');
        });

        // Tabela pivot advogado_especialidades
        Schema::create('advogado_especialidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advogado_id')->constrained()->onDelete('cascade');
            $table->foreignId('especialidade_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['advogado_id', 'especialidade_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advogado_especialidades');
        Schema::dropIfExists('especialidades');
    }
};

