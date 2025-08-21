-- =====================================================
-- JIPI E-COMMERCE SYSTEM - NEW FEATURES DATABASE UPDATES
-- =====================================================
-- Run these queries on your server to add new features
-- Date: 2025-01-31
-- =====================================================

-- 1. CUSTOMER REMINDERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `customer_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `reminder_date` date NOT NULL,
  `reminder_time` time DEFAULT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `reminder_date` (`reminder_date`),
  KEY `status` (`status`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. VENDOR PAYMENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `vendor_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `sender` varchar(255) NOT NULL,
  `receiver` varchar(255) NOT NULL,
  `mode` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `vendor_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `status` (`status`),
  KEY `date` (`date`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. DROPSHIPMENT ORDERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `dropshipment_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_address` text NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `center` varchar(255) NOT NULL,
  `status` enum('Pending','Processed','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `price` decimal(10,2) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `tracking_carrier` varchar(100) DEFAULT NULL,
  `tracking_url` varchar(500) DEFAULT NULL,
  `notes` text,
  `created_by` int(11) NOT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `processed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `status` (`status`),
  KEY `center` (`center`),
  KEY `created_by` (`created_by`),
  KEY `processed_by` (`processed_by`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. CENTERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `centers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. UPDATE NOTIFICATIONS TABLE TO INCLUDE STAFF USER TYPE
-- =====================================================
ALTER TABLE `notifications` 
MODIFY COLUMN `user_type` enum('customer','admin','staff') NOT NULL;

-- 6. ADD BALANCE_AFTER COLUMN TO WALLET_TRANSACTIONS TABLE
-- =====================================================
-- Check if balance_after column exists, if not add it
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() 
     AND TABLE_NAME = 'wallet_transactions' 
     AND COLUMN_NAME = 'balance_after') = 0,
    'ALTER TABLE `wallet_transactions` ADD COLUMN `balance_after` decimal(10,2) DEFAULT NULL AFTER `amount`',
    'SELECT "Column balance_after already exists" as message'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 7. ADD INDEXES FOR BETTER PERFORMANCE (SAFE VERSION)
-- =====================================================
-- Add indexes to existing tables for better query performance
-- These queries will fail gracefully if indexes already exist

-- Indexes for orders table
ALTER TABLE `orders` ADD INDEX `idx_user_id` (`user_id`);
ALTER TABLE `orders` ADD INDEX `idx_status` (`status`);
ALTER TABLE `orders` ADD INDEX `idx_created_at` (`created_at`);

-- Indexes for products table
ALTER TABLE `products` ADD INDEX `idx_status` (`status`);
ALTER TABLE `products` ADD INDEX `idx_category` (`category`);

-- Indexes for users table
ALTER TABLE `users` ADD INDEX `idx_user_type` (`user_type`);
ALTER TABLE `users` ADD INDEX `idx_status` (`status`);

-- Indexes for leads table
ALTER TABLE `leads` ADD INDEX `idx_status` (`status`);
ALTER TABLE `leads` ADD INDEX `idx_created_at` (`created_at`);

-- Indexes for support_tickets table
ALTER TABLE `support_tickets` ADD INDEX `idx_status` (`status`);
ALTER TABLE `support_tickets` ADD INDEX `idx_user_id` (`user_id`);

-- Indexes for recharge_requests table
ALTER TABLE `recharge_requests` ADD INDEX `idx_status` (`status`);
ALTER TABLE `recharge_requests` ADD INDEX `idx_user_id` (`user_id`);

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================
-- Run these queries to verify the tables were created successfully

-- Check if all new tables exist
SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    CREATE_TIME
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME IN ('customer_reminders', 'vendor_payments', 'dropshipment_orders', 'centers');

-- Check centers table structure
DESCRIBE centers;

-- Check notifications table structure
DESCRIBE notifications;

-- Check wallet_transactions table structure
DESCRIBE wallet_transactions;

-- =====================================================
-- ROLLBACK QUERIES (if needed)
-- =====================================================
-- Uncomment and run these if you need to remove the new features

/*
-- Remove new tables
DROP TABLE IF EXISTS customer_reminders;
DROP TABLE IF EXISTS vendor_payments;
DROP TABLE IF EXISTS dropshipment_orders;
DROP TABLE IF EXISTS centers;

-- Remove balance_after column
ALTER TABLE wallet_transactions DROP COLUMN balance_after;

-- Revert notifications user_type
ALTER TABLE notifications MODIFY COLUMN user_type enum('customer','admin') NOT NULL;
*/

-- =====================================================
-- END OF DATABASE UPDATES
-- ===================================================== 