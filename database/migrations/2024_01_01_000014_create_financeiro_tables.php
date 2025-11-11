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
        // Tabela de contas a receber (honorários)
        Schema::create('contas_receber', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cliente_id')->constrained()->onDelete('restrict');
            $table->foreignId('advogado_id')->constrained('advogados')->onDelete('restrict');
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->enum('status', ['pendente', 'pago', 'cancelado'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('data_vencimento');
            $table->index('status');
            $table->index(['cliente_id', 'status']);
            $table->index(['processo_id', 'status']);
        });

        // Tabela de contas a pagar
        Schema::create('contas_pagar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->enum('status', ['pendente', 'pago', 'cancelado'])->default('pendente');
            $table->string('arquivo_comprovante')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('data_vencimento');
            $table->index('status');
        });

        // Tabela de movimentações financeiras
        Schema::create('movimentacoes_financeiras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->enum('tipo', ['entrada', 'saida']);
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data');
            $table->foreignId('conta_receber_id')->nullable()->constrained('contas_receber')->onDelete('set null');
            $table->foreignId('conta_pagar_id')->nullable()->constrained('contas_pagar')->onDelete('set null');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('tipo');
            $table->index('data');
            $table->index(['tipo', 'data']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_financeiras');
        Schema::dropIfExists('contas_pagar');
        Schema::dropIfExists('contas_receber');
    }
};


