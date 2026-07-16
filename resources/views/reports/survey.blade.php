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
            margin-bottom: 0px; /* Space for the footer  55px*/
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
    <div class="content">
        <table style="width:100%; border:none; border-collapse:collapse; margin-bottom:15px; margin-top: -20px;">
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

        <table style="width:100%; border-collapse:collapse; margin: 0px 0; font-size:11px;">
            <tr>
                <td rowspan="2" style="width:34%; border:1px solid #000; padding:3px; text-align:center; vertical-align:middle;">
                    <div style="font-size:10px; font-weight:bold;">
                        OVERALL SATISFACTION
                    </div>
                    <div style="font-size:20px; font-weight:bold; margin:2px 0;">
                        {{ $counts['rating'] }}
                    </div>
                    <div style="font-size:9px; color: #606060;">
                        Employee Satisfaction Rating
                    </div>
                </td>
                <td style="width:22%; border:1px solid #000; padding:5px; text-align:center;">
                    <div style="font-size:10px; color: #606060;">Eligible Employees</div>
                    <div style="font-size:15px; font-weight:bold;">
                        {{ $counts['active'] }}
                    </div>
                </td>
                <td style="width:22%; border:1px solid #000; padding:5px; text-align:center;">
                    <div style="font-size:10px; color: #606060;">Survey Respondents</div>
                    <div style="font-size:15px; font-weight:bold;">
                        {{ $counts['answered'] }}
                    </div>
                </td>
                <td style="width:22%; border:1px solid #000; padding:5px; text-align:center;">
                    <div style="font-size:10px; color: #606060;">Response Rate</div>
                    <div style="font-size:15px; font-weight:bold;">
                        {{ number_format(($counts['answered'] / $counts['active']) * 100, 2) }}%
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border:1px solid #000; padding:5px;">
                    <strong>Summary:</strong>
                    {{ $counts['answered'] }} out of {{ $counts['active'] }} eligible employees completed the survey.
                </td>
            </tr>
        </table>
        <h6 style="font-size: 11px; margin-top: 12px;"> Employment Status Breakdown</h6>
        <table style="width:100%; border-collapse:collapse; margin-top: -23px; font-size:9px;">
            <thead>
                <tr style="background:#efefef;">
                    <th style="border:1px solid #000; padding:6px; text-align:left;">Employment Status</th>
                    <th style="border:1px solid #000; padding:6px; width:90px;">Eligible</th>
                    <th style="border:1px solid #000; padding:6px; width:90px;">Respondents</th>
                    <th style="border:1px solid #000; padding:6px; width:90px;">Response Rate</th>
                    <th style="border:1px solid #000; padding:6px; width:120px;">Satisfaction Rating</th>
                </tr>
            </thead>

            <tbody>
                @foreach($counts['statuses'] as $status)
                <tr>
                    <td style="border:1px solid #000; padding:6px;">
                        {{ $status['name'] }}
                    </td>

                    <td style="border:1px solid #000; padding:6px; text-align:center;">
                        {{ $status['eligible'] }}
                    </td>

                    <td style="border:1px solid #000; padding:6px; text-align:center;">
                        {{ $status['respondents'] }}
                    </td>

                    <td style="border:1px solid #000; padding:6px; text-align:center;">
                        {{ number_format(($status['respondents'] / max($status['eligible'],1)) * 100, 2) }}%
                    </td>

                    <td style="border:1px solid #000; padding:6px; text-align:center; font-weight:bold;">
                        {{ $status['count'] }}
                    </td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr style="font-weight:bold; background:#f5f5f5;">
                    <td style="border:1px solid #000; padding:6px;">
                        TOTAL
                    </td>

                    <td style="border:1px solid #000; padding:6px; text-align:center;">
                        {{ $counts['active'] }}
                    </td>

                    <td style="border:1px solid #000; padding:6px; text-align:center;">
                        {{ $counts['answered'] }}
                    </td>

                    <td style="border:1px solid #000; padding:6px; text-align:center;">
                        {{ number_format(($counts['answered'] / max($counts['active'],1)) * 100, 2) }}%
                    </td>

                    <td style="border:1px solid #000; padding:6px; text-align:center;">
                        {{ $counts['rating'] }}
                    </td>
                </tr>
            </tfoot>
        </table>



        <h6 style="font-size: 11px; margin-top: 12px;"> Survey Questions Analysis</h6>
        <table style="width:100%; border-collapse:collapse; margin-top: -23px; font-size:9px;">
            <thead>
                <tr style="background:#efefef;">
                    <th style="border:1px solid #000; padding:6px; text-align:left;">
                        Survey Question
                    </th>
                    <th style="border:1px solid #000; padding:6px;">DN</th>
                    <th style="border:1px solid #000; padding:6px;">N</th>
                    <th style="border:1px solid #000; padding:6px;">NS</th>
                    <th style="border:1px solid #000; padding:6px;">Y</th>
                    <th style="border:1px solid #000; padding:6px;">DY</th>
                    <th style="border:1px solid #000; padding:6px;">Satisfaction</th>
                </tr>
            </thead>

            <tbody>
                @foreach($questions['body'] as $question)
                <tr>
                    <td style="border:1px solid #000; padding:6px;">
                        {{ $question->name }}
                    </td>

                    <td style="border:1px solid #000; text-align:center;">{{ $question->dn }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $question->n }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $question->ns }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $question->y }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $question->dy }}</td>

                    <td style="border:1px solid #000; text-align:center; font-weight:bold;">
                        {{ $question->percentage }}%
                    </td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr style="background:#f5f5f5; font-weight:bold;">
                    <td style="border:1px solid #000;">TOTAL</td>

                    <td style="border:1px solid #000; text-align:center;">{{ $questions['footer']['dn'] }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $questions['footer']['n'] }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $questions['footer']['ns'] }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $questions['footer']['y'] }}</td>
                    <td style="border:1px solid #000; text-align:center;">{{ $questions['footer']['dy'] }}</td>

            


                    <td style="border:1px solid #000; text-align:center;">
                        {{ $questions['footer']['percentage'] }}%
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body> 