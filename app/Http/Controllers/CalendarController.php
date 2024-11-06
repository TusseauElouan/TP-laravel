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
        $query = Absence::with(['motif', 'user'])
            ->where('is_deleted', false);

        if ($userId !== 'all') {
            $query->where('user_id_salarie', $userId);
        } elseif (!Auth::user()->isAn('admin')) {
            $query->where('user_id_salarie', Auth::id());
        }

        $absences = $query->get();

        return response()->json($absences);
    }
}
