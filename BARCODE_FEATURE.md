# Fitur Barcode Scanner

## Ringkasan
Fitur barcode scanner memungkinkan Anda untuk:
1. **Menambahkan kode barcode** saat membuat atau mengedit produk
2. **Scan barcode fisik** menggunakan kamera laptop di halaman kasir
3. **Auto-add produk** ke keranjang setelah barcode berhasil di-scan

## Cara Penggunaan

### 1. Menambahkan Barcode ke Produk

#### Saat Membuat Produk Baru:
1. Buka halaman Admin → Produk → **Tambah Produk**
2. Isi nama barang dan detail lainnya
3. Pada field **"Barcode/Kode Produk"**, masukkan kode barcode produk
   - Contoh: `8993176110074` (format EAN-13)
   - Mendukung: EAN-13, UPC, CODE-128, dll
4. Klik **Simpan**

#### Saat Mengedit Produk:
1. Buka halaman Admin → Produk
2. Klik **Edit** pada produk yang ingin ditambahkan barcode
3. Isi field **"Barcode/Kode Produk"**
4. Klik **Update**

### 2. Menggunakan Scanner di Kasir

1. Buka halaman **Kasir → Transaksi** (`/kasir/transaksi`)
2. Klik tombol **"Scan Barcode"** dengan icon kamera
3. Izinkan browser mengakses kamera laptop Anda
4. Arahkan kamera ke barcode fisik pada produk
5. Scanner akan otomatis:
   - Mendeteksi barcode
   - Mencari produk di database
   - Menambahkan produk ke keranjang belanja

## Format Barcode yang Didukung

Scanner mendukung berbagai format barcode standar:
- **EAN-13** (13 digit) - Umum di retail Indonesia
- **EAN-8** (8 digit)
- **UPC-A** (12 digit)
- **UPC-E** (6 digit)
- **CODE-128**
- **CODE-39**
- **CODE-93**
- **ITF** (Interleaved 2 of 5)

## Contoh Barcode

Produk **Indomie Goreng** sudah memiliki barcode: `8993176110074`

Anda bisa test dengan:
1. Generate barcode `8993176110074` menggunakan generator online
2. Tampilkan di layar atau print
3. Scan menggunakan fitur scanner

## Teknologi yang Digunakan

- **html5-qrcode v2.3.8**: Library JavaScript untuk scanning
- **getUserMedia API**: Akses kamera browser
- **Laravel Backend**: Pencarian produk berdasarkan barcode

## Validasi

- Barcode bersifat **opsional** (boleh kosong)
- Maximum 50 karakter
- Harus **unique** (tidak boleh duplikat)
- Bisa berisi angka dan huruf

## Tips

1. **Pencahayaan**: Pastikan ruangan cukup terang untuk hasil scan optimal
2. **Jarak**: Posisikan barcode 10-20cm dari kamera
3. **Sudut**: Arahkan barcode tegak lurus terhadap kamera
4. **Fokus**: Tunggu beberapa detik hingga kamera fokus
5. **Barcode Rusak**: Jika barcode tidak terbaca, masukkan kode manual di search box

## Troubleshooting

### Scanner tidak muncul?
- Pastikan browser memiliki izin akses kamera
- Coba refresh halaman
- Gunakan browser Chrome/Edge terbaru

### Produk tidak ditemukan setelah scan?
- Pastikan produk memiliki barcode di database
- Cek barcode sudah benar dan tidak typo
- Coba scan ulang dengan pencahayaan lebih baik

### Kamera tidak terdeteksi?
- Periksa kamera laptop tidak digunakan aplikasi lain
- Restart browser
- Cek pengaturan privacy/security browser

## Database Schema

Field `barcode` di tabel `products`:
```php
$table->string('barcode', 50)->nullable()->after('id')->index();
```

## File yang Dimodifikasi

1. **Views**:
   - `resources/views/admin/products/create.blade.php` - Form tambah barcode
   - `resources/views/admin/products/edit.blade.php` - Form edit barcode
   - `resources/views/admin/products/index.blade.php` - Tampilan barcode di card
   - `resources/views/kasir/transactions/index.blade.php` - Scanner modal

2. **Controller**:
   - `app/Http/Controllers/Admin/ProductController.php` - Validasi barcode
   - `app/Http/Controllers/Kasir/TransactionController.php` - Search by barcode

3. **Model**:
   - `app/Models/Product.php` - Field barcode di fillable

4. **Migration**:
   - `database/migrations/2025_10_30_040000_add_barcode_to_products_table.php`

## Support

Untuk pertanyaan atau masalah, silakan hubungi developer atau buat issue di repository.

---
**Last Updated**: October 30, 2025
