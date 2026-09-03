<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 60px 55px 60px 55px;
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

        .info-cell {
            padding: 8px;
        }

        .info-row {
            width: 100%;
        }

        table.form table.info-row td {
            border: none;
            padding: 1px 0;
        }

        .info-label {
            width: 150px;
            font-weight: bold;
        }

        .info-colon {
            width: 12px;
        }

        table.log {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000000;
            border-top: none;
        }

        table.log th {
            border: 1px solid #000000;
            padding: 5px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
        }

        table.log td {
            border: 1px solid #000000;
            padding: 5px;
            vertical-align: top;
            font-size: 10.5px;
            text-align: center;
        }

        table.log .col-date {
            width: 13%;
        }

        table.log .col-performed-by {
            width: 17%;
        }

        table.log .row-blank td {
            height: 26px;
        }
    </style>
</head>
<body>
    <div class="form-number">
        <div class="code">FASS-MAIN F03</div>
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
                <div class="form-title">{{ $title }}</div>
            </td>
        </tr>
        <tr>
            <td class="info-cell">
                <table class="info-row">
                    @foreach($infoRows as $row)
                        <tr>
                            <td class="info-label">{{ $row[0] }}</td>
                            <td class="info-colon">:</td>
                            <td>{{ $row[1] }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>

    <table class="log">
        <thead>
            <tr>
                <th class="col-date">Date</th>
                <th>Operation Performed</th>
                <th>Remarks</th>
                <th class="col-performed-by">Performed by:</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td class="col-date">{{ $record->date }}</td>
                    <td>{{ $record->operation_performed }}</td>
                    <td>{{ $record->remarks ?: '-' }}</td>
                    <td>{{ $record->performer?->profile?->fullname ?: '-' }}</td>
                </tr>
            @endforeach
            @for($i = 0; $i < 3; $i++)
                <tr class="row-blank">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
        </tbody>
    </table>
</body>
</html>
