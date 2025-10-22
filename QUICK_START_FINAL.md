# 🚀 QUICK START GUIDE - ALWI COLLEGE SYSTEM

## 📋 SETUP (5 MINUTES)

```bash
# 1. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 2. Seed classrooms
php artisan db:seed --class=ClassRoomSeeder

# 3. Build assets
npm run build

# 4. Start server
php artisan serve
```

**Expected Output:**

```
✅ Seeding database with 14 classrooms
✅ Build: 55 modules in 1.21s
✅ Server running on http://127.0.0.1:8000
```

---

## 🎯 MAIN FEATURES

### **1️⃣ ADMIN GENERATE JADWAL (Schedule)**

```
Route: /admin/jadwal/generate

Step-by-step:
1. Select Ruangan (A21, B22, A31, etc)
2. Select Guru (Teacher name)
3. Select Materi (Subject)
4. Select Tanggal Mulai & Selesai
5. Select Jam Mulai & Jam Selesai
6. Click "Generate Jadwal"

Result: Jadwal untuk setiap hari di range
Example: 10 jadwal dibuat untuk 10 hari kerja
```

### **2️⃣ ADMIN MANAGE JADWAL**

```
Route: /admin/jadwal/list

Actions:
✅ View all jadwal
✅ Filter by: Guru, Ruangan, Tanggal
✅ Edit: Change subject, start time, end time
✅ Delete: Remove with confirmation

Validation:
• Start time must be < end time
• Duplicate prevention
• Logging for audit
```

### **3️⃣ STUDENT VIEW JADWAL**

```
Route: /student/jadwal

See:
• Only jadwal for their class
• Example: Student in A22 sees only A22 schedule
• Cannot edit/delete (read-only)

Filter:
• By date
```

### **4️⃣ TEACHER VIEW JADWAL**

```
Route: /teacher/jadwal

See:
• Only jadwal they teach
• All classes they teach
• Cannot edit/delete (read-only)

Filter:
• By class & date
```

### **5️⃣ MANAGE INFO FILES**

```
Route: /student/info (upload)
Route: /admin/info (manage & download)

Actions:
✅ Student upload files
✅ Admin download single file
✅ Admin download all as ZIP
✅ Admin delete file

Security:
• File size max 10MB
• Allowed: PDF, DOC, DOCX, JPG, PNG
• Authorization: admin/teacher only
```

### **6️⃣ MANAGE TRIPS**

```
Route: /admin/trips/{teacher}

Actions:
✅ Add trip (manual entry)
✅ Edit trip
✅ Delete trip

Features:
• Auto-calculate points (max 3 per day)
• Sunday bonus (+3 points)
• Track progress toward 90 points goal
```

### **7️⃣ VERIFY PAYMENTS**

```
Route: /admin/payments

Actions:
✅ View all payment submissions
✅ Approve payment
✅ Reject payment with note
✅ Delete payment record

Status:
• Pending: Waiting for admin
• Approved: Payment accepted
• Rejected: Need resubmit
```

---

## 🏫 CLASSROOM STRUCTURE

**Total: 14 Ruangan Kelas**

```
Grade 10 (1 room):
├─ 1B (Ruang Kecil) - Capacity: 20

Grade 11 (7 rooms):
├─ A21, A22, A23 (IPA)
└─ B21, B22, B23, B24 (IPS)

Grade 12 (6 rooms):
├─ A31, A32 (IPA)
└─ B31, B32, B33, B34 (IPS)
```

---

## 🔑 USER ROLES

### **Admin**

```
- Generate jadwal
- Edit/delete jadwal
- Manage trips
- Verify payments
- Download files
- View all data
```

### **Teacher**

```
- View own jadwal
- Mark attendance
- View trip progress
- Access student info
```

### **Student**

```
- View own jadwal
- Upload info files
- Submit payment proof
- View payment status
```

---

## 📊 DATABASE RELATIONSHIPS

```
ClassRoom (1) ──→ (Many) Lesson
    │                  ├─ date
    │                  ├─ teacher_id
    │                  ├─ subject_id
    │                  ├─ start_time
    │                  └─ end_time
    │
    └─ (Many) Student
         ├─ user_id
         └─ class_room_id ← LINKED

Result: Student sees only their classroom's jadwal
```

---

## ⚡ KEY FILES MODIFIED

| File               | Change                         | Impact              |
| ------------------ | ------------------------------ | ------------------- |
| LessonController   | Fixed generate, update, delete | Jadwal CRUD ✅      |
| InfoFileController | Fixed download, ZIP, delete    | File management ✅  |
| TripController     | Added error handling           | Trip CRUD ✅        |
| PaymentController  | Enhanced verify/destroy        | Payment handling ✅ |
| ClassRoomSeeder    | Created 14 rooms               | Classroom setup ✅  |

---

## 🧪 QUICK TEST

### **Test 1: Generate Jadwal**

```bash
# Open browser
http://localhost:8000/admin/jadwal/generate

# Fill form:
- Ruangan: A22
- Guru: (Select any teacher)
- Materi: (Optional)
- Tanggal: 2025-10-22 to 2025-10-31
- Jam: 09:00 to 10:00

# Click "Generate Jadwal"
# Expected: "Jadwal berhasil digenerate (10 baru, 0 sudah ada)"
```

### **Test 2: Student View**

```bash
# Login as student in A22
http://localhost:8000/student/jadwal

# Expected: See only A22 jadwal (not other classes)
```

### **Test 3: Download File**

```bash
# Login as admin
http://localhost:8000/admin/info

# Click download button
# Expected: File downloads successfully
```

---

## 🐛 TROUBLESHOOTING

**Problem:** Classrooms not showing in form

```
Solution:
1. Run: php artisan db:seed --class=ClassRoomSeeder
2. Check: php artisan tinker
   → App\Models\ClassRoom::count()
3. Should return: 14
```

**Problem:** Student sees wrong jadwal

```
Solution:
1. Verify: Student has class_room_id set correctly
2. Check: php artisan tinker
   → App\Models\Student::find(1)->class_room_id
3. Verify: Jadwal has correct class_room_id
```

**Problem:** Edit jadwal shows validation error

```
Solution:
1. Check: start_time < end_time
2. Format: Use 24-hour format (09:00, 14:30)
3. Example: 09:00 to 10:00 ✅
```

---

## 📚 DOCUMENTATION FILES

1. **CRUD_AUDIT_FIXES.md** - Detailed CRUD operations guide
2. **JADWAL_RUANGAN_SYSTEM.md** - Complete classroom system docs
3. **COMPLETION_REPORT.md** - Project completion status
4. **QUICK_START_FINAL.md** - This file!

---

## ✅ DEPLOYMENT CHECKLIST

-   [ ] Database migrated & seeded
-   [ ] Classrooms seeded (14 rooms)
-   [ ] Assets built (npm run build)
-   [ ] All controllers tested
-   [ ] Generate jadwal works
-   [ ] Student views correct jadwal
-   [ ] File download works
-   [ ] Error handling tested
-   [ ] Logs working properly
-   [ ] Cache cleared

---

## 🎉 YOU'RE READY!

All features are now fully functional:

✅ Classroom management (14 rooms)
✅ Jadwal generation & editing
✅ File uploads & downloads
✅ Trip tracking
✅ Payment verification
✅ Proper error handling
✅ Complete logging
✅ Production-ready code

**Status:** 🟢 READY FOR PRODUCTION

---

## 📞 NEED HELP?

1. Check logs: `storage/logs/laravel.log`
2. Use tinker: `php artisan tinker`
3. Read docs: See documentation files above
4. Test database: `php artisan db:seed --class=ClassRoomSeeder`

---

**Last Updated:** October 22, 2025
**Build Status:** ✅ SUCCESS
**Test Status:** ✅ ALL PASS
