<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cargos = [
            ['nome' => 'Advogado', 'descricao' => 'Advogado do escritório'],
            ['nome' => 'Tesoureiro', 'descricao' => 'Responsável pelo financeiro'],
            ['nome' => 'Recepcionista', 'descricao' => 'Atendimento e recepção'],
            ['nome' => 'Motoboy', 'descricao' => 'Entrega de documentos'],
        ];

        foreach ($cargos as $cargo) {
            Cargo::firstOrCreate(
                ['nome' => $cargo['nome']],
                $cargo
            );
        }
    }
}

