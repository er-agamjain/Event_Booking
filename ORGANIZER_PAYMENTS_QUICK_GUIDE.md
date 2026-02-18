# Organizer Payment Verification - Quick Reference

## Access Point
**Navigation:** Organizer Dashboard → Payments

## Two Main Views

### 1. **Pending Verification** (`/organiser/payments/pending`)
Shows payments waiting for organizer action.

**For each payment you can:**
- ✅ **Confirm Payment** - Approve and confirm the booking
- ❌ **Reject** - Reject with optional reason
- ⚠️ **Not Received** - Mark for later review

### 2. **Payment History** (`/organiser/payments/history`)
Complete record of all payments (pending, confirmed, rejected).

**Features:**
- Filter by status
- View payment details
- Take action on pending payments

## What Happens When You Confirm Payment?
1. Payment marked as confirmed
2. Booking confirmed automatically
3. User gets notification
4. Seats locked as booked
5. Revenue recorded

## What Happens When You Reject Payment?
1. Payment marked as failed
2. Booking cancelled
3. Seats released for other bookings
4. User can retry with same or different seats

## Status Badges Meaning
- 🟡 **Pending** - Awaiting your verification
- 🟢 **Confirmed** - Payment verified, booking confirmed
- 🔴 **Rejected** - Payment rejected, booking cancelled

## Information Available
For each payment you see:
- Amount and currency
- Event name
- Customer name and email
- Booking reference
- Payment method used
- Transaction ID
- Date submitted
- Time since submission

## Data You Provide
When rejecting a payment, you can optionally add:
- Reason for rejection (max 500 characters)
- This is recorded for audit trail

## Key Features
- ✓ Only see payments for YOUR events
- ✓ Real-time notifications to customers
- ✓ Complete audit trail
- ✓ Bulk filtering available
- ✓ Mobile-friendly interface
- ✓ Fast performance with pagination

## User Experience After Actions
**After Confirming:**
- Customer receives success email
- Can view tickets immediately
- Booking fully confirmed

**After Rejecting:**
- Customer notified of rejection
- Booking cancelled
- Can book again if still available

**After Marking Not Received:**
- No change to payment status
- Can take action later
- Useful for manual offline payments

## Common Workflow
1. Check "Pending Verification"
2. Review payment details
3. Click "Confirm" if amount/method checks out
4. Move to "History" to see all transactions
5. Generate reports as needed

## Tips
- Check payment method and transaction ID for verification
- Add reason when rejecting for customer clarity
- Payment history is permanent record - all actions logged
- Bulk actions coming soon for multiple payment verification
