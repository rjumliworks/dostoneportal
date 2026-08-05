<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tardiness Report</title>
    <style>
        @page { margin: 20px; }
        html * { font-family: Arial, Helvetica, sans-serif; }
        body { font-size: 10px; }
        table, td, th { border: 1px solid black; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 4px; text-align: center; }
        td { padding: 3px 5px; }
        .legend-box { display: inline-block; width: 14px; height: 10px; margin-right: 4px; border: 1px solid #333; vertical-align: middle; }
        .division-row td { background-color: #b8cce4; font-weight: bold; }
        .lvl-warning { background-color: #ffc477; }
        .lvl-danger { background-color: #ff6b6b; }
    </style>
</head>
<body>
    <table style="width:100%; border:none; border-collapse:collapse; margin-bottom:6px;">
        <tr>
            <td style="width:60px; border:none; text-align:left;">
                <img src="{{ public_path('images/logo-sm.png') }}" style="width:50px;">
            </td>
            <td style="border:none; text-align:center;">
                <div style="font-size:14px; font-weight:bold;">DEPARTMENT OF SCIENCE AND TECHNOLOGY</div>
                <div style="font-size:12px;">Regional Office No. IX</div>
                <div style="font-size:12px; font-weight:bold; margin-top:4px;">MONTHLY SUMMARY REPORT ON TARDINESS AND UNDERTIME (MINUTES &amp; OCCURRENCES)</div>
                <div style="font-size:11px;">For the Month of {{ $month }} {{ $year }}</div>
            </td>
            <td style="width:60px; border:none;"></td>
        </tr>
    </table>

    <table style="border:none; margin-bottom: 10px;">
        <tr>
            <td style="border:none; width:130px; font-weight:bold; font-size:11px; vertical-align: top;">LEGEND:</td>
            <td style="border:none;">
                <div><span class="legend-box lvl-warning"></span> Total no. of Undertime/Tardy : 6-9 occurrences</div>
                <div><span class="legend-box lvl-danger"></span> Total no. of Undertime/Tardy : 10 above occurrences</div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width:4%;">#</th>
                <th>Name</th>
                <th style="width:13%;">Undertime Minutes</th>
                <th style="width:13%;">Tardy Minutes</th>
                <th style="width:17%;">Total Summary of Undertime/Tardy in Minutes</th>
                <th style="width:16%;">Total Occurrences Undertime/Tardy</th>
                <th style="width:10%;">Incomplete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
                <tr class="division-row">
                    <td colspan="7">{{ strtoupper($group['division']) }}</td>
                </tr>
                @foreach($group['users'] as $index => $user)
                    <tr @class(['lvl-danger' => $user['occurrences'] >= 10, 'lvl-warning' => $user['occurrences'] >= 6 && $user['occurrences'] < 10])>
                        <td style="text-align:center;">{{ $index + 1 }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td style="text-align:center;">{{ $user['undertime'] }}</td>
                        <td style="text-align:center;">{{ $user['tardiness'] }}</td>
                        <td style="text-align:center;">{{ $user['total'] }}</td>
                        <td style="text-align:center;">{{ $user['occurrences'] }}</td>
                        <td style="text-align:center;">{{ $user['incomplete_count'] }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    <p style="font-size:10px; margin-top:6px;">*Only completed DTRs are counted in the totals above; the Incomplete column shows DTRs excluded for that reason.</p>
</body>
</html>
