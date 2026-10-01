Saya ingin membangun aplikasi **SaaS Billing & Management untuk RT/RW Net / ISP kecil** menggunakan Laravel.

Aplikasi ini harus dirancang sebagai **multi-tenant SaaS**, sehingga satu aplikasi dapat digunakan oleh banyak pemilik RT/RW Net.

PENTING:

**Jangan langsung coding.**

Untuk tahap pertama, bertindak sebagai:

* Software Architect
* Backend Architect
* SaaS Architect
* Database Architect
* Product Designer
* Payment Integration Architect

Saya ingin kamu melakukan diskusi dan membuat **blueprint arsitektur lengkap terlebih dahulu**.

Jangan membuat migration, controller, model, route, atau implementasi kode sebelum blueprint disetujui.

---

# 1. KONSEP UTAMA

Produk ini adalah SaaS untuk membantu pemilik RT/RW Net mengelola:

* Pelanggan internet
* Paket internet
* Billing bulanan
* Invoice
* Pembayaran
* Payment Gateway
* Manual Payment
* WhatsApp notification
* Email notification
* Reminder tagihan
* Auto Cut / Auto Isolation
* Auto Restore
* Integrasi MikroTik
* Laporan pendapatan
* Laporan piutang
* Monitoring router
* User & Role
* Subscription SaaS

Sistem harus dirancang supaya dapat digunakan oleh banyak tenant.

Contoh:

```text
Tenant A = BudiNet
Tenant B = AndiNet
Tenant C = CitraNet
```

Semua menggunakan aplikasi yang sama.

---

# 2. UI / DESIGN SYSTEM — WAJIB MENGGUNAKAN TABLER

Gunakan **Tabler** sebagai template UI/dashboard utama.

Website resmi:

https://tabler.io/

Tabler digunakan sebagai fondasi UI untuk:

* Super Admin
* Tenant Admin
* Dashboard
* Sidebar
* Topbar
* Table
* Form
* Modal
* Dropdown
* Badge
* Alert
* Toast
* Chart
* Pagination
* Empty State
* Loading State
* Error State

**Jangan membuat admin dashboard dari nol jika komponen Tabler sudah menyediakan komponennya.**

Gunakan style dan komponen Tabler secara konsisten.

Jangan mencampurkan banyak template UI berbeda.

Jika membutuhkan komponen yang belum tersedia, buat custom component yang secara visual tetap mengikuti design system Tabler.

## Prinsip UI

Saya ingin:

* Modern
* Clean
* Profesional
* Tidak terlalu ramai
* Tidak terlalu besar
* Tidak terlalu kecil
* Responsive
* Mobile friendly
* Typography nyaman
* Table rapi
* Card proporsional
* Sidebar tidak terlalu lebar
* Spacing konsisten
* Warna tidak berlebihan

Hindari:

* Hindari Emoticon
* Dashboard yang terlalu penuh
* Card raksasa
* Font terlalu besar
* Gradient berlebihan
* Animasi berlebihan
* Shadow berlebihan
* UI seperti template admin lama

Gunakan Bahasa Indonesia untuk UI utama.

Naming kode tetap menggunakan Bahasa Inggris.

---

# 3. DOMAIN / DASHBOARD

Gunakan satu dashboard utama untuk seluruh tenant.

Contoh:

https://app.domain.com

Semua pemilik RT/RW Net login melalui URL yang sama.

```text
https://app.domain.com/login
```

Setelah login, sistem menentukan tenant berdasarkan user yang login.

Contoh:

```text
BudiNet
Tenant ID: 27

AndiNet
Tenant ID: 28

CitraNet
Tenant ID: 29
```

Jangan membuat dashboard terpisah untuk setiap tenant pada MVP.

Semua tenant menggunakan:

```text
app.domain.com
```

Tetapi data harus benar-benar terisolasi berdasarkan tenant.

Di masa depan boleh disiapkan kemungkinan:

```text
budinet.domain.com
andinet.domain.com
```

atau custom domain, tetapi jangan menjadi prioritas MVP.

---

# 4. ROLE UTAMA

Ada dua level utama.

## SUPER ADMIN SAAS

Pemilik platform.

Super Admin mengelola:

* Tenant
* SaaS Plan
* Subscription
* SaaS Payment
* System Monitoring
* WhatsApp Provider
* Email System
* Payment Provider configuration
* System Logs
* Announcement
* Support
* Platform Settings

## TENANT OWNER / ADMIN RT/RW NET

Pemilik jaringan yang berlangganan SaaS.

Tenant hanya boleh melihat dan mengelola data miliknya sendiri.

---

# 5. MULTI-TENANCY

Ini adalah salah satu bagian terpenting.

Tenant A tidak boleh melihat:

* Customer Tenant B
* Invoice Tenant B
* Payment Tenant B
* Router Tenant B
* Logs Tenant B
* Settings Tenant B

Jangan hanya mengandalkan filtering di frontend.

Tenant isolation harus ditegakkan di backend.

Diskusikan:

### Option A

Shared database:

```text
tenant_id
```

pada tabel-tabel tenant.

### Option B

Database terpisah setiap tenant.

Bandingkan:

* Performance
* Security
* Maintenance
* Backup
* Cost
* Scalability
* Complexity

Berikan rekomendasi untuk MVP dan jelaskan alasannya.

---

# 6. LOGIN

Semua tenant login melalui:

```text
app.domain.com/login
```

Login menggunakan:

* Email
* Password

Opsional:

* Tenant ID

Setelah login:

```text
User
↓
Tenant
↓
Dashboard Tenant
```

Pastikan authorization dan tenant isolation benar.

Gunakan layout Tabler.

---

# 7. SUPER ADMIN PANEL

Gunakan Tabler.

Struktur:

```text
Dashboard

Tenants
├── All Tenants
├── Active
├── Trial
├── Suspended
└── Detail Tenant

SaaS Plans

Subscriptions

SaaS Payments

System Monitoring
├── Routers
├── Queue
├── Scheduler
├── Webhook
└── System Health

Payment Providers

WhatsApp

Email

System Logs

Announcements

Support / Tickets

Settings
```

Dashboard Super Admin minimal menampilkan:

* Total tenant
* Active tenant
* Trial tenant
* Suspended tenant
* Total customers
* MRR
* Subscription revenue
* New tenant
* Subscription overdue
* System health
* Failed webhook
* Failed notification
* Router errors

---

# 8. TENANT ADMIN PANEL

Gunakan Tabler.

Sidebar:

```text
Dashboard

Customers
├── All Customers
├── Active
├── Overdue
├── Isolated
├── Suspended
└── Add Customer

Billing
├── Invoices
├── Generate Invoice
├── Due Today
├── Overdue
├── Payments
└── Payment Verification

Internet Packages

MikroTik
├── Routers
├── Profiles
├── Online Users
├── Isolation
└── Logs

Payments
├── Payment Methods
├── Payment Gateway
├── Manual Payment
└── Transaction Logs

Notifications
├── WhatsApp
├── Email
├── Templates
└── Logs

Reports
├── Revenue
├── Receivables
├── Customers
├── Payments
└── Monthly Report

Settings
├── Business Profile
├── Billing
├── Auto Cut
├── Payment
├── WhatsApp
├── Email
└── Users & Roles
```

---

# 9. CUSTOMER MANAGEMENT

Customer memiliki minimal:

```text
id
tenant_id
customer_code
name
phone
email
address
package_id
router_id
mikrotik_username
mikrotik_identifier
billing_cycle
due_date
status
notes
created_at
updated_at
```

Status:

```text
ACTIVE
UNPAID
OVERDUE
ISOLATED
SUSPENDED
INACTIVE
```

Gunakan tabel Tabler yang:

* Searchable
* Sortable
* Pagination
* Filter status
* Filter package
* Filter due date
* Bulk action jika diperlukan

---

# 10. INTERNET PACKAGE

Paket minimal:

```text
name
price
download_speed
upload_speed
mikrotik_profile
description
status
```

Contoh:

```text
10 Mbps
Rp100.000

20 Mbps
Rp150.000

30 Mbps
Rp200.000
```

---

# 11. AUTO BILLING

Gunakan Laravel Scheduler + Queue.

Contoh:

Tanggal 1:

```text
Generate invoice
↓
Invoice created
↓
Send WhatsApp
↓
Send Email
```

Jatuh tempo tanggal 10:

```text
Reminder
```

H+1:

```text
Overdue
```

H+N:

```text
Auto Isolation
```

Semua jadwal harus dapat dikonfigurasi oleh tenant.

---

# 12. INVOICE

Invoice otomatis memiliki:

```text
invoice_number
tenant_id
customer_id
period_start
period_end
due_date
subtotal
discount
tax
total
status
```

Status:

```text
DRAFT
UNPAID
PARTIAL
PAID
OVERDUE
CANCELLED
```

Invoice harus bisa:

* View
* Download PDF
* Payment Link
* Send WhatsApp
* Send Email

Gunakan komponen Tabler untuk invoice page.

---

# 13. PAYMENT ARCHITECTURE

Pembayaran pelanggan internet **bukan uang milik platform SaaS**.

Setiap tenant dapat mengatur payment gateway miliknya sendiri.

Flow:

```text
Customer
↓
Invoice Tenant
↓
Payment Page
↓
Payment Gateway milik Tenant
↓
Customer membayar
↓
Gateway Webhook
↓
SaaS
↓
Invoice PAID
↓
Restore MikroTik
```

SaaS hanya mengelola:

* Invoice
* Payment transaction
* Payment status
* Webhook
* Payment logs
* Reconciliation
* Notification
* Auto Restore

Jangan membuat saldo customer/tenant yang menganggap uang pembayaran berada di rekening SaaS.

---

# 14. PAYMENT GATEWAY PROVIDER

Sediakan provider abstraction.

Minimal siapkan adapter:

### Duitku

### Xendit

### Midtrans

### Tripay

Jangan membuat BillingService bergantung langsung kepada provider tertentu.

Gunakan:

```text
PaymentGatewayInterface
```

Contoh:

```text
createPayment()
getPaymentStatus()
verifyWebhook()
handleWebhook()
refundPayment()
```

Provider:

```text
DuitkuGateway
XenditGateway
MidtransGateway
TripayGateway
```

Jika provider baru ditambahkan, BillingService tidak boleh perlu dibongkar.

---

# 15. PAYMENT GATEWAY CONFIGURATION

Tenant dapat:

```text
Settings
↓
Payment
↓
Payment Gateway
```

Contoh:

```text
Provider:
[ Duitku ]

Merchant Code:
[ ******** ]

API Key:
[ ******** ]

Environment:
[ Sandbox / Production ]

Status:
● Connected

[ Test Connection ]
[ Save ]
```

Credential sensitif harus dienkripsi.

Jangan menyimpan API secret dalam plaintext.

---

# 16. PAYMENT METHOD

Satu tenant dapat memiliki beberapa metode pembayaran.

Contoh:

```text
Duitku
BCA Manual
DANA Manual
QRIS Manual
Cash
```

Gunakan model fleksibel:

```text
payment_methods

id
tenant_id
type
provider
name
credentials
configuration
is_active
sort_order
```

Contoh:

```text
Tenant 27
│
├── Gateway
│   └── Duitku
│
├── Manual
│   └── BCA
│
├── Manual
│   └── DANA
│
└── Manual
    └── QRIS
```

---

# 17. MANUAL PAYMENT

Tenant dapat membuat metode manual.

Contoh:

```text
BCA
BudiNet
1234567890

DANA
08123456789

QRIS
Upload QR Code
```

Customer memilih:

```text
Duitku
BCA
DANA
QRIS
```

Jika manual:

```text
Invoice
Rp150.000

Transfer ke:
BCA
1234567890
BudiNet

Upload Bukti Pembayaran
```

Status:

```text
WAITING_VERIFICATION
```

Admin tenant:

```text
Approve
Reject
```

Approve:

```text
Payment = PAID
Invoice = PAID
Customer = ACTIVE
Restore MikroTik
Send Notification
```

Reject:

```text
Payment = REJECTED
Invoice tetap UNPAID/OVERDUE
```

---

# 18. PAYMENT LINK

Setiap invoice harus memiliki payment page:

```text
https://app.domain.com/pay/INV-202610-000123
```

Customer tidak harus login untuk membayar.

Halaman harus menggunakan UI Tabler dan menampilkan:

* Nama bisnis
* Nomor invoice
* Customer
* Periode
* Jatuh tempo
* Total
* Payment methods
* Status pembayaran

---

# 19. WEBHOOK

Semua payment gateway menggunakan webhook.

Contoh:

```text
POST /api/webhooks/duitku
POST /api/webhooks/xendit
POST /api/webhooks/midtrans
POST /api/webhooks/tripay
```

Webhook harus:

* Verify signature
* Validate amount
* Validate invoice
* Validate tenant
* Idempotent
* Mencegah duplicate payment
* Menyimpan raw payload
* Mencatat provider response
* Retry handling

Jangan percaya webhook tanpa validasi.

---

# 20. PAYMENT TRANSACTION

Pisahkan:

```text
invoices
payments
payment_transactions
payment_methods
```

Payment minimal:

```text
id
tenant_id
invoice_id
customer_id
payment_method_id
provider
provider_transaction_id
amount
status
paid_at
raw_response
```

Status:

```text
PENDING
SUCCESS
FAILED
EXPIRED
REFUNDED
```

---

# 21. AUTO CUT / AUTO ISOLATION

Tenant dapat mengatur:

```text
Auto Cut:
ON / OFF

Grace Period:
3 hari

Isolation Time:
00:00

Warning:
H-3
H-1

Isolation Profile:
ISOLATED
```

Flow:

```text
Invoice overdue
↓
Grace period
↓
Warning
↓
Auto Isolation
↓
MikroTik
↓
Customer ISOLATED
```

Jangan menghapus customer.

---

# 22. AUTO RESTORE

Ketika payment berhasil:

```text
Payment SUCCESS
↓
Invoice PAID
↓
Customer ACTIVE
↓
MikroTik Restore
↓
Profile normal
↓
WhatsApp
↓
Email
```

Restore harus idempotent.

---

# 23. MIKROTIK

Tenant dapat memiliki lebih dari satu router.

Contoh:

```text
BudiNet
├── Router Jakarta
├── Router Cikarang
└── Router Bekasi
```

Gunakan:

```text
MikrotikService
```

### Metode Koneksi Router (2 Opsi Wajib Didukung):

1. **Opsi 1: Koneksi Langsung (Direct Public IP / DDNS)**

   * Digunakan jika tenant memiliki IP Publik Statis atau DDNS MikroTik (`*.sn.mynetname.net`).
   * Port standar API RouterOS: `8728` (plaintext) atau `8729` (API-SSL).
   * Tenant menginput Host, Port, Username, dan Password API di dashboard.
2. **Opsi 2: Auto-VPN Tunneling (SSTP / WireGuard) untuk Router di Balik CGNAT**

   * Digunakan jika router tenant tidak memiliki IP Publik (menggunakan internet rumahan seperti IndiHome, Biznet Home, dll di balik CGNAT).
   * Server SaaS menyediakan VPN Server (SSTP/WireGuard).
   * Dashboard SaaS men-generate akun VPN unik dan menyediakan **1 baris script konfigurasi siap pakai** yang bisa langsung di-copy & paste ke Terminal Winbox oleh tenant.
   * Router MikroTik melakukan *dial-out* ke VPN SaaS, sehingga SaaS dapat mengakses API router melalui IP Tunnel virtual (`10.99.x.x:8728`) secara aman dan stabil.

Router minimal:

```text
id
tenant_id
name
connection_type (DIRECT / VPN_TUNNEL)
host
port
username
encrypted_password
vpn_user
encrypted_vpn_password
tunnel_ip
status (ONLINE / OFFLINE)
last_seen
```

Credential harus terenkripsi.
User API di MikroTik menggunakan grup khusus dengan hak akses terbatas (`read`, `write`, `api`, `test`) tanpa hak administratif berbahaya (`reboot`, `sensitive`, `password`).

---

# 24. WHATSAPP

Gunakan:

```text
WhatsAppService
```

### Metode Koneksi WhatsApp (2 Opsi Didukung):

1. **Opsi 1: Third-Party API Gateway (Fonnte, Wablas, Whacenter, RuangWA)**:
   * Tenant memasukkan API Token / Secret milik akun gateway mereka sendiri.
   * Sangat stabil, hemat resource server, dan minim risiko banned.
2. **Opsi 2: Self-Hosted / QR Connect (Baileys / Node.js Service)**:
   * Tenant melakukan scan QR langsung di dashboard untuk menghubungkan nomor WhatsApp mereka sendiri tanpa perlu biaya langganan API pihak ketiga.

Notification:

* Invoice generated
* Reminder H-3
* Reminder H-1
* Due date
* Overdue
* Isolation warning
* Isolation success
* Payment received
* Restore success

Log:

```text
tenant_id
customer_id
invoice_id
recipient
template
message
status
provider
provider_message_id
error
sent_at
```

---

# 25. EMAIL

Gunakan Laravel Mail + Queue.

Template:

* Invoice
* Reminder
* Overdue
* Payment received
* Isolation
* Restore

Tenant dapat mengatur template.

---

# 26. QUEUE & SCHEDULER

Gunakan Laravel Queue untuk:

* Generate invoice
* Send WhatsApp
* Send Email
* Payment processing
* Webhook processing
* Auto isolation
* Auto restore
* Report generation

Scheduler untuk:

* Generate monthly invoices
* Check due invoices
* Check overdue
* Auto isolation
* Cleanup
* Retry failed jobs

Semua job harus idempotent.

---

# 27. REPORT

Tenant:

```text
Revenue
Receivables
Paid
Unpaid
Overdue
Isolated
Customer growth
Payment method
Monthly revenue
```

Super Admin:

```text
SaaS revenue
Subscriptions
Active tenants
New tenants
Churn
Platform usage
```

Gunakan Chart.js atau chart component yang kompatibel dengan Tabler.

---

# 28. CUSTOMER PORTAL

Pertimbangkan:

* Lihat invoice
* Bayar invoice
* Download invoice
* Riwayat pembayaran
* Status internet
* Profile

Tentukan apakah MVP atau Phase 2.

---

# 29. USER & ROLE TENANT

Tenant Owner dapat membuat:

```text
OWNER
ADMIN
FINANCE
TECHNICIAN
```

Permission:

OWNER:
Full access

ADMIN:
Customers + Billing

FINANCE:
Invoice + Payment + Reports

TECHNICIAN:
Customers + MikroTik + Isolation

---

# 30. AUDIT LOG

Catat:

* Login
* Logout
* Customer created
* Customer updated
* Invoice generated
* Invoice cancelled
* Payment approved
* Payment rejected
* Gateway changed
* Router changed
* Customer isolated
* Customer restored
* Webhook received
* Webhook failed

---

# 31. SECURITY

Perhatikan:

* Tenant isolation
* Authorization
* CSRF
* XSS
* SQL Injection
* Rate limiting
* Secure webhook
* Webhook signature
* Encryption
* Password hashing
* Session security
* API authentication
* Secret management
* Audit log

---

# 32. TECH STACK

Gunakan:

* Laravel versi stabil/current (Laravel 11 / 12)
* PHP 8.3+
* MySQL / MariaDB
* Tabler UI (Official Design System)
* Frontend Stack: **Blade Components + Alpine.js + Tabler Core** (ringan, cepat, native Tabler, tanpa framework JS berat yang rumit)
* Laravel Queue (Redis)
* Laravel Scheduler
* RouterOS API Client

Untuk frontend, prioritaskan pendekatan yang:

* Sederhana
* Stabil
* Mudah dirawat
* Cepat
* Cocok dengan Tabler
* Tidak over-engineered

Jangan menggunakan banyak framework frontend sekaligus tanpa alasan.

---

# 33. UI IMPLEMENTATION RULE

Gunakan Tabler sebagai design system utama.

Jika menggunakan Blade:

```text
resources/views/
├── layouts/
│   ├── super-admin.blade.php
│   └── tenant.blade.php
│
├── components/
│   ├── card.blade.php
│   ├── table.blade.php
│   ├── badge.blade.php
│   ├── modal.blade.php
│   └── alert.blade.php
│
├── super-admin/
└── tenant/
```

Jangan menduplikasi HTML UI yang sama berkali-kali.

Buat reusable components.

---

# 34. MVP

Tentukan fitur MVP yang realistis.

Prioritas awal:

```text
Authentication
Multi Tenant
Super Admin
Tenant Admin
Customers
Packages
Invoices
Manual Payment
Duitku
Payment Webhook
WhatsApp
Email
MikroTik
Auto Isolation
Auto Restore
Reports
Audit Logs
Tabler UI
```

Kemudian Phase 2 dapat mencakup:

```text
Xendit
Midtrans
Tripay
Customer Portal
Advanced Reports
Advanced WhatsApp
Multiple Payment Gateway
Custom Domain
Advanced Analytics
```

Tetapi jangan menganggap pembagian ini final.

Berikan rekomendasi berdasarkan dependency dan effort.

---

# 35. OUTPUT YANG SAYA INGINKAN SEKARANG

**JANGAN CODING.**

Berikan blueprint dengan urutan:

1. Product Overview
2. User Roles
3. Multi-Tenant Architecture
4. URL / Domain Architecture
5. Super Admin Architecture
6. Tenant Admin Architecture
7. Customer Architecture
8. Billing Architecture
9. Invoice Architecture
10. Payment Architecture
11. Payment Gateway Abstraction
12. Duitku Architecture
13. Xendit Architecture
14. Midtrans Architecture
15. Tripay Architecture
16. Manual Payment Architecture
17. Webhook Architecture
18. MikroTik Architecture
19. Auto Cut Architecture
20. Auto Restore Architecture
21. WhatsApp Architecture
22. Email Architecture
23. Queue Architecture
24. Scheduler Architecture
25. Database ERD Conceptual
26. Security Architecture
27. Role & Permission Architecture
28. Audit Log Architecture
29. Tabler UI Architecture
30. MVP / Phase 2 / Phase 3
31. Technical Risks
32. Open Questions

Untuk setiap keputusan penting, berikan:

* Opsi
* Kelebihan
* Kekurangan
* Rekomendasi
* Dampak terhadap Laravel
* Dampak terhadap database

**Jangan mengambil keputusan besar sendiri jika masih ada trade-off.**

Setelah blueprint selesai, tunggu persetujuan saya.

**Jangan membuat kode implementasi sebelum saya mengatakan bahwa blueprint sudah disetujui.**

Tujuan akhir:

Membangun SaaS RT/RW Net yang terlihat seperti produk SaaS profesional, menggunakan **Tabler sebagai UI foundation**, memiliki **multi-tenant architecture**, billing otomatis, invoice otomatis, **Duitku/Xendit/Midtrans/Tripay**, manual payment, WhatsApp, Email, MikroTik Auto Cut, Auto Restore, dan dapat dikembangkan menjadi produk SaaS komersial.
