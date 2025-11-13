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
        Schema::create('comunicacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->enum('tipo', ['email', 'whatsapp', 'sms', 'sistema', 'telefone', 'presencial'])->default('sistema');
            $table->string('assunto')->nullable();
            $table->text('mensagem');
            $table->enum('direcao', ['enviada', 'recebida'])->default('enviada');
            $table->boolean('lida')->default(false);
            $table->timestamp('data_leitura')->nullable();
            $table->json('anexos')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('tipo');
            $table->index('direcao');
            $table->index('lida');
            $table->index(['cliente_id', 'lida']);
            $table->index(['processo_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunicacoes');
    }
};





