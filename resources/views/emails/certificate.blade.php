<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Your Certificates</title>
</head>

<body style="margin: 0; padding: 0; background-color: #eef1f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td align="center" style="padding: 32px 16px;">

                <!-- Email Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                    style="max-width: 480px; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(20,40,70,.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #144C8D; border-bottom: 3px solid #F1A442; padding: 18px 28px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="width: 36px; vertical-align: middle;">
                                        <img src="{{ asset('images/logo-sm.png') }}" alt="DOST" width="30" height="30" style="display: block; width: 30px; height: 30px;">
                                    </td>
                                    <td style="vertical-align: middle; padding-left: 12px;">
                                        <p style="margin: 0; color: rgba(255,255,255,.72); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;">
                                            Department of Science and Technology &mdash; IX
                                        </p>
                                        <p style="margin: 2px 0 0; color: #ffffff; font-size: 14px; font-weight: 700;">
                                            One<span style="color: #F1A442;">DOST</span>4U
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 32px 28px 24px;">
                            <p style="margin: 0 0 4px; color: #144C8D; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;">
                                Certificates Enclosed
                            </p>

                            <h1 style="margin: 0 0 14px; color: #1a202c; font-size: 19px; font-weight: 700;">
                                Thank you, {{ $data->recipientName }}!
                            </h1>

                            <p style="margin: 0 0 20px; color: #4a5568; font-size: 12px; line-height: 1.3;">
                                Thank you for completing the Customer Satisfaction Feedback for
                                <strong>{{ $data->session->title }}</strong>, part of
                                <strong>{{ $data->session->event->name }}</strong>. Your Certificate of
                                Appearance and Certificate of Participation are attached to this email as PDF
                                files.
                            </p>

                            <!-- Session Details -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 16px; background-color: #f7fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <p style="margin: 0 0 10px; color: #718096; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;">
                                            Session Details
                                        </p>
                                        <p style="margin: 0 0 6px; color: #1a202c; font-size: 15px; font-weight: 700;">
                                            {{ $data->session->title }}
                                        </p>
                                        @if ($data->session->schedules?->first()?->date)
                                            <p style="margin: 0 0 2px; color: #4a5568; font-size: 13px;">
                                                {{ \Carbon\Carbon::parse($data->session->schedules->first()->date)->format('l, F j, Y') }}
                                            </p>
                                        @endif
                                        @if ($data->session->venue?->establishment)
                                            <p style="margin: 0; color: #4a5568; font-size: 13px;">
                                                {{ $data->session->venue->establishment }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 20px 0 0; color: #4a5568; font-size: 12px; line-height: 1.3;">
                                You can view your registration and attendance history anytime through
                                <a href="https://mobile.dost9.ph" style="color: #144C8D; font-weight: 700; text-decoration: underline;">mobile.dost9.ph</a>.
                            </p>
                        </td>
                    </tr>

                    <!-- Security Notice -->
                    <tr>
                        <td style="padding: 16px 28px; background-color: #f7fafc; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; color: #718096; font-size: 12px; line-height: 1.6;">
                                <strong style="color: #4a5568;">Security tip:</strong> DOST-IX will never ask for
                                sensitive information such as your One-Time PIN (OTP) or password via email or SMS.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Footer -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 480px;">
                    <tr>
                        <td align="center" style="padding: 16px 12px;">
                            <p style="margin: 0; color: #94a3b8; font-size: 11px; line-height: 1.6;">
                                DOST Regional Office No. IX &middot; Pettit Barracks, Zamboanga City<br>
                                This is an automated message, please do not reply.
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>

</html>
