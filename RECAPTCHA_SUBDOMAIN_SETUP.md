# reCAPTCHA Subdomain Configuration Guide

This guide explains how to configure Google reCAPTCHA to work with all subdomains in your TalentLit application.

## Problem

When using subdomains (e.g., `tenant1.example.com`, `tenant2.example.com`), reCAPTCHA may fail because:
1. The subdomain is not registered in Google's reCAPTCHA console
2. Google verifies the hostname and rejects unregistered domains
3. Each subdomain needs to be explicitly allowed in Google's configuration

## Solution

### ✅ Automatic Subdomain Support (Recommended)

**Good News!** The code has been updated to automatically support all subdomains when you register only the **base domain** in Google reCAPTCHA console.

### Step 1: Configure Google reCAPTCHA Console (Simple Method)

1. **Go to Google reCAPTCHA Admin Console**
   - Visit: https://www.google.com/recaptcha/admin
   - Sign in with your Google account

2. **Select Your reCAPTCHA Site**
   - Click on your existing reCAPTCHA site (or create a new one)

3. **Add Only Base Domain(s)**
   - In the "Domains" section, add **only your base domain(s)**
   - Example: `talentlit.com` or `example.com`
   - **You do NOT need to add each subdomain individually!**

4. **How It Works:**
   - Register: `talentlit.com` in Google console
   - Works automatically for: `tenant1.talentlit.com`, `tenant2.talentlit.com`, etc.
   - The code automatically accepts subdomains when the base domain matches

### Alternative: Manual Subdomain Registration (Not Recommended)

If you prefer to register each subdomain manually (not necessary with the updated code):

1. **Add Each Subdomain Individually**
   - Google reCAPTCHA does **NOT** support wildcard domains (e.g., `*.example.com`)
   - You must list each subdomain individually
   - Maximum 200 domains per reCAPTCHA site

5. **Alternative: Use Separate reCAPTCHA Keys**
   - Create separate reCAPTCHA sites for different subdomains
   - Use different site keys per subdomain (requires code changes)

### Step 2: Update Environment Configuration (Optional)

The code automatically accepts subdomains of registered domains. However, you can optionally restrict which base domains are allowed:

```env
# Optional: Comma-separated list of allowed base domains
# All subdomains of these domains will be automatically accepted
RECAPTCHA_ALLOWED_DOMAINS=talentlit.com,example.com

# Optional: Set to false to require exact domain matches (default: true)
# When true, subdomains are automatically accepted if base domain matches
RECAPTCHA_AUTO_ACCEPT_SUBDOMAINS=true
```

**Note:** If you leave `RECAPTCHA_ALLOWED_DOMAINS` empty, all domains that Google accepts will work. The `RECAPTCHA_AUTO_ACCEPT_SUBDOMAINS=true` setting (default) ensures subdomains work automatically.

### Step 3: Verify Configuration

1. **Test on Main Domain**
   - Visit your main domain (e.g., `https://example.com`)
   - Try submitting a form with reCAPTCHA
   - Check browser console for errors

2. **Test on Subdomain**
   - Visit a subdomain (e.g., `https://tenant1.example.com`)
   - Try submitting a form with reCAPTCHA
   - Check browser console and server logs

3. **Check Server Logs**
   - Look for reCAPTCHA verification logs in `storage/logs/laravel.log`
   - Check for hostname mismatch errors
   - Verify that hostname is being logged correctly

### Step 4: Troubleshooting

#### Issue: "reCAPTCHA verification failed" on subdomains

**Possible Causes:**
1. Subdomain not added to Google reCAPTCHA console
2. Hostname mismatch in verification response
3. reCAPTCHA token expired or reused

**Solutions:**
1. **Add Subdomain to Google Console:**
   - Go to Google reCAPTCHA admin console
   - Add the exact subdomain (e.g., `tenant1.example.com`)
   - Wait a few minutes for changes to propagate

2. **Check Logs:**
   ```bash
   tail -f storage/logs/laravel.log | grep -i recaptcha
   ```
   Look for:
   - `hostname` in verification result
   - `error_codes` that indicate domain issues
   - `request_hostname` vs `verified_hostname` mismatch

3. **Verify Domain in Google Console:**
   - Ensure the subdomain is listed exactly as it appears in the browser
   - Check for typos (e.g., `tenant1.example.com` vs `tenant-1.example.com`)

#### Issue: reCAPTCHA widget not loading on subdomain

**Possible Causes:**
1. CORS issues
2. Script loading blocked
3. Site key not configured

**Solutions:**
1. Check browser console for JavaScript errors
2. Verify `RECAPTCHA_SITE_KEY` is set in `.env`
3. Clear browser cache and cookies
4. Check if ad blockers are interfering

#### Issue: "Invalid site key" error

**Possible Causes:**
1. Wrong site key for the domain
2. Site key not configured in environment

**Solutions:**
1. Verify `RECAPTCHA_SITE_KEY` in `.env` matches Google console
2. Ensure the site key is for the correct reCAPTCHA type (v2 vs v3)
3. Check that the site key is for the correct domain

## Implementation Details

### Updated Files

1. **`app/Services/RecaptchaService.php`**
   - Added hostname validation for subdomain support
   - Improved error logging with hostname information
   - Added `isValidHostname()` method to check subdomain matches

2. **`app/Rules/RecaptchaRule.php`**
   - Passes request hostname to verification service
   - Better error messages for subdomain issues

3. **`config/recaptcha.php`**
   - Added `allowed_domains` configuration option
   - Supports comma-separated list of base domains

### How It Works

1. **Request Flow:**
   - User submits form on subdomain (e.g., `tenant1.example.com`)
   - reCAPTCHA widget loads with site key
   - User completes reCAPTCHA challenge
   - Form submits with `g-recaptcha-response` token

2. **Verification Flow:**
   - Server receives token and request hostname
   - Token sent to Google's verification API
   - Google returns verification result with hostname
   - Service validates hostname matches request (supports subdomains)
   - Returns success/failure

3. **Hostname Matching:**
   - Exact match: `tenant1.example.com` === `tenant1.example.com` ✓
   - Subdomain match: `tenant1.example.com` ends with `.example.com` ✓
   - Base domain match: Base domains match ✓

## Best Practices

1. **Register All Subdomains in Google Console**
   - Add each subdomain before it's used
   - Keep a list of all active subdomains
   - Remove unused subdomains to maintain security

2. **Monitor Logs**
   - Regularly check reCAPTCHA verification logs
   - Watch for hostname mismatch errors
   - Track failed verification attempts

3. **Use Environment-Specific Keys**
   - Use different keys for development and production
   - Register `localhost` and `127.0.0.1` for local development
   - Use production keys only in production environment

4. **Test Regularly**
   - Test reCAPTCHA on new subdomains before going live
   - Verify after Google console changes
   - Test form submissions on all active subdomains

## Google reCAPTCHA Console Limits

- **Maximum Domains:** Google allows up to 200 domains per reCAPTCHA site
- **Domain Format:** Must be exact match (no wildcards)
- **Propagation Time:** Changes may take 5-10 minutes to take effect

## Alternative Solutions

If you have many subdomains (more than 200), consider:

1. **Multiple reCAPTCHA Sites:**
   - Create separate reCAPTCHA sites for different tenant groups
   - Use different site keys per group
   - Requires code changes to select correct key

2. **Domain-Based Key Selection:**
   - Store reCAPTCHA site key per tenant
   - Select key based on current subdomain
   - More complex but supports unlimited subdomains

3. **Use reCAPTCHA v3:**
   - reCAPTCHA v3 may have different domain handling
   - Consider switching if v2 doesn't meet your needs

## Support

If you continue to experience issues:

1. Check application logs: `storage/logs/laravel.log`
2. Check Google reCAPTCHA console for domain configuration
3. Verify environment variables are set correctly
4. Test with Google's reCAPTCHA test page
5. Contact Google reCAPTCHA support if domain issues persist

## Summary

To make reCAPTCHA work with all subdomains **automatically**:

1. ✅ **Register only the base domain** in Google reCAPTCHA console (e.g., `talentlit.com`)
2. ✅ Ensure site key and secret key are configured in `.env`
3. ✅ All subdomains will work automatically (no need to add each one!)
4. ✅ Test on main domain and a subdomain to verify
5. ✅ Monitor logs for verification issues

### How It Works

- **Google Console:** Register `talentlit.com`
- **Automatic Support:** `tenant1.talentlit.com`, `tenant2.talentlit.com`, etc. all work
- **No Manual Updates:** New subdomains work immediately without any configuration changes
- **Base Domain Matching:** Code automatically accepts subdomains when base domain matches

The code has been updated to automatically handle subdomain verification - you only need to register the base domain in Google's console!

