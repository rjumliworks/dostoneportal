<!doctype html>
<html lang="en">
    <head>
        <style>
             html * {
            font-family:Arial, Helvetica, sans-serif;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
        }
            table, td, th {
                margin: 0px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }    
            th {
                padding: 8px;
            }
            td {
                padding: 3px 5px 2px;
            }
            .custom {
                transform: scale(.7);
            }
            .wew td, .wew th {
                border: 1.3px solid black;
            }
        </style>
    </head>
    <body>
        <div style="font-family: Arial, Helvetica, sans-serif">
            <p style="font-size: 8px; margin-left: 8px; margin-top: -20px;"><i>Civil Service Form No.6</i></p>
            <p style="font-size: 8px; margin-left: 8px; margin-top: -10px;"><i>Revised 2022</i></p>
            <img src="{{ public_path('images/logo-sm.png') }}" alt="tag" style="position: absolute; top: 1; left: 60; width: 55px; height: 55px;">
            
            <center style="font-size: 10px; font-weight: bold;">Republic of the Philippines</center>
            <center style="font-size: 10px; font-weight: bold;">DEPARTMENT OF SCIENCE AND TECHNOLOGY REGIONAL OFFICE IX</center>
            <center style="font-size: 10px; font-weight: bold;">Pettit Barracks, Zamboanga City</center>
            <br>
            <center style="font-size: 16px; margin-bottom: 10px; font-weight: bold;">APPLICATION FOR LEAVE</center>

            <table style="font-size: 9px; padding: 10px;  border: 1.3px solid black;">
                <tbody>
                    <tr>
                        <td style="width: 40%; border-right: none;">
                           
                            <table style="margin-top: 0px;">
                                <tbody>
                                    <tr>
                                        <td style="font-size: 10px;"><span style="margin-left: -5px;">1. OFFICE/DEPARTMENT</span></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; font-size: 10px; font-weight: bold;">{{ $data['employee']['unit_short'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width: 60%; border-left: none;" colspan="2">
                            <table style="margin-top: 0px; margin-left: -5px;">
                                <tbody>
                                    <tr>
                                        <td style="width: 15%; font-size: 10px;">2. NAME: </td>
                                        <td style="text-align: center; font-size: 8px;">(Last)</td>
                                        <td style="text-align: center; font-size: 8px;">(First)</td>
                                        <td style="text-align: center; font-size: 8px;">(Middle)</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="text-align: center; font-size: 10px; font-weight: bold;">{{ strtoupper($data['employee']['lastname']) }}</td>
                                        <td style="text-align: center; font-size: 10px; font-weight: bold;">{{ strtoupper($data['employee']['firstname']) }}</td>
                                        <td style="text-align: center; font-size: 10px; font-weight: bold;">{{ strtoupper($data['employee']['middlename']) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 33%; border-right: none; border-top: 1.3px solid #333;">
                            <p style="margin-bottom: 7px; margin-top: 8px; font-size: 10px;">3. DATE OF FILING : <span style="font-weight: bold;">{{ strtoupper($data['created_at']) }}</span></p>
                        </td>
                        <td style="width: 41%; border-right: none; border-left: none; border-top: 1.3px solid #333;">
                            <p style="margin-bottom: 7px; margin-top: 8px; font-size: 10px;">4. POSITION : <span style="font-weight: bold;">{{ strtoupper($data['employee']['position_short']) }}</span></p>
                        </td>
                        <td style="width: 25%; border-left: none; border-top: 1.3px solid #333;">
                            <p style="margin-bottom: 7px; margin-top: 8px; font-size: 10px;">5. SALARY : <span style="font-weight: bold;">{{trim($data['employee']['salary'],'₱ ')}}</span></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-bottom: 4px double #333; border-top: 4px double #333;">
                            <p style="text-align: center; font-size: 12px; font-weight: bold; margin-top: 0px; margin-bottom: 0px;">6. DETAILS OF APPLICATION</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="font-size: 9px; padding: 10px; margin-top:-20px;">
                <tbody>
                    <tr>
                        <td style="width: 55%; border-left: 1.3px solid black; border-right: 1.3px solid black; font-size: 10px;">
                        6.A TYPE OF LEAVE TO BE AVAILED OF
                        </td>
                        <td style="width: 45%; border-right: 1.3px solid black; font-size: 10px;">
                        6.B DETAILS OF LEAVE
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 55%; border-right: 1.3px solid black; border-left: 1.3px solid black; border-bottom: 1.3px solid black;">
                            <table style="height: 300px; border-spacing:0; border-collapse: collapse;" cellspacing="0">
                                @foreach($types as $type)
                                <tr>
                                    <td style="padding: 3px;">
                                        <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; {{ $type['name'] == $data['type']['name'] ? 'background-color: blue;' : '' }}"></span> &nbsp;
                                        <span style="font-size: 11px; margin-top: -10px;">{{$type['name']}}</span> <span style="font-size: 7px;">({{$type['citation']}})</span>
                                    </td>
                                </tr>
                                @endforeach
                                {{-- <tr>
                                    <td>
                                        <br />
                                        Others: 
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" value="" style="width: 97.5%; outline: 0;border-width: 0 0 1px; border-color: black; font-size: 12px;">
                                    </td>
                                </tr> --}}
                            </table>
                        </td>
                        <td style="width: 45%; border-right: 1.3px solid black; border-bottom: 1.3px solid black;">
                            <table style="height: 300px; border-spacing:0; border-collapse: collapse; margin-top: 5px;" cellspacing="0">
                            @foreach($titles as $title)
                                <tr>
                                    <td style="width: 100%; margin: 0px; padding: 0px;"><span style="margin-left: 0px; font-size: 10px; font-style: italic;">In case of {{$title['type']}}: </span></td>
                                </tr>
                                @foreach($details as $detail)
                              
                                    <tr>
                                        @if($title['type'] == $detail['type'])
                                        <td style="padding: 3px;">
                                            <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; {{ $detail['id'] == $data['detail']['id'] ? 'background-color: blue;' : '' }}"></span> &nbsp;
                                            <span style="font-size: 11px;">{{$detail['name']}}</span>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endforeach
                                <tr>
                                    <td>
                                        <input type="text" value="{{$data['purpose']}}" style="width: 95%; padding: 5px; font-style: italic; height: 53px; outline: 0;border-width: 0 0 1px; border-color: black; border: .5px dotted rgb(81, 81, 81)">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 55%; border-left: 1.3px solid black; border-right: 1.3px solid black; font-size: 10px;">
                        6.C NUMBER OF WORKING DAYS APPLIED FOR
                        </td>
                        <td style="width: 45%; border-right: 1.3px solid black; font-size: 10px;">
                        6.D COMMUTATION
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 55%; border-right: 1.3px solid black; border-left: 1.3px solid black; border-bottom: 1.3px solid black;">
                            <input type="text" value="{{ fmod($data['count'], 1) == 0 ? (int)$data['count'] : $data['count'] }} {{($data['count'] > 1) ? 'days' : 'day'}}" style="margin-left: 16px; font-weight: bold; width: 90%; outline: 0;border-width: 0 0 1px; border-color: black; font-size: 10px;">
                            <p style="margin-left: 16px;">INCLUSIVE DATES </p>
                            <input type="text" value="{{$data['date']}}" style="font-weight: bold; font-size: 10px; margin-left: 16px; width: 90%; outline: 0;border-width: 0 0 1px; border-color: black; font-size: 10px;">
                        </td>
                        <td style="width: 45%; border-right: 1.3px solid black; border-left: 1.3px solid black; border-bottom: 1.3px solid black;">
                           
                            <table style="border-spacing:0; border-collapse: collapse;" cellspacing="0">
                                <tr>
                                    <td style="padding-left: 6px;">
                                        <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black;"></span> &nbsp;
                                        <span style="font-size: 10px; margin-top: -10px;">Not Requested</span>
                                    </td>
                                </tr>
                                 <tr>
                                    <td style="padding-left: 6px;">
                                        <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black;"></span> &nbsp;
                                        <span style="font-size: 10px; margin-top: -10px;">Requested</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br /><br />
                                        <div style="position: relative; display: inline-block; width: 100%;">
                                            <img 
                                                src="{{ public_path('storage/profile-signatures/' . $data['employee']['signature']) }}" 
                                                alt="Signature" 
                                                style="
                                                    position: absolute;
                                                    top: -30px; /* adjust vertical placement */
                                                    left: 50%;
                                                    transform: translateX(-50%);
                                                    height: 60px;
                                                    width: auto;
                                                    opacity: 1; /* slightly transparent if you want */
                                                "
                                            >
                                            <input type="text" style="margin-left: 14px; width: 91.5%; height: 8px; outline: 0;border-width: 0 0 1px; border-color: black; margin-top: 14px;">
                                        </div>    
                                        <p style="margin-top: -1px; margin-bottom: 0px; margin-left: 90px;">(Signature of Applicant)</p>
                                    </td>
                                </tr>
                           
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="font-size: 9px; padding: 10px;  border: 1.3px solid black; margin-top:-21px;" >
                <tbody>
                    <tr>
                        <td colspan="3" style="border-bottom: 4px double #333; border-top: 4px double #333;">
                            <p style="text-align: center; font-size: 12px; font-weight: bold; margin-top: 0px; margin-bottom: 0px;">7. DETAILS OF ACTION ON APPLICATION</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="font-size: 9px; padding: 10px; margin-top:-20px;">
                <tbody>
                    <tr>
                        <td style="width: 55%; border-left: 1.3px solid black; border-right: 1.3px solid black; font-size: 10px;">
                            7.A CERTIFICATION OF LEAVE CREDITS
                        </td>
                        <td style="width: 45%; border-right: 1.3px solid black; font-size: 10px;">
                            7.B RECOMMENDATION
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 55%; border-right: 1.3px solid black; border-left: 1.3px solid black; border-bottom: none;">
                            <p style="text-align: center; margin-top: -2px;">As of <input type="text" value="{{$data['created_at']}}" style="width: 40%; height: 10px; outline: 0;border-width: 0 0 1px; border-color: black; margin-bottom: -2px;"></p>
                            <table class="wew" style="width: 90%; margin-left: 20px;">
                                <tbody>
                                    <tr>
                                        <td style="width: 33%;"></td>
                                        {{-- <td style="text-align: center; width: 33%;">Vacation Leave</td>
                                        <td style="text-align: center; width: 33%;">Sick Leave</td> --}}
                                        @foreach($data['credits'] as $leave)
                                            <th style="text-align: center; width: {{ 100 / (count($data['credits']) + 1) }}%;">
                                                {{ $leave['credit']['leave']['name'] }}
                                            </th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td>Total Earned</td>
                                        @foreach($data['credits'] as $values)
                                            <td style="text-align: center;">
                                                {{ $values['log']['old_balance'] ?? 0 }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td>Less this application</td>
                                        @foreach($data['credits'] as $values)
                                            <td style="text-align: center;">
                                                {{ $values['log']['amount'] ?? 0 }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td>Balance</td>
                                        @foreach($data['credits'] as $values)
                                            <td style="text-align: center;">
                                                {{ $values['log']['new_balance'] ?? 0 }}
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                            {{-- <h3 style="text-align:center; margin-top: 33px;"></h3>
                            <p style="text-align:center; margin-top: -17px; font-weight: bold;">____________________________________________</p>
                            <p style="text-align:center; margin-top: -8px;">(Authorized Official)</p> --}}
                           
                        </td>
                        <td style="width: 45%; border-right: 1.3px solid black; border-left: 1.3px solid black; border-bottom: none;">
                            <table style="border-spacing:0; border-collapse: collapse; margin-top: 0px;" cellspacing="0">
                                <tr>
                                    <td style="padding-left: 6px;">
                                        <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black;"></span> &nbsp;
                                        <span style="font-size: 10px; margin-top: -10px;">For Approval</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 6px;">
                                        <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black;"></span> &nbsp;
                                        <span style="font-size: 10px; margin-top: -10px;">For disapproval due to</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" style="  margin-left: 14px; width: 91%; height: 8px; outline: 0;border-width: 0 0 1px; border-color: black">
                                        <input type="text" style="  margin-left: 14.5px; width: 91%; height: 8px; outline: 0;border-width: 0 0 1px; border-color: black">
                                        <input type="text" style="  margin-left: 14px; width: 91%; height: 8px; outline: 0;border-width: 0 0 1px; border-color: black">
                                    </td>
                                </tr>
                               
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 55%; border-right: 1.3px solid black; border-left: 1.3px solid black; border-bottom: 1.3px solid black;">
                            <table style="border-spacing:0; border-collapse: collapse; margin-top: 0px;" cellspacing="0">
                                <tr>
                                <td style="text-align: center; padding-top: 20px;">
                                    <input type="text"
                                        value="{{ $data['signatory']['certified']['name']}}"
                                        style="
                                        text-align: center;
                                        text-transform: uppercase;
                                        font-size: 13px;
                                        font-weight: bold;
                                        margin-left: 8px; width: 91.5%;
                                        border: none;
                                        border-bottom: 1px solid black;
                                        outline: none;
                                        padding-bottom: 4px;
                                        ">
                                    <p style="margin-top: 0px; margin-bottom: 0; text-align: center;">
                                        (Authorized Officer)
                                    </p>
                                </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 45%; border-right: 1.3px solid black; border-left: 1.3px solid black;  border-bottom: 1.3px solid black;">
                            <table style="border-spacing:0; border-collapse: collapse; margin-top: 0px;" cellspacing="0">
                                <tr>
                                    <td style="text-align: center; padding-top: 20px;">
                                        <div style="position: relative; display: inline-block; width: 100%;">
                                            @if(!empty($data['signatories'][0]['recommended']['signature']))
                                                <img 
                                                    src="{{ public_path('storage/profile-signatures/' . $data['signatories'][0]['recommended']['signature']) }}" 
                                                    alt="Signature" 
                                                    style="
                                                        position: absolute;
                                                        top: -30px; /* adjust vertical placement */
                                                        left: 50%;
                                                        transform: translateX(-50%);
                                                        height: 60px;
                                                        width: auto;
                                                        opacity: 1; /* slightly transparent if you want */
                                                    ">
                                            @endif
                                            <input type="text"
                                                {{-- value="{{ ($data['recommended']) ? $data['recommended']['name'] : $signatory['recommended'][0]['name']}}" --}}
                                                value="{{ $data['signatory']['recommended']['name']}}"
                                                style="
                                                text-align: center;
                                                font-size: 13px;
                                                text-transform: uppercase;
                                                font-weight: bold;
                                                margin-left: 11px; width: 91.5%;
                                                border: none;
                                                border-bottom: 1px solid black;
                                                outline: none;
                                                padding-bottom: 4px;
                                                ">
                                        </div>
                                        <p style="margin-top: 0px; margin-bottom: 0; text-align: center;">
                                            (Authorized Officer)
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 55%; border-left: 1.3px solid black; font-size: 10px;">
                        7.C NOTED FOR:
                        </td>
                        <td style="width: 45%; border-right: 1.3px solid black; font-size: 10px;">
                        7.D DISAPPROVED DUE TO
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 55%; border-left: 1.3px solid black; font-size: 10px;">
                            <p style="margin-top: -2px;"><input type="text" value="{{ fmod($data['pay'], 1) == 0 ? (int)$data['pay'] : $data['pay'] }}" style="text-align: center; margin-left: 20px; width: 12%; height: 10px; outline: 0;border-width: 0 0 1px; border-color: black; margin-bottom: -2px;">{{($data['pay'] > 1) ? 'days' : 'day'}} with pay</p>
                            <p style="margin-top: -10px;"><input type="text" value="{{ fmod($data['nopay'], 1) == 0 ? (int)$data['nopay'] : $data['nopay'] }}" style="text-align: center; margin-left: 20px; width: 12%; height: 10px; outline: 0;border-width: 0 0 1px; border-color: black; margin-bottom: -2px;"> {{($data['nopay'] > 1) ? 'days' : 'day'}} without pay</p>
                            <p style="margin-top: -10px;"><input type="text" value="" style="text-align: center; margin-left: 20px; width: 12%; height: 10px; outline: 0;border-width: 0 0 1px; border-color: black; margin-bottom: -2px;"> others (Specify)</p>
                        </td>
                        <td style="width: 45%; border-right: 1.3px solid black; font-size: 10px;">
                             <p style="margin-top: -10px;"><input type="text" value="" style="margin-left: 20px; width: 88%; height: 10px; outline: 0;border-width: 0 0 1px; border-color: black; margin-bottom: -2px;"></p>
                             <p style="margin-top: -10px;"><input type="text" value="" style="margin-left: 20px; width: 88%; height: 10px; outline: 0;border-width: 0 0 1px; border-color: black; margin-bottom: -2px;"></p>
                             <p style="margin-top: -10px;"><input type="text" value="" style="margin-left: 20px; width: 88%; height: 10px; outline: 0;border-width: 0 0 1px; border-color: black; margin-bottom: -2px;"></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 55%; border-bottom: 1.3px solid black; border-left: 1.3px solid black; border-right: 1.3px solid black; text-align:center;">
                            <div style="position: relative; display: inline-block; width: 100%;">
                               @if(!empty($data['signatories'][0]['approved']['signature']))
                                    <img 
                                        src="{{ public_path('storage/profile-signatures/' . $data['signatories'][0]['approved']['signature']) }}" 
                                        alt="Signature" 
                                        style="
                                            position: absolute;
                                            top: -30px; /* adjust vertical placement */
                                            left: 50%;
                                            transform: translateX(-50%);
                                            height: 60px;
                                            width: auto;
                                            opacity: 1; /* slightly transparent if you want */
                                        "
                                    >
                                @endif
                                <input type="text"
                                    {{-- value="{{ ($data['approved']) ? $data['approved']['name'] : $signatory['approved']['name']}}" --}}
                                    value="{{ $data['signatory']['approved']['name']}}"
                                    style="
                                    margin-top: 0px;
                                    text-align: center;
                                    text-transform: uppercase;
                                    font-size: 13px;
                                    font-weight: bold;
                                    width: 40%;
                                    border: none;
                                    border-bottom: 1px solid black;
                                    outline: none;
                                    padding-bottom: 4px;
                                    ">  
                            </div>
                            <p style="margin-top: 0px; margin-bottom: 0; text-align: center; font-weight: bold;">
                                (Authorized Official)
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>