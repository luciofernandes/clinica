<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('health_plans')->insert([
            ['name' => 'GEAP'],
            ['name' => 'Bradesco Saúde'],
            ['name' => 'Unimed'],
            ['name' => 'SulAmérica'],
            ['name' => 'Hapvida'],
            ['name'=>'Cassi'],
        ]);

    }
}
