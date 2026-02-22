# 🛡️ Laravel RBAC Boilerplate

A clean and ready-to-use **Role-Based Access Control (RBAC)** boilerplate built with Laravel and [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission). Comes with a full admin panel UI using the **SB Admin 2** Bootstrap theme for managing users, roles, and permissions out of the box.

---

## ✨ Features

- 🔐 **Authentication** — Login and Register with Laravel session-based auth
- 👥 **User Management** — Create, read, update, delete users; assign multiple roles per user
- 🎭 **Role Management** — Create and manage roles; assign grouped permissions with select-all toggle
- 🔑 **Permission Management** — Define granular permissions using `resource.action` naming convention
- 👑 **Super Admin** — Bypasses all permission checks via `Gate::before`
- 🎨 **SB Admin 2 UI** — Professional Bootstrap 4 admin dashboard template
- 🔍 **Search & Pagination** — Built-in search and paginated tables for all resources
- 🛡️ **Route & Controller Protection** — Middleware-based permission guards on every endpoint
- 🧩 **Blade Directives** — `@can` / `@cannot` used throughout views for clean UI permission handling
- 🗃️ **PostgreSQL** — Configured for PostgreSQL as the primary database

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11 |
| Auth & RBAC | Spatie Laravel Permission |
| Database | PostgreSQL |
| UI Theme | SB Admin 2 (Bootstrap 4) |
| Frontend | Bootstrap 4, jQuery, Font Awesome |

---

## 📋 Prerequisites

Before getting started, make sure you have the following installed:

- PHP >= 8.2
- Composer
- Node.js & NPM
- PostgreSQL
- Git

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/laravel-rbac-boilerplate.git
cd laravel-rbac-boilerplate
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install NPM Dependencies

```bash
npm install && npm run build
```

### 4. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Then edit `.env` and update your database credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Publish Spatie Permission Config

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 7. Seed Roles, Permissions & Default Admin

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### 8. Start the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## 🔑 Default Credentials

After seeding, a super admin account is created automatically:

| Field | Value |
|---|---|
| Email | `superadmin@example.com` |
| Password | `password` |

> ⚠️ **Important:** Change the default password immediately in a production environment.

---

## 📁 Project Structure

```
app/
├── Http/
│   └── Controllers/
│       ├── Auth/
│       │   ├── LoginController.php
│       │   └── RegisterController.php
│       ├── UserController.php
│       ├── RoleController.php
│       └── PermissionController.php
├── Models/
│   └── User.php                     # Uses HasRoles trait

database/
└── seeders/
    └── RolesAndPermissionsSeeder.php

resources/views/
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
└── admin/
    ├── layout.blade.php
    ├── users/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── roles/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    └── permissions/
        ├── index.blade.php
        ├── create.blade.php
        └── edit.blade.php

routes/
└── web.php
```

---

## 🗂️ Default Roles & Permissions

### Roles

| Role | Description |
|---|---|
| `super-admin` | Has full access to everything, bypasses all permission checks |
| `admin` | Can manage users and view roles |
| `user` | Default role assigned to newly registered users; no admin access |

### Permissions

Permissions follow the `resource.action` naming convention:

| Permission | Description |
|---|---|
| `user.view` | View the user list |
| `user.create` | Create new users |
| `user.edit` | Edit existing users |
| `user.delete` | Delete users |
| `role.view` | View the role list |
| `role.create` | Create new roles |
| `role.edit` | Edit existing roles |
| `role.delete` | Delete roles |
| `permission.view` | View the permission list |
| `permission.create` | Create new permissions |
| `permission.edit` | Edit existing permissions |
| `permission.delete` | Delete permissions |

---

## 🌐 Routes Overview

```
GET    /login                    Show login form
POST   /login                    Process login
POST   /logout                   Logout
GET    /register                 Show register form
POST   /register                 Process registration
GET    /dashboard                Dashboard (auth required)

GET    /admin/users              List all users
GET    /admin/users/create       Create user form
POST   /admin/users              Store new user
GET    /admin/users/{id}/edit    Edit user form
PUT    /admin/users/{id}         Update user
DELETE /admin/users/{id}         Delete user

GET    /admin/roles              List all roles
GET    /admin/roles/create       Create role form
POST   /admin/roles              Store new role
GET    /admin/roles/{id}/edit    Edit role form
PUT    /admin/roles/{id}         Update role
DELETE /admin/roles/{id}         Delete role

GET    /admin/permissions        List all permissions
GET    /admin/permissions/create Create permission form
POST   /admin/permissions        Store new permission
GET    /admin/permissions/{id}/edit Edit permission form
PUT    /admin/permissions/{id}   Update permission
DELETE /admin/permissions/{id}   Delete permission
```

---

## 🧪 Usage in Code

### Protecting Routes with Middleware

Routes are grouped under the `auth` middleware and prefixed with `/admin`:

```php
// routes/web.php

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});
```

### Protecting Controllers

This project uses Laravel 11's `HasMiddleware` interface instead of the deprecated `$this->middleware()` in the constructor:

```php
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:user.view',   only: ['index', 'show']),
            new Middleware('permission:user.create', only: ['create', 'store']),
            new Middleware('permission:user.edit',   only: ['edit', 'update']),
            new Middleware('permission:user.delete', only: ['destroy']),
        ];
    }
}
```

### Checking Permissions in Blade

```blade
@can('user.create')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
@endcan
```

### Checking Roles in PHP

```php
if (auth()->user()->hasRole('super-admin')) {
    // ...
}

if (auth()->user()->can('user.delete')) {
    // ...
}
```

---

## ⚙️ Customization

### Adding New Permissions

Add to `RolesAndPermissionsSeeder.php`:
```php
'post.view', 'post.create', 'post.edit', 'post.delete',
```

Then re-run the seeder:
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

Or create permissions directly from the admin panel at `/admin/permissions`.

### Extending the Super Admin Gate

The `Gate::before` in `AuthServiceProvider` makes super-admin bypass all checks automatically. No additional configuration needed.

---

## 🤝 Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/my-feature`)
3. Commit your changes (`git commit -m 'Add my feature'`)
4. Push to the branch (`git push origin feature/my-feature`)
5. Open a Pull Request

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).

---

## 🙏 Credits

- [Laravel](https://laravel.com)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [SB Admin 2](https://startbootstrap.com/theme/sb-admin-2)
