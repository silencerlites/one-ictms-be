
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body style="font-family:Arial,sans-serif;background:#f4f6f9;padding:30px;">
    <div style="max-width:560px;margin:auto;background:#ffffff;padding:32px;border-radius:10px;">
        <h2 style="color:#1f2937;">Verify Your Email</h2>

        <p>Hello,</p>

        <p>Thank you for registering. Please use the verification code below to verify your email address.</p>

        <div style="text-align:center;margin:30px 0;">
            <strong style="font-size:32px;letter-spacing:8px;color:#2563eb;">
                {{ $otp }}
            </strong>
        </div>

        <p>This verification code will expire in <strong>10 minutes</strong>.</p>

        <p>If you did not register an account, please ignore this email.</p>

        <hr style="border:0;border-top:1px solid #e5e7eb;margin:24px 0;">

        <p style="font-size:12px;color:#6b7280;">
            {{ config('app.name') }} — Email verification
        </p>
    </div>
</body>
</html>
