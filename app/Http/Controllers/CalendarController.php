<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
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

        $absences = $query->get();

        // Transformation des absences pour inclure les préférences de couleur
        $absences = $absences->map(function ($absence) {
            $colorPreference = $absence->user->colorPreferences
                ->where('motif_id', $absence->motif_id)
                ->first();

            // Ajout des couleurs par défaut si aucune préférence n'est trouvée
            $absence->color_preference = $colorPreference ? [
                'background_color' => $colorPreference->background_color,
                'text_color' => $colorPreference->text_color ?? '#FFFFFF',
                'border_color' => $colorPreference->border_color
            ] : [
                'background_color' => $absence->isValidated ? '#10B981' : '#EF4444',
                'text_color' => '#FFFFFF',
                'border_color' => $absence->isValidated ? '#059669' : '#DC2626'
            ];

            return $absence;
        });

        return response()->json($absences);
    }
}
