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
        Schema::create('movimentacoes_processuais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data');
            $table->string('origem')->nullable()->comment('tribunal, manual, api');
            $table->json('dados_api')->nullable()->comment('Dados retornados da API do tribunal');
            $table->boolean('importado_api')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('data');
            $table->index('origem');
            $table->index(['processo_id', 'data']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_processuais');
    }
};

