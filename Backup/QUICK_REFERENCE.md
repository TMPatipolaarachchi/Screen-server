# Quick Reference Guide

## File Structure Overview

```
fuel_station_app/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php ..................... Login/Register logic
│   │   │   ├── AdminController.php ......................... User management
│   │   │   ├── ProfileController.php ....................... Profile management
│   │   │   └── HomeController.php .......................... Dashboard
│   │   └── Middleware/
│   │       └── IsAdmin.php ................................. Admin role check
│   │
│   └── Models/
│       └── User.php ........................................ User model with soft deletes
│
├── database/
│   ├── migrations/
│   │   ├── 2025_01_08_000000_create_users_table.php
│   │   └── 2025_01_08_000001_add_roles_and_soft_deletes_to_users_table.php
│   │
│   └── seeders/
│       └── DatabaseSeeder.php .............................. Default data
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php ............................... Main layout
│       ├── auth/
│       │   ├── login.blade.php ............................. Login form
│       │   └── register.blade.php .......................... Register form
│       ├── profile/
│       │   ├── show.blade.php .............................. View profile
│       │   └── edit.blade.php .............................. Edit profile
│       ├── admin/
│       │   └── users/
│       │       ├── index.blade.php ......................... User list
│       │       └── edit.blade.php .......................... Edit user
│       └── home.blade.php .................................. Dashboard
│
├── routes/
│   └── web.php ............................................. All routes
│
├── bootstrap/
│   └── app.php ............................................. Middleware setup
│
├── .env.example ............................................ Environment template
├── .gitignore .............................................. Git ignore rules
├── README.md ............................................... Project documentation
├── INSTALLATION.md ......................................... Installation guide
└── QUICK_REFERENCE.md ...................................... This file
```

## Database Schema

### Users Table
```sql
users
├── id (bigint, PK, auto-increment)
├── name (string)
├── email (string, unique)
├── email_verified_at (timestamp, nullable)
├── password (string, hashed)
├── role (enum: 'admin', 'user', default: 'user')
├── phone (string, nullable)
├── nic_number (string, unique, nullable)
├── remember_token (string, nullable)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp, nullable - soft delete)
```

## Routes Quick Map

### Public Routes
| Method | Route | Controller | Method | Auth |
|--------|-------|------------|--------|------|
| GET | /login | Auth\AuthController | loginForm | No |
| POST | /login | Auth\AuthController | login | No |
| GET | /register | Auth\AuthController | registerForm | No |
| POST | /register | Auth\AuthController | register | No |

### Authenticated Routes
| Method | Route | Controller | Method | Auth | Admin |
|--------|-------|------------|--------|------|-------|
| GET | / | HomeController | index | Yes | - |
| POST | /logout | Auth\AuthController | logout | Yes | - |


### Admin Routes
| Method | Route | Controller | Method | Auth | Admin |
|--------|-------|------------|--------|------|-------|
| GET | /admin/users | AdminController | users | Yes | Yes |
| GET | /admin/users/{id}/edit | AdminController | editUser | Yes | Yes |
| PUT | /admin/users/{id} | AdminController | updateUser | Yes | Yes |
| DELETE | /admin/users/{id} | AdminController | deleteUser | Yes | Yes |
| POST | /admin/users/{id}/restore | AdminController | restoreUser | Yes | Yes |
| DELETE | /admin/users/{id}/force | AdminController | forceDeleteUser | Yes | Yes |

## Common Laravel Commands

### Development
```bash
# Start development server
php artisan serve

# Alternative port
php artisan serve --port=8001

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Tail logs
tail -f storage/logs/laravel.log
```

### Database
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Refresh migrations (WARNING: Deletes data!)
php artisan migrate:refresh

# Fresh migrations with seed
php artisan migrate:fresh --seed

# Seed database
php artisan db:seed

# Make new migration
php artisan make:migration create_table_name

# Make model with migration
php artisan make:model ModelName -m
```

### Tinker (Interactive Shell)
```bash
# Start tinker
php artisan tinker

# Check database connection
>>> DB::connection()->getPdo()

# Query users
>>> App\Models\User::all()

# Create user
>>> App\Models\User::create(['name' => 'Test', 'email' => 'test@example.com', 'password' => bcrypt('password')])

# Exit tinker
>>> exit()
```

### Code Generation
```bash
# Generate controller
php artisan make:controller ControllerName

# Generate model
php artisan make:model ModelName

# Generate middleware
php artisan make:middleware MiddlewareName

# Generate seeder
php artisan make:seeder SeederName
```

## Authentication Flow

### User Registration
1. User navigates to `/register`
2. Fills in name, email, password
3. System validates input
4. Password is hashed with bcrypt
5. User created with `role = 'user'` (default)
6. User is auto-logged in
7. Redirects to home dashboard

### User Login
1. User navigates to `/login`
2. Enters email and password
3. System validates credentials
4. Session is created if valid
5. Redirects to requested page or home

### Permission Check
1. Middleware checks if user is authenticated
2. If admin route, checks if `user->role === 'admin'`
3. If unauthorized, redirects with error message

## Key Features Implementation

### Soft Deletes
```php
// In User model
use SoftDeletes;

// Delete user (soft delete)
$user->delete();

// Restore user
$user->restore();

// Permanently delete
$user->forceDelete();

// Include soft deleted
User::withTrashed()->all();

// Only soft deleted
User::onlyTrashed()->all();
```

### Role-Based Access Control
```php
// In controller
if (auth()->user()->role === 'admin') {
    // Admin only code
}

// In routes
Route::middleware(['admin'])->group(function () {
    // Admin only routes
});

// In view
@if (auth()->user()->role === 'admin')
    // Admin only UI
@endif
```

### Password Hashing
```php
// Hash password
Hash::make($password);

// Check password
Hash::check($inputPassword, $user->password);

// Update password
$user->update(['password' => Hash::make($newPassword)]);
```

## Theme Customization

### Colors (in layouts/app.blade.php)
```css
:root {
    --fuel-green: #2d5016;      /* Primary */
    --fuel-yellow: #ffc300;     /* Accent */
    --fuel-black: #1a1a1a;      /* Dark */
}
```

### Add New Color Scheme
```css
/* Add to app.blade.php */
--fuel-secondary: #your-color;

/* Use in CSS */
background-color: var(--fuel-secondary);
```

## Security Checklist

- [x] CSRF protection (enabled by default)
- [x] Password hashing (bcrypt)
- [x] SQL injection prevention (Eloquent ORM)
- [x] XSS protection (Blade escaping)
- [x] Authentication middleware
- [x] Authorization (admin role check)
- [x] Soft deletes (data preservation)
- [x] Rate limiting ready
- [x] HTTPS ready
- [x] Environment variables (.env)

## Deployment Checklist

Before deploying to production:

```bash
# 1. Update .env
APP_DEBUG=false
APP_ENV=production

# 2. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Database
php artisan migrate --force

# 4. Permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 5. Test critical routes
- Login works
- Admin can manage users
- User profile editable
- Logout works
```

## Useful Development Resources

- **Laravel Docs**: https://laravel.com/docs
- **Bootstrap Docs**: https://getbootstrap.com/docs
- **FontAwesome Icons**: https://fontawesome.com/icons
- **Blade Templates**: https://laravel.com/docs/blade
- **Eloquent ORM**: https://laravel.com/docs/eloquent

## Common Issues & Solutions

### Issue: "Class not found"
**Solution**: Run `composer autoload`
```bash
composer dump-autoload
```

### Issue: "View not found"
**Solution**: Check view path and file extension (.blade.php)

### Issue: "CSRF token mismatch"
**Solution**: Ensure @csrf is in form
```blade
<form method="POST">
    @csrf
    <!-- form fields -->
</form>
```

### Issue: "Undefined variable"
**Solution**: Pass variable from controller
```php
return view('profile.edit', ['user' => $user]);
```

### Issue: "Session expires quickly"
**Solution**: Update `.env` SESSION_LIFETIME
```env
SESSION_LIFETIME=120  # minutes
```

## Version Information

- **Laravel**: 12.x
- **PHP**: 8.2+
- **Bootstrap**: 5.3.0
- **FontAwesome**: 6.4.0

---

**Quick Reference Guide v1.0**  
**Last Updated**: January 8, 2025
