# PDP v2 - NextGen Personal Planner (PHP MVC, XAMPP, MySQL)

PDP v2 (Personalized Daily Planner NextGen) is an enhanced web-based application that helps users manage tasks and time, built using:

- PHP (MVC pattern)
- MySQL (via XAMPP)
- Modern Glassmorphism UI (Dark Theme)

This README explains how to run the project and outlines next development steps.

## Requirements

- XAMPP installed (Apache + MySQL)
- PHP 7.4+ (or compatible with your XAMPP)
- Web browser

## Project Structure

Root folder:

- `index.php` – front controller and simple router
- `config.php` – database configuration and PDO helper
- `database.sql` – schema and seed data (categories)
- `app/core` – base MVC classes
  - `Controller.php` – base controller with `render()` method
  - `Model.php` – base model with shared `db()` method
- `app/controllers` – application controllers
  - `AuthController.php` – login, registration, logout
  - `DashboardController.php` – main user dashboard and task creation
- `app/models` – database models
  - `User.php` – user retrieval/creation
  - `Task.php` – task creation and listing
  - `Category.php` – category listing
- `views` – HTML views
  - `views/auth/index.php` – login and registration page
  - `views/dashboard/index.php` – dashboard with task list and add form
- Legacy helpers
  - `dashboard.php` – redirects into MVC dashboard route
  - `logout.php` – standalone logout script (MVC logout also available)

## Database Setup

1. Start Apache and MySQL from the XAMPP Control Panel.
2. Open `http://localhost/phpmyadmin` in your browser.
3. Click the "Import" tab.
4. Choose `database.sql` from:

   `c:\xampp\htdocs\PDP\database.sql`

5. Click "Go" to execute the script.

This will:

- Create the `personalized_daily_planner` database.
- Create all core tables (`users`, `tasks`, `categories`, etc.).
- Insert default categories: Work, Study, Health, Personal.

If you prefer the command line and `mysql` is available in PATH:

```bash
mysql -u root -p < c:\xampp\htdocs\PDP\database.sql
```

Use your actual MySQL password if it is not empty.

## Configuration

Database connection settings are in `config.php`:

- Host: `127.0.0.1`
- Database: `personalized_daily_planner`
- User: `root`
- Password: empty string by default

Adjust `$dbUser` and `$dbPass` if your XAMPP MySQL credentials differ.

## Running the Application

1. Ensure Apache and MySQL are running in XAMPP.
2. Navigate in your browser to:

   `http://localhost/PDP/index.php`

3. You should see the authentication page with:
   - Login form
   - Registration form

4. Or use the default users below.

### Default Credentials

After running `seed_users.php`, you can use these accounts:

- **Admin User**:
  - Email: `admin@example.com`
  - Password: `admin123`
  - Role: Admin
- **Regular User**:
  - Email: `john@example.com`
  - Password: `user123`
- **Regular User 2**:
  - Email: `jane@example.com`
  - Password: `user123`

Basic flow:

- Register a new user account.
- Log in using the same email and password.
- You will be redirected to the dashboard.
- From the dashboard, add tasks with optional deadline, priority, and category.
- Tasks are listed for the currently logged-in user.

## Routing Overview

The front controller (`index.php`) acts as a tiny router.

- If user is not logged in:
  - Requests are routed to `AuthController@index`.
- If user is logged in:
  - Default route: `DashboardController@index`.
  - You can explicitly set routes via query parameters:
    - `index.php?controller=auth&action=index`
    - `index.php?controller=auth&action=logout`
    - `index.php?controller=dashboard&action=index`

Controller class names follow the pattern:

- `App\Controllers\{Name}Controller`

And are autoloaded from `app/controllers`.

## Current Features

- User registration with validation and password hashing.
- User login with validation and session-based authentication.
- Simple dashboard:
  - Add tasks for the logged-in user.
  - List tasks with deadline, priority, category, and status.
- Basic categories pre-seeded via SQL.
- MVC layering:
  - Controllers: request handling and orchestration.
  - Models: database access.
  - Views: presentational HTML.

## Next Steps

The next steps you asked to capture are:

- Adding an MVC-based admin panel.
- Adding task edit/delete/complete actions as separate controller methods and routes.

Suggested direction:

1. **MVC-based admin panel**
   - Create `AdminController` under `app/controllers`.
   - Add routes like `index.php?controller=admin&action=index`.
   - Implement actions for:
     - Viewing users and basic system stats.
     - Managing templates and categories.
   - Add views under `views/admin`.

2. **Task edit/delete/complete actions**
   - Extend `Task` model with methods like `update`, `delete`, `markCompleted`.
   - Extend `DashboardController` or add a `TaskController` with actions:
     - `edit`, `update`, `delete`, `complete`.
   - Update `views/dashboard/index.php` to add buttons/links for those operations.
   - Use query parameters or simple routing conventions like:
     - `index.php?controller=task&action=complete&id={task_id}`

As you continue, keep all new logic inside controllers/models and use views only for display so the MVC structure remains clean.

