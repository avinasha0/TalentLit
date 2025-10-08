<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Your Account</title>
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
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .activation-button {
            display: inline-block;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .activation-button:hover {
            transform: translateY(-2px);
        }
        .alternative-link {
            margin-top: 20px;
            padding: 20px;
            background-color: #f3f4f6;
            border-radius: 8px;
            font-size: 14px;
            color: #6b7280;
        }
        .alternative-link a {
            color: #4f46e5;
            word-break: break-all;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .security-note {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            color: #92400e;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">TalentLit</div>
            <h1 class="title">Activate Your Account</h1>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $user['name'] }}</strong>,</p>
            
            <p>Thank you for registering with TalentLit! To complete your account setup, please activate your email address by clicking the button below:</p>

            <div style="text-align: center;">
                <a href="{{ $activationUrl }}" class="activation-button">
                    Activate My Account
                </a>
            </div>

            <p>Once activated, you'll be able to:</p>
            <ul>
                <li>Access your TalentLit dashboard</li>
                <li>Create and manage job postings</li>
                <li>Track candidates and applications</li>
                <li>Use all our recruitment features</li>
            </ul>

            <div class="security-note">
                <strong>Security Note:</strong> This activation link will expire in 24 hours for your security. If you didn't create an account with TalentLit, please ignore this email.
            </div>

            <div class="alternative-link">
                <p><strong>Button not working?</strong> Copy and paste this link into your browser:</p>
                <a href="{{ $activationUrl }}">{{ $activationUrl }}</a>
            </div>
        </div>

        <div class="footer">
            <p>This email was sent to {{ $user['email'] }}</p>
            <p>If you have any questions, please contact our support team.</p>
            <p>&copy; {{ date('Y') }} TalentLit. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
