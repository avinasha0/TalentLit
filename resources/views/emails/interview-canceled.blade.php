<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interview Canceled</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9fafb; padding: 30px; }
        .interview-details { background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { margin: 10px 0; }
        .label { font-weight: bold; color: #374151; }
        .value { color: #6b7280; }
        .footer { text-align: center; color: #6b7280; font-size: 14px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Interview Canceled</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $candidate->first_name }},</p>
            
            <p>We regret to inform you that the following interview has been canceled:</p>
            
            <div class="interview-details">
                <h2>Canceled Interview Details</h2>
                
                <div class="detail-row">
                    <span class="label">Position:</span>
                    <span class="value">{{ $job ? $job->title : 'General Interview' }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Scheduled Date & Time:</span>
                    <span class="value">{{ $interview->scheduled_at->format('l, F j, Y \a\t g:i A') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Duration:</span>
                    <span class="value">{{ $interview->duration_minutes }} minutes</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Mode:</span>
                    <span class="value">{{ ucfirst($interview->mode) }}</span>
                </div>
                
                @if($interview->location)
                    <div class="detail-row">
                        <span class="label">Location:</span>
                        <span class="value">{{ $interview->location }}</span>
                    </div>
                @endif
                
                @if($panelists->count() > 0)
                    <div class="detail-row">
                        <span class="label">Interview Panel:</span>
                        <span class="value">{{ $panelists->pluck('name')->join(', ') }}</span>
                    </div>
                @endif
            </div>
            
            <p>We apologize for any inconvenience this may cause. We will be in touch soon to reschedule at a more convenient time.</p>
            
            <p>If you have any questions or would like to discuss alternative arrangements, please don't hesitate to contact us.</p>
            
            <p>Thank you for your understanding.</p>
            
            <p>Best regards,<br>
            The Hiring Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
