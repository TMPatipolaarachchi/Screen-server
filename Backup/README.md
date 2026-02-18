# Fuel Station Management System

A comprehensive Laravel web application for managing fuel station operations with user authentication, role-based access control, and user management features.

## Features

- **User Authentication**: Secure login and registration system
- **User Roles**: Admin and regular user roles with different permissions
- **User Management**: Admins can view, edit, and delete users
- **Profile Management**: Users can update their personal information (name, email, phone, NIC number)
- **Password Management**: Secure password change functionality
- **Dashboard**: Role-based dashboard for different user types
- **Soft Delete**: Users are soft-deleted and can be restored
- **Modern UI**: Built with Bootstrap 5 with fuel station themed colors (green, yellow, black)
- **Responsive Design**: Works on desktop, tablet, and mobile devices

## Technology Stack

- **Backend**: Laravel 12
- **Database**: MySQL / SQLite
- **Frontend**: Blade Templates with Bootstrap 5
- **Authentication**: Laravel built-in authentication
- **Frontend Icons**: FontAwesome 6.4

## Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8.0+ (or SQLite)
- Node.js & npm (for frontend assets, optional)

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/fuel_station_app.git
cd fuel_station_app
```

### 2. Install Composer Dependencies

```bash
composer install
```

### 3. Setup Environment File

```bash
cp .env.example .env
```

Edit the `.env` file and configure your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fuel_station_app
DB_USERNAME=root
DB_PASSWORD=
```

Or for SQLite (no configuration needed, database is file-based):

```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Database Migrations

```bash
php artisan migrate
```

### 6. Seed Default Data

This will create a default admin user and test users:

```bash
php artisan db:seed
```

### 7. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Default Credentials

After seeding, you can login with these credentials:

### Admin Account
- **Email**: `admin@example.com`
- **Password**: `password`
- **Role**: Administrator

### Test User Account
- **Email**: `user@example.com`
- **Password**: `password`
- **Role**: Regular User

## Project Structure

```
fuel_station_app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php      # Authentication logic
│   │   │   ├── AdminController.php         # Admin user management
│   │   │   ├── ProfileController.php       # User profile management
│   │   │   └── HomeController.php          # Home dashboard
│   │   └── Middleware/
│   │       └── IsAdmin.php                 # Admin role verification
│   └── Models/
│       └── User.php                        # User model with soft deletes
├── database/
│   ├── migrations/
│   │   └── 2025_01_08_000001_add_roles_and_soft_deletes_to_users_table.php
│   └── seeders/
│       └── DatabaseSeeder.php              # Default data seeding
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php               # Main layout template
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── profile/
│       │   ├── show.blade.php
│       │   └── edit.blade.php
│       ├── admin/
│       │   └── users/
│       │       ├── index.blade.php
│       │       └── edit.blade.php
│       └── home.blade.php                  # Dashboard
├── routes/
│   └── web.php                            # All route definitions
├── bootstrap/
│   └── app.php                            # Middleware registration
└── public/
    └── (CSS, JS, images)
```

## Routes

### Public Routes (Unauthenticated)
- `GET /login` - Login page
- `POST /login` - Process login
- `GET /register` - Registration page
- `POST /register` - Process registration

### Authenticated Routes
- `GET /` - Dashboard/Home
- `POST /logout` - Logout



### Admin Routes (Authenticated + Admin Role)
- `GET /admin/users` - View all users
- `GET /admin/users/{id}/edit` - Edit user
- `PUT /admin/users/{id}` - Update user
- `DELETE /admin/users/{id}` - Soft delete user
- `POST /admin/users/{id}/restore` - Restore deleted user
- `DELETE /admin/users/{id}/force` - Permanently delete user

## Database Schema

### Users Table

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    phone VARCHAR(20) NULL,
    nic_number VARCHAR(20) UNIQUE NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL  -- Soft deletes
);
```

## Middleware

### Authentication Middleware
Protects routes requiring authenticated users. Applied to home, profile, and admin routes.

### Admin Middleware (IsAdmin)
Verifies user has 'admin' role. Applied to `/admin/*` routes.

## Security Features

- Password hashing using bcrypt
- CSRF protection on all forms
- SQL injection prevention through Eloquent ORM
- XSS protection through Blade escaping
- Soft deletes for user privacy
- Role-based access control (RBAC)

## Usage Examples

### Login as Admin
1. Navigate to `http://localhost:8000/login`
2. Enter `admin@example.com` and password `password`
3. Access admin panel from navbar

### Create New User
1. Click "Register" on login page
2. Fill in required information
3. Submit form to create account

### Edit User Profile
1. Login as any user
2. Click "Profile" in navbar
3. Click "Edit Profile" button
4. Update information and save

### Manage Users (Admin Only)
1. Login as admin
2. Click "Manage Users" in navbar
3. View all users in table
4. Click "Edit" to modify user details or role
5. Click "Delete" to soft delete
6. Click "Restore" to restore deleted users

## Customization

### Change Fuel Station Theme Colors

Edit the theme colors in `resources/views/layouts/app.blade.php`:

```css
:root {
    --fuel-green: #2d5016;      /* Primary green */
    --fuel-yellow: #ffc300;     /* Accent yellow */
    --fuel-black: #1a1a1a;      /* Dark background */
}
```

### Add More User Fields

1. Create a migration:
   ```bash
   php artisan make:migration add_new_fields_to_users_table
   ```

2. Add columns in the migration
3. Update the User model's `$fillable` array
4. Update form views

## Common Commands

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Refresh database and seed
php artisan migrate:fresh --seed

# Create a backup before production
php artisan backup:run

# Clear all caches
php artisan cache:clear

# View application logs
tail -f storage/logs/laravel.log
```

## Troubleshooting

### Database Connection Error
- Verify `.env` database credentials
- Ensure MySQL is running
- Check database name exists

### Missing Migrations
```bash
php artisan migrate
```

### Seeding Issues
```bash
php artisan migrate:fresh --seed
```

### Permission Denied Errors
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## Git Configuration

Before pushing to GitHub:

```bash
# Initialize git repository
git init

# Add all files
git add .

# Initial commit
git commit -m "Initial commit: Fuel Station Management System"

# Add remote origin (replace with your repo URL)
git remote add origin https://github.com/yourusername/fuel_station_app.git

# Push to main branch
git branch -M main
git push -u origin main
```

## Environment Variables Reference

```env
# App
APP_NAME="Fuel Station"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database - MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fuel_station_app
DB_USERNAME=root
DB_PASSWORD=

# Or for SQLite
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Mail
MAIL_MAILER=log
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
```

## API Response Examples

All routes return HTML views. For API responses, consider using Laravel API resources.

## Contributing

1. Create a feature branch
2. Make your changes
3. Test thoroughly
4. Commit with clear messages
5. Push to your fork
6. Create a pull request

## License

This project is open source and available under the MIT license.

## Support

For issues or questions, please create an issue in the GitHub repository or contact the development team.

## Future Enhancements

- [ ] Fuel pump management
- [ ] Transaction history
- [ ] Email notifications
- [ ] SMS alerts
- [ ] Export reports to PDF/Excel
- [ ] API endpoints
- [ ] Two-factor authentication
- [ ] Activity logging
- [ ] User avatar upload
- [ ] Dark mode support

---

**Version**: 1.0.0  
**Last Updated**: January 8, 2025  
**Author**: Development Team

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
