<?php

namespace Database\Seeders;

use App\Models\Motif;
use Illuminate\Database\Seeder;

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
