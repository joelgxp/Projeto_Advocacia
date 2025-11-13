<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarefa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'processo_id',
        'cliente_id',
        'delegado_para_id',
        'titulo',
        'descricao',
        'data',
        'hora',
        'prioridade',
        'status',
        'notificado',
        'data_conclusao',
        'observacoes',
    ];

    protected $casts = [
        'data' => 'date',
        'hora' => 'datetime',
        'data_conclusao' => 'datetime',
        'notificado' => 'boolean',
    ];

    /**
     * Relacionamento com usuário (responsável)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
     * Relacionamento com usuário delegado
     */
    public function delegadoPara()
    {
        return $this->belongsTo(User::class, 'delegado_para_id');
    }
}





