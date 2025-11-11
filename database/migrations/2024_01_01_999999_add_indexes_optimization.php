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
        // Índices adicionais para otimização de queries
        Schema::table('processos', function (Blueprint $table) {
            if (!$this->hasIndex('processos', 'processos_status_index')) {
                $table->index('status', 'processos_status_index');
            }
            if (!$this->hasIndex('processos', 'processos_advogado_status_index')) {
                $table->index(['advogado_id', 'status'], 'processos_advogado_status_index');
            }
            if (!$this->hasIndex('processos', 'processos_cliente_status_index')) {
                $table->index(['cliente_id', 'status'], 'processos_cliente_status_index');
            }
        });

        Schema::table('audiencias', function (Blueprint $table) {
            if (!$this->hasIndex('audiencias', 'audiencias_data_status_index')) {
                $table->index(['data', 'status'], 'audiencias_data_status_index');
            }
        });

        Schema::table('clientes', function (Blueprint $table) {
            if (!$this->hasIndex('clientes', 'clientes_ativo_index')) {
                $table->index('ativo', 'clientes_ativo_index');
            }
        });

        Schema::table('advogados', function (Blueprint $table) {
            if (!$this->hasIndex('advogados', 'advogados_ativo_index')) {
                $table->index('ativo', 'advogados_ativo_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->dropIndex('processos_status_index');
            $table->dropIndex('processos_advogado_status_index');
            $table->dropIndex('processos_cliente_status_index');
        });

        Schema::table('audiencias', function (Blueprint $table) {
            $table->dropIndex('audiencias_data_status_index');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropIndex('clientes_ativo_index');
        });

        Schema::table('advogados', function (Blueprint $table) {
            $table->dropIndex('advogados_ativo_index');
        });
    }

    /**
     * Verificar se índice já existe
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT COUNT(*) as count 
             FROM information_schema.statistics 
             WHERE table_schema = ? 
             AND table_name = ? 
             AND index_name = ?",
            [$databaseName, $table, $indexName]
        );
        
        return $result[0]->count > 0;
    }
};

