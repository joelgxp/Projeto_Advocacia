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
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tipo')->comment('prazo, audiencia, movimentacao, sistema');
            $table->string('titulo');
            $table->text('mensagem');
            $table->string('link')->nullable();
            $table->boolean('lida')->default(false);
            $table->timestamp('data_leitura')->nullable();
            $table->json('dados')->nullable();
            $table->timestamps();
            
            $table->index('tipo');
            $table->index('lida');
            $table->index(['user_id', 'lida']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};



