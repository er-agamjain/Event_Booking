# Event Booking System - Quick Start Guide

## 🚀 Get Started in 5 Minutes

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 5.7+
- Node.js 16+

### Step 1: Install Dependencies (1 min)
```bash
cd e:/Event_Booking
composer install
npm install
```

### Step 2: Setup Environment (1 min)
```bash
# Already configured, but verify .env has:
php artisan key:generate

# Check .env settings:
DB_CONNECTION=mysql
DB_DATABASE=event_booking
DB_USERNAME=root
DB_PASSWORD=  # your MySQL password
```

### Step 3: Database Setup (2 min)
```bash
php artisan migrate --seed
php artisan storage:link
```

### Step 4: Start Servers (1 min)
```bash
# Terminal 1 - PHP Server
php artisan serve

# Terminal 2 - Frontend
npm run dev
```

### Step 5: Access Application
- **URL**: http://localhost:8000
- Open in browser and you're done! ✅

---

## 🔐 Test Credentials

After running seeders, use these to login:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@example.com | password |
| **Organiser** | organiser@example.com | password |
| **User** | user@example.com | password |

---

## 🎯 Try These Actions

### As User (user@example.com)
1. Login to http://localhost:8000
2. Browse Events → Click any event
3. Click "Book Now" on a ticket
4. Confirm booking
5. Download ticket as PDF
6. View in "My Bookings"

### As Organiser (organiser@example.com)
1. Login as organiser
2. Click "My Events" (organiser menu)
3. Click "Create Event"
4. Fill event form and create
5. Add free and paid tickets
6. View bookings coming from users

### As Admin (admin@example.com)
1. Login as admin
2. Access "/admin/dashboard"
3. View all users, events, bookings
4. Manage organisers
5. View transactions

---

## 📁 Key Files to Know

```
app/Models/                    # Database models
├── User.php
├── Event.php
├── Booking.php
└── ... (more models)

app/Http/Controllers/          # Business logic
├── User/
├── Organiser/
├── Admin/
└── Auth/

routes/web.php                # All routes (fully documented)

resources/views/               # UI templates
├── user/                      # User pages
├── organiser/                 # Organiser pages
├── admin/                      # Admin pages
└── auth/                       # Auth pages

database/migrations/           # Database structure
database/seeders/              # Test data

.env                          # Configuration (already setup)
```

---

## 🔍 File Locations

### Database Schema
- **Roles**: Admin, Organiser, User
- **Users**: Extended with roles, Google OAuth, commission
- **Events**: Created by organisers
- **Tickets**: Free or Paid tickets per event
- **Bookings**: User bookings with auto-generated reference
- **Payments**: Payment tracking
- **Commissions**: Organiser commissions

See [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) for full details.

---

## 📚 Routes

### User Routes
```
GET  /user/events              # Browse all events
GET  /user/events/{id}         # View event details
POST /user/bookings/{ticket}   # Book a ticket
GET  /user/bookings/history    # View my bookings
GET  /user/tickets/{id}/download  # Download PDF
```

### Organiser Routes
```
GET  /organiser/events         # My events
POST /organiser/events         # Create event
GET  /organiser/events/{id}    # Event details
POST /organiser/events/{id}/tickets  # Add ticket
GET  /organiser/bookings       # View bookings
```

### Admin Routes
```
GET  /admin/dashboard          # Dashboard
GET  /admin/organisers         # Manage organisers
GET  /admin/users              # All users
GET  /admin/events             # All events
GET  /admin/bookings           # All bookings
```

See [ROUTES_REFERENCE.md](ROUTES_REFERENCE.md) for complete route list with examples.

---

## 🛠️ Customization

### Change Database
Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=your_host
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

Then run:
```bash
php artisan migrate --seed
```

### Add Google OAuth
1. Get credentials from [Google Cloud Console](https://console.cloud.google.com)
2. Add to `.env`:
```
GOOGLE_CLIENT_ID=your_id
GOOGLE_CLIENT_SECRET=your_secret
```
3. Update `config/services.php`
4. Test login button

### Setup Payments
Update `.env` with Stripe/PayPal keys, then configure in:
- `app/Http/Controllers/User/PaymentController.php`

### Send Emails
Configure mail in `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

---

## 🐛 Troubleshooting

### "Cannot connect to database"
- Verify MySQL is running
- Check DB credentials in .env
- Ensure database exists: `CREATE DATABASE event_booking;`

### "No such file or directory" on storage
```bash
php artisan storage:link
```

### "Token mismatch" on form submission
- Clear cache: `php artisan cache:clear`
- Clear cookies in browser

### Port 8000 already in use
```bash
php artisan serve --port=8001
```

### npm run dev not working
```bash
npm install
npm run dev
```

---

## 📊 Database Size

After seeding, you'll have:
- 3 roles
- 3 test users (1 admin, 1 organiser, 1 user)
- Ready for data insertion

---

## 🔄 Reset Everything

Start fresh:
```bash
# Reset database
php artisan migrate:reset
php artisan migrate --seed

# Clear cache
php artisan cache:clear
php artisan config:clear

# Restart servers
# Kill terminal windows and restart
php artisan serve
npm run dev
```

---

## 📝 Features Checklist

**All Implemented ✅**

### User Features
- [x] Registration & Login
- [x] Google OAuth (ready)
- [x] Browse Events
- [x] Search/Filter Events
- [x] Book Tickets (Free & Paid)
- [x] Payment Processing (ready)
- [x] Download Tickets as PDF
- [x] View Booking History
- [x] View Payment History

### Organiser Features
- [x] Create Events
- [x] Manage Tickets
- [x] View Bookings
- [x] Edit/Delete Events
- [x] Commission Tracking (ready)

### Admin Features
- [x] Manage Organisers
- [x] View All Users
- [x] View All Events
- [x] View All Bookings
- [x] View Transactions
- [x] Commission Management
- [x] Dashboard Analytics

---

## 💡 Pro Tips

1. **Sort by newest**: Most routes support pagination
2. **Search events**: Use the search bar on events page
3. **Multiple bookings**: Users can book multiple times
4. **Check admin dashboard**: See system metrics
5. **View raw SQL**: Check `database/migrations/` folder
6. **Test error handling**: Try invalid inputs

---

## 📞 Need Help?

1. Check the [SETUP_GUIDE.md](SETUP_GUIDE.md) for detailed instructions
2. See [ROUTES_REFERENCE.md](ROUTES_REFERENCE.md) for all API endpoints
3. Read [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) for database structure
4. Review [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md) for features
5. Check [COMPLETION_CHECKLIST.md](COMPLETION_CHECKLIST.md) for what's done

---

## 🎉 You're All Set!

The system is **100% functional** and ready to use.

**Next Step**: Login and start testing!

```bash
# Make sure you're running:
php artisan serve      # Terminal 1
npm run dev            # Terminal 2

# Then visit:
http://localhost:8000
```

Happy booking! 🎫✨
