<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TimeRestriction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Les administrateurs ne sont pas soumis aux restrictions horaires
        if ($user->isAn('admin')) {
            return $next($request);
        }

        // Récupérer les plages horaires de l'utilisateur
        $timeAccess = $user->timeAccess()->first();

        // Si aucune configuration spécifique n'existe, utiliser les valeurs par défaut
        if (!$timeAccess) {
            $startTime = Carbon::createFromTimeString('08:00:00');
            $endTime = Carbon::createFromTimeString('18:00:00');
        } else {
            if (!$timeAccess->is_active) {
                return redirect()->route('time-restriction')
                    ->with('error', 'Votre accès à l\'application a été désactivé.');
            }
            $startTime = Carbon::createFromTimeString($timeAccess->start_time);
            $endTime = Carbon::createFromTimeString($timeAccess->end_time);
        }

        $currentTime = Carbon::now();
        $currentTime->setSeconds(0); // Ignorer les secondes pour la comparaison

        // Vérifier si l'heure actuelle est dans la plage autorisée
        if ($currentTime->between($startTime, $endTime)) {
            return $next($request);
        }

        // Rediriger vers une page d'erreur avec un message explicatif
        return redirect()->route('time-restriction')
            ->with('error', sprintf(
                'L\'accès à l\'application est limité entre %s et %s.',
                $startTime->format('H:i'),
                $endTime->format('H:i')
            ));
    }
}
