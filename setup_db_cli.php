<?php
/**
 * Command Line Database Setup Script
 * Run this script from terminal: php setup_db_cli.php
 */

// Database configuration for XAMPP
$host = '127.0.0.1'; // Use IP instead of localhost
$port = 3306;
$username = 'root';
$password = '';
$database = 'jipi_db';

echo "=== JIPI E-commerce Database Setup ===\n\n";

// Test connection to MySQL server
try {
    $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Connected to MySQL server successfully\n";
    
    // Check if database exists
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Database '$database' already exists\n";
    } else {
        // Create database
        $pdo->exec("CREATE DATABASE `$database` CHARACTER SET utf8 COLLATE utf8_general_ci");
        echo "✓ Database '$database' created successfully\n";
    }
    
    // Connect to the specific database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Connected to database '$database' successfully\n";
    
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
    echo "✓ Users table created successfully\n";
    
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
    echo "✓ Wallets table created successfully\n";
    
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
    echo "✓ Products table created successfully\n";
    
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
    echo "✓ Orders table created successfully\n";
    
    // Create wallet_transactions table
    $sql = "CREATE TABLE IF NOT EXISTS `wallet_transactions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `type` enum('credit', 'debit') NOT NULL,
        `amount` decimal(10,2) NOT NULL,
        `description` varchar(255),
        `reference_id` int(11),
        `reference_type` enum('order', 'recharge', 'refund', 'manual') NOT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($sql);
    echo "✓ Wallet transactions table created successfully\n";
    
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
    echo "✓ Recharge requests table created successfully\n";
    
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
    echo "✓ Support tickets table created successfully\n";
    
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
    echo "✓ Ticket replies table created successfully\n";
    
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
    echo "✓ Customer addresses table created successfully\n";
    
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
    echo "✓ Leads table created successfully\n";
    
    // Insert default admin user
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@jipi.com', $adminPassword, 'System', 'Administrator', 'admin']);
    echo "✓ Default admin user created\n";
    
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
    echo "✓ Sample products created\n";
    
    // Insert sample staff user
    $staffPassword = password_hash('staff123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['staff', 'staff@jipi.com', $staffPassword, 'John', 'Staff', 'staff']);
    echo "✓ Sample staff user created\n";
    
    // Insert sample customer
    $customerPassword = password_hash('customer123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['customer', 'customer@jipi.com', $customerPassword, 'Jane', 'Customer', 'customer']);
    echo "✓ Sample customer user created\n";
    
    echo "\n=== Database Setup Complete! ===\n";
    echo "\nDefault Login Credentials:\n";
    echo "- Admin: admin / admin123\n";
    echo "- Staff: staff / staff123\n";
    echo "- Customer: customer / customer123\n";
    echo "\nYou can now access the system at: http://localhost/jipi\n";
    
} catch (PDOException $e) {
    echo "✗ Database Error: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "- Make sure XAMPP is running\n";
    echo "- Check that MySQL service is started\n";
    echo "- Verify the database credentials\n";
    echo "- Try accessing phpMyAdmin at http://localhost/phpmyadmin\n";
}
?> 