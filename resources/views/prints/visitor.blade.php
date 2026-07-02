<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <style>
        /* Styles for the footer */
        @page {
           
        }

        html * {
            font-family:Arial, Helvetica, sans-serif;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
        }

        .content {
            margin-bottom:55px; /* Space for the footer */
        }

        table,
        td,
        th {
            border: .5px solid black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            padding: 3px;
            vertical-align: top;
        }
        td {
            padding: 3px;
            /* vertical-align: top; */
            /* text-align: center; */
        }
        input[type=checkbox] {
            transform: scale(.7);
        }
        .a {
            width: 55px; 
            height: 55px;
        }
        label {
            display: block;
            padding-left: 15px;
            text-indent: -15px;
        }
        input {
            width: 13px;
            height: 13px;
            padding: 0;
            margin:0;
            vertical-align: bottom;
            position: relative;
            top: -5px;
            left: 7px;
            *overflow: hidden;
        }
        input[type=checkbox] { display: inline; }
        input[type=checkbox]:before { font-family: DejaVu Sans; }
        label {
            display: inline-block;
        }
        .footer {
            position: fixed;
            bottom: -10;
            width: 100%;
            left: 0;
            margin-left: auto;
            margin-right: auto;
        }
        .page-break {
            page-break-after: always;
        }
        .letter-p {
        position: relative;
        font-size: 100px;
        font-weight: bold;
        display: inline-block;
        color: black;
    }

    /* First line */
    .letter-p::before {
        content: '';
        position: absolute;
        width: 60%; /* adjust length */
        height: 5px; /* line thickness */
        background-color: red; /* line color */
        top: 20%; /* vertical position */
        left: 20%; /* horizontal start */
        transform: rotate(-10deg); /* optional tilt */
    }

    /* Second line */
    .letter-p::after {
        content: '';
        position: absolute;
        width: 50%;
        height: 5px;
        background-color: blue;
        top: 50%;
        left: 25%;
        transform: rotate(5deg);
    }
    .cntr {
        text-align: center;
    }
        </style>
    </head>
    <?php 

    $loopcount = 0;
?>
<body>
    
    {{-- <div class="footer">
        <table style="border-bottom-style: hidden; border-right-style: hidden; border-top-style: hidden; border-left-style: hidden;">
            <tr>
                <td style="width: 40%; text-align: left; font-weight: bold; color:red;"><hr/></td>
            </tr>
        </table>
        <table style="margin-top: -5px; border-bottom-style: hidden; border-right-style: hidden; border-top-style: hidden; border-left-style: hidden;">
            <tr>
                <td style="border-right-style: hidden; width: 3%; text-align: right;"></td>
                <td style="border-right-style: hidden;" style="width: 50%; text-align: left; font-size: 10px;"><br/> <span style="font-weight: bold; color: #072388;">test</span></td>
                <td style="border-left-style: hidden; width: 50%; text-align: right; font-size: 10px;">OP-007-F1 (front page) <br/>Rev 13 | Feb 01, 2025</td>
            </tr>   
        </table>
    </div> --}}


    <div class="content">
       
            <div style="font-family:Arial;">
                <img src="{{ public_path('images/logo-sm.png') }}" alt="tag" style="position: absolute; top: -4; left: 60; width: 50px; height: 50px;">
                <center style="font-size: 10px; margin-bottom: 0px; text-transform: uppercase;">DEPARTMENT OF SCIENCE AND TECHNOLOGY - IX</center>
                <center style="font-size: 11px; margin-bottom: 0px; font-weight: bold;">REGIONAL STANDARDS AND TESTING LABORATORIES</center>
                <center style="font-size: 11px;">Pettit Barracks, Zamboanga City | ord@ro9.dost.gov.ph</center>
                <br/>
                <center style="margin-top: 8px; font-size: 12px;  color:#000; font-weight: bold; padding: 2px;">DATE TIME RECORD</center>
                <center style="font-size: 10px; background-color: blue; color:#fff; font-weight: bold; padding: 2px;">On-the-Job Training</center>
            </div>

            <table style="border: 1px solid black; font-size: 10px; margin-top: 15px;">
            <tbody>
               <tr>
                    <td width="10%">Name :</td>
                    <td width="40%">
                        <span style="font-weight: bold; color: #072388;">
                            {{ $info['name'] }}
                        </span>
                    </td>
                    <td width="10%">Affiliation :</td>
                    <td width="40%">
                        <span style="color: #072388;">
                            {{ $info['affiliation']['name'] }}
                        </span>
                    </td>
                    
                </tr>
            </tbody>
        </table>
   
          <table style="border: 1px solid black; font-size: 10px; margin-top: 22px; width:100%; border-collapse: collapse;">

            <thead style="background-color:#c8c8c8; font-size: 9px;">
                <tr>
                    <th>Date</th>
                    <th>AM In</th>
                    <th>AM Out</th>
                    <th>PM In</th>
                    <th>PM Out</th>
                    <th>Total Hours</th>
                </tr>
            </thead>

            <tbody>
                @foreach($lists as $dtr)
                  @php
    $totalMinutes = 0;

    // Get the current DTR date
    $currentDate = \Carbon\Carbon::parse($dtr['date']);

    // Check if date is June 9-12, 2026
    $specialSchedule = $currentDate->betweenIncluded(
        \Carbon\Carbon::create(2026, 6, 9),
        \Carbon\Carbon::create(2026, 6, 12)
    );

    // Set schedule based on date
    if ($specialSchedule) {
        // June 9-12, 2026
        $amStartHour = 8;
        $amEndHour   = 12;
        $pmStartHour = 13;
        $pmEndHour   = 17;
    } else {
        // Default schedule
        $amStartHour = 7;
        $amEndHour   = 12;
        $pmStartHour = 13;
        $pmEndHour   = 18;
    }

    // AM
    if (isset($dtr['am_in']->time) && isset($dtr['am_out']->time)) {
        $amIn  = \Carbon\Carbon::parse($dtr['am_in']->time)->seconds(0);
        $amOut = \Carbon\Carbon::parse($dtr['am_out']->time)->seconds(0);

        $amStart = $amIn->copy()->setTime($amStartHour, 0);
        $amEnd   = $amIn->copy()->setTime($amEndHour, 0);

        if ($amIn->lt($amStart)) {
            $amIn = $amStart;
        }

        if ($amOut->gt($amEnd)) {
            $amOut = $amEnd;
        }

        if ($amOut->gt($amIn)) {
            $totalMinutes += $amIn->diffInMinutes($amOut);
        }
    }

    // PM
    if (isset($dtr['pm_in']->time) && isset($dtr['pm_out']->time)) {
        $pmIn  = \Carbon\Carbon::parse($dtr['pm_in']->time)->seconds(0);
        $pmOut = \Carbon\Carbon::parse($dtr['pm_out']->time)->seconds(0);

        $pmStart = $pmIn->copy()->setTime($pmStartHour, 0);
        $pmEnd   = $pmIn->copy()->setTime($pmEndHour, 0);

        if ($pmIn->lt($pmStart)) {
            $pmIn = $pmStart;
        }

        if ($pmOut->gt($pmEnd)) {
            $pmOut = $pmEnd;
        }

        if ($pmOut->gt($pmIn)) {
            $totalMinutes += $pmIn->diffInMinutes($pmOut);
        }
    }

    $grandMinutes = ($grandMinutes ?? 0) + $totalMinutes;
    $totalHours = $totalMinutes
        ? floor($totalMinutes / 60) . 'h ' . ($totalMinutes % 60) . 'm'
        : '';
@endphp
                    <tr>
                        <td class="cntr">{{ \Carbon\Carbon::parse($dtr['date'])->format('F d, Y') }}</td>
                        <td class="cntr">
                            {{ isset($dtr['am_in']->time) ? \Carbon\Carbon::parse($dtr['am_in']->time)->format('h:i A') : '-' }}
                        </td>
                        <td class="cntr">
                            {{ isset($dtr['am_out']->time) ? \Carbon\Carbon::parse($dtr['am_out']->time)->format('h:i A') : '-' }}
                        </td>
                        <td class="cntr">
                            {{ isset($dtr['pm_in']->time) ? \Carbon\Carbon::parse($dtr['pm_in']->time)->format('h:i A') : '-' }}
                        </td>
                        <td class="cntr">
                            {{ isset($dtr['pm_out']->time) ? \Carbon\Carbon::parse($dtr['pm_out']->time)->format('h:i A') : '-' }}
                        </td>
                        <td class="cntr">{{ $totalHours }}</td>
                    </tr>
                    
                    @endforeach
                    @php
                    $grandHours = floor($grandMinutes / 60);
                    $remainingMinutes = $grandMinutes % 60;
                    @endphp
                    <tr>
                        <td colspan="5" style="text-align:right; font-weight:bold;">
                            TOTAL:
                        </td>
                        <td class="cntr" style="font-weight:bold;">
                            {{ $grandHours }}h {{ $remainingMinutes }}m
                        </td>
                    </tr>
             
                </tbody>

            </table>
            <br><br><br>

            <table style="width:100%; border:none; margin-top:40px;">
                <tr>
                    <td style="border:none; width:60%;"></td>
                    <td style="border:none; width:40%; text-align:center;">
                        <div style="margin-bottom:5px;">
                            ____________________________
                        </div>
                        <div style="font-weight:bold; font-size:10px;">
                            Julius T. Fojas
                        </div>
                        <div style="font-size:10px;">
                            Laboratory Head
                        </div>
                    </td>
                </tr>
            </table>

    </div>
</body>
</html>