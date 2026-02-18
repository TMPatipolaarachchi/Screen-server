# Installation & Setup Guide

## System Requirements

### Minimum Requirements
- PHP 8.2 or higher
- MySQL 8.0+ OR SQLite 3.x
- Composer 2.0+
- Git (optional but recommended)

### Recommended Environment
- PHP 8.3 or higher
- MySQL 8.0.23+
- Ubuntu 20.04+ / Windows 10+ / macOS 10.15+
- 2GB RAM minimum
- 500MB disk space

## Step-by-Step Installation

### Windows Installation

#### 1. Prerequisites Setup

**Install Composer:**
- Download from https://getcomposer.org/download/
- Run installer and follow prompts
- Verify installation: `composer --version`

**Install PHP:**
- Option A: Use XAMPP (Recommended for beginners)
  - Download from https://www.apachefriends.org/
  - Choose PHP 8.3+ version
  - Install in default location
  
- Option B: Manual PHP installation
  - Download from https://windows.php.net/download/
  - Extract to `C:\php`
  - Add to PATH environment variable

**Verify Installation:**
```cmd
php --version
composer --version
```

#### 2. Clone or Extract Project

```cmd
cd "C:\Users\YourUsername\Desktop\fuel station"
```

#### 3. Install Dependencies

```cmd
cd fuel_station_app
composer install
```

#### 4. Generate Application Key

```cmd
php artisan key:generate
```

#### 5. Configure Environment

```cmd
copy .env.example .env
```

Edit `.env` file and configure database:

**For SQLite (Easiest):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=C:\Users\YourUsername\Desktop\fuel station\fuel_station_app\database\database.sqlite
```

**For MySQL with XAMPP:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fuel_station_app
DB_USERNAME=root
DB_PASSWORD=
```

#### 6. Create MySQL Database (if using MySQL)

Open phpMyAdmin in XAMPP or use Command Line:

```sql
CREATE DATABASE fuel_station_app;
```

#### 7. Run Migrations and Seed

```cmd
php artisan migrate:fresh --seed
```

#### 8. Start the Development Server

```cmd
php artisan serve
```

Access at: `http://localhost:8000`

---

### macOS Installation

#### 1. Install Homebrew

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

#### 2. Install PHP and Composer

```bash
brew install php@8.3 composer mysql@8.0
```

#### 3. Start MySQL Service

```bash
brew services start mysql@8.0
```

#### 4. Clone Project

```bash
cd ~/Desktop/"fuel station"
```

#### 5. Install Dependencies

```bash
cd fuel_station_app
composer install
```

#### 6. Generate Key

```bash
php artisan key:generate
```

#### 7. Configure Environment

```bash
cp .env.example .env
nano .env
```

Configure database section

#### 8. Create Database

```bash
mysql -u root -e "CREATE DATABASE fuel_station_app;"
```

#### 9. Run Migrations

```bash
php artisan migrate:fresh --seed
```

#### 10. Start Server

```bash
php artisan serve
```

---

### Linux Installation (Ubuntu/Debian)

#### 1. Update System

```bash
sudo apt update && sudo apt upgrade -y
```

#### 2. Install Requirements

```bash
sudo apt install -y php8.3 php8.3-cli php8.3-mysql php8.3-xml php8.3-curl php8.3-mbstring composer mysql-server git
```

#### 3. Start MySQL

```bash
sudo service mysql start
```

#### 4. Clone Project

```bash
cd ~/Desktop/"fuel station"
git clone <your-repo-url> fuel_station_app
cd fuel_station_app
```

Or extract ZIP file and navigate to directory.

#### 5. Install Composer Packages

```bash
composer install
```

#### 6. Generate Application Key

```bash
php artisan key:generate
```

#### 7. Configure .env

```bash
cp .env.example .env
nano .env
```

Update database configuration:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=fuel_station_app
DB_USERNAME=root
DB_PASSWORD=
```

#### 8. Create Database

```bash
mysql -u root -p -e "CREATE DATABASE fuel_station_app;"
```

#### 9. Set Permissions

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 10. Run Migrations

```bash
php artisan migrate:fresh --seed
```

#### 11. Start Development Server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

---

## Docker Installation (Optional)

If you prefer Docker:

### 1. Install Docker Desktop
- Download from https://www.docker.com/products/docker-desktop

### 2. Start Containers

```bash
cd fuel_station_app
docker-compose up -d
```

### 3. Install Dependencies

```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate:fresh --seed
```

### 4. Access Application

- URL: `http://localhost:8000`
- Database: Accessible on `localhost:3306`

---

## Database Setup

### Option 1: SQLite (Simplest)

SQLite works out of the box. The database file will be created automatically at:
```
database/database.sqlite
```

No additional configuration needed.

### Option 2: MySQL

#### Windows (XAMPP)

1. Open XAMPP Control Panel
2. Start Apache and MySQL modules
3. Click "Admin" next to MySQL to open phpMyAdmin
4. Create database:
   ```sql
   CREATE DATABASE fuel_station_app;
   ```

#### Command Line (All Systems)

```bash
mysql -u root -p
```

Then:
```sql
CREATE DATABASE fuel_station_app;
CREATE USER 'fuel_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON fuel_station_app.* TO 'fuel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fuel_station_app
DB_USERNAME=fuel_user
DB_PASSWORD=your_password
```

---

## Verification Checklist

After installation, verify everything works:

```bash
# Check PHP version (should be 8.2+)
php --version

# Check Composer
composer --version

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()

# Check routes
php artisan route:list | grep -E "(home|login|register|admin)"

# Run tests
php artisan test
```

---

## Default Login Credentials

After running migrations with seeders:

### Admin Account
- **Email**: `admin@example.com`
- **Password**: `password`

### Regular User Account
- **Email**: `user@example.com`
- **Password**: `password`

---

## Troubleshooting

### "Class 'PDO' not found"
**Solution**: Install PHP MySQL extension
```bash
# Ubuntu
sudo apt install php8.3-mysql

# macOS
brew install php@8.3-mysql

# Windows: Uncomment in php.ini
; extension=pdo_mysql
```

### "Access denied for user 'root'@'localhost'"
**Solution**: 
1. Verify MySQL is running
2. Check DB_USERNAME and DB_PASSWORD in .env
3. For XAMPP, default password is usually empty

### "SQLSTATE[HY000]: General error: 1 no such table: users"
**Solution**: 
```bash
php artisan migrate:fresh --seed
```

### "The storage path is not writable"
**Solution**:
```bash
# Linux/macOS
chmod -R 775 storage bootstrap/cache

# Windows (Run as Administrator)
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

### Port 8000 Already in Use
**Solution**: Use different port
```bash
php artisan serve --port=8001
```

### Composer Memory Issues
**Solution**: Increase memory
```bash
composer install --no-interaction
# Or
php -d memory_limit=-1 composer install
```

---

## Production Deployment

### Before Going Live

1. **Security**
   ```bash
   # Edit .env
   APP_DEBUG=false
   APP_ENV=production
   ```

2. **Optimize**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Database**
   ```bash
   php artisan migrate --force
   ```

4. **File Permissions**
   ```bash
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 755 storage bootstrap/cache
   ```

5. **SSL Certificate**
   - Use Let's Encrypt (free)
   - Configure HTTPS in web server

### Recommended Hosting

- **Shared Hosting**: Bluehost, HostGator, SiteGround
- **VPS**: DigitalOcean, Linode, Vultr
- **Cloud**: AWS, Google Cloud, Azure
- **Platform**: Heroku, Render, Railway

---

## Next Steps

1. Review [README.md](README.md) for feature overview
2. Check [routes/web.php](routes/web.php) for available endpoints
3. Explore [app/Http/Controllers](app/Http/Controllers) for business logic
4. Review security configurations in [bootstrap/app.php](bootstrap/app.php)

---

## Support & Resources

- **Laravel Documentation**: https://laravel.com/docs
- **Project Issues**: Create issue on GitHub
- **Laravel Forum**: https://laracasts.com/discuss
- **Stack Overflow**: Tag with `laravel` and `fuel-station-app`

---

**Installation Guide Version**: 1.0  
**Last Updated**: January 8, 2025
