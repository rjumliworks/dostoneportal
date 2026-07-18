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
            margin-bottom: 50px; /* Space for the footer */
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
            padding: 3px;
            vertical-align: top;
        }
        .table td {
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
            overflow: hidden;
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
    </style>
</head>
<body>
    {{-- <div class="footer">
        
        <table style="border-bottom-style: hidden; border-right-style: hidden; border-top-style: hidden; border-left-style: hidden;">
            <tr>
                <td style="width: 100%; text-align: left; font-weight: bold;"><hr/></td>
            </tr>
        </table>
        <table style="margin-top: -5px; border-bottom-style: hidden; border-right-style: hidden; border-top-style: hidden; border-left-style: hidden;">
            <tr>
                <td style="border-right-style: hidden; width: 3%; text-align: right;">-</td>
                <td style="border-right-style: hidden;" style="width: 50%; text-align: left; font-size: 10px;"><br/> <span style="font-weight: bold; color: #072388;">123456789</span></td>
                <td style="border-left-style: hidden; width: 50%; text-align: right; font-size: 10px;">FM-FOS-SET F11 (front page) <br/>Rev 02/07-01-23</td>
                
            </tr>
        </table>
    </div> --}}


    <div class="content">
        <div class="header">
            <div style="font-family:Arial;">
                <table  style="width:100%; border:none; border-collapse:collapse; margin-bottom:15px; margin-top: -20px;">
                    <tr>
                        <td style="width:78px; text-align:left;">
                            <img src="{{ public_path('images/logos/logo-sm.png') }}" style="width:75px;">
                        </td>
                        <td style="text-align:left; font-family:Arial;" >
                            <div style="font-size:11px;">Republic of the Philippines</div>
                            <div style="font-size: 15px; margin-bottom: 0px; font-weight: bold;">DEPARTMENT OF SCIENCE AND TECHNOLOGY</div>
                            <div style="font-size:13px;">ZAMBOANGA PENINSULA</div>
                            <div style="font-size:11px;  font-weight: bold;">OneDOST4U: Solutions and Opportunities for All</div>
                        </td>
                        <td style="width:78px; text-align:right;">
                            <img src="{{ public_path('images/logos/bagongpilipinas.png') }}" style="width:75px;">
                        </td>
                    </tr>
                </table>
                <center style="margin-top: 8px; font-size: 11px;  color:#000; font-weight: bold; padding: 2px;">CUSTOMER SATISFACTION FEEDBACK</center>
                <center style="font-size: 11px; background-color: #097eeb; color:#fff; font-weight: bold; padding: 2px; text-transform: uppercase; ">{{$session}}</center>
            </div> 
        </div>
     <table class="table" style="border: 1px solid black; margin-top: 15px;">
        <thead style="background-color:#c8c8c8; padding: 5px; font-size: 10px;">
            <tr>
                <th rowspan="2" style="vertical-align: middle;" width="23%">Service Quality Attributes</th>
                <th colspan="5" style="vertical-align: middle;">Client Satisfaction Rating</th>
                <th rowspan="2" style="vertical-align: middle;" width="8%">Score<br>(Total)</th>
                <th rowspan="2" style="vertical-align: middle;" width="8%">Rating</th>
                <th rowspan="2" style="vertical-align: middle;" width="15%">(Average)</th>
            </tr>
            <tr style="font-size: 9px;">
                <th width="10%">Very<br>Satisfied<br>(5)</th>
                <th width="10%">Satisfied<br>(4)</th>
                <th width="10%">Neither<br>(3)</th>
                <th width="10%">Dissatisfied<br>(2)</th>
                <th width="10%">Very<br>Dissatisfied<br>(1)</th>
            </tr>
        </thead>

        <tbody>
            @php
                $grandCount5 = $grandCount4 = $grandCount3 = $grandCount2 = $grandCount1 = 0;
                $grandTotalScore = 0;
                $grandTotalResponses = 0;
            @endphp

            @foreach ($questions as $question)
                @php
                    $count5 = $question->ratings->where('rating', 5)->count();
                    $count4 = $question->ratings->where('rating', 4)->count();
                    $count3 = $question->ratings->where('rating', 3)->count();
                    $count2 = $question->ratings->where('rating', 2)->count();
                    $count1 = $question->ratings->where('rating', 1)->count();

                    $totalCount = $count1 + $count2 + $count3 + $count4 + $count5;

                    // Weighted total score
                    $totalScore = ($count5 * 5) + ($count4 * 4) + ($count3 * 3) + ($count2 * 2) + ($count1 * 1);

                    // Average
                    $average = $totalCount > 0 ? $totalScore / $totalCount : 0;

                    // Accumulate grand totals
                    $grandCount5 += $count5;
                    $grandCount4 += $count4;
                    $grandCount3 += $count3;
                    $grandCount2 += $count2;
                    $grandCount1 += $count1;

                    $grandTotalScore += $totalScore;
                    $grandTotalResponses += $totalCount;
                @endphp

                <tr style="text-align: center;">
                    <td style="text-align:left;">{{ $question->name }}</td>
                    <td>{{ $count5 }}</td>
                    <td>{{ $count4 }}</td>
                    <td>{{ $count3 }}</td>
                    <td>{{ $count2 }}</td>
                    <td>{{ $count1 }}</td>
                    <td>{{ $totalScore }}</td>
                    <td>
                        {{ number_format($average, 2) }}
                    
                    </td>
                    <td>
                        <small>
                            @if ($average >= 4.5)
                                Excellent
                            @elseif ($average >= 3.5)
                                Very Satisfactory
                            @elseif ($average >= 2.5)
                                Satisfactory
                            @elseif ($average >= 1.5)
                                Fair
                            @else
                                Poor
                            @endif
                        </small>
                    </td>
                </tr>
            @endforeach
        </tbody>

    {{-- ✅ Footer with weighted totals --}}
    <tfoot style="background-color:#e8e8e8; font-weight: bold; text-align: center;">
        @php
            // Weighted totals (count * rating)
            $weighted5 = $grandCount5 * 5;
            $weighted4 = $grandCount4 * 4;
            $weighted3 = $grandCount3 * 3;
            $weighted2 = $grandCount2 * 2;
            $weighted1 = $grandCount1 * 1;

            $overallAverage = $grandTotalResponses > 0 ? $grandTotalScore / $grandTotalResponses : 0;
        @endphp
        <tr>
            <td style="text-align:left;">TOTAL (Count × Rating)</td>
            <td>{{ $weighted5 }} ({{ $grandCount5 }})</td>
            <td>{{ $weighted4 }} ({{ $grandCount4 }})</td>
            <td>{{ $weighted3 }} ({{ $grandCount3 }})</td>
            <td>{{ $weighted2 }} ({{ $grandCount2 }})</td>
            <td>{{ $weighted1 }} ({{ $grandCount1 }})</td>
            <td>{{ $grandTotalScore }}</td>
            <td>
                {{ number_format($overallAverage, 2) }}
            </td>
            <td>
                <small>
                    @if ($overallAverage >= 4.5)
                        Excellent
                    @elseif ($overallAverage >= 3.5)
                        Very Satisfactory
                    @elseif ($overallAverage >= 2.5)
                        Satisfactory
                    @elseif ($overallAverage >= 1.5)
                        Fair
                    @else
                        Poor
                    @endif
                </small>
            </td>
        </tr>
    </tfoot>
</table>

    <table  class="table" style="border: 1px solid black; margin-top: 15px;">
        <thead style="background-color:#c8c8c8; padding: 5px; font-size: 10px;">
            <tr>
                <th style="vertical-align: middle;" width="33.3%">Total No. of Participants</th>
                <th style="vertical-align: middle;" width="33.3%">Likert Scale Rating (Average)</th>
                <th style="vertical-align: middle;" width="33.3%">Customer Satisfaction Rating</th>
            </tr>
        </thead>
        <tbody>
           <tr style="text-align: center; font-size: 12px;">
            <td>{{ $participantCount }}</td>
            <td>
                {{ number_format($overallAverage, 2) }}
             
            </td>
            <td>   <small>
                    @if ($overallAverage >= 4.5)
                        Excellent
                    @elseif ($overallAverage >= 3.5)
                        Very Satisfactory
                    @elseif ($overallAverage >= 2.5)
                        Satisfactory
                    @elseif ($overallAverage >= 1.5)
                        Fair
                    @else
                        Poor
                    @endif
                </small></td>
        </tr>
        </tbody>
    </table>

    <table class="table" style="border: 1px solid black; margin-top: 15px;">
        <thead style="background-color:#c8c8c8; padding: 5px; font-size: 10px;">
            <tr>
                <th style="vertical-align: middle;">Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
            <tr>
                 <td>{{ $comment['comment'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>



    </div>

    
</body>
</html>