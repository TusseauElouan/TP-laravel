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
        // Récupération des absences
        $query = Absence::with(['motif', 'user', 'user.colorPreferences'])
            ->where('is_deleted', false);

        if ($userId !== 'all') {
            $query->where('user_id_salarie', $userId);
        } elseif (!Auth::user()->isAn('admin')) {
            $query->where('user_id_salarie', Auth::id());
        }

        $absences = $query->get();

            return [
                'id' => $absence->id,
                'title' => "{$absence->motif->libelle} - {$absence->user->prenom} {$absence->user->nom}",
                'start' => Carbon::parse($absence->date_absence_debut)->format('Y-m-d'),
                'end' => $endDate,
                'backgroundColor' => $absence->isValidated ? '#10B981' : '#EF4444',
                'borderColor' => $absence->isValidated ? '#059669' : '#DC2626',
                'allDay' => true,
                'type' => 'absence',
                'extendedProps' => [
                    'absence_id' => $absence->id,
                    'status' => $absence->isValidated ? 'Validée' : 'En attente',
                    'user' => "{$absence->user->prenom} {$absence->user->nom}",
                    'motif' => $absence->motif->libelle,
                    'dateDebut' => Carbon::parse($absence->date_absence_debut)->format('d/m/Y'),
                    'dateFin' => Carbon::parse($absence->date_absence_fin)->format('d/m/Y'),
                ]
            ];
        });

        // Récupération des jours fériés
        $joursFeries = JourFerie::all()->map(function ($jourFerie) {
            // Même logique pour les jours fériés
            $endDate = Carbon::parse($jourFerie->date)->addDay()->format('Y-m-d');

            return [
                'id' => $jourFerie->id,
                'title' => $jourFerie->nom,
                'start' => Carbon::parse($jourFerie->date)->format('Y-m-d'),
                'end' => $endDate,
                'backgroundColor' => '#4B5563',
                'borderColor' => '#374151',
                'allDay' => true,
                'type' => 'holiday',
                'display' => 'background',
                'color' => '#E5E7EB',
                'extendedProps' => [
                    'holiday_id' => $jourFerie->id,
                    'recurring' => $jourFerie->is_recurring,
                    'date' => Carbon::parse($jourFerie->date)->format('d/m/Y'),
                ]
            ];
        });

        return response()->json([
            'absences' => $absences,
            'holidays' => $joursFeries
        ]);
    }
}
