<?php

namespace App\Models;

use App\Enums\PrazoStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prazo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'processo_id',
        'user_id',
        'titulo',
        'descricao',
        'tipo',
        'data_vencimento',
        'data_calculo',
        'dias_uteis',
        'status',
        'notificado',
        'data_notificacao',
        'observacoes',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_calculo' => 'date',
        'data_notificacao' => 'date',
        'notificado' => 'boolean',
        'status' => PrazoStatus::class,
    ];

    /**
     * Relacionamento com processo
     */
    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

