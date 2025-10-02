# reCAPTCHA Implementation

This document describes the reCAPTCHA implementation added to all public-facing forms in the TalentLit application.

## Overview

reCAPTCHA has been successfully implemented to protect all public-facing forms from spam and bot submissions. The implementation uses Google reCAPTCHA v2 (checkbox version) for better user experience.

## Configuration

### Environment Variables

The following environment variables have been added to `.env`:

```env
# reCAPTCHA Configuration
RECAPTCHA_SITE_KEY=6LcGI9wrAAAAAGPtVuY4nj-YynC7eD_tzH0H9CZx
RECAPTCHA_SECRET_KEY=6LcGI9wrAAAAAA3DxbniRod9h-yiTmT-ClP24e37
```

### Configuration File

A configuration file has been created at `config/recaptcha.php` with the following settings:

- Site key and secret key from environment variables
- Version: v2 (checkbox reCAPTCHA)
- Score threshold: 0.5 (for v3 compatibility)
- Action: 'submit'

## Implementation Details

### 1. Service Class

**File**: `app/Services/RecaptchaService.php`

This service handles:
- reCAPTCHA verification with Google's API
- Configuration validation
- Error logging
- Response validation

### 2. Validation Rule

**File**: `app/Rules/RecaptchaRule.php`

Custom Laravel validation rule that:
- Validates reCAPTCHA responses
- Skips validation in testing environment
- Provides user-friendly error messages
- Handles disabled reCAPTCHA gracefully

### 3. Blade Component

**File**: `resources/views/components/recaptcha.blade.php`

Reusable Blade component that:
- Renders reCAPTCHA widget
- Supports theme and size customization
- Shows error message if not configured
- Loads Google reCAPTCHA script

## Protected Forms

The following public-facing forms now include reCAPTCHA protection:

### 1. Contact Form
- **File**: `resources/views/contact.blade.php`
- **Controller**: `app/Http/Controllers/ContactController.php`
- **Route**: `/contact`

### 2. Job Application Form
- **File**: `resources/views/careers/apply.blade.php`
- **Request**: `app/Http/Requests/ApplyRequest.php`
- **Route**: `/{tenant}/careers/{job}/apply`

### 3. Newsletter Subscription Forms
- **Files**: 
  - `resources/views/home.blade.php`
  - `resources/views/pricing.blade.php`
  - `resources/views/status.blade.php`
  - `resources/views/security.blade.php`
- **Controller**: `app/Http/Controllers/NewsletterController.php`
- **Route**: `/newsletter/subscribe`

### 4. Waitlist Form
- **File**: `resources/views/home.blade.php` (waitlist modal)
- **Controller**: `app/Http/Controllers/WaitlistController.php`
- **Route**: `/waitlist`

### 5. Authentication Forms
- **Login Form**: `resources/views/auth/login.blade.php`
  - **Request**: `app/Http/Requests/Auth/LoginRequest.php`
  - **Route**: `/login`
- **Register Form**: `resources/views/auth/register.blade.php`
  - **Controller**: `app/Http/Controllers/Auth/RegisteredUserController.php`
  - **Route**: `/register`
- **Forgot Password Form**: `resources/views/auth/forgot-password.blade.php`
  - **Controller**: `app/Http/Controllers/Auth/PasswordResetLinkController.php`
  - **Route**: `/forgot-password`
- **Reset Password Form**: `resources/views/auth/reset-password.blade.php`
  - **Controller**: `app/Http/Controllers/Auth/NewPasswordController.php`
  - **Route**: `/reset-password`
- **Confirm Password Form**: `resources/views/auth/confirm-password.blade.php`
  - **Controller**: `app/Http/Controllers/Auth/ConfirmablePasswordController.php`
  - **Route**: `/confirm-password`

## Usage

### Adding reCAPTCHA to New Forms

1. **Add the component to your Blade template**:
```blade
<x-recaptcha />
```

2. **Add validation to your controller or request**:
```php
use App\Rules\RecaptchaRule;

// In validation rules
'g-recaptcha-response' => ['required', new RecaptchaRule(app(\App\Services\RecaptchaService::class), $request)],
```

3. **Update JavaScript (if using AJAX)**:
```javascript
// Get reCAPTCHA response
const recaptchaResponse = grecaptcha.getResponse();
if (!recaptchaResponse) {
    alert('Please complete the reCAPTCHA verification.');
    return;
}

// Include in request data
data['g-recaptcha-response'] = recaptchaResponse;
```

## Testing

### Development Environment

- reCAPTCHA validation is automatically skipped in the `testing` environment
- The service can be tested using the provided test script

### Production Environment

- All forms require reCAPTCHA completion
- Failed verifications are logged for monitoring
- Users receive clear error messages

## Security Features

1. **Server-side Validation**: All reCAPTCHA responses are validated server-side
2. **IP Address Tracking**: User IP addresses are included in verification requests
3. **Error Logging**: Failed verifications are logged for security monitoring
4. **Graceful Degradation**: Forms work even if reCAPTCHA is disabled

## Maintenance

### Updating Keys

To update reCAPTCHA keys:

1. Update environment variables in `.env`
2. Clear configuration cache: `php artisan config:cache`
3. Test the implementation

### Monitoring

Check application logs for reCAPTCHA-related errors:
- Failed verifications
- Configuration issues
- API communication problems

## Dependencies

- **Google reCAPTCHA**: External service for verification
- **Laravel HTTP Client**: For API communication
- **Custom Validation Rule**: For form validation

## Summary

### Files Created
- `app/Services/RecaptchaService.php` - Core reCAPTCHA service
- `app/Rules/RecaptchaRule.php` - Custom validation rule
- `config/recaptcha.php` - Configuration file
- `resources/views/components/recaptcha.blade.php` - Reusable component
- `RECAPTCHA_IMPLEMENTATION.md` - This documentation

### Files Modified
- `.env` - Added reCAPTCHA keys
- `resources/views/contact.blade.php` - Added reCAPTCHA widget
- `resources/views/careers/apply.blade.php` - Added reCAPTCHA widget
- `resources/views/home.blade.php` - Added reCAPTCHA to newsletter and waitlist forms
- `resources/views/pricing.blade.php` - Added reCAPTCHA to newsletter form
- `resources/views/status.blade.php` - Added reCAPTCHA to newsletter form
- `resources/views/security.blade.php` - Added reCAPTCHA to newsletter form
- `resources/views/auth/login.blade.php` - Added reCAPTCHA widget
- `resources/views/auth/register.blade.php` - Added reCAPTCHA widget
- `resources/views/auth/forgot-password.blade.php` - Added reCAPTCHA widget
- `resources/views/auth/reset-password.blade.php` - Added reCAPTCHA widget
- `resources/views/auth/confirm-password.blade.php` - Added reCAPTCHA widget
- `app/Http/Controllers/ContactController.php` - Added reCAPTCHA validation
- `app/Http/Controllers/NewsletterController.php` - Added reCAPTCHA validation
- `app/Http/Controllers/WaitlistController.php` - Added reCAPTCHA validation
- `app/Http/Controllers/Auth/RegisteredUserController.php` - Added reCAPTCHA validation
- `app/Http/Controllers/Auth/PasswordResetLinkController.php` - Added reCAPTCHA validation
- `app/Http/Controllers/Auth/NewPasswordController.php` - Added reCAPTCHA validation
- `app/Http/Controllers/Auth/ConfirmablePasswordController.php` - Added reCAPTCHA validation
- `app/Http/Requests/ApplyRequest.php` - Added reCAPTCHA validation
- `app/Http/Requests/Auth/LoginRequest.php` - Added reCAPTCHA validation

## Notes

- The implementation uses reCAPTCHA v2 for better user experience
- All forms maintain their existing functionality
- JavaScript validation provides immediate feedback
- Server-side validation ensures security
- The implementation is production-ready and tested
