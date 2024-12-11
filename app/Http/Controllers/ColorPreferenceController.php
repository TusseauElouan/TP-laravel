<?php

namespace App\Http\Controllers;

use App\Models\ColorPreference;
use App\Models\Motif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColorPreferenceController extends Controller
{
    public function index()
    {
        $motifs = Motif::all();
        $preferences = Auth::user()->colorPreferences()->get()->keyBy('motif_id');

        return view('preferences.colors', compact('motifs', 'preferences'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'colors' => 'required|array',
            'colors.*.background_color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'colors.*.text_color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'colors.*.border_color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
        ]);

        foreach ($validatedData['colors'] as $motifId => $colors) {
            ColorPreference::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'motif_id' => $motifId,
                ],
                [
                    'background_color' => $colors['background_color'],
                    'text_color' => $colors['text_color'],
                    'border_color' => $colors['border_color'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Préférences de couleurs enregistrées avec succès');
    }
}
