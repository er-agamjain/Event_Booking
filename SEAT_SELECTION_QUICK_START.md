# Seat Selection - Quick Start Guide

## For End Users

### How to Book Tickets

1. **Go to Event Details**
   - Click on an event to view details
   - Scroll down to "Book Your Tickets" section

2. **Select Show Timing**
   - Choose your preferred date and time from the dropdown
   - Venue information appears next to each timing

3. **View Available Seats**
   - Seat map displays with color-coded seats
   - Green seats = Available
   - Gray seats = Already booked
   - Each seat shows its row and column number

4. **Select Your Seats**
   - Click on any green seat to select it
   - Selected seats get a yellow border
   - Click again to deselect
   - Category and price shown in tooltip when hovering

5. **Review Your Selection**
   - Selected seats appear in the summary below
   - Total price updates automatically
   - Shows price for each individual seat

6. **Confirm and Pay**
   - Click "Confirm Booking" button
   - Proceed to payment page
   - Complete payment to finalize booking

### Seat Categories

Each event may have different seat categories with different prices:

- **VIP Seats** 🌟
  - Premium location and view
  - Highest price
  - Limited availability

- **Gold Seats** 💛
  - Mid-range location
  - Medium price
  - Good availability

- **Silver Seats** ⚪
  - Standard location
  - Base price
  - Most availability

### Tips

✅ **Do:**
- Select multiple seats if booking for a group
- Review your selection before payment
- Screenshot your confirmation
- Check booking history for confirmation

❌ **Don't:**
- Refresh page during seat selection (will reset)
- Select more seats than needed (price increases)
- Leave booking pending too long (seats may be released)

---

## For Administrators/Event Organizers

### Setting Up Seat Categories

1. **Create a Venue**
   - Define total capacity
   - Note the venue name

2. **Add Seat Categories**
   - Name: "VIP", "Gold", "Silver" (or custom)
   - Total Seats: number available in this category
   - Base Price: ticket price for this category
   - Color: visual identifier on seat map
   - Description: optional notes

3. **Create Show Timings**
   - Select venue
   - Set date and time
   - Seats automatically generated

4. **Verify Seat Layout**
   - Preview on event page shows seat map
   - Check colors match categories
   - Confirm pricing is correct

### Pricing Strategy

**Example Setup:**
```
Venue: Grand Theater
Total Capacity: 300 seats

Seat Categories:
├─ VIP: 50 seats @ ₹1000 each
├─ Gold: 100 seats @ ₹500 each
└─ Silver: 150 seats @ ₹250 each
```

**Revenue Example:**
- All VIP: 50 × ₹1000 = ₹50,000
- All Gold: 100 × ₹500 = ₹50,000
- All Silver: 150 × ₹250 = ₹37,500
- **Total Potential Revenue: ₹137,500**

### Monitoring Bookings

1. **View Bookings**
   - Go to Bookings section
   - See all bookings for your events
   - Filter by status (pending, confirmed, cancelled)

2. **Track Revenue**
   - Monitor seat sales by category
   - Track confirmed vs. pending bookings
   - Check payment status

3. **Manage Capacity**
   - View available seats per category
   - Block seats if needed (maintenance, etc.)
   - Adjust pricing for dynamic pricing (future feature)

### Common Operations

**Blocking Seats for Maintenance:**
```
1. Go to Event → Show Timing
2. Click "Manage Seats"
3. Select seats to block
4. Mark as "blocked"
```

**Changing Prices:**
- Edit seat category to update base price
- Affects new bookings only
- Existing bookings retain original price

**Viewing Seat Map:**
- Event page shows seat preview
- Color legend shows all categories
- Availability percentage per category

---

## Technical Details

### Database Structure

**Bookings Table:**
- booking_id (PK)
- user_id (FK to users)
- event_id (FK to events)
- show_timing_id (FK to show_timings)
- total_price (calculated)
- status (pending/confirmed/cancelled)
- payment_status (pending/paid/failed)

**Booking_Seat Table (Pivot):**
- booking_id (FK)
- seat_id (FK)

**Seats Table:**
- seat_id (PK)
- show_timing_id (FK)
- seat_category_id (FK)
- row_number
- column_number
- seat_number (e.g., "A1")
- status (available/reserved/booked/blocked)
- current_price (overrides category price if set)

### API Endpoint

**GET /user/show-timings/{showTiming}/seats**

Returns:
```json
{
  "seats": [
    {
      "category_id": 1,
      "category_name": "VIP",
      "category_color": "#fbbf24",
      "price": "1000.00",
      "seats": [
        {
          "id": 101,
          "seat_number": "A1",
          "row": 1,
          "column": 1,
          "price": "1000.00"
        }
      ]
    }
  ],
  "categories": [...],
  "venue": {...}
}
```

### Seat Selection Workflow

```
User selects show timing
        ↓
API fetches available seats
        ↓
Seat map renders (grouped by row)
        ↓
User clicks seats to select/deselect
        ↓
JavaScript tracks selections in Map
        ↓
Total price recalculated
        ↓
User submits form with seat IDs
        ↓
Backend validates seat availability
        ↓
Booking created
        ↓
Seats linked via booking_seat pivot
        ↓
Seats marked as "reserved"
        ↓
User proceeds to payment
        ↓
After payment → seats marked as "booked"
```

### Price Calculation

```javascript
Total Price = Sum of (each_seat.price)

Where each_seat.price is:
- seat.current_price (if set), OR
- seat_category.base_price (default)
```

---

## Troubleshooting

### Issue: Seats not loading
**Solution:**
- Ensure show timing has venue assigned
- Check venue has seat categories
- Verify seats were created for show timing
- Check browser console for errors

### Issue: Price shows as 0
**Solution:**
- Ensure seat categories have base_price set
- Check if seat has custom price that's null
- Verify decimal fields in database

### Issue: Can't select seats
**Solution:**
- Refresh page and try again
- Verify you're logged in as user
- Check if show timing is still available
- Ensure you have JavaScript enabled

### Issue: Booking fails after seat selection
**Solution:**
- Seats may have been booked by another user
- Try selecting different seats
- Verify all seats are from same show timing
- Check that show timing hasn't been cancelled

---

## Support

For issues or questions:
1. Check browser console (F12) for error messages
2. Verify database has correct data
3. Check user permissions/role
4. Review application logs in `storage/logs/`

