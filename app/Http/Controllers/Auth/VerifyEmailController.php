<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Summary of __invoke
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($user && $user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
