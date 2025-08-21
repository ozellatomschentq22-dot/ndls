<?php
/**
 * PHP 8.x Compatibility Patch for CodeIgniter 3
 * Include this file at the top of your index.php if you encounter deprecation warnings
 */

// Suppress deprecation warnings for PHP 8.x
if (version_compare(PHP_VERSION, '8.0', '>=')) {
    // Suppress dynamic property creation warnings
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
    
    // Alternative: Set error handler to suppress specific warnings
    set_error_handler(function($severity, $message, $file, $line) {
        if (strpos($message, 'Creation of dynamic property') !== false) {
            return true; // Suppress this warning
        }
        return false; // Let other errors through
    }, E_DEPRECATED | E_USER_DEPRECATED);
}

// You can also add this to your .htaccess file:
// php_value error_reporting "E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED"
?> 