<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documents requested</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #fffbeb; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #d97706; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0 0 8px;">Pre-onboarding documents</h1>
            <p style="margin:0;">{{ $tenant->name }} needs a few documents from you before you join.</p>
        </div>

        <p>Dear {{ $application->candidate->first_name }} {{ $application->candidate->last_name }},</p>

        <p>Please sign in to your applicant portal and upload the required items for <strong>{{ $application->jobOpening->title ?? 'your application' }}</strong>. You will see a checklist with status for each document.</p>

        <p><a href="{{ $portalUrl }}">{{ $portalUrl }}</a></p>

        <p style="font-size:13px;color:#64748b;">If you have questions, reply to your hiring contact or reach out to {{ $tenant->name }}.</p>

        <div class="footer">
            <p>This message was sent by {{ $tenant->name }}.</p>
        </div>
    </div>
</body>
</html>
