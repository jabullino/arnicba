<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
Use Illuminate\Support\Facades\DB;

class GradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $sql=database_path(path:'/seeders/LlenaGrados.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
