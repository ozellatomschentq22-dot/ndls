<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {

    private $table = 'notifications';

    public function __construct() {
        parent::__construct();
    }

    public function create_table() {
        $sql = "CREATE TABLE IF NOT EXISTS notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            user_type ENUM('customer', 'admin', 'staff') NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            type ENUM('info', 'success', 'warning', 'danger') DEFAULT 'info',
            category ENUM('order', 'support', 'wallet', 'system', 'chat') DEFAULT 'system',
            is_read BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_user_type (user_type),
            INDEX idx_is_read (is_read),
            INDEX idx_category (category),
            INDEX idx_created_at (created_at)
        )";
        
        return $this->db->query($sql);
    }

    // Add a new notification
    public function add_notification($user_id, $user_type, $title, $message, $type = 'info', $category = 'system') {
        $data = array(
            'user_id' => $user_id,
            'user_type' => $user_type,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'category' => $category,
            'is_read' => FALSE
        );
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // Get notifications for a user
    public function get_notifications($user_id, $user_type, $limit = 50, $offset = 0) {
        return $this->db->where('user_id', $user_id)
                        ->where('user_type', $user_type)
                        ->order_by('created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get($this->table)
                        ->result();
    }

    // Get unread notifications count
    public function get_unread_count($user_id, $user_type) {
        $result = $this->db->where('user_id', $user_id)
                           ->where('user_type', $user_type)
                           ->where('is_read', FALSE)
                           ->count_all_results($this->table);
        return $result;
    }

    // Get unread notifications
    public function get_unread_notifications($user_id, $user_type, $limit = 10) {
        return $this->db->where('user_id', $user_id)
                        ->where('user_type', $user_type)
                        ->where('is_read', FALSE)
                        ->order_by('created_at', 'DESC')
                        ->limit($limit)
                        ->get($this->table)
                        ->result();
    }

    // Mark notification as read
    public function mark_as_read($notification_id) {
        return $this->db->where('id', $notification_id)
                        ->update($this->table, array('is_read' => TRUE));
    }

    // Mark all notifications as read for a user
    public function mark_all_as_read($user_id, $user_type) {
        return $this->db->where('user_id', $user_id)
                        ->where('user_type', $user_type)
                        ->update($this->table, array('is_read' => TRUE));
    }

    // Delete notification
    public function delete_notification($notification_id) {
        return $this->db->where('id', $notification_id)
                        ->delete($this->table);
    }

    // Delete old notifications (older than 30 days)
    public function cleanup_old_notifications() {
        $thirty_days_ago = date('Y-m-d H:i:s', strtotime('-30 days'));
        return $this->db->where('created_at <', $thirty_days_ago)
                        ->delete($this->table);
    }

    // Get notifications by category
    public function get_notifications_by_category($user_id, $user_type, $category, $limit = 20) {
        return $this->db->where('user_id', $user_id)
                        ->where('user_type', $user_type)
                        ->where('category', $category)
                        ->order_by('created_at', 'DESC')
                        ->limit($limit)
                        ->get($this->table)
                        ->result();
    }

    // Get notification statistics
    public function get_notification_stats($user_id, $user_type) {
        $stats = array();
        
        // Total notifications
        $stats['total'] = $this->db->where('user_id', $user_id)
                                   ->where('user_type', $user_type)
                                   ->count_all_results($this->table);
        
        // Unread notifications
        $stats['unread'] = $this->db->where('user_id', $user_id)
                                    ->where('user_type', $user_type)
                                    ->where('is_read', FALSE)
                                    ->count_all_results($this->table);
        
        // Read notifications
        $stats['read'] = $stats['total'] - $stats['unread'];
        
        return $stats;
    }
    
    // Count all notifications
    public function count_all_notifications($user_type = null) {
        if ($user_type) {
            $this->db->where('user_type', $user_type);
        }
        return $this->db->count_all_results($this->table);
    }
    
    // Get all notifications with pagination (for staff/admin)
    public function get_all_notifications($limit = 20, $offset = 0, $user_type = null) {
        if ($user_type) {
            $this->db->where('user_type', $user_type);
        }
        
        return $this->db->order_by('created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get($this->table)
                        ->result();
    }
}
?> 