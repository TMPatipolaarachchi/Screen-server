# ⚡ QUICK START GUIDE - GET RUNNING IN 5 MINUTES

## 🚀 Start Here

This guide will get your Fuel Station app running in 5 minutes.

---

## ✅ Prerequisites Check

Make sure you have installed:
- [x] PHP 8.2 or higher
- [x] Composer
- [x] Git (optional)

**Check Installation:**
```bash
php --version
composer --version
```

---

## 📦 Step 1: Navigate to Project (30 seconds)

```bash
cd "c:\Users\thari\Desktop\fuel station\fuel_station_app"
```

Or open the folder in your file explorer.

---

## 🎬 Step 2: Start the Server (30 seconds)

In terminal/command prompt:

```bash
php artisan serve
```

You'll see:
```
Starting Laravel development server: http://127.0.0.1:8000
```

---

## 🌐 Step 3: Open in Browser (30 seconds)

Click the link or go to: **http://localhost:8000**

---

## 🔐 Step 4: Login (1 minute)

### Option A: Login as Admin
1. Click "Login" link
2. Enter:
   - **Email**: `admin@example.com`
   - **Password**: `password`
3. Click "Login"

### Option B: Login as Regular User
1. Click "Login" link
2. Enter:
   - **Email**: `user@example.com`
   - **Password**: `password`
3. Click "Login"

---

## 🎯 Step 5: Explore Features (2 minutes)

### As Admin:
1. Click **"Manage Users"** in navbar
2. See all users in table
3. Click **"Edit"** to modify user
4. Click **"Delete"** to remove user
5. Click **"Restore"** to restore deleted user

### As Regular User:
1. Click **"Profile"** in navbar
2. Click **"Edit Profile"** to update info
3. Edit name, email, phone, NIC number
4. Update password if needed
5. See your profile info on dashboard

### As Anyone:
1. View dashboard on home page
2. Click "Logout" to logout
3. Register a new account if you want

---

## 📝 What to Try Next

### Try These Actions:
1. ✅ Login with admin account
2. ✅ Go to admin panel (Manage Users)
3. ✅ Edit a user
4. ✅ Delete a user
5. ✅ Restore a deleted user
6. ✅ Logout
7. ✅ Login with regular user
8. ✅ Edit your profile
9. ✅ Change your password
10. ✅ Logout

All should work perfectly!

---

## 🛑 Having Issues?

### Server Won't Start
```bash
# Try different port:
php artisan serve --port=8001
```

### Database Error
```bash
# Refresh database:
php artisan migrate:fresh --seed
```

### Can't Login
Check credentials:
- Admin: `admin@example.com` / `password`
- User: `user@example.com` / `password`

### Page Not Loading
1. Stop server (Ctrl+C)
2. Start again: `php artisan serve`
3. Clear browser cache
4. Try different browser

---

## 📚 Learn More

Want to know more? Check these files:

1. **[README.md](README.md)** - Full project overview
2. **[QUICK_REFERENCE.md](QUICK_REFERENCE.md)** - Developer guide
3. **[INSTALLATION.md](INSTALLATION.md)** - Detailed setup
4. **[PROJECT_SUMMARY.md](PROJECT_SUMMARY.md)** - Complete details
5. **[DEPLOYMENT.md](DEPLOYMENT.md)** - Go live guide

---

## 🎨 Customize the App

### Change App Name
Edit `.env` file:
```env
APP_NAME="My Fuel Station"
```

### Change Theme Colors
Edit `resources/views/layouts/app.blade.php`:
```css
--fuel-green: #2d5016;      /* Change this */
--fuel-yellow: #ffc300;     /* Or this */
--fuel-black: #1a1a1a;      /* Or this */
```

### Add More Users
Login as admin → Manage Users → Each user can register

---

## 🚀 Deploy Online

Ready to put it online?

1. Read **[DEPLOYMENT.md](DEPLOYMENT.md)**
2. Choose hosting (Heroku easiest)
3. Deploy in 10 minutes!

---

## 📞 Support

- Check [README.md](README.md) for help
- Read [QUICK_REFERENCE.md](QUICK_REFERENCE.md) for solutions
- Refer to [INSTALLATION.md](INSTALLATION.md) for setup issues

---

## 🎯 Key Features at a Glance

| Feature | Where to Find |
|---------|---------------|
| Login | `/login` |
| Register | `/register` |
| Dashboard | `/` (home) |
| Your Profile | `/profile` |
| Edit Profile | `/profile/edit` |
| Manage Users | `/admin/users` (admin only) |
| Logout | Click logout button |

---

## ⭐ Pro Tips

1. **Use Admin Account** for full feature access
2. **Try Both Roles** to see different views
3. **Test Delete** then **Restore** functionality
4. **Check Responsive** - resize browser window
5. **Review Code** - it's well-commented!

---

## ✅ Your First 5 Minutes Checklist

- [ ] Open terminal/command prompt
- [ ] Navigate to project folder
- [ ] Run `php artisan serve`
- [ ] Open `http://localhost:8000`
- [ ] Login with admin account
- [ ] Explore admin panel
- [ ] Logout
- [ ] Login with regular user
- [ ] Edit profile
- [ ] Logout

**Done! 🎉**

---

## 📝 Notes

- All features are **fully working**
- Database is **pre-seeded** with test users
- You can **modify anything** you want
- All code is **well-commented**
- Ready for **production deployment**

---

**You're all set! Enjoy your Fuel Station Management System! 🛢️**

---

**QUICK START GUIDE v1.0**  
**Last Updated**: January 8, 2025
