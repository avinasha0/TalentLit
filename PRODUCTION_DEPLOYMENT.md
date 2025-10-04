# Production Deployment Fix for 403 Forbidden Error

## Problem
Getting "403 Forbidden - Access to this resource on the server is denied!" error on production.

## Root Cause
The main issues causing the 403 error are:
1. Missing root `.htaccess` file to redirect requests to the `public` folder
2. Incorrect `APP_URL` in environment configuration
3. Potential file permission issues

## Solution

### 1. Root .htaccess File (Already Created)
A `.htaccess` file has been created in the root directory with the following content:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect all requests to the public directory
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ /public/$1 [L,QSA]
</IfModule>
```

### 2. Environment Configuration
Update your `.env` file on the production server with these changes:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://talentlit.com
LOG_LEVEL=error
```

### 3. File Permissions (Windows Server)
Run these commands on your Windows server:
```cmd
icacls "storage" /grant "Everyone:(OI)(CI)F"
icacls "bootstrap\cache" /grant "Everyone:(OI)(CI)F"
```

### 4. Laravel Optimization
Run these commands on your production server:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Automated Fix Script
Run the automated fix script:
```bash
php fix_production.php
```

## Verification Steps
1. Check that your domain resolves to the correct directory
2. Verify that `public/index.php` is accessible
3. Test the main application URL: `https://talentlit.com`
4. Check Laravel logs in `storage/logs/` for any errors

## Important Notes
- The `.htaccess` file in the root directory will redirect all requests to the `public` folder
- This setup ensures that Laravel's public folder is the document root
- File permissions have been set to allow web server access
- Environment is configured for production with debug mode disabled

## Troubleshooting
If you still get 403 errors:
1. Check web server error logs
2. Verify mod_rewrite is enabled on your server
3. Ensure the web server has read access to all files
4. Check that the document root points to the Laravel project directory (not the public folder)

## Security Considerations
- Never commit `.env` files to version control
- Ensure `APP_DEBUG=false` in production
- Use HTTPS in production (`APP_URL=https://talentlit.com`)
- Regularly update dependencies and Laravel framework
