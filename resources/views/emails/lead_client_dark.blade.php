<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wedin Wheels</title>
</head>
<body style="margin:0; padding:0; background:#0b0f14; font-family:Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#0b0f14; padding:30px 0;">
    <tr>
        <td align="center">

            <table width="650" cellpadding="0" cellspacing="0"
                   style="max-width:650px; width:100%; background:#111826; border:1px solid #2a2f3b; border-radius:18px; overflow:hidden;">

                {{-- Header --}}
                <tr>
                    <td style="padding:22px 26px; background:#0f1724; border-bottom:1px solid #2a2f3b;">
                        <table width="100%">
                            <tr>
                                <td style="color:#ffc107; font-weight:800; font-size:18px;">
                                    Wedin Wheels
                                </td>
                                <td align="right" style="color:#9aa4b2; font-size:12px;">
                                    {{ now()->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:26px;">
                        <h2 style="margin:0 0 10px; color:#ffffff; font-size:22px; font-weight:800;">
                            Hello {{ $lead->client_name ?? 'Client' }} 👋
                        </h2>

                        <p style="margin:0 0 16px; color:#b7bcc9; font-size:14px; line-height:1.7;">
                            Thank you for choosing <b style="color:#ffc107;">Wedin Wheels</b>.
                            This email is regarding your booking request. Below are your lead details:
                        </p>

                        {{-- Lead Card --}}
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="background:#0b1220; border:1px solid #2a2f3b; border-radius:14px; padding:16px;">
                            <tr>
                                <td style="color:#9aa4b2; font-size:13px; padding:6px 0;">Lead ID</td>
                                <td align="right" style="color:#fff; font-size:13px; font-weight:700; padding:6px 0;">
                                    {{ $lead->unique_id ?? 'NA' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#9aa4b2; font-size:13px; padding:6px 0;">Event Type</td>
                                <td align="right" style="color:#fff; font-size:13px; font-weight:700; padding:6px 0;">
                                    {{ $lead->event_type ?? 'NA' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#9aa4b2; font-size:13px; padding:6px 0;">Booking Date</td>
                                <td align="right" style="color:#fff; font-size:13px; font-weight:700; padding:6px 0;">
                                    {{ $lead->booking_date ?? 'NA' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#9aa4b2; font-size:13px; padding:6px 0;">Contact</td>
                                <td align="right" style="color:#fff; font-size:13px; font-weight:700; padding:6px 0;">
                                    {{ $lead->contact ?? 'NA' }}
                                </td>
                            </tr>
                        </table>

                        {{-- Custom message️ message --}}
                        @if(!empty($customMessage))
                            <div style="margin-top:16px; padding:14px 16px; background:rgba(255,193,7,.10); border:1px solid rgba(255,193,7,.25); border-radius:14px;">
                                <div style="color:#ffc107; font-weight:800; font-size:13px; margin-bottom:6px;">
                                    Message from Wedin Wheels Team
                                </div>
                                <div style="color:#e6e8ee; font-size:13px; line-height:1.7;">
                                    {!! nl2br(e($customMessage)) !!}
                                </div>
                            </div>
                        @endif

                        {{-- CTA --}}
                        <div style="margin-top:20px; text-align:center;">
                            <a href="https://wedinwheels.com"
                               style="display:inline-block; padding:12px 22px; background:#ffc107; color:#111; text-decoration:none; border-radius:999px; font-weight:800; font-size:14px;">
                                Visit Website
                            </a>
                        </div>

                        <p style="margin:18px 0 0; color:#9aa4b2; font-size:12px; line-height:1.6; text-align:center;">
                            Need help? Reply to this email or contact us on WhatsApp.
                        </p>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="padding:18px 26px; background:#0f1724; border-top:1px solid #2a2f3b; text-align:center;">
                        <span style="color:#9aa4b2; font-size:12px;">
                            © {{ date('Y') }} Wedin Wheels. All rights reserved.
                        </span>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
