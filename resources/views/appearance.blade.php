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
            margin: 10px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
        }
        .content {
            margin-bottom:55px; /* Space for the footer */
        }
        .page-break {
            page-break-after: always;
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
    </style>
</head>
<body>
     <div class="content">
        <div class="header">
    <div style="font-family:Arial;">
        {{-- <img src="{{ public_path('images/dost.png') }}" alt="tag" style="position: absolute; top: -4; left: 15; width: 80px; height: 80px;">
        <div style="position: absolute; top: -2; left: 73;">
            <p style="font-size: 15px; margin-bottom: -9px;">Republic of the Philippines</p>
            <p style="font-size: 17px; margin-bottom: -10px; font-weight: bold;">DEPARTMENT OF SCIENCE AND TECHNOLOGY</p>
            <p style="font-size: 15px; margin-bottom: 0px;">Regional Office IX</p>
        </div>
        <img src="{{ public_path('images/bagongpilipinas.png') }}" alt="tag" style="position: absolute; top: -4; right: 15; width: 80px; height: 80px;"> --}}
        <table  style="width:100%; border:none; border-collapse:collapse; margin-bottom:15px; margin-top: -20px;">
            <tr>
                <td style="width:78px; text-align:left;">
                    <img src="{{ public_path('images/logos/logo-sm.png') }}" style="width:75px; margin-left: -10px;">
                </td>
                <td style="text-align:left; font-family:Arial;" >
                    <div style="margin-left: -20px;">
                        <div style="font-size:11px; margin-bottom: -10px;">Republic of the Philippines</div>
                        <div style="font-size: 16px; margin-bottom: -10px; font-weight: bold;">DEPARTMENT OF SCIENCE AND TECHNOLOGY</div>
                        <div style="font-size:12px; margin-bottom: -10px;">ZAMBOANGA DEL NORTE</div>
                        <div style="font-size:11px;  font-weight: bold;">OneDOST4U: Solutions and Opportunities for All</div>
                    </div>
                </td>
                <td style="width:78px; text-align:right;">
                    <img src="{{ public_path('images/logos/bagongpilipinas.png') }}" style="width:75px; margin-right: -10px;">
                </td>
            </tr>
        </table>
        <h1 style="text-align: center; font-size: 26px; margin-top: 140px;">CERTIFICATE OF APPEARANCE</h1>

        <p style="text-align: justify; font-size: 15px; line-height: 1.4; margin-top: 60px;">
            This is to certify that <b><ins>Juan D. Dela Cruz</ins></b> of <b><ins>Sample Affiliation Inc.</ins></b> Appeared at <b><ins>Sample Venue, Zamboanga City</ins></b> on <b><ins>{{ \Carbon\Carbon::now()->format('F d, Y') }}</b></ins> to participate<b><ins> Sample Event Name.</b></ins>
        </p>

        <p style="text-align: justify; margin-top: 30px; font-size: 15px; line-height: 1.4;">
            This certification is issued in compliance with the standing regulations provided under 
            Republic Act No. 3847 duly implemented by COA Circular No. 127
            for the purpose of establishing the evidence and duration of his/her appearance hereto.  
            The truth of which is hereby vouchsafed and guaranteed by the undersigned.
        </p>

        <p  style="margin-top: 30px; font-size: 15px;">
            Issued this <b><ins>{{ \Carbon\Carbon::now()->format('jS \\d\\a\\y \\o\\f F Y') }}</ins></b> in Zamboanga City, Philippines.
        </p>

        <div style="float: right; text-align: center; margin-top: 70px; position: relative; width: 200px; height: 200px;">
            <!-- Signature Image -->
            <img src="{{ public_path('images/esig.png') }}" 
                alt="tag" 
                style="width: 200px; height: 200px; position: absolute; top: 0; left: 0;">

            <!-- Name & Title -->
            <div style="position: absolute; bottom: 10px; left: 0; width: 100%;">
                <h1 style="font-size: 17px; margin: 0; color: black;">NUHMAN M. ALJANI</h1>
                <p style="font-size: 13px; margin: 0; color: black;">Provincial S&T Director</p>
            </div>
        </div>
        
    </div>
</div>
</div>
</body>
</html>
