<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalarioMinimoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sql=database_path(path:'/seeders/LlenaSalarioMinimo.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
