<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $sql=database_path(path:'/seeders/LlenaCursos.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
