<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>WedinWheels | Car Selection</title>
</head>
<body style="margin:0;padding:0;background:#0b0b0b;font-family:Arial, sans-serif;color:#eaeaea;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#0b0b0b;padding:30px 0;">
        <tr>
            <td align="center">

                <!-- Main Container -->
                <table width="620" cellpadding="0" cellspacing="0"
                    style="background:#141414;border:1px solid #2b2b2b;border-radius:14px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.6);">

                    <!-- Header -->
                    <tr>
                        <td style="padding:22px 26px;background:linear-gradient(90deg,#0f0f0f,#1f1f1f);border-bottom:1px solid #2b2b2b;">
                            <h1 style="margin:0;font-size:22px;letter-spacing:0.5px;color:#ffffff;">
                                WedinWheels
                            </h1>
                            <p style="margin:6px 0 0;font-size:13px;color:#bdbdbd;">
                                Premium Wedding Car Booking
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:26px;">

                            <p style="margin:0 0 14px;font-size:15px;color:#eaeaea;">
                                Hi <b style="color:#ffffff;">{{ $lead->client_name ?? 'Customer' }}</b>,
                            </p>

                            <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#cfcfcf;">
                                Thank you for choosing <b style="color:#ffffff;">WedinWheels</b>.
                                Please select your car using the button below.
                            </p>

                            <!-- Custom message -->
                            @if(!empty($msg))
                                <div style="background:#0f0f0f;border:1px solid #2b2b2b;padding:14px 16px;border-radius:10px;margin:18px 0;">
                                    <p style="margin:0;font-size:13px;color:#cfcfcf;white-space:pre-line;line-height:1.6;">
                                        {{ $msg }}
                                    </p>
                                </div>
                            @endif

                            <!-- CTA Button -->
                            <div style="text-align:center;margin:26px 0 22px;">
                                <a href="{{ $url }}"
                                   style="display:inline-block;padding:14px 26px;border-radius:10px;
                                          background:#2f2f2f;color:#ffffff;text-decoration:none;
                                          font-size:14px;font-weight:bold;border:1px solid #3d3d3d;">
                                   ✅ Select Your Car
                                </a>
                            </div>

                            <!-- Fallback URL -->
                            <p style="margin:0;font-size:12px;color:#8f8f8f;line-height:1.6;">
                                If the button doesn’t work, copy & paste this link:
                                <br>
                                <a href="{{ $url }}" style="color:#bdbdbd;text-decoration:underline;">
                                    {{ $url }}
                                </a>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:18px 26px;background:#0f0f0f;border-top:1px solid #2b2b2b;">
                            <p style="margin:0;font-size:12px;color:#9a9a9a;line-height:1.6;">
                                © {{ date('Y') }} WedinWheels. All rights reserved.
                                <br>
                                Need help? Reply to this email.
                            </p>

                            <p style="margin:10px 0 0;font-size:11px;color:#6e6e6e;">
                                Sent on: {{ now() }}
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
