# Fix: "Localhost is not in the list of supported domains"

## Quick Fix

You're seeing this error because `localhost` is not registered in your Google reCAPTCHA console. Here are two solutions:

## Solution 1: Register localhost in Google Console (Recommended for Testing)

### Steps:

1. **Go to Google reCAPTCHA Admin Console**
   - Visit: https://www.google.com/recaptcha/admin
   - Sign in with your Google account

2. **Select Your reCAPTCHA Site**
   - Click on your existing reCAPTCHA site
   - Or create a new one specifically for development

3. **Add localhost Domains**
   - Click on "Settings" or edit your site
   - Scroll to "Domains" section
   - Click "Add Domain"
   - Add these domains **one by one**:
     ```
     localhost
     127.0.0.1
     ```
   - **Important:** You must add both separately
   - Click "Submit" or "Save"

4. **Wait a Few Minutes**
   - Changes may take 2-5 minutes to propagate

5. **Test Again**
   - Visit: `https://newsubdomain.localhost/login`
   - reCAPTCHA should work now!

## Solution 2: Skip reCAPTCHA for Localhost in Development (Easier)

If you don't want to register localhost in Google console, you can skip validation for localhost subdomains in development:

### Steps:

1. **Update `.env` file:**
   ```env
   APP_ENV=local
   RECAPTCHA_SKIP_LOCALHOST_IN_DEV=true
   ```

2. **Clear config cache:**
   ```bash
   php artisan config:clear
   ```

3. **Test Again**
   - Visit: `https://newsubdomain.localhost/login`
   - reCAPTCHA validation will be skipped automatically
   - Forms will work without reCAPTCHA on localhost subdomains

## Which Solution to Use?

### Use Solution 1 (Register localhost) if:
- ✅ You want to test reCAPTCHA functionality locally
- ✅ You want to ensure reCAPTCHA works exactly like production
- ✅ You're testing subdomain functionality

### Use Solution 2 (Skip in dev) if:
- ✅ You just want to develop without reCAPTCHA blocking you
- ✅ You don't need to test reCAPTCHA locally
- ✅ You want faster development workflow

## Current Configuration

Check your `.env` file:

```env
# Current setting (default: true)
RECAPTCHA_SKIP_LOCALHOST_IN_DEV=true

# To require reCAPTCHA even on localhost, set to false
# RECAPTCHA_SKIP_LOCALHOST_IN_DEV=false
```

## Troubleshooting

### Issue: Still getting "localhost not in list" error

**If using Solution 1:**
1. Verify `localhost` is added in Google console (not just `*.localhost`)
2. Wait 5-10 minutes for changes to propagate
3. Clear browser cache
4. Check that you're using the correct site key for the site that has localhost registered

**If using Solution 2:**
1. Verify `APP_ENV=local` in `.env`
2. Verify `RECAPTCHA_SKIP_LOCALHOST_IN_DEV=true` in `.env`
3. Run: `php artisan config:clear`
4. Restart your development server

### Issue: reCAPTCHA widget not loading

1. Check browser console for errors
2. Verify `RECAPTCHA_SITE_KEY` is set in `.env`
3. Check that site key matches the one in Google console
4. Clear browser cache

### Issue: Validation still required on localhost subdomain

1. Check that `APP_ENV=local` (not `production` or `testing`)
2. Verify `RECAPTCHA_SKIP_LOCALHOST_IN_DEV=true`
3. Check logs: `storage/logs/laravel.log` for reCAPTCHA messages
4. Make sure you're accessing a subdomain (e.g., `newsubdomain.localhost`), not just `localhost`

## Verification

After applying either solution, check the logs:

```bash
tail -f storage/logs/laravel.log | grep -i recaptcha
```

You should see:
- **Solution 1:** `reCAPTCHA verification result` with `success: true`
- **Solution 2:** `reCAPTCHA validation skipped for localhost subdomain in development`

## Summary

✅ **Solution 1:** Register `localhost` in Google console → Full reCAPTCHA testing  
✅ **Solution 2:** Set `RECAPTCHA_SKIP_LOCALHOST_IN_DEV=true` → Skip validation in dev  

Both solutions work! Choose based on your needs.

