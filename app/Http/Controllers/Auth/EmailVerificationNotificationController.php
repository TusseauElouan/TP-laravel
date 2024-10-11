<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        // Vérifiez si l'utilisateur est authentifié
        $user = Auth::user();

        if ($user) { // L'utilisateur est connecté
            if ($user->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', absolute: false));
            }

            $user->sendEmailVerificationNotification();

            return back()->with('status', 'verification-link-sent');
        }

        return redirect()->route('login')->with('error', __('You need to be logged in'));
    }
}
