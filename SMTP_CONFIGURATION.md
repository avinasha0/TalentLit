# SMTP Configuration for Contact Form

This document explains how to configure SMTP settings for the contact form functionality.

## Environment Variables

Add the following variables to your `.env` file:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="TalentLit Support"
```

## Popular SMTP Providers

### Gmail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="TalentLit Support"
```

**Note:** For Gmail, you need to:
1. Enable 2-factor authentication
2. Generate an App Password (not your regular password)
3. Use the App Password in `MAIL_PASSWORD`

### Outlook/Hotmail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@outlook.com
MAIL_FROM_NAME="TalentLit Support"
```

### SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-verified-sender@domain.com
MAIL_FROM_NAME="TalentLit Support"
```

### Mailgun
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your-mailgun-smtp-username
MAIL_PASSWORD=your-mailgun-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-verified-sender@domain.com
MAIL_FROM_NAME="TalentLit Support"
```

## Testing the Configuration

1. Update your `.env` file with the appropriate SMTP settings
2. Clear the config cache: `php artisan config:clear`
3. Test the contact form at `/contact`
4. Check the Laravel logs in `storage/logs/laravel.log` for any errors

## Troubleshooting

### Common Issues

1. **Authentication Failed**: Check your username and password
2. **Connection Timeout**: Verify the host and port settings
3. **SSL/TLS Issues**: Try different encryption settings (tls, ssl, or null)
4. **Gmail Issues**: Make sure you're using an App Password, not your regular password

### Debug Mode

To debug email issues, temporarily set:
```env
MAIL_MAILER=log
```

This will log emails to `storage/logs/laravel.log` instead of sending them.

## Security Notes

- Never commit your `.env` file to version control
- Use environment-specific credentials
- Consider using a dedicated email service for production
- Regularly rotate your email credentials

## Contact Form Features

The contact form includes:
- Form validation with error messages
- Success/error feedback to users
- Professional email template
- Responsive design matching the home page
- CSRF protection
- Rate limiting (can be added if needed)

## Files Created

- `app/Http/Controllers/ContactController.php` - Handles form submission
- `app/Mail/ContactMail.php` - Email template class
- `resources/views/contact.blade.php` - Contact page view
- `resources/views/emails/contact.blade.php` - Email template
- Routes added to `routes/web.php`
