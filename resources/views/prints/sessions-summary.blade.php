<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessions Summary Report</title>
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
        .table th {
            padding: 4px;
            vertical-align: middle;
            text-align: center;
        }
        .table td {
            padding: 4px;
            vertical-align: middle;
            text-align: center;
        }
        .section-title {
            font-size: 11px;
            background-color: #097eeb;
            color: #fff;
            font-weight: bold;
            padding: 4px;
            text-transform: uppercase;
            margin-top: 14px;
            margin-bottom: 6px;
        }
        .session-title-row td {
            background-color: #072388;
            color: #fff;
            font-weight: bold;
            font-size: 10px;
            text-align: left !important;
        }
        .per-day-table {
            margin-top: 2px;
            margin-bottom: 10px;
        }
        .per-day-table th {
            background-color: #f2f2f2;
            font-size: 8px;
        }
        .kpi-table td {
            width: 16.6%;
            padding: 6px;
        }
        .kpi-value {
            font-size: 15px;
            font-weight: bold;
            color: #072388;
        }
        .kpi-label {
            font-size: 8px;
            color: #555;
            text-transform: uppercase;
        }
        .text-muted {
            color: #888;
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
                <center style="margin-top: 8px; font-size: 11px; color:#000; font-weight: bold; padding: 2px;">SESSIONS SUMMARY REPORT</center>
                @if($event)
                    <center style="font-size: 11px; background-color: #097eeb; color:#fff; font-weight: bold; padding: 2px; text-transform: uppercase;">{{ $event->name }}</center>
                @endif
            </div>
            <div style="text-align:right; font-size: 8px; color:#555; margin-top:4px;">Generated: {{ $printedAt }}</div>
        </div>

        {{-- Overall summary --}}
        <table class="table kpi-table" style="border: 1px solid black; margin-top: 10px;">
            <tr>
                <td>
                    <div class="kpi-value">{{ $totalCapacity ?: '—' }}</div>
                    <div class="kpi-label">Total Capacity</div>
                </td>
                <td>
                    <div class="kpi-value">{{ $totalRegistered }}</div>
                    <div class="kpi-label">Total Registered</div>
                </td>
                <td>
                    <div class="kpi-value">{{ $overallCapacityPercent !== null ? $overallCapacityPercent.'%' : '—' }}</div>
                    <div class="kpi-label">% of Capacity</div>
                </td>
                <td>
                    <div class="kpi-value">{{ $totalWarmBodies }}</div>
                    <div class="kpi-label">Total Warm Bodies</div>
                </td>
                <td>
                    <div class="kpi-value">{{ $overallAttendancePercent !== null ? $overallAttendancePercent.'%' : '—' }}</div>
                    <div class="kpi-label">% Attendance</div>
                </td>
                <td>
                    <div class="kpi-value">{{ $overallParticipants }}</div>
                    <div class="kpi-label">Overall Participants (Unique)</div>
                </td>
            </tr>
        </table>

        {{-- Multi-day sessions --}}
        @if($multiDaySessions->count() > 0)
            <div class="section-title">Sessions with Multiple Schedules</div>
            @foreach($multiDaySessions as $session)
                <table class="table" style="border: 1px solid black; margin-top: 8px;">
                    <tr class="session-title-row">
                        <td colspan="6">{{ $session['title'] }} &mdash; {{ $session['venue']->name ?? '' }}{{ !empty($session['venue']->establishment) ? ', '.$session['venue']->establishment : '' }}</td>
                    </tr>
                    <tr>
                        <th width="16%">Capacity</th>
                        <th width="16%">Registered</th>
                        <th width="17%">% of Capacity</th>
                        <th width="17%">Warm Bodies (Unique)</th>
                        <th width="17%">% Attendance</th>
                        <th width="17%">Days</th>
                    </tr>
                    <tr>
                        <td>{{ $session['capacity'] ?: '—' }}</td>
                        <td>{{ $session['registered'] }}</td>
                        <td>{{ $session['capacity_percent'] !== null ? $session['capacity_percent'].'%' : '—' }}</td>
                        <td>{{ $session['warm_bodies'] }}</td>
                        <td>{{ $session['attendance_percent'] !== null ? $session['attendance_percent'].'%' : '—' }}</td>
                        <td>{{ $session['dates']->count() }}</td>
                    </tr>
                </table>
                <table class="table per-day-table" style="border: 1px solid black;">
                    <tr>
                        @foreach($session['dates'] as $day)
                            <th>{{ date('M j, Y', strtotime($day)) }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($session['dates'] as $day)
                            <td>{{ $session['per_day'][$day] ?? 0 }} present</td>
                        @endforeach
                    </tr>
                </table>
            @endforeach
        @endif

        {{-- Single-day sessions --}}
        @if($singleDaySessions->count() > 0)
            <div class="section-title">Single-Day Sessions</div>
            <table class="table" style="border: 1px solid black; margin-top: 8px;">
                <thead>
                    <tr>
                        <th width="3%">#</th>
                        <th width="20%">Session</th>
                        <th width="15%">Venue</th>
                        <th width="12%">Date</th>
                        <th width="10%">Capacity</th>
                        <th width="10%">Registered</th>
                        <th width="10%">% of Capacity</th>
                        <th width="10%">Present</th>
                        <th width="10%">% Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($singleDaySessions as $index => $session)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align:left;">{{ $session['title'] }}</td>
                            <td style="text-align:left;">{{ $session['venue']->name ?? '' }}</td>
                            <td>{{ $session['dates']->first() ? date('M j, Y', strtotime($session['dates']->first())) : '—' }}</td>
                            <td>{{ $session['capacity'] ?: '—' }}</td>
                            <td>{{ $session['registered'] }}</td>
                            <td>{{ $session['capacity_percent'] !== null ? $session['capacity_percent'].'%' : '—' }}</td>
                            <td>{{ $session['warm_bodies'] }}</td>
                            <td>{{ $session['attendance_percent'] !== null ? $session['attendance_percent'].'%' : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if($multiDaySessions->count() === 0 && $singleDaySessions->count() === 0)
            <table class="table" style="border: 1px solid black; margin-top: 15px;">
                <tr>
                    <td style="text-align:center;">No sessions found.</td>
                </tr>
            </table>
        @endif
    </div>
</body>
</html>
