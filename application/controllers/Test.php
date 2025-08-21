<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['title'] = 'CodeIgniter 3 Test Page';
        $data['message'] = 'Welcome to CodeIgniter 3!';
        $data['version'] = CI_VERSION;
        $data['php_version'] = PHP_VERSION;
        
        $this->load->view('test_view', $data);
    }

    public function info() {
        echo "<h1>CodeIgniter 3 Information</h1>";
        echo "<p><strong>CodeIgniter Version:</strong> " . CI_VERSION . "</p>";
        echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
        echo "<p><strong>Base URL:</strong> " . base_url() . "</p>";
        echo "<p><strong>Current URL:</strong> " . current_url() . "</p>";
        echo "<p><strong>Site URL:</strong> " . site_url() . "</p>";
        
        // Test database connection
        $this->load->database();
        try {
            if ($this->db->simple_query('SELECT 1')) {
                echo "<p><strong>Database:</strong> Connected successfully</p>";
            } else {
                echo "<p><strong>Database:</strong> Connection failed</p>";
            }
        } catch (Exception $e) {
            echo "<p><strong>Database:</strong> Connection error: " . $e->getMessage() . "</p>";
        }
    }
} 