# Laragon Environment Configuration

This guide provides all the steps required to set up a Laravel project in a **Laragon** environment, install dependencies, configure `.env`, and set up an **admin user**.

---

## **1. Install Project Dependencies**

Run the following commands in your terminal:

```bash
composer install
composer global require laravel/installer
php artisan key:generate
npm install
npm install vite --save-dev
npm install laravel-vite-plugin --save-dev
npm run dev
npm run build
composer require spatie/laravel-permission
```

---

## **2. Configure `.env` File**

Edit the `.env` file in the root of your project and update the following values:

```env
APP_URL=http://127.0.0.1:8000
DATABASE=your_database_name
```

Make sure your database credentials are set properly.

---

## **3. Admin User Configuration**

### **Step 1 — Open Laravel Tinker**
```bash
php artisan tinker
```

### **Step 2 — Fetch All Users**
```php
$users = App\Models\User::all();
```

### **Step 3 — Get the Admin Role**
```php
$adminRole = Spatie\Permission\Models\Role::where('name', 'admin')->first();
```

### **Step 4 — Assign Admin Role to a User**
If the user is not already an admin, assign the role:

```php
$user->assignRole('admin');
```

---

## **Summary**
| Step | Command / Action | Description |
|------|------------------|-------------|
| **1** | `composer install` | Install Laravel dependencies |
| **2** | `composer global require laravel/installer` | Install Laravel installer globally |
| **3** | `php artisan key:generate` | Generate the application key |
| **4** | `npm install` | Install Node.js dependencies |
| **5** | `npm install vite --save-dev` | Install Vite for development |
| **6** | `npm install laravel-vite-plugin --save-dev` | Install Laravel Vite plugin |
| **7** | `npm run dev` | Start development server |
| **8** | `npm run build` | Build assets for production |
| **9** | `composer require spatie/laravel-permission` | Install Spatie permissions package |
| **10** | `.env` update | Set APP_URL and database credentials |
| **11** | `php artisan tinker` | Open Laravel Tinker |
| **12** | `$user->assignRole('admin')` | Assign admin role |

---

## **Final Notes**
- Ensure you have **Composer**, **Node.js**, and **Laragon** properly installed.
- Make sure to create a database before running migrations.
- Always verify your `.env` settings before starting the application.

---

**Author:** CoreDev Solutions LLC 
**Last Updated:** 2025-09-07
