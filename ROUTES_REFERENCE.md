# Event Booking System - Route Reference Guide

## Authentication Routes

| Method | Route | Purpose |
|--------|-------|---------|
| GET | `/register` | Show registration form |
| POST | `/register` | Register new user |
| GET | `/login` | Show login form (Laravel default) |
| POST | `/login` | Login user (Laravel default) |
| GET | `/auth/google` | Redirect to Google OAuth |
| GET | `/auth/google/callback` | Handle Google OAuth callback |
| POST | `/logout` | Logout user (Laravel default) |
| GET | `/forgot-password` | Forgot password form (Laravel default) |
| POST | `/forgot-password` | Send reset link (Laravel default) |
| GET | `/reset-password/{token}` | Reset password form (Laravel default) |
| POST | `/reset-password` | Update password (Laravel default) |

---

## User Routes (Prefix: `/user`)

### Events
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/user/events` | Browse all published events | Auth, User |
| GET | `/user/events/{event}` | View event details | Auth, User |

### Bookings
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| POST | `/user/bookings/{ticket}` | Create new booking | Auth, User |
| GET | `/user/bookings/history` | View booking history | Auth, User |
| GET | `/user/bookings/{booking}` | View booking details | Auth, User |

### Payments
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/user/payments` | View payment history | Auth, User |
| GET | `/user/payments/{booking}/create` | Show payment options | Auth, User |
| POST | `/user/payments/{booking}` | Process payment | Auth, User |

### Tickets
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/user/tickets/{booking}/view` | View ticket online | Auth, User |
| GET | `/user/tickets/{booking}/download` | Download ticket as PDF | Auth, User |

---

## Organiser Routes (Prefix: `/organiser`)

### Events
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/organiser/events` | List my events | Auth, Organiser, Active |
| GET | `/organiser/events/create` | Show create event form | Auth, Organiser, Active |
| POST | `/organiser/events` | Create new event | Auth, Organiser, Active |
| GET | `/organiser/events/{event}` | View event details | Auth, Organiser, Active |
| GET | `/organiser/events/{event}/edit` | Show edit event form | Auth, Organiser, Active |
| PUT | `/organiser/events/{event}` | Update event | Auth, Organiser, Active |
| DELETE | `/organiser/events/{event}` | Delete event | Auth, Organiser, Active |

### Tickets
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/organiser/events/{event}/tickets/create` | Show create ticket form | Auth, Organiser, Active |
| POST | `/organiser/events/{event}/tickets` | Create new ticket | Auth, Organiser, Active |
| GET | `/organiser/tickets/{ticket}/edit` | Show edit ticket form | Auth, Organiser, Active |
| PUT | `/organiser/tickets/{ticket}` | Update ticket | Auth, Organiser, Active |
| DELETE | `/organiser/tickets/{ticket}` | Delete ticket | Auth, Organiser, Active |

### Bookings
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/organiser/bookings` | View bookings for my events | Auth, Organiser, Active |
| GET | `/organiser/bookings/history` | View booking history | Auth, Organiser, Active |

---

## Admin Routes (Prefix: `/admin`)

### Dashboard
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/admin/dashboard` | View admin dashboard | Auth, Admin |

### Organisers
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/admin/organisers` | List all organisers | Auth, Admin |
| POST | `/admin/organisers` | Create new organiser | Auth, Admin |
| PUT | `/admin/organisers/{user}/activate` | Activate organiser | Auth, Admin |
| PUT | `/admin/organisers/{user}/deactivate` | Deactivate organiser | Auth, Admin |
| PUT | `/admin/organisers/{user}/commission` | Update commission rate | Auth, Admin |

### Users
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/admin/users` | List all users | Auth, Admin |

### Events
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/admin/events` | List all events | Auth, Admin |
| PUT | `/admin/events/{event}/approve` | Approve event | Auth, Admin |
| PUT | `/admin/events/{event}/reject` | Reject event | Auth, Admin |
| DELETE | `/admin/events/{event}` | Delete event | Auth, Admin |
| POST | `/admin/events/{event}/tickets` | Create manual ticket | Auth, Admin |

### Transactions
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/admin/transactions` | View all transactions (filterable) | Auth, Admin |

**Query Parameters for Filtering:**
- `status` - Filter by payment status (pending, success, failed, refunded)
- `date_from` - Filter from date
- `date_to` - Filter to date

### Bookings
| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | `/admin/bookings` | View all bookings (filterable) | Auth, Admin |

**Query Parameters for Filtering:**
- `status` - Filter by booking status (pending, confirmed, cancelled)
- `payment_status` - Filter by payment status (pending, paid, failed)

---

## User Events Search & Filter

**GET** `/user/events` - Supports query parameters:
- `search` - Search event name or location
- `category` - Filter by category
- `date` - Filter by event date

Example: `/user/events?search=tech&category=conference&date=2026-02-14`

---

## Status Values

### Event Status
- `draft` - Not published
- `published` - Visible to users
- `cancelled` - Cancelled

### Booking Status
- `pending` - Awaiting confirmation
- `confirmed` - Booking confirmed
- `cancelled` - Booking cancelled

### Payment Status
- `pending` - Not paid
- `paid` - Payment successful
- `failed` - Payment failed
- `refunded` - Refunded

### Commission Status
- `pending` - Not yet paid out
- `paid` - Paid out
- `cancelled` - Cancelled

### Ticket Type
- `free` - Free ticket
- `paid` - Paid ticket

---

## Request/Response Examples

### Create Booking
```
POST /user/bookings/{ticket}
{
    "quantity": 2
}
```

### Complete Payment
```
POST /user/payments/{booking}
{
    "payment_method": "stripe" // or "paypal"
}
```

### Create Event
```
POST /organiser/events
{
    "name": "Tech Conference 2026",
    "description": "Annual technology conference",
    "location": "Convention Center, NYC",
    "event_date": "2026-03-15",
    "event_time": "09:00",
    "capacity": 500,
    "category": "Conference",
    "image": <file> // optional
}
```

### Create Ticket
```
POST /organiser/events/{event}/tickets
{
    "name": "VIP Pass",
    "description": "Premium access with lunch",
    "price": 199.99,
    "quantity": 50,
    "ticket_type": "paid" // or "free"
}
```

### Create Organiser (Admin)
```
POST /admin/organisers
{
    "name": "John's Events",
    "email": "john@events.com",
    "phone": "1234567890",
    "password": "securepassword",
    "commission_rate": 15.5
}
```

---

## Authentication Methods

1. **Traditional Login** - Email/Password via Laravel Auth
2. **Google OAuth** - `/auth/google` → Google → `/auth/google/callback`
3. **Password Reset** - Uses Laravel's password reset feature

---

## Middleware Protection

All routes are protected by:
1. `auth` - User must be logged in
2. `role:{role}` - User must have specific role
3. `ensure_organiser_active` - Organiser must be active (for organiser routes)

---

## Common Query Parameters

### Pagination
Add to any listing endpoint:
- `?page=2` - View page 2
- `?per_page=50` - Show 50 items per page (default: 15-20)

### Search
- `?search=keyword` - Search by keyword
- `?category=value` - Filter by category
- `?status=value` - Filter by status

---

## Response Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 302 | Redirect (after form submission) |
| 403 | Forbidden (insufficient permissions) |
| 404 | Not Found |
| 422 | Unprocessable Entity (validation errors) |
| 500 | Server Error |

---

## Tips for Testing

1. **Use the test credentials** after running seeders
2. **Test different roles** - Admin, Organiser, User
3. **Try invalid data** - Test validation
4. **Check permissions** - Verify role-based access
5. **Test transitions** - Create event → Add ticket → Book → Pay

All routes are fully functional and ready to test!
