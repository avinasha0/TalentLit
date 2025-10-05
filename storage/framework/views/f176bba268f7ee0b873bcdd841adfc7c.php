<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - TalentLit</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        .otp-code {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            letter-spacing: 8px;
            margin: 30px 0;
            font-family: 'Courier New', monospace;
        }
        .instructions {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">TL</div>
            <h1 style="margin: 0; color: #1e293b;">Verify Your Email Address</h1>
            <p style="color: #64748b; margin: 10px 0 0;">Complete your waitlist registration</p>
        </div>

        <p>Hello!</p>
        
        <p>Thank you for joining the TalentLit waitlist! To complete your registration, please verify your email address using the OTP code below:</p>

        <div class="otp-code"><?php echo e($otp); ?></div>

        <div class="instructions">
            <h3 style="margin-top: 0; color: #1e293b;">Instructions:</h3>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Enter this 6-digit code in the verification form</li>
                <li>The code will expire in 10 minutes</li>
                <li>If you didn't request this, please ignore this email</li>
            </ul>
        </div>

        <p><strong>Why verify your email?</strong></p>
        <ul>
            <li>Ensure we can contact you when Pro features are available</li>
            <li>Prevent spam and maintain a quality waitlist</li>
            <li>Get priority access to new features and updates</li>
        </ul>

        <div class="footer">
            <p>This email was sent to <strong><?php echo e($email); ?></strong></p>
            <p>If you have any questions, feel free to contact our support team.</p>
            <p style="margin-top: 20px;">
                <strong>TalentLit Team</strong><br>
                <a href="mailto:support@talentlit.com" style="color: #667eea;">support@talentlit.com</a>
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\emails\waitlist-otp.blade.php ENDPATH**/ ?>