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
        Schema::create('prazos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['judicial', 'administrativo', 'outro'])->default('judicial');
            $table->date('data_vencimento');
            $table->date('data_calculo')->nullable();
            $table->integer('dias_uteis')->nullable();
            $table->enum('status', ['pendente', 'em_alert', 'vencido', 'concluido'])->default('pendente');
            $table->boolean('notificado')->default(false);
            $table->date('data_notificacao')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('data_vencimento');
            $table->index('status');
            $table->index('notificado');
            $table->index(['processo_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prazos');
    }
};


