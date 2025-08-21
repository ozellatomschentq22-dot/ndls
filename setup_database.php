<?php
/**
 * Database Setup Script for CodeIgniter 3 E-commerce System
 * Run this script to create the database and all required tables
 */

// Database configuration
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'jipi_db';

echo "<h1>CodeIgniter 3 E-commerce System Database Setup</h1>";

// Test connection to MySQL server
try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Connected to MySQL server successfully</p>";
    
    // Check if database exists
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Database '$database' already exists</p>";
    } else {
        // Create database
        $pdo->exec("CREATE DATABASE `$database` CHARACTER SET utf8 COLLATE utf8_general_ci");
        echo "<p style='color: green;'>✓ Database '$database' created successfully</p>";
    }
    
    // Connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Connected to database '$database' successfully</p>";
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL UNIQUE,
        `email` varchar(100) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `first_name` varchar(50) NOT NULL,
        `last_name` varchar(50) NOT NULL,
        `phone` varchar(20),
        `role` enum('admin', 'staff', 'customer') NOT NULL DEFAULT 'customer',
        `status` enum('active', 'inactive') NOT NULL DEFAULT 'active',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Users table created successfully</p>";
    
    // Create wallets table
    $sql = "CREATE TABLE IF NOT EXISTS `wallets` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_id` (`user_id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Wallets table created successfully</p>";
    
    // Create products table
    $sql = "CREATE TABLE IF NOT EXISTS `products` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `description` text,
        `strength` varchar(50),
        `quantity` int(11) NOT NULL,
        `price` decimal(10,2) NOT NULL,
        `status` enum('active', 'inactive') NOT NULL DEFAULT 'active',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Products table created successfully</p>";
    
    // Create orders table
    $sql = "CREATE TABLE IF NOT EXISTS `orders` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `order_number` varchar(20) NOT NULL UNIQUE,
        `user_id` int(11) NOT NULL,
        `product_id` int(11) NOT NULL,
        `quantity` int(11) NOT NULL,
        `total_amount` decimal(10,2) NOT NULL,
        `status` enum('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
        `shipping_address` text,
        `notes` text,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Orders table created successfully</p>";
    
    // Create wallet_transactions table
    $sql = "CREATE TABLE IF NOT EXISTS `wallet_transactions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `type` enum('credit', 'debit') NOT NULL,
        `amount` decimal(10,2) NOT NULL,
        `balance_after` decimal(10,2) NULL,
        `description` varchar(255),
        `reference_id` int(11),
        `reference_type` enum('order', 'recharge', 'refund', 'manual') NOT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Wallet transactions table created successfully</p>";
    
    // Create recharge_requests table
    $sql = "CREATE TABLE IF NOT EXISTS `recharge_requests` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `amount` decimal(10,2) NOT NULL,
        `payment_mode` enum('credit_card', 'paypal', 'venmo', 'zelle', 'cashapp', 'bank_transfer') NOT NULL,
        `transaction_id` varchar(100),
        `status` enum('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
        `approved_by` int(11),
        `approved_at` timestamp NULL,
        `notes` text,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Recharge requests table created successfully</p>";
    
    // Create support_tickets table
    $sql = "CREATE TABLE IF NOT EXISTS `support_tickets` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `ticket_number` varchar(20) NOT NULL UNIQUE,
        `user_id` int(11) NOT NULL,
        `assigned_to` int(11),
        `subject` varchar(255) NOT NULL,
        `message` text NOT NULL,
        `category` enum('general', 'order', 'payment', 'product', 'technical', 'refund', 'other') NOT NULL DEFAULT 'general',
        `priority` enum('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
        `status` enum('open', 'in_progress', 'closed') NOT NULL DEFAULT 'open',
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Support tickets table created successfully</p>";
    
    // Create ticket_replies table
    $sql = "CREATE TABLE IF NOT EXISTS `ticket_replies` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `ticket_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `message` text NOT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Ticket replies table created successfully</p>";
    
    // Create customer_addresses table
    $sql = "CREATE TABLE IF NOT EXISTS `customer_addresses` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `address_name` varchar(100) NOT NULL,
        `full_name` varchar(100) NOT NULL,
        `address_line1` varchar(255) NOT NULL,
        `address_line2` varchar(255),
        `city` varchar(100) NOT NULL,
        `state` varchar(100) NOT NULL,
        `postal_code` varchar(20) NOT NULL,
        `country` varchar(100) NOT NULL DEFAULT 'USA',
        `phone` varchar(20),
        `is_default` tinyint(1) NOT NULL DEFAULT 0,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Customer addresses table created successfully</p>";
    
    // Create leads table
    $sql = "CREATE TABLE IF NOT EXISTS `leads` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `assigned_to` int(11),
        `first_name` varchar(50) NOT NULL,
        `last_name` varchar(50) NOT NULL,
        `email` varchar(100),
        `phone` varchar(20),
        `company` varchar(100),
        `interest` text,
        `status` enum('new', 'contacted', 'qualified', 'converted', 'dropped') NOT NULL DEFAULT 'new',
        `notes` text,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "<p style='color: green;'>✓ Leads table created successfully</p>";
    
    // Create customer_reminders table
    $sql = "CREATE TABLE IF NOT EXISTS customer_reminders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_id INT NOT NULL,
        admin_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
        status ENUM('active', 'completed', 'archived') DEFAULT 'active',
        due_date DATE NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_customer_id (customer_id),
        INDEX idx_admin_id (admin_id),
        INDEX idx_status (status),
        INDEX idx_priority (priority),
        INDEX idx_due_date (due_date)
    )";
    $pdo->exec($sql);

    // Create vendor_payments table
    $sql = "CREATE TABLE IF NOT EXISTS vendor_payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date DATE NOT NULL,
        sender VARCHAR(255) NOT NULL,
        receiver VARCHAR(255) NOT NULL,
        mode ENUM('Zelle', 'Cash App', 'Venmo') NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        status ENUM('Approved', 'Declined') NOT NULL,
        vendor VARCHAR(255) NOT NULL,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_date (date),
        INDEX idx_vendor (vendor),
        INDEX idx_status (status),
        INDEX idx_mode (mode),
        INDEX idx_created_by (created_by)
    )";
    $pdo->exec($sql);
    
    // Insert default admin user
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@jipi.com', $adminPassword, 'System', 'Administrator', 'admin']);
    echo "<p style='color: green;'>✓ Default admin user created</p>";
    
    // Insert sample products
    $products = [
        ['Product A', 'High-quality supplement', '500mg', 30, 29.99],
        ['Product A', 'High-quality supplement', '500mg', 60, 54.99],
        ['Product A', 'High-quality supplement', '500mg', 90, 79.99],
        ['Product A', 'High-quality supplement', '500mg', 180, 149.99],
        ['Product B', 'Premium formula', '1000mg', 30, 39.99],
        ['Product B', 'Premium formula', '1000mg', 60, 69.99],
        ['Product B', 'Premium formula', '1000mg', 90, 99.99],
        ['Product B', 'Premium formula', '1000mg', 180, 179.99]
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO products (name, description, strength, quantity, price) VALUES (?, ?, ?, ?, ?)");
    foreach ($products as $product) {
        $stmt->execute($product);
    }
    echo "<p style='color: green;'>✓ Sample products created</p>";
    
    // Insert sample staff user
    $staffPassword = password_hash('staff123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['staff', 'staff@jipi.com', $staffPassword, 'John', 'Staff', 'staff']);
    echo "<p style='color: green;'>✓ Sample staff user created</p>";
    
    // Insert sample customer
    $customerPassword = password_hash('customer123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['customer', 'customer@jipi.com', $customerPassword, 'Jane', 'Customer', 'customer']);
    echo "<p style='color: green;'>✓ Sample customer user created</p>";
    
    echo "<h2>Database Setup Complete!</h2>";
    echo "<p><strong>Default Login Credentials:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin / admin123</li>";
    echo "<li><strong>Staff:</strong> staff / staff123</li>";
    echo "<li><strong>Customer:</strong> customer / customer123</li>";
    echo "</ul>";
    echo "<p>You can now:</p>";
    echo "<ul>";
    echo "<li><a href='http://localhost/jipi/auth/login'>Login to the system</a></li>";
    echo "<li><a href='http://localhost/jipi/test'>Visit the test page</a></li>";
    echo "<li>Start developing your e-commerce application</li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database Error: " . $e->getMessage() . "</p>";
    echo "<h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Make sure XAMPP is running</li>";
    echo "<li>Check that MySQL service is started</li>";
    echo "<li>Verify the database credentials in application/config/database.php</li>";
    echo "<li>Try accessing phpMyAdmin at <a href='http://localhost/phpmyadmin'>http://localhost/phpmyadmin</a></li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><strong>Database Configuration:</strong></p>";
echo "<ul>";
echo "<li>Host: $host</li>";
echo "<li>Username: $username</li>";
echo "<li>Password: " . ($password ? '***' : '(empty)') . "</li>";
echo "<li>Database: $database</li>";
echo "</ul>";
?> 