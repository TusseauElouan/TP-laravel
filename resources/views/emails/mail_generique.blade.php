<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #ffffff; background-color: #2c2c2c; margin: 0; padding: 0;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #3b3b3b; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="padding: 20px; background-color: #1e1e1e; text-align: center;">
                <h1 style="color: #f0b429; margin-top: 0; font-size: 28px;">{{ $subject }}</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <p style="color: #e0e0e0; font-size: 16px; margin-bottom: 20px;">{{ $content }}</p>

                @if(!empty($details))
                    <table cellpadding="8" cellspacing="0" border="0" width="100%" style="border-collapse: collapse; margin-top: 10px;">
                        @foreach($details as $key => $value)
                            <tr style="background-color: #4a4a4a;">
                                <th style="color: #f0b429; text-align: left; border: 1px solid #444; padding: 10px;">{{ $key }}</th>
                                <td style="color: #e0e0e0; border: 1px solid #444; padding: 10px;">{{ $value }}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; background-color: #1e1e1e; text-align: center;">
                <p style="color: #888888; font-size: 14px;">Merci de votre attention.</p>
            </td>
        </tr>
    </table>
</body>
</html>
