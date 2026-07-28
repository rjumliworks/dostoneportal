<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration Successful</title>
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
                                Registration Confirmation
                            </p>

                            @if ($isVip)
                                <p style="margin: 0 0 4px; display: inline-block; background-color: #F1A442; color: #1a202c; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; padding: 3px 10px; border-radius: 999px;">
                                    VIP / Special Guest
                                </p>
                            @endif

                            <h1 style="margin: 0 0 14px; color: #1a202c; font-size: 19px; font-weight: 700;">
                                Thank you, {{ $name }}!
                            </h1>

                            @if ($isVip)
                                <p style="margin: 0 0 20px; color: #4a5568; font-size: 12px; line-height: 1.3;">
                                    As our valued VIP guest, your registration for <strong>{{ $session->title }}</strong>
                                    has been automatically confirmed &mdash; your seat is guaranteed and no further
                                    review is needed. We look forward to having you.
                                </p>
                            @elseif ($wasFull)
                                <p style="margin: 0 0 20px; color: #4a5568; font-size: 12px; line-height: 1.3;">
                                    The session you requested, <strong>{{ $session->title }}</strong>, had already
                                    reached its maximum capacity. You've been placed on the waitlist for this
                                    session &mdash; if a slot opens up, waitlisted registrants are offered a seat
                                    in the order they registered.
                                </p>
                            @elseif ($session)
                                <p style="margin: 0 0 20px; color: #4a5568; font-size: 12px; line-height: 1.3;">
                                    Your registration for <strong>{{ $session->title }}</strong> has been received
                                    and is now pending review.
                                </p>
                            @endif

                            @if ($session)
                                <!-- Session Details -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                        <td style="padding: 16px; background-color: #f7fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                            <p style="margin: 0 0 10px; color: #718096; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;">
                                                {{ $isVip ? 'Confirmed Session' : ($wasFull ? 'Waitlisted Session' : 'Session Details') }}
                                            </p>
                                            <p style="margin: 0 0 6px; color: #1a202c; font-size: 15px; font-weight: 700;">
                                                {{ $session->title }}
                                            </p>
                                            @if ($session->schedules?->first()?->date)
                                                <p style="margin: 0 0 2px; color: #4a5568; font-size: 13px;">
                                                    {{ \Carbon\Carbon::parse($session->schedules->first()->date)->format('l, F j, Y') }}
                                                </p>
                                            @endif
                                            @if ($session->venue?->establishment)
                                                <p style="margin: 0; color: #4a5568; font-size: 13px;">
                                                    {{ $session->venue->establishment }}
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            @if ($isVip)
                                <p style="margin: 20px 0 0; color: #4a5568; font-size: 12px; line-height: 1.3;">
                                    You may view your registration details anytime through
                                    <a href="https://mobile.dost9.ph" style="color: #144C8D; font-weight: 700; text-decoration: underline;">mobile.dost9.ph</a>.
                                    Should your plans change, please let the organizers know ahead of time.
                                </p>
                            @elseif ($wasFull)
                                <p style="margin: 20px 0 0; color: #4a5568; font-size: 12px; line-height: 1.3;">
                                    You can check your waitlist status, or browse other sessions open to the
                                    public, through
                                    <a href="https://mobile.dost9.ph" style="color: #144C8D; font-weight: 700; text-decoration: underline;">mobile.dost9.ph</a>
                                    &mdash; each session shows its own registration status on the app.
                                </p>
                            @elseif ($session)
                                <p style="margin: 20px 0 0; color: #4a5568; font-size: 12px; line-height: 1.3;">
                                    You can also register for other sessions open to the public &mdash; each
                                    session shows its own registration status on the mobile app. Then, to view
                                    the status of your registration, visit the app through
                                    <a href="https://mobile.dost9.ph" style="color: #144C8D; font-weight: 700; text-decoration: underline;">mobile.dost9.ph</a>.
                                </p>
                            @else
                                <p style="margin: 0 0 20px; color: #4a5568; font-size: 14px; line-height: 1.3;">
                                    Your registration has been received. Open
                                    <a href="https://mobile.dost9.ph" style="color: #144C8D; font-weight: 700; text-decoration: underline;">mobile.dost9.ph</a>
                                    on your phone's browser to search for sessions and exhibits that fit your
                                    interests &mdash; as long as they're open to the public, you can check if
                                    registration is already open and sign up.
                                </p>
                            @endif
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
