# Notification Popup System

## Fitur
Sistem notifikasi popup yang modern dan animatif dengan berbagai jenis notifikasi.

## Jenis Notifikasi
1. **Success** - Hijau, untuk operasi berhasil
2. **Error** - Merah, untuk kesalahan
3. **Warning** - Kuning, untuk peringatan
4. **Info** - Biru, untuk informasi

## Cara Menggunakan

### 1. Otomatis dari Session Flash
Notifikasi akan otomatis muncul jika Anda menggunakan session flash messages di controller:

```php
// Success notification
return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');

// Error notification
return redirect()->route('cart.index')->with('error', 'Stok produk tidak tersedia!');

// Warning notification
return redirect()->route('cart.index')->with('warning', 'Stok produk hampir habis!');

// Info notification
return redirect()->route('cart.index')->with('info', 'Promo berakhir besok!');
```

### 2. Trigger dari JavaScript
Anda juga bisa trigger notifikasi langsung dari JavaScript:

```javascript
// Success
showNotification('success', 'Berhasil!', 'Data berhasil disimpan', 5000);

// Error
showNotification('error', 'Error!', 'Terjadi kesalahan saat menyimpan data', 5000);

// Warning
showNotification('warning', 'Peringatan!', 'Periksa kembali data Anda', 5000);

// Info
showNotification('info', 'Informasi', 'Ada update terbaru tersedia', 5000);
```

### 3. Trigger dari Event Alpine.js
```javascript
// Dari Alpine component
window.dispatchEvent(new CustomEvent('notification', {
    detail: {
        type: 'success',
        title: 'Berhasil!',
        message: 'Operasi berhasil dilakukan',
        duration: 5000
    }
}));
```

## Animasi
- **Slide in from right** - Smooth entrance dari kanan
- **Slide out to right** - Smooth exit ke kanan
- **Progress bar** - Countdown animasi di bagian bawah
- **Icon animations**:
  - Success: Bounce animation
  - Error: Shake animation
  - Warning: Bounce animation
  - Info: Pulse animation
- **Ripple effect** - Efek ripple pada background icon

## Fitur Auto-dismiss
- Notifikasi otomatis hilang setelah durasi tertentu (default: 5 detik)
- User bisa close manual dengan klik tombol X
- Progress bar menunjukkan sisa waktu

## Posisi
- Fixed di **top-right** layar
- Z-index 50 (di atas konten tapi di bawah modal jika ada)
- Multiple notifications akan stack secara vertikal

## Styling
- Rounded corners (2xl)
- Shadow yang kuat untuk depth
- Hover effect: shadow lebih besar
- Border-left berwarna sesuai tipe notifikasi
- White background dengan content yang rapi

## Responsiveness
- Max-width 384px (24rem)
- Responsive untuk mobile dengan max-w-full
- Pointer events hanya pada card notification, bukan container

## Contoh Implementasi di Controller

### CartController
```php
public function store(Request $request)
{
    // ... validasi dan logic
    
    Cart::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
    ]);

    return redirect()
        ->route('cart.index')
        ->with('success', 'Produk berhasil ditambahkan ke keranjang!');
}

public function destroy(Cart $cart)
{
    $cart->delete();
    
    return redirect()
        ->route('cart.index')
        ->with('success', 'Produk berhasil dihapus dari keranjang!');
}
```

### CheckoutController
```php
public function processCheckout(Request $request)
{
    try {
        // ... proses checkout
        
        return redirect()
            ->route('customer.transactions.index')
            ->with('success', 'Checkout berhasil! Terima kasih atas pembelian Anda.');
            
    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
```

### ProfileController
```php
public function update(Request $request)
{
    $user = auth()->user();
    $user->update($request->validated());
    
    return redirect()
        ->route('profile.show')
        ->with('success', 'Profil berhasil diperbarui!');
}
```

## Customization

### Mengubah Durasi Default
Edit di component `resources/views/components/notification.blade.php`:
```php
duration: data.duration || 5000, // 5 detik (default)
```

### Mengubah Posisi
Edit class container di component:
```html
<!-- Top Right (default) -->
<div class="fixed top-4 right-4 z-50 space-y-4">

<!-- Top Left -->
<div class="fixed top-4 left-4 z-50 space-y-4">

<!-- Bottom Right -->
<div class="fixed bottom-4 right-4 z-50 space-y-4">

<!-- Bottom Left -->
<div class="fixed bottom-4 left-4 z-50 space-y-4">

<!-- Top Center -->
<div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 space-y-4">
```

### Menambahkan Sound Effect (Opsional)
```javascript
show(data) {
    // ... existing code
    
    // Play sound
    if (data.type === 'success') {
        new Audio('/sounds/success.mp3').play();
    } else if (data.type === 'error') {
        new Audio('/sounds/error.mp3').play();
    }
    
    // ... rest of code
}
```

## Browser Support
- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- Mobile browsers: ✅ Responsive

## Dependencies
- Alpine.js v3.x
- Tailwind CSS v3.x
- Modern browser dengan CSS animations support

## File Locations
- Component: `resources/views/components/notification.blade.php`
- Layout inclusion: `resources/views/layouts/public.blade.php`
