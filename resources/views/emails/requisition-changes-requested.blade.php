<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Changes Requested: Requisition #{{ $requisition->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #fed7aa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .content { padding: 20px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; }
        .button { display: inline-block; background: #f97316; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin: 10px 0; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .comments-box { background: #fff7ed; padding: 15px; border-left: 4px solid #f97316; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Changes Requested</h1>
            <p>Changes have been requested for your requisition.</p>
        </div>

        <div class="content">
            <div class="info-box">
                <h3>Requisition Details</h3>
                <p><strong>ID:</strong> {{ $requisition->id }}</p>
                <p><strong>Title:</strong> {{ $requisition->job_title }}</p>
            </div>

            <div class="comments-box">
                <h3>Requested Changes</h3>
                <p>{{ $comments }}</p>
            </div>

            <p><a href="{{ $editLink }}" class="button">Edit & Resubmit</a></p>

            <p>Please review the requested changes above, update your requisition, and resubmit for approval.</p>
        </div>

        <div class="footer">
            <p>This is an automated notification from {{ $tenant->name }}.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

