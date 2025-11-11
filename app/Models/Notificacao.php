<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use HasFactory;

    /**
     * Nome da tabela (pluralização customizada)
     */
    protected $table = 'notificacoes';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'mensagem',
        'link',
        'lida',
        'data_leitura',
        'dados',
    ];

    protected $casts = [
        'lida' => 'boolean',
        'data_leitura' => 'datetime',
        'dados' => 'array',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Marcar como lida
     */
    public function marcarComoLida()
    {
        $this->update([
            'lida' => true,
            'data_leitura' => now(),
        ]);
    }
}

