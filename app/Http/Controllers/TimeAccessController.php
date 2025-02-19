<?php

namespace App\Http\Controllers;

use App\Models\TimeAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeAccessController extends Controller
{
    public function index()
    {
        $users = User::with('timeAccess')->get();
        foreach($users as $user) {
            if (!$user->timeAccess) {
                TimeAccess::create([
                    'user_id' => $user->id,
                    'start_time' => '08:00',
                    'end_time' => '18:00',
                    'is_active' => true
                ]);
            }
        }

        $users = User::with('timeAccess')->get();
        return view('time-access.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        // Nettoyage des heures pour s'assurer qu'elles sont au format H:i
        $startTime = substr($request->start_time, 0, 5);
        $endTime = substr($request->end_time, 0, 5);

        try {
            // Validation que les heures sont bien au format attendu
            $startCarbon = Carbon::createFromFormat('H:i', $startTime);
            $endCarbon = Carbon::createFromFormat('H:i', $endTime);

            // Vérifier que l'heure de fin est après l'heure de début
            if ($endCarbon->lte($startCarbon)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['end_time' => 'L\'heure de fin doit être après l\'heure de début']);
            }

            // Mettre à jour ou créer l'entrée TimeAccess
            TimeAccess::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_active' => $request->has('is_active')
                ]
            );

            return redirect()->route('time-access.index')
                ->with('success', 'Plages horaires mises à jour avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Format d\'heure invalide']);
        }
    }
}
