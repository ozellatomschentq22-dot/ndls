<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_message_model extends CI_Model {

    private $table = 'admin_messages';

    public function __construct() {
        parent::__construct();
    }

    // Create admin_messages table if it doesn't exist
    public function create_table() {
        $sql = "CREATE TABLE IF NOT EXISTS admin_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_id INT NOT NULL,
            customer_name VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            subject VARCHAR(255) DEFAULT 'New Message',
            is_read BOOLEAN DEFAULT FALSE,
            admin_id INT NULL,
            admin_name VARCHAR(255) NULL,
            is_admin_reply BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_customer_id (customer_id),
            INDEX idx_is_read (is_read),
            INDEX idx_created_at (created_at),
            INDEX idx_admin_id (admin_id)
        )";
        
        $this->db->query($sql);
    }

    // Add a message from customer to admin
    public function add_customer_message($customer_id, $customer_name, $message) {
        $data = array(
            'customer_id' => $customer_id,
            'customer_name' => $customer_name,
            'message' => $message,
            'is_read' => FALSE
        );
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // Add a reply from admin to customer
    public function add_admin_reply($customer_id, $admin_id, $admin_name, $message) {
        $data = array(
            'customer_id' => $customer_id,
            'customer_name' => 'Admin Reply',
            'message' => $message,
            'is_read' => FALSE,
            'admin_id' => $admin_id,
            'admin_name' => $admin_name,
            'is_admin_reply' => TRUE
        );
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // Create a new message from staff to customer
    public function create_message($data) {
        $message_data = array(
            'customer_id' => $data['customer_id'],
            'customer_name' => 'Staff Message',
            'message' => $data['message'],
            'is_read' => FALSE
        );
        
        // Add optional fields if they exist in the table
        if (isset($data['admin_id'])) {
            $message_data['admin_id'] = $data['admin_id'];
        }
        if (isset($data['admin_name'])) {
            $message_data['admin_name'] = $data['admin_name'];
        } else {
            $message_data['admin_name'] = 'Staff';
        }
        if (isset($data['is_admin_reply'])) {
            $message_data['is_admin_reply'] = $data['is_admin_reply'];
        } else {
            $message_data['is_admin_reply'] = TRUE;
        }
        
        $this->db->insert($this->table, $message_data);
        return $this->db->insert_id();
    }

    // Get all unread messages for admin
    public function get_unread_messages() {
        return $this->db->where('is_read', FALSE)
                        ->order_by('created_at', 'ASC')
                        ->get($this->table)
                        ->result();
    }

    // Get all messages for admin with customer information
    public function get_all_messages($limit = 50) {
        $this->db->select('admin_messages.customer_id, users.first_name, users.last_name, users.email, COUNT(admin_messages.id) as message_count, MAX(admin_messages.created_at) as last_message_time')
                 ->from($this->table)
                 ->join('users', 'users.id = admin_messages.customer_id', 'left')
                 ->group_by('admin_messages.customer_id, users.first_name, users.last_name, users.email')
                 ->order_by('last_message_time', 'DESC')
                 ->limit($limit);
        
        $result = $this->db->get()->result();
        
        // Ensure customer_id is always present
        foreach ($result as $row) {
            if (!isset($row->customer_id)) {
                $row->customer_id = 0;
            }
        }
        
        return $result;
    }

    // Get messages by customer
    public function get_messages_by_customer($customer_id) {
        return $this->db->where('customer_id', $customer_id)
                        ->order_by('created_at', 'ASC')
                        ->get($this->table)
                        ->result();
    }

    // Mark message as read
    public function mark_as_read($message_id) {
        return $this->db->where('id', $message_id)
                        ->update($this->table, array('is_read' => TRUE));
    }

    // Mark all messages from customer as read
    public function mark_customer_messages_as_read($customer_id) {
        return $this->db->where('customer_id', $customer_id)
                        ->where('is_read', FALSE)
                        ->update($this->table, array('is_read' => TRUE));
    }

    // Get unread count
    public function get_unread_count() {
        return $this->db->where('is_read', FALSE)
                        ->count_all_results($this->table);
    }

    // Get recent messages for admin dashboard
    public function get_recent_messages($limit = 10) {
        return $this->db->order_by('created_at', 'DESC')
                        ->limit($limit)
                        ->get($this->table)
                        ->result();
    }

    // Delete message
    public function delete_message($message_id) {
        return $this->db->where('id', $message_id)
                        ->delete($this->table);
    }

    // Get message by ID
    public function get_message($message_id) {
        return $this->db->where('id', $message_id)
                        ->get($this->table)
                        ->row();
    }

    // Check if customer has unread messages
    public function customer_has_unread($customer_id) {
        return $this->db->where('customer_id', $customer_id)
                        ->where('is_read', FALSE)
                        ->count_all_results($this->table) > 0;
    }
}
?> 