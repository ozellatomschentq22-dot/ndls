<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .info-box {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $title; ?></h1>
        
        <div class="info-box success">
            <h3><?php echo $message; ?></h3>
        </div>
        
        <div class="info-box">
            <h4>System Information:</h4>
            <p><strong>CodeIgniter Version:</strong> <?php echo $version; ?></p>
            <p><strong>PHP Version:</strong> <?php echo $php_version; ?></p>
            <p><strong>Base URL:</strong> <?php echo base_url(); ?></p>
            <p><strong>Current URL:</strong> <?php echo current_url(); ?></p>
        </div>
        
        <div class="info-box">
            <h4>Quick Links:</h4>
            <a href="<?php echo base_url('test/info'); ?>" class="btn">System Info</a>
            <a href="<?php echo base_url(); ?>" class="btn">Home</a>
            <a href="<?php echo base_url('welcome'); ?>" class="btn">Welcome Page</a>
        </div>
        
        <div class="info-box">
            <h4>Next Steps:</h4>
            <ul>
                <li>Create your first controller in <code>application/controllers/</code></li>
                <li>Create views in <code>application/views/</code></li>
                <li>Create models in <code>application/models/</code></li>
                <li>Configure your database in <code>application/config/database.php</code></li>
                <li>Set up your routes in <code>application/config/routes.php</code></li>
            </ul>
        </div>
    </div>
</body>
</html> 