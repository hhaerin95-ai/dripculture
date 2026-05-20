# 🔥 DRIP CULTURE — Laravel E-Commerce

Converted dari raw PHP ke Laravel 11. Sistem ordering streetwear dengan fitur penuh.

---

## 📁 Struktur Project

```
drip-culture/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/LoginController.php
│   │   ├── Auth/RegisterController.php
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── OrderController.php
│   │   ├── ProfileController.php
│   │   └── ContactController.php
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Product.php
│       ├── Cart.php
│       ├── Order.php
│       └── OrderItem.php
├── database/
│   ├── migrations/
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── home.blade.php
│   ├── products/ (index, show)
│   ├── cart/index.blade.php
│   ├── checkout/ (index, confirmation)
│   ├── orders/index.blade.php
│   ├── profile/edit.blade.php
│   ├── auth/ (login, register)
│   └── contact.blade.php
├── routes/web.php
└── public/css/style.css
```

---

## 🚀 Setup (Step by Step)

### 1. Install Laravel
```bash
composer create-project laravel/laravel drip-culture-app
cd drip-culture-app
```

### 2. Copy semua files dari folder ini ke dalam project Laravel
Salin semua folder: `app/`, `database/`, `resources/views/`, `routes/web.php`, `public/css/`

### 3. Setup `.env`
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
DB_DATABASE=streetwear_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Setup Database
```bash
# Buat database dulu dalam MySQL
mysql -u root -e "CREATE DATABASE streetwear_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migration + seeder
php artisan migrate --seed
```

### 5. Run Server
```bash
php artisan serve
```

Buka: **http://localhost:8000**

---

## 🔑 Login Credentials

| Role | Email | Password |
|------|-------|----------|
| User | admin@dripculture.my | Admin@1234 |

---

## ✨ Fitur Yang Diconvert

| PHP Lama | Laravel Baru |
|----------|-------------|
| `$_SESSION` manual | Laravel Auth + Session |
| `mysqli` raw queries | Eloquent ORM |
| `htmlspecialchars()` everywhere | Blade auto-escape `{{ }}` |
| Manual redirect + header() | `redirect()->route()` |
| SQL injection risiko | Query binding / Eloquent |
| No CSRF | `@csrf` token |
| Manual validation | `$request->validate()` |
| config.php include | `.env` + `config/` |
| Spaghetti PHP/HTML | MVC + Blade templates |

---

## 📌 Routes

```
GET  /                    → Home
GET  /products            → Senarai produk
GET  /products/{id}       → Detail produk
GET  /cart                → Cart (auth)
POST /cart/add            → Tambah ke cart
POST /cart/{id}/update    → Update qty
POST /cart/{id}/remove    → Remove item
POST /cart/clear          → Kosongkan cart
GET  /checkout            → Checkout form (auth)
POST /checkout            → Submit order
GET  /checkout/confirmation → Order confirmed
GET  /orders              → History orders
GET  /orders/{id}         → Detail order
GET  /profile             → Edit profile
POST /profile             → Update profile
GET  /contact             → Contact page
POST /contact             → Send message
GET  /login               → Login form
POST /login               → Login
GET  /register            → Register form
POST /register            → Daftar
POST /logout              → Logout
```

---

## 🧰 Composer Dependencies (tambah dalam composer.json)

```json
"require": {
    "php": "^8.2",
    "laravel/framework": "^11.0"
}
```

Tiada package tambahan diperlukan — Laravel built-in auth digunakan.
