<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\JourFerie;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index(): View
    {
        $users = [];
        if (Auth::user()->isAn('admin')) {
            $users = User::all();
        }

        return view('calendar.index', compact('users'));
    }

    public function getAbsences(string $userId = 'all'): JsonResponse
    {
        $query = Absence::with(['motif', 'user', 'user.colorPreferences'])
            ->where('is_deleted', false);

        if ($userId !== 'all') {
            $query->where('user_id_salarie', $userId);
        } elseif (!Auth::user()->isAn('admin')) {
            $query->where('user_id_salarie', Auth::id());
        }

        $events = [];

        // Traitement des absences
        $absences = $query->get();
        foreach ($absences as $absence) {
            $colorPreference = $absence->user->colorPreferences
                ->where('motif_id', $absence->motif_id)
                ->first();

            $colors = $colorPreference ? [
                'backgroundColor' => $colorPreference->background_color,
                'textColor' => $colorPreference->text_color ?? '#FFFFFF',
                'borderColor' => $colorPreference->border_color
            ] : [
                'backgroundColor' => $absence->isValidated ? '#10B981' : '#EF4444',
                'textColor' => '#FFFFFF',
                'borderColor' => $absence->isValidated ? '#059669' : '#DC2626'
            ];

            $events[] = array_merge([
                'id' => $absence->id,
                'title' => "{$absence->motif->libelle} - {$absence->user->prenom} {$absence->user->nom}",
                'start' => $absence->date_absence_debut,
                'end' => $absence->date_absence_fin,
                'allDay' => true,
                'type' => 'absence',
                'extendedProps' => [
                    'absence_id' => $absence->id,
                    'status' => $absence->isValidated ? 'Validée' : 'En attente',
                    'user' => "{$absence->user->prenom} {$absence->user->nom}",
                    'motif' => $absence->motif->libelle
                ]
            ], $colors);
        }

        // Traitement des jours fériés
        $joursFeries = JourFerie::all();
        foreach ($joursFeries as $jourFerie) {
            $events[] = [
                'id' => 'holiday-' . $jourFerie->id,
                'title' => $jourFerie->nom,
                'start' => $jourFerie->date,
                'end' => $jourFerie->date,
                'backgroundColor' => '#4B5563',
                'borderColor' => '#374151',
                'allDay' => true,
                'type' => 'holiday',
                'display' => 'background',
                'color' => '#E5E7EB'
            ];
        }

        return response()->json($events);
    }
}
