<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_payments_model extends CI_Model {
    
    private $table = 'vendor_payments';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Create table (for migration)
    public function create_table() {
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
        
        return $this->db->query($sql);
    }
    
    // Add a new payment record
    public function add_payment($data) {
        // Clean amount - remove $ and , before saving
        $amount = str_replace(['$', ','], '', $data['amount']);
        $amount = floatval($amount);
        
        $payment_data = array(
            'date' => $data['date'],
            'sender' => $data['sender'],
            'receiver' => $data['receiver'],
            'mode' => $data['mode'],
            'amount' => $amount,
            'status' => $data['status'],
            'vendor_id' => $data['vendor_id'],
            'screenshot' => $data['screenshot'] ?? NULL,
            'created_by' => $data['created_by']
        );
        
        $this->db->insert($this->table, $payment_data);
        return $this->db->insert_id();
    }
    
    // Get all payments with pagination
    public function get_all_payments($limit = 50, $offset = 0, $filters = array()) {
        $this->db->select('vendor_payments.*, users.first_name as creator_first_name, users.last_name as creator_last_name, vendors.name as vendor_name, vendors.payout_type')
                 ->from($this->table)
                 ->join('users', 'users.id = vendor_payments.created_by')
                 ->join('vendors', 'vendors.id = vendor_payments.vendor_id', 'left');
        
        // Apply filters
        if (!empty($filters['vendor'])) {
            $this->db->like('vendors.name', $filters['vendor']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('vendor_payments.status', $filters['status']);
        }
        
        if (!empty($filters['mode'])) {
            $this->db->where('vendor_payments.mode', $filters['mode']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('vendor_payments.date >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('vendor_payments.date <=', $filters['date_to']);
        }
        
        return $this->db->order_by('vendor_payments.date', 'DESC')
                        ->order_by('vendor_payments.created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result();
    }
    
    // Count all payments
    public function count_all_payments($filters = array()) {
        $this->db->from($this->table)
                 ->join('vendors', 'vendors.id = vendor_payments.vendor_id', 'left');
        
        // Apply filters
        if (!empty($filters['vendor'])) {
            $this->db->like('vendors.name', $filters['vendor']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('vendor_payments.status', $filters['status']);
        }
        
        if (!empty($filters['mode'])) {
            $this->db->where('vendor_payments.mode', $filters['mode']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('vendor_payments.date >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('vendor_payments.date <=', $filters['date_to']);
        }
        
        return $this->db->count_all_results();
    }
    
    // Get payment by ID
    public function get_payment_by_id($id) {
        return $this->db->select('vendor_payments.*, users.first_name as creator_first_name, users.last_name as creator_last_name, vendors.name as vendor_name, vendors.payout_type')
                        ->from($this->table)
                        ->join('users', 'users.id = vendor_payments.created_by')
                        ->join('vendors', 'vendors.id = vendor_payments.vendor_id', 'left')
                        ->where('vendor_payments.id', $id)
                        ->get()
                        ->row();
    }
    
    // Update payment
    public function update_payment($id, $data) {
        // Clean amount - remove $ and , before saving
        if (isset($data['amount'])) {
            $amount = str_replace(['$', ','], '', $data['amount']);
            $data['amount'] = floatval($amount);
        }
        
        return $this->db->where('id', $id)->update($this->table, $data);
    }
    
    // Delete payment
    public function delete_payment($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }
    
    // Get vendor summary (vendor-wise total approvals and rejections)
    public function get_vendor_summary() {
        $sql = "SELECT 
                    vendor_payments.vendor_id,
                    vendors.name as vendor,
                    COUNT(*) as total_payments,
                    SUM(CASE WHEN vendor_payments.status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN vendor_payments.status = 'Approved' THEN 1 ELSE 0 END) as approved_count,
                    SUM(CASE WHEN vendor_payments.status = 'Declined' THEN 1 ELSE 0 END) as declined_count,
                    SUM(CASE WHEN vendor_payments.status = 'Pending' THEN vendor_payments.amount ELSE 0 END) as pending_amount,
                    SUM(CASE WHEN vendor_payments.status = 'Approved' THEN vendor_payments.amount ELSE 0 END) as approved_amount,
                    SUM(CASE WHEN vendor_payments.status = 'Declined' THEN vendor_payments.amount ELSE 0 END) as declined_amount,
                    SUM(vendor_payments.amount) as total_amount
                FROM vendor_payments 
                LEFT JOIN vendors ON vendors.id = vendor_payments.vendor_id
                GROUP BY vendor_payments.vendor_id, vendors.name 
                ORDER BY total_amount DESC";
        
        return $this->db->query($sql)->result();
    }
    
    // Get overall summary
    public function get_overall_summary() {
        $sql = "SELECT 
                    COUNT(*) as total_payments,
                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved_count,
                    SUM(CASE WHEN status = 'Declined' THEN 1 ELSE 0 END) as declined_count,
                    SUM(CASE WHEN status = 'Pending' THEN amount ELSE 0 END) as pending_amount,
                    SUM(CASE WHEN status = 'Approved' THEN amount ELSE 0 END) as approved_amount,
                    SUM(CASE WHEN status = 'Declined' THEN amount ELSE 0 END) as declined_amount,
                    SUM(amount) as total_amount
                FROM vendor_payments";
        
        return $this->db->query($sql)->row();
    }
    
    // Get payments by vendor
    public function get_payments_by_vendor($vendor, $limit = 50, $offset = 0) {
        return $this->db->select('vendor_payments.*, users.first_name as creator_first_name, users.last_name as creator_last_name')
                        ->from($this->table)
                        ->join('users', 'users.id = vendor_payments.created_by')
                        ->where('vendor_payments.vendor', $vendor)
                        ->order_by('vendor_payments.date', 'DESC')
                        ->order_by('vendor_payments.created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result();
    }
    
    // Get unique vendors
    public function get_unique_vendors() {
        return $this->db->select('vendors.id, vendors.name')
                        ->from('vendors')
                        ->where('vendors.status', 'active')
                        ->order_by('vendors.name', 'ASC')
                        ->get()
                        ->result();
    }
    
    // Get payment modes
    public function get_payment_modes() {
        return array('Zelle', 'Cash App', 'Venmo');
    }
    
    // Get payment statuses
    public function get_payment_statuses() {
        return array('Pending', 'Approved', 'Declined');
    }
    
    // Search payments
    public function search_payments($search_term, $limit = 50, $offset = 0) {
        $this->db->select('vendor_payments.*, users.first_name as creator_first_name, users.last_name as creator_last_name')
                 ->from($this->table)
                 ->join('users', 'users.id = vendor_payments.created_by')
                 ->group_start()
                 ->like('vendor_payments.sender', $search_term)
                 ->or_like('vendor_payments.receiver', $search_term)
                 ->or_like('vendor_payments.vendor', $search_term)
                 ->group_end();
        
        return $this->db->order_by('vendor_payments.date', 'DESC')
                        ->order_by('vendor_payments.created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result();
    }
    
    // Count search results
    public function count_search_results($search_term) {
        $this->db->group_start()
                 ->like('sender', $search_term)
                 ->or_like('receiver', $search_term)
                 ->or_like('vendor', $search_term)
                 ->group_end();
        
        return $this->db->count_all_results($this->table);
    }

    // Check for duplicate payment
    public function check_duplicate_payment($date, $sender, $receiver, $amount, $vendor_id) {
        return $this->db->where('date', $date)
                        ->where('sender', $sender)
                        ->where('receiver', $receiver)
                        ->where('amount', $amount)
                        ->where('vendor_id', $vendor_id)
                        ->get($this->table)
                        ->row();
    }
} 