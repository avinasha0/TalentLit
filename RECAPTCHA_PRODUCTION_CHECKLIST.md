# reCAPTCHA Production Checklist

## ✅ Production Behavior Verification

### How It Works in Production

**The code is designed to work differently in production vs development:**

#### Development (APP_ENV=local/development):
- ✅ reCAPTCHA widget is **skipped** for localhost subdomains
- ✅ Shows "reCAPTCHA skipped in development mode"
- ✅ Form submits without reCAPTCHA

#### Production (APP_ENV=production):
- ✅ reCAPTCHA widget **loads normally**
- ✅ Full reCAPTCHA validation **required**
- ✅ `dev-skip` value is **rejected** (security safeguard)

## 🔒 Production Configuration

### Required `.env` Settings:

```env
# Production Environment
APP_ENV=production
APP_DEBUG=false

# reCAPTCHA Configuration
RECAPTCHA_SITE_KEY=your_production_site_key
RECAPTCHA_SECRET_KEY=your_production_secret_key

# Optional: Keep this true (only affects localhost, not production)
RECAPTCHA_SKIP_LOCALHOST_IN_DEV=true

# Optional: Auto-accept subdomains (recommended: true)
RECAPTCHA_AUTO_ACCEPT_SUBDOMAINS=true
```

### Google reCAPTCHA Console Setup:

1. **Register Base Domain:**
   - Go to: https://www.google.com/recaptcha/admin
   - Add your production domain: `talentlit.com` (or your domain)
   - **Do NOT add localhost** (only needed for development)

2. **Subdomains Work Automatically:**
   - `talentlit.com` ✓
   - `tenant1.talentlit.com` ✓ (works automatically)
   - `tenant2.talentlit.com` ✓ (works automatically)

## ✅ Code Verification

### Component Logic (recaptcha.blade.php):

```php
Line 14: $shouldSkip = $skipLocalhostInDev && 
                      app()->environment(['local', 'development']) && 
                      $isLocalhost;
```

**In Production:**
- `app()->environment(['local', 'development'])` = **FALSE**
- `$shouldSkip` = **FALSE**
- Widget **loads normally** ✓

### Validation Logic (RecaptchaRule.php):

```php
Line 50-58: Only skips if environment is 'local' or 'development'
Line 64-72: Rejects 'dev-skip' in production (security safeguard)
```

**In Production:**
- Environment check = **FALSE** (not local/development)
- Validation **runs normally** ✓
- `dev-skip` value **rejected** if somehow sent ✓

## ✅ Production Testing

### Test Checklist:

1. **Environment Check:**
   ```bash
   php artisan tinker
   >>> config('app.env')
   # Should return: "production"
   >>> app()->environment('production')
   # Should return: true
   ```

2. **Component Test:**
   - Visit production URL: `https://talentlit.com/login`
   - Check page source: Should see `<div class="g-recaptcha"` 
   - Should NOT see: "reCAPTCHA skipped in development mode"
   - Should NOT see: `<input type="hidden" name="g-recaptcha-response" value="dev-skip">`

3. **Validation Test:**
   - Try submitting form without completing reCAPTCHA
   - Should get error: "Please complete the reCAPTCHA verification"
   - Complete reCAPTCHA and submit
   - Should work successfully

4. **Subdomain Test:**
   - Visit: `https://tenant1.talentlit.com/login`
   - reCAPTCHA should load and work
   - Form should validate properly

## 🔒 Security Safeguards

### Built-in Protections:

1. **Environment Check:**
   - Skip only works in `local` or `development`
   - Production always requires reCAPTCHA

2. **dev-skip Rejection:**
   - If `dev-skip` value somehow reaches production, it's rejected
   - Logs error for security monitoring

3. **Hostname Validation:**
   - Server-side verification always runs in production
   - Google API validates the token

## 📋 Pre-Deployment Checklist

- [ ] `APP_ENV=production` in `.env`
- [ ] `APP_DEBUG=false` in `.env`
- [ ] Production reCAPTCHA site key configured
- [ ] Production reCAPTCHA secret key configured
- [ ] Base domain registered in Google console
- [ ] Test reCAPTCHA on main domain
- [ ] Test reCAPTCHA on subdomain
- [ ] Verify widget loads (not skipped)
- [ ] Verify validation works
- [ ] Check logs for any errors

## 🚨 Common Issues

### Issue: reCAPTCHA not loading in production

**Check:**
1. `APP_ENV=production` (not `local`)
2. Site key is correct
3. Domain is registered in Google console
4. Browser console for errors

### Issue: Validation failing in production

**Check:**
1. Secret key is correct
2. Domain is registered in Google console
3. Check logs: `storage/logs/laravel.log`
4. Verify subdomain is allowed (base domain matching)

### Issue: Still seeing "skipped in development"

**Check:**
1. `APP_ENV` is set to `production`
2. Clear config cache: `php artisan config:clear`
3. Clear view cache: `php artisan view:clear`
4. Hard refresh browser

## ✅ Summary

**Production Behavior:**
- ✅ reCAPTCHA **always loads** (not skipped)
- ✅ Validation **always required**
- ✅ `dev-skip` **rejected** if sent
- ✅ Subdomains work automatically
- ✅ Full security protection enabled

**The skip only happens in development - production is fully protected!**

