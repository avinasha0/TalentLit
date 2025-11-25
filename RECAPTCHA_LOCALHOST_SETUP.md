# reCAPTCHA Localhost Subdomain Setup

## Quick Fix for Localhost Subdomains

The code has been updated to support localhost subdomains (e.g., `tenant1.localhost`, `subdomain.localhost`).

## Setup Steps

### 1. Register localhost in Google reCAPTCHA Console

1. Go to: https://www.google.com/recaptcha/admin
2. Select your reCAPTCHA site (or create a new one for development)
3. In the "Domains" section, add:
   ```
   localhost
   127.0.0.1
   ```
   **Note:** You need to add both `localhost` and `127.0.0.1` separately.

### 2. Configure Environment

Add to your `.env` file:

```env
# reCAPTCHA Configuration
RECAPTCHA_SITE_KEY=your_localhost_site_key
RECAPTCHA_SECRET_KEY=your_localhost_secret_key
```

### 3. Test Localhost Subdomains

After setup, these should all work:
- `http://localhost:8000` - Main domain (validation skipped for convenience)
- `http://tenant1.localhost:8000` - Subdomain (reCAPTCHA works)
- `http://tenant2.localhost:8000` - Another subdomain (reCAPTCHA works)
- `http://127.0.0.1:8000` - IP address (validation skipped)

## How It Works

### Updated Behavior

1. **Exact localhost:** Validation is skipped (for convenience during development)
   - `localhost` ✓ (skipped)
   - `127.0.0.1` ✓ (skipped)

2. **Localhost subdomains:** Validation works normally
   - `tenant1.localhost` ✓ (validated)
   - `subdomain.localhost` ✓ (validated)

3. **Base domain matching:** Code automatically accepts subdomains when base domain matches
   - Google may return `localhost` for `tenant1.localhost`
   - Code recognizes they're both localhost and accepts it

## Testing

### Test on Localhost Subdomain

1. **Add to hosts file** (if not already):
   ```
   127.0.0.1    tenant1.localhost
   ```

2. **Access your app:**
   ```
   http://tenant1.localhost:8000
   ```

3. **Test reCAPTCHA:**
   - Try submitting a form with reCAPTCHA
   - It should work now!

## Troubleshooting

### Issue: reCAPTCHA not showing on localhost subdomain

**Check:**
1. `localhost` is registered in Google reCAPTCHA console
2. Site key is correct in `.env`
3. Browser console for JavaScript errors

### Issue: "Invalid site key" error

**Solution:**
- Verify site key matches the one in Google console
- Ensure `localhost` (not `*.localhost`) is registered
- Clear browser cache

### Issue: Verification fails on localhost subdomain

**Check logs:**
```bash
tail -f storage/logs/laravel.log | grep -i recaptcha
```

Look for:
- `hostname` values
- `request_hostname` values
- Error codes

## Development vs Production

### Development (localhost)
- Register: `localhost`, `127.0.0.1`
- Works for: All localhost subdomains automatically

### Production
- Register: `talentlit.com` (or your domain)
- Works for: All production subdomains automatically

## Important Notes

- **Separate Keys:** Consider using different reCAPTCHA keys for development and production
- **Hosts File:** Make sure localhost subdomains are in your hosts file
- **Port Numbers:** Code handles ports automatically (e.g., `localhost:8000`)

## Example Hosts File Entry

For Windows (`C:\Windows\System32\drivers\etc\hosts`):
```
127.0.0.1    localhost
127.0.0.1    tenant1.localhost
127.0.0.1    tenant2.localhost
```

For Linux/Mac (`/etc/hosts`):
```
127.0.0.1    localhost
127.0.0.1    tenant1.localhost
127.0.0.1    tenant2.localhost
```

## Summary

✅ **Fixed:** Localhost subdomains now work with reCAPTCHA  
✅ **Simple:** Just register `localhost` in Google console  
✅ **Automatic:** All localhost subdomains work without individual registration  
✅ **Flexible:** Exact `localhost` still skips validation for convenience  

