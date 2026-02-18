# Event Booking System - Database Schema Reference

## Overview
MySQL database with 7 main tables supporting the complete event booking system.

---

## Table: `roles`

Stores the three user role types.

| Column | Type | Attributes | Purpose |
|--------|------|-----------|---------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique role identifier |
| `name` | VARCHAR(255) | UNIQUE, NOT NULL | Role name (Admin, Organiser, User) |
| `description` | VARCHAR(255) | NULLABLE | Role description |
| `created_at` | TIMESTAMP | | Record creation time |
| `updated_at` | TIMESTAMP | | Last update time |

### Sample Data
```sql
INSERT INTO roles VALUES 
(1, 'Admin', 'Administrator', ...),
(2, 'Organiser', 'Event Organiser', ...),
(3, 'User', 'Regular User', ...);
```

---

## Table: `users`

Core user table extended for roles and authentication.

| Column | Type | Attributes | Purpose |
|--------|------|-----------|---------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | User identifier |
| `name` | VARCHAR(255) | NOT NULL | Full name |
| `email` | VARCHAR(255) | UNIQUE, NOT NULL | Email address |
| `email_verified_at` | TIMESTAMP | NULLABLE | Email verification timestamp |
| `password` | VARCHAR(255) | NOT NULL | Hashed password |
| `phone` | VARCHAR(255) | NULLABLE | Phone number |
| `role_id` | BIGINT | NOT NULL, FK | Reference to roles table |
| `is_active` | BOOLEAN | DEFAULT 1 | Account active status |
| `commission_rate` | DECIMAL(5,2) | DEFAULT 0 | Organiser commission percentage |
| `google_id` | VARCHAR(255) | UNIQUE, NULLABLE | Google OAuth ID |
| `google_token` | VARCHAR(255) | NULLABLE | Google OAuth token |
| `remember_token` | VARCHAR(100) | NULLABLE | "Remember me" token |
| `created_at` | TIMESTAMP | | Account creation time |
| `updated_at` | TIMESTAMP | | Last update time |

### Indexes
- `role_id` - Indexed for role-based queries
- `email` - Unique indexed

### Constraints
- Foreign Key: `role_id` → `roles.id` (ON DELETE CASCADE)

---

## Table: `events`

Event details managed by organisers.

| Column | Type | Attributes | Purpose |
|--------|------|-----------|---------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Event identifier |
| `organiser_id` | BIGINT | NOT NULL, FK | Organiser user ID |
| `name` | VARCHAR(255) | NOT NULL | Event name |
| `description` | TEXT | NULLABLE | Event description |
| `location` | VARCHAR(255) | NOT NULL | Event location |
| `event_date` | DATETIME | NOT NULL | Date and time of event |
| `event_time` | TIME | NOT NULL | Specific event time |
| `capacity` | INT | NOT NULL | Total ticket capacity |
| `status` | ENUM | DEFAULT 'draft' | draft, published, cancelled |
| `image` | VARCHAR(255) | NULLABLE | Event image path |
| `category` | VARCHAR(255) | NULLABLE | Event category |
| `created_at` | TIMESTAMP | | Event creation time |
| `updated_at` | TIMESTAMP | | Last update time |

### Indexes
- `organiser_id` - Indexed for organiser queries
- `event_date` - Indexed for date-based queries

### Constraints
- Foreign Key: `organiser_id` → `users.id` (ON DELETE CASCADE)

### Sample Query
```sql
-- Find upcoming published events
SELECT * FROM events 
WHERE status = 'published' 
AND event_date > NOW()
ORDER BY event_date ASC;
```

---

## Table: `tickets`

Ticket types for each event (free or paid).

| Column | Type | Attributes | Purpose |
|--------|------|-----------|---------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Ticket identifier |
| `event_id` | BIGINT | NOT NULL, FK | Reference to events table |
| `name` | VARCHAR(255) | NOT NULL | Ticket type name |
| `description` | TEXT | NULLABLE | Ticket description |
| `price` | DECIMAL(10,2) | DEFAULT 0 | Ticket price |
| `quantity` | INT | NOT NULL | Total tickets available |
| `quantity_sold` | INT | DEFAULT 0 | Tickets already sold |
| `ticket_type` | ENUM | DEFAULT 'free' | free or paid |
| `created_at` | TIMESTAMP | | Ticket creation time |
| `updated_at` | TIMESTAMP | | Last update time |

### Indexes
- `event_id` - Indexed for event queries

### Constraints
- Foreign Key: `event_id` → `events.id` (ON DELETE CASCADE)

### Availability Calculation
```
Available = quantity - quantity_sold
```

### Sample Query
```sql
-- Get available tickets for an event
SELECT * FROM tickets 
WHERE event_id = 1 
AND (quantity - quantity_sold) > 0;
```

---

## Table: `bookings`

User ticket bookings.

| Column | Type | Attributes | Purpose |
|--------|------|-----------|---------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Booking identifier |
| `user_id` | BIGINT | NOT NULL, FK | Booking user ID |
| `event_id` | BIGINT | NOT NULL, FK | Event booked |
| `ticket_id` | BIGINT | NOT NULL, FK | Ticket type booked |
| `quantity` | INT | NOT NULL | Number of tickets |
| `total_price` | DECIMAL(10,2) | NOT NULL | Total booking price |
| `booking_reference` | VARCHAR(255) | UNIQUE, NOT NULL | Auto-generated reference (BK...) |
| `status` | ENUM | DEFAULT 'pending' | pending, confirmed, cancelled |
| `payment_status` | ENUM | DEFAULT 'pending' | pending, paid, failed |
| `created_at` | TIMESTAMP | | Booking creation time |
| `updated_at` | TIMESTAMP | | Last update time |

### Indexes
- `user_id` - Indexed for user queries
- `event_id` - Indexed for event queries
- `booking_reference` - Unique indexed for lookups

### Constraints
- Foreign Key: `user_id` → `users.id` (ON DELETE CASCADE)
- Foreign Key: `event_id` → `events.id` (ON DELETE CASCADE)
- Foreign Key: `ticket_id` → `tickets.id` (ON DELETE CASCADE)

### Auto-Generated Reference
```
Format: BK<unique_id> (e.g., BK5F3A7E2C)
Generated on booking creation
```

### Sample Query
```sql
-- Get user's confirmed bookings
SELECT b.*, e.name, t.name as ticket_name
FROM bookings b
JOIN events e ON b.event_id = e.id
JOIN tickets t ON b.ticket_id = t.id
WHERE b.user_id = ? 
AND b.status = 'confirmed'
ORDER BY b.created_at DESC;
```

---

## Table: `payments`

Payment transaction tracking.

| Column | Type | Attributes | Purpose |
|--------|------|-----------|---------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Payment identifier |
| `booking_id` | BIGINT | NOT NULL, FK | Associated booking |
| `user_id` | BIGINT | NOT NULL, FK | User making payment |
| `amount` | DECIMAL(10,2) | NOT NULL | Payment amount |
| `payment_method` | VARCHAR(50) | NOT NULL | stripe, paypal, etc. |
| `transaction_id` | VARCHAR(255) | UNIQUE, NULLABLE | External transaction ID |
| `status` | ENUM | DEFAULT 'pending' | pending, success, failed, refunded |
| `payment_date` | DATETIME | NULLABLE | When payment was processed |
| `created_at` | TIMESTAMP | | Record creation time |
| `updated_at` | TIMESTAMP | | Last update time |

### Indexes
- `user_id` - Indexed for user queries
- `booking_id` - Indexed for booking queries

### Constraints
- Foreign Key: `booking_id` → `bookings.id` (ON DELETE CASCADE)
- Foreign Key: `user_id` → `users.id` (ON DELETE CASCADE)

### Sample Query
```sql
-- Get user's successful payments
SELECT * FROM payments
WHERE user_id = ?
AND status = 'success'
ORDER BY payment_date DESC;

-- Calculate total revenue (admin)
SELECT SUM(amount) as total_revenue
FROM payments
WHERE status = 'success'
AND payment_date BETWEEN '2026-01-01' AND '2026-12-31';
```

---

## Table: `commissions`

Commission tracking for organisers.

| Column | Type | Attributes | Purpose |
|--------|------|-----------|---------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Commission record ID |
| `organiser_id` | BIGINT | NOT NULL, FK | Organiser user ID |
| `booking_id` | BIGINT | NOT NULL, FK | Associated booking |
| `commission_rate` | DECIMAL(5,2) | NOT NULL | Commission percentage |
| `commission_amount` | DECIMAL(10,2) | NOT NULL | Calculated commission amount |
| `status` | ENUM | DEFAULT 'pending' | pending, paid, cancelled |
| `created_at` | TIMESTAMP | | Record creation time |
| `updated_at` | TIMESTAMP | | Last update time |

### Indexes
- `organiser_id` - Indexed for organiser queries
- `booking_id` - Indexed for booking queries

### Constraints
- Foreign Key: `organiser_id` → `users.id` (ON DELETE CASCADE)
- Foreign Key: `booking_id` → `bookings.id` (ON DELETE CASCADE)

### Commission Calculation
```
commission_amount = booking.total_price * (commission_rate / 100)
```

### Sample Query
```sql
-- Get pending commissions for an organiser
SELECT SUM(commission_amount) as pending_amount
FROM commissions
WHERE organiser_id = ?
AND status = 'pending';

-- Get paid out commissions for analytics
SELECT SUM(commission_amount) as total_paid
FROM commissions
WHERE organiser_id = ?
AND status = 'paid';
```

---

## Table: `password_reset_tokens`

Laravel's password reset tokens.

| Column | Type | Attributes | Purpose |
|--------|------|-----------|---------|
| `email` | VARCHAR(255) | PRIMARY KEY | User email |
| `token` | VARCHAR(255) | NOT NULL | Reset token |
| `created_at` | TIMESTAMP | NULLABLE | Token creation time |

---

## Table: `sessions`

Laravel session storage.

| Column | Type | Attributes | Purpose |
|--------|------|-----------|---------|
| `id` | VARCHAR(255) | PRIMARY KEY | Session ID |
| `user_id` | BIGINT | NULLABLE, INDEX | Associated user |
| `ip_address` | VARCHAR(45) | NULLABLE | Client IP |
| `user_agent` | TEXT | NULLABLE | Browser/client info |
| `payload` | LONGTEXT | NOT NULL | Session data |
| `last_activity` | INT | INDEX | Last activity timestamp |

---

## Relationships Diagram

```
roles (1) ──── (many) users
                  │
                  ├─── (1) ──── (many) events ──── (many) tickets ──── (many) bookings
                  │                                                         │
                  │                                                         ├─ payments
                  │                                                         └─ commissions
                  │
                  └─── (many) bookings
                         │
                         └─ payments
                         └─ commissions ──── organiser (users)
```

---

## Useful SQL Queries

### Total Revenue for Date Range
```sql
SELECT SUM(p.amount) as total_revenue
FROM payments p
WHERE p.status = 'success'
AND DATE(p.payment_date) BETWEEN '2026-01-01' AND '2026-01-31';
```

### Organiser Commission Owed
```sql
SELECT u.name, SUM(c.commission_amount) as owed
FROM commissions c
JOIN users u ON c.organiser_id = u.id
WHERE c.status = 'pending'
GROUP BY c.organiser_id
ORDER BY owed DESC;
```

### Event Performance
```sql
SELECT e.name, COUNT(b.id) as bookings, SUM(b.total_price) as revenue
FROM events e
LEFT JOIN bookings b ON e.id = b.event_id
GROUP BY e.id
ORDER BY revenue DESC;
```

### Upcoming Events
```sql
SELECT e.name, e.event_date, COUNT(t.id) as ticket_types
FROM events e
LEFT JOIN tickets t ON e.id = t.id
WHERE e.status = 'published'
AND e.event_date > NOW()
ORDER BY e.event_date ASC;
```

### User Booking History
```sql
SELECT b.booking_reference, e.name, b.quantity, b.total_price, b.status
FROM bookings b
JOIN events e ON b.event_id = e.id
WHERE b.user_id = ?
ORDER BY b.created_at DESC;
```

---

## Data Type Reference

- `BIGINT` - Large integers (IDs, auto-increment)
- `VARCHAR(n)` - Text up to n characters
- `TEXT/LONGTEXT` - Large text blocks
- `INT` - Regular integers
- `DECIMAL(10,2)` - Numbers with 2 decimal places (money)
- `ENUM` - Fixed list of values
- `BOOLEAN` - True/False (stored as TINYINT)
- `DATETIME` - Date and time
- `TIME` - Time only
- `DATE` - Date only
- `TIMESTAMP` - Automatic timestamp management

---

## Database Performance Tips

1. **Indexes**: All foreign keys and frequently searched columns are indexed
2. **Pagination**: Always use pagination on listing endpoints
3. **Eager Loading**: Use `with()` in Laravel to avoid N+1 queries
4. **Soft Deletes**: Consider implementing soft deletes for audit trails
5. **Caching**: Cache event listings and popular searches

---

All tables are properly normalized with appropriate foreign keys and indexes for optimal performance!
