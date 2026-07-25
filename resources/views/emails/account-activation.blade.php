<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Activate Your Account</title>
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
                                        <img src="{{ asset('images/logo-sm.png') }}" alt="DOST" style="display: block; width: 40px; height: 40px;">
                                    </td>
                                    <td style="vertical-align: middle; padding-left: 12px;">
                                        <p style="margin: 0; color: rgba(255,255,255,100); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;">
                                            Department of Science and Technology &mdash; IX
                                        </p>
                                        <p style="margin: 2px 0 0; color: #ffffff; font-size: 12px;">
                                            One<span style="color: #F1A442;">DOST</span>4U : Solutions and Opportunities for All
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
                                Account Verification
                            </p>

                            <h1 style="margin: 0 0 14px; color: #1a202c; font-size: 19px; font-weight: 700;">
                                Good day, {{ $user->username }}
                            </h1>

                            <p style="margin: 0 0 20px; color: #4a5568; font-size: 14px; line-height: 1.6;">
                                Your account requires activation. Enter the code below to complete your registration.
                                For your security, it expires in <strong>10 minutes</strong>.
                            </p>

                            <!-- Activation Code -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                <tr>
                                    <td align="center" style="padding: 16px; background-color: #f7fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <p style="margin: 0 0 6px; color: #718096; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;">
                                            Activation Code
                                        </p>
                                        <p style="margin: 0; color: #144C8D; font-size: 26px; font-weight: 700; letter-spacing: 6px; font-family: 'Courier New', monospace;">
                                            {{ $code }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Security Notice -->
                    <tr>
                        <td style="padding: 16px 28px; background-color: #f7fafc; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; color: #718096; font-size: 12px; line-height: 1.6;">
                                <strong style="color: #4a5568;">Security tip:</strong> if you didn't request this code, please ignore this email or contact the MIS Unit.
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
