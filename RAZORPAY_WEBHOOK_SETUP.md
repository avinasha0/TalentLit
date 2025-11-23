# Razorpay Webhook Configuration Guide

## Overview
This guide will help you configure webhooks in your Razorpay dashboard to receive payment and subscription notifications automatically.

## Prerequisites
- Razorpay account with API access
- Your application deployed and accessible via HTTPS
- `RAZORPAY_WEBHOOK_SECRET` environment variable ready to be set

## Step-by-Step Configuration

### Step 1: Access Razorpay Dashboard
1. Log in to your [Razorpay Dashboard](https://dashboard.razorpay.com/)
2. Navigate to **Settings** → **Webhooks** (or **Webhooks** from the left sidebar)

### Step 2: Add New Webhook
1. Click on **"Add New Webhook"** or **"Create Webhook"** button
2. You'll see a form to configure the webhook

### Step 3: Configure Webhook URL
Enter your webhook URL in the format:
```
https://yourdomain.com/webhook/razorpay
```

**For TalentLit Production:**
```
https://talentlit.com/webhook/razorpay
```

**Important Notes:**
- The URL must use **HTTPS** (not HTTP)
- The URL must be publicly accessible (not localhost)
- The URL should match exactly: `/webhook/razorpay` (case-sensitive)

### Step 4: Select Webhook Events
Select the following events that your application needs to handle:

**Required Events for Subscriptions:**
- ✅ `payment.captured` - When a payment is successfully captured (one-time payments)
- ✅ `payment.failed` - When a payment fails
- ✅ `invoice.paid` - **When a recurring subscription payment is charged** (This is the event Razorpay sends when subscription invoice is paid)
- ✅ `invoice.expired` - When a subscription invoice expires (payment failed or not completed)
- ✅ `order.paid` - When an order is paid (one-time payments)

**Optional Events:**
- `invoice.partially_paid` - When a subscription payment is partially completed (handled but not critical)

### Step 5: Set Webhook Secret and Save
1. **Set a Webhook Secret:**
   - In the webhook form, find the **"Secret"** or **"Webhook Secret"** field
   - **Enter your own secret** (Razorpay doesn't auto-generate it)
   - Format: `whsec_` + your custom string (e.g., `whsec_talentlit_prod_2024_secure_key`)
   - Make it long and random (at least 32 characters after `whsec_`)
   
2. **Click "Save" or "Create Webhook"**
3. **Copy the secret immediately** - Razorpay won't show it again after saving!
4. Store it securely - you'll need it for your `.env` file

### Step 6: Update Environment Variables
Add the webhook secret to your `.env` file:

```env
RAZORPAY_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxxx
```

**Important:** 
- Never commit this secret to version control
- Use different secrets for development and production
- Keep the secret secure and rotate it if compromised

### Step 7: Test the Webhook
1. In Razorpay Dashboard, go to **Webhooks** → Select your webhook
2. Click **"Send Test Webhook"** or use the **"Test"** button
3. Select an event type (e.g., `payment.captured`)
4. Click **"Send Test"**
5. Check your application logs to verify the webhook was received and processed

### Step 8: Verify Webhook Status
- Check the **"Recent Deliveries"** section in the webhook settings
- Look for successful deliveries (status code 200)
- If you see failures, check:
  - URL is correct and accessible
  - Webhook secret matches in `.env`
  - Server logs for error details

## Webhook Endpoint Details

**Route:** `POST /webhook/razorpay`

**Authentication:** 
- No authentication required (public endpoint)
- Security is handled via signature verification using `X-Razorpay-Signature` header

**Expected Response:**
- Success: `{"status": "success"}` with HTTP 200
- Error: `{"error": "error message"}` with HTTP 400

## Troubleshooting

### Webhook Not Receiving Events
1. **Check URL Accessibility:**
   ```bash
   curl -X POST https://yourdomain.com/webhook/razorpay
   ```
   Should return a response (even if error about missing signature)

2. **Verify Webhook Secret:**
   - Ensure `RAZORPAY_WEBHOOK_SECRET` in `.env` matches the secret in Razorpay dashboard
   - Clear config cache: `php artisan config:clear`

3. **Check Server Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```
   Look for webhook-related errors

4. **Verify Events are Selected:**
   - Go to Razorpay Dashboard → Webhooks → Your Webhook
   - Ensure all required events are checked

### Signature Verification Failures
- Ensure `RAZORPAY_WEBHOOK_SECRET` is correct
- Check that the secret hasn't been regenerated in Razorpay dashboard
- Verify the webhook payload is not being modified by middleware or proxies

### Webhook Delivery Failures
- Check if your server is accessible from the internet
- Verify SSL certificate is valid
- Check firewall rules allow incoming POST requests
- Ensure Laravel route is not blocked by middleware

## Security Best Practices

1. **Always use HTTPS** for webhook URLs
2. **Never expose webhook secret** in code or logs
3. **Verify webhook signature** (already implemented in `RazorPayService::handleWebhook`)
4. **Use different webhooks** for development and production
5. **Monitor webhook deliveries** regularly in Razorpay dashboard
6. **Implement idempotency** to handle duplicate webhook deliveries

## Current Implementation

Your application already handles these webhook events:
- `payment.captured` → Updates subscription status to 'active'
- `payment.failed` → Logs failure and updates subscription status
- `subscription.charged` → Updates subscription `expires_at` for recurring payments
- `subscription.cancelled` → Updates subscription status to 'cancelled'

## Support

If you encounter issues:
1. Check Razorpay Dashboard → Webhooks → Recent Deliveries for error details
2. Review Laravel logs: `storage/logs/laravel.log`
3. Test webhook endpoint manually using Razorpay's test feature
4. Contact Razorpay support if webhook delivery continues to fail

