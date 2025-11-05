# Fitur Quick Search dengan Barcode Scanner

## Overview
Fitur ini memungkinkan kasir untuk melakukan scan barcode produk menggunakan hardware barcode scanner atau input manual. Produk yang di-scan akan otomatis ditambahkan ke keranjang belanja.

## Teknologi yang Digunakan
- **Library**: Picqer PHP Barcode Generator (v3.2.3)
- **Tipe Barcode**: CODE_128 (default), EAN-13, CODE_39, CODE_93, UPC-A
- **Format Output**: PNG, SVG, HTML
- **Frontend**: Vanilla JavaScript dengan auto-detection untuk hardware scanner

## Struktur File

### 1. Backend Service
**File**: `app/Services/BarcodeService.php`
- `generatePNG($code, $type)` - Generate barcode sebagai PNG
- `generateSVG($code, $type)` - Generate barcode sebagai SVG
- `generateHTML($code, $type)` - Generate barcode sebagai HTML
- `generateForProduct($product, $format)` - Generate barcode untuk produk tertentu
- `validateEAN13($code)` - Validasi EAN-13 dengan checksum
- `calculateEAN13Checksum($code)` - Hitung checksum EAN-13
- `generateRandomBarcode($length)` - Generate barcode random untuk produk baru
- `getAvailableTypes()` - List tipe barcode yang tersedia

### 2. Controller
**File**: `app/Http/Controllers/Kasir/TransactionController.php`
- `scanBarcode(Request $request)` - API endpoint untuk scan barcode
  - Method: POST
  - Route: `/kasir/transaksi/scan-barcode`
  - Input: `barcode` (string)
  - Output: JSON dengan data produk atau error message

**File**: `app/Http/Controllers/Admin/ProductController.php`
- `showBarcode($id)` - Tampilkan barcode single produk
  - Method: GET
  - Route: `/admin/products/{id}/barcode`
- `printBarcodes(Request $request)` - Print bulk barcode labels
  - Method: POST
  - Route: `/admin/products/print-barcodes`
  - Input: `product_ids[]` (array)

### 3. Views

#### Kasir Transaction Page
**File**: `resources/views/kasir/transactions/index.blade.php`
- Input field barcode dengan auto-focus
- Tombol manual scan
- JavaScript auto-detection untuk hardware scanner
- Notifikasi success/error
- Auto-add to cart functionality

**Features**:
- Deteksi hardware scanner (typing speed < 100ms between characters)
- Buffer accumulation untuk barcode scanner
- Auto-submit setelah Enter key
- Manual input dengan button scan
- Visual feedback (notifications)

#### Admin Barcode Display
**File**: `resources/views/admin/products/barcode.blade.php`
- Display barcode single produk
- Info produk (nama, harga, stok)
- Tombol print dengan print-friendly CSS
- Tombol edit produk

#### Admin Bulk Print
**File**: `resources/views/admin/products/print-barcodes.blade.php`
- Grid layout 3 kolom untuk print efisien
- Label berisi: nama produk, harga, barcode image, barcode number
- Print-friendly CSS dengan page break handling
- Optimized untuk print A4

#### Admin Product List
**File**: `resources/views/admin/products/index.blade.php`
- Tombol "Print Label" untuk setiap produk
- Bulk print button di header untuk print semua barcode sekaligus
- Display barcode number di card produk

### 4. Routes
**File**: `routes/web.php`
```php
// Kasir - Barcode Scan
Route::post('/kasir/transaksi/scan-barcode', [TransactionController::class, 'scanBarcode'])
    ->name('kasir.transactions.scan-barcode');

// Admin - Barcode Management
Route::get('/admin/products/{id}/barcode', [ProductController::class, 'showBarcode'])
    ->name('products.barcode');
Route::post('/admin/products/print-barcodes', [ProductController::class, 'printBarcodes'])
    ->name('products.print-barcodes');
```

## Cara Penggunaan

### 1. Hardware Barcode Scanner
1. Pastikan barcode scanner terhubung dengan komputer (USB/Bluetooth)
2. Konfigurasikan scanner dalam mode "Keyboard Emulation"
3. Buka halaman kasir transaksi
4. Arahkan fokus ke input field barcode (akan auto-focus)
5. Scan barcode produk
6. Produk otomatis ditambahkan ke cart

**Auto-Detection**:
- System mendeteksi input dari hardware scanner berdasarkan typing speed
- Jika karakter diterima dalam < 100ms, dianggap dari scanner
- Setelah Enter key, langsung proses scan

### 2. Manual Input
1. Buka halaman kasir transaksi
2. Ketik barcode number di input field
3. Klik tombol "Scan Barcode" atau tekan Enter
4. Produk ditambahkan ke cart

### 3. Print Barcode Labels

#### Single Product
1. Buka halaman "Manajemen Produk" (Admin)
2. Pada card produk, klik "Print Label"
3. Akan membuka halaman barcode produk
4. Klik "Print Barcode" untuk print

#### Bulk Print
1. Buka halaman "Manajemen Produk" (Admin)
2. Klik "Print All Barcodes" di header
3. Akan membuka halaman dengan semua barcode dalam grid 3 kolom
4. Klik tombol "Print" untuk print semua

## Keamanan & Validasi

### Input Validation
- Barcode harus berupa string tidak kosong
- Maximum length: 50 karakter (sesuai database)
- Validasi EAN-13 dengan checksum calculation
- Sanitasi input untuk mencegah XSS

### CSRF Protection
- Semua POST request dilindungi CSRF token
- Token included dalam fetch API calls

### Error Handling
- Barcode tidak ditemukan → Notifikasi error
- Produk tidak valid → Log error & notifikasi
- Server error → Generic error message untuk user

## Database Schema

### Products Table
```sql
ALTER TABLE products ADD COLUMN barcode VARCHAR(50) NULLABLE;
CREATE INDEX idx_products_barcode ON products(barcode);
```

**Barcode Column**:
- Type: VARCHAR(50)
- Nullable: Yes
- Indexed: Yes (untuk performance)
- Default: NULL (akan di-generate otomatis saat create/update)

## Performance Optimization

### Database
- Index pada kolom `barcode` untuk fast lookup
- Query optimization dengan select specific columns

### Frontend
- Debounce untuk hardware scanner (buffer accumulation)
- Minimal DOM manipulation
- Efficient event listeners

### Barcode Generation
- On-demand generation (hanya saat display/print)
- Caching di browser untuk repeated views
- Optimized image size (PNG compression)

## Testing Scenarios

### 1. Hardware Scanner Test
- [ ] Scan barcode yang valid → Produk masuk cart
- [ ] Scan barcode tidak valid → Error notification
- [ ] Scan barcode tidak ada di database → Error notification
- [ ] Scan multiple produk secara cepat → Semua masuk cart
- [ ] Scan produk yang sama → Quantity bertambah

### 2. Manual Input Test
- [ ] Input barcode valid + klik scan → Success
- [ ] Input barcode valid + Enter key → Success
- [ ] Input barcode kosong → Error validation
- [ ] Input barcode panjang (>50 char) → Error validation

### 3. Print Label Test
- [ ] Print single barcode → Format correct
- [ ] Print bulk barcodes → Layout 3 kolom
- [ ] Print A4 paper → Page breaks correct
- [ ] Barcode readable by scanner after print

### 4. Edge Cases
- [ ] Produk tanpa barcode → Show "No Barcode" button disabled
- [ ] Scan saat cart penuh → Handle gracefully
- [ ] Multiple tabs scanning → No conflicts
- [ ] Internet connection loss → Proper error handling

## Configuration

### Barcode Type
Default: CODE_128 (most versatile)

Untuk mengganti tipe default, edit `BarcodeService.php`:
```php
public function generateForProduct($product, $format = 'png', $type = BarcodeGeneratorPNG::TYPE_CODE_128)
```

### Label Dimensions (Print)
Edit CSS di `print-barcodes.blade.php`:
```css
.labels-container {
    grid-template-columns: repeat(3, 1fr); /* 3 kolom per baris */
}
```

### Auto-Detection Threshold
Edit JavaScript di `transactions/index.blade.php`:
```javascript
const SCANNER_THRESHOLD = 100; // 100ms between keystrokes
```

## Troubleshooting

### Hardware Scanner Tidak Terdeteksi
1. Cek koneksi USB/Bluetooth
2. Pastikan scanner dalam mode "Keyboard Emulation"
3. Test scanner di Notepad untuk verifikasi output
4. Periksa keyboard layout setting

### Barcode Tidak Terbaca Setelah Print
1. Periksa print quality (DPI minimum 300)
2. Gunakan printer laser untuk hasil terbaik
3. Hindari resize barcode saat print
4. Pastikan barcode tidak blur atau pecah

### Produk Tidak Masuk Cart Setelah Scan
1. Cek browser console untuk error JavaScript
2. Verifikasi CSRF token valid
3. Periksa network tab untuk API response
4. Cek database apakah barcode exists

### Performance Issues
1. Tambahkan pagination di product list
2. Limit bulk print maksimal 100 produk
3. Implementasi lazy loading untuk images
4. Optimize database dengan index

## Future Enhancements

### Possible Improvements
- [ ] Generate EAN-13 barcodes dengan valid checksum
- [ ] Support QR codes untuk produk dengan banyak info
- [ ] Barcode scanner sound effect untuk feedback
- [ ] Batch barcode generation untuk import bulk products
- [ ] Barcode format customization (width, height, margin)
- [ ] Export barcodes to CSV/Excel
- [ ] API endpoint untuk mobile app scanner
- [ ] Analytics: most scanned products

### Mobile Support
- [ ] Responsive design untuk tablet
- [ ] Camera-based scanning (HTML5 getUserMedia)
- [ ] Touch-friendly UI
- [ ] Offline mode dengan service worker

## Dependencies

### PHP Packages
```json
{
    "picqer/php-barcode-generator": "^3.2"
}
```

### Installation
```bash
composer require picqer/php-barcode-generator
```

### No Additional JavaScript Libraries Required
- Pure vanilla JavaScript
- No jQuery dependency
- No external barcode scanning JS library needed

## API Reference

### POST /kasir/transaksi/scan-barcode

**Request Body**:
```json
{
    "barcode": "1234567890"
}
```

**Success Response** (200):
```json
{
    "success": true,
    "message": "Produk ditemukan",
    "product": {
        "id": 123,
        "nama_barang": "Product Name",
        "harga": 50000,
        "stok": 100,
        "formatted_price": "Rp 50.000",
        "barcode": "1234567890"
    }
}
```

**Error Response** (404):
```json
{
    "success": false,
    "message": "Produk dengan barcode tersebut tidak ditemukan"
}
```

**Error Response** (422):
```json
{
    "success": false,
    "message": "Barcode harus diisi"
}
```

## Support

Untuk pertanyaan atau issue terkait barcode scanner:
1. Cek dokumentasi ini terlebih dahulu
2. Review error logs di `storage/logs/laravel.log`
3. Test dengan barcode sample yang sudah ada di database
4. Verifikasi hardware scanner berfungsi di aplikasi lain

---

**Created**: 2024
**Version**: 1.0.0
**Laravel Version**: 12.35.0
**PHP Version**: 8.2.12
