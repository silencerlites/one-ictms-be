
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Verification</title>
</head>
<body style="font-family:Arial,sans-serif;background:#f4f6f9;padding:30px;">
    <div style="max-width:560px;margin:auto;background:#ffffff;padding:32px;border-radius:10px;">
        <h2 style="color:#1f2937;">Login Verification</h2>

        <p>Hello,</p>

        <p>Use the following one-time password to complete your login.</p>

        <div style="text-align:center;margin:30px 0;">
            <strong style="font-size:32px;letter-spacing:8px;color:#2563eb;">
                {{ $otp }}
            </strong>
        </div>

        <p>This OTP will expire in <strong>5 minutes</strong>.</p>

        <p>If you did not request this login, you can ignore this email.</p>

        <hr style="border:0;border-top:1px solid #e5e7eb;margin:24px 0;">

        <p style="font-size:12px;color:#6b7280;">
            {{ config('app.name') }} — Automated security notification
        </p>
    </div>
</body>
</html>
