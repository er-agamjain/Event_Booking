# Payment Method Configuration Guide

## Overview
The Event Booking System now includes a comprehensive payment method configuration system that allows administrators to enable/disable payment methods and configure their credentials.

## Features

### 1. **Admin Panel Configuration**
Location: `Admin Dashboard → Platform Settings → Payment Methods Configuration`

#### Configurable Payment Methods:

#### **UPI Payment**
- **Status**: Enabled by default
- **Configuration Fields**:
  - UPI ID (default: `eventbooking@upi`)
  - Merchant Name (default: `Event Booking`)
- **Supported Apps**: Google Pay, PhonePe, Paytm

#### **Stripe (Credit/Debit Cards)**
- **Status**: Disabled by default
- **Configuration Fields**:
  - Stripe Public Key (starts with `pk_live_` or `pk_test_`)
  - Stripe Secret Key (starts with `sk_live_` or `sk_test_`)
- **Security**: Keys are stored as password fields (hidden input)

#### **PayPal**
- **Status**: Disabled by default
- **Configuration Fields**:
  - PayPal Client ID
  - PayPal Secret
- **Security**: Both fields are password-protected

#### **NetBanking**
- **Status**: Enabled by default
- **Configuration Fields**:
  - Gateway Selection (Razorpay, Instamojo, PayU)
- **Supported Banks**: All major Indian banks

#### **Digital Wallets**
- **Status**: Enabled by default
- **Configuration Fields**:
  - Gateway Selection (Razorpay, Instamojo, PayU)
- **Supported Wallets**: Paytm, Amazon Pay, PhonePe Wallet

### 2. **Database Storage**
All settings are stored in the `platform_settings` table with the following structure:
```
key: 'upi_enabled', 'stripe_enabled', 'paypal_enabled', etc.
value: '1' (enabled) or '0' (disabled) / configuration value
type: 'text', 'select', etc.
```

### 3. **Payment Creation Page**
Location: `/user/payments/{booking}/create`

**Features:**
- Only enabled payment methods are displayed to users
- Each payment method shows:
  - Icon (Font Awesome)
  - Name and description
  - Color-coded for easy identification
- Error message if no payment methods are enabled
- Dynamically renders based on admin configuration

### 4. **Payment Success Page**
Location: `/user/payments/{booking}/{payment}/success`

**Features:**
- Shows QR code for e-ticket
- Displays selected seats (if applicable)
- Shows payment details and booking reference
- For UPI payments, displays:
  - UPI ID with copy-to-clipboard button
  - Amount to pay
  - Reference number

## Helper Functions

The `PaymentHelper` class provides convenient methods:

```php
// Check if a payment method is enabled
PaymentHelper::isUPIEnabled()
PaymentHelper::isStripeEnabled()
PaymentHelper::isPayPalEnabled()
PaymentHelper::isNetbankingEnabled()
PaymentHelper::isWalletEnabled()

// Get payment method configuration
PaymentHelper::getUPIId()
PaymentHelper::getUPIMerchantName()
PaymentHelper::getStripePublicKey()
PaymentHelper::getPayPalClientId()
PaymentHelper::getNetbankingGateway()
PaymentHelper::getWalletGateway()

// Get all enabled methods
PaymentHelper::getActivePaymentMethods()
PaymentHelper::getEnabledMethods()
PaymentHelper::hasAnyPaymentMethod()

// Get specific method details
PaymentHelper::getPaymentMethodDetails($method)
```

## Setup Instructions

### 1. Access Admin Settings
```
1. Login as Admin
2. Go to Admin Dashboard
3. Click "Platform Settings"
4. Scroll to "Payment Methods Configuration" section
```

### 2. Enable/Disable Payment Methods
- Check/uncheck the checkbox next to each payment method
- Configuration fields appear when method is enabled (for credential-based methods)

### 3. Configure Credentials

#### **For UPI:**
- Set your UPI ID (e.g., `yourname@bankname`)
- Set merchant name (displayed in UPI apps)

#### **For Stripe:**
- Get keys from https://dashboard.stripe.com/
- Copy Public Key and Secret Key
- Paste in corresponding fields

#### **For PayPal:**
- Get credentials from PayPal Developer Portal
- Copy Client ID and Secret
- Paste in corresponding fields

#### **For NetBanking & Wallets:**
- Select preferred payment gateway
- Configure gateway credentials separately in your .env file

### 4. Save Settings
- Click "Save Settings" button
- Confirmation message appears on success

## User Experience

### Booking Flow:
1. User selects seats
2. Confirms booking
3. Redirected to payment page
4. Only enabled payment methods are shown
5. User selects preferred method
6. Redirected to payment success page
7. Gets QR e-ticket and payment confirmation

## Security Considerations

- ✅ All sensitive credentials (API keys, secrets) are stored as password fields
- ✅ Payment settings are validated before saving
- ✅ Only authorized admins can modify payment settings
- ✅ UPI ID and merchant name are safely displayed
- ✅ Payment credentials are not exposed in frontend code

## Files Modified/Created

### New Files:
- `app/Helpers/PaymentHelper.php` - Payment configuration helper class
- `resources/views/user/payments/success.blade.php` - Payment success page (previous session)

### Modified Files:
- `resources/views/admin/settings/index.blade.php` - Added payment methods section
- `resources/views/user/payments/create.blade.php` - Dynamic payment method rendering
- `app/Http/Controllers/Admin/PlatformSettingController.php` - Payment settings handling

### Routes:
- `GET /admin/settings` - View payment settings
- `PUT /admin/settings` - Update payment settings
- `GET /user/payments/{booking}/create` - Payment method selection
- `POST /user/payments/{booking}` - Process payment
- `GET /user/payments/{booking}/{payment}/success` - Payment success

## Testing Payment Methods

### UPI (Default Enabled)
- Test with default UPI ID: `eventbooking@upi`
- Shows on payment page with mobile icon

### Others (Disabled by Default)
- Must be enabled and configured in admin settings first
- Full gateway integration required (.env configuration)

## Next Steps

1. **Complete Stripe Integration** (if needed):
   - Add Stripe PHP package
   - Implement payment processing logic
   
2. **Complete PayPal Integration** (if needed):
   - Add PayPal SDK
   - Implement payment processing logic
   
3. **Gateway Integration** (NetBanking & Wallets):
   - Select and integrate your preferred gateway
   - Configure gateway credentials in .env
   
4. **Testing**:
   - Test each enabled payment method
   - Verify QR code generation
   - Validate payment success page

## Troubleshooting

### Payment methods not showing:
- Check that at least one method is enabled in admin settings
- Clear Laravel cache: `php artisan cache:clear`

### Configuration not saving:
- Verify form submission (check browser console)
- Check database permissions
- Verify PlatformSetting model is accessible

### UPI ID not displaying:
- Check that UPI is enabled
- Verify UPI ID is set in settings (minimum 6 characters)
- Check for any caching issues

## Support

For issues or questions about payment configuration, refer to:
- Admin Settings page (has helpful tooltips)
- Database schema in `migrations/`
- PlatformSetting model in `app/Models/`
