<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Calendrier des Absences</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .validated { color: #10B981; }
        .pending { color: #EF4444; }
        .holiday { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Calendrier des Absences</h1>
        <p>Généré le {{ now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Utilisateur</th>
                <th>Type</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr @if($event['type'] === 'holiday') class="holiday" @endif>
                    <td>
                        @if($event['type'] === 'absence')
                            Du {{ \Carbon\Carbon::parse($event['start'])->format('d/m/Y') }}
                            au {{ \Carbon\Carbon::parse($event['end'])->format('d/m/Y') }}
                        @else
                            {{ \Carbon\Carbon::parse($event['start'])->format('d/m/Y') }}
                        @endif
                    </td>
                    <td>
                        @if($event['type'] === 'absence')
                            {{ $event['extendedProps']['user'] }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($event['type'] === 'absence')
                            {{ $event['extendedProps']['motif'] }}
                        @else
                            {{ $event['title'] }}
                        @endif
                    </td>
                    <td>
                        @if($event['type'] === 'absence')
                            <span class="{{ $event['extendedProps']['status'] === 'Validée' ? 'validated' : 'pending' }}">
                                {{ $event['extendedProps']['status'] }}
                            </span>
                        @else
                            Jour férié
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
