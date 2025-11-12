<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vara;

class VarasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $varas = [
            ['nome' => 'Vara 1', 'comarca' => 'Comarca Central', 'ativo' => true],
            ['nome' => 'Vara 2', 'comarca' => 'Comarca Central', 'ativo' => true],
            ['nome' => 'Vara 3', 'comarca' => 'Comarca Central', 'ativo' => true],
        ];

        foreach ($varas as $vara) {
            Vara::firstOrCreate(
                ['nome' => $vara['nome']],
                $vara
            );
        }
    }
}




