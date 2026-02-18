# Event Booking System

A comprehensive Laravel-based event booking platform with role-based access control for Admin, Event Organisers, and Users.

## Features

### Admin Capabilities
- Create and manage organisers
- Activate/Deactivate organiser accounts
- View all users and transactions
- Manage commission rates for organisers
- Approve/Reject events
- Create manual tickets for events
- View booking history with filters
- Monitor system revenue and metrics

### Organiser Capabilities
- Create and manage events
- Add and manage event tickets (Free & Paid)
- View and manage bookings for their events
- Track booking history
- Monitor event status

### User Capabilities
- User registration and login with Google OAuth
- Browse and search events by category, location, date
- Book free and paid tickets
- Process payments (Stripe/PayPal integration ready)
- Download tickets as PDF
- View booking and payment history
- Receive booking confirmation emails

## Database Models

- **Role**: Admin, Organiser, User
- **User**: Stores user information with role assignment
- **Event**: Event details managed by organisers
- **Ticket**: Ticket types for events (Free/Paid)
- **Booking**: User ticket bookings
- **Payment**: Payment transactions
- **Commission**: Commission tracking for organisers

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 5.7+
- Node.js & npm

### Installation Steps

1. **Clone the repository**
```bash
cd e:/Event_Booking
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Configure environment**
```bash
cp .env.example .env
# Update database credentials in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_booking
DB_USERNAME=root
DB_PASSWORD=
```

4. **Generate application key**
```bash
php artisan key:generate
```

5. **Run migrations and seed database**
```bash
php artisan migrate --seed
```

This creates:
- Admin user: admin@example.com / password
- Organiser user: organiser@example.com / password
- Regular user: user@example.com / password

6. **Setup storage linking**
```bash
php artisan storage:link
```

7. **Start development server**
```bash
php artisan serve
```

8. **Compile frontend assets (in another terminal)**
```bash
npm run dev
```

### Access the application
- **URL**: http://localhost:8000
- **Admin Dashboard**: http://localhost:8000/admin/dashboard
- **Organiser Dashboard**: http://localhost:8000/organiser/events
- **User Portal**: http://localhost:8000/user/events

## Package Requirements

The system uses these key Laravel packages:
- `laravel/socialite` - Google OAuth integration
- `barryvdh/laravel-dompdf` - PDF ticket generation
- `stripe/stripe-php` - Payment processing (when configured)

## Install Additional Packages

```bash
# For Google OAuth
composer require laravel/socialite

# For PDF generation
composer require barryvdh/laravel-dompdf

# For Stripe payments (optional)
composer require stripe/stripe-php
```

## Configuration

### Google OAuth Setup
1. Create Google OAuth credentials at Google Cloud Console
2. Add credentials to `.env`:
```
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
```

3. Update `config/services.php` with Google credentials

### Payment Gateway Setup
Configure Stripe or PayPal credentials in `.env` and implement in PaymentController

### Email Configuration
Update mail settings in `.env` for booking confirmation emails

## Default Test Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Organiser | organiser@example.com | password |
| User | user@example.com | password |

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Organiser/
│   │   ├── User/
│   │   └── Auth/
│   └── Middleware/
├── Models/
│   ├── User.php
│   ├── Role.php
│   ├── Event.php
│   ├── Ticket.php
│   ├── Booking.php
│   ├── Payment.php
│   └── Commission.php
└── Mail/
    ├── BookingConfirmation.php
    └── TicketMail.php

database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_events_table.php
│   ├── create_tickets_table.php
│   ├── create_bookings_table.php
│   ├── create_payments_table.php
│   └── create_commissions_table.php
└── seeders/
    └── DatabaseSeeder.php

resources/
└── views/
    ├── auth/
    ├── user/
    ├── organiser/
    ├── admin/
    ├── layouts/
    ├── emails/
    └── tickets/

routes/
└── web.php
```

## Key Routes

### Authentication
- `GET /register` - Registration form
- `POST /register` - Register user
- `GET /auth/google` - Google login redirect
- `GET /auth/google/callback` - Google callback

### User Routes
- `GET /user/events` - Browse events
- `GET /user/events/{event}` - View event details
- `POST /user/bookings/{ticket}` - Book ticket
- `GET /user/bookings/history` - View booking history
- `GET /user/payments` - View payment history
- `GET /user/tickets/{booking}/download` - Download ticket PDF

### Organiser Routes
- `GET /organiser/events` - View my events
- `POST /organiser/events` - Create event
- `GET /organiser/events/{event}` - View event details
- `POST /organiser/events/{event}/tickets` - Create ticket
- `GET /organiser/bookings` - View event bookings

### Admin Routes
- `GET /admin/dashboard` - Dashboard
- `GET /admin/organisers` - Manage organisers
- `GET /admin/users` - View users
- `GET /admin/events` - Manage events
- `GET /admin/transactions` - View transactions
- `GET /admin/bookings` - View bookings

## Notes

- PDF ticket generation is ready to use with Laravel DomPDF
- Payment integration is templated for Stripe and PayPal
- Email notifications are configured but require proper mail setup
- All user inputs are validated and sanitized
- Role-based middleware ensures proper access control
- Organiser can only be deactivated by Admin

## Future Enhancements

- Implement full Stripe payment integration
- Add PayPal payment gateway
- Complete email notification system
- Admin commission management dashboard
- Event analytics and reporting
- Refund management system
- Venue management
- Seating arrangements
- Advanced search filters
- Review and ratings system

## Support

For issues or questions, please contact the development team.

## License

This project is proprietary and confidential.
