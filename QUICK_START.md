# 🚀 Quick Start: Deploy Lami to Render

## Automated Deployment (Recommended)

### Step 1: Push to Git
```bash
git add .
git commit -m "Add Render deployment configuration"
git push
```

### Step 2: Deploy on Render
1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click **"New"** → **"Blueprint"**
3. Connect your GitHub/GitLab/Bitbucket repository
4. Render will automatically:
   - ✅ Create PostgreSQL database (`lami-database`)
   - ✅ Create web service (`lami-backend`)
   - ✅ Configure all environment variables
   - ✅ Build and deploy your application

### Step 3: Set Manual Variables (After First Deploy)
Go to `lami-backend` service → **Environment** tab and add:

```bash
# Generate these values:
ADMIN_SECRET=$(openssl rand -hex 32)
FRONTEND_URL=https://your-frontend-domain.com
SEED_ADMIN_PASSWORD=your-strong-password-here
```

**Note:** `APP_KEY` will be auto-generated on first run if not set.

### Step 4: Verify Deployment
- Check service logs for successful migration
- Test API: `https://lami-backend.onrender.com/api/cars`
- Verify health check is passing

## 🎯 That's It!

Your Laravel backend is now live on Render with:
- ✅ Automatic database setup
- ✅ Automatic environment variable configuration
- ✅ Docker-based deployment
- ✅ Auto-migrations on startup
- ✅ Health checks enabled

## 📝 What You Need to Set Manually

Only 3 variables need manual setup:
1. **ADMIN_SECRET** - For admin registration security
2. **FRONTEND_URL** - Your frontend URL for CORS
3. **SEED_ADMIN_PASSWORD** - Admin user password

Everything else is automated! 🎉

## 🔧 Troubleshooting

**Build fails?**
- Check that `backend/Dockerfile` exists
- Verify `render.yaml` is in repository root

**Database connection error?**
- Wait a few minutes for database to be fully provisioned
- Check database service is running

**APP_KEY issues?**
- Check service logs - it should auto-generate
- Or set manually: `php artisan key:generate --show` in Render shell

## 📚 More Details

See `RENDER_DEPLOYMENT.md` for complete documentation.

