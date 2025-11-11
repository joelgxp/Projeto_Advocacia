<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audiencia extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nome da tabela (pluralização customizada)
     */
    protected $table = 'audiencias';

    protected $fillable = [
        'processo_id',
        'advogado_id',
        'cliente_id',
        'descricao',
        'tipo',
        'data',
        'hora',
        'local',
        'observacoes',
        'status',
        'notificado',
        'data_notificacao',
    ];

    protected $casts = [
        'data' => 'date',
        'hora' => 'datetime',
        'data_notificacao' => 'date',
        'notificado' => 'boolean',
    ];

    /**
     * Relacionamento com processo
     */
    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }

    /**
     * Relacionamento com advogado
     */
    public function advogado()
    {
        return $this->belongsTo(Advogado::class);
    }

    /**
     * Relacionamento com cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}

