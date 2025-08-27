# PPJUB Public Website

Website public untuk sistem carian si mati dan ahli PPJUB.

## 🌐 Domain
- **Public Website**: `https://www.ppjub.my`
- **Admin System**: `https://ppjub.com.my`

## 🏗️ Structure

```
public_website/
├── index.php              # Main search page
├── includes/
│   ├── db_connect.php     # Database connection & functions
│   ├── header.php         # Header template
│   └── footer.php         # Footer template
├── css/
│   └── style.css          # Main stylesheet
├── js/
│   └── main.js            # JavaScript functionality
└── README.md              # This file
```

## 🔧 Setup Instructions

### 1. Database Configuration
Update `includes/db_connect.php` dengan maklumat database anda:

```php
$host = 'localhost';
$dbname = 'ekubur_db';        // Nama database E-Kubur
$username = 'root';            // Username database
$password = 'root';            // Password database
```

### 2. Upload to Hosting
1. Upload semua files ke folder `public_html` hosting
2. Pastikan folder permissions betul (755 untuk folders, 644 untuk files)
3. Test database connection

### 3. Test Website
1. Buka `https://www.ppjub.my`
2. Test search functionality
3. Verify database connection

## 📱 Features

### Search Functionality
- **Carian Si Mati**: Search berdasarkan nama atau IC
- **Carian Ahli PPJUB**: Search ahli PPJUB
- **Real-time Results**: Display results dengan styling

### Responsive Design
- **Mobile-First**: Optimized untuk mobile devices
- **Modern UI**: Clean dan professional design
- **Fast Loading**: Optimized performance

### Security
- **Input Sanitization**: Prevent XSS attacks
- **Prepared Statements**: Prevent SQL injection
- **Read-only Access**: Public website hanya boleh read data

## 🗄️ Database Tables

Website ini access tables berikut dari sistem E-Kubur:

### Kematian Table
```sql
SELECT id, nama, tarikh_lahir, ic, tarikh_meninggal, 
       latitude, longitude, lokasi, status_perkhidmatan, created_at
FROM kematian
```

### PPJUB Table
```sql
SELECT id, nama, ic, telefon, email, alamat, status_keahlian, created_at
FROM ppjub
```

### Waris Table (for details)
```sql
SELECT nama, hubungan, telefon
FROM waris
```

## 🚀 Deployment

### Local Development
1. Setup local web server (XAMPP, WAMP, etc.)
2. Copy files ke `htdocs` folder
3. Update database connection untuk local environment

### Production Deployment
1. Upload ke hosting server
2. Update database connection untuk production
3. Setup SSL certificate
4. Test semua functionality

## 📋 Required Pages

### Main Pages
- [x] **index.php** - Main search page
- [ ] **feedback.php** - Borang maklum balas
- [ ] **contact.php** - Hubungi kami
- [ ] **details.php** - Detailed view for results

### Legal Pages
- [ ] **penafian.php** - Penafian
- [ ] **privasi.php** - Dasar privasi
- [ ] **terma.php** - Terma penggunaan
- [ ] **peta-laman.php** - Peta laman

## 🔒 Security Notes

1. **Database User**: Gunakan read-only user untuk public website
2. **Input Validation**: Semua input di-sanitize
3. **Error Handling**: Jangan expose sensitive database info
4. **Rate Limiting**: Consider implementing untuk prevent abuse

## 📞 Support

Untuk bantuan teknikal, hubungi:
- **Email**: info@ppjub.my
- **Phone**: +60 12-345 6789

## 📝 Changelog

### v1.0.0 (Current)
- Initial release
- Basic search functionality
- Responsive design
- Database integration

### Future Updates
- Advanced search filters
- Export functionality
- API endpoints
- Enhanced security features
