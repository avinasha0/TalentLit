<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team Invitation - <?php echo e($tenant->name); ?></title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: white;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        .button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-radius: 0 0 8px 8px;
        }
        .role-badge {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to <?php echo e($tenant->name); ?>!</h1>
        <p>You've been invited to join our team</p>
    </div>

    <div class="content">
        <h2>Hello <?php echo e($user->name); ?>,</h2>
        
        <p>You've been invited to join <strong><?php echo e($tenant->name); ?></strong> as a <span class="role-badge"><?php echo e($roleName); ?></span>.</p>
        
        <p>As a team member, you'll have access to our hiring platform where you can:</p>
        
        <ul>
            <?php if($roleName === 'Owner' || $roleName === 'Admin' || $roleName === 'Recruiter'): ?>
                <li>Create and manage job postings</li>
                <li>View and manage candidates</li>
                <li>Schedule and conduct interviews</li>
            <?php endif; ?>
            <?php if($roleName === 'Owner' || $roleName === 'Admin'): ?>
                <li>Access analytics and reports</li>
                <li>Manage team members and settings</li>
            <?php endif; ?>
            <?php if($roleName === 'Hiring Manager'): ?>
                <li>View job postings and candidates</li>
                <li>Participate in interviews</li>
            <?php endif; ?>
        </ul>

        <?php if($invitationToken): ?>
            <p>To get started, please click the button below to accept your invitation and set up your password:</p>
            
            <div style="background: #f3f4f6; padding: 15px; border-radius: 6px; margin: 20px 0;">
                <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
                <p><strong>Role:</strong> <?php echo e($roleName); ?></p>
            </div>

            <p><strong>Important:</strong> You'll be prompted to set a secure password when you accept the invitation.</p>

            <div style="text-align: center;">
                <a href="<?php echo e($invitationUrl); ?>" class="button">Accept Invitation & Set Password</a>
            </div>
        <?php else: ?>
            <p>To get started, please log in to your account using the credentials that were created for you:</p>
            
            <div style="background: #f3f4f6; padding: 15px; border-radius: 6px; margin: 20px 0;">
                <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
                <p><strong>Login URL:</strong> <a href="<?php echo e($loginUrl); ?>"><?php echo e($loginUrl); ?></a></p>
            </div>

            <p><strong>Important:</strong> You'll be prompted to set a new password on your first login for security purposes.</p>

            <div style="text-align: center;">
                <a href="<?php echo e($loginUrl); ?>" class="button">Get Started</a>
            </div>
        <?php endif; ?>

        <p>If you have any questions or need assistance, please don't hesitate to reach out to your team administrator.</p>

        <p>Welcome aboard!</p>
        <p>The <?php echo e($tenant->name); ?> Team</p>
    </div>

    <div class="footer">
        <p>This invitation was sent to <?php echo e($user->email); ?></p>
        <p>If you didn't expect this invitation, please ignore this email.</p>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/emails/team-invitation.blade.php ENDPATH**/ ?>