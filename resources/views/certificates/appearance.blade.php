<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Certificate of Appearance</title>
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
            font-family: 'Certificate Body', Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #000000;
        }

        .content {
            padding: 50px 60px;
        }

        .header-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .header-table .logo {
            width: 78px;
        }

        .header-table .logo img {
            width: 75px;
        }

        .header-table .logo-left img {
            margin-left: -10px;
        }

        .header-table .logo-right img {
            margin-right: -10px;
        }

        .header-table .agency-text {
            text-align: left;
        }

        .header-table .agency-text .agency-text-inner {
            margin-left: -10px;
        }

        .header-table .agency-text .country {
            font-size: 11px;
            margin-bottom: -5px;
        }

        .header-table .agency-text .agency {
            font-size: 16px;
            margin-bottom: -5px;
            font-weight: bold;
        }

        .header-table .agency-text .region {
            font-size: 12px;
            margin-bottom: -5px;
        }

        .header-table .agency-text .tagline {
            font-size: 11px;
            font-weight: bold;
        }

        h1.title {
            text-align: center;
            font-size: 26px;
            margin-top: 50px;
            margin-bottom: 0px;
        }

        .caption {
            text-align: center;
            font-size: 15px;
            margin-top: 40px;
        }

        #recipient {
            text-align: center;
            color: #80211d;
            font-family: 'Certificate Name', sans-serif;
            font-weight: 700;
            font-style: italic;
            margin-top: 10px;
        }

        #body {
            text-align: justify;
            line-height: 1.6;
            margin-top: 25px;
        }

        .compliance {
            text-align: justify;
            font-size: 15px;
            line-height: 1.4;
            margin-top: 30px;
        }

        #issued {
            margin-top: 30px;
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

        .signature {
            float: right;
            text-align: center;
            margin-top: 60px;
            position: relative;
            width: 200px;
        }

        .signature img {
            width: 160px;
            margin-bottom: -50px;
        }

        .signature p {
            margin: 0;
            color: #000000;
        }

        .signature .name {
            font-size: 15px;
        }

        .signature .position {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="content">
        <table class="header-table">
            <tr>
                <td class="logo logo-left" style="text-align: left;">
                    <img src="{{ public_path('images/logos/logo-sm.png') }}" alt="DOST">
                </td>
                <td class="agency-text">
                    <div class="agency-text-inner">
                        <div class="country">Republic of the Philippines</div>
                        <div class="agency">DEPARTMENT OF SCIENCE AND TECHNOLOGY</div>
                        <div class="region">ZAMBOANGA DEL NORTE</div>
                        <div class="tagline">OneDOST4U: Solutions and Opportunities for All</div>
                    </div>
                </td>
                <td class="logo logo-right" style="text-align: right;">
                    <img src="{{ public_path('images/logos/bagongpilipinas.png') }}" alt="Bagong Pilipinas">
                </td>
            </tr>
        </table>

        <h1 class="title">CERTIFICATE OF APPEARANCE</h1>

        <p style="text-align: justify; font-size: 15px; line-height: 1.4; margin-top: 60px;">
            This is to certify that <b><ins>{{ $recipientName }}</ins></b> of <b><ins>{{ $affiliationName }}</ins></b> appeared at <b><ins>{{ $venueText }}</ins></b> on <b><ins>{{ $sessionDateText }}</ins></b> to participate in the<b><ins> ZamPen Regional Science, Technology, and Innovation Week 2026
(Zampen RSTW 2026).</ins></b>
        </p>

        <p class="compliance">
            This certification is issued in compliance with the standing regulations provided under
            Republic Act No. 3847 duly implemented by COA Circular No. 127
            for the purpose of establishing the evidence and duration of his/her appearance hereto.
            The truth of which is hereby vouchsafed and guaranteed by the undersigned.
        </p>

        <p id="issued">
            @foreach ($issueSegments as $segment)<span class="{{ $segment['style'] }}">{{ $segment['text'] }}</span>@endforeach
        </p>

        @if (($sessionId ?? null) == 9)
        <div class="signature">
            <img src="{{ public_path('images/jtf.png') }}" alt="Signature">
            <p class="name">JULIUS T. FOJAS</p>
            <p class="position">Laboratory Head</p>
        </div>
        @else
        <div class="signature">
            <img src="{{ public_path('images/nma.png') }}" alt="Signature">
            <p class="name">NUHMAN M. ALJANI</p>
            <p class="position">Provincial S&T Director</p>
        </div>
        @endif
    </div>
</body>
</html>
