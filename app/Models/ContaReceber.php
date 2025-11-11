<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaReceber extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nome da tabela
     */
    protected $table = 'contas_receber';

    protected $fillable = [
        'processo_id',
        'cliente_id',
        'advogado_id',
        'descricao',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
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
     * Relacionamento com advogado
     */
    public function advogado()
    {
        return $this->belongsTo(Advogado::class);
    }
}

