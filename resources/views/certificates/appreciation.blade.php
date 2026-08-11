<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate</title>
    <style>
        @font-face {
            font-family: 'Certificate Body';
            font-weight: 400;
            font-style: normal;
            src: url("{{ public_path('fonts') }}/Roboto-Regular.ttf") format('truetype');
        }

        @font-face {
            font-family: 'Certificate Body';
            font-weight: 700;
            font-style: normal;
            src: url("{{ public_path('fonts') }}/Roboto-Bold.ttf") format('truetype');
        }

        @font-face {
            font-family: 'Certificate Name';
            font-weight: 700;
            font-style: italic;
            src: url("{{ public_path('fonts') }}/Poppins-BoldItalic.ttf") format('truetype');
        }

        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Certificate Body', sans-serif;
        }

        #background {
            position: absolute;
            top: 0;
            left: 0;
            width: 2000px;
            height: 1414px;
        }

        #recipient {
            position: absolute;
            left: 208px;
            top: 600px;
            width: 1220px;
            height: 120px;
            color: #80211d;
            font-family: 'Certificate Name', sans-serif;
            font-weight: 700;
            font-style: italic;
            white-space: nowrap;
        }

        #body {
            position: absolute;
            left: 214px;
            top: 800px;
            width: 1337px;
            text-align: justify;
            color: #000000;
        }

        #issued {
            position: absolute;
            left: 214px;
            top: 987px;
            width: 1300px;
            color: #000000;
        }

        .emphasis {
            color: #771d1b;
            font-weight: 700;
        }

        .event {
            color: #14314f;
            font-weight: 700;
        }

        .venue {
            color: #ec8836;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <img id="background" src="{{ public_path('images/appreciation-template.png') }}" alt="">

    <div id="recipient" style="font-size: {{ $recipientFontSize }}px; line-height: {{ $recipientFontSize }}px;">
        {{ $data['participant']['firstname'].' '.$data['participant']['middlename'][0].'. '.$data['participant']['lastname'] }}
    </div>

    <div id="body" style="font-size: {{ $bodyFontSize }}px; line-height: 36.3px;">
        @foreach ($bodySegments as $segment)<span class="{{ $segment['style'] }}">{{ $segment['text'] }}</span>@endforeach
    </div>

    <div id="issued" style="font-size: {{ $bodyFontSize }}px; line-height: 36.3px;">
        @foreach ($issueSegments as $segment)<span class="{{ $segment['style'] }}">{{ $segment['text'] }}</span>@endforeach
    </div>
</body>
</html>
