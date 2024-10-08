<?php

namespace Database\Seeders;

use App\Models\Motif;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MotifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Motif::factory(10)
        ->create();
    }
}
