# 📝 DEPLOYMENT & MIGRATION GUIDE

## Step-by-Step Deployment Guide

This guide covers deploying your Fuel Station Management System to production.

---

## Pre-Deployment Checklist

### Code Quality
- [ ] All tests passing
- [ ] No console errors or warnings
- [ ] Code reviewed
- [ ] Comments added to complex logic
- [ ] Security vulnerabilities checked

### Environment Configuration
- [ ] .env.example updated with all variables
- [ ] Environment variables documented
- [ ] Database credentials configured
- [ ] Mail service configured (if needed)
- [ ] API keys added securely

### Database
- [ ] Migrations tested locally
- [ ] Seeders tested locally
- [ ] Backup strategy planned
- [ ] Database cleanup scripts ready
- [ ] Migration rollback plan documented

### Security
- [ ] HTTPS certificate obtained
- [ ] Firewall configured
- [ ] Database user limited permissions
- [ ] Application secrets stored securely
- [ ] Error messages don't leak information

### Performance
- [ ] Cache strategy configured
- [ ] Database indexes optimized
- [ ] Assets minified and optimized
- [ ] CDN configured (if applicable)
- [ ] Load testing completed

---

## Production .env Configuration

Create a `.env` file in production with:

```env
# Application
APP_NAME="Fuel Station"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE
APP_URL=https://yourdomain.com

# Database - Production
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=fuel_station_app
DB_USERNAME=db_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# Mail - Configure for notifications
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@fuelstation.com

# Cache
CACHE_DRIVER=redis
CACHE_TTL=86400

# Session
SESSION_DRIVER=cookie
SESSION_LIFETIME=120

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=warning

# Queue - For background jobs
QUEUE_CONNECTION=database
```

---

## Hosting Provider Options

### 1. Heroku (Easiest - PaaS)

#### Setup
```bash
# Install Heroku CLI
# Login to Heroku
heroku login

# Create app
heroku create your-app-name

# Add MySQL add-on
heroku addons:create cleardb:ignite

# Deploy
git push heroku main

# Run migrations
heroku run php artisan migrate --force

# Seed database
heroku run php artisan db:seed
```

#### Pros:
- Easiest setup
- Automatic scaling
- Free SSL
- One-click deployment

#### Cons:
- More expensive than VPS
- Limited customization
- Slower than dedicated servers

### 2. DigitalOcean (VPS - Recommended)

#### Setup with App Platform
```bash
# Connect GitHub repository
# Select PHP 8.3 runtime
# Configure environment variables
# Set build command: composer install && npm install && npm run build
# Set start command: php -S 0.0.0.0:8080 -t public
# Deploy
```

#### Setup with Droplet (More Control)
```bash
# Create Droplet (Ubuntu 22.04 LTS)
# SSH into server
ssh root@your-server-ip

# Update system
apt update && apt upgrade -y

# Install LAMP stack
apt install -y apache2 mysql-server php8.3 php8.3-curl php8.3-mysql php8.3-mbstring composer git

# Clone repository
git clone your-repo-url /var/www/fuel_station_app
cd /var/www/fuel_station_app

# Install dependencies
composer install --optimize-autoloader --no-dev

# Create .env file
cp .env.example .env
# Edit .env with production settings

# Generate key
php artisan key:generate

# Set permissions
chown -R www-data:www-data /var/www/fuel_station_app
chmod -R 755 storage bootstrap/cache

# Create Apache config
# ... (see below)

# Enable rewrite module
a2enmod rewrite

# Restart Apache
systemctl restart apache2

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed
```

### 3. AWS (Cloud - Most Scalable)

#### EC2 Instance Setup
```bash
# Launch EC2 instance (Ubuntu 22.04)
# Security group: Allow HTTP (80), HTTPS (443), SSH (22)

# SSH into instance
ssh -i your-key.pem ubuntu@your-instance-ip

# Update system
sudo apt update && sudo apt upgrade -y

# Install requirements
sudo apt install -y apache2 mysql-server php8.3 php8.3-curl php8.3-mysql php8.3-mbstring composer git certbot python3-certbot-apache

# Deploy application (same as DigitalOcean above)

# Setup SSL with Let's Encrypt
sudo certbot --apache -d yourdomain.com

# Configure RDS for database (optional)
# ... or use local MySQL
```

### 4. Shared Hosting (Budget Option)

#### File Manager Upload
1. Use FTP/SFTP to upload files
2. Use hosting control panel (cPanel, Plesk)
3. Configure database through control panel
4. Run migrations via SSH or artisan command

---

## Apache VirtualHost Configuration

Create `/etc/apache2/sites-available/fuel-station.conf`:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    ServerAdmin admin@yourdomain.com
    
    DocumentRoot /var/www/fuel_station_app/public
    
    <Directory /var/www/fuel_station_app/public>
        AllowOverride All
        Require all granted
        
        # Laravel URL rewriting
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [QSA,L]
    </Directory>
    
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php8.3-fpm.sock|fcgi://localhost"
    </FilesMatch>
    
    ErrorLog ${APACHE_LOG_DIR}/fuel-station-error.log
    CustomLog ${APACHE_LOG_DIR}/fuel-station-access.log combined
</VirtualHost>
```

Enable it:
```bash
sudo a2ensite fuel-station.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## Nginx Configuration

Create `/etc/nginx/sites-available/fuel-station`:

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/fuel_station_app/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }
    
    location ~ /\.ht {
        deny all;
    }
    
    error_log /var/log/nginx/fuel-station-error.log;
    access_log /var/log/nginx/fuel-station-access.log;
}
```

Enable it:
```bash
sudo ln -s /etc/nginx/sites-available/fuel-station /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## SSL Certificate (HTTPS)

### Let's Encrypt with Certbot

#### Apache
```bash
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

#### Nginx
```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

#### Manual
```bash
sudo certbot certonly --standalone -d yourdomain.com
```

### Auto-Renewal
```bash
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

---

## Database Backup Strategy

### Automated Backups

Create `/usr/local/bin/backup-db.sh`:

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/fuel_station"
DB_NAME="fuel_station_app"
DB_USER="backup_user"

mkdir -p $BACKUP_DIR

mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME | gzip > $BACKUP_DIR/backup_$DATE.sql.gz

# Keep only last 30 days
find $BACKUP_DIR -name "backup_*.sql.gz" -mtime +30 -delete
```

Make executable:
```bash
chmod +x /usr/local/bin/backup-db.sh
```

Add to crontab:
```bash
crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-db.sh
```

### Manual Backup
```bash
# Backup database
mysqldump -u root -p fuel_station_app > backup_$(date +%Y%m%d).sql

# Backup files
tar -czf fuel_station_backup_$(date +%Y%m%d).tar.gz /var/www/fuel_station_app
```

---

## Post-Deployment Tasks

### 1. Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# If using queue
php artisan queue:restart
```

### 2. Set Permissions

```bash
# Set correct ownership
sudo chown -R www-data:www-data /var/www/fuel_station_app

# Set correct permissions
sudo chmod -R 755 /var/www/fuel_station_app
sudo chmod -R 775 storage bootstrap/cache
```

### 3. Configure Cron Job

```bash
# Add to crontab
crontab -e

# For Laravel scheduler
* * * * * cd /var/www/fuel_station_app && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Setup Monitoring

```bash
# Install New Relic (optional)
sudo apt install newrelic-php5

# Or use Sentry for error tracking
# Add to .env: SENTRY_LARAVEL_DSN=your_sentry_dsn
```

### 5. Configure Email

Update `.env` with production mail service:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=app_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Fuel Station"
```

---

## Continuous Integration/Deployment (CI/CD)

### GitHub Actions Example

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      
      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/fuel_station_app
            git pull origin main
            composer install --optimize-autoloader --no-dev
            php artisan migrate --force
            php artisan cache:clear
```

---

## Monitoring & Maintenance

### Log Monitoring

```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log

# Check specific date
grep "2025-01-08" storage/logs/laravel.log

# Check errors only
grep -i "error" storage/logs/laravel.log
```

### Performance Monitoring

```bash
# Check disk usage
df -h

# Check memory usage
free -h

# Check CPU usage
top

# Check database connections
mysql -e "SHOW PROCESSLIST;"
```

### Regular Maintenance

```bash
# Weekly: Clear logs older than 30 days
find storage/logs -name "laravel-*.log" -mtime +30 -delete

# Weekly: Backup database
mysqldump -u root -p fuel_station_app > backup.sql

# Monthly: Update composer packages
composer update

# Monthly: Update PHP
apt update && apt upgrade
```

---

## Troubleshooting Production Issues

### Application Not Loading

```bash
# Check permissions
ls -la storage bootstrap/cache

# Check error log
tail -f storage/logs/laravel.log

# Check web server log
tail -f /var/log/apache2/error.log
tail -f /var/log/nginx/error.log
```

### Database Connection Error

```bash
# Test database connection
mysql -h localhost -u fuel_user -p fuel_station_app -e "SELECT 1;"

# Check .env database credentials
cat .env | grep DB_

# Restart MySQL
sudo systemctl restart mysql
```

### Slow Application

```bash
# Check database query performance
php artisan tinker
>>> DB::enableQueryLog(); DB::getQueryLog();

# Optimize database
php artisan tinker
>>> Illuminate\Support\Facades\DB::statement('OPTIMIZE TABLE users;');

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### 500 Error

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server logs
sudo journalctl -xe

# Test artisan command
php artisan tinker
>>> echo "OK";
```

---

## Security Hardening

### File Permissions
```bash
# Restrict storage directory
chmod 700 storage
chmod 700 bootstrap/cache

# Restrict .env file
chmod 600 .env
```

### Firewall Rules
```bash
# Ubuntu UFW
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable

# AWS Security Group
# Inbound: SSH (22), HTTP (80), HTTPS (443)
# Outbound: All
```

### Database Security
```bash
# Create limited database user
mysql> CREATE USER 'fuel_app'@'localhost' IDENTIFIED BY 'strong_password';
mysql> GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, DROP ON fuel_station_app.* TO 'fuel_app'@'localhost';
mysql> FLUSH PRIVILEGES;
```

### Remove Default Files
```bash
# Remove unnecessary files
rm -rf node_modules
rm -f .git

# Only keep what's needed in production
```

---

## Rollback Plan

### If Something Goes Wrong

```bash
# Stop application
sudo systemctl stop apache2  # or nginx

# Restore from backup
cp backup_previous.tar.gz /var/www/
cd /var/www
tar -xzf backup_previous.tar.gz

# Restore database
mysql fuel_station_app < backup_previous.sql

# Restart
sudo systemctl start apache2

# Clear cache
php artisan cache:clear
```

---

## Performance Optimization

### Database Query Optimization
```bash
# Add indexes
php artisan tinker
>>> DB::statement("ALTER TABLE users ADD INDEX idx_role (role);");

# Analyze query performance
EXPLAIN SELECT * FROM users WHERE role = 'admin';
```

### Caching Strategy
```php
// In .env
CACHE_DRIVER=redis
CACHE_TTL=3600

// In code
Cache::remember('users.count', 3600, function() {
    return User::count();
});
```

### Asset Optimization
```bash
# Minify CSS/JS
npm run production

# Use CDN for assets (optional)
# Configure in filesystem
```

---

## Checklist for First Production Deployment

- [ ] Domain name registered and pointed to server
- [ ] SSL certificate installed and working (HTTPS)
- [ ] .env file created with production settings
- [ ] Database created and migrations run
- [ ] Permissions set correctly on storage/cache
- [ ] Cache and configuration cached
- [ ] Database backups automated
- [ ] Error logging configured
- [ ] Firewall configured
- [ ] Monitoring set up
- [ ] Email service configured
- [ ] Admin user created
- [ ] Test login works
- [ ] Test admin panel works
- [ ] Performance tested
- [ ] Backup verified
- [ ] Rollback plan documented

---

## Support

- **Deployment Issues**: Check logs and this guide
- **Laravel Deployment**: https://laravel.com/docs/deployment
- **DigitalOcean Tutorials**: https://www.digitalocean.com/community/tutorials
- **Server Monitoring**: Use tools like New Relic, DataDog

---

**DEPLOYMENT GUIDE v1.0**  
**Last Updated**: January 8, 2025
