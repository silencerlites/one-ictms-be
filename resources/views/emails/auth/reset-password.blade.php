
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="font-family:Arial,sans-serif;background:#f4f6f9;padding:30px;">
    <div style="max-width:560px;margin:auto;background:#ffffff;padding:32px;border-radius:10px;">
        <h2 style="color:#1f2937;">Reset Your Password</h2>

        <p>Hello,</p>

        <p>We received a request to reset your account password.</p>

        <p style="text-align:center;margin:30px 0;">
            <a href="{{ $resetUrl }}"
               style="background:#2563eb;color:#ffffff;padding:14px 24px;text-decoration:none;border-radius:6px;display:inline-block;">
                Reset Password
            </a>
        </p>

        <p>If the button does not work, copy and paste the following URL into your browser:</p>

        <p style="word-break:break-all;">
            <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
        </p>

        <p>If you did not request a password reset, you can ignore this email.</p>

        <hr style="border:0;border-top:1px solid #e5e7eb;margin:24px 0;">

        <p style="font-size:12px;color:#6b7280;">
            {{ config('app.name') }} — Password reset notification
        </p>
    </div>
</body>
</html>
