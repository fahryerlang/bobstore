# Fitur Request Top Up Saldo Online

## Deskripsi
Fitur ini memungkinkan pembeli/member untuk melakukan request top up saldo secara online tanpa harus datang langsung ke toko atau menghubungi admin. Admin dapat melihat, menyetujui, atau menolak request top up dari dashboard admin.

## Fitur Utama

### Untuk Pembeli/Member:
1. **Request Top Up Online**
   - Pembeli dapat mengajukan request top up dengan jumlah tertentu
   - Minimal top up: Rp 1.000
   - Maksimal top up: Rp 100.000.000
   - Dapat menambahkan catatan untuk admin
   - Hanya boleh memiliki 1 request pending pada satu waktu

2. **Riwayat Request**
   - Melihat semua request yang pernah diajukan
   - Filter berdasarkan status (pending, approved, rejected)
   - Detail lengkap setiap request termasuk catatan admin

3. **Status Request**
   - **Pending**: Request menunggu persetujuan admin
   - **Approved**: Request disetujui dan saldo telah ditambahkan
   - **Rejected**: Request ditolak dengan alasan dari admin

### Untuk Admin:
1. **Kelola Request Top Up**
   - Melihat semua request dari pembeli
   - Filter berdasarkan status
   - Badge notifikasi untuk request pending
   - Approval/rejection dengan catatan

2. **Proses Request**
   - Approve: Otomatis menambahkan saldo ke wallet pembeli
   - Reject: Memberikan alasan penolakan kepada pembeli
   - Catatan admin akan ditampilkan ke pembeli

## Struktur Database

### Tabel: wallet_topup_requests
```sql
- id: primary key
- user_id: ID pembeli yang request
- amount: jumlah top up yang diminta
- status: enum('pending', 'approved', 'rejected')
- user_notes: catatan dari pembeli (opsional)
- admin_notes: catatan dari admin (opsional)
- admin_id: ID admin yang memproses
- wallet_transaction_id: ID transaksi wallet (jika approved)
- approved_at: timestamp persetujuan
- rejected_at: timestamp penolakan
- created_at, updated_at: timestamps
```

## Routes

### Customer Routes (Auth Required):
```php
GET  /saldo/request-topup          - Form request top up
POST /saldo/request-topup          - Submit request top up
GET  /saldo/requests               - List request top up user
GET  /saldo/requests/{id}          - Detail request top up
```

### Admin Routes (Admin Only):
```php
GET  /admin/wallets/requests/list     - List semua request top up
GET  /admin/wallets/requests/{id}     - Detail request & form approval
POST /admin/wallets/requests/{id}/approve - Approve request
POST /admin/wallets/requests/{id}/reject  - Reject request
```

## Model & Service

### Model: WalletTopupRequest
- Relasi ke User (pembeli)
- Relasi ke Admin (yang memproses)
- Relasi ke WalletTransaction (jika approved)
- Scope: pending(), approved(), rejected()
- Accessor untuk formatted amount dan status label

### Service: WalletService
**New Methods:**
- `createTopupRequest($user, $amount, $userNotes)` - Buat request baru
- `approveTopupRequest($request, $admin, $adminNotes)` - Approve & tambah saldo
- `rejectTopupRequest($request, $admin, $adminNotes)` - Reject request
- `getPendingTopupRequests()` - Get pending requests
- `getAllTopupRequests($status, $perPage)` - Get semua requests dengan filter
- `getUserTopupRequests($user, $status, $perPage)` - Get requests user

## Validasi

### Request Top Up (Customer):
- Amount: required, numeric, min:1000, max:100000000
- User notes: optional, string, max:500
- Tidak boleh ada request pending sebelumnya

### Approve Request (Admin):
- Admin notes: optional, string, max:500
- Request harus dalam status pending

### Reject Request (Admin):
- Admin notes: required, string, max:500
- Request harus dalam status pending

## Notifikasi & Badge

1. **Badge di Menu Admin**
   - Menampilkan jumlah request pending
   - Badge merah di menu "Kelola Saldo"

2. **Badge di Halaman Wallet Index**
   - Tombol "Request Top Up" untuk akses cepat
   - Counter request pending

3. **Alert untuk User**
   - Notifikasi jika ada request pending saat akan membuat request baru
   - Success/error message setelah action

## Flow Penggunaan

### Flow Customer:
1. Login sebagai pembeli/member
2. Akses menu "Saldo Saya"
3. Klik "Request Top Up Online"
4. Isi jumlah top up dan catatan (opsional)
5. Submit request
6. Tunggu approval dari admin
7. Check status di "Riwayat Request"

### Flow Admin:
1. Login sebagai admin
2. Lihat notifikasi badge (jika ada pending request)
3. Akses "Kelola Saldo" > "Request Top Up"
4. Pilih request untuk diproses
5. Review detail request dan info pembeli
6. Approve (saldo otomatis ditambahkan) atau Reject (dengan alasan)
7. Pembeli akan mendapat update status

## Keamanan

1. **Authorization**
   - Customer hanya bisa melihat request miliknya sendiri
   - Admin bisa melihat semua request
   - Route dilindungi dengan middleware auth dan role

2. **Validation**
   - Limit amount min/max
   - Satu user hanya boleh 1 pending request
   - Request sudah diproses tidak bisa diproses ulang

3. **Transaction Safety**
   - Approval menggunakan DB transaction
   - Rollback otomatis jika ada error
   - Balance tracking yang akurat

## Cara Penggunaan

### Setup (Sudah Dilakukan):
```bash
php artisan migrate
```

### Testing Customer:
1. Login sebagai pembeli/customer
2. Buka `/saldo`
3. Klik tombol "Request Top Up Online"
4. Isi form dan submit

### Testing Admin:
1. Login sebagai admin
2. Buka `/admin/wallets/requests/list`
3. Pilih request dan proses

## File-file yang Dibuat/Dimodifikasi

### New Files:
- `database/migrations/2025_11_05_000000_create_wallet_topup_requests_table.php`
- `app/Models/WalletTopupRequest.php`
- `resources/views/customers/wallet/request-topup.blade.php`
- `resources/views/customers/wallet/topup-requests.blade.php`
- `resources/views/customers/wallet/topup-request-detail.blade.php`
- `resources/views/admin/wallets/topup-requests.blade.php`
- `resources/views/admin/wallets/topup-request-detail.blade.php`

### Modified Files:
- `app/Models/User.php` - Tambah relasi walletTopupRequests
- `app/Services/WalletService.php` - Tambah methods untuk request management
- `app/Http/Controllers/WalletController.php` - Tambah methods untuk customer
- `app/Http/Controllers/Admin/WalletController.php` - Tambah methods untuk admin
- `routes/web.php` - Tambah routes untuk fitur ini
- `resources/views/customers/wallet/index.blade.php` - Tambah tombol request
- `resources/views/admin/wallets/index.blade.php` - Tambah tombol & badge
- `resources/views/layouts/public.blade.php` - Tambah badge notifikasi

## Catatan Penting

1. **Cara Top Up Tetap Ada**: Sistem top up manual oleh admin tetap dipertahankan
2. **Info Banner**: Banner informasi di halaman wallet customer sudah diupdate dengan info request online
3. **Real-time Badge**: Badge notifikasi menggunakan query real-time (bisa dioptimasi dengan caching jika diperlukan)
4. **User Experience**: 
   - User tidak bisa membuat request baru jika masih ada yang pending
   - Alert clear untuk setiap action
   - Timeline visual untuk tracking status request

## Future Enhancements (Opsional)

1. Email notification saat request approved/rejected
2. Push notification di browser
3. Upload bukti transfer untuk request
4. Auto-approval untuk amount tertentu
5. Limit request per hari/bulan
6. Integration dengan payment gateway
