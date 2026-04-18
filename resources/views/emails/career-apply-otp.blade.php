<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application verification code</title>
</head>
<body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #111827; max-width: 560px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 22px;">Verify your email to apply</h1>
    <p>You requested a verification code to submit an application for <strong>{{ $job->title }}</strong> at <strong>{{ $tenant->name }}</strong>.</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p style="font-size: 28px; letter-spacing: 0.25em; font-weight: 700; color: #4f46e5;">{{ $otp }}</p>
    <p style="font-size: 14px; color: #4b5563;">This code expires in {{ $expiresMinutes }} minutes. If you did not request this, you can ignore this message.</p>
    <p style="margin-top: 32px; font-size: 14px; color: #6b7280;">Thanks,<br>{{ $tenant->name }}</p>
</body>
</html>
