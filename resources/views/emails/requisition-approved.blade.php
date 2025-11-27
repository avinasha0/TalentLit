<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Requisition Approved: #{{ $requisition->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #d1fae5; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .content { padding: 20px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; }
        .button { display: inline-block; background: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin: 10px 0; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Requisition Approved</h1>
            <p>Your requisition has been approved!</p>
        </div>

        <div class="content">
            <div class="info-box">
                <h3>Requisition Details</h3>
                <p><strong>ID:</strong> {{ $requisition->id }}</p>
                <p><strong>Title:</strong> {{ $requisition->job_title }}</p>
                @if($approver)
                    <p><strong>Approved by:</strong> {{ $approver->name }}</p>
                @endif
                <p><strong>Approved at:</strong> {{ $requisition->approved_at ? $requisition->approved_at->format('F j, Y \a\t g:i A') : now()->format('F j, Y \a\t g:i A') }}</p>
            </div>

            <p><a href="{{ $viewLink }}" class="button">View Requisition</a></p>

            <p>Your requisition has been successfully approved and is ready for the next steps.</p>
        </div>

        <div class="footer">
            <p>This is an automated notification from {{ $tenant->name }}.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

