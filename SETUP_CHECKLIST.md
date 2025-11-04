# 🚀 SETUP CHECKLIST: SISTEM PENGHAPUSAN JADWAL

**Target: Setup & Deploy Sistem Penghapusan Jadwal Otomatis**

---

## ✅ FASE 1: DEVELOPMENT (SUDAH SELESAI)

### Code Creation

-   [x] Create `app/Console/Commands/DeleteExpiredLessons.php`
-   [x] Create `app/Console/Kernel.php`
-   [x] Create `database/migrations/2025_11_04_120000_create_deleted_lessons_log_table.php`
-   [x] Create `resources/views/lessons/expired.blade.php`
-   [x] Create `resources/views/lessons/deleted-log.blade.php`
-   [x] Create `SISTEM_PENGHAPUSAN_JADWAL.md` (dokumentasi lengkap)
-   [x] Create `SISTEM_DELETION_RINGKASAN.md` (ringkasan & checklist)

### Code Modification

-   [x] Modify `app/Http/Controllers/LessonController.php`
    -   [x] Add `showExpiredLessons()` method
    -   [x] Add `showDeletedLog()` method
    -   [x] Add `destroyManual($id)` method

### Database

-   [x] Run `php artisan migrate` ✓ SUCCESS
    -   Tabel `deleted_lessons_log` tercipta

### Testing

-   [x] Test command `php artisan schedule:cleanup` ✓ SUCCESS
    -   Output: "✅ Cleanup selesai! 1 jadwal dihapus"
    -   Verified: 1 record ter-insert ke deleted_lessons_log
-   [x] Build `npm run build` ✓ SUCCESS
    -   55 modules transformed
    -   Build time: 1.68s

---

## ⏳ FASE 2: ROUTING SETUP (TODO - NEXT)

### Add Routes to `routes/web.php`

```php
// Add this to your admin routes group
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // ... existing routes ...

    // NEW: Jadwal management routes
    Route::get('/jadwal/will-delete', [LessonController::class, 'showExpiredLessons'])
        ->name('lessons.show-expired');

    Route::get('/jadwal/delete-log', [LessonController::class, 'showDeletedLog'])
        ->name('lessons.show-delete-log');

    Route::delete('/jadwal/{id}', [LessonController::class, 'destroyManual'])
        ->name('lessons.destroy');
});
```

**Status:** [ ] NOT DONE - Perlu tambah routes

---

## 🧪 FASE 3: LOCAL TESTING (TODO - AFTER ROUTES)

### Test Routes Exist

```bash
# Check routes are registered
php artisan route:list | grep jadwal

# Expected output:
# GET|HEAD   admin/jadwal/will-delete
# GET|HEAD   admin/jadwal/delete-log
# DELETE     admin/jadwal/{id}
```

**Status:** [ ] NOT DONE

### Manual Testing Scenario

**Test Case 1: View Expired Lessons Page**

```bash
# Step 1: Open browser
http://localhost:8000/admin/jadwal/will-delete

# Step 2: Should see:
✓ Page title "⏰ Jadwal yang Akan Dihapus"
✓ Info box about cleanup schedule
✓ Table with expired lessons (if any)
✓ Pagination links
✓ "Lihat Log" button

# Expected result: ✅ Page loads without error
```

**Test Case 2: View Deleted Log Page**

```bash
# Step 1: Open browser
http://localhost:8000/admin/jadwal/delete-log

# Step 2: Should see:
✓ Page title "📊 Log Jadwal yang Dihapus"
✓ Stats: Total, Auto, Manual deleted count
✓ Table with deletion history
✓ Column: Tanggal Jadwal, Dihapus Pada, Dihapus Oleh, etc.

# Expected result: ✅ Page loads, shows deletion history
```

**Test Case 3: Manual Delete**

```bash
# Step 1: Go to /admin/jadwal/will-delete
# Step 2: Click "🗑️ Hapus" button on any lesson
# Step 3: Confirm delete

# Expected result:
✓ Page redirects
✓ Success message: "✅ Jadwal berhasil dihapus"
✓ Lesson removed from expired list
✓ Record added to deleted_lessons_log with deleted_by = user email
```

**Status:** [ ] NOT DONE

---

## 🔧 FASE 4: PRODUCTION SETUP (TODO - DEPLOYMENT)

### Prerequisites

-   [ ] SSH access ke production server
-   [ ] Cron job capability
-   [ ] Ability to run PHP commands

### Step 1: Deploy Code to Server

```bash
# Pull/Deploy kode ke production
# (menggunakan git, rsync, atau deployment tool)

# Verify files exist:
ls -la app/Console/Commands/DeleteExpiredLessons.php
ls -la app/Console/Kernel.php
```

**Status:** [ ] NOT DONE

### Step 2: Run Migrations

```bash
# SSH ke server
ssh user@server.com

# Navigate ke project
cd /var/www/alwi-college

# Run migration
php artisan migrate

# Expected output:
# 2025_11_04_120000_create_deleted_lessons_log_table
# ✓ DONE
```

**Status:** [ ] NOT DONE

### Step 3: Setup Cron Job

```bash
# Edit crontab
crontab -e

# Add this line at the end:
* * * * * cd /var/www/alwi-college && php artisan schedule:run >> /dev/null 2>&1

# Save & Exit (Ctrl+X in nano, then Y, then Enter)

# Verify cron added
crontab -l | grep schedule:run
```

**Status:** [ ] NOT DONE

### Step 4: Verify Cron (Optional)

```bash
# Wait 1 minute, then check if cron ran:
grep CRON /var/log/syslog | tail -5

# Or check Laravel logs:
tail -f /var/www/alwi-college/storage/logs/laravel.log | grep schedule
```

**Status:** [ ] NOT DONE

### Step 5: Manual Test on Production

```bash
# Test command manually
php artisan schedule:cleanup

# Expected output:
# "✅ Cleanup selesai! X jadwal dihapus" atau "Tidak ada jadwal"
```

**Status:** [ ] NOT DONE

---

## 📋 FILE STRUCTURE VERIFICATION

### Check All Files Created

```bash
# Run from project root
ls -la app/Console/Commands/DeleteExpiredLessons.php  # ✓
ls -la app/Console/Kernel.php                         # ✓
ls -la database/migrations/2025_11_04_120000_*        # ✓
ls -la resources/views/lessons/expired.blade.php      # ✓
ls -la resources/views/lessons/deleted-log.blade.php  # ✓
```

**Status:** [x] VERIFIED ✓

### Check Modified Files

```bash
# Verify methods added to LessonController
grep "showExpiredLessons" app/Http/Controllers/LessonController.php  # ✓
grep "showDeletedLog" app/Http/Controllers/LessonController.php      # ✓
grep "destroyManual" app/Http/Controllers/LessonController.php       # ✓
```

**Status:** [x] VERIFIED ✓

---

## 🎯 ACCEPTANCE CRITERIA

### For Development ✅ COMPLETE

-   [x] Command created dan dapat dijalankan manual
-   [x] Database table created dengan struktur benar
-   [x] Controller methods added dan tidak error
-   [x] Views dibuat dengan UI yang user-friendly
-   [x] Build successful tanpa warning
-   [x] Documentation lengkap tersedia

### For Testing (TODO)

-   [ ] Routes registered dan accessible
-   [ ] Page /admin/jadwal/will-delete shows expired lessons
-   [ ] Page /admin/jadwal/delete-log shows deletion history
-   [ ] Manual delete works correctly
-   [ ] Data correct in database
-   [ ] No error messages in logs

### For Production (TODO)

-   [ ] Code deployed ke server
-   [ ] Database migration executed
-   [ ] Cron job setup dan running
-   [ ] Automatic cleanup verified
-   [ ] Log monitoring setup
-   [ ] Monitoring dashboard accessible

---

## 🔍 DEBUGGING COMMANDS

### If Something Wrong

**Check if command exists:**

```bash
php artisan list | grep schedule:cleanup
```

**Run command with verbose output:**

```bash
php artisan schedule:cleanup --verbose
```

**Check database table:**

```bash
php artisan tinker
>>> DB::table('deleted_lessons_log')->count()
>>> DB::table('deleted_lessons_log')->latest()->first()
```

**Monitor logs live:**

```bash
tail -f storage/logs/laravel.log
```

**Check cron status (Linux):**

```bash
sudo service cron status
grep CRON /var/log/syslog | tail -20
```

---

## 📊 EXPECTED RESULTS TIMELINE

### Day 1 (Implementation)

-   [x] Code written and tested
-   [ ] Routes added
-   [ ] Pages accessible
-   [ ] Manual delete works

### Day 2-3 (Testing)

-   [ ] Automated test 1-2 days
-   [ ] Verify cleanup runs daily
-   [ ] Check database records accumulating
-   [ ] Verify logs are being written

### Week 1 (Monitoring)

-   [ ] Monitor cron job logs daily
-   [ ] Verify no errors
-   [ ] Check UI pages working
-   [ ] User acceptance test

### Ongoing (Production)

-   [ ] Daily 00:30 cleanup running
-   [ ] Old lessons automatically deleted
-   [ ] Data backed up in deleted_lessons_log
-   [ ] Admin can view history anytime

---

## 📞 QUICK REFERENCE

### Important Paths

-   Command: `app/Console/Commands/DeleteExpiredLessons.php`
-   Scheduler: `app/Console/Kernel.php`
-   Controller: `app/Http/Controllers/LessonController.php`
-   Views: `resources/views/lessons/expired.blade.php` & `deleted-log.blade.php`
-   Logs: `storage/logs/laravel.log`

### Important Routes (To Be Added)

-   GET `/admin/jadwal/will-delete` → showExpiredLessons()
-   GET `/admin/jadwal/delete-log` → showDeletedLog()
-   DELETE `/admin/jadwal/{id}` → destroyManual()

### Important Tables

-   `lessons` - Existing table (queries modified)
-   `deleted_lessons_log` - New table (created by migration)

### Important Times

-   Cleanup runs: Every day at 00:30 (12:30 AM)
-   Timezone: Server timezone (likely Asia/Jakarta)

---

## 🎓 WHAT YOU LEARNED

1. **Laravel Console Commands** - How to create custom artisan commands
2. **Task Scheduling** - How to setup recurring tasks in Kernel
3. **Database Migrations** - How to create & manage database tables
4. **Cron Jobs** - How to run tasks automatically on Linux servers
5. **Audit Logging** - How to track deletions for compliance
6. **Error Handling** - Graceful error handling & logging

---

## 🚀 NEXT STEPS

### Immediately (15 mins)

1. [ ] Add routes to `routes/web.php`
2. [ ] Test routes with `php artisan route:list`

### Today (1-2 hours)

3. [ ] Open browser and test both pages
4. [ ] Verify manual delete works
5. [ ] Check database records

### This Week (Ongoing)

6. [ ] Deploy to production server
7. [ ] Setup cron job
8. [ ] Monitor for 2-3 days
9. [ ] Verify cleanup runs daily

---

## 📝 NOTES

-   Command name: `schedule:cleanup`
-   Default time: 00:30 (jam 12:30 pagi)
-   Default condition: Delete lessons with `date < today`
-   Tracking: All deletions logged to `deleted_lessons_log`
-   Manual delete: Admin can delete specific lessons anytime
-   No data loss: All deleted lessons backed up in log table

---

**Created: November 4, 2025**
**Status: Ready for Testing & Production Deployment**
**Next Action: Add routes & test in local environment**
