<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
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
    </style>
</head>
<?php 
    $travel = json_encode($travel); 
    $travel = json_decode($travel, true);   
?>
<body>
    
    <div class="footer">
        <table style="border-bottom-style: hidden; border-right-style: hidden; border-top-style: hidden; border-left-style: hidden;">
            <tr>
                <td style="width: 40%; text-align: left; font-weight: bold; color: black;"><hr/></td>
            </tr>
        </table>
        <table style="margin-top: -5px; border-bottom-style: hidden; border-right-style: hidden; border-top-style: hidden; border-left-style: hidden;">
            <tr>
                <td style="border-right-style: hidden; width: 3%; text-align: right;"> <img src="<?php echo $qrCodeImage; ?>"  width="30" height="30" alt="QR Code"></td>
                <td style="border-right-style: hidden;" style="width: 50%; text-align: left; font-size: 10px;"><br/> <span style="font-weight: bold; color: #072388;">{{$travel['code']}}</span></td>
                <td style="border-left-style: hidden; width: 50%; text-align: right; font-size: 10px;"></td>
            </tr>
        </table>
    </div>

    <div class="content">
        @foreach ($divisions as $divisionData)
            <div style="font-family:Arial;">
                <img src="{{ public_path('images/logo-sm.png') }}" alt="tag" style="position: absolute; top: -4; left: 60; width: 50px; height: 50px;">
                <center style="font-size: 10px; margin-bottom: 0px; text-transform: uppercase;">Republic of the Philippines</center>
                <center style="font-size: 11px; margin-bottom: 0px; font-weight: bold;">DEPARTMENT OF SCIENCE AND TECHNOLOGY</center>
                <center style="font-size: 11px;">Pettit Baracks, Zamboanga City | (062) 991-1024 | dost9info@gmail.com</center>
            
                <br/>
                <center style="margin-top: 8px; font-size: 11px;  color:#000; font-weight: bold; padding: 2px;">LOCAL TRAVEL ORDER</center>
                <center style="font-size: 11px; background-color: #097eeb; color:#fff; font-weight: bold; padding: 2px; text-transform: uppercase; ">{{$divisionData['division']}}</center>
            </div>
            <table style="border: 1px solid black;">
                <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
                    <tr>    
                        <th style="vertical-align: middle;" width="50%">LOCAL TRAVEL ORDER NO.</th>
                        <th style="vertical-align: middle;" width="50%">DATE AND TIME</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="text-align: center; text-transform: uppercase; color: #072388; font-weight: bold;">
                        <td style="text-align: center; padding: 5px; font-size: 10x;">{{$travel['travel_code']}}</td>
                        <td style="text-align: center; padding: 5px; font-size: 10x;">{{$travel['created_at']}}</td>
                    </tr>
                </tbody>
            </table>
            
            <h6 style="font-size: 10px; margin-top: 12px;">AUTHORITY TO TRAVEL IS HEREBY GRANTED TO : </h6>
            <table style="border: 1px solid black; margin-top: -22px;">
                <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
                    <tr>    
                        <th style="vertical-align: middle;" width="33.3%">NAME</th>
                        <th style="vertical-align: middle;" width="33.3%">POSITION</th>
                        <th style="vertical-align: middle;" width="33.3%">UNIT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($divisionData['employees'] as $employee)
                        <tr style="text-align: center; text-transform: uppercase; color: #072388; font-weight: bold;">
                            <td style="text-align: center; padding: 5px; font-size: 10x;">{{$employee['name']}}</td>
                            <td style="text-align: center; padding: 5px; font-size: 10x;">{{$employee['position']}}</td>
                            <td style="text-align: center; padding: 5px; font-size: 5x;">{{ strlen($employee['unit']) > 37 ? $employee['unit_short'] : $employee['unit'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h6 style="font-size: 10px; margin-top: 12px;">PURPOSE(S) OF THE TRAVEL : </h6>
            <table style="border: 1px solid black; font-size: 12px; margin-top: -22px;">
                <tbody>
                    <tr>
                        <td style="padding: 10px;">
                            <span>{{$travel['purpose']}}</span>
                            @if($travel['remarks'])
                            <br/><br/><br/>
                                <span style="margin-top: 10px; font-style: italic;">Remarks : {{$travel['remarks']}}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <h6 style="font-size: 10px; margin-top: 12px;">TRAVEL DETAILS :</h6>
            <table style="border: 1px solid black; font-size: 12px; margin-top: -22px;">
                <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
                    <tr>    
                        <th style="vertical-align: middle;" width="33.3%">DESTINATION</th>
                        <th style="vertical-align: middle;" width="33.3%">INCLUSIVE DATE(S) OF TRAVEL</th>
                        <th style="vertical-align: middle;" width="33.3%">TRANSPORTATION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="text-align: center; font-size: 10px; color: #072388;">
                        <td style="text-align: center; padding: 7px; text-transform: uppercase;">
                            <span style="font-weight: bold;">{{$travel['destination']}}</span> <br/> <span style="font-size: 9px; color: gray;">({{$travel['venue']}})</span>
                        </td>
                        <td style="text-align: center; padding: 7px; text-transform: uppercase;">
                            <span style="font-weight: bold;">{{$travel['date']}}</span> <br/> <span style="font-size: 9px; color: gray;">(DEPARTURE TIME : {{$travel['time']}})</span>
                        </td>
                        <td style="text-align: center; padding: 7px; text-transform: uppercase;">
                            <span style="font-weight: bold;">{{$travel['mode']}}</span> <br/> 
                            @if($travel['mode'] == 'Official Vehicle')
                                <span style="font-size: 9px; color: gray;">{{$travel['vehicle']}}</span>
                            @else
                                <span style="font-size: 9px; color: gray;">{{$travel['transpo']}}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <h6 style="font-size: 10px; margin-top: 12px;">TRAVEL EXPENSE DETAILS :</h6>
            <table style="border: 1px solid black; font-size: 12px; margin-top: -22px;">
                <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
                    <tr>    
                        <th style="vertical-align: middle;" width="33.3%">CHARGED TO</th>
                        <th style="text-align: left;" width="66.7%">TRAVEL EXPENSES TO BE INCURRED</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="font-size: 10px; color: #072388;">
                        <td style="text-align: center; padding: 7px; text-transform: uppercase;"><span style="font-weight: bold;">{{$travel['expense']}}</span> </td>
                        <td style="text-align: left; padding: 7px;">
                           <ul style="list-style: none; padding: 0; margin: 0; font-weight: bold;">
                                @foreach ($travel['expenses'] as $expense)
                                    <li style="display: inline; margin-right: 10px;">
                                        &bull; {{ $expense['name'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="border: 1px solid black; font-size: 9px; margin-top: 15px;">
                <tbody>
                    <tr>
                        <td style="padding: 10px;">
                            <span>A report of your travel must be submitted to the Agency Head/Supervising Official within 7 days from completion of travel. Liquidation of
                            cash advances should be in accordance with Executive Order No. 298: Rules and Regulations and New Rates of Allowances for Official
                            Local and Foreign Travels of Government Personnel.</span>
                          
                            <br/><br/>
                            <span style="margin-top: 10px; font-style: italic;">The undersigned certifies the appropriation of funds for actual cost of accommodation.</span>
                        </td>
                    </tr>
                </tbody>
            </table>
           
            <center style="margin-top: 15px; font-size: 8px; background-color: #000; color:#fff; font-weight: bold; padding: 2px;">FOR RECOMMENDATION AND APPROVAL SIGNATURE</center>
            <table style="border: 1px solid black; font-size: 10px; margin-top: 0px; page-break-inside: avoid;">
            <tbody>
                <tr>
                    <td style="min-height: 50px; border-bottom-style: hidden;">
                        @if($divisionData['division'] != 'Office of the Regional Director')
                            <span style="font-size:9px; color: #606060;">RECOMMENDING APPROVAL:</span>
                        @endif
                    </td>
                    <td style="min-height: 50px; border-bottom-style: hidden;"><span style="font-size:9px; color: #606060;">APPROVED:</span></td>
                </tr>
                <tr>
                    <td style="min-height: 100px; padding: 15px; border-bottom-style: hidden;"></td>
                    <td style="min-height: 100px; padding: 15px; border-bottom-style: hidden;"></td>
                </tr>
                <tr style="text-align: center;">
                    <td width="33.3%"><span style="font-weight: bold; font-size: 11px; color: #072388; text-transform: uppercase;">
                        @if($divisionData['division'] == 'Office of the Regional Director')

                        @else
                        {{ $divisionData['recommend']['name']}}</span><hr style="margin-top: 0px; margin-bottom: 1px; border: .1px solid black; width: 80%;">
                            @if($divisionData['recommend']['oic'])
                                OIC - Assistant Regional Director ({{$divisionData['recommend']['short']}})
                            @else 
                                Assistant Regional Director ({{$divisionData['recommend']['short']}})
                            @endif
                        @endif
                    </td>
                    <td width="33.3%"><span style="font-weight: bold; font-size: 11px; color: #072388; text-transform: uppercase;">{{ $divisionData['approval']['name']}}</span><hr style="margin-top: 0px; margin-bottom: 1px; border: .1px solid black; width: 80%;">
                        @if($divisionData['approval']['oic'])
                            OIC - Regional Director 
                        @else 
                            Regional Director
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
            @if (!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    </div>
</body>
</html>