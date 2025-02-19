<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanningHistory;

class PlanningHistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = PlanningHistory::with(['user']) // Supprime 'planning'
            ->when($request->action_type, function ($query, $actionType) {
                return $query->where('action_type', $actionType);
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('planning-history.index', compact('histories'));
    }
}
