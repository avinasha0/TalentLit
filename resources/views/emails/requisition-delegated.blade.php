<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Approval Request: Requisition #{{ $requisition->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dbeafe; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .content { padding: 20px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; }
        .button { display: inline-block; background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin: 10px 0; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Approval Delegated to You</h1>
            <p>You have been delegated to review a requisition.</p>
        </div>

        <div class="content">
            <div class="info-box">
                <h3>Requisition Details</h3>
                <p><strong>ID:</strong> {{ $requisition->id }}</p>
                <p><strong>Title:</strong> {{ $requisition->job_title }}</p>
                <p><strong>Department:</strong> {{ $requisition->department ?? 'N/A' }}</p>
                <p><strong>Budget:</strong> ${{ number_format($requisition->budget_min) }} - ${{ number_format($requisition->budget_max) }}</p>
                @if($delegator)
                    <p><strong>Delegated by:</strong> {{ $delegator->name }}</p>
                @endif
            </div>

            <p><a href="{{ $approvalLink }}" class="button">Review Requisition</a></p>

            <p>Please review this requisition and take appropriate action (Approve, Reject, Request Changes, or Delegate).</p>
        </div>

        <div class="footer">
            <p>This is an automated notification from {{ $tenant->name }}.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

