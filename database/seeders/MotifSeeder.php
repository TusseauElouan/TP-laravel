<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Motif;

class MotifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $motifs = [
            ['libelle' => 'Congé annuel', 'is_accessible_salarie' => true, 'is_deleted' => false],
            ['libelle' => 'Maladie', 'is_accessible_salarie' => true, 'is_deleted' => false],
            ['libelle' => 'Congé sans solde', 'is_accessible_salarie' => true, 'is_deleted' => false],
            ['libelle' => 'Formation', 'is_accessible_salarie' => true, 'is_deleted' => false],
            ['libelle' => 'Congé maternité', 'is_accessible_salarie' => false, 'is_deleted' => false],
            ['libelle' => 'Absence exceptionnelle', 'is_accessible_salarie' => true, 'is_deleted' => false],
            ['libelle' => 'Mission extérieure', 'is_accessible_salarie' => true, 'is_deleted' => false],
            ['libelle' => 'Télétravail', 'is_accessible_salarie' => true, 'is_deleted' => false],
        ];

        foreach ($motifs as $motif) {
            Motif::create($motif);
        }
    }
}
