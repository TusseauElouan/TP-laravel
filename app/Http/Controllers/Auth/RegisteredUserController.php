<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'nom' => ['required', 'string', 'max:255'],  // Last Name (nom)
            'prenom' => ['required', 'string', 'max:255'],  // First Name (prenom)
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = new User();
        $user->nom = $validatedData['nom'];
        $user->prenom = $validatedData['prenom'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);

        $user->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
