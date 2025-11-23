# How to Add RAZORPAY_WEBHOOK_SECRET

## Step 1: Set Webhook Secret in Razorpay Dashboard

**IMPORTANT:** Razorpay does NOT automatically generate a webhook secret. **You need to create it yourself** when setting up the webhook.

1. **Log in to Razorpay Dashboard:**
   - Go to https://dashboard.razorpay.com/
   - Navigate to **Settings** → **Webhooks**

2. **Create or Edit Your Webhook:**
   - Click **"+ Add New Webhook"** (or edit existing webhook)
   - Fill in webhook URL: `https://talentlit.com/webhook/razorpay`
   - **Find the "Secret" or "Webhook Secret" field**
   - **Enter your own secret** (e.g., `whsec_talentlit_prod_2024_secure_key_12345`)
   - Select the events you need
   - Click **"Create Webhook"** or **"Save"**

3. **Copy the Secret Immediately:**
   - After saving, **Razorpay won't show the secret again!**
   - Make sure you copied it before closing the page
   - Store it securely (password manager, secure notes, etc.)

**If you already created the webhook without a secret:**
- Edit the webhook and add a secret field
- If secret field is not visible, you may need to delete and recreate the webhook
- Or contact Razorpay support

## Step 2: Add to Your `.env` File

1. **Open your `.env` file** (in the root directory of your project)

2. **Find or add Razorpay configuration section:**
   Look for existing Razorpay variables like:
   ```env
   RAZORPAY_KEY_ID=rzp_xxxxx
   RAZORPAY_KEY_SECRET=xxxxx
   ```

3. **Add the webhook secret:**
   Add this line (replace with your actual secret):
   ```env
   RAZORPAY_WEBHOOK_SECRET=whsec_your_actual_secret_here
   ```

   **Example:**
   ```env
   RAZORPAY_KEY_ID=rzp_test_1234567890
   RAZORPAY_KEY_SECRET=abcdefghijklmnopqrstuvwxyz
   RAZORPAY_WEBHOOK_SECRET=whsec_abc123def456ghi789jkl012mno345pqr678stu901vwx234yz
   PRO_PLAN_MODE=active
   ```

4. **Save the file**

## Step 3: Clear Config Cache

After adding the secret, clear Laravel's config cache:

```bash
php artisan config:clear
```

Or if you're on production server:
```bash
php artisan config:clear
php artisan cache:clear
```

## Step 4: Verify It's Working

1. **Check if the variable is loaded:**
   ```bash
   php artisan tinker
   ```
   Then run:
   ```php
   config('razorpay.webhook_secret')
   ```
   It should show your webhook secret (not null)

2. **Test the webhook:**
   - Go to Razorpay Dashboard → Webhooks → Your Webhook
   - Click "Send Test Webhook"
   - Check "Recent Deliveries" for status 200

## Important Notes

- ✅ **Never commit `.env` file to Git** - it contains sensitive secrets
- ✅ **Use different secrets** for development and production
- ✅ **Keep the secret secure** - don't share it publicly
- ✅ **If secret is regenerated** in Razorpay dashboard, update `.env` immediately

## Troubleshooting

### Secret is null after adding to .env
- Make sure you saved the `.env` file
- Run `php artisan config:clear`
- Check for typos in the variable name: `RAZORPAY_WEBHOOK_SECRET` (all caps, underscores)

### Webhook signature verification fails
- Verify the secret in `.env` matches exactly with Razorpay dashboard
- Make sure there are no extra spaces or quotes around the secret
- Clear config cache again: `php artisan config:clear`

### Can't find webhook secret in Razorpay dashboard
- Make sure the webhook is created and saved
- Some accounts show it in "Settings" → "Webhooks" → Click on webhook → "Secret" or "Webhook Secret"
- If still not visible, contact Razorpay support

