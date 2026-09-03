<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Equipment Maintenance Schedule - {{ $year }}</title>
    <style>
        @page {
            margin: 40px 50px 110px 50px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000000;
        }

        .form-number {
            width: 160px;
            float: right;
            border: 1px solid #000000;
            padding: 4px 6px;
            font-size: 10px;
            margin-bottom: 6px;
        }

        .form-number .code {
            font-weight: bold;
        }

        .clear {
            clear: both;
        }

        table.form {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000000;
        }

        table.form td {
            border: 1px solid #000000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .title-cell {
            text-align: center;
        }

        .agency-name {
            font-weight: bold;
            font-size: 14px;
        }

        .agency-region {
            font-weight: bold;
            font-size: 12px;
        }

        .form-title {
            font-weight: bold;
            font-size: 13px;
        }

        .year-row {
            font-size: 11px;
            font-weight: bold;
            padding: 4px 8px;
            border: 1px solid #000000;
            border-top: none;
        }

        table.schedule {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000000;
            border-top: none;
        }

        table.schedule th {
            border: 1px solid #000000;
            padding: 5px 3px;
            font-size: 10.5px;
            font-weight: bold;
            text-align: center;
        }

        table.schedule td {
            border: 1px solid #000000;
            padding: 4px 3px;
            font-size: 10px;
            vertical-align: middle;
        }

        table.schedule .col-code {
            width: 10%;
            text-align: center;
        }

        table.schedule .col-month {
            width: 4.7%;
            height: 18px;
        }

        .text-muted {
            color: #6c757d;
            font-weight: normal;
        }

        .mark {
            height: 16px;
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 16px 16px;
        }

        .mark-planned {
            background-image: url("{{ public_path('images/schedule/planned.png') }}");
        }

        .mark-completed {
            background-image: url("{{ public_path('images/schedule/completed.png') }}");
        }

        .footer {
            position: fixed;
            bottom: -95px;
            left: 0;
            right: 0;
            font-size: 10px;
        }

        .footer table.sign {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000000;
        }

        .footer table.sign td {
            padding: 8px;
            vertical-align: top;
        }

        .footer .line {
            display: inline-block;
            border-bottom: 1px solid #000000;
            width: 220px;
        }

        .footer .legend-box {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 1px solid #000000;
            vertical-align: middle;
            margin-right: 4px;
        }
    </style>
</head>
<body>
    <div class="form-number">
        <div class="code">FASS-MAIN F02</div>
        <div>Rev. 0 / 08-16-07</div>
    </div>
    <div class="clear"></div>

    <table class="form">
        <tr>
            <td class="title-cell">
                <div class="agency-name">DEPARTMENT OF SCIENCE AND TECHNOLOGY</div>
                <div class="agency-region">Regional Office No. IX</div>
            </td>
        </tr>
        <tr>
            <td class="title-cell">
                <div class="form-title">EQUIPMENT MAINTENANCE SCHEDULE</div>
            </td>
        </tr>
    </table>
    <div class="year-row">YEAR: {{ $year }}</div>

    <table class="schedule">
        <thead>
            <tr>
                <th class="col-code">Code No.</th>
                <th>Equipment Name</th>
                <th class="col-month">Jan</th>
                <th class="col-month">Feb</th>
                <th class="col-month">Mar</th>
                <th class="col-month">Apr</th>
                <th class="col-month">May</th>
                <th class="col-month">Jun</th>
                <th class="col-month">Jul</th>
                <th class="col-month">Aug</th>
                <th class="col-month">Sep</th>
                <th class="col-month">Oct</th>
                <th class="col-month">Nov</th>
                <th class="col-month">Dec</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td class="col-code"><strong>{{ $row['code'] }}</strong></td>
                    <td><strong>{{ $row['name'] }}</strong> @if($row['type'])<span class="text-muted">({{ $row['type'] }})</span>@endif</td>
                    @for($m = 1; $m <= 12; $m++)
                        @php
                            $markClass = in_array($m, $row['completed']) ? 'mark-completed' : (in_array($m, $row['planned']) ? 'mark-planned' : '');
                        @endphp
                        <td class="col-month"><div class="mark {{ $markClass }}"></div></td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table class="sign">
            <tr>
                <td style="width: 55%;">
                    <p>Prepared by : <span class="line">&nbsp;</span></p>
                    <p>Approved by : <span class="line">&nbsp;</span></p>
                </td>
                <td style="width: 20%;">
                    <p>Date: <span class="line" style="width: 120px;">&nbsp;</span></p>
                    <p>Date: <span class="line" style="width: 120px;">&nbsp;</span></p>
                </td>
                <td style="width: 25%;">
                    <p><strong>Legend:</strong></p>
                    <p><span class="legend-box mark-planned"></span> Planned</p>
                    <p><span class="legend-box mark-completed"></span> Completed</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
