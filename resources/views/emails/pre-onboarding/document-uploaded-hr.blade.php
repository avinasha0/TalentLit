<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document uploaded</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #eff6ff; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2563eb; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0 0 8px;">Pre-onboarding upload</h1>
            <p style="margin:0;">A candidate uploaded or replaced a document.</p>
        </div>

        <p><strong>Candidate:</strong> {{ $application->candidate->full_name ?? 'Unknown' }}</p>
        <p><strong>Role:</strong> {{ $application->jobOpening->title ?? '—' }}</p>
        <p><strong>Document:</strong> {{ $document->title }}</p>

        <p><a href="{{ $candidateProfileUrl }}">Open candidate profile</a></p>

        <div class="footer">
            <p>{{ $tenant->name }}</p>
        </div>
    </div>
</body>
</html>
