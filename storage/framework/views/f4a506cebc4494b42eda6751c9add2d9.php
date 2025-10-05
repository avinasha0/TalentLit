<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - TalentLit</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        .otp-container {
            background: #f1f5f9;
            border: 2px dashed #3b82f6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #3b82f6;
            letter-spacing: 8px;
            margin: 10px 0;
        }
        .otp-label {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .expiry-notice {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #92400e;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">TalentLit</div>
            <h1>Verify Your Email Address</h1>
        </div>

        <p>Hello!</p>
        
        <p>Thank you for registering with TalentLit. To complete your registration and activate your account, please verify your email address using the OTP code below:</p>

        <div class="otp-container">
            <div class="otp-label">Your verification code is:</div>
            <div class="otp-code"><?php echo e($otp); ?></div>
        </div>

        <div class="expiry-notice">
            <strong>Important:</strong> This code will expire in 15 minutes for security reasons.
        </div>

        <p>If you didn't create an account with TalentLit, please ignore this email.</p>

        <p>Once verified, you'll have access to:</p>
        <ul>
            <li>Create and manage job postings</li>
            <li>Track candidates through your hiring pipeline</li>
            <li>Schedule and manage interviews</li>
            <li>Access detailed analytics and reporting</li>
        </ul>

        <div class="footer">
            <p>This email was sent to <?php echo e($email); ?></p>
            <p>If you have any questions, please contact our support team.</p>
            <p>&copy; <?php echo e(date('Y')); ?> TalentLit. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\emails\email-verification-otp.blade.php ENDPATH**/ ?>