<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'reservees' ? 'Reservees' : 'Participants' }}</title>
    <style>
        html * {
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
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
        }
        .footer {
            position: fixed;
            bottom: -10px;
            width: 100%;
            left: 0;
            font-size: 9px;
            color: #555;
        }
    </style>
</head>
<body>
    @php
        $list = $type === 'reservees' ? $reservedList : $mainList;
        $heading = $type === 'reservees' ? 'LIST OF RESERVED / WAITLISTED PARTICIPANTS' : 'LIST OF PARTICIPANTS';
        $footerLabel = $type === 'reservees' ? 'Total Reservees' : 'Total Participants';
    @endphp
    <div class="footer">
        <table style="border: hidden;">
            <tr>
                <td style="border: hidden; width: 50%; text-align: left;">Printed on {{ $printedAt }}</td>
                <td style="border: hidden; width: 50%; text-align: right;">{{ $footerLabel }}: {{ $list->count() }}</td>
            </tr>
        </table>
    </div>

    <div class="content">
        <div style="font-family:Arial;">
            <table style="width:100%; border:none; border-collapse:collapse; margin-bottom:15px;">
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
            <center style="margin-top: 8px; font-size: 11px; color:#000; font-weight: bold; padding: 2px;">{{ $heading }}</center>
            <center style="font-size: 11px; background-color: #097eeb; color:#fff; font-weight: bold; padding: 2px; text-transform: uppercase;">{{ $data->event->name ?? '' }}</center>
        </div>

        <table class="table" style="border: 1px solid black; margin-top: 10px;">
            <thead style="background-color:#c8c8c8;">
                <tr>
                    <th width="52%">SESSION</th>
                    <th width="25%">VENUE</th>
                    <th width="23%">INCLUSIVE DATE</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-size: 12px;">
                    <td style="text-align: center;">{{ $data->title }}</td>
                    <td style="text-align: center;">{{ $data->venue->name }}, {{ $data->venue->establishment }}</td>
                    <td style="text-align: center;">{{ $date }}</td>
                </tr>
            </tbody>
        </table>

        <center style="margin-top: 15px; font-size: 11px; color:#000; font-weight: bold; padding: 2px;">{{ $type === 'reservees' ? 'RESERVED / WAITLISTED' : 'PARTICIPANTS' }} ({{ $list->count() }})</center>
        <table class="table" style="border: 1px solid black; margin-top: 5px;">
            <thead style="background-color:#c8c8c8;">
                <tr>
                    <th width="4%">#</th>
                    <th width="6%">PHOTO</th>
                    <th width="17%">NAME</th>
                    <th width="24%">AFFILIATION / DESIGNATION</th>
                    <th width="21%">EMAIL / CONTACT NO.</th>
                    <th width="14%">DATE REGISTERED</th>
                    <th width="14%">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $item)
                    @include('prints.participants-row', ['item' => $item, 'index' => $loop->iteration])
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">No {{ $type === 'reservees' ? 'reservees' : 'participants' }} found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
