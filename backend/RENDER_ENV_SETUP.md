# Render Environment Variables Setup Guide

This guide will help you set up your Laravel application on Render.

## Required Environment Variables in Render Dashboard

Set these in your Render service's **Environment** tab:

### Application Settings
```
APP_NAME=Laravel
APP_ENV=production
APP_KEY=<generate using: php artisan key:generate>
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
LOG_LEVEL=error
```

### Database Configuration (PostgreSQL)
Get these from your Render PostgreSQL database dashboard:
```
DB_CONNECTION=pgsql
DB_HOST=<from Render PostgreSQL dashboard>
DB_PORT=5432
DB_DATABASE=<from Render PostgreSQL dashboard>
DB_USERNAME=<from Render PostgreSQL dashboard>
DB_PASSWORD=<from Render PostgreSQL dashboard>
```

### Frontend URL
```
FRONTEND_URL=https://your-frontend-domain.com
```

### Admin Configuration
```
ADMIN_SECRET=<your-secret-key-for-admin-registration>
SEED_ADMIN_EMAIL=admin@example.com
SEED_ADMIN_USERNAME=admin
SEED_ADMIN_PASSWORD=<strong-password>
```

### Optional: Redis (if using)
```
REDIS_HOST=<from Render Redis dashboard>
REDIS_PASSWORD=<from Render Redis dashboard>
REDIS_PORT=6379
```

## Steps to Deploy on Render

1. **Create a PostgreSQL Database on Render**
   - Go to Render Dashboard → New → PostgreSQL
   - Note down the connection details

2. **Create a Web Service**
   - Go to Render Dashboard → New → Web Service
   - Connect your repository
   - Set the following:
     - **Root Directory**: `backend` (important!)
     - **Environment**: Docker
     - **Dockerfile Path**: `backend/Dockerfile` (or just `Dockerfile` if root directory is set to backend)
     - **Docker Context**: `backend` (or leave blank if root directory is set)
     - No build/start commands needed - Dockerfile handles everything

3. **Set Environment Variables**
   - Go to your service → Environment tab
   - Add all the variables listed above
   - **Important**: Generate `APP_KEY` by running `php artisan key:generate` locally and copy the value

4. **Deploy**
   - Render will automatically build and deploy your application

## Notes

- The `APP_KEY` must be generated. Run `php artisan key:generate` locally and copy the base64 key.
- Database migrations will run automatically on each deploy (via docker-entrypoint.sh).
- The Dockerfile uses a multi-stage build for optimized image size.
- Make sure your `FRONTEND_URL` matches your actual frontend domain for CORS to work.
- Never commit `.env` files with real credentials to Git.
- The `.env.render` file is a template - use it as reference but set actual values in Render's dashboard.

## Quick Start Checklist

- [ ] Create PostgreSQL database on Render
- [ ] Create Web Service with Docker environment
- [ ] Set Root Directory to `backend`
- [ ] Generate APP_KEY: `php artisan key:generate` (copy the output)
- [ ] Set all environment variables in Render dashboard
- [ ] Update APP_URL with your Render service URL
- [ ] Update FRONTEND_URL with your frontend URL
- [ ] Deploy and verify!

