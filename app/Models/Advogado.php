<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advogado extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'oab',
        'foto',
        'biografia',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com processos
     */
    public function processos()
    {
        return $this->hasMany(Processo::class);
    }

    /**
     * Relacionamento com audiências
     */
    public function audiencias()
    {
        return $this->hasMany(Audiencia::class);
    }

    /**
     * Relacionamento com especialidades
     */
    public function especialidades()
    {
        return $this->belongsToMany(Especialidade::class, 'advogado_especialidades');
    }

    /**
     * Relacionamento com contas a receber
     */
    public function contasReceber()
    {
        return $this->hasMany(ContaReceber::class);
    }
}


