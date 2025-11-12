<?php

namespace App\Enums;

enum PrazoStatus: string
{
    case PENDENTE = 'pendente';
    case CUMPRIDO = 'cumprido';
    case VENCIDO = 'vencido';
    case CANCELADO = 'cancelado';

    public function label(): string
    {
        return match($this) {
            self::PENDENTE => 'Pendente',
            self::CUMPRIDO => 'Cumprido',
            self::VENCIDO => 'Vencido',
            self::CANCELADO => 'Cancelado',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDENTE => 'warning',
            self::CUMPRIDO => 'success',
            self::VENCIDO => 'danger',
            self::CANCELADO => 'secondary',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}




