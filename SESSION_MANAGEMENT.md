# Session Management - Auto Logout

## Cara Kerja

Aplikasi ini dikonfigurasi untuk **otomatis logout** user dalam kondisi berikut:

### 1. **Session Expire on Close**
- Session akan otomatis terhapus saat browser ditutup
- Konfigurasi: `SESSION_EXPIRE_ON_CLOSE=true` di file `.env`

### 2. **Auto Logout saat Server Restart**
Gunakan script `start-server.bat` untuk memulai server:
```bash
.\start-server.bat
```

Script ini akan:
1. Membersihkan semua session yang ada
2. Clear cache aplikasi
3. Memulai Laravel development server

### 3. **Manual Clear Session**
Jika ingin clear session secara manual:
```bash
php artisan session:clear-all
```

### 4. **Session Timeout**
- Session akan expire setelah 120 menit (2 jam) tidak ada aktivitas
- User akan otomatis logout dan diarahkan ke homepage
- Konfigurasi: `SESSION_LIFETIME=120` di file `.env`

## Konfigurasi

Di file `.env`, tambahkan atau ubah:
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_EXPIRE_ON_CLOSE=true
```

## Cara Memulai Aplikasi

### Opsi 1: Menggunakan Script (Recommended)
```bash
.\start-server.bat
```

### Opsi 2: Manual
```bash
php artisan session:clear-all
php artisan cache:clear
php artisan config:clear
php artisan serve
```

## Fitur Session Management

✅ Auto logout saat browser ditutup
✅ Auto clear session saat server restart
✅ Session timeout setelah tidak aktif
✅ Redirect ke homepage setelah logout
✅ Pesan notifikasi "Sesi Anda telah berakhir"

## Middleware

- `ClearStaleSession`: Mengecek dan membersihkan session yang sudah kadaluarsa
- Berjalan otomatis pada setiap request

## Notes

- Pastikan menjalankan `start-server.bat` setiap kali memulai aplikasi untuk session yang bersih
- User harus login ulang setiap kali aplikasi/server di-restart
- Homepage: `http://127.0.0.1:8000` (tanpa login)
