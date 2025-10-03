<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Received</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .content { padding: 20px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #666; }
        .button { display: inline-block; background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Received</h1>
            <p>Thank you for your interest in joining <?php echo e($tenant->name); ?>!</p>
        </div>

        <div class="content">
            <p>Dear <?php echo e($candidate->first_name); ?> <?php echo e($candidate->last_name); ?>,</p>

            <p>We have successfully received your application for the position of <strong><?php echo e($job->title); ?></strong> at <?php echo e($tenant->name); ?>.</p>

            <h3>Application Details:</h3>
            <ul>
                <li><strong>Position:</strong> <?php echo e($job->title); ?></li>
                <li><strong>Department:</strong> <?php echo e($job->department->name); ?></li>
                <li><strong>Location:</strong> <?php echo e($job->location->name); ?></li>
                <li><strong>Employment Type:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $job->employment_type))); ?></li>
                <li><strong>Application ID:</strong> <?php echo e($application->id); ?></li>
                <li><strong>Applied On:</strong> <?php echo e($application->applied_at->format('F j, Y \a\t g:i A')); ?></li>
            </ul>

            <h3>What's Next?</h3>
            <p>Our recruitment team will review your application and get back to you within 5-7 business days. If you have any questions, please don't hesitate to contact us.</p>

            <p>Thank you again for your interest in <?php echo e($tenant->name); ?>!</p>

            <p>Best regards,<br>
            The <?php echo e($tenant->name); ?> Recruitment Team</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>If you have any questions, please contact our HR department.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\emails\application-received.blade.php ENDPATH**/ ?>