# Webhook Setup - Next Steps Checklist

## ✅ Step 1: Add Webhook Secret to Environment

1. **Copy the Webhook Secret from Razorpay Dashboard:**
   - Go to Razorpay Dashboard → Settings → Webhooks
   - Click on your webhook
   - Copy the **Webhook Secret** (starts with `whsec_`)

2. **Add to your `.env` file:**
   ```env
   RAZORPAY_WEBHOOK_SECRET=whsec_your_actual_secret_here
   ```

3. **Clear Laravel config cache:**
   ```bash
   php artisan config:clear
   ```

## ✅ Step 2: Test Webhook Connection

1. **In Razorpay Dashboard:**
   - Go to **Webhooks** → Select your webhook
   - Click **"Send Test Webhook"** or **"Test"** button
   - Select event: `payment.captured`
   - Click **"Send Test"**

2. **Check Webhook Delivery Status:**
   - In Razorpay Dashboard, check **"Recent Deliveries"** section
   - Look for status code **200** (Success)
   - If you see errors, check the error message

3. **Check Your Application Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```
   - Look for: `Invoice paid webhook received` or `Payment captured handled`
   - If you see errors, note them down

## ✅ Step 3: Verify Webhook URL is Accessible

Test if your webhook endpoint is publicly accessible:

```bash
curl -X POST https://talentlit.com/webhook/razorpay
```

**Expected Response:**
- Should return an error about missing signature (this is normal - means endpoint is accessible)
- If you get connection error, check server/firewall settings

## ✅ Step 4: Test Real Payment Flow

1. **Test One-Time Payment:**
   - Go to your subscription page
   - Click "Upgrade to Pro" (if it's a one-time payment plan)
   - Complete the payment
   - Check webhook delivery in Razorpay dashboard
   - Verify subscription status in your application

2. **Test Recurring Subscription Payment:**
   - Create a subscription payment
   - Complete the initial payment
   - Wait for the next billing cycle (or use Razorpay test mode to trigger)
   - Verify `invoice.paid` webhook is received
   - Check that subscription `expires_at` is extended

## ✅ Step 5: Monitor Webhook Deliveries

**Regular Monitoring:**
- Check Razorpay Dashboard → Webhooks → Recent Deliveries daily
- Look for any failed deliveries (status code other than 200)
- Review application logs for webhook processing errors

**What to Look For:**
- ✅ **200 Status**: Webhook delivered and processed successfully
- ⚠️ **400/500 Status**: Check application logs for errors
- ❌ **Timeout**: Check server response time and firewall settings

## ✅ Step 6: Verify All Events Are Working

Test each webhook event type:

1. **`payment.captured`** - Test with a successful payment
2. **`payment.failed`** - Test with a failed payment (use test cards)
3. **`invoice.paid`** - Test with recurring subscription payment
4. **`invoice.expired`** - Test by letting a subscription invoice expire
5. **`order.paid`** - Test with one-time order payment

## 🔧 Troubleshooting

### Webhook Returns 400/500 Error

1. **Check Laravel Logs:**
   ```bash
   tail -f storage/logs/laravel.log | grep -i webhook
   ```

2. **Common Issues:**
   - **Signature verification failed**: Check `RAZORPAY_WEBHOOK_SECRET` matches dashboard
   - **Route not found**: Verify route exists: `POST /webhook/razorpay`
   - **Database error**: Check database connection and table structure
   - **Missing columns**: Run migrations if needed

### Webhook Not Receiving Events

1. **Verify Events Selected:**
   - Go to Razorpay Dashboard → Webhooks → Your Webhook
   - Ensure all required events are checked:
     - `payment.captured`
     - `payment.failed`
     - `invoice.paid`
     - `invoice.expired`
     - `order.paid`

2. **Check Webhook URL:**
   - Must be HTTPS (not HTTP)
   - Must be publicly accessible
   - Must match exactly: `https://talentlit.com/webhook/razorpay`

3. **Check Server Logs:**
   - Verify webhook endpoint is receiving requests
   - Check for firewall/security blocks

## 📋 Verification Checklist

- [ ] Webhook secret added to `.env` file
- [ ] Config cache cleared (`php artisan config:clear`)
- [ ] Test webhook sent successfully from Razorpay dashboard
- [ ] Webhook delivery shows status 200 in Razorpay dashboard
- [ ] Application logs show webhook received and processed
- [ ] Real payment test completed successfully
- [ ] Subscription status updated correctly after payment
- [ ] Recurring payment webhook (`invoice.paid`) tested
- [ ] All required events are selected in Razorpay dashboard

## 🎯 Success Indicators

You'll know everything is working when:

1. ✅ Test webhook from Razorpay dashboard returns 200 status
2. ✅ Application logs show webhook events being processed
3. ✅ Real payments update subscription status automatically
4. ✅ Recurring payments extend subscription `expires_at` automatically
5. ✅ No failed webhook deliveries in Razorpay dashboard

## 📞 Need Help?

If you encounter issues:
1. Check `storage/logs/laravel.log` for detailed error messages
2. Review Razorpay Dashboard → Webhooks → Recent Deliveries for delivery status
3. Verify all environment variables are set correctly
4. Test webhook endpoint accessibility with curl

