# Admin Users Manager

This package provides comprehensive CRUD operations for managing admin user in the admin panel.

## Features

- Role-based user management (Admin, Super Admin, Accountant Manager)
- Create new users with specific roles
- View a list of users filtered by role
- Update user details and roles
- Delete users

---

## Requirements

- PHP >=8.2
- Laravel Framework >= 12.x

---

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-admins.git"
    }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/admins:@dev
    ```

### 3. Publish assets
    ```bash
    php artisan admin:publish --force
    ```
---

## Usage

1. **Create**: Add a new user with name, email, password, and assign a role.
2. **Read**: View all users, filter by role, and paginate results.
3. **Update**: Edit user information and change roles.
4. **Delete**: Remove users as needed.

## Example Endpoints

| Method | Endpoint            | Description                         |
|--------|---------------------|-------------------------------------|
| GET    | `/admins`           | List all admins                     |
| POST   | `/admins`           | Create a new admin                  |
| GET    | `/admins/{id}`      | Get admin details                   |
| PUT    | `/admins/{id}`      | Update a admin                      |
| DELETE | `/admins/{id}`      | Delete a admin                      |
| GET    | `/admins?role=admin`| List admins by role (e.g., Admin)   |

---

## Protecting Admin Routes

Protect your routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // Admin users routes here
});
```
---

## Database Tables

- `admins` - Stores admin users information
---

## License

This package is open-sourced software licensed under the MIT license.