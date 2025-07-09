# Admin Users

This package provides comprehensive CRUD operations for managing users with different roles—Admin, Super Admin, and Accountant Manager—in the admin panel.

## Features

- Role-based user management (Admin, Super Admin, Accountant Manager)
- Create new users with specific roles
- View a list of users filtered by role
- Update user details and roles
- Delete users

## Roles

- **Admin**: Manage users and perform standard admin tasks.
- **Super Admin**: Full access, including managing Admins and Accountant Managers.
- **Accountant Manager**: Access to accounting-related user management.

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

## Requirements

- PHP 8.2+
- Laravel Framework

## Update `composer.json` file

Add the following to your `composer.json` to use the package from a local path:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-admins.git"
    }
]
```

## Installation

```bash
composer require admin/admins:@dev
```

## Usage

1. Publish the configuration and migration files:
    ```bash
    php artisan admin:publish --force

    composer dump-autoload
    
    php artisan migrate
    ```
2. Access the Admin manager from your admin dashboard.

## Example

```php
// Creating a new admin
$admin = new Admin();
$admin->first_name = 'John';
$admin->last_name = 'Doe';
$admin->email = 'john.doe@example.com';
$admin->mobile = '9876543210';
$admin->status = 1;
$admin->save();
```

## Customization

You can customize views, routes, and permissions by editing the configuration file.

## License

This package is open-sourced software licensed under the Dotsquares.write code in the readme.md file regarding to the admin/admin manager