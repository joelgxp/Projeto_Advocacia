<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Especialidade;

class EspecialidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidades = [
            ['nome' => 'Criminal', 'descricao' => 'Direito Criminal'],
            ['nome' => 'Trabalhista', 'descricao' => 'Direito do Trabalho'],
            ['nome' => 'Familiar', 'descricao' => 'Direito de Família'],
            ['nome' => 'Civil', 'descricao' => 'Direito Civil'],
            ['nome' => 'Tributário', 'descricao' => 'Direito Tributário'],
        ];

        foreach ($especialidades as $especialidade) {
            Especialidade::firstOrCreate(
                ['nome' => $especialidade['nome']],
                $especialidade
            );
        }
    }
}




