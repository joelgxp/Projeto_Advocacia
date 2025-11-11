<?php

namespace App\Enums;

enum ProcessoStatus: string
{
    case ABERTO = 'aberto';
    case ANDAMENTO = 'andamento';
    case CONCLUIDO = 'concluido';
    case ARQUIVADO = 'arquivado';
    case CANCELADO = 'cancelado';

    public function label(): string
    {
        return match($this) {
            self::ABERTO => 'Aberto',
            self::ANDAMENTO => 'Em Andamento',
            self::CONCLUIDO => 'Concluído',
            self::ARQUIVADO => 'Arquivado',
            self::CANCELADO => 'Cancelado',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ABERTO => 'secondary',
            self::ANDAMENTO => 'primary',
            self::CONCLUIDO => 'success',
            self::ARQUIVADO => 'dark',
            self::CANCELADO => 'danger',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}


