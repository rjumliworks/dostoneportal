<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>DOST Special Order No. {{ $post->number }}</title>
    <style>
        @page {
            margin: 50px 60px 100px 60px;
        }

        body {
            margin: 0.2in;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #000000;
        }

        .header-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
            margin-bottom: 10px;
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
            margin-bottom: 0px;
        }

        .header-table .agency-text .agency {
            font-size: 16px;
            margin-bottom: 0px;
            font-weight: bold;
        }

        .header-table .agency-text .region {
            font-size: 12px;
            margin-bottom: 0px;
        }

        .header-table .agency-text .tagline {
            font-size: 11px;
            font-weight: bold;
        }

        hr.divider {
            border: none;
            border-top: 2px solid #000000;
            margin: 8px 0 20px 0;
        }

        .order-title {
            font-weight: bold;
            font-size: 13px;
            margin: 0;
        }

        .order-series {
            margin: 0 0 24px 0;
        }

        .content {
            text-align: justify;
            line-height: 1.6;
        }

        .content p {
            margin: 0 0 12px 0;
        }

        .content ul {
            margin: 0 0 12px 0;
            padding-left: 22px;
        }

        .signature-block {
            float: right;
            text-align: center;
            width: 220px;
            margin-top: 30px;
        }

        .signature-block img {
            width: 130px;
            margin-bottom: -40px;
        }

        .signature-block p {
            margin: 0;
        }

        .signature-block .name {
            font-weight: bold;
        }

        .clear {
            clear: both;
        }

        .date-line {
            margin-top: 50px;
        }

        .footer {
            position: fixed;
            bottom: -80px;
            left: 0;
            right: 0;
            border-top: 2px solid #000000;
            padding-top: 8px;
            font-size: 9px;
        }

        .footer table {
            width: 100%;
        }

        .footer .tagline-row {
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="logo logo-left" style="text-align: left;">
                <img src="{{ public_path('images/logos/logo-sm.png') }}" alt="DOST">
            </td>
            <td class="agency-text">
                <div class="agency-text-inner">
                    <div class="country">Republic of the Philippines</div>
                    <div class="agency">DEPARTMENT OF SCIENCE AND TECHNOLOGY</div>
                    <div class="region">ZAMBOANGA PENINSULA</div>
                    <div class="tagline">OneDOST4U: Solutions and Opportunities for All</div>
                </div>
            </td>
            <td class="logo logo-right" style="text-align: right;">
                <img src="{{ public_path('images/logos/bagongpilipinas.png') }}" alt="Bagong Pilipinas">
            </td>
        </tr>
    </table>
    <br />
    {{-- <hr class="divider"> --}}

    @if ($post->number)
        <p class="order-title">DOST SPECIAL ORDER No. <u>{{ $post->number }}</u></p>
        <p class="order-series">Series of {{ $post->created_at->year }}</p>
    @endif

    <div class="content">
        {!! $post->content !!}
    </div>

    <div class="signature-block">
        <img src="{{ public_path('images/rss.png') }}" alt="Signature">
        <p class="name">ROSEMARIE S. SALAZAR</p>
        <p class="position">Regional Director</p>
    </div>

    <div class="clear"></div>

    <p class="date-line">{{ $post->created_at->format('d F Y') }}</p>

    <div class="footer">
        <table>
            <tr>
                <td style="width:38%;">
                    <b>Postal Address:</b><br>
                    P.O. Box 118, Petit Barracks,<br>
                    Zamboanga City, 7000
                </td>
                <td style="width:32%;">
                    <b>Tel. No.:</b> (062) 991-1024, (062) 991-2050<br>
                    <b>Telefax:</b> (062) 991-2051, (062) 992-1114
                </td>
                <td style="width:30%;">
                    <b>Website:</b> https://region9.dost.gov.ph<br>
                    <b>E-mail Address:</b> ord@ro9.dost.gov.ph
                </td>
            </tr>
        </table>
        <div class="tagline-row">INNOVATE &nbsp;&gt;&nbsp; EXCEL &nbsp;&gt;&nbsp; LEAD</div>
    </div>
</body>
</html>
