<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Newsletter Subscription</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .otp-box {
            background-color: #f7fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            letter-spacing: 8px;
            margin: 0;
            font-family: 'Courier New', monospace;
        }
        .instructions {
            background-color: #edf2f7;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .footer {
            background-color: #f7fafc;
            padding: 30px;
            text-align: center;
            color: #718096;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .expiry-notice {
            background-color: #fef5e7;
            border: 1px solid #f6e05e;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #744210;
        }
        .security-note {
            background-color: #e6fffa;
            border: 1px solid #4fd1c7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #234e52;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Your Subscription</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">TalentLit Newsletter</p>
        </div>
        
        <div class="content">
            <h2 style="color: #2d3748; margin-top: 0;">Welcome to TalentLit!</h2>
            
            <p>Thank you for subscribing to our newsletter! To complete your subscription and start receiving our latest updates, please verify your email address using the OTP code below.</p>
            
            <div class="otp-box">
                <p style="margin: 0 0 15px 0; color: #4a5568; font-weight: 600;">Your verification code is:</p>
                <div class="otp-code">{{ $otp }}</div>
            </div>
            
            <div class="instructions">
                <h3 style="margin-top: 0; color: #2d3748;">How to verify:</h3>
                <ol style="margin: 10px 0; padding-left: 20px;">
                    <li>Copy the 6-digit code above</li>
                    <li>Return to the website where you subscribed</li>
                    <li>Enter the code in the verification field</li>
                    <li>Click "Verify" to complete your subscription</li>
                </ol>
            </div>
            
            <div class="expiry-notice">
                <strong>⏰ Important:</strong> This verification code will expire in 10 minutes for security reasons.
            </div>
            
            <div class="security-note">
                <strong>🔒 Security Note:</strong> If you didn't request this newsletter subscription, please ignore this email. No further action is required.
            </div>
            
            <p>Once verified, you'll receive:</p>
            <ul>
                <li>Latest recruitment trends and insights</li>
                <li>Product updates and new features</li>
                <li>Best practices for hiring and talent acquisition</li>
                <li>Exclusive content and resources</li>
            </ul>
            
            <p style="margin-bottom: 0;">If you have any questions or need assistance, feel free to contact our support team.</p>
        </div>
        
        <div class="footer">
            <p style="margin: 0 0 10px 0;"><strong>TalentLit</strong> - Modern ATS for Smart Hiring</p>
            <p style="margin: 0; font-size: 12px;">
                This email was sent to {{ $email }}. If you have any questions, please contact us at 
                <a href="mailto:support@talentlit.com" style="color: #667eea;">support@talentlit.com</a>
            </p>
            <p style="margin: 10px 0 0 0; font-size: 12px;">
                © {{ date('Y') }} TalentLit. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
