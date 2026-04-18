<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant portal login</title>
</head>
<body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #111827; max-width: 560px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 22px;">{{ !empty($isReturningApplicant) ? 'Your applicant portal password was updated' : 'Your applicant account is ready' }}</h1>
    @if(!empty($isReturningApplicant))
        <p>Thank you for applying again with <strong>{{ $tenant->name }}</strong>. A <strong>new temporary sign-in password</strong> was generated automatically for your existing applicant account. Use it below to sign in (any previous applicant password for this account no longer works).</p>
    @else
        <p>You can sign in to track your applications with <strong>{{ $tenant->name }}</strong>.</p>
    @endif
    <p><strong>Login email:</strong> {{ $loginEmail }}</p>
    <p><strong>Temporary password (auto-generated):</strong> {{ $plainPassword }}</p>
    <p style="font-size: 14px; color: #4b5563;">This password was created automatically for your account. For your security, sign in and change it from your account settings as soon as that option is available.</p>
    <p style="margin-top: 28px;">
        <a href="{{ $loginUrl }}" style="display: inline-block; background: #4f46e5; color: #ffffff; text-decoration: none; padding: 12px 20px; border-radius: 8px; font-weight: 600;">Sign in</a>
    </p>
    <p style="margin-top: 24px; font-size: 14px;">
        After signing in you will be taken to your applicant dashboard:<br>
        <a href="{{ $portalUrl }}">{{ $portalUrl }}</a>
    </p>
    <p style="margin-top: 32px; font-size: 14px; color: #6b7280;">Thanks,<br>{{ $tenant->name }}</p>
</body>
</html>
