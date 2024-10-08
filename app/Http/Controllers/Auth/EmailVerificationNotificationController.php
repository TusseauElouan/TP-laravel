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
        if (Auth::check()) {
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', absolute: false));
            }

            $request->user()->sendEmailVerificationNotification();

            return back()->with('status', 'verification-link-sent');
        }

        return redirect()->route('login')->with('error', __('You need to be logged in'));
    }
}
