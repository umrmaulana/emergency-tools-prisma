# Emergency Tools Inspector System

Sistem mobile-responsive untuk inspector emergency tools menggunakan CodeIgniter 3.

## Fitur Sistem

1. **User Authentication**

   - Login dengan username/NPK dan password
   - Session management

2. **Dashboard Inspector**

   - Welcome screen dengan informasi user
   - Menu navigasi ke Emergency Tools

3. **Emergency Tools Module**

   - Navigation ke Location dan Checksheet
   - Location: Daftar semua lokasi equipment
   - Checksheet: Pilihan scan QR atau dropdown equipment

4. **Checksheet/Inspection System**
   - Scan QR Code menggunakan kamera
   - Input manual QR code
   - Dropdown selection equipment
   - Form inspection dengan checksheet items
   - Upload foto untuk setiap item
   - Status OK/Not OK untuk setiap item
   - Catatan umum inspection

## Struktur Database

Sistem menggunakan 8 tabel utama:

- `users`: Data user (inspector/supervisor)
- `tm_locations`: Master lokasi equipment
- `tm_master_equipment_types`: Master jenis equipment
- `tm_equipments`: Data equipment
- `tm_checksheet_templates`: Template checksheet per jenis equipment
- `tr_inspections`: Header transaksi inspection
- `tr_inspection_details`: Detail item inspection
- `tr_attachments`: File attachment (foto, dll)

## File yang Dibuat

### Controllers

- `/application/controllers/emergency_tools/Inspector.php` - Main controller untuk inspector

### Models

- `/application/models/emergency_tools/Equipment_model.php` - Model untuk equipment
- `/application/models/emergency_tools/Location_model.php` - Model untuk location
- `/application/models/emergency_tools/Inspection_model.php` - Model untuk inspection
- `/application/models/emergency_tools/Checksheet_model.php` - Model untuk checksheet

### Views

- `/application/views/emergency_tools/inspector/dashboard.php` - Dashboard inspector
- `/application/views/emergency_tools/inspector/emergency_tools.php` - Menu emergency tools
- `/application/views/emergency_tools/inspector/location.php` - Daftar lokasi
- `/application/views/emergency_tools/inspector/checksheet.php` - Pilihan checksheet (QR/dropdown)
- `/application/views/emergency_tools/inspector/inspection_form.php` - Form inspection lengkap

## Cara Testing

1. **Setup Database**

   - Import file `database_emergency_tools.sql` ke MySQL
   - Update konfigurasi database di `application/config/database.php`

2. **Login Test**

   - Username: `umrmaulana` / NPK: `71030`, Password: `Maulana1` (Level: spv)
   - Username: `supervisor` / NPK: `SPV001`, Password: `admin123` (Level: spv)

   Note: Untuk testing inspector, buat user baru dengan level 'inspector' di tabel users.

3. **Flow Testing**
   - Login → Dashboard Inspector → Emergency Tools
   - Pilih Location untuk melihat daftar lokasi
   - Pilih Checksheet → Scan QR atau pilih dari dropdown
   - Isi form inspection dengan status dan foto
   - Submit inspection

## URL Routes

- `/` atau `/auth` - Login page
- `/emergency_tools/inspector` - Dashboard inspector
- `/emergency_tools/inspector/emergency_tools` - Menu emergency tools
- `/emergency_tools/inspector/location` - Daftar lokasi
- `/emergency_tools/inspector/checksheet` - Checksheet selection
- `/emergency_tools/inspector/inspection_form/{equipment_id}` - Form inspection

## Features Mobile-Responsive

- Bootstrap 5 untuk responsive design
- QR Code scanner menggunakan kamera device
- Touch-friendly interface
- Optimized untuk mobile browser
- Camera capture untuk foto upload

## Security Features

- Session-based authentication
- Form validation
- CSRF protection (CI3 built-in)
- File upload validation
- SQL injection protection via Active Record

## Technologies Used

- CodeIgniter 3
- Bootstrap 5
- Font Awesome 6
- jsQR library untuk QR scanning
- HTML5 Camera API

Sistem siap untuk testing dan development lebih lanjut!
