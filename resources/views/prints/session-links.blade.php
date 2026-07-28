<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Links</title>
    <style>
        @page {

        }

        html * {
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
        }

        .content {
            margin-bottom: 50px;
        }

        .table table,
        .table td,
        .table th {
            border: .5px solid black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .table {
            table-layout: fixed;
        }
        .table th {
            padding: 4px;
            vertical-align: middle;
        }
        .table td {
            padding: 4px;
            vertical-align: top;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-all;
            white-space: normal;
        }
        .link-cell {
            width: 80%;
            font-size: 8px;
            color: #072388;
        }
        .link-cell a {
            color: #072388;
            text-decoration: underline;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <div style="font-family:Arial;">
                <table style="width:100%; border:none; border-collapse:collapse; margin-bottom:15px; margin-top: -20px;">
                    <tr>
                        <td style="width:78px; text-align:left;">
                            <img src="{{ public_path('images/logos/logo-sm.png') }}" style="width:75px;">
                        </td>
                        <td style="text-align:left; font-family:Arial;">
                            <div style="font-size:11px;">Republic of the Philippines</div>
                            <div style="font-size: 15px; margin-bottom: 0px; font-weight: bold;">DEPARTMENT OF SCIENCE AND TECHNOLOGY</div>
                            <div style="font-size:13px;">ZAMBOANGA PENINSULA</div>
                            <div style="font-size:11px; font-weight: bold;">OneDOST4U: Solutions and Opportunities for All</div>
                        </td>
                        <td style="width:78px; text-align:right;">
                            <img src="{{ public_path('images/logos/bagongpilipinas.png') }}" style="width:75px;">
                        </td>
                    </tr>
                </table>
                <center style="margin-top: 8px; font-size: 11px; background-color: #097eeb; color:#fff; font-weight: bold; padding: 2px; text-transform: uppercase;">Session Links</center>
            </div>
            <div style="text-align:right; font-size: 8px; color:#555; margin-top:4px;">Printed: {{ $printedAt }}</div>
        </div>

        @forelse ($sessions as $index => $session)
            <table class="table" style="border: 1px solid black; margin-top: {{ $index === 0 ? '15px' : '10px' }};">
                <tbody>
                    <tr>
                        <td colspan="2" style="background-color:#097eeb; color:#fff; font-weight:bold; font-size: 10px;">{{ $session['title'] }}</td>
                    </tr>
                    <tr>
                        <td style="width: 20%; font-weight: bold; background-color:#f2f2f2;">Registration Link</td>
                        <td class="link-cell"><a href="{{ $session['registration'] }}">{{ $session['registration'] }}</a></td>
                    </tr>
                    <tr>
                        <td style="width: 20%; font-weight: bold; background-color:#f2f2f2;">VIP Link</td>
                        <td class="link-cell"><a href="{{ $session['vip'] }}">{{ $session['vip'] }}</a></td>
                    </tr>
                    <tr>
                        <td style="width: 20%; font-weight: bold; background-color:#f2f2f2;">Attendance Link</td>
                        <td class="link-cell"><a href="{{ $session['attendance'] }}">{{ $session['attendance'] }}</a></td>
                    </tr>
                </tbody>
            </table>
        @empty
            <table class="table" style="border: 1px solid black; margin-top: 15px;">
                <tr>
                    <td style="text-align:center;">No sessions found.</td>
                </tr>
            </table>
        @endforelse
    </div>
</body>
</html>
