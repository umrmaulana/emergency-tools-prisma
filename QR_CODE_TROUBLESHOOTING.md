# QR Code Troubleshooting Guide - Emergency Tools System

## Masalah: "Equipment not found" saat scan QR code

### Penyebab Umum:

1. **Format QR Code tidak sesuai dengan yang diharapkan sistem**
2. **QR Code berisi path file gambar, bukan equipment code**
3. **Case sensitivity dalam pencarian**
4. **Data QR code tidak match dengan database**

### Solusi yang Telah Diterapkan:

#### 1. **Perbaikan Method `get_by_qrcode`**

Sistem sekarang mencari equipment dengan 4 cara:

- Exact match dengan QR path
- Match dengan equipment_code (case insensitive)
- Partial match (LIKE)
- Extract code dari filename QR

#### 2. **Multiple Search Strategy**

```php
// Contoh pencarian:
// QR Code: "AP-001" -> Cari equipment_code = "AP-001"
// QR Code: "qr_ap_001_123456" -> Extract "AP-001" lalu cari
// QR Code: "ap-001" -> Convert uppercase lalu cari
```

#### 3. **Kamera Default ke Belakang**

- Sistem sekarang prioritas menggunakan kamera belakang
- Auto fallback ke kamera depan jika belakang tidak tersedia

#### 4. **Debug Tools**

Untuk troubleshooting, akses:

- `http://localhost:8080/emergency_tools/inspector/debug_qr` - Lihat semua equipment
- `http://localhost:8080/emergency_tools/inspector/debug_qr/[QR_CODE]` - Test specific QR

### Cara Test QR Code:

#### Equipment Codes yang Tersedia:

- **TEST-003** (Maintenance)
- **AP-001** (Active)
- **AP-002** (Active)

#### Format QR Code yang Didukung:

1. `TEST-003` (equipment code langsung)
2. `test-003` (case insensitive)
3. `qr_test_003_1754548121` (extracted dari filename)
4. Path lengkap: `assets/emergency_tools/img/qrcode/qr_test_003_1754548121.png`

### Langkah Testing:

1. **Login sebagai inspector:**

   - Username: `71030` atau `umrmaulana`
   - Password: `Maulana1`

2. **Akses Emergency Tools → Checksheet**

3. **Test QR Scanner:**

   - Klik "Scan QR Code"
   - Akan otomatis menggunakan kamera belakang
   - Atau gunakan "Enter QR code manually" untuk test

4. **Test dengan Manual Input:**
   - Masukkan: `TEST-003`, `AP-001`, atau `AP-002`
   - Klik Search

### Monitoring & Debugging:

#### Error Messages yang Lebih Detail:

Sistem sekarang menampilkan QR code yang tidak ditemukan untuk memudahkan debugging.

#### Preview QR Code:

Sebelum submit, sistem akan menampilkan konfirmasi dengan QR code yang terdeteksi.

### Tips Troubleshooting:

1. **Jika tetap "Equipment not found":**

   - Gunakan debug URL untuk cek format QR yang benar
   - Pastikan equipment tersebut ada di database dengan status 'active'

2. **Jika kamera tidak bisa akses:**

   - Pastikan browser memiliki permission untuk kamera
   - Gunakan HTTPS atau localhost
   - Check console browser untuk error

3. **QR Code tidak terbaca:**
   - Pastikan QR code clear dan tidak blur
   - Coba manual input sebagai alternative
   - Gunakan switch camera jika ada multiple kamera

### Format QR Code Standar:

Untuk QR code baru, disarankan menggunakan format equipment_code langsung (contoh: "AP-001") untuk kemudahan dan konsistensi.
