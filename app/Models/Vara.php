<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vara extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'comarca',
        'tribunal',
        'endereco',
        'telefone',
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
}





