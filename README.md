# 🕌 E-Kubur CMS

**Sistem Pengurusan Maklumat Kematian & PPJUB dengan Integrasi API dan Web Awam**

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [API Setup](#-api-setup)
- [Public Website Setup](#-public-website-setup)
- [Deployment](#-deployment)
- [Usage](#-usage)
- [File Structure](#-file-structure)
- [Troubleshooting](#-troubleshooting)

## 🌟 Overview

E-Kubur adalah sistem CMS (Content Management System) yang dibangunkan untuk menguruskan maklumat kematian dan PPJUB (Pusat Pengurusan Jenazah dan Urusan Berkaitan). Sistem ini dilengkapi dengan:

- **Admin Panel**: Pengurusan data kematian, PPJUB, dan sistem
- **Public Website**: Laman web awam dengan sistem maklum balas
- **API Integration**: RESTful API untuk integrasi dengan sistem lain
- **e-Solat Integration**: Widget waktu solat dari JAKIM
- **Security Features**: reCAPTCHA, email verification, audit logging

## ✨ Features

### 🔐 Core Features
- **User Management**: Role-based access control
- **Kematian Module**: Pengurusan data kematian dengan lampiran
- **PPJUB Module**: Pengurusan pusat pengurusan jenazah
- **Audit Logging**: Complete activity tracking dengan IP & user agent
- **System Settings**: Konfigurasi sistem yang fleksibel

### 🌐 Public Website
- **Feedback System**: Borang maklum balas dengan reCAPTCHA v2
- **Email Verification**: 6-digit verification code
- **File Attachments**: Support untuk JPEG, PNG, PDF (hingga 100MB)
- **Mobile Responsive**: Design yang mobile-friendly

### 🔌 API & Integrations
- **Laravel Sanctum**: Token-based authentication
- **e-Solat JAKIM**: Prayer times integration
- **Weather API**: Cuaca integration
- **CORS Support**: Cross-origin resource sharing

## 🖥️ System Requirements

- **PHP**: 8.1 atau lebih tinggi
- **Laravel**: 10.x
- **Database**: MySQL 8.0+ atau MariaDB 10.5+
- **Web Server**: Apache/Nginx
- **Node.js**: 16+ (untuk build assets)
- **Composer**: 2.0+

## 🚀 Installation

### 1. Clone Repository
```bash
git clone https://github.com/mfar1984/e-kubur.git
cd e-kubur
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ekubur
DB_USERNAME=root
DB_PASSWORD=root

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed
```

### 5. Build Assets
```bash
# Build frontend assets
npm run build
```

### 6. Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
```

## ⚙️ Configuration

### Environment Variables (.env)

#### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ekubur
DB_USERNAME=root
DB_PASSWORD=root
```

#### Mail Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### File Upload Limits
```env
UPLOAD_MAX_FILESIZE=100M
POST_MAX_SIZE=100M
```

#### Session Configuration
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### System Settings (Tetapan Umum)

Akses `http://localhost:8000/tetapan` untuk konfigurasi:

- **reCAPTCHA Settings**: Site key dan secret key
- **Prayer Zone**: Zon e-Solat JAKIM
- **Email Configuration**: SMTP settings
- **System Timeout**: Session timeout settings

## 🔌 API Setup

### 1. Sanctum Configuration

#### config/sanctum.php
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

#### config/cors.php
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:8080', 'https://yourdomain.com'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'credentials' => true,
```

### 2. API Endpoints

#### Public Endpoints (No Auth Required)
```bash
GET    /api/v1/recaptcha/config    # reCAPTCHA configuration
POST   /api/v1/feedback           # Submit feedback
POST   /api/v1/feedback/verify    # Verify email code
GET    /api/esolat/today          # Prayer times
GET    /api/v1/health             # Health check
```

#### Protected Endpoints (Auth Required)
```bash
POST   /api/v1/auth/login         # User login
POST   /api/v1/auth/logout        # User logout
GET    /api/v1/user               # User profile
```

### 3. API Authentication

#### Generate Token
```bash
# Via Tinker
php artisan tinker
$user = App\Models\User::find(1);
$token = $user->createToken('api-token', ['admin:all'])->plainTextToken;
```

#### Use Token
```bash
# Headers
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

## 🌐 Public Website Setup

### 1. File Locations

#### Main Files
- **`public_website/index.php`** - Homepage
- **`public_website/feedback.php`** - Feedback form
- **`public_website/contact.php`** - Contact page

#### JavaScript Files
- **`public_website/js/main.js`** - Main JavaScript
- **`public_website/js/recaptcha-helper.js`** - reCAPTCHA integration

#### CSS Files
- **`public_website/css/style.css`** - Main stylesheet

### 2. Configuration Changes for Production

#### A. Update Domain URLs

**File: `public_website/js/recaptcha-helper.js`**
```javascript
// Line 38: Change from localhost:8000 to production domain
const response = await fetch('https://yourdomain.com/api/v1/recaptcha/config', {
```

**File: `public_website/feedback.php`**
```javascript
// Line 215: Change from localhost:8000 to production domain
fetch('https://yourdomain.com/api/v1/feedback', {

// Line 250: Change from localhost:8000 to production domain
fetch('https://yourdomain.com/api/v1/feedback/verify', {
```

**File: `public_website/js/main.js`**
```javascript
// Update any localhost references to production domain
// Search for: localhost:8000
// Replace with: https://yourdomain.com
```

#### B. Update .htaccess

**File: `public_website/.htaccess`**
```apache
RewriteEngine On

# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Canonical domain
RewriteCond %{HTTP_HOST} !^yourdomain\.com$ [NC]
RewriteRule ^(.*)$ https://yourdomain.com/$1 [L,R=301]

# Pretty URLs
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

### 3. Local Development Setup

#### Start Public Website Server
```bash
cd public_website
php -S localhost:8080
```

#### Start Laravel Server
```bash
# In main project directory
php artisan serve
```

#### Access URLs
- **Laravel Admin**: http://localhost:8000
- **Public Website**: http://localhost:8080

## 🚀 Deployment

### 1. Production Environment

#### Update .env
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_HOST=your-production-db-host
DB_DATABASE=your-production-db
DB_USERNAME=your-production-user
DB_PASSWORD=your-production-password

MAIL_HOST=your-production-smtp
MAIL_USERNAME=your-production-email
MAIL_PASSWORD=your-production-password
```

#### Update Domain References
```bash
# Search and replace all localhost references
find . -type f -name "*.php" -o -name "*.js" | xargs sed -i 's/localhost:8000/yourdomain.com/g'
find . -type f -name "*.php" -o -name "*.js" | xargs sed -i 's/localhost:8080/yourdomain.com/g'
```

### 2. Server Commands

#### Pre-deployment
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Post-deployment
```bash
# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Restart services
sudo systemctl restart apache2
sudo systemctl restart mysql
```

### 3. SSL Certificate

#### Let's Encrypt (Recommended)
```bash
# Install certbot
sudo apt install certbot python3-certbot-apache

# Get certificate
sudo certbot --apache -d yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## 📁 File Structure

```
e-kubur/
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic
│   └── Providers/           # Service providers
├── config/                  # Configuration files
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── public/                  # Laravel public files
├── public_website/          # Public website files
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   ├── includes/           # PHP includes
│   └── pages/              # Static pages
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Source CSS
│   └── js/                 # Source JavaScript
├── routes/                  # Route definitions
└── storage/                 # File storage
```

## 🛠️ Usage

### 1. Admin Panel

#### Access Admin
```
URL: http://localhost:8000
Username: admin@ekubur.com
Password: password
```

#### Key Features
- **Dashboard**: System overview dan statistics
- **Kematian**: Manage death records dan attachments
- **PPJUB**: Manage funeral management centers
- **Users**: User management dan roles
- **Settings**: System configuration
- **Audit Logs**: Activity tracking

### 2. Public Website

#### Feedback System
1. User fill feedback form
2. reCAPTCHA verification
3. Email verification code sent
4. User enter 6-digit code
5. Feedback submitted to admin

#### File Attachments
- **Supported**: JPEG, PNG, PDF
- **Max Size**: 100MB per file
- **Storage**: `storage/app/public/feedback-attachments/`

### 3. API Usage

#### Get Prayer Times
```bash
curl "https://yourdomain.com/api/esolat/today"
```

#### Submit Feedback
```bash
curl -X POST "https://yourdomain.com/api/v1/feedback" \
  -H "Content-Type: application/json" \
  -d '{"nama":"Test User","emel":"test@example.com","mesej":"Test message"}'
```

## 🔧 Troubleshooting

### Common Issues

#### 1. Session Issues
```bash
# Clear session cache
php artisan session:table
php artisan migrate
php artisan config:clear
```

#### 2. File Upload Errors
```bash
# Check PHP limits
php -i | grep -i "upload_max_filesize"
php -i | grep -i "post_max_size"

# Update .env
UPLOAD_MAX_FILESIZE=100M
POST_MAX_SIZE=100M
```

#### 3. reCAPTCHA Not Working
```bash
# Check configuration
php artisan tinker
App\Models\Tetapan::first()->recaptcha_enabled

# Verify keys in Tetapan Umum
http://localhost:8000/tetapan
```

#### 4. Email Not Sending
```bash
# Test SMTP
php artisan tinker
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')->subject('Test');
});

# Check logs
tail -f storage/logs/laravel.log
```

### Debug Commands

#### System Status
```bash
# Check routes
php artisan route:list

# Check configuration
php artisan config:show

# Check database
php artisan migrate:status

# Check storage
php artisan storage:link
```

#### Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Error logs
tail -f storage/logs/laravel-*.log
```

## 📚 Additional Resources

- **Laravel Documentation**: https://laravel.com/docs
- **MySQL Documentation**: https://dev.mysql.com/doc/
- **reCAPTCHA Setup**: https://developers.google.com/recaptcha
- **e-Solat API**: https://www.e-solat.gov.my/

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support and questions:
- **Email**: support@ekubur.com
- **Issues**: [GitHub Issues](https://github.com/mfar1984/e-kubur/issues)
- **Documentation**: [Wiki](https://github.com/mfar1984/e-kubur/wiki)

---

**Built with ❤️ for the Muslim community**
