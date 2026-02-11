<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplatePeticao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'templates_peticoes';

    protected $fillable = [
        'nome',
        'descricao',
        'categoria',
        'conteudo',
        'variaveis',
        'ativo',
        'user_id',
    ];

    protected $casts = [
        'variaveis' => 'array',
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com usuário criador
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para templates ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope por categoria
     */
    public function scopeCategoria($query, string $categoria)
    {
        return $query->where('categoria', $categoria);
    }
}
