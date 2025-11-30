# Vercel Deployment Setup Guide

This guide will help you configure your frontend on Vercel to connect to your backend on Render.

## 🔧 Required Environment Variables

Your frontend needs to know where your backend API is hosted. You need to set the following environment variable in Vercel.

### Environment Variable for Vercel

```
VITE_API_URL=https://your-backend-service.onrender.com/api
```

**Important Notes:**
- Replace `your-backend-service` with your actual Render backend service name
- The URL should end with `/api` (this is the API base path)
- Example: `https://lami-backend.onrender.com/api`

## 📝 Steps to Configure on Vercel

### Step 1: Get Your Backend URL
1. Go to your [Render Dashboard](https://dashboard.render.com)
2. Find your backend service (likely named `lami-backend`)
3. Copy the service URL (e.g., `https://lami-backend.onrender.com`)
4. Add `/api` to the end: `https://lami-backend.onrender.com/api`

### Step 2: Set Environment Variable in Vercel

#### Option A: Via Vercel Dashboard (Recommended)
1. Go to your [Vercel Dashboard](https://vercel.com/dashboard)
2. Select your project
3. Go to **Settings** → **Environment Variables**
4. Click **Add New**
5. Add the following:
   - **Key**: `VITE_API_URL`
   - **Value**: `https://your-backend-service.onrender.com/api` (replace with your actual URL)
   - **Environment**: Select all (Production, Preview, Development) or just Production
6. Click **Save**
7. **Important**: After adding the variable, you need to redeploy for it to take effect:
   - Go to **Deployments** tab
   - Click the three dots (⋯) on the latest deployment
   - Click **Redeploy**

#### Option B: Via Vercel CLI
```bash
vercel env add VITE_API_URL
# Enter the value when prompted: https://your-backend-service.onrender.com/api
# Select the environments (production, preview, development)
```

## ✅ Verify Configuration

After setting the environment variable and redeploying:

1. Check your Vercel deployment logs to ensure the build completed successfully
2. Visit your Vercel site
3. Open browser DevTools → Network tab
4. Try to use a feature that calls the API (like viewing cars or logging in)
5. Check that requests are going to your Render backend URL

## 🔄 Backend CORS Configuration

Make sure your backend on Render has the correct CORS configuration:

1. Go to Render Dashboard → Your Backend Service → Environment tab
2. Ensure `FRONTEND_URL` is set to your Vercel frontend URL:
   ```
   FRONTEND_URL=https://your-frontend.vercel.app
   ```
   Or your custom domain if you have one set up.

## 🏠 Local Development

For local development, create a `.env` file in the `frontend` directory:

```env
VITE_API_URL=http://localhost:8000/api
```

Or if you want to test against your Render backend:
```env
VITE_API_URL=https://your-backend-service.onrender.com/api
```

**Note:** The `.env` file is for local development only. Vercel uses environment variables set in the dashboard for production builds.

## 🐛 Troubleshooting

### API requests are failing
- Verify `VITE_API_URL` is set correctly in Vercel
- Check that you've redeployed after adding the environment variable
- Verify the backend URL is accessible (try opening it in a browser)
- Check browser console for CORS errors (might need to update `FRONTEND_URL` on Render)

### Environment variable not working
- Make sure the variable name starts with `VITE_` (required for Vite)
- Verify you selected the correct environment (Production/Preview/Development)
- Redeploy after adding the variable
- Check Vercel build logs for any errors

### CORS errors
- Make sure `FRONTEND_URL` in Render matches your Vercel URL
- Check that your Render backend service is running
- Verify the backend CORS configuration in `backend/config/cors.php`

## 📚 Additional Resources

- [Vercel Environment Variables Documentation](https://vercel.com/docs/concepts/projects/environment-variables)
- [Vite Environment Variables Documentation](https://vitejs.dev/guide/env-and-mode.html)

