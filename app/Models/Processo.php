<?php

namespace App\Models;

use App\Enums\ProcessoStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Processo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_processo',
        'cliente_id',
        'advogado_id',
        'vara_id',
        'especialidade_id',
        'processado_id',
        'status',
        'data_abertura',
        'data_peticao',
        'data_audiencia',
        'hora_audiencia',
        'quantidade_audiencias',
        'observacoes',
        'ultima_movimentacao',
        'ultima_movimentacao_data',
    ];

    protected $casts = [
        'data_abertura' => 'date',
        'data_peticao' => 'date',
        'data_audiencia' => 'date',
        'hora_audiencia' => 'datetime',
        'ultima_movimentacao_data' => 'date',
        'status' => ProcessoStatus::class,
    ];

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

    /**
     * Relacionamento com vara
     */
    public function vara()
    {
        return $this->belongsTo(Vara::class);
    }

    /**
     * Relacionamento com especialidade
     */
    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class);
    }

    /**
     * Relacionamento com processado
     */
    public function processado()
    {
        return $this->belongsTo(Cliente::class, 'processado_id');
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
     * Relacionamento com prazos
     */
    public function prazos()
    {
        return $this->hasMany(Prazo::class);
    }

    /**
     * Relacionamento com movimentações processuais
     */
    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoProcessual::class);
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

    /**
     * Accessor para status label
     */
    public function getStatusLabelAttribute(): ?string
    {
        return $this->status?->label();
    }

    /**
     * Accessor para status color
     */
    public function getStatusColorAttribute(): ?string
    {
        return $this->status?->color();
    }
}
