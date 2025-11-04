# Member Role Conversion System

## Deskripsi
Sistem ini memungkinkan admin untuk mengkonversi pengguna biasa (pembeli) menjadi member (customer) tanpa perlu membuat akun baru. Member otomatis mendapat akses ke loyalty point system.

## Perbedaan Role

### 👤 Pembeli Biasa
- Role: `pembeli`
- Akses: Dapat melakukan belanja dan checkout
- Tidak mendapat loyalty points
- Tidak ada benefit khusus

### ⭐ Member / Customer
- Role: `customer`
- Akses: Semua akses pembeli + loyalty system
- Mendapat loyalty points setiap belanja
- Bisa tukar points untuk diskon
- Ada level member (Bronze, Silver, Gold, Platinum)
- Multiplier points sesuai level

## Cara Konversi Role

### 1. Via Edit User (Untuk User yang Sudah Ada)
**Path:** Admin Dashboard → Kelola Pengguna → Edit

**Langkah:**
1. Pilih user yang ingin dikonversi
2. Klik tombol Edit
3. Ubah Role dari "Pembeli Biasa" ke "Member / Pelanggan Terdaftar"
4. Sistem akan otomatis:
   - Set `member_since` = timestamp saat ini
   - Set `member_level` = bronze
   - Set `points` = 0
5. Klik "Update Pengguna"
6. User langsung aktif sebagai member

**Peringatan:**
- Jika mengubah dari Member ke Pembeli Biasa, akan muncul warning
- Points tidak akan hilang tapi tidak bisa digunakan
- Member level akan tetap tersimpan

### 2. Via Create User (Untuk User Baru)
**Path:** Admin Dashboard → Kelola Pengguna → Tambah Pengguna

**Langkah:**
1. Isi form nama, email, password
2. Pilih Role: "Member / Pelanggan Terdaftar"
3. Akan muncul info box orange yang menjelaskan benefit member
4. Klik "Simpan Pengguna"
5. User langsung terdaftar sebagai member dengan status Bronze

## Database Schema

### Users Table (Modified Fields)
```sql
ALTER TABLE users 
ADD COLUMN points INT DEFAULT 0,
ADD COLUMN member_level ENUM('bronze', 'silver', 'gold', 'platinum') DEFAULT 'bronze',
ADD COLUMN member_since TIMESTAMP NULL;
```

### Validasi Role
Controller sekarang menerima role: `admin`, `kasir`, `customer`, `pembeli`

## Member Level System

### Level Hierarchy
1. **Bronze** 🥉
   - Points multiplier: 1.0x
   - Initial level untuk semua member baru
   - Warna badge: Gray

2. **Silver** 🥈
   - Points multiplier: 1.2x
   - Threshold: 5,000 total points earned
   - Warna badge: Indigo

3. **Gold** 🥇
   - Points multiplier: 1.5x
   - Threshold: 15,000 total points earned
   - Warna badge: Yellow

4. **Platinum** 💎
   - Points multiplier: 2.0x
   - Threshold: 50,000 total points earned
   - Warna badge: Purple

### Auto Upgrade
- Level otomatis naik berdasarkan total points yang pernah diperoleh
- Upgrade terjadi setelah setiap transaksi
- Tidak bisa turun level

## UI Changes

### Admin Users Index Page
```blade
- Menampilkan badge role dengan warna berbeda
- Member role memiliki warna orange
- Tampil badge level member (Bronze/Silver/Gold/Platinum) di bawah badge role
- Badge level menggunakan emoji dan warna sesuai tier
```

### Admin Users Edit Page
```blade
- Dropdown role sekarang ada 4 opsi:
  1. 🔐 Admin: Akses penuh ke semua fitur sistem
  2. 💰 Kasir: Akses kelola produk dan transaksi penjualan
  3. ⭐ Member: Dapat loyalty poin setiap belanja & tukar poin untuk diskon
  4. 👤 Pembeli Biasa: Akses belanja tanpa benefit poin loyalty

- Jika user adalah member, tampil info card:
  - Member level badge
  - Jumlah points saat ini
  - Nilai rupiah dari points
  - Tanggal member sejak kapan

- Jika admin ubah dari customer ke pembeli, muncul warning merah
```

### Admin Users Create Page
```blade
- Dropdown role sama seperti edit
- Jika pilih "customer", muncul info box orange:
  "Member otomatis diaktifkan! User akan langsung mendapat status Bronze 
   dengan 0 poin. Poin akan bertambah setiap belanja (Rp 1.000 = 1 poin)."
```

## Controller Logic

### UserController@store
```php
// Jika role adalah customer (member), inisialisasi member fields
if ($validated['role'] === 'customer') {
    $userData['points'] = 0;
    $userData['member_level'] = 'bronze';
    $userData['member_since'] = now();
}
```

### UserController@update
```php
// Deteksi konversi role
$oldRole = $user->role;
$newRole = $validated['role'];

// Jika mengubah dari non-member menjadi customer (member)
if ($oldRole !== 'customer' && $newRole === 'customer') {
    $user->points = 0;
    $user->member_level = 'bronze';
    $user->member_since = now();
}

// Custom success message
if ($oldRole !== 'customer' && $newRole === 'customer') {
    return 'Pengguna berhasil dikonversi menjadi Member dengan status Bronze! 
            Poin loyalty telah diaktifkan.';
}
```

## JavaScript Enhancements

### Edit Page
```javascript
// Tampilkan warning jika admin mengubah dari customer ke pembeli
roleSelect.addEventListener('change', function() {
    if (originalRole === 'customer' && this.value === 'pembeli') {
        warningBox.classList.remove('hidden');
    } else {
        warningBox.classList.add('hidden');
    }
});
```

### Create Page
```javascript
// Tampilkan info box jika pilih customer
roleSelect.addEventListener('change', function() {
    if (this.value === 'customer') {
        memberInfo.classList.remove('hidden');
    } else {
        memberInfo.classList.add('hidden');
    }
});
```

## Use Cases

### Skenario 1: Pembeli Baru Langsung Jadi Member
**Situasi:** Toko ingin pelanggan baru langsung dapat benefit loyalty
**Solusi:**
1. Admin create user baru
2. Pilih role "Member"
3. User langsung aktif dengan 0 points, Bronze level
4. Saat belanja pertama, dapat points

### Skenario 2: Upgrade Pembeli Lama Jadi Member
**Situasi:** Ada pembeli yang sudah sering belanja, ingin dijadikan member
**Solusi:**
1. Admin cari user tersebut di Kelola Pengguna
2. Klik Edit
3. Ubah role dari "Pembeli Biasa" ke "Member"
4. User otomatis dapat status Bronze dengan 0 points
5. Transaksi selanjutnya mulai dapat points

### Skenario 3: Downgrade Member Jadi Pembeli Biasa
**Situasi:** Member melanggar aturan atau request sendiri
**Solusi:**
1. Admin edit user
2. Ubah role dari "Member" ke "Pembeli Biasa"
3. Sistem tampilkan warning merah
4. Points tetap tersimpan di database tapi tidak aktif
5. User tidak bisa gunakan atau kumpul points lagi

## Integration dengan Loyalty System

Sistem ini terintegrasi penuh dengan:
- `LoyaltyPointService` untuk kalkulasi points
- `PointTransaction` model untuk audit trail
- Kasir Transaction page untuk redeem points
- Auto level upgrade berdasarkan total earned points

## Testing Checklist

- [x] Create user baru dengan role customer
- [x] Convert pembeli ke customer
- [x] Convert customer ke pembeli (dengan warning)
- [x] Member info tampil di edit page
- [x] Member badge tampil di index page
- [x] Points initialization (0, bronze, member_since)
- [ ] Test transaksi dengan member yang baru dikonversi
- [ ] Test point earning setelah konversi
- [ ] Test level upgrade path

## Files Modified

1. **Database:**
   - `database/migrations/2025_11_04_000000_add_points_to_users_table.php`

2. **Models:**
   - `app/Models/User.php` (added member methods)

3. **Controllers:**
   - `app/Http/Controllers/Admin/UserController.php`

4. **Views:**
   - `resources/views/admin/users/index.blade.php`
   - `resources/views/admin/users/create.blade.php`
   - `resources/views/admin/users/edit.blade.php`

5. **Services:**
   - `app/Services/LoyaltyPointService.php` (no changes, already compatible)

## Future Enhancements

1. Bulk convert: Pilih multiple users dan convert sekaligus
2. Import CSV: Upload file Excel untuk mass member registration
3. Member invitation: Member bisa invite teman, dapat bonus points
4. Points expiration: Auto expire points setelah 1 tahun
5. Birthday bonus: Auto give points di hari ulang tahun member
6. Spending tier: Badge khusus untuk top spenders
7. Member analytics: Dashboard khusus untuk member metrics

## Support

Untuk pertanyaan atau issue, silakan hubungi:
- Developer: [Your Name]
- Documentation: MEMBER_ROLE_CONVERSION.md
- Related Docs: SESSION_MANAGEMENT.md, CATEGORIES_TAGS_SYSTEM.md
