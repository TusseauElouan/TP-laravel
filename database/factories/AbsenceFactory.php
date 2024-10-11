<?php

namespace Database\Factories;

use App\Models\Motif;
use App\Models\User;
use Carbon\Carbon;
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
        $startDate = Carbon::now()->addDays(rand(0, 59));
        $absenceDuration = rand(1, 14);
        $endDate = $startDate->copy()->addDays($absenceDuration);

        return [
            'motif_id' => Motif::all()->random()->id,
            'user_id_salarie' => User::all()->random()->id,
            'date_absence_debut' => $startDate,
            'date_absence_fin' => $endDate,
            'isValidated' => false,
            'is_deleted' => false,
        ];
    }
}
