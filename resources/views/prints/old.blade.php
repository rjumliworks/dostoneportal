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
        @foreach($lists as $date => $list)
            <div style="font-family:Arial;">
                <img src="{{ public_path('images/logo-sm.png') }}" alt="tag" style="position: absolute; top: -4; left: 60; width: 50px; height: 50px;">
                <center style="font-size: 10px; margin-bottom: 0px; text-transform: uppercase;">DEPARTMENT OF SCIENCE AND TECHNOLOGY - IX</center>
                <center style="font-size: 11px; margin-bottom: 0px; font-weight: bold;">REGIONAL STANDARDS AND TESTING LABORATORIES</center>
                <center style="font-size: 11px;">Pettit Barracks, Zamboanga City | ord@ro9.dost.gov.ph</center>
                <br/>
                <center style="margin-top: 8px; font-size: 12px;  color:#000; font-weight: bold; padding: 2px;">DATE TIME RECORD</center>
                <center style="font-size: 10px; background-color: blue; color:#fff; font-weight: bold; padding: 2px;"> {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</center>
                <center style="margin-top: 8px; font-size: 12px;  color:#000; font-weight: bold; padding: 2px;">{{$station->others}} ({{$station->name}})</center>
            </div>

            
          <table style="border: 1px solid black; font-size: 10px; margin-top: 22px; width:100%; border-collapse: collapse;">

            <thead style="background-color:#c8c8c8; font-size: 9px;">
                <tr>
                    <th>Employee</th>

                    <th>AM In</th>
                    <th>Minutes</th>

                    <th>AM Out</th>
                    <th>Minutes</th>

                    <th>PM In</th>
                    <th>Minutes</th>

                    <th>PM Out</th>
                    <th>Minutes</th>
                </tr>
            </thead>

            <tbody>
                @foreach($list as $dtr)

                @php
                    // NEW
                    $newInAM = isset($dtr['am_in_at']->time) ? \Carbon\Carbon::parse($dtr['am_in_at']->time) : null;
                    $newOutAM = isset($dtr['am_out_at']->time) ? \Carbon\Carbon::parse($dtr['am_out_at']->time) : null;
                    $newInPM = isset($dtr['pm_in_at']->time) ? \Carbon\Carbon::parse($dtr['pm_in_at']->time) : null;
                    $newOutPM = isset($dtr['pm_out_at']->time) ? \Carbon\Carbon::parse($dtr['pm_out_at']->time) : null;

                    // OLD
                    $oldInAM = isset($dtr['olds']['inAM']) ? \Carbon\Carbon::createFromTimestamp($dtr['olds']['inAM'])->timezone('Asia/Manila') : null;
                    $oldOutAM = isset($dtr['olds']['outAM']) ? \Carbon\Carbon::createFromTimestamp($dtr['olds']['outAM'])->timezone('Asia/Manila') : null;
                    $oldInPM = isset($dtr['olds']['inPM']) ? \Carbon\Carbon::createFromTimestamp($dtr['olds']['inPM'])->timezone('Asia/Manila') : null;
                    $oldOutPM = isset($dtr['olds']['outPM']) ? \Carbon\Carbon::createFromTimestamp($dtr['olds']['outPM'])->timezone('Asia/Manila') : null;

                    // DIFF FUNCTION
                    $diff = function($new, $old) {
                        if (!$new || !$old) return '-';

                        $newMinutes = ($new->hour * 60) + $new->minute;
                        $oldMinutes = ($old->hour * 60) + $old->minute;

                        return abs($newMinutes - $oldMinutes);
                    };
                    @endphp

                    {{-- ROW 1: NEW --}}
                    <tr>
                        <td rowspan="2">{{ $dtr['name'] ?? '' }}</td>

                        {{-- AM IN --}}
                        <td class="cntr">{{ $newInAM ? $newInAM->format('h:i A') : '-' }}</td>
                        <td class="cntr" rowspan="2">{{ $diff($newInAM, $oldInAM) }}</td>

                        {{-- AM OUT --}}
                        <td class="cntr">{{ $newOutAM ? $newOutAM->format('h:i A') : '-' }}</td>
                        <td class="cntr" rowspan="2">{{ $diff($newOutAM, $oldOutAM) }}</td>

                        {{-- PM IN --}}
                        <td class="cntr">{{ $newInPM ? $newInPM->format('h:i A') : '-' }}</td>
                        <td class="cntr" rowspan="2">{{ $diff($newInPM, $oldInPM) }}</td>

                        {{-- PM OUT --}}
                        <td class="cntr">{{ $newOutPM ? $newOutPM->format('h:i A') : '-' }}</td>
                        <td class="cntr" rowspan="2">{{ $diff($newOutPM, $oldOutPM) }}</td>
                    </tr>

                    {{-- ROW 2: OLD --}}
                    <tr>

                        <td class="cntr">{{ $oldInAM ? $oldInAM->format('h:i A') : '-' }}</td>

                        <td class="cntr">{{ $oldOutAM ? $oldOutAM->format('h:i A') : '-' }}</td>

                        <td class="cntr">{{ $oldInPM ? $oldInPM->format('h:i A') : '-' }}</td>

                        <td class="cntr">{{ $oldOutPM ? $oldOutPM->format('h:i A') : '-' }}</td>
                    </tr>

                    @endforeach
                </tbody>

            </table>

            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
            @php $loopcount++; @endphp
        @endforeach
    </div>
</body>
</html>