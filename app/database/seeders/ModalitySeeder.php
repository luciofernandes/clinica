<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('modalities')->insert([
            ['name' => 'FISIOTERAPIA INFANTIL	', 'description' => 'FISIOTERAPIA INFANTIL'],
            ['name' => 'FONOAUDIOLOGIA', 'description' => 'FONOAUDIOLOGIA'],
            ['name' => 'MUSICOTERAPIA', 'description' => 'MUSICOTERAPIA'],
            ['name' => 'NEUROPEDIATRIA', 'description' => 'NEUROPEDIATRIA'],
            ['name' => 'OSTEOPATIA INFANTIL	', 'description' => 'OSTEOPATIA INFANTIL'],
            ['name' => 'PEDIASUIT', 'description' => 'PEDIASUIT'],
            ['name' => 'PSICOLOGIA INFANTIL	', 'description' => 'PSICOLOGIA INFANTIL'],
            ['name' => 'PSICOPEDAGOGIA', 'description' => 'PSICOPEDAGOGIA'],
            ['name' => 'RPG', 'description' => 'RPG'],
        ]);
    }
}
