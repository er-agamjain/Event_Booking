# Interactive Seat Selection & Dynamic Pricing Implementation

## Overview
The system now includes an interactive seat selection interface with dynamic pricing based on seat categories (VIP, Gold, Silver, etc.).

## Features Implemented

### 1. **Interactive Seat Map Component**
- Users can select seats directly from a visual seat map on the event details page
- Seats are color-coded by category
- Real-time price updates based on selected seats
- Seats display row and column numbers for easy identification
- Legend shows available, booked, and selected seats

### 2. **Dynamic Pricing Based on Seat Category**
- Each seat category has its own base price (VIP, Gold, Silver)
- Total booking price is calculated based on the sum of selected seat prices
- Prices are fetched from the database and updated in real-time

### 3. **Seat Selection Workflow**
1. User selects an event and show timing
2. Available seats are fetched via AJAX (grouped by category)
3. User clicks seats to select/deselect
4. Selected seats are displayed with their individual prices
5. Total price is calculated and displayed
6. User confirms booking and proceeds to payment

### 4. **Database Changes**
- **New Table**: `booking_seat` - Pivot table linking bookings to selected seats
- **Modified Table**: `bookings` - Added `show_timing_id` column to link bookings to specific show timings

### 5. **API Endpoint**
- **Route**: `GET /user/show-timings/{showTiming}/seats`
- **Returns**: JSON object with available seats grouped by category, including pricing information

## File Structure

```
resources/views/
├── user/
│   └── events/
│       ├── show.blade.php (Updated - now includes seat selector)
│       └── partials/
│           └── seat-selector.blade.php (NEW - interactive component)
│   └── bookings/
│       ├── history.blade.php (Updated - displays seat info)
│       └── show.blade.php (Updated - displays booked seats)

app/Http/Controllers/User/
├── BookingController.php (Updated)
│   ├── getSeats() - NEW - returns available seats for a show timing
│   └── store() - Updated - handles seat-based bookings

app/Models/
├── Booking.php (Updated)
│   └── seats() - NEW - many-to-many relationship with Seat model
│   └── showTiming() - NEW - belongs-to relationship with ShowTiming
├── Seat.php (Existing)
└── ShowTiming.php (Existing)

database/migrations/
└── 2026_01_21_000000_add_seats_to_bookings.php (NEW)
```

## How It Works

### Booking Flow
1. **User selects show timing**: JavaScript fetches available seats for that timing
2. **Seat Map Renders**: Seats are grouped by row and displayed with category colors
3. **User clicks seats**: Selected seats are tracked in a Map object
4. **Real-time calculation**: Total price updates as seats are added/removed
5. **Booking confirmation**: Form is submitted with seat IDs and show_timing_id
6. **Backend validation**: 
   - Verifies all selected seats are available and belong to the correct show timing
   - Calculates total price based on seat categories
   - Reserves seats (status = 'reserved')
   - Creates booking with linked seats

### Seat Status Lifecycle
- `available` → User can select
- `reserved` → Seats held during payment process (temporary)
- `booked` → Seats confirmed after payment
- `blocked` → Admin-blocked seats (cannot be selected)

## Frontend Components

### Seat Selector HTML
- Show timing dropdown
- Seat grid with interactive buttons
- Category price display
- Selected seats summary
- Booking form

### JavaScript Functionality
- Fetches seats from API endpoint
- Manages selected seats in a Map object
- Renders seat grid dynamically
- Calculates and updates total price
- Validates before form submission
- Provides visual feedback for selection

### Styling
- Color-coded seats by category
- Hover effects for available seats
- Selected seats have border highlight
- Responsive grid layout
- Mobile-friendly interface

## Database Schema

### booking_seat (Pivot Table)
```sql
CREATE TABLE booking_seat (
    id BIGINT PRIMARY KEY,
    booking_id BIGINT (Foreign Key → bookings.id),
    seat_id BIGINT (Foreign Key → seats.id),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE(booking_id, seat_id)
);
```

### Modified bookings Table
```sql
ALTER TABLE bookings ADD COLUMN show_timing_id BIGINT (after event_id);
ALTER TABLE bookings ADD FOREIGN KEY (show_timing_id) REFERENCES show_timings(id);
```

## API Response Example

```json
{
  "seats": [
    {
      "category_id": 1,
      "category_name": "VIP",
      "category_color": "#fbbf24",
      "price": "500.00",
      "seats": [
        {
          "id": 1,
          "seat_number": "A1",
          "row": 1,
          "column": 1,
          "price": "500.00"
        }
      ]
    }
  ],
  "categories": [
    {
      "id": 1,
      "name": "VIP",
      "color": "#fbbf24",
      "base_price": "500.00"
    }
  ],
  "venue": {
    "id": 1,
    "name": "Grand Theater"
  }
}
```

## Usage Instructions for Users

1. **Browse Events**: Navigate to the event details page
2. **Select Show Timing**: Choose desired date/time from dropdown
3. **View Available Seats**: Seat map displays with available seats highlighted
4. **Select Seats**: Click on seats to select (click again to deselect)
5. **Review Selection**: Selected seats and total price shown below seat map
6. **Confirm Booking**: Click "Confirm Booking" button
7. **Proceed to Payment**: Complete payment to finalize booking

## Validation & Error Handling

- **No seats selected**: Form submission prevented with alert
- **Seats become unavailable**: Error message displayed if selected seats are booked between selection and confirmation
- **Invalid show timing**: Request rejected with 404
- **Unauthorized access**: Booking details accessible only to booking owner

## Future Enhancements

- Seat hold timer (temporary reservation expires after X minutes)
- Group booking recommendations
- Seat map customization for organizers
- Bulk seat blocking/pricing
- Seat accessibility indicators
- Seat selection undo/redo

## Testing

### Manual Testing Steps
1. Create an event with show timings and venue
2. Set up seats with multiple categories (VIP, Gold, Silver)
3. Navigate to event details page
4. Select a show timing
5. Verify seat map loads correctly with category colors
6. Select seats and verify price calculation
7. Proceed with booking and verify seats are linked
8. Check booking history to see selected seats

### Expected Behavior
- Seats properly color-coded by category ✓
- Prices update dynamically as seats are selected ✓
- Selected seats persist and display with borders ✓
- Total price calculated correctly ✓
- Booking created with linked seats ✓
- Reserved seats prevent double-booking ✓
