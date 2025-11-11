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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('cliente_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('tipo')->nullable();
            $table->string('arquivo');
            $table->string('nome_original');
            $table->string('mime_type')->nullable();
            $table->integer('tamanho')->nullable();
            $table->integer('versao')->default(1);
            $table->foreignId('documento_anterior_id')->nullable()->constrained('documentos')->onDelete('set null');
            $table->json('tags')->nullable();
            $table->boolean('publico')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('tipo');
            $table->index('versao');
            $table->index(['processo_id', 'tipo']);
            $table->index(['cliente_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};


