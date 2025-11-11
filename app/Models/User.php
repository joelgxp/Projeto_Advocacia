<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'telefone',
        'ativo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ativo' => 'boolean',
        ];
    }

    /**
     * Relacionamento com advogado
     */
    public function advogado()
    {
        return $this->hasOne(Advogado::class);
    }

    /**
     * Relacionamento com processos como advogado responsável
     */
    public function processos()
    {
        return $this->hasMany(Processo::class, 'advogado_id');
    }

    /**
     * Relacionamento com tarefas
     */
    public function tarefas()
    {
        return $this->hasMany(Tarefa::class);
    }

    /**
     * Relacionamento com notificações
     */
    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class);
    }

    /**
     * Relacionamento com cliente (se o usuário for um cliente)
     */
    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'cpf_cnpj', 'cpf');
    }

    /**
     * Relacionamento com tarefas delegadas
     */
    public function tarefasDelegadas()
    {
        return $this->hasMany(Tarefa::class, 'delegado_para_id');
    }

    /**
     * Verifica se o usuário está ativo
     */
    public function isAtivo(): bool
    {
        return $this->ativo ?? true;
    }
}

