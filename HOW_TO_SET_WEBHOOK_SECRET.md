# How to Set Webhook Secret in Razorpay Dashboard

## Important: You Create the Secret Yourself!

Razorpay **does NOT automatically generate** a webhook secret. **You need to create it yourself** when setting up the webhook.

## Option 1: Set Secret When Creating Webhook (Recommended)

### Step 1: Create/Edit Webhook in Razorpay Dashboard

1. **Go to Razorpay Dashboard:**
   - Navigate to **Settings** → **Webhooks**
   - Click **"+ Add New Webhook"** (or edit existing webhook)

2. **Fill in Webhook Details:**
   - **Webhook URL**: `https://talentlit.com/webhook/razorpay`
   - **Secret**: **Enter your own secret here** (this is what you'll use as `RAZORPAY_WEBHOOK_SECRET`)
     - Example: `whsec_talentlit_production_2024_secure_key_12345`
     - Or generate a random string: `whsec_` + random characters
   - **Alert Email**: Your email for webhook failure notifications
   - **Active Events**: Select the events (payment.captured, invoice.paid, etc.)

3. **Save the Webhook:**
   - Click **"Create Webhook"** or **"Save"**
   - **IMPORTANT:** Copy the secret you entered - Razorpay won't show it again!

## Option 2: If Webhook Already Created Without Secret

If you already created the webhook without setting a secret:

1. **Edit the Webhook:**
   - Go to **Settings** → **Webhooks**
   - Click on your webhook
   - Click **"Edit"** or **"Update"**
   - Add a **Secret** field value
   - Save

2. **If Secret Field is Not Visible:**
   - Some Razorpay accounts may not show the secret field after creation
   - You may need to **delete and recreate** the webhook with a secret
   - Or contact Razorpay support to add a secret to existing webhook

## Generating a Secure Webhook Secret

You can generate a secure random secret. Here are some options:

### Option A: Use a Random String Generator
```bash
# Generate a random secret (Linux/Mac)
openssl rand -hex 32
# Result: abc123def456... (add whsec_ prefix)

# Or use online generator: https://randomkeygen.com/
```

### Option B: Create Your Own Secret
Format: `whsec_` + your custom string

Examples:
- `whsec_talentlit_prod_2024_secure`
- `whsec_abc123def456ghi789jkl012`
- `whsec_my_custom_secret_key_12345`

**Important:** 
- Make it long and random (at least 32 characters after `whsec_`)
- Keep it secure and don't share it
- Use different secrets for development and production

## Step 3: Add Secret to Your .env File

Once you've set the secret in Razorpay dashboard:

1. **Open your `.env` file**

2. **Add the webhook secret:**
   ```env
   RAZORPAY_WEBHOOK_SECRET=whsec_your_secret_here
   ```
   
   Replace `whsec_your_secret_here` with the **exact same secret** you entered in Razorpay dashboard.

3. **Save the file**

4. **Clear config cache:**
   ```bash
   php artisan config:clear
   ```

## Step 4: Verify Secret Matches

The secret in your `.env` file **MUST match exactly** with the secret you set in Razorpay dashboard.

**To verify:**
1. Check Razorpay Dashboard → Webhooks → Your Webhook
2. If secret is visible, compare with `.env` file
3. If not visible, you'll need to remember what you set (or recreate webhook)

## Troubleshooting

### "Secret field not visible in Razorpay dashboard"
- Some Razorpay account types may not show the secret field
- Try editing the webhook - secret field might appear there
- If still not visible, you may need to:
  - Delete and recreate the webhook with a secret
  - Contact Razorpay support

### "Webhook signature verification fails"
- Ensure the secret in `.env` **exactly matches** what you set in Razorpay dashboard
- Check for extra spaces, quotes, or typos
- Clear config cache: `php artisan config:clear`
- Verify the secret is being loaded: `php artisan tinker` → `config('razorpay.webhook_secret')`

### "Can't find where to set secret"
- Look for "Secret" field when creating/editing webhook
- It might be labeled as "Webhook Secret" or just "Secret"
- If using Razorpay API, secret is set via API call (not dashboard)

## Alternative: Use Razorpay API to Set Secret

If dashboard doesn't allow setting secret, you can use Razorpay API:

```php
// This is just for reference - you'd need to implement this
$razorpay->webhook->create([
    'url' => 'https://talentlit.com/webhook/razorpay',
    'secret' => 'whsec_your_secret_here',
    'events' => ['payment.captured', 'invoice.paid', ...]
]);
```

## Quick Checklist

- [ ] Created/edited webhook in Razorpay dashboard
- [ ] Set a custom secret when creating webhook
- [ ] Copied the secret immediately (Razorpay won't show it again)
- [ ] Added secret to `.env` file: `RAZORPAY_WEBHOOK_SECRET=whsec_...`
- [ ] Cleared config cache: `php artisan config:clear`
- [ ] Verified secret matches between dashboard and `.env`
- [ ] Tested webhook from Razorpay dashboard

## Important Notes

⚠️ **Razorpay does NOT show the secret again after creation!**
- Make sure to copy it immediately when you create/edit the webhook
- Store it securely (password manager, secure notes, etc.)
- If you lose it, you'll need to recreate the webhook with a new secret

✅ **The secret is used for security:**
- Razorpay signs webhook requests with this secret
- Your application verifies the signature to ensure requests are from Razorpay
- This prevents unauthorized webhook calls

