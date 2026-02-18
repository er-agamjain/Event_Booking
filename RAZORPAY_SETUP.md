# Razorpay Payment Integration Guide

## Configuration Steps

### 1. Admin Settings Configuration
1. Go to Admin Panel → Settings
2. Find "Razorpay" payment method section
3. Enable the checkbox for "Razorpay"
4. Enter your Razorpay credentials:
   - **Razorpay Key ID**: `rzp_live_xxxxxxxxxxxx` (from Razorpay Dashboard)
   - **Razorpay Secret Key**: Your secret key (keep this secure)

### 2. Getting Razorpay Credentials
1. Sign up at [Razorpay Dashboard](https://dashboard.razorpay.com)
2. Go to Settings → API Keys
3. Copy your Key ID (public) and Secret (keep it secure)
4. Paste them in the admin settings

### 3. Payment Methods Supported
Razorpay supports multiple payment methods:
- **Credit/Debit Cards** (Visa, Mastercard, RuPay)
- **UPI** (All UPI apps - Google Pay, PhonePe, Paytm, WhatsApp Pay, etc.)
- **Digital Wallets** (Paytm, Amazon Pay, PhonePe, Mobikwik)
- **NetBanking** (All major Indian banks)
- **NEFT/RTGS** (Bank transfers)

## Payment Flow

### User Side
1. User selects "Razorpay" as payment method
2. Clicks "Proceed to pay"
3. See payment summary page with Razorpay option
4. Clicks "I Have Completed Payment"
5. Payment is created with status = "pending"
6. User sees "Awaiting Verification" page

### Admin Side
1. Admin goes to Admin Panel → Payments → Pending
2. Reviews pending payments
3. Clicks "Approve" to confirm payment and send tickets
4. OR Clicks "Reject" to cancel booking

## Integration Notes

### Current Implementation
- Razorpay is configured as a "pending verification" payment method
- Admin manually verifies and approves payments
- This ensures security and prevents fraud

### For Advanced Implementation
To enable automatic Razorpay payment processing:
1. Implement Razorpay API client
2. Handle Razorpay webhook callbacks
3. Auto-approve payments when webhook confirms success
4. Implement refund processing

## Webhook Configuration (Future Enhancement)

To enable automatic payment confirmation:
1. Go to Razorpay Dashboard → Settings → Webhooks
2. Add webhook URL: `https://yourdomain.com/api/razorpay-webhook`
3. Select events: `payment.authorized`, `payment.failed`
4. Copy webhook secret
5. Implement webhook handler in your application

## Testing

### Test Credentials
- Use Razorpay's test mode credentials during development
- Switch to live credentials in production

### Test Cards
Razorpay provides test cards for different scenarios:
- **Success**: `4111 1111 1111 1111` (Visa)
- **Declined**: `4000 0000 0000 0002`
- Use any future expiry date and any CVV

### Test UPI
- UPI ID: `test@razorpay`
- OTP: `000000`

## Security Best Practices

1. **Never expose secret keys** in client-side code
2. **Use HTTPS** for all payment pages
3. **Verify webhook signatures** before processing
4. **Store encrypted** payment transaction IDs
5. **Implement rate limiting** on payment endpoints
6. **Regular security audits** of payment code

## Troubleshooting

### Issue: "Invalid Key ID"
- Verify Key ID is correct
- Check it's from the right Razorpay account
- Ensure Key ID is not expired

### Issue: "Invalid Secret Key"
- Verify Secret key is exactly correct
- No extra spaces or characters
- Check it's from the same Razorpay account

### Issue: Payment not appearing in admin panel
- Check if payment was created with correct booking ID
- Verify admin user has proper permissions
- Check if payment status is "pending"

## Support
For Razorpay support: [Razorpay Support](https://razorpay.com/support)
For application support: Contact admin@eventbooking.com
