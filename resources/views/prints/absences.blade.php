<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Absences Report</title>
    <style>
        @page { margin: 20px; }
        html * { font-family: Arial, Helvetica, sans-serif; }
        body { font-size: 10px; }
        table, td, th { border: 1px solid black; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 4px; text-align: center; }
        td { padding: 3px 5px; }
        .division-row td { background-color: #b8cce4; font-weight: bold; }
    </style>
</head>
<body>
    <table style="width:100%; border:none; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            <td style="width:60px; border:none; text-align:left;">
                <img src="{{ public_path('images/logo-sm.png') }}" style="width:50px;">
            </td>
            <td style="border:none; text-align:center;">
                <div style="font-size:14px; font-weight:bold;">DEPARTMENT OF SCIENCE AND TECHNOLOGY</div>
                <div style="font-size:12px;">Regional Office No. IX</div>
                <div style="font-size:12px; font-weight:bold; margin-top:4px;">NUMBER OF ABSENCES</div>
                <div style="font-size:11px;">For the Month of {{ $month }} {{ $year }}</div>
            </td>
            <td style="width:60px; border:none;"></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width:6%;">#</th>
                <th>Name</th>
                <th style="width:22%;">Absences</th>
                <th style="width:22%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
                <tr class="division-row">
                    <td colspan="4">{{ strtoupper($group['division']) }}</td>
                </tr>
                @foreach($group['users'] as $index => $user)
                    <tr>
                        <td style="text-align:center;">{{ $index + 1 }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td style="text-align:center;">{{ $user['absences'] }}</td>
                        <td style="text-align:center;">{{ $user['total'] }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
