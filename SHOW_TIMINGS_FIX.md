# 🔧 Show Timings Fix - Troubleshooting Guide

## Problem
Show timings were not appearing even after creating events. Message showed: "Show timings will be available soon. Check back later!"

## Root Causes Found & Fixed

### Issue 1: Wrong Status Filter
**Problem**: EventController was filtering for `status = 'active'` but the database only supports `'scheduled'`, `'cancelled'`, `'completed'`

**Fix**: Changed filter in EventController to `status = 'scheduled'`

**File**: `app/Http/Controllers/User/EventController.php` (Line 42)

### Issue 2: Show Timings Not Have Correct Status on Creation
**Problem**: When organizers created show timings, the status wasn't being explicitly set

**Fix**: The status now defaults to `'scheduled'` via the database migration

**File**: `app/Http/Controllers/Organiser/ShowTimingController.php` (Line 28)

### Issue 3: Existing Show Timings May Have Invalid Status
**Problem**: If any show timings were created before this fix, they might have NULL or 'active' status

**Fix**: New migration to update all existing show timings to 'scheduled'

**File**: `database/migrations/2026_01_21_000001_fix_show_timings_status.php`

---

## Steps to Fix Your Installation

### 1. Update Your Code
The code has already been fixed in:
- ✅ `EventController.php` - Uses correct status filter
- ✅ `ShowTimingController.php` - No longer tries to set 'active' status

### 2. Run Migration to Fix Existing Data
```bash
php artisan migrate
```

This will execute the new migration that fixes any existing show timings with invalid status.

### 3. Test

**Create a New Show Timing:**
1. Go to Organizer Dashboard
2. Create Event
3. Create Show Timing
4. Set future date/time
5. Save

**View Event & Check Seats:**
1. Go to User Events
2. Click on the event
3. You should now see the dropdown with show timings
4. Select a show timing
5. Seats should load!

---

## What to Look For

### ✅ Should Work Now:
- Show timings dropdown appears with future show times
- Seat map loads when show timing is selected
- Real-time price calculation works
- Booking can be completed

### 🔍 Debug Steps if Still Not Working

**1. Check if event is published:**
```bash
php artisan tinker
> Event::find(1)->status
# Should be 'published'
```

**2. Check show timings exist:**
```bash
php artisan tinker
> Event::find(1)->showTimings()->get()
# Should show at least one record
```

**3. Check show timing status:**
```bash
php artisan tinker
> ShowTiming::find(1)->status
# Should be 'scheduled'
```

**4. Check show timing date is in future:**
```bash
php artisan tinker
> ShowTiming::find(1)->show_date_time > now()
# Should be true (1)
```

**5. Check venue has seat categories:**
```bash
php artisan tinker
> ShowTiming::find(1)->venue->seatCategories->count()
# Should be > 0
```

**6. Check seats exist for show timing:**
```bash
php artisan tinker
> ShowTiming::find(1)->seats->count()
# Should be > 0
```

---

## Complete Show Timing Creation Flow

```
1. Create Event (status: published)
2. Create Venue
3. Add Seat Categories to Venue (VIP, Gold, Silver)
4. Create Show Timing
   ├─ Select Event
   ├─ Select Venue  
   ├─ Set future date/time
   ├─ Set duration
   ├─ Set available seats
   └─ Save (status auto-set to 'scheduled')
5. View Event
   ├─ See show timing in dropdown
   ├─ Select show timing
   ├─ See seat map load
   └─ Can select seats!
```

---

## Changed Files Summary

### Modified (2 files):
```
app/Http/Controllers/User/EventController.php
├─ Line 42: Changed status filter from 'active' to 'scheduled'

app/Http/Controllers/Organiser/ShowTimingController.php
├─ Removed explicit status setting (lets migration default handle it)
```

### Created (1 file):
```
database/migrations/2026_01_21_000001_fix_show_timings_status.php
└─ Fixes any existing invalid status values
```

---

## Valid Show Timing Status Values

```
Only these values are allowed:
├─ 'scheduled' (default when creating)
├─ 'cancelled' (when organizer cancels)
└─ 'completed' (after event happens)

NOT allowed:
├─ 'active' ❌ (removed - was causing the issue)
├─ NULL ❌ (now fixed to 'scheduled')
└─ Other values ❌
```

---

## Key Points

✅ **Show timings must be in the future** - `show_date_time >= now()`

✅ **Show timings must have status 'scheduled'** - Not 'active' or NULL

✅ **Venue must have seat categories** - VIP, Gold, Silver, etc.

✅ **Event must be published** - Status must be 'published'

✅ **Seats must exist for the show timing** - Auto-generated when show timing created

---

## After Running Migration

Your existing show timings will be updated:
- Any with `status = NULL` → `status = 'scheduled'`
- Any with `status = 'active'` → `status = 'scheduled'` (new code no longer uses this)

New show timings will automatically get `status = 'scheduled'` via the database default.

---

## Quick Verification Command

```bash
# Check all show timings
php artisan tinker
> ShowTiming::with('event', 'venue')->get()->toArray()

# Should show:
# - show_date_time in the future
# - status = 'scheduled'
# - event_id and venue_id populated
# - venue has seatCategories with seats
```

---

**Status**: ✅ Fixed

**What to do next**: 
1. Run migrations: `php artisan migrate`
2. Create new show timing
3. Enjoy seat selection! 🎊
