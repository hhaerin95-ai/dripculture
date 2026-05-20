# STRWEAR Admin Module — Setup Instructions
### TSE3623 Web Application Development | Person B (Admin)

---

## What's Included

| File | Purpose |
|---|---|
| `routes/admin.php` | All admin routes (prefix: `/admin`) |
| `app/Http/Middleware/AdminAuthenticated.php` | Protects all admin routes |
| `app/Http/Controllers/Admin/AdminAuthController.php` | Login / Logout |
| `app/Http/Controllers/Admin/DashboardController.php` | Dashboard stats |
| `app/Http/Controllers/Admin/ProductController.php` | Full CRUD + Variants |
| `app/Http/Controllers/Admin/OrderController.php` | Order list + status update |
| `app/Http/Controllers/Admin/PaymentController.php` | Verify / reject payments |
| `app/Http/Controllers/Admin/InventoryController.php` | Stock adjustment |
| `app/Http/Controllers/Admin/ReportController.php` | Sales report + CSV/PDF export |
| `resources/views/admin/` | All Blade views |
| `app/Models/_ALL_MODELS.php` | All Eloquent models (split into individual files) |

---

## Step 1 — Copy Files Into Your Laravel Project

Copy the entire folder structure into your existing Laravel project root.

---

## Step 2 — Register Routes

In `bootstrap/app.php` (Laravel 11) OR `RouteServiceProvider.php` (Laravel 10):

### Laravel 11 (`bootstrap/app.php`)
```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    then: function () {
        Route::middleware('web')
             ->group(base_path('routes/admin.php'));
    },
)
```

### Laravel 10 (`app/Providers/RouteServiceProvider.php`)
```php
public function boot(): void
{
    $this->routes(function () {
        Route::middleware('web')->group(base_path('routes/web.php'));
        Route::middleware('web')->group(base_path('routes/admin.php'));
    });
}
```

---

## Step 3 — Register Middleware

### Laravel 11 (`bootstrap/app.php`)
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin.auth' => \App\Http\Middleware\AdminAuthenticated::class,
    ]);
})
```

### Laravel 10 (`app/Http/Kernel.php`)
```php
protected $routeMiddleware = [
    // ... existing ...
    'admin.auth' => \App\Http\Middleware\AdminAuthenticated::class,
];
```

---

## Step 4 — Split Models

Open `app/Models/_ALL_MODELS.php` and copy each class into its own file:
- `Product.php`, `Category.php`, `Variant.php`, `Image.php`
- `Order.php`, `OrderItem.php`, `Payment.php`, `Address.php`
- `OrderHistory.php`, `StockLog.php`, `Cart.php`, `Role.php`

> If your teammate already made some models, just add missing relationships.

---

## Step 5 — Check Database Column Names

The models use **snake_case** matching the project schema:

| Model column | DB column (from schema) |
|---|---|
| `user_id` | UserID → snake: user_id |
| `full_name` | FullName → snake: full_name |
| `role_id` | RoleID → snake: role_id |
| `order_id` | OrderID → snake: order_id |
| etc. | etc. |

> If your teammate used camelCase column names in migrations, update the `$fillable` arrays accordingly.

---

## Step 6 — Storage Link

```bash
php artisan storage:link
```

This allows product images to be served from `/storage/products/`.

---

## Step 7 — Seed an Admin User

Run this in `php artisan tinker`:

```php
\App\Models\User::create([
    'role_id'      => 1,         // 1 = Admin (adjust if different)
    'full_name'    => 'Admin',
    'email'        => 'admin@strwear.com',
    'password'     => bcrypt('Admin@1234'),
    'phone_number' => '0123456789',
    'status'       => 'Active',
    'created_at'   => now(),
]);
```

---

## Step 8 — Access the Panel

Navigate to: **http://localhost:8000/admin/login**

Login with:
- Email: `admin@strwear.com`
- Password: `Admin@1234`

---

## Admin Panel Pages

| URL | Page |
|---|---|
| `/admin/login` | Admin Login |
| `/admin/dashboard` | Dashboard (stats + charts) |
| `/admin/products` | Product list |
| `/admin/products/create` | Add product + variants + images |
| `/admin/products/{id}/edit` | Edit product |
| `/admin/orders` | All orders with filters |
| `/admin/orders/{id}` | Order detail + update status |
| `/admin/payments` | Payment list + verify/reject |
| `/admin/inventory` | Stock levels + adjust |
| `/admin/reports` | Sales report + export CSV/PDF |

---

## Features Covered (Rubric)

| Rubric Criteria | Implemented |
|---|---|
| Functionality & Features | ✅ Full CRUD, order management, payments, reports |
| Database Design | ✅ All models match project ERD |
| Responsiveness & UI | ✅ Bootstrap 5 + custom dark theme |
| Use of Web/DB Frameworks | ✅ Laravel routing, Eloquent ORM, Blade |
| Code Quality | ✅ Clean controllers, separated concerns |
| Security & Validation | ✅ CSRF, session auth, form validation |
| Innovation / Creativity | ✅ Chart.js dashboard, CSV+PDF export, stock alerts |
