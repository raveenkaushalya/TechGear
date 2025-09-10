# TechGear Database Setup Instructions

This guide will help you set up the TechGear database system that connects the admin products page with the customer-facing categories page.

## Prerequisites

1. XAMPP installed with MySQL and Apache running
2. TechGear website files placed in `c:/xampp/htdocs/TechGear/`

## Setup Steps

### 1. Create and Populate the Database

1. Open phpMyAdmin by going to `http://localhost/phpmyadmin/`
2. Click on the "Import" tab at the top
3. Click "Choose File" and select the `setup_database.sql` file from the root TechGear directory
4. Click "Go" to execute the SQL script
   - This will create the `techgear` database
   - Create tables for categories, products, and product images
   - Populate the tables with sample data

### 2. Database Configuration

The database connection is already configured in:
`src/includes/db_connection.php`

If your MySQL setup uses a different username, password, or hostname, update the configuration in this file.

### 3. How the Connection Works

The system is now set up to maintain product listings in real-time:

1. **Admin Panel** (`src/admin/products.php`):
   - Admins can add, edit, and delete products
   - Changes are stored in the database
   - Product details include name, description, price, quantity, category, status, and image

2. **Categories Page** (`src/pages/categories.php`):
   - Displays products from the database grouped by category
   - Any changes made in the admin panel are immediately reflected
   - Product details and images are dynamically loaded from the database

3. **Product Manager** (`src/includes/product_manager.php`):
   - Serves as the bridge between the admin interface and the customer-facing website
   - Handles database operations for both pages

## Technical Notes

- The database uses InnoDB for foreign key support
- Products are linked to categories with a one-to-many relationship
- Product images are stored separately with a one-to-many relationship to products
- The system supports featured products and limited editions

## Troubleshooting

If you encounter database connection issues:

### 1. Check MySQL Server Status

First, verify that your MySQL server is running:

1. Open XAMPP Control Panel
2. Look for MySQL in the list of services
3. The status should show "Running" with a green indicator
4. If not running, click the "Start" button next to MySQL
5. Wait a few seconds and check if the status changes to "Running"

### 2. Verify Database Existence

Make sure the TechGear database was created properly:

1. Open phpMyAdmin: http://localhost/phpmyadmin/
2. Look for 'techgear' in the list of databases on the left side
3. If it doesn't exist, import the setup_database.sql file as described in the setup steps

### 3. Check Connection Settings

Verify the database connection settings:

1. Open `src/includes/db_connection.php`
2. Check that the settings match your MySQL configuration:
   ```php
   define('DB_HOST', '127.0.0.1'); // Use 'localhost' if IP doesn't work
   define('DB_PORT', 3306);        // Default MySQL port
   define('DB_USERNAME', 'root');  // Default XAMPP username
   define('DB_PASSWORD', '');      // Default XAMPP has no password
   define('DB_NAME', 'techgear');  // Our database name
   ```
3. If you've set a custom password for MySQL, update it here

### 4. Test MySQL Connection Manually

You can test your MySQL connection outside of the website:

1. Open Command Prompt/Terminal
2. Enter: `mysql -u root -p -h 127.0.0.1`
   (Use your username/password if different from default)
3. If it connects successfully, type `SHOW DATABASES;` to see if 'techgear' is listed
4. If it fails to connect, it indicates a MySQL server issue

### 5. Check Apache Error Logs

If problems persist, check the error logs:

1. Open XAMPP Control Panel
2. Click the "Logs" button for Apache
3. Look for any PHP or MySQL related errors

### Fallback Mode

If you're unable to resolve database connection issues, TechGear has a built-in fallback mode:

1. The website will continue to function using static backup data
2. You'll see a notification banner when browsing in fallback mode
3. Admin functions (add/edit/delete products) will be temporarily disabled
4. Once the database connection is restored, the website will automatically switch back to database mode

For additional help, refer to the code comments in the relevant files.
