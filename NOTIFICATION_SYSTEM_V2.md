# Notification System v2.0 - Center Modal with Loading Animation

## 🎯 Fitur Update

### 1. **Fixed Duplicate Notification**
- ❌ Removed: Sistem notifikasi lama di `layoutState()` 
- ✅ Sekarang hanya 1 sistem: `notification.blade.php`

### 2. **Center Modal Design**
- Popup muncul **di tengah layar** dengan backdrop blur
- Desain modern dengan shadow besar dan rounded corners
- Mobile responsive dengan `max-w-md`

### 3. **Loading → Checkmark Animation**
#### Phase 1: Loading (800ms)
- Circular spinner berputar dengan animasi stroke-dashoffset
- Warna sesuai type: green, red, yellow, blue

#### Phase 2: Complete
- **Success**: Checkmark dengan draw animation (path drawing)
- **Error**: X mark dengan shake animation
- **Warning**: Exclamation dengan bounce animation
- **Info**: Info icon dengan pulse animation

#### Phase 3: Button Appears
- Tombol "OK" muncul setelah animasi selesai
- Delayed entrance dengan slide up effect
- Warna button sesuai notification type

## 🎨 Design Specs

### Layout
```
┌─────────────────────────────┐
│  Backdrop (blur + opacity)  │
│                             │
│    ┌──────────────────┐    │
│    │  White Card      │    │
│    │                  │    │
│    │  [Loading/Icon]  │    │
│    │                  │    │
│    │  Title (bold)    │    │
│    │  Message (sm)    │    │
│    │                  │    │
│    │  [OK Button]     │    │
│    │                  │    │
│    └──────────────────┘    │
│                             │
└─────────────────────────────┘
```

### Colors
- **Success**: Green 500/600
- **Error**: Red 500/600
- **Warning**: Yellow 500/600
- **Info**: Blue 500/600

### Animations
1. **spin-loading**: Circular stroke animation (1.5s loop)
2. **checkmark**: Path drawing effect (0.5s)
3. **shake**: Horizontal shake (0.4s)
4. **bounce**: Tailwind default bounce
5. **pulse**: Tailwind default pulse

## 📝 Usage

### From Blade (Session Flash)
```php
// Controller
return redirect()->back()->with('success', 'Data berhasil disimpan!');
return redirect()->back()->with('error', 'Terjadi kesalahan!');
return redirect()->back()->with('warning', 'Peringatan!');
return redirect()->back()->with('info', 'Informasi penting');
```

### From JavaScript
```javascript
// Manual trigger
window.showNotification('success', 'Berhasil!', 'Produk ditambahkan ke keranjang');
window.showNotification('error', 'Error!', 'Stok tidak mencukupi');
window.showNotification('warning', 'Peringatan!', 'Sesi hampir habis');
window.showNotification('info', 'Info', 'Update tersedia');

// Custom duration (default 3000ms)
window.showNotification('success', 'Title', 'Message', 5000);
```

## 🔧 Technical Details

### Animation Timeline
```
0ms     : Modal appears (scale + opacity)
0-800ms : Loading spinner animates
800ms   : Icon transition (loading → complete)
800ms+  : Complete icon animates (checkmark/shake/bounce)
1100ms+ : OK button appears
3000ms+ : Auto dismiss (if user doesn't click OK)
```

### CSS Custom Animations
```css
@keyframes spin-loading {
  /* Circular stroke animation with rotation */
  0%   : stroke-dashoffset: 226, rotate: 0deg
  50%  : stroke-dashoffset: 50
  100% : stroke-dashoffset: 226, rotate: 360deg
}

@keyframes checkmark {
  /* Path drawing effect */
  0%   : stroke-dashoffset: 50 (invisible)
  100% : stroke-dashoffset: 0 (fully drawn)
}

@keyframes shake {
  /* Error shake effect */
  0%, 100%: translateX(0)
  25%    : translateX(-8px)
  75%    : translateX(8px)
}
```

### Alpine.js State Management
```javascript
notification = {
  id: unique_id,
  type: 'success|error|warning|info',
  title: 'string',
  message: 'string',
  duration: 3000,
  visible: true,
  animationPhase: 'loading' | 'complete'
}
```

## 🐛 Fixes Applied

### Issue 1: Duplicate Notifications
**Problem**: 2 popup muncul bersamaan
**Root Cause**: 
- Old system: `layoutState().showFlash()` di public.blade.php
- New system: `notificationHandler()` di notification.blade.php
**Solution**: Removed old system entirely

### Changes Made:
1. `public.blade.php`:
   - ❌ Removed: `x-on:notify.window="showFlash($event.detail)"`
   - ❌ Removed: Old flash div (lines 17-37)
   - ❌ Removed: `showFlash()` method from layoutState
   - ✅ Kept: `notificationHandler()` in notification component

## ✨ User Experience Flow

1. User performs action (add to cart, checkout, etc.)
2. **Instant**: Modal fades in center with backdrop
3. **0-800ms**: Loading spinner shows "processing"
4. **800ms**: Spinner fades out, result icon animates in
5. **Success**: Checkmark draws smoothly
6. **Error**: X appears with shake
7. **1100ms**: OK button slides up
8. **User clicks OK OR auto-dismiss**: Modal fades out

## 🎬 Demo Scenarios

### Test Success Notification
```bash
# Add to resources/views/home.blade.php temporarily
@if(true)
    <script>
        setTimeout(() => {
            window.showNotification('success', 'Berhasil!', 'Produk berhasil ditambahkan ke keranjang');
        }, 1000);
    </script>
@endif
```

### Test All Types
```javascript
setTimeout(() => window.showNotification('success', 'Success!', 'Operation completed'), 1000);
setTimeout(() => window.showNotification('error', 'Error!', 'Something went wrong'), 3000);
setTimeout(() => window.showNotification('warning', 'Warning!', 'Please be careful'), 5000);
setTimeout(() => window.showNotification('info', 'Info', 'Did you know...'), 7000);
```

## 📱 Responsive Design
- Desktop: 320px min-width, max-width 448px
- Mobile: Adapts with padding, never overflows
- Backdrop blur works on all modern browsers

## ♿ Accessibility
- Semantic HTML with proper roles
- Keyboard accessible (can be enhanced with focus trap)
- Clear visual feedback for all states
- Auto-dismiss with manual override option

---

**Updated**: November 4, 2025
**Version**: 2.0
**Status**: ✅ Production Ready
