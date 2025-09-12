# Stripe Setup Guide

## Step 1: Create .env file
Create a `.env` file in your project root directory (`C:\laragon\www\eros-portal\.env`) with the following content:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eros_portal
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Stripe Configuration - REPLACE WITH YOUR ACTUAL KEYS
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here

# Stripe Price IDs - REPLACE WITH YOUR ACTUAL PRICE IDs
STRIPE_STARTER_PRICE_ID=price_starter_monthly_id
STRIPE_PRO_PRICE_ID=price_pro_monthly_id
STRIPE_DIABLO_PRICE_ID=price_diablo_monthly_id
```

## Step 2: Get your Stripe keys

### From Stripe Dashboard:
1. Go to [Stripe Dashboard](https://dashboard.stripe.com)
2. Navigate to **Developers** → **API Keys**
3. Copy your keys:
   - **Publishable key** (starts with `pk_test_` or `pk_live_`)
   - **Secret key** (starts with `sk_test_` or `sk_live_`)

### For Webhook Secret:
1. Go to **Developers** → **Webhooks**
2. Create a new webhook endpoint: `https://yourdomain.com/stripe/webhook`
3. Copy the **Signing secret** (starts with `whsec_`)

## Step 3: Create Products in Stripe

1. Go to **Products** in Stripe Dashboard
2. Create 3 products:
   - **Eros Starter** - $97/month recurring
   - **Eros Pro** - $197/month recurring  
   - **Eros Diablo** - $497/month recurring
3. Copy the **Price IDs** (starts with `price_`)

## Step 4: Replace the placeholder values

Replace these in your `.env` file:
- `pk_test_your_publishable_key_here` → Your actual publishable key
- `sk_test_your_secret_key_here` → Your actual secret key
- `whsec_your_webhook_secret_here` → Your actual webhook secret
- `price_starter_monthly_id` → Your actual Starter price ID
- `price_pro_monthly_id` → Your actual Pro price ID
- `price_diablo_monthly_id` → Your actual Diablo price ID

## Step 5: Generate App Key

After creating the `.env` file, run this command to generate the Laravel app key:

```bash
php artisan key:generate
```

## Step 6: Test the integration

1. Go to `/admin/analytics` (as admin)
2. Check if Stripe data loads
3. Test payment flow at `/payment` (admin only)

## That's it! 🎉

Your Stripe integration will be ready to track sales and process payments.
