# JIPI E-commerce System

A comprehensive e-commerce platform built with CodeIgniter 3 featuring multi-role functionality, wallet system, and lead management.

## 🚀 Features

### 👤 Multi-Role System
- **Admin**: Full access to all modules (CRUD + Approval + Reports)
- **Staff**: Can manage everything except deleting data
- **Customer**: Can place orders, recharge wallet, view orders, raise tickets

### 📦 Product Management
- Fixed quantities: 30, 60, 90, 180
- Product details: name, strength, price per quantity
- Active/inactive product status

### 💰 Wallet System
- Each customer has a wallet balance
- Admin can manually credit/debit wallets
- Customers can:
  - View balance
  - Request wallet recharge via defined payment modes
  - Use balance to place orders
- Recharge requests require manual admin approval

### 🔐 Payment Modes
- Credit Card
- PayPal
- Venmo
- Zelle
- CashApp
- Bank Transfer

### 🧾 Order Management
- Customers place orders from fixed-quantity products
- Order statuses: pending, processing, shipped, delivered, cancelled
- Admin/Staff can update status
- Order cost automatically deducted from wallet
- Automatic refund on order cancellation

### 🎟️ Support Tickets
- Customers can raise support tickets
- Admin/Staff can respond and update status
- Ticket statuses: open, in_progress, closed
- Priority levels: low, medium, high
- Ticket assignment to staff members

### 📈 Lead Management
- Admin/Staff can add/manage leads
- Lead details: name, contact, interest, company
- Lead statuses: new, contacted, qualified, converted, dropped
- Convert leads to customers
- Lead assignment to staff members

### 💻 Customer Dashboard
- View wallet balance
- Order history
- Support tickets
- Recharge requests
- Request new orders, wallet recharges, support tickets

## Requirements

- PHP 5.6+ (recommended: PHP 7.4+)
  - **Note**: PHP 8.x is supported with deprecation warnings suppressed
- MySQL 5.5+ or MariaDB 10.0+
- Apache web server
- mod_rewrite enabled

## Installation

1. **Clone or download** this project to your XAMPP htdocs directory
2. **Start XAMPP** and ensure Apache and MySQL are running
3. **Set up database** (recommended):
   - Run the database setup script: http://localhost/jipi/setup_database.php
   - Or manually create database via phpMyAdmin: http://localhost/phpmyadmin
   - Database name: `jipi_db`

## Configuration

### Base URL
The base URL is configured for: `http://localhost/jipi/`

If your setup is different, update `application/config/config.php`:
```php
$config['base_url'] = 'http://localhost/jipi/';
```

### Database
Database settings are in `application/config/database.php`:
- Host: localhost
- Username: root
- Password: (empty for default XAMPP)
- Database: jipi_db

### Clean URLs
Clean URLs are enabled (no index.php in URLs). If you have issues:
1. Ensure mod_rewrite is enabled in Apache
2. Check that .htaccess file is present in the root directory

## Testing

1. **Set up database first**: http://localhost/jipi/setup_database.php
2. **Login to the system**: http://localhost/jipi/auth/login
3. **Admin Dashboard**: http://localhost/jipi/admin/dashboard
4. **Test page**: http://localhost/jipi/test

### Default Login Credentials:
- **Admin**: admin / admin123
- **Staff**: staff / staff123  
- **Customer**: customer / customer123

## Project Structure

```
jipi/
├── application/          # Application files
│   ├── config/          # Configuration files
│   ├── controllers/     # Controllers
│   │   ├── Auth.php     # Authentication controller
│   │   ├── Admin.php    # Admin dashboard
│   │   └── Test.php     # Test controller
│   ├── models/          # Models
│   │   ├── User_model.php    # User management
│   │   └── Wallet_model.php  # Wallet operations
│   ├── views/           # Views
│   │   ├── auth/        # Authentication views
│   │   ├── admin/       # Admin dashboard views
│   │   └── test_view.php
│   └── ...
├── system/              # CodeIgniter system files
├── index.php            # Front controller
├── .htaccess            # URL rewriting
├── setup_database.php   # Database setup script
└── README.md            # This file
```

## Auto-loaded Libraries

The following libraries are auto-loaded:
- Database
- Session
- Form Validation

## Auto-loaded Helpers

The following helpers are auto-loaded:
- URL Helper
- Form Helper
- HTML Helper

## Development

### Creating a New Controller
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyController extends CI_Controller {
    public function index() {
        $this->load->view('my_view');
    }
}
```

### Creating a New Model
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyModel extends CI_Model {
    public function get_data() {
        return $this->db->get('my_table')->result();
    }
}
```

### Creating a New View
Create files in `application/views/` with `.php` extension.

## Security Notes

- Change default database credentials in production
- Set appropriate file permissions
- Keep CodeIgniter system files secure
- Use HTTPS in production

## Troubleshooting

### PHP 8.x Deprecation Warnings
- Deprecation warnings are automatically suppressed
- If you still see warnings, check the `.htaccess` file
- Alternative: Use the `php8_compatibility.php` patch

### 404 Errors
- Check if mod_rewrite is enabled
- Verify .htaccess file exists
- Check Apache configuration

### Database Connection Issues
- Ensure MySQL is running
- Run the database setup script: http://localhost/jipi/setup_database.php
- Verify database credentials in `application/config/database.php`
- Check if database exists

### Permission Issues
- Ensure web server has read access to project files
- Check write permissions for logs and cache directories

## Resources

- [CodeIgniter 3 Documentation](https://codeigniter.com/userguide3/)
- [CodeIgniter 3 User Guide](https://codeigniter.com/userguide3/)
- [CodeIgniter Forums](https://forum.codeigniter.com/) 