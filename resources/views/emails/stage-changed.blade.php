<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .content { padding: 20px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; }
        .stage-info { background: #e8f5e8; padding: 15px; border-radius: 4px; margin: 15px 0; border-left: 4px solid #28a745; }
        .message-box { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 15px 0; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Status Update</h1>
            <p>Your application status has been updated at {{ $tenant->name }}!</p>
        </div>

        <div class="content">
            <p>Dear {{ $candidate->first_name }} {{ $candidate->last_name }},</p>

            <p>We wanted to inform you that your application for the position of <strong>{{ $job->title }}</strong> has been moved to a new stage in our recruitment process.</p>

            <div class="stage-info">
                <h3>Current Status</h3>
                <p><strong>Stage:</strong> {{ $stage->name }}</p>
                <p><strong>Position:</strong> {{ $job->title }}</p>
                <p><strong>Company:</strong> {{ $tenant->name }}</p>
                <p><strong>Updated On:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
            </div>

            @if($message)
            <div class="message-box">
                <h3>Message from the Recruitment Team</h3>
                <p>{{ $message }}</p>
            </div>
            @endif

            <h3>What's Next?</h3>
            <p>Our recruitment team will continue to review your application and will be in touch with you regarding the next steps in the process.</p>

            <p>If you have any questions about your application or this update, please don't hesitate to contact our HR department.</p>

            <p>Thank you for your continued interest in {{ $tenant->name }}!</p>

            <p>Best regards,<br>
            The {{ $tenant->name }} Recruitment Team</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>If you have any questions, please contact our HR department.</p>
        </div>
    </div>
</body>
</html>
