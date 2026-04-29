# PulseFit Database Setup Guide

## 1. Database Configuration

Before running the application, you need to configure the database connection:

**File to Edit:** `api/db.config.php`

Update these constants with your database credentials:
```php
define('DB_HOST', 'localhost');      // Your MySQL server hostname
define('DB_USER', 'root');           // MySQL username
define('DB_PASS', '');               // MySQL password
define('DB_NAME', 'pulsefit_db');    // Database name
define('DB_PORT', 3306);             // MySQL port
```

---

## 2. Create the Database

Run this SQL command in your MySQL client (phpMyAdmin, MySQL Workbench, or MySQL CLI):

```sql
CREATE DATABASE IF NOT EXISTS pulsefit_db;
USE pulsefit_db;
```

---

## 3. Create the Users Table

Run this SQL to create the users table with all required fields:

```sql
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique user identifier',
    first_name VARCHAR(100) NOT NULL COMMENT 'User first name',
    last_name VARCHAR(100) NOT NULL COMMENT 'User last name',
    email VARCHAR(255) UNIQUE NOT NULL COMMENT 'User email address',
    home_address VARCHAR(500) COMMENT 'Residential address',
    home_phone VARCHAR(20) COMMENT 'Home phone number',
    cell_phone VARCHAR(20) COMMENT 'Mobile/cell phone number',
    date_of_birth DATE COMMENT 'Date of birth for fitness tracking',
    gender ENUM('Male', 'Female', 'Other', 'Prefer not to say') COMMENT 'Gender preference',
    emergency_contact_name VARCHAR(100) COMMENT 'Emergency contact person',
    emergency_contact_phone VARCHAR(20) COMMENT 'Emergency contact phone',
    membership_status ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active' COMMENT 'Current membership status',
    membership_type VARCHAR(50) COMMENT 'Type of membership (e.g., Premium, Basic, Trial)',
    join_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Account creation date',
    notes TEXT COMMENT 'Additional notes or special requirements',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp',
    INDEX idx_email (email),
    INDEX idx_name (last_name, first_name),
    INDEX idx_phone (home_phone, cell_phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 4. Suggested Fields Explanation

### Required Fields (from your specification)
- **first_name** - User's first name
- **last_name** - User's last name
- **email** - Contact email (searchable, unique)
- **home_address** - Residential address
- **home_phone** - Home phone number (searchable)
- **cell_phone** - Mobile phone number (searchable)

### Recommended Additional Fields
- **date_of_birth** - Helps with personalized fitness recommendations and age-appropriate programs
- **gender** - Used for customized workout suggestions and wellness tracking
- **emergency_contact_name** - Safety requirement for gym/fitness facilities
- **emergency_contact_phone** - Safety requirement for gym/fitness facilities
- **membership_status** - Track which members are active, inactive, or suspended
- **membership_type** - Distinguish between membership tiers (Premium, Basic, Trial)
- **notes** - Store special requests, allergies, injuries, or special considerations
- **join_date** - Track membership tenure and create retention reports
- **created_at / updated_at** - Audit trail for data management

---

## 5. How to Connect from the Website

The website uses the functions in `api/db.config.php`:

### Get Connection
```php
require_once __DIR__ . '/../api/db.config.php';

$conn = get_db_connection();
if (!$conn) {
    die("Database connection failed");
}
```

### Execute Query with Parameters
```php
$query = "SELECT * FROM users WHERE email = ?";
$result = execute_query($conn, $query, [$email], 's');

if ($result) {
    $user = $result->fetch_assoc();
} else {
    echo "Query failed";
}
```

### Close Connection
```php
close_db_connection($conn);
```

---

## 6. Connection String Summary

| Item | Value |
|------|-------|
| **Host** | localhost (your local machine) or your server IP |
| **Port** | 3306 (default MySQL port) |
| **Database** | pulsefit_db |
| **Username** | root (change for production) |
| **Password** | (configure in db.config.php) |

---

## 7. Testing the Connection

After configuring `api/db.config.php`, you can test the connection by creating a test file:

```php
<?php
require_once 'api/db.config.php';

$conn = get_db_connection();
if ($conn) {
    echo "✓ Database connection successful!";
    close_db_connection($conn);
} else {
    echo "✗ Database connection failed!";
}
?>
```

---

## 8. Important Security Notes for Production

- Never use 'root' user in production - create a dedicated MySQL user
- Use strong passwords for database credentials
- Store credentials in environment variables, not hardcoded
- Use prepared statements (already implemented in db.config.php) to prevent SQL injection
- Backup your database regularly
- Use HTTPS in production
- Restrict database access by IP address if possible

---

## Need Help?

If you encounter connection issues:
1. Verify MySQL server is running
2. Check credentials in `api/db.config.php`
3. Ensure database and table are created
4. Check MySQL error logs for detailed error messages
5. Verify PHP MySQLi extension is enabled: `php -m | grep mysqli`
