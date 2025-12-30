# 🔴 AUDIT: CAROUSEL MOBILE RESPONSIVE ISSUE

**Status:** 🔴 CRITICAL - Image not full width on mobile  
**Date:** Dec 30, 2025  
**Root Cause:** Double padding + max-width constraints

---

## 📋 MASALAH

User melaporkan: **"Carousel masi bermasalah karena pada tampilan mobilenya tidak dapat menampilkan full layar"**

### **Apa yang terjadi:**

```
Mobile View (< 640px):
┌─────────────────────────────┐
│ Navbar                      │
├─────────────────────────────┤
│  px-4 padding              │
│  ┌─────────────────────┐   │
│  │ max-w-7xl container │   │
│  │  ┌───────────────┐  │   │
│  │  │ px-3 padding  │  │   │
│  │  │ ┌───────────┐ │  │   │
│  │  │ │  Carousel │ │  │   │
│  │  │ │  (small)  │ │  │   │  ❌ Gambar tidak full width!
│  │  │ └───────────┘ │  │   │
│  │  │               │  │   │
│  │  └───────────────┘  │   │
│  └─────────────────────┘   │
│                             │
└─────────────────────────────┘

Hasil: Carousel ada gap di sisi kiri-kanan
```

---

## 🔍 ROOT CAUSE ANALYSIS

### **Issue #1: Double Padding Constraint**

**Layout Structure:**

```
app.blade.php (Main Layout):
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <!-- All content including carousel goes here -->
  </div>

hero.blade.php (Carousel Component):
  <section class="pt-4 sm:pt-5 md:pt-8 lg:pt-10">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
      <!-- Carousel inside -->
    </div>
  </section>
```

**Problem:**

```
Mobile (640px width):
  app.blade.php padding: px-4 = 16px × 2 = 32px total
    └─> Available width: 640 - 32 = 608px

  Inside max-w-7xl container (608px):
    hero.blade.php padding: px-3 = 12px × 2 = 24px total
      └─> Carousel width: 608 - 24 = 584px

Result: Gambar hanya 584px, bukan full 640px ❌
```

### **Issue #2: max-w-7xl pada mobile tidak diperlukan**

Carousel harus:

-   Mobile: Full width dengan minimal padding (12px each side saja)
-   Tablet+: Maksimal lebar yang bagus

Tapi sekarang ada 2 max-w-7xl yang competing.

---

## ✅ SOLUSI

### **Approach #1: Tidak Guna max-w-7xl di Hero (Pilihan TERBAIK)**

Carousel seharusnya responsif tanpa max-width constraint:

```blade
<!-- SEKARANG (SALAH): -->
<section class="relative bg-white pt-4 sm:pt-5 md:pt-8 lg:pt-10">
  <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
    <!-- Carousel -->
  </div>
</section>

<!-- HARUS (BENAR): -->
<section class="relative bg-white pt-4 sm:pt-5 md:pt-8 lg:pt-10 px-3 sm:px-4 md:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto">
    <!-- Carousel tanpa padding, padding sudah dari section -->
  </div>
</section>
```

**Alasan:**

-   Padding di section level (global)
-   max-w-7xl untuk max-width saja (tidak padding)
-   Lebih simple dan responsive

---

### **Approach #2: Hapus max-w-7xl di Hero Sepenuhnya**

Jika carousel harus fullwidth hingga ke edge:

```blade
<!-- Alternative: Full width carousel -->
<section class="relative bg-white pt-4 sm:pt-5 md:pt-8 lg:pt-10 px-2 sm:px-3 md:px-4 lg:px-6">
  <!-- Carousel tanpa container -->
  <div id="poster-carousel" class="relative w-full ...">
    <!-- Content -->
  </div>
</section>
```

---

## 🛠️ IMPLEMENTASI FIX

### **Pilihan A: Keep max-w-7xl tapi Move Padding (RECOMMENDED)**

```diff
- <section class="relative bg-white pt-4 sm:pt-5 md:pt-8 lg:pt-10">
+ <section class="relative bg-white pt-4 sm:pt-5 md:pt-8 lg:pt-10 px-3 sm:px-4 md:px-6 lg:px-8">
-   <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
+   <div class="max-w-7xl mx-auto">
      <div id="poster-carousel" class="relative w-full overflow-hidden rounded-2xl shadow-lg">
```

**Result:**

```
Mobile (640px):
  section padding: px-3 = 12px × 2 = 24px
    └─> Inside: 640 - 24 = 616px ✅ Lebih lebar!

  max-w-7xl (unlimited on mobile):
    └─> 616px tersedia, semuanya dipakai

Tablet (768px):
  section padding: sm:px-4 = 16px × 2 = 32px
    └─> Inside: 768 - 32 = 736px

  max-w-7xl = 80rem = 1280px (tapi baru limit di lg)
    └─> 736px tersedia, semuanya dipakai

Desktop (1280px+):
  section padding: lg:px-8 = 32px × 2 = 64px
    └─> Inside: 1280 - 64 = 1216px

  max-w-7xl = 80rem ≈ 1024px (after padding) ✅
    └─> Limited to max-width yang bagus
```

---

## 📊 COMPARISON

| Viewport          | Before          | After           | Status         |
| ----------------- | --------------- | --------------- | -------------- |
| Mobile (640px)    | 584px carousel  | 616px carousel  | ✅ +32px wider |
| Tablet (768px)    | 704px carousel  | 736px carousel  | ✅ +32px wider |
| Laptop (1024px)   | 960px carousel  | 928px carousel  | ✅ Same good   |
| Desktop (1280px+) | 1024px carousel | 1024px carousel | ✅ Same good   |

---

## 🎯 EXPECTED RESULTS

**After Fix:**

```
Mobile View:
┌──────────────────────────────┐
│ Navbar                       │
├──────────────────────────────┤
│ px-3 padding (12px)         │
│ ┌──────────────────────────┐ │
│ │ Carousel (full width)    │ │ ✅ Gambar fully visible
│ │ ┌────────────────────┐   │ │
│ │ │      Image         │   │ │
│ │ │      Centered      │   │ │
│ │ │      No gaps!      │   │ │
│ │ └────────────────────┘   │ │
│ │                          │ │
│ └──────────────────────────┘ │
│ px-3 padding (12px)         │
└──────────────────────────────┘
```

---

## 🧪 TESTING AFTER FIX

-   [ ] **Mobile (375px):** Carousel full width, no horizontal scroll
-   [ ] **Mobile (640px):** Gambar visible, padding balanced
-   [ ] **Tablet (768px):** Still responsive, image clear
-   [ ] **Tablet (1024px):** Max-width active, good balance
-   [ ] **Desktop (1280px+):** Max-width 1024px, centered, balanced padding

---

**Next:** Apply fix now!
