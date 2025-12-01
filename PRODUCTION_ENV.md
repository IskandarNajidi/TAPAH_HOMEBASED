# 🚀 Production Environment Variables

This file contains the **exact environment variables** you need to set on Railway and Vercel for production deployment.

---

## 🚂 Railway Backend Environment Variables

**Service:** tapahhomebased-production
**URL:** https://tapahhomebased-production.up.railway.app

Go to: Railway → Your Backend Service → **Variables** tab

Copy and paste these variables:

```env
# ============================================
# Application Configuration
# ============================================
APP_NAME=TAPAH_HOMEBASED
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:+gyyHZbklaqAwVbMD+pgD19Ems/+GmEz3hyFZd/x9IE=

# ============================================
# URLs
# ============================================
APP_URL=https://tapahhomebased-production.up.railway.app
FRONTEND_URL=https://tapah-homebased.vercel.app

# ============================================
# Database (MySQL)
# ============================================
# Railway automatically provides these when you add MySQL database
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

# ============================================
# Session & Cache
# ============================================
SESSION_DRIVER=database
CACHE_STORE=database

# ============================================
# Sanctum (Authentication)
# ============================================
SANCTUM_STATEFUL_DOMAINS=tapah-homebased.vercel.app

# ============================================
# Logging
# ============================================
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

---

## ▲ Vercel Frontend Environment Variables

**Service:** tapah-homebased
**URL:** https://tapah-homebased.vercel.app

Go to: Vercel → Your Project → **Settings** → **Environment Variables**

Add these variables:

```env
# ============================================
# Backend API URL (Railway)
# ============================================
VITE_API_BASE_URL=https://tapahhomebased-production.up.railway.app

# ============================================
# Encryption Key
# ============================================
# Generate a random 32-character string at:
# https://www.random.org/strings/
VITE_CRYPTO_KEY=YOUR_RANDOM_32_CHAR_STRING_HERE
```

**⚠️ Important:** Replace `YOUR_RANDOM_32_CHAR_STRING_HERE` with an actual random string!

---

## 🔧 Railway Settings

### Build Command:
```bash
composer install --no-dev --optimize-autoloader
```

### Start Command:
```bash
php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

**Note:** Railway automatically assigns a port via `$PORT` environment variable. You don't need to specify `:8080` in your APP_URL.

---

## ✅ Deployment Checklist

### Railway (Backend):
- [ ] MySQL database added to Railway project
- [ ] All environment variables set (see above)
- [ ] Build command configured
- [ ] Start command configured
- [ ] Service deployed successfully
- [ ] Check logs for errors
- [ ] Test health endpoint: `https://tapahhomebased-production.up.railway.app/api/health`

### Vercel (Frontend):
- [ ] Environment variables set (VITE_API_BASE_URL and VITE_CRYPTO_KEY)
- [ ] `frontend` directory set as root
- [ ] Build command: `npm run build`
- [ ] Output directory: `dist`
- [ ] Framework preset: Vite
- [ ] Service deployed successfully
- [ ] Test: `https://tapah-homebased.vercel.app`

---

## 🧪 Testing Endpoints

### Backend Health Check:
```
https://tapahhomebased-production.up.railway.app/api/health
```

**Expected Response (Success):**
```json
{
  "status": "healthy",
  "app_name": "TAPAH_HOMEBASED",
  "environment": "production",
  "app_key_set": true,
  "frontend_url": "https://tapah-homebased.vercel.app",
  "database": {
    "status": "connected",
    "type": "mysql",
    "users_table_exists": true,
    "user_count": 0
  }
}
```

### Backend API Login:
```
https://tapahhomebased-production.up.railway.app/api/login
```

**Expected Response (No credentials):**
```json
{
  "message": "The email field is required. (and 1 more error)",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

**❌ Should NOT return:** `500 Internal Server Error`

### Frontend:
```
https://tapah-homebased.vercel.app
```

Should load the login page without errors.

---

## 🔒 Security Notes

1. **APP_KEY:** Already generated for you. Keep it secret!
2. **VITE_CRYPTO_KEY:** Generate a unique random string for production
3. **Never commit .env files** to Git
4. **Set APP_DEBUG=false** in production
5. **Use HTTPS** for all production URLs (already configured)

---

## 📞 Need Help?

If you see errors:
1. Check Railway logs for backend errors
2. Check Vercel function logs for frontend errors
3. Test the `/api/health` endpoint
4. Verify all environment variables are set correctly
5. Ensure MySQL database is running on Railway

---

**Last Updated:** December 1, 2025
**Your Domains:**
- Frontend: https://tapah-homebased.vercel.app
- Backend: https://tapahhomebased-production.up.railway.app
