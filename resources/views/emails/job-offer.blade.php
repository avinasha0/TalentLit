<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job offer</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f0fdfa; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #0d9488; }
        .btn { display: inline-block; padding: 12px 20px; margin: 8px 8px 8px 0; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 14px; }
        .btn-accept { background: #0d9488; color: #fff !important; }
        .btn-reject { background: #f1f5f9; color: #334155 !important; border: 1px solid #cbd5e1; }
        .btn-discuss { background: #2563eb; color: #fff !important; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0 0 8px;">Congratulations — you have an offer</h1>
            <p style="margin:0;">{{ $tenant->name }} has extended an offer regarding your application.</p>
        </div>

        <p>Dear {{ $application->candidate->first_name }} {{ $application->candidate->last_name }},</p>

        <p>Your application for <strong>{{ $application->jobOpening->title ?? 'the role' }}</strong> has reached the <strong>Offer</strong> stage. Please let us know how you would like to proceed.</p>

        <p style="margin-top:24px;"><strong>Your response</strong> (choose one):</p>
        <p>
            <a class="btn btn-accept" href="{{ $acceptUrl }}" target="_blank" rel="noopener">Accept offer</a>
            <a class="btn btn-reject" href="{{ $rejectUrl }}" target="_blank" rel="noopener">Decline offer</a>
            <a class="btn btn-discuss" href="{{ $discussionUrl }}" target="_blank" rel="noopener">Request discussion</a>
        </p>

        <p style="font-size:14px;color:#64748b;">You can also sign in to your applicant portal and respond from <strong>My applications</strong>:</p>
        <p><a href="{{ $portalUrl }}">{{ $portalUrl }}</a></p>

        <p style="font-size:13px;color:#64748b;">These links are personal to you and expire after some time. If they stop working, open the portal link above.</p>

        <div class="footer">
            <p>This message was sent by {{ $tenant->name }}.</p>
        </div>
    </div>
</body>
</html>
