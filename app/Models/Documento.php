<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'processo_id',
        'cliente_id',
        'user_id',
        'titulo',
        'descricao',
        'tipo',
        'arquivo',
        'nome_original',
        'mime_type',
        'tamanho',
        'versao',
        'documento_anterior_id',
        'tags',
        'publico',
    ];

    protected $casts = [
        'tags' => 'array',
        'publico' => 'boolean',
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

    /**
     * Relacionamento com documento anterior (versionamento)
     */
    public function documentoAnterior()
    {
        return $this->belongsTo(Documento::class, 'documento_anterior_id');
    }

    /**
     * Relacionamento com versões posteriores
     */
    public function versoesPosteriores()
    {
        return $this->hasMany(Documento::class, 'documento_anterior_id');
    }
}



