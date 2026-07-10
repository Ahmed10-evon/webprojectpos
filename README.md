# CRAVE ABS — Laravel + MySQL Rebuild

This is a full rebuild of the original Next.js/Supabase clothing-brand admin panel (CRAVE ABS) using **Laravel 11 + PHP + MySQL (phpMyAdmin)**, with two login roles:

- **Admin** — full access to everything.
- **Salesman** — can use the POS, view products, record sales, and manage
  membership sign-ups, but **cannot** register new products, edit/archive
  products, update prices, or see anything in the Purchases module
  (requisitions, purchase orders, receiving stock, purchase history,
  returns) or Refunds/Reports. Every one of those routes is protected
  server-side by a `role:admin` middleware — it's not just a hidden menu
  item, hitting the URL directly returns a 403.

Login uses Laravel's built-in session guard (`Auth::attempt`), so every
signed-in user gets a server-side session (stored in the `sessions` MySQL
table) with a secure, http-only cookie holding just the session ID. Ticking
"Remember me" additionally sets a long-lived `remember_token` cookie so the
login persists across browser restarts.

## 1. Requirements

- PHP 8.2+
- Composer
- MySQL (e.g. via XAMPP/WAMP/Laragon, with phpMyAdmin)
- Node.js is **not required** — the UI uses Tailwind via CDN, no build step.

## 2. Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Open **phpMyAdmin**, create a database named `crave_abs` (utf8mb4), then
edit `.env` if your MySQL credentials differ from the defaults:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crave_abs
DB_USERNAME=root
DB_PASSWORD=
```

Then run migrations and seed the two starter accounts + reference data:

```bash
php artisan migrate --seed
php artisan serve
```

Visit `http://127.0.0.1:8000`.

## 3. Default logins (change the passwords after first login!)

| Role     | Email                      | Password |
|----------|-----------------------------|----------|
| Admin    | admin@craveabs.test        | password |
| Salesman | salesman@craveabs.test     | password |

Admins can create/deactivate more Salesman or Admin accounts under
**Staff Accounts** (`/users`).

## 4. What's included

- **Auth**: session + cookie based login, "remember me", account
  deactivation, role stored on the `users` table (`admin` / `salesman`).
- **Products**: barcode-based catalog, categories/units/brands, price
  updates, archive/restore (admin-only to manage; salesman can view).
- **POS Terminal**: barcode scan → cart (stored server-side in the
  session) → discount/tax → checkout → printable receipt. Open to both roles.
- **Sales**: All Sales ledger, search-based Add Sale, Sales Orders. Open to
  both roles.
- **Purchases** (admin-only): Requisition, Purchase Orders (multi-line),
  Add Purchase (receive stock against a barcode), Purchase History,
  Purchase Returns.
- **Refunds** (admin-only): look up a sale by barcode, refund it, stock is
  restored automatically.
- **Reports** (admin-only): revenue, breakdown by payment method, net
  profit vs. Daily Cost, date-range filter, CSV export.
- **Daily Cost** / **Daily Sales Survey** (admin-only): manual cost
  tracking and a simple day-by-day sales feed that can sync from real
  sales data.
- **Membership**: enroll/list/renew/revoke members (open to both roles);
  the discount-percent setting is admin-only.
- **Settings** (admin-only): business info, receipt footer text, barcode
  prefix, tax rates.
- **Staff Accounts** (admin-only): create/deactivate/delete Admin or
  Salesman logins.

## 5. Where the role check actually lives

Every admin-only page is grouped under this in `routes/web.php`:

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Products management, all of Purchases, Refunds, Reports,
    // Daily Cost, Survey, Membership Settings, Settings, Staff Accounts
});
```

The `role` middleware is `app/Http/Middleware/EnsureUserHasRole.php` — it
checks `$request->user()->role` against the roles passed to it (`role:admin`
or `role:admin,salesman`) and aborts with a 403 otherwise. The sidebar in
`resources/views/layouts/app.blade.php` also hides those links from a
Salesman, but the real enforcement is the route middleware, not the hidden
UI — a Salesman can't get in even by typing the URL directly.

## 6. Notes on scope

This is a faithful, from-scratch Laravel implementation of the original
app's feature set and data model (dresses/products, sales, purchases,
requisitions, purchase orders, returns, memberships, daily costs, survey,
settings) — it is not a line-by-line port of the React/Supabase code, since
the frameworks work quite differently. If you want any screen to match a
very specific interaction from the original app more closely, point it out
and it can be adjusted.
