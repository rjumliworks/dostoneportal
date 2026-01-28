{{-- <p>Good day {{ $user->username }},</p>

<p>Your account requires activation.</p>

<p><strong>Activation Code:</strong></p>

<h2 style="letter-spacing:3px;">{{ $code }}</h2>

<p>This code will expire in 10 minutes.</p>

<p>Regards,<br>{{ config('app.name') }}</p> --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Activate Your Account</title>
</head>

<body
    style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f4f7fa; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%"
        style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">

        <!-- Header -->
        <tr>
            <td style="padding: 25px 25px; background: linear-gradient(135deg, #004080 0%, #0099CC 100%);">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="vertical-align: middle; width: auto; padding-right: 20px; margin-top:">
                            <img src="{{ asset('images/logo-sm.png') }}" alt="DOST Logo"
                                style="max-width: 50px; margin-top: -7px; height: auto; display: block;">
                        </td>
                        <td style="vertical-align: middle; width: auto; text-align: left;">
                            <h1 style="margin-left: -30px; margin-top: 10px; color: #ffffff; font-size: 17px; font-weight: 600; letter-spacing: -0.5px;">
                                DEPARTMENT OF SCIENCE AND TECHNOLOGY - IX
                            </h1>
                            <p style="margin-left: -30px; margin-top: -12px; color: #ffffff; font-size: 16px; font-weight: 600; letter-spacing: -0.5px;">One<span style="color: rgb(44, 143, 205) !important;">DOST</span>4U : <span style="font-weight: 400;">Unified Information Management System</span></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Main Content -->
        <tr>
            <td style="padding: 50px 40px;">
                <!-- Greeting -->

                <h2 style="margin: 0 0 20px 0; color: #1a202c; font-size: 24px; font-weight: 600;">
                    Good day {{ $user->username }},
                    {{-- Good day {{ $user->username }}, --}}
                </h2>

                <!-- Message -->
                <p style="margin: 0 0 20px 0; color: #4a5568; font-size: 16px; line-height: 1.6;">
                    Your account requires activation. To complete your registration, please use the activation code
                    below.
                </p>

                <p style="margin: 0 0 30px 0; color: #4a5568; font-size: 16px; line-height: 1.6;">
                    This code will expire in <strong>10 minutes</strong> for security purposes.
                </p>


                <!-- Activation Code -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center"
                    style="margin: 0 auto;">
                    <tr>
                        <td
                            style="padding: 20px 40px; text-align: center; background-color: #f7fafc; border-radius: 8px; border: 2px dashed #0099CC;">
                            <p
                                style="margin: 0 0 10px 0; color: #4a5568; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                                Activation Code
                            </p>
                            <p
                                style="margin: 0; color: #004080; font-size: 32px; font-weight: 700; letter-spacing: 4px; font-family: 'Courier New', monospace;">
                                {{ $code }}
                                {{-- {{ $activationCode ?? '12345678' }} --}}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Security Notice -->
        <tr>
            <td style="padding: 30px 40px; background-color: #f7fafc; border-top: 1px solid #e2e8f0;">
                <p style="margin: 0; color: #718096; font-size: 13px; line-height: 1.6;">
                    <strong style="color: #4a5568;">🔒 Security Tip:</strong> If you didn't create an account with us,
                    please ignore this email or contact the MIS Unit if you have concerns.
                </p>
            </td>
        </tr>
    </table>
</body>

</html>