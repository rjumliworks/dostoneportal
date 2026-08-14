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
            font-size: 10px;
        }

        .content {
            margin-bottom:100px; /* Space for the footer */
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
             vertical-align: middle; 
            text-align: center;
        }
        .table td {
            padding: 3px;
            /* vertical-align: top; */
            /* text-align: center; */
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
    <div class="footer">
        <table style="border: hidden; font-size: 10px; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="min-height: 50px; padding: 15px; border-bottom-style: hidden;"></td>
                    <td style="min-height: 50px; padding: 15px; border: hidden;"></td>
                    <td style="min-height: 50px; padding: 15px; border-bottom-style: hidden;"></td>
                </tr>
                <tr style="text-align: center;">
                    <td width="33.3%"><span style="font-weight: bold; font-size: 11px; color: #072388; text-transform: uppercase;">{{$head['user']['profile']['firstname']}} {{$head['user']['profile']['middlename'][0]}}. {{$head['user']['profile']['lastname']}}</span><hr style="margin-top: 0px; margin-bottom: 1px; border: .1px solid black; width: 50%;">Name and Signature </br><span style="color: gray;">(Facilitated By)</span></td>
                    <td width="33.3%" style="border-left-style: hidden; border-right-style: hidden;"> </td>
                    <td width="33.3%"><span style="font-weight: bold; font-size: 11px; color: #072388; text-transform: uppercase;">NUHMAN M. ALJANI</span><hr style="margin-top: 0px; margin-bottom: 1px; border: .1px solid black; width: 50%;">Provincial S&T Director</br><span style="color: gray;">(Noted By)</span></td>
                </tr>
            </tbody>
        </table>
        <table style="border-bottom-style: hidden; border-right-style: hidden; border-top-style: hidden; border-left-style: hidden;">
            <tr>
                <td style="width: 100%; text-align: left; font-weight: bold;"><hr/></td>
            </tr>
        </table>
        <table style="margin-top: -5px; border-bottom-style: hidden; border-right-style: hidden; border-top-style: hidden; border-left-style: hidden;">
            <tr>
                <td style="border-right-style: hidden; width: 3%; text-align: right;"> <img src="<?php echo $qrCodeImage; ?>"  width="30" height="30" alt="QR Code"></td>
                <td style="border-right-style: hidden;" style="width: 50%; text-align: left; font-size: 10px;"><br/> <span style="font-weight: bold; color: #072388;">{{$data['code']}}</span></td>
                <td style="border-left-style: hidden; width: 50%; text-align: right; font-size: 10px;">FM-FOS-SET F11 (front page) <br/>Rev 02/07-01-23</td>
                
            </tr>
        </table>
    </div>


    @php $days = (isset($dates) && count($dates) > 1) ? $dates : [$date]; @endphp
    @foreach ($days as $day)
    <div class="content @if(!$loop->last) page-break @endif">
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
                <center style="margin-top: 8px; font-size: 11px;  color:#000; font-weight: bold; padding: 2px;">ATTENDANCE SHEET</center>
                <center style="font-size: 11px; background-color: #097eeb; color:#fff; font-weight: bold; padding: 2px; text-transform: uppercase; ">{{$data['event']['name']}}</center>
            </div>

            <table class="table" style="border: 1px solid black; margin-top: 10px;">
                <thead style="background-color:#c8c8c8; padding: 5px; font-size: 10px;">
                    <tr>
                        <th style="vertical-align: middle;" width="52%">SESSION</th>
                        <th style="vertical-align: middle;" width="25%">VENUE</th>
                        <th style="vertical-align: middle;" width="23%">{{ count($days) > 1 ? 'DATE' : 'INCLUSIVE DATE' }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="font-size: 12px;">
                        <td style="text-align: center;">{{$data['title']}}</td>
                        <td style="text-align: center;">{{$data['venue']['name']}}, {{$data['venue']['establishment']}}</td>
                        <td style="text-align: center;">{{ count($days) > 1 ? date('F j, Y', strtotime($day)) : $day }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table class="table" style="border: 1px solid black; margin-top: 15px;">
            <thead style="background-color:#c8c8c8; padding: 5px; font-size: 10px;">
                <tr>
                    <th rowspan="2" width="20px;">#</th>
                    <th rowspan="2" width="190px;">NAME</th>
                    <th rowspan="2" width="180px;">AFFILIATION / DESIGNATION</th>
                    <th rowspan="2" width="100px;">EMAIL / CONTACT NO.</th>
                    <th rowspan="2" width="25px;">SEX</th>
                    <th rowspan="2" width="25px;">AGE</th>
                    <th colspan="3" width="30px;">Check if applicable</th>
                    <th colspan="2" width="80px;">Photo</th>
                </tr>
                <tr>
                    <th width="25px;">4Ps</th>
                    <th width="25px;">PWD</th>
                    <th width="25px;">IP</th>
                    <th width="80px;">Registered</th>
                    <th width="80px;">Captured</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $rows = $dailyAttendance
                        ? ($dailyAttendance[$day] ?? collect())
                        : $data['attendees'];
                @endphp
                @foreach ($rows as $attendee)
                    <tr style="text-align: center;">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $attendee->participant->firstname ?? '' }} {{ $attendee->participant->lastname ?? '' }}<br>
                            <small style="color: gray;">{{$attendee->attended_at ?? '' }}</small>
                        </td>
                        <td style="text-align: center;">
                            @php
                                $affiliation = $attendee->participant->detail->affiliation ?? null;
                                $affiliationText = ($affiliation?->name === 'Others')
                                    ? $attendee->participant->detail->others
                                    : $affiliation?->name;
                            @endphp
                            {{ $affiliationText ?? '' }}<br>
                            <small style="color: gray;">{{ $attendee->participant->detail->designation ?? '' }}</small>
                        </td>
                        <td style="text-align: center;">
                            {{ $attendee->participant->email ?? '' }}<br>
                            <small style="color: gray;">{{ $attendee->participant->mobile ?? '' }}</small>
                        </td>
                        <td>{{ $attendee->participant->detail->sex->name[0] ?? '' }}</td>
                        <td>{{ $attendee->participant->detail->age ?? '-' }}</td>
                        <td>{{ $attendee->is_4ps ? '✔' : '' }}</td>
                        <td>{{ $attendee->is_pwd ? '✔' : '' }}</td>
                        <td>{{ $attendee->is_ip ? '✔' : '' }}</td>
                        <td>
                            <img src="{{ $attendee->participant->detail->avatar_base64 }}" style="width:80px; height:auto;" alt="Photo">
                            <!-- @if(!empty($attendee->participant->detail->signature_base64))
                                <img src="{{ $attendee->participant->detail->signature_base64 }}" style="width:80px; height:auto;" alt="Signature">
                            @else
                                <span>No signature</span>
                            @endif -->
                        </td>
                        <td>
                            <img src="{{ $attendee->image_base64 }}" style="width:80px; height:auto;" alt="Photo">
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
    @endforeach


</body>
</html>