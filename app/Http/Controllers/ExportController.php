<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function exportAbsences(): StreamedResponse
    {
        // Récupération des absences de l'utilisateur
        $absences = Absence::with(['motif'])
            ->where('user_id_salarie', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // En-têtes du fichier CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="mes_absences.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Création du callback pour générer le CSV
        $callback = function() use ($absences) {
            $file = fopen('php://output', 'w');

            // En-têtes UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // En-têtes des colonnes
            fputcsv($file, [
                'Date de la demande',
                'Motif d\'absence',
                'Statut',
                'Date de début',
                'Date de fin'
            ], ';');

            // Données
            foreach ($absences as $absence) {
                $status = $absence->isValidated ? 'Validée' : 'En attente';

                fputcsv($file, [
                    $absence->created_at->format('d/m/Y'),
                    $absence->motif->libelle ?? 'Non spécifié',
                    $status,
                    date('d/m/Y', strtotime($absence->date_absence_debut)),
                    date('d/m/Y', strtotime($absence->date_absence_fin))
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
