<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovimentacaoProcessual extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nome da tabela
     */
    protected $table = 'movimentacoes_processuais';

    protected $fillable = [
        'processo_id',
        'user_id',
        'titulo',
        'descricao',
        'data',
        'origem',
        'dados_api',
        'importado_api',
    ];

    protected $casts = [
        'data' => 'date',
        'dados_api' => 'array',
        'importado_api' => 'boolean',
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

