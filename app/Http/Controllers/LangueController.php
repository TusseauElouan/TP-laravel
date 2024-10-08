<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LangueController extends Controller
{
    /**
     * Change the application language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|in:fr,en', // Ajoutez ici les codes de langue supportés
        ]);

        session()->put('locale', $validated['locale']);
        App::setLocale($validated['locale']);

        return redirect()->back();
    }
}
