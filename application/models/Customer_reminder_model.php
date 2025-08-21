<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_reminder_model extends CI_Model {
    
    private $table = 'customer_reminders';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Create table (for migration)
    public function create_table() {
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
        
        return $this->db->query($sql);
    }
    
    // Add a new reminder
    public function add_reminder($data) {
        $reminder_data = array(
            'customer_id' => $data['customer_id'],
            'admin_id' => $data['admin_id'],
            'title' => $data['title'],
            'content' => $data['content'],
            'priority' => isset($data['priority']) ? $data['priority'] : 'medium',
            'status' => isset($data['status']) ? $data['status'] : 'active',
            'due_date' => isset($data['due_date']) && !empty($data['due_date']) ? $data['due_date'] : NULL
        );
        
        $this->db->insert($this->table, $reminder_data);
        return $this->db->insert_id();
    }
    
    // Get reminders for a specific customer
    public function get_reminders_by_customer($customer_id, $status = null) {
        $this->db->select('customer_reminders.*, users.first_name as admin_first_name, users.last_name as admin_last_name')
                 ->from($this->table)
                 ->join('users', 'users.id = customer_reminders.admin_id')
                 ->where('customer_reminders.customer_id', $customer_id);
        
        if ($status) {
            $this->db->where('customer_reminders.status', $status);
        }
        
        return $this->db->order_by('customer_reminders.created_at', 'DESC')
                        ->get()
                        ->result();
    }
    
    // Get all reminders with pagination
    public function get_all_reminders($limit = 50, $offset = 0, $status = null, $priority = null) {
        $this->db->select('customer_reminders.*, 
                          customer.first_name as customer_first_name, 
                          customer.last_name as customer_last_name,
                          admin.first_name as admin_first_name, 
                          admin.last_name as admin_last_name')
                 ->from($this->table)
                 ->join('users as customer', 'customer.id = customer_reminders.customer_id')
                 ->join('users as admin', 'admin.id = customer_reminders.admin_id');
        
        if ($status) {
            $this->db->where('customer_reminders.status', $status);
        }
        
        if ($priority) {
            $this->db->where('customer_reminders.priority', $priority);
        }
        
        return $this->db->order_by('customer_reminders.created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result();
    }
    
    // Get reminder by ID
    public function get_reminder_by_id($id) {
        $this->db->select('customer_reminders.*, 
                          customer.first_name as customer_first_name, 
                          customer.last_name as customer_last_name,
                          admin.first_name as admin_first_name, 
                          admin.last_name as admin_last_name')
                 ->from($this->table)
                 ->join('users as customer', 'customer.id = customer_reminders.customer_id')
                 ->join('users as admin', 'admin.id = customer_reminders.admin_id')
                 ->where('customer_reminders.id', $id);
        
        return $this->db->get()->row();
    }
    
    // Update reminder
    public function update_reminder($id, $data) {
        $update_data = array(
            'title' => $data['title'],
            'content' => $data['content'],
            'priority' => $data['priority'],
            'status' => $data['status'],
            'due_date' => isset($data['due_date']) && !empty($data['due_date']) ? $data['due_date'] : NULL
        );
        
        return $this->db->where('id', $id)->update($this->table, $update_data);
    }
    
    // Delete reminder
    public function delete_reminder($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }
    
    // Get reminders by status
    public function get_reminders_by_status($status) {
        $this->db->select('customer_reminders.*, 
                          customer.first_name as customer_first_name, 
                          customer.last_name as customer_last_name,
                          admin.first_name as admin_first_name, 
                          admin.last_name as admin_last_name')
                 ->from($this->table)
                 ->join('users as customer', 'customer.id = customer_reminders.customer_id')
                 ->join('users as admin', 'admin.id = customer_reminders.admin_id')
                 ->where('customer_reminders.status', $status);
        
        return $this->db->order_by('customer_reminders.created_at', 'DESC')->get()->result();
    }
    
    // Get overdue reminders
    public function get_overdue_reminders() {
        $this->db->select('customer_reminders.*, 
                          customer.first_name as customer_first_name, 
                          customer.last_name as customer_last_name,
                          admin.first_name as admin_first_name, 
                          admin.last_name as admin_last_name')
                 ->from($this->table)
                 ->join('users as customer', 'customer.id = customer_reminders.customer_id')
                 ->join('users as admin', 'admin.id = customer_reminders.admin_id')
                 ->where('customer_reminders.status', 'active')
                 ->where('customer_reminders.due_date IS NOT NULL')
                 ->where('customer_reminders.due_date <', date('Y-m-d'));
        
        return $this->db->order_by('customer_reminders.due_date', 'ASC')->get()->result();
    }
    
    // Get due today reminders
    public function get_due_today_reminders() {
        $this->db->select('customer_reminders.*, 
                          customer.first_name as customer_first_name, 
                          customer.last_name as customer_last_name,
                          admin.first_name as admin_first_name, 
                          admin.last_name as admin_last_name')
                 ->from($this->table)
                 ->join('users as customer', 'customer.id = customer_reminders.customer_id')
                 ->join('users as admin', 'admin.id = customer_reminders.admin_id')
                 ->where('customer_reminders.status', 'active')
                 ->where('customer_reminders.due_date', date('Y-m-d'));
        
        return $this->db->order_by('customer_reminders.priority', 'DESC')->get()->result();
    }
    
    // Get upcoming reminders (next 7 days)
    public function get_upcoming_reminders($days = 7) {
        $end_date = date('Y-m-d', strtotime("+$days days"));
        
        $this->db->select('customer_reminders.*, 
                          customer.first_name as customer_first_name, 
                          customer.last_name as customer_last_name,
                          admin.first_name as admin_first_name, 
                          admin.last_name as admin_last_name')
                 ->from($this->table)
                 ->join('users as customer', 'customer.id = customer_reminders.customer_id')
                 ->join('users as admin', 'admin.id = customer_reminders.admin_id')
                 ->where('customer_reminders.status', 'active')
                 ->where('customer_reminders.due_date IS NOT NULL')
                 ->where('customer_reminders.due_date >=', date('Y-m-d'))
                 ->where('customer_reminders.due_date <=', $end_date);
        
        return $this->db->order_by('customer_reminders.due_date', 'ASC')->get()->result();
    }
    
    // Count reminders by status
    public function count_reminders_by_status($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results($this->table);
    }
    
    // Count reminders by priority
    public function count_reminders_by_priority($priority = null) {
        if ($priority) {
            $this->db->where('priority', $priority);
        }
        return $this->db->count_all_results($this->table);
    }
    
    // Get reminder statistics
    public function get_reminder_stats() {
        $stats = array();
        
        // Total reminders
        $stats['total'] = $this->db->count_all($this->table);
        
        // Reminders by status
        $status_counts = $this->db->select('status, COUNT(*) as count')
                                  ->group_by('status')
                                  ->get($this->table)
                                  ->result();
        
        foreach ($status_counts as $status) {
            $stats[$status->status] = $status->count;
        }
        
        // Reminders by priority
        $priority_counts = $this->db->select('priority, COUNT(*) as count')
                                    ->group_by('priority')
                                    ->get($this->table)
                                    ->result();
        
        foreach ($priority_counts as $priority) {
            $stats[$priority->priority . '_priority'] = $priority->count;
        }
        
        // Overdue reminders
        $stats['overdue'] = $this->db->where('status', 'active')
                                     ->where('due_date IS NOT NULL')
                                     ->where('due_date <', date('Y-m-d'))
                                     ->count_all_results($this->table);
        
        // Due today
        $stats['due_today'] = $this->db->where('status', 'active')
                                       ->where('due_date', date('Y-m-d'))
                                       ->count_all_results($this->table);
        
        // Due this week
        $end_date = date('Y-m-d', strtotime('+7 days'));
        $stats['due_this_week'] = $this->db->where('status', 'active')
                                           ->where('due_date IS NOT NULL')
                                           ->where('due_date >=', date('Y-m-d'))
                                           ->where('due_date <=', $end_date)
                                           ->count_all_results($this->table);
        
        return $stats;
    }
    
    // Search reminders
    public function search_reminders($search_term, $limit = 50, $offset = 0) {
        $this->db->select('customer_reminders.*, 
                          customer.first_name as customer_first_name, 
                          customer.last_name as customer_last_name,
                          admin.first_name as admin_first_name, 
                          admin.last_name as admin_last_name')
                 ->from($this->table)
                 ->join('users as customer', 'customer.id = customer_reminders.customer_id')
                 ->join('users as admin', 'admin.id = customer_reminders.admin_id')
                 ->group_start()
                 ->like('customer_reminders.title', $search_term)
                 ->or_like('customer_reminders.content', $search_term)
                 ->or_like('customer.first_name', $search_term)
                 ->or_like('customer.last_name', $search_term)
                 ->or_like('admin.first_name', $search_term)
                 ->or_like('admin.last_name', $search_term)
                 ->group_end();
        
        return $this->db->order_by('customer_reminders.created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result();
    }
    
    // Mark reminder as completed
    public function mark_completed($id) {
        return $this->db->where('id', $id)->update($this->table, array('status' => 'completed'));
    }
    
    // Mark reminder as archived
    public function mark_archived($id) {
        return $this->db->where('id', $id)->update($this->table, array('status' => 'archived'));
    }
    
    // Reactivate reminder
    public function reactivate($id) {
        return $this->db->where('id', $id)->update($this->table, ['status' => 'active']);
    }
    
    // Count all reminders
    public function count_all_reminders($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results($this->table);
    }
    
    // Get recent reminders
    public function get_recent_reminders($limit = 5) {
        $this->db->select('customer_reminders.*, 
                          customer.first_name as customer_first_name, 
                          customer.last_name as customer_last_name,
                          admin.first_name as admin_first_name, 
                          admin.last_name as admin_last_name')
                 ->from($this->table)
                 ->join('users as customer', 'customer.id = customer_reminders.customer_id')
                 ->join('users as admin', 'admin.id = customer_reminders.admin_id')
                 ->where('customer_reminders.status', 'active')
                 ->order_by('customer_reminders.created_at', 'DESC')
                 ->limit($limit);
        
        return $this->db->get()->result();
    }
    
    // Get reminders with pagination (alias for get_all_reminders)
    public function get_reminders($limit = 20, $offset = 0, $status = null, $priority = null) {
        return $this->get_all_reminders($limit, $offset, $status, $priority);
    }
}
?> 