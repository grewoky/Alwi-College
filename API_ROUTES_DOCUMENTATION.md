# 📡 API & Routes Documentation

## Dashboard Routes

### Student Dashboard

```
GET /dashboard
├─ Authenticated only
├─ Controller: DashboardController@student
├─ View: resources/views/dashboard/student.blade.php
└─ Middleware: auth, role:student
```

---

## Required Routes (untuk links di dashboard)

### Info/Kisi-kisi Upload

```
GET /info
├─ Name: info.index
├─ Purpose: List uploaded info/kisi-kisi
└─ Link dari: "Unggah Info / Kisi-kisi" button
```

### Payment Management

```
GET /payment
├─ Name: pay.index
├─ Purpose: Show payment history & upload proof
├─ Link dari: "Upload Bukti Pembayaran" & "Riwayat Pembayaran" buttons
└─ Features:
   ├─ List semua payment siswa
   ├─ Form untuk upload bukti baru
   └─ Status tracking (pending, approved, rejected)
```

---

## Data Flow

### 1. Dashboard Load Flow

```
GET /dashboard
    ↓
DashboardController@student
    ├─ Get student (or create if not exist)
    ├─ Get present count dari Attendance
    ├─ Get last payment
    ├─ Get all payments (untuk payment check)
    ├─ Get total students (untuk statistik)
    ├─ Get total teachers (untuk statistik)
    ↓
Blade template render dengan data:
    ├─ $presentCount
    ├─ $lastPayment
    ├─ $payments
    ├─ $totalStudents
    └─ $totalTeachers
```

### 2. Payment Notification Logic

```
Controller Pass: $payments (collection of Payment)

Blade View Check:
    ↓
$currentMonth = now()->format('m')
$currentYear = now()->format('Y')
$monthPeriod = $currentMonth . '-' . $currentYear
    ↓
$paymentThisMonth = $payments
    .where('month_period', $monthPeriod)
    .where('status', 'approved')
    .first()
    ↓
IF paymentThisMonth EXISTS:
    Show Green Alert "Pembayaran Sudah Lunas"
ELSE:
    Show Red Alert "Pembayaran Belum Lunas"
```

### 3. Carousel Event Flow

```
DOM Ready
    ↓
CarouselController Initialize
    ├─ Find all .carousel-slide elements
    ├─ Find all .carousel-dot elements
    ├─ Find navigation buttons
    └─ Attach event listeners
    ↓
Start Autoplay (5s interval)
    ↓
Listen for Events:
    ├─ Click .carousel-next → showSlide(current + 1)
    ├─ Click .carousel-prev → showSlide(current - 1)
    ├─ Click .carousel-dot → showSlide(index)
    ├─ Mouse Enter → pauseAutoplay()
    ├─ Mouse Leave → resumeAutoplay()
    └─ Arrow Keys → prev/next slide
```

---

## Database Queries

### Get Payment Status for Current Month

```php
$currentMonth = now()->format('m');
$currentYear = now()->format('Y');
$monthPeriod = $currentMonth . '-' . $currentYear;

$payment = Payment::where('student_id', $student->id)
    ->where('month_period', $monthPeriod)
    ->where('status', 'approved')
    ->first();
```

### Get All Student Count (for statistics)

```php
$totalStudents = Student::count();
```

### Get All Teacher Count (for statistics)

```php
$totalTeachers = Teacher::count();
```

### Get Attendance Count

```php
$presentCount = Attendance::where('student_id', $student->id)
    ->where('status', 'present')
    ->count();
```

---

## Expected Database State

### Migrations Required

```
✓ users
✓ students (user_id FK)
✓ teachers (user_id FK)
✓ payments (student_id FK)
✓ attendances (student_id FK)
✓ class_rooms
✓ subjects
```

### Sample Data Structure

#### Payments Table

```sql
id | student_id | month_period | amount | proof_path | status | created_at | updated_at
1  | 1          | 10-2025      | 500000 | /path/...  | approved | ... | ...
2  | 1          | 09-2025      | 500000 | /path/...  | approved | ... | ...
3  | 1          | 08-2025      | 500000 | /path/...  | pending  | ... | ...
```

#### Students Table

```sql
id | user_id | class_room_id | nis | created_at | updated_at
1  | 2       | 1             | 001 | ...        | ...
2  | 3       | 1             | 002 | ...        | ...
```

---

## Frontend Integration

### JavaScript File

```
resources/js/carousel.js
├─ Class: CarouselController
├─ Methods:
│  ├─ init()
│  ├─ showSlide(index)
│  ├─ handleNext()
│  ├─ handlePrev()
│  ├─ handleDotClick(index)
│  ├─ handleKeyboard(event)
│  ├─ startAutoplay()
│  ├─ pauseAutoplay()
│  ├─ resumeAutoplay()
│  └─ destroy()
└─ Initialization: Auto on DOMContentLoaded
```

### CSS Classes (Tailwind)

```
.carousel-container       → Main container
.carousel-slide          → Individual slides
.carousel-prev/next      → Navigation buttons
.carousel-dot            → Dot indicators
.payment-alert-success   → Success alert
.payment-alert-warning   → Warning alert
.info-card              → Info cards
.quick-access-card      → Quick access buttons
.about-card             → About section
```

---

## Error Handling

### Payment Check Errors

```php
// Handle jika $payments null
@if($payments)
    // Check payment
@else
    // Default: show alert belum bayar
@endif

// Handle jika student tidak ada
$student = \App\Models\Student::firstOrCreate(['user_id'=>Auth::id()]);
// Auto-create jika belum ada
```

### Carousel Errors

```javascript
// Check if carousel elements exist
if (document.querySelector(".carousel-container")) {
    new CarouselController();
}

// Fallback jika JS error
// Carousel tetap visible, hanya tidak animated
```

---

## Performance Considerations

### Query Optimization

```php
// Instead of:
$payments = Payment::all(); // Ambil semua

// Use:
$payments = Payment::where('student_id', $student->id)->get();
```

### N+1 Query Prevention

```php
// Use eager loading jika perlu
$payments = Payment::where('student_id', $student->id)
    ->with('student')
    ->get();
```

### Caching (Optional)

```php
// Cache dashboard data untuk 1 jam
$dashboardData = Cache::remember("dashboard.{$student->id}", 3600, function() {
    return [
        'payments' => Payment::where('student_id', $student->id)->get(),
        'totalStudents' => Student::count(),
        'totalTeachers' => Teacher::count(),
    ];
});
```

---

## Testing Endpoints

### Manual Testing via Browser

```
1. Open: http://localhost:8000/dashboard
2. Login as student
3. Verify carousel animates
4. Check payment alert displays correctly
5. Click all buttons to verify links work
```

### API Testing (if API exists)

```bash
# Get dashboard data
GET /api/dashboard

# Get payment status
GET /api/payment/status

# Get statistics
GET /api/statistics
```

### Tinker Testing

```bash
php artisan tinker

# Test payment logic
$student = Student::find(1);
$payments = $student->payment()->get();
```

---

## Deployment Checklist

-   [ ] All migrations run
-   [ ] Database seeded with test data
-   [ ] Assets built: `npm run build`
-   [ ] Cache cleared: `php artisan cache:clear`
-   [ ] View cache cleared: `php artisan view:clear`
-   [ ] Routes tested
-   [ ] Payment logic verified
-   [ ] Carousel working smooth
-   [ ] Responsive design checked
-   [ ] All links working

---

## Future Enhancements

1. **Real-time Notifications**

    - Pusher/WebSocket untuk instant payment status update

2. **Analytics Dashboard**

    - Chart untuk payment trends
    - Attendance statistics

3. **Email Notifications**

    - Send email saat payment pending/approved

4. **Mobile App Integration**

    - API endpoints untuk mobile app

5. **Advanced Filtering**
    - Filter by month/year di payment history

---

**Version:** 1.0.0
**Last Updated:** October 17, 2025
