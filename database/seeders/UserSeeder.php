<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)
            ->create();
        if (! User::find(11)) {
            User::create(['nom' => 'Tusseau', 'prenom' => 'Elouan', 'email' => 'tusseauelouan@gmail.com', 'password' => Hash::make('Tusse@u05'), 'isAdmin' => true]);
        }
    }
}
