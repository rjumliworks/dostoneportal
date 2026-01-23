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
    // $travel = json_encode($travel); 
    // $travel = json_decode($travel, true);   
    // $signatory = $data['signatories'][0];
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
                <td style="border-right-style: hidden;" style="width: 50%; text-align: left; font-size: 10px;"><br/> <span style="font-weight: bold; color: #072388;">{{$data['code']}}</span></td>
                <td style="border-left-style: hidden; width: 50%; text-align: right; font-size: 10px;"></td>
            </tr>
        </table>
    </div>

    <div class="content">
        
            <div style="font-family:Arial;">
                <img src="{{ public_path('images/logo-sm.png') }}" alt="tag" style="position: absolute; top: -4; left: 60; width: 50px; height: 50px;">
                <center style="font-size: 10px; margin-bottom: 0px; text-transform: uppercase;">Republic of the Philippines</center>
                <center style="font-size: 11px; margin-bottom: 0px; font-weight: bold;">DEPARTMENT OF SCIENCE AND TECHNOLOGY - IX</center>
                <center style="font-size: 11px;">Pettit Baracks, Zamboanga City | (062) 991-1024 | dost9info@gmail.com</center>
            
                <br/>
                {{-- <center style="margin-top: 15px; font-size: 11px;  color:#000; font-weight: bold; padding: 2px;">RENDER OVERTIME SERVICE</center> --}}
            
            <div style="
                font-size: 11px;
                font-weight: bold;
                text-transform: uppercase;
                text-align: center;
                letter-spacing: 1px;
                color: #097eeb;
                border-bottom: 2px solid #097eeb;
                padding-top: 4px;
                border-top: 2px solid #097eeb;
                padding-bottom: 4px;
                margin-bottom: 10px;
                margin-top: 10px;
            ">
                RENDER OVERTIME SERVICE
            </div>


            </div>
            <table style="border: 1px solid black;">
                <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
                    <tr>    
                        <th style="vertical-align: middle;" width="50%">REQUESTED BY</th>
                        <th style="vertical-align: middle;" width="50%">REQUESTED DATE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="text-align: center; text-transform: uppercase; color: #072388; font-weight: bold;">
                        <td style="text-align: center; padding: 5px; font-size: 10x;">{{$data['created_by']}}</td>
                        <td style="text-align: center; padding: 5px; font-size: 10x;">{{$data['created_at']}}</td>
                    </tr>
                </tbody>
            </table>

            <h6 style="font-size: 10px; margin-top: 12px;">LIST OF EMPLOYEE : </h6>
            <table style="border: 1px solid black; margin-top: -22px;">
                <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
                    <tr>    
                        <th style="vertical-align: middle;" width="33%">NAME</th>
                        <th style="vertical-align: middle;" width="33%">POSITION</th>
                        <th style="vertical-align: middle;" width="33%">DIVISION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['employees'] as $employee)
                        <tr style="text-align: center; text-transform: uppercase; color: #072388; font-weight: bold;">
                            <td style="text-align: center; padding: 5px; font-size: 10x;">{{$employee['name']}}</td>
                            <td style="text-align: center; padding: 5px; font-size: 10x;">
                                {{$employee['position_short']}}
                                 {{ strlen($employee['position']) > 37 ? $employee['position_short'] : $employee['position'] }}
                            </td>
                            <td style="text-align: center; padding: 5px; font-size: 10x;">
                                {{-- {{$employee['division_short']}} --}}
                                {{ strlen($employee['division']) > 37 ? $employee['division_short'] : $employee['division'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h6 style="font-size: 10px; margin-top: 12px;">DETAILS :</h6>
            <table style="border: 1px solid black; font-size: 12px; margin-top: -22px;">
                <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
                    <tr>    
                        <th style="vertical-align: middle;" width="50%">TARGET DATE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="font-size: 10px; color: #072388;">
                        <td style="text-align: center; padding: 7px; text-transform: uppercase;">
                            <span style="font-weight: bold;">{{$data['date']}}</span>  
                        </td>
                    </tr>
                </tbody>
                 <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
                    <tr>    
                        <th style="vertical-align: middle;" width="50%">PURPOSE/TARGET DELIVERABLES</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="font-size: 10px; color: #072388;">
                        <td style="text-align: center; padding: 7px; text-transform: uppercase;">
                            <span style="font-weight: bold;">{{$data['purpose']}}</span> 
                        </td>
                    </tr>
                </tbody>
            </table>

            <center style="margin-top: 15px; font-size: 8px; background-color: #000; color:#fff; font-weight: bold; padding: 2px;">FOR RECOMMENDATION AND APPROVAL SIGNATURE</center>
            <table style="border: 1px solid black; font-size: 10px; margin-top: 0px; page-break-inside: avoid;">
                <tbody>
                    <tr>
                        <td style="min-height: 50px; border-bottom-style: hidden;">
                            {{-- @if($division != 'Office of the Regional Director') --}}
                            <span style="font-size:9px; color: #606060;">RECOMMENDING APPROVAL:</span>
                            {{-- @endif --}}
                        </td>
                        <td style="min-height: 50px; border-bottom-style: hidden;">
                            <span style="font-size:9px; color: #606060;">APPROVED:</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="min-height: 100px; padding: 15px; border-bottom-style: hidden;"></td>
                        <td style="min-height: 100px; padding: 15px; border-bottom-style: hidden;"></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td width="33.3%" style="vertical-align: bottom; position: relative; height: 50px; text-align: center;">
                            <div style="position: relative; height:50px;">
                                {{-- Signature container --}}
                                <div style="position: absolute; bottom: 25px; left: 50%; transform: translateX(-50%);">
                                    @if(!empty($data['signatories'][0]['recommended']['signature']))
                                        <img 
                                            src="{{ public_path('storage/profile-signatures/' . $data['signatories'][0]['recommended']['signature']) }}" 
                                            alt="Signature" 
                                            style="height: 60px; width: auto;"
                                        >
                                    @endif
                                </div>

                                {{-- Name and designation --}}
                              <div style="position: absolute; bottom: 0; width: 100%;">
                                    <span style="font-weight: bold; font-size: 11px; color: #072388; text-transform: uppercase;">
                                        {{  ($data['signatory']['cto']['name']) }}
                                    </span>
                                    <hr style="margin-top: 0px; margin-bottom: 1px; border: .1px solid black; width: 80%;">
                                    <div style="font-size: 10px;">
                                        {{  ($data['signatory']['cto']['role']) }}
                                    </div>
                                </div>
                            </div>
                            {{-- @endif --}}
                        </td>
                        <td width="33.3%" style="vertical-align: bottom; position: relative; height: 50px; text-align: center;">
                            <div style="position: relative; height: 50px;">
                                {{-- Signature container --}}
                                <div style="position: absolute; bottom: 25px; left: 50%; transform: translateX(-50%);">
                                    @if(!empty($data['signatories'][0]['approved']['signature']))
                                        <img 
                                            src="{{ public_path('storage/profile-signatures/' . $data['signatories'][0]['approved']['signature']) }}" 
                                            alt="Signature" 
                                            style="height: 60px; width: auto;"
                                        >
                                    @endif
                                </div>

                                {{-- Name and designation --}}
                                <div style="position: absolute; bottom: 0; width: 100%;">
                                    <span style="font-weight: bold; font-size: 11px; color: #072388; text-transform: uppercase;">
                                        {{ $data['signatory']['approved']['name']}}
                                    </span>
                                    <hr style="margin-top: 0px; margin-bottom: 1px; border: .1px solid black; width: 80%;">
                                    <div style="font-size: 10px;">
                                       {{  $data['signatory']['approved']['role'] }}
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                </tbody>
            </table>
        
    </div>
</body>
</html>