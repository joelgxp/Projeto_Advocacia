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
        Schema::create('audiencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->constrained()->onDelete('cascade');
            $table->foreignId('advogado_id')->constrained('advogados')->onDelete('restrict');
            $table->foreignId('cliente_id')->constrained()->onDelete('restrict');
            $table->string('descricao');
            $table->string('tipo')->nullable();
            $table->date('data');
            $table->time('hora');
            $table->string('local');
            $table->text('observacoes')->nullable();
            $table->enum('status', ['agendada', 'realizada', 'cancelada', 'adiada'])->default('agendada');
            $table->boolean('notificado')->default(false);
            $table->date('data_notificacao')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('data');
            $table->index('status');
            $table->index('notificado');
            $table->index(['processo_id', 'data']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audiencias');
    }
};



