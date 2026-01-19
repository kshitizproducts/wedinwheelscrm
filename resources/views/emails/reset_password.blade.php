<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
</head>
<body style="margin:0;background:#0b0b0b;font-family:Arial,sans-serif;color:#fff;">

  <table width="100%" style="padding:30px 0;">
    <tr>
      <td align="center">
        <table width="620" cellpadding="0" cellspacing="0"
          style="background:#141414;border:1px solid #2b2b2b;border-radius:14px;overflow:hidden;">
          
          <tr>
            <td style="padding:22px 26px;background:linear-gradient(90deg,#0f0f0f,#1f1f1f);border-bottom:1px solid #2b2b2b;">
              <h1 style="margin:0;color:#fff;font-size:22px;">WedinWheels</h1>
              <p style="margin:6px 0 0;color:#bdbdbd;font-size:13px;">Password Reset Request</p>
            </td>
          </tr>

          <tr>
            <td style="padding:26px;">
              <p style="margin:0 0 10px;color:#eaeaea;font-size:15px;">
                Hi <b style="color:#fff;">{{ $user->name ?? 'User' }}</b>,
              </p>

              <p style="margin:0 0 16px;color:#cfcfcf;font-size:14px;line-height:1.7;">
                We received a request to reset your password. Click the button below to set a new password.
              </p>

              <div style="text-align:center;margin:22px 0;">
                <a href="{{ $resetUrl }}"
                  style="display:inline-block;padding:14px 26px;border-radius:10px;background:#2f2f2f;color:#fff;
                  text-decoration:none;font-size:14px;font-weight:bold;border:1px solid #3d3d3d;">
                  ✅ Reset Password
                </a>
              </div>

              <p style="margin:0;font-size:12px;color:#9a9a9a;line-height:1.7;">
                If the button does not work, copy & paste this link:
                <br>
                <a href="{{ $resetUrl }}" style="color:#bdbdbd;text-decoration:underline;">{{ $resetUrl }}</a>
              </p>

              <p style="margin:18px 0 0;font-size:12px;color:#6e6e6e;">
                This link will expire in 30 minutes.
              </p>
            </td>
          </tr>

          <tr>
            <td style="padding:18px 26px;background:#0f0f0f;border-top:1px solid #2b2b2b;">
              <p style="margin:0;font-size:12px;color:#9a9a9a;">
                © {{ date('Y') }} WedinWheels. All rights reserved.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
