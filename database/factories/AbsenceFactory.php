<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Motif;
use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Motif>
 */
class AbsenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::now()->addDays(rand(0, 60));
        $absenceDuration = rand(1, 15);
        $endDate = $startDate->copy()->addDays($absenceDuration);

        return [
            'motif_id' => Motif::all()->random()->id,
            'user_id_salarie' => User::all()->random()->id,
            'date_absence_debut' => $startDate,
            'date_absence_fin' => $endDate,
            'isValidated' => false,
            'is_deleted' => false
        ];
    }
}
