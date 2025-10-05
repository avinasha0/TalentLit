<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interview Updated</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f59e0b; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9fafb; padding: 30px; }
        .interview-details { background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { margin: 10px 0; }
        .label { font-weight: bold; color: #374151; }
        .value { color: #6b7280; }
        .button { display: inline-block; background-color: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 10px 0; }
        .footer { text-align: center; color: #6b7280; font-size: 14px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Interview Updated</h1>
        </div>
        
        <div class="content">
            <p>Hello <?php echo e($candidate->first_name); ?>,</p>
            
            <p>We wanted to inform you that your interview details have been updated. Here are the current details:</p>
            
            <div class="interview-details">
                <h2>Updated Interview Details</h2>
                
                <div class="detail-row">
                    <span class="label">Position:</span>
                    <span class="value"><?php echo e($job ? $job->title : 'General Interview'); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Date & Time:</span>
                    <span class="value"><?php echo e($interview->scheduled_at->format('l, F j, Y \a\t g:i A')); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Duration:</span>
                    <span class="value"><?php echo e($interview->duration_minutes); ?> minutes</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Mode:</span>
                    <span class="value"><?php echo e(ucfirst($interview->mode)); ?></span>
                </div>
                
                <?php if($interview->location): ?>
                    <div class="detail-row">
                        <span class="label">Location:</span>
                        <span class="value"><?php echo e($interview->location); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if($interview->meeting_link): ?>
                    <div class="detail-row">
                        <span class="label">Meeting Link:</span>
                        <span class="value">
                            <a href="<?php echo e($interview->meeting_link); ?>" style="color: #f59e0b;"><?php echo e($interview->meeting_link); ?></a>
                        </span>
                    </div>
                <?php endif; ?>
                
                <?php if($panelists->count() > 0): ?>
                    <div class="detail-row">
                        <span class="label">Interview Panel:</span>
                        <span class="value"><?php echo e($panelists->pluck('name')->join(', ')); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if($interview->notes): ?>
                    <div class="detail-row">
                        <span class="label">Additional Notes:</span>
                        <span class="value"><?php echo e($interview->notes); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if($interview->meeting_link): ?>
                <div style="text-align: center;">
                    <a href="<?php echo e($interview->meeting_link); ?>" class="button">Join Meeting</a>
                </div>
            <?php endif; ?>
            
            <p>Please note the updated details and make sure to:</p>
            <ul>
                <li>Update your calendar with the new time</li>
                <li>Arrive 5-10 minutes early</li>
                <li>Bring a copy of your resume</li>
                <li>Prepare any questions you may have about the role</li>
                <?php if($interview->mode === 'remote'): ?>
                    <li>Test your internet connection and camera/microphone beforehand</li>
                <?php endif; ?>
            </ul>
            
            <p>If you have any questions about these changes, please contact us as soon as possible.</p>
            
            <p>We apologize for any inconvenience and look forward to meeting with you!</p>
            
            <p>Best regards,<br>
            The Hiring Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\emails\interview-updated.blade.php ENDPATH**/ ?>