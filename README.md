# MooWiFi - Sistem Billing & Manajemen RT/RW Net

> **Otomatisasi Jaringan, Maksimalkan Cuan.**

MooWiFi adalah aplikasi Multi-Tenant Billing & Network Management modern yang dirancang khusus untuk pengusaha RT/RW Net dan ISP lokal. Dibangun dengan Laravel 12 dan Tabler UI.

---

## Fitur Utama

- **Multi-Tenant Architecture**: Pemisahan data lengkap antar tenant RT/RW Net.
- **Manajemen Pelanggan**: Registrasi pelanggan PPPoE & Hotspot, status isolir, jatuh tempo.
- **Billing & Faktur Otomatis**: Generator invoice bulanan, reminder jatuh tempo otomatis via WhatsApp & Email.
- **Integrasi Payment Gateway**: Mendukung Duitku, Xendit, Midtrans, Tripay, serta Pembayaran Manual (Transfer Bank & QRIS) dengan verifikasi bukti bayar.
- **Integrasi MikroTik RouterOS**:
  - Direct Public IP / DDNS.
  - Auto-Cut / Auto-Isolation saat invoice jatuh tempo.
  - Auto-Restore otomatis seketika setelah pembayaran sukses (Webhook).
- **Notifikasi Multi-Channel**: WhatsApp Gateway (Fonnte, Wablas, Whacenter, RuangWA, atau Scan QR Baileys) dan Email Notifikasi.
- **Tabler UI Modern**: Tampilan responsif, bersih, mendukung Dark Mode & Light Mode, serta sidebar collapse.

---

## Panduan Instalasi Lokal

### 1. Clone Repository
```bash
git clone https://github.com/alimron16/moowifi.git
cd moowifi
```

### 2. Install Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan konfigurasi database MySQL di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=moowifi
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeder Database
```bash
php artisan migrate --seed
```

### 5. Build Aset Frontend
```bash
npm run build
```

### 6. Jalankan Aplikasi
```bash
php artisan serve
```
Akses di browser: `http://localhost:8000`

---

## Lisensi
Hak Cipta (c) 2026 MooWiFi. Seluruh hak cipta dilindungi.
