# 🚀 Deployment Guide for TAPAH HOMEBASED

This guide will help you deploy your application with **Vercel (Frontend)** and **Railway (Backend)**.

---

## 📋 Prerequisites

- [x] Vercel account: https://vercel.com
- [x] Railway account: https://railway.app
- [x] GitHub repository connected to both services

---

## 🔧 Backend Deployment (Railway)

### Step 1: Configure Environment Variables in Railway

Go to your Railway project dashboard and add the following environment variables:

```env
# Application Settings
APP_NAME=TAPAH_HOMEBASED
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tapahhomebased-production.up.railway.app

# Frontend URL (for OAuth redirects)
FRONTEND_URL=https://tapah-homebased.vercel.app

# App Key (Generate using: php artisan key:generate)
APP_KEY=base64:YOUR_GENERATED_KEY_HERE

# Database (Railway will auto-provide if you add a database service)
DB_CONNECTION=mysql
DB_HOST=${MYSQLHOST}
DB_PORT=${MYSQLPORT}
DB_DATABASE=${MYSQLDATABASE}
DB_USERNAME=${MYSQLUSER}
DB_PASSWORD=${MYSQLPASSWORD}

# Or use SQLite for simpler setup:
# DB_CONNECTION=sqlite

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database

# Sanctum Stateful Domains (allows your Vercel frontend to authenticate)
SANCTUM_STATEFUL_DOMAINS=tapah-homebased.vercel.app,localhost:5173

# CORS Settings (handled in code, but you can override)
# FRONTEND_URL is already set above

# Mail Settings (optional - for production emails)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=hello@tapahhomebased.com
MAIL_FROM_NAME="${APP_NAME}"

# Google OAuth (if you're using Google Sign-in)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://tapahhomebased-production.up.railway.app/auth/google/callback
```

### Step 2: Build & Start Command

Railway should auto-detect Laravel, but if needed, configure:

**Build Command:**
```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Start Command:**
```bash
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

### Step 3: Add a Database (Optional)

If using MySQL/PostgreSQL:
1. In Railway, click **"+ New"** → **Database** → Choose MySQL or PostgreSQL
2. Railway will automatically inject `DATABASE_URL` and related variables
3. Update your `.env` to use these variables

---

## 🎨 Frontend Deployment (Vercel)

### Step 1: Configure Environment Variables in Vercel

Go to your Vercel project → **Settings** → **Environment Variables** and add:

```env
# Backend API URL
VITE_API_BASE_URL=https://tapahhomebased-production.up.railway.app

# Encryption Key (use the same key in production for consistency)
VITE_CRYPTO_KEY=YOUR_RANDOM_32_CHAR_STRING_HERE
```

**Important Notes:**
- `VITE_API_BASE_URL` must be your Railway backend URL **without `/api`** at the end
- `VITE_CRYPTO_KEY` should be a random 32-character string for security (generate one at: https://www.random.org/strings/)

### Step 2: Build Settings

Vercel should auto-detect Vite, but verify these settings:

**Framework Preset:** Vite
**Root Directory:** `frontend`
**Build Command:** `npm run build`
**Output Directory:** `dist`
**Install Command:** `npm install`

### Step 3: Deploy

1. Connect your GitHub repository to Vercel
2. Select the `frontend` directory as the root
3. Add the environment variables above
4. Click **Deploy**

---

## ✅ Post-Deployment Checklist

After deploying both services, verify the following:

### Backend (Railway)

- [ ] Visit: `https://tapahhomebased-production.up.railway.app`
- [ ] You should see a Laravel default page or API response
- [ ] Test API endpoint: `https://tapahhomebased-production.up.railway.app/api/login`
- [ ] Check Railway logs for any errors

### Frontend (Vercel)

- [ ] Visit: `https://tapah-homebased.vercel.app`
- [ ] Login page should load without errors
- [ ] Open browser DevTools → Network tab
- [ ] Try logging in (check if API calls go to Railway backend)
- [ ] Check for CORS errors (there shouldn't be any)

### OAuth & Authentication

- [ ] Google Sign-in button works (if configured)
- [ ] OAuth redirects back to Vercel frontend with token
- [ ] Token is stored in sessionStorage
- [ ] You can access protected routes after login

---

## 🐛 Common Issues & Solutions

### Issue 1: CORS Errors

**Error:** `Access to XMLHttpRequest blocked by CORS policy`

**Solution:**
- Verify `FRONTEND_URL` is set correctly in Railway
- Check `backend/config/cors.php` includes your Vercel domain
- Restart Railway service after changing environment variables

### Issue 2: 404 on Frontend Routes

**Error:** Refreshing any page (except homepage) gives 404

**Solution:**
- Ensure `vercel.json` exists in the frontend directory (already created)
- Redeploy frontend on Vercel

### Issue 3: API Returns 401 Unauthorized

**Error:** All authenticated API calls fail

**Solution:**
- Verify `SANCTUM_STATEFUL_DOMAINS` includes `tapah-homebased.vercel.app`
- Check that `VITE_API_BASE_URL` is set correctly in Vercel
- Clear browser cache and sessionStorage

### Issue 4: Google OAuth Fails

**Error:** OAuth redirect fails or returns error

**Solution:**
- Verify Google Cloud Console has correct redirect URIs:
  - `https://tapahhomebased-production.up.railway.app/auth/google/callback`
- Check `FRONTEND_URL` is set in Railway environment variables
- Verify `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` are set correctly

---

## 🔒 Security Recommendations

1. **Never commit `.env` files** to Git
2. **Use strong `APP_KEY`** - Generate with `php artisan key:generate`
3. **Use strong `VITE_CRYPTO_KEY`** - Random 32+ character string
4. **Disable debug mode** in production: `APP_DEBUG=false`
5. **Use HTTPS** for all production URLs (Vercel and Railway use HTTPS by default)
6. **Rotate secrets regularly** (API keys, tokens, etc.)

---

## 📚 Additional Resources

- **Laravel Deployment Docs:** https://laravel.com/docs/10.x/deployment
- **Vercel Deployment Docs:** https://vercel.com/docs
- **Railway Deployment Docs:** https://docs.railway.app
- **Sanctum SPA Authentication:** https://laravel.com/docs/10.x/sanctum#spa-authentication

---

## 🎯 Quick Reference: Your Deployment URLs

| Service | URL |
|---------|-----|
| **Frontend (Vercel)** | https://tapah-homebased.vercel.app |
| **Backend (Railway)** | https://tapahhomebased-production.up.railway.app |
| **API Endpoint** | https://tapahhomebased-production.up.railway.app/api |

---

## 🛠 Development vs Production URLs

### Development (Local)
- **Frontend:** http://localhost:5173
- **Backend:** http://127.0.0.1:8000
- **API:** http://127.0.0.1:8000/api

### Production
- **Frontend:** https://tapah-homebased.vercel.app
- **Backend:** https://tapahhomebased-production.up.railway.app
- **API:** https://tapahhomebased-production.up.railway.app/api

---

## ✨ Need Help?

If you encounter issues:
1. Check Railway logs for backend errors
2. Check Vercel function logs for frontend errors
3. Use browser DevTools → Console/Network tabs
4. Verify all environment variables are set correctly
5. Ensure both services are deployed successfully

Good luck with your deployment! 🚀
