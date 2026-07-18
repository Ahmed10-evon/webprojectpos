# CRAVE ABS — Point of Sale & Business Management System

CRAVE ABS is a full-stack **PHP Laravel + MySQL (phpMyAdmin)** web application built for a clothing brand's retail shop. It replaces an earlier Next.js/Supabase prototype with a from-scratch Laravel implementation covering product inventory, a barcode-driven POS terminal, purchasing/stock workflows, memberships, refunds, and business reports — all behind server-enforced, role-based access control.

---

## Table of Contents

1. [Tech Stack](#tech-stack)
2. [Key Features](#key-features)
3. [User Roles](#user-roles)
4. [Project Structure](#project-structure)
5. [System Architecture](#system-architecture)
6. [Database Schema](#database-schema)
7. [Requirements](#requirements)
8. [Installation & Setup (with phpMyAdmin)](#installation--setup-with-phpmyadmin)
9. [Environment Variables](#environment-variables)
10. [Default Login Accounts](#default-login-accounts)
11. [Role-Based Access Control](#role-based-access-control)
12. [Third-Party API Integrations](#third-party-api-integrations)
13. [Testing](#testing)
14. [Notes on Scope](#notes-on-scope)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend framework | **Laravel 11** (PHP 8.2+) |
| Database | **MySQL**, managed through **phpMyAdmin** |
| ORM | Eloquent |
| Frontend | Blade templates + **Tailwind CSS** (loaded via CDN — no Node/npm build step required) |
| Auth scaffold | Laravel Breeze (session-based) |
| Package manager | Composer (PHP), NPM (optional, Vite assets only) |
| External APIs | CurrencyAPI, OpenWeather, TimezoneDB (dashboard widgets) |

Node.js is **not required** to run the app day-to-day — the UI uses the Tailwind CDN build, so there is no `npm run dev`/`npm run build` step in the loop.

---

## Key Features

- **Authentication** — session + cookie based login (`Auth::attempt`), "Remember me", forced logout on account deactivation. Public self-registration is disabled; only an Admin can create new logins.
- **Products** — barcode-based catalog, category/brand/unit tagging, price & stock management, low-stock flagging, archive/restore.
- **POS Terminal** — scan a barcode → item added to a session-based cart → apply discount/tax → checkout → stock automatically decremented → printable receipt.
- **Sales** — full sales ledger (All Sales), manual/search-based sale entry (Add Sale), customer pre-orders (Sales Orders).
- **Purchasing** — requisitions, multi-line purchase orders, receiving stock against a barcode, purchase history, purchase returns.
- **Refunds** — look up a completed sale, refund it, stock is restored automatically.
- **Reports** — revenue by date range and payment method, net profit vs. daily costs, CSV export.
- **Daily Cost / Sales Survey** — manual expense logging and a day-by-day sales feed.
- **Membership** — enroll/list/renew/revoke walk-in customer memberships with a configurable discount percentage.
- **Settings** — business info, receipt footer text, barcode prefix, tax rates.
- **Staff Accounts** — Admins create, deactivate, or delete Admin/Salesman logins.
- **Dashboard** — today's revenue, low-stock alerts, live currency exchange rate, live weather, and live local time widgets.

---

## User Roles

| Role | Description |
|---|---|
| **Admin** | Full access to every module in the system. |
| **Salesman** | Can use the POS, view products, record sales, and manage membership sign-ups — but **cannot** manage products, purchases, refunds, reports, daily costs/survey, or settings. |

Every restricted route is protected **server-side**, not just hidden in the sidebar — visiting a restricted URL directly as a Salesman returns an HTTP 403.

---

## Project Structure

```
app/
  Http/
    Controllers/       # One controller per module (Products, POS, Sales,
                        # Purchases, Refunds, Reports, Settings, Users, ...)
    Middleware/         # EnsureUserHasRole.php, EnsureAccountIsActive.php
    Requests/            # Form validation classes (e.g. LoginRequest)
  Models/                # Eloquent models: Product, Sale, User, Purchase, ...
  Services/               # CurrencyService, WeatherService, TimezoneService
database/
  migrations/             # Versioned table schema definitions
  seeders/                # Default Admin/Salesman accounts + reference data
resources/
  views/                  # Blade templates, grouped by module
    layouts/               # Shared app shell (sidebar, Tailwind, theme)
    pos/, products/, sales/, purchases/, settings/, users/ ...
routes/
  web.php                 # All application routes
  auth.php                # Breeze authentication routes
config/                    # app.php, database.php, services.php, session.php
.env                        # Local environment configuration (not committed)
```

---

## System Architecture

The project follows Laravel's **MVC** pattern:

```
Browser Request
     |
     v
routes/web.php  --->  Middleware (auth, role:admin, active)
     |
     v
Controller (app/Http/Controllers/...)
     |            \
     v             v
Model (Eloquent)   Service (CurrencyService, WeatherService, ...)
     |
     v
MySQL Database (via phpMyAdmin)
     |
     v
Blade View (resources/views/...) ---> Rendered HTML back to Browser
```

A request first hits **`routes/web.php`**, which maps a URL + HTTP verb to a **Controller** method after passing through **Middleware** (authentication + role checks). The Controller talks to **Eloquent Models** to read/write MySQL data (and to **Service** classes for outbound API calls), then hands the data to a **Blade View**, which renders the final HTML.

---

## Database Schema

All tables are defined as versioned migrations under `database/migrations/` and can be recreated on any machine with `php artisan migrate`.

| Table | Purpose |
|---|---|
| `users` | Login accounts — `role` (admin/salesman) and `is_active` columns drive access control |
| `products` | Product catalog/inventory — `barcode` (unique), `category`, `brand`, `unit` (plain strings), `price`, `quantity`, `status` |
| `categories`, `units`, `brands` | Reference lists used to populate dropdowns (not foreign-keyed to `products`) |
| `sales` | One row per unit sold — `product_id` & `user_id` foreign keys, `amount_paid`, `discount_amount`, `tax_amount`, `status`, `sold_at` |
| `sales_orders` | Customer pre-orders (standalone — not linked to `sales`) |
| `memberships` | Walk-in customer loyalty records (phone, expiry, status) |
| `membership_settings` | Single-row config for the membership discount percentage |
| `purchase_requisitions` | What needs re-stocking |
| `purchase_orders` / `purchase_order_items` | Supplier orders and their line items |
| `purchases` | Stock received against a barcode (optional `product_id` link) |
| `purchase_returns` | Purchases reversed/returned to a supplier |
| `stock_adjustments` | Manual inventory corrections — `product_id` & `user_id` foreign keys, `direction`, before/after quantities |
| `business_settings` | Shop name, address, receipt footer, barcode prefix |
| `tax_rates` | Configurable tax rates used at checkout |
| `daily_costs` | Manual daily expense entries feeding the Reports module |
| `survey_records` | Day-by-day sales feed |

**Foreign-key relationships that actually exist in the schema:**

```
users        ──< sales
users        ──< stock_adjustments
products     ──< sales
products     ──< stock_adjustments
products     ──< purchases            (nullable)
products     ──< purchase_returns     (nullable)
purchase_orders ──< purchase_order_items
```

> `categories`, `units`, and `brands` are **not** foreign-keyed to `products` — the product record stores their names as plain strings. `sales_orders`, `memberships`, and `purchase_requisitions` are standalone tables with no foreign keys to other modules.

---

## Requirements

- PHP 8.2+
- Composer
- MySQL (via **XAMPP** / **WAMP** / **Laragon**, managed through **phpMyAdmin**)
- Node.js is **not required**

---

## Installation & Setup (with phpMyAdmin)

**1. Install PHP dependencies**

```bash
composer install
```

**2. Create your local environment file**

```bash
cp .env.example .env
php artisan key:generate
```

**3. Create the database in phpMyAdmin**

- Open phpMyAdmin (usually `http://localhost/phpmyadmin`).
- Click **New**, name the database `crave_abs`, and set the collation to `utf8mb4_unicode_ci` (or leave the default `utf8mb4`).
- No tables need to be created manually — Laravel's migrations will build the schema in the next step.

**4. Point `.env` at your MySQL/phpMyAdmin database**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crave_abs
DB_USERNAME=root
DB_PASSWORD=
```

Adjust `DB_USERNAME` / `DB_PASSWORD` to match your local MySQL credentials (XAMPP/Laragon defaults are shown above).

**5. Run migrations and seed starter data**

```bash
php artisan migrate --seed
```

This creates every table listed in [Database Schema](#database-schema) and seeds two starter accounts plus reference data (categories/units/brands). You can refresh and re-seed at any time with `php artisan migrate:fresh --seed` — check phpMyAdmin's **Structure** tab afterward to confirm the tables were created.

**6. Serve the application**

```bash
php artisan serve
```

Visit **http://127.0.0.1:8000**.

---

## Environment Variables

Laravel reads all environment-specific configuration from `.env` via the `env()` helper, exposed to the app through `config/*.php`. Copy `.env.example` to `.env` and fill in the values below.

**Core app & database**

| Variable | Purpose |
|---|---|
| `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_URL` | App name, environment, encryption key, base URL |
| `APP_DEBUG` | Detailed error pages during development |
| `DB_CONNECTION` | Set to `mysql` |
| `DB_HOST`, `DB_PORT` | MySQL server address (`127.0.0.1:3306` for XAMPP/Laragon) |
| `DB_DATABASE` | Database name — `crave_abs` |
| `DB_USERNAME`, `DB_PASSWORD` | MySQL/phpMyAdmin login credentials |
| `SESSION_DRIVER`, `SESSION_LIFETIME` | Sessions are stored in the `sessions` MySQL table |

**Third-party API keys** (power the dashboard widgets)

| Variable | Used by |
|---|---|
| `CURRENCYAPI_KEY`, `CURRENCYAPI_BASE`, `CURRENCYAPI_TARGETS` | `app/Services/CurrencyService.php` — live exchange rates |
| `OPENWEATHER_API_KEY`, `OPENWEATHER_CITY`, `OPENWEATHER_UNITS` | `app/Services/WeatherService.php` — dashboard weather widget |
| `TIMEZONEDB_API_KEY`, `TIMEZONEDB_ZONE`, `TIMEZONEDB_GATEWAY` | `app/Services/TimezoneService.php` — dashboard clock widget |
| `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_FROM_ADDRESS` | Outgoing mail (password-reset emails) |

If any API key is left blank, the corresponding dashboard widget fails gracefully (returns `null`) instead of breaking the page.

---

## Default Login Accounts

> ⚠️ Change these passwords after first login.

| Role | Email | Password |
|---|---|---|
| Admin | `admin@craveabs.test` | `password` |
| Salesman | `salesman@craveabs.test` | `password` |

Admins can create or deactivate additional Salesman/Admin accounts under **Staff Accounts** (`/users`).

---

## Role-Based Access Control

Every admin-only page is grouped under this in `routes/web.php`:

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Product management, all of Purchases, Refunds, Reports,
    // Daily Cost, Survey, Membership Settings, Settings, Staff Accounts
});
```

The `role` middleware alias resolves to `app/Http/Middleware/EnsureUserHasRole.php`, which checks the signed-in user's `role` column against the roles passed into `role:...` and aborts with a `403` otherwise:

```php
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'You do not have permission to access this page.');
        }
        return $next($request);
    }
}
```

A second middleware, `EnsureAccountIsActive`, runs globally on every web request and force-logs-out any user whose `is_active` flag has been switched off by an Admin.

The sidebar (`resources/views/layouts/app.blade.php`) also hides restricted links from a Salesman for a cleaner UI, but the **actual enforcement is the middleware** — typing a restricted URL directly still returns a 403.

---

## Third-Party API Integrations

`app/Services/` isolates all outbound HTTP calls so controllers stay thin:

- **`CurrencyService`** — fetches live exchange rates from CurrencyAPI, cached for 6 hours.
- **`WeatherService`** — fetches current weather from OpenWeather for the dashboard.
- **`TimezoneService`** — fetches the current local time from TimezoneDB.

Each service is constructor-injected into `DashboardController` (and `PosController` where relevant), reads its API key from `.env`, and caches responses so free-tier API quotas aren't burned on every page load.

---

## Testing

```bash
php artisan test
```

PHPUnit feature tests under `tests/Feature/` cover the authentication flows inherited from Breeze (login, password reset/confirmation/update, email verification) and profile management. Manual testing should also confirm that Salesman-restricted URLs return `403` when visited directly, proving the middleware enforces access control rather than just the hidden sidebar links.

---

## Notes on Scope

This is a faithful, from-scratch Laravel implementation of the original app's feature set and data model (products, sales, purchases, requisitions, purchase orders, returns, memberships, daily costs, survey, settings) — it is **not** a line-by-line port of the earlier React/Supabase codebase, since the two frameworks work quite differently.