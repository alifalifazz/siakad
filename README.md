# Sistem Informasi Akademik (SIA)

Aplikasi Sistem Informasi Akademik sederhana berbasis PHP native (tanpa framework)
dengan frontend Bootstrap + AdminLTE.

## Fitur
- Login multi-role: Admin, Dosen, Mahasiswa
- Admin: kelola data mahasiswa, dosen, mata kuliah
- Dosen: input nilai mahasiswa untuk mata kuliah yang diampu
- Mahasiswa: isi KRS (Kartu Rencana Studi), lihat KHS & IPK

## Instalasi
1. Import `database/sia_db.sql` ke MySQL/MariaDB
2. Sesuaikan koneksi database di `config/database.php`
4. Buka `http://localhost:8000`

## Struktur Folder
- `config/` - konfigurasi database
- `includes/` - helper, auth, template header/footer
- `auth/` - proses login & logout
- `admin/` - halaman & fitur admin
- `dosen/` - halaman & fitur dosen
- `mahasiswa/` - halaman & fitur mahasiswa
- `assets/` - css & js custom
- `database/` - skema SQL

## Akun Default (password: `password`)
| Role      | Username |
|-----------|----------|
| Admin     | admin    |
| Dosen     | dosen1   |
| Mahasiswa | mhs1     |

## Alur Fitur Utama
1. **Admin** login → kelola data Mahasiswa, Dosen, dan Mata Kuliah (CRUD lengkap).
2. **Mahasiswa** login → isi KRS (pilih mata kuliah, dibatasi maksimal 24 SKS per tahun ajaran) → lihat KHS & IPK otomatis terhitung.
3. **Dosen** login → pilih mata kuliah yang diampu → input nilai angka mahasiswa peserta → sistem otomatis mengonversi ke nilai huruf & bobot mutu.
4. **Perhitungan IPK**: `IPK = total(SKS x Bobot) / total SKS` dari seluruh mata kuliah yang sudah dinilai (fungsi `hitung_ipk()` di `includes/functions.php`).

## Teknologi
- Backend: PHP native (PDO, tanpa framework)
- Frontend: Bootstrap 5 + AdminLTE
- Database: MySQL/MariaDB
