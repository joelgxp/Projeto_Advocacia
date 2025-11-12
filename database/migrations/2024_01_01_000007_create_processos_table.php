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
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_processo', 50)->unique()->nullable();
            $table->foreignId('cliente_id')->constrained()->onDelete('restrict');
            $table->foreignId('advogado_id')->constrained('advogados')->onDelete('restrict');
            $table->foreignId('vara_id')->constrained()->onDelete('restrict');
            $table->foreignId('especialidade_id')->constrained()->onDelete('restrict');
            $table->foreignId('processado_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->enum('status', ['aberto', 'andamento', 'concluido', 'arquivado', 'cancelado'])->default('aberto');
            $table->date('data_abertura');
            $table->date('data_peticao')->nullable();
            $table->date('data_audiencia')->nullable();
            $table->time('hora_audiencia')->nullable();
            $table->integer('quantidade_audiencias')->default(0);
            $table->text('observacoes')->nullable();
            $table->text('ultima_movimentacao')->nullable();
            $table->date('ultima_movimentacao_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('numero_processo');
            $table->index('status');
            $table->index('data_abertura');
            $table->index('data_audiencia');
            $table->index(['cliente_id', 'status']);
            $table->index(['advogado_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processos');
    }
};




