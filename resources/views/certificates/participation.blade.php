<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0; /* Remove page margins for full bg */
        }
        @font-face {
            font-family: 'Great Vibes';
            src: url({{ public_path('fonts/LeckerliOne-Regular.ttf') }}) format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
            position: relative;
        }

        /* Full background */
        .certificate-bg {
            position: fixed; /* Always behind */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        /* Content with margins */
        .content {
            margin: 40px 60px; /* Your custom safe margins */
            position: relative;
            z-index: 1;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('images/ca.png') }}" class="certificate-bg">
    <div class="content">
        <h1 style="text-align: center; font-size: 40px; margin-top: 300px; text-transform: uppercase; margin-left: -185px; margin-bottom: -30px; color:#00018d; font-family: 'Roboto', sans-serif;">
            <ins>{{$data['participant']['firstname'].' '.$data['participant']['middlename'][0].'. '.$data['participant']['lastname']}}</ins>
        </h1>
        
        <div style="max-width: 780px; margin-left: 23px; margin-top: 20px;">
            <p style="text-align: center; font-size: 15px; line-height: 1.4; margin-top: 60px;">
                  For the active participation during the session 
                <b><ins><i>"{{$data['session']['title']}}"</i></ins></b> conducted as part of the
                <b><ins>{{$data['session']['event']['name']}}</b></ins> held on 
               {{ \Carbon\Carbon::parse($data['session']['schedules'][0]['date'])->format('d F Y') }} at {{ $data['session']['venue']['name']}}, {{ $data['session']['event']['detail']['venue']}}. 
            </p> 

            <p style="text-align: center; margin-top: 50px; font-size: 15px;">
                Given this <b>{{ \Carbon\Carbon::now()->format('jS \\d\\a\\y \\o\\f F Y') }} in Zamboanga City, Philippines.</b>
            </p>
        </div>

        <img src="{{ public_path('images/esig.png') }}" 
                alt="tag" 
                style="width: 200px; height: 200px; position: absolute; bottom: 15; left: 240;">
    </div>
</body>
</html>
