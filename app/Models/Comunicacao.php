<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comunicacao extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nome da tabela (pluralização customizada)
     */
    protected $table = 'comunicacoes';

    protected $fillable = [
        'processo_id',
        'cliente_id',
        'user_id',
        'tipo',
        'assunto',
        'mensagem',
        'direcao',
        'lida',
        'data_leitura',
        'anexos',
    ];

    protected $casts = [
        'lida' => 'boolean',
        'data_leitura' => 'datetime',
        'anexos' => 'array',
    ];

    /**
     * Relacionamento com processo
     */
    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }

    /**
     * Relacionamento com cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

