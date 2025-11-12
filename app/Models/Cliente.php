<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'tipo_pessoa',
        'email',
        'telefone',
        'celular',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'observacoes',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com processos
     */
    public function processos()
    {
        return $this->hasMany(Processo::class);
    }

    /**
     * Relacionamento com documentos
     */
    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    /**
     * Relacionamento com audiências
     */
    public function audiencias()
    {
        return $this->hasMany(Audiencia::class);
    }

    /**
     * Relacionamento com contas a receber
     */
    public function contasReceber()
    {
        return $this->hasMany(ContaReceber::class);
    }

    /**
     * Relacionamento com comunicações
     */
    public function comunicacoes()
    {
        return $this->hasMany(Comunicacao::class);
    }

    /**
     * Relacionamento com tarefas
     */
    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }
}




