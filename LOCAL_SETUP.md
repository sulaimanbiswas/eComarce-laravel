# Local Setup — Windows + Git Bash

Use **Git Bash** for all commands below. This guide covers a basic local shop first; optional modules are listed at the end.

## 1. Requirements

- PHP 8.4 with ZIP, PDO MySQL, cURL, GD, Intl, Mbstring, Fileinfo and XML extensions.
- Composer 2, Node.js with npm, and MySQL 8.
- Laravel Herd configured to serve this project from its `public` directory.

Open Git Bash in the project:

```bash
cd /d/eComarce-laravel
php --version
composer --version
node --version
npm --version
php --ini
php -m
```

Use `php` and `composer` from your Windows PATH. The project's `./php` and `./composer` files are hosting wrappers, so use the commands without `./` locally.

If Composer reports a missing ZIP extension, open the loaded `php.ini` shown by `php --ini`, enable `extension=zip`, and restart the relevant PHP service. Confirm with:

```bash
php --ri zip
```

## 2. Install dependencies

```bash
composer install
composer check-platform-reqs
npm ci
```

Use the existing lock files. `composer update` is not needed for setup.

## 3. Set up `.env`

Create `.env` only if it does not already exist:

```bash
if [ ! -f .env ]; then
    cp .env.example .env
fi
```

Edit the existing `.env` and set these values. Use your own MySQL username and password.

```dotenv
APP_NAME="Shapla Mart"
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=Asia/Dhaka
APP_URL=http://127.0.0.1:8000

APP_DEMO=false
APP_ONINDA=false
APP_RESELL=false
APP_RESELLER=false
ONINDA_URL=
INSTANT_ORDER_FORWARDING=false
ACCOUNTING_ENABLED=false
INVESTMENT_ENABLED=false

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_laravel
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=custom
SESSION_DOMAIN=null
FILESYSTEM_DISK=public
CACHE_STORE=file
CACHE_MEMO=false
QUEUE_CONNECTION=database
DB_QUEUE_RETRY_AFTER=960
SCOUT_DRIVER=database

LOG_CHANNEL=stack
LOG_STACK=single
MAIL_MAILER=log
RESPONSE_CACHE_ENABLED=false
CDN_ENABLED=false
BKASH_SANDBOX=true
REDX_ENABLED=false
```

`APP_URL` above is this workspace's current local address. If you use a different address in Herd, replace it with that address, including the scheme and port. Open the site using the same address.

`SESSION_DRIVER=custom` is required by this project's session table. Keep your existing `APP_KEY`; generate one only when it is empty.

## 4. Prepare MySQL and the application

Start MySQL through your local service manager. Create the database using your database manager, or run this Git Bash command if `mysql` is on PATH:

```bash
mysql -u root -p -e 'CREATE DATABASE IF NOT EXISTS ecommerce_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'
```

Enter your MySQL password when prompted. Then run:

```bash
php artisan config:clear --no-interaction

if grep -Eq '^APP_KEY=[[:space:]]*$' .env; then
    php artisan key:generate --no-interaction
fi

php artisan migrate --no-interaction
mkdir -p public/storage
npm run build
php artisan sitemap:generate --no-interaction
```

The public disk already points to `public/storage`; a storage symlink is not needed.

## 5. Seed the initial setup

After migrations, run this in Git Bash. No Tinker commands are needed:

```bash
php artisan setup:local --no-interaction
php artisan sitemap:generate --no-interaction
```

The setup creates:

- Four active staff accounts: Admin, Manager, Salesman and Uploader.
- A customer, a verified reseller, a pending reseller and an active investor.
- Affiliate profiles for the demo users, without a referral tree or commissions.
- Company and display settings, delivery areas, product-button defaults, service text and theme colors.
- A Power Tools category, Craftline Tools brand, Electric Wood Planer and Circular Saw products with stock and matching local images, and a Workshop Essentials homepage section.
- Header/topbar/footer navigation and four editable placeholder pages.
- The existing accounting service's default chart of accounts and transaction categories, with no seeded transactions or opening balances.

New accounts all use **`Password123!`**:

| Account | Email | Login path |
| --- | --- | --- |
| Admin | `admin@example.com` | `/getpass` |
| Manager | `manager@example.com` | `/getpass` |
| Salesman | `salesman@example.com` | `/getpass` |
| Uploader | `uploader@example.com` | `/getpass` |
| Customer / User | `customer@example.com` | `/login` in Oninda mode |
| Verified reseller | `reseller@example.com` | `/login` in Oninda mode |
| Pending reseller | `pending@example.com` | `/login` in Oninda mode; verification still required |
| Investor | `investor@example.com` | `/investor/login` when Investment is enabled |

You can rerun the setup. It fills missing settings and records without duplicating them or overwriting customized catalog data, settings or account passwords. Original Local Demo catalog labels are upgraded to the realistic names above; prices and stock are preserved.

If a demo email already exists and you want its password to become `Password123!`, run:

```bash
php artisan setup:local --reset-passwords --no-interaction
```

This resets passwords only for the eight demo email addresses in the table. Other accounts are untouched. Existing account names, roles and activation/verification settings are preserved.

For a fresh local database, the standard Laravel command also works:

```bash
php artisan migrate --seed --no-interaction
```

These demo seeders are restricted to `local` and `testing` environments. They do not enable modules or configure payment/SMS/courier credentials. Avoid `migrate:fresh` and `db:wipe` on a database you want to keep.

## 6. Use the seeded shop

1. Sign in as Admin at `/getpass`.
2. Open `/shop` to see the sample products and open `/` for the demo homepage section.
3. Replace demo company details, images, products and placeholder page content through Admin screens.
4. Enable optional modules only when you need them, using the settings below.

The sample products each start with 25 units. Rerunning the seeders does not reset stock, balances or existing passwords. No sample orders, payments, investments or commissions are created.

## 7. Daily development

Keep Herd and MySQL running. For frontend changes, open Git Bash and run:

```bash
cd /d/eComarce-laravel
npm run dev
```

In another Git Bash terminal, run the queue worker when background jobs are needed:

```bash
cd /d/eComarce-laravel
php artisan queue:work --tries=1 --timeout=900 --no-interaction
```

Keep these terminals open. Press `Ctrl+C` to stop a process.

Keep `DB_QUEUE_RETRY_AFTER` greater than the worker's `--timeout` to avoid processing a long job twice.

With the logging settings above, read the application log using:

```bash
tail -f storage/logs/laravel.log
```

If you use daily logs instead, use:

```bash
tail -f "storage/logs/laravel-$(date +%F).log"
```

A log file must exist before `tail` can follow it. Native Windows PHP cannot run Laravel Pail because it requires PCNTL.

## 8. Optional modules

| Feature                   | Configuration and next step                                                                                                              |
| ------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| Reseller platform         | Set `APP_ONINDA=true` and `APP_RESELL=true`. Sign in at `/login` with the seeded verified reseller, then use `/reseller/dashboard`. New users can register through `/register`. |
| Accounting                | Set `ACCOUNTING_ENABLED=true`, then configure it through the Admin accounting screens.                                                   |
| Investment                | Set `INVESTMENT_ENABLED=true`. Sign in with the seeded investor at `/investor/login`. Manage additional investors through `/admin/investment/investors/create`.   |
| Payment, SMS and couriers | Enter the provider's sandbox/test credentials in the relevant configuration or Admin settings before using the integration.              |
| Connected reseller shop   | Configure its platform URL, database connection and credentials before enabling synchronization.                                         |

After changing `.env`:

```bash
php artisan config:clear --no-interaction
```

The scheduler also dispatches Oninda synchronization jobs. Run it only after configuring the integrations you need:

```bash
php artisan schedule:work --no-interaction
```

## 9. Quick fixes

| Problem                                   | What to do                                                                                              |
| ----------------------------------------- | ------------------------------------------------------------------------------------------------------- |
| `php` or `composer` not found             | Add the Windows PHP/Composer installation to PATH and reopen Git Bash.                                  |
| Missing ZIP or MySQL extension            | Check `php --ini`, enable the matching extension, then restart PHP.                                     |
| Database connection error                 | Start MySQL and check the `DB_*` values in `.env`.                                                      |
| Session error mentioning `user_id`        | Set `SESSION_DRIVER=custom`, then clear configuration cache.                                            |
| Frontend changes are missing              | Run `npm run dev`, or rebuild with `npm run build`.                                                     |
| A page uses an old template               | Run `php artisan view:clear --no-interaction`.                                                          |
| Sitemap is missing or uses an old address | Correct `APP_URL`, clear configuration cache, then run `php artisan sitemap:generate --no-interaction`. |
| Admin login fails                         | Use `/getpass`; check that the Admin account is active and that you are using its existing password.    |

These instructions are for local development. Actual payments, SMS delivery and courier callbacks need separate integration checks.
