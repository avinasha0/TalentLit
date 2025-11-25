# reCAPTCHA Production Setup - Automatic Subdomain Support

## Quick Setup Guide

### ✅ Simple Solution (Recommended)

**You only need to register your BASE DOMAIN in Google reCAPTCHA console - all subdomains will work automatically!**

### Step 1: Google reCAPTCHA Console

1. Go to: https://www.google.com/recaptcha/admin
2. Select your reCAPTCHA site (or create new one)
3. In "Domains" section, add **only your base domain**:
   ```
   talentlit.com
   ```
   **That's it!** You don't need to add subdomains.

### Step 2: Environment Configuration

Add to your `.env` file:

```env
# reCAPTCHA Configuration
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here

# Optional: Auto-accept subdomains (default: true)
RECAPTCHA_AUTO_ACCEPT_SUBDOMAINS=true

# Optional: Restrict to specific base domains
# RECAPTCHA_ALLOWED_DOMAINS=talentlit.com
```

### Step 3: Test

1. Test on main domain: `https://talentlit.com`
2. Test on subdomain: `https://tenant1.talentlit.com`
3. Both should work automatically!

## How It Works

### Automatic Subdomain Support

The code automatically handles subdomains:

1. **Google Verification:** When a user completes reCAPTCHA on `tenant1.talentlit.com`, Google verifies it
2. **Base Domain Matching:** Code checks if the base domain matches (e.g., `talentlit.com`)
3. **Auto-Accept:** If base domains match, verification is accepted automatically
4. **No Manual Updates:** New subdomains work immediately without any changes

### Example Flow

```
User on: tenant1.talentlit.com
  ↓
Completes reCAPTCHA
  ↓
Google verifies (may return hostname as "talentlit.com" or "tenant1.talentlit.com")
  ↓
Code checks: base domain = "talentlit.com" ✓
  ↓
Verification accepted! ✅
```

## Production Checklist

- [ ] Register base domain in Google reCAPTCHA console
- [ ] Add `RECAPTCHA_SITE_KEY` to `.env`
- [ ] Add `RECAPTCHA_SECRET_KEY` to `.env`
- [ ] Test on main domain
- [ ] Test on a subdomain
- [ ] Verify logs show successful verification

## Troubleshooting

### Issue: reCAPTCHA not working on subdomain

**Check:**
1. Base domain is registered in Google console
2. Site key and secret key are correct in `.env`
3. Check logs: `storage/logs/laravel.log`
4. Look for "reCAPTCHA verification result" entries

### Issue: "Invalid site key" error

**Solution:**
- Verify `RECAPTCHA_SITE_KEY` matches Google console
- Clear config cache: `php artisan config:clear`

### Issue: Verification fails on subdomain

**Solution:**
- Ensure base domain is registered (not just subdomain)
- Check that `RECAPTCHA_AUTO_ACCEPT_SUBDOMAINS=true` (default)
- Review logs for hostname mismatch errors

## Benefits

✅ **No Manual Updates:** New subdomains work automatically  
✅ **Simple Configuration:** Only register base domain  
✅ **Scalable:** Works with unlimited subdomains  
✅ **Production Ready:** Handles edge cases and logging  

## Important Notes

- **Base Domain Only:** Register `talentlit.com`, not `*.talentlit.com`
- **Automatic:** All subdomains work without adding them individually
- **Secure:** Still validates through Google's API
- **Logged:** All verifications are logged for monitoring

## Support

If issues persist:
1. Check application logs: `storage/logs/laravel.log`
2. Verify Google console domain configuration
3. Test with Google's reCAPTCHA test page
4. Review `RECAPTCHA_SUBDOMAIN_SETUP.md` for detailed troubleshooting

