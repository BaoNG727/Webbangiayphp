# Nike Shoe Store - Production Deployment Guide

## Prerequisites

Before deploying the Nike Shoe Store to production, ensure you have:

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- SSL certificate for HTTPS
- Email service (SMTP)

## Step-by-Step Deployment

### 1. Server Setup

#### Apache Configuration
```apache
<VirtualHost *:443>
    ServerName your-domain.com
    DocumentRoot /var/www/html/Webgiay
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    <Directory /var/www/html/Webgiay>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Security headers
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName your-domain.com
    Redirect permanent / https://your-domain.com/
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com;
    root /var/www/html/Webgiay;
    index index-mvc.php;
    
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    
    # Security headers
    add_header X-Frame-Options "DENY" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    
    location / {
        try_files $uri $uri/ /index-mvc.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index-mvc.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }
    
    location ~ \.(md|sql|env)$ {
        deny all;
    }
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}
```

### 2. Database Setup

1. **Create Database:**
```sql
CREATE DATABASE nike_shoe_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. **Import Database Schema:**
```bash
mysql -u root -p nike_shoe_store < database.sql
```

3. **Create Database User:**
```sql
CREATE USER 'nikestore_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON nike_shoe_store.* TO 'nikestore_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. File Permissions

Set appropriate file permissions for security:

```bash
# Set ownership
chown -R www-data:www-data /var/www/html/Webgiay

# Set directory permissions
find /var/www/html/Webgiay -type d -exec chmod 755 {} \;

# Set file permissions
find /var/www/html/Webgiay -type f -exec chmod 644 {} \;

# Make uploads directory writable
chmod 755 /var/www/html/Webgiay/uploads

# Create cache and logs directories
mkdir -p /var/www/html/Webgiay/cache
mkdir -p /var/www/html/Webgiay/logs
chmod 755 /var/www/html/Webgiay/cache
chmod 755 /var/www/html/Webgiay/logs
```

### 4. Configuration Updates

#### Update Database Configuration
Edit `app/Core/Config.php`:

```php
const DB_HOST = 'localhost';
const DB_NAME = 'nike_shoe_store';
const DB_USER = 'nikestore_user';
const DB_PASS = 'your_secure_password';
```

#### Update Site Configuration
```php
const SITE_URL = 'https://your-domain.com';
const SITE_EMAIL = 'support@your-domain.com';
const ENABLE_HTTPS = true;
const DEBUG_MODE = false;
const DISPLAY_ERRORS = false;
```

#### Update Email Configuration
```php
const SMTP_HOST = 'smtp.your-provider.com';
const SMTP_USERNAME = 'your-email@your-domain.com';
const SMTP_PASSWORD = 'your-app-password';
```

### 5. Security Hardening

#### Create .htaccess file (Apache)
```apache
# Redirect HTTP to HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Deny access to sensitive files
<Files ~ "\.(md|sql|env|log)$">
    Order allow,deny
    Deny from all
</Files>

# Deny access to directories
Options -Indexes

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>

# Pretty URLs
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index-mvc.php [QSA,L]
```

#### PHP Security Settings
Add to `php.ini` or `.user.ini`:

```ini
expose_php = Off
allow_url_fopen = Off
allow_url_include = Off
display_errors = Off
log_errors = On
error_log = /var/www/html/Webgiay/logs/php_errors.log
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
upload_max_filesize = 5M
post_max_size = 5M
```

### 6. Performance Optimization

#### Enable OPcache
Add to `php.ini`:

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

#### Enable Compression
```ini
zlib.output_compression = On
zlib.output_compression_level = 6
```

### 7. SSL/TLS Setup

#### Using Let's Encrypt (Certbot)
```bash
# Install Certbot
sudo apt-get install certbot python3-certbot-apache

# Obtain certificate
sudo certbot --apache -d your-domain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### 8. Monitoring and Logging

#### Log Rotation
Create `/etc/logrotate.d/nikestore`:

```
/var/www/html/Webgiay/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

#### Health Check Script
Create `health-check.php`:

```php
<?php
// Basic health check
$checks = [
    'database' => false,
    'files' => false,
    'cache' => false
];

try {
    // Check database
    $pdo = new PDO("mysql:host=localhost;dbname=nike_shoe_store", "user", "pass");
    $checks['database'] = true;
    
    // Check file permissions
    $checks['files'] = is_writable('/var/www/html/Webgiay/uploads');
    
    // Check cache directory
    $checks['cache'] = is_writable('/var/www/html/Webgiay/cache');
    
} catch (Exception $e) {
    // Log error
}

header('Content-Type: application/json');
echo json_encode(['status' => 'ok', 'checks' => $checks]);
?>
```

### 9. Backup Strategy

#### Database Backup Script
Create `backup-db.sh`:

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/nikestore"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

mysqldump -u nikestore_user -p nike_shoe_store > $BACKUP_DIR/db_backup_$DATE.sql
gzip $BACKUP_DIR/db_backup_$DATE.sql

# Keep only last 30 days
find $BACKUP_DIR -name "*.gz" -type f -mtime +30 -delete
```

#### File Backup
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/nikestore"
DATE=$(date +%Y%m%d_%H%M%S)

tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /var/www/html/Webgiay/uploads
find $BACKUP_DIR -name "files_backup_*.tar.gz" -type f -mtime +30 -delete
```

### 10. Go Live Checklist

- [ ] SSL certificate installed and working
- [ ] Database created and populated
- [ ] File permissions set correctly
- [ ] Configuration updated for production
- [ ] Email testing completed
- [ ] Security headers configured
- [ ] Performance optimization enabled
- [ ] Backup scripts configured
- [ ] Monitoring/logging setup
- [ ] Domain DNS configured
- [ ] Error pages tested
- [ ] Payment gateway configured (if applicable)
- [ ] Search engine optimization
- [ ] Site testing completed

### 11. Post-Deployment

1. **Test all functionality:**
   - User registration/login
   - Product browsing
   - Cart operations
   - Checkout process
   - Order management
   - Contact forms

2. **Monitor logs:**
```bash
tail -f /var/www/html/Webgiay/logs/php_errors.log
tail -f /var/log/apache2/error.log
```

3. **Performance monitoring:**
   - Set up Google Analytics
   - Monitor page load times
   - Check mobile responsiveness

4. **SEO Setup:**
   - Submit sitemap to search engines
   - Set up Google Search Console
   - Configure robots.txt

### Troubleshooting

#### Common Issues:

1. **500 Internal Server Error:**
   - Check PHP error logs
   - Verify file permissions
   - Check .htaccess syntax

2. **Database Connection Error:**
   - Verify database credentials
   - Check MySQL service status
   - Confirm database exists

3. **SSL Issues:**
   - Verify certificate installation
   - Check intermediate certificates
   - Test with SSL checker tools

4. **Performance Issues:**
   - Enable OPcache
   - Check slow query log
   - Monitor server resources

For additional support, contact the development team or refer to the documentation.
