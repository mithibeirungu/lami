# Automated Render Deployment Guide for Lami

This project uses Render's Infrastructure as Code (IaC) with `render.yaml` to automate deployment.

## 🚀 Quick Start

### Option 1: One-Click Deploy (Recommended)

1. **Push your code to GitHub/GitLab/Bitbucket**
   - Make sure `render.yaml` is in the repository root
   - Make sure `backend/Dockerfile` exists

2. **Connect to Render**
   - Go to [Render Dashboard](https://dashboard.render.com)
   - Click "New" → "Blueprint"
   - Connect your repository
   - Render will automatically detect `render.yaml` and create all services

3. **Set Manual Environment Variables** (Optional - can be done after deployment)
   After the initial deployment, go to your `lami-backend` service → Environment tab and set:
   - `ADMIN_SECRET` - Your secret key for admin registration (generate with: `openssl rand -hex 32`)
   - `FRONTEND_URL` - Your frontend URL (e.g., `https://your-frontend.onrender.com` or your custom domain)
   - `SEED_ADMIN_PASSWORD` - Strong password for the admin user
   - `APP_KEY` - (Optional) Will be auto-generated on first run if not set. To set manually:
     - Run locally: `cd backend && ./generate-app-key.sh`
     - Or in Render shell: `php artisan key:generate --show`

4. **Deploy!**
   - Render will automatically build and deploy
   - The database will be created automatically
   - Migrations will run on startup

### Option 2: Manual Setup (Alternative)

If you prefer to set up manually:

1. Create a PostgreSQL database named `lami-database`
2. Create a Web Service:
   - Root Directory: `backend`
   - Environment: Docker
   - Dockerfile Path: `backend/Dockerfile`
3. Copy environment variables from `render.yaml` to the dashboard

## 📋 What Gets Created Automatically

- ✅ **PostgreSQL Database** (`lami-database`)
  - Database name: `lami`
  - User: `lami_user`
  - All connection details automatically linked

- ✅ **Web Service** (`lami-backend`)
  - Docker-based deployment
  - All environment variables configured
  - Database connection automatically linked
  - Health checks enabled

## 🔧 Manual Configuration Required

After initial deployment, you **must** set these in Render dashboard:

1. **ADMIN_SECRET**
   - Used for admin registration endpoint
   - Generate a secure random string
   - Example: `openssl rand -hex 32`

2. **FRONTEND_URL**
   - Your frontend application URL
   - Used for CORS configuration
   - Example: `https://lami-frontend.onrender.com`

3. **SEED_ADMIN_PASSWORD**
   - Password for the admin user
   - Use a strong password

4. **APP_KEY** (if not auto-generated)
   - Run `php artisan key:generate` in the shell
   - Copy the output to Environment Variables

## 🔐 Security Notes

- Never commit sensitive values to `render.yaml`
- Variables marked with `sync: false` must be set manually
- The `APP_KEY` should be generated and kept secret
- Database credentials are automatically managed by Render

## 📝 Environment Variables Reference

All environment variables are defined in `render.yaml`. Key variables:

- **Database**: Automatically linked from `lami-database` service
- **APP_URL**: Automatically set from service hostname
- **APP_KEY**: Auto-generated (or set manually)
- **Manual**: `ADMIN_SECRET`, `FRONTEND_URL`, `SEED_ADMIN_PASSWORD`

## 🛠️ Updating Configuration

To update environment variables:

1. **Via render.yaml** (recommended for non-sensitive values):
   - Edit `render.yaml`
   - Push to repository
   - Render will auto-update

2. **Via Dashboard** (for sensitive values):
   - Go to service → Environment tab
   - Add/edit variables
   - Redeploy

## 🐛 Troubleshooting

### Database Connection Issues
- Check that database service is running
- Verify environment variables are set correctly
- Check database logs in Render dashboard

### Build Failures
- Ensure `backend/Dockerfile` exists
- Check build logs for specific errors
- Verify all dependencies in `composer.json` and `package.json`

### Migration Errors
- Check database connection
- Verify database user has proper permissions
- Review migration files for compatibility

## 📚 Additional Resources

- [Render Documentation](https://render.com/docs)
- [Render Blueprint Spec](https://render.com/docs/blueprint-spec)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)

## ✅ Post-Deployment Checklist

- [ ] Database is running and accessible
- [ ] Web service is deployed and healthy
- [ ] `APP_KEY` is set (or auto-generated - check logs)
- [ ] `ADMIN_SECRET` is set (required for admin registration)
- [ ] `FRONTEND_URL` is set (required for CORS)
- [ ] `SEED_ADMIN_PASSWORD` is set (if using admin seeder)
- [ ] Migrations ran successfully (check logs)
- [ ] API endpoints are accessible
- [ ] CORS is configured correctly
- [ ] Health check is passing

## 🎯 Quick Commands

### Generate APP_KEY locally:
```bash
cd backend
./generate-app-key.sh
```

### Generate ADMIN_SECRET:
```bash
openssl rand -hex 32
```

### Test API after deployment:
```bash
curl https://lami-backend.onrender.com/api/cars
```

