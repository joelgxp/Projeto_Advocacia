<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Especialidade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com processos
     */
    public function processos()
    {
        return $this->hasMany(Processo::class);
    }

    /**
     * Relacionamento com advogados
     */
    public function advogados()
    {
        return $this->belongsToMany(Advogado::class, 'advogado_especialidades');
    }
}





