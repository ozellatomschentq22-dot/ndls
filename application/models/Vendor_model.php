<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_model extends CI_Model {
    
    private $table = 'vendors';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Add a new vendor
    public function add_vendor($data) {
        $vendor_data = array(
            'name' => $data['name'],
            'payout_type' => $data['payout_type'],
            'flat_rate_inr' => $data['payout_type'] === 'flat' ? $data['flat_rate_inr'] : NULL,
            'percentage_rate' => $data['payout_type'] === 'percentage' ? $data['percentage_rate'] : NULL,
            'percentage_inr_rate' => $data['payout_type'] === 'percentage' ? $data['percentage_inr_rate'] : NULL,
            'status' => $data['status'],
            'created_by' => $data['created_by']
        );
        
        $this->db->insert($this->table, $vendor_data);
        return $this->db->insert_id();
    }
    
    // Get all vendors with pagination
    public function get_all_vendors($limit = 50, $offset = 0, $filters = array()) {
        $this->db->select('vendors.*, users.first_name as creator_first_name, users.last_name as creator_last_name')
                 ->from($this->table)
                 ->join('users', 'users.id = vendors.created_by');
        
        // Apply filters
        if (!empty($filters['name'])) {
            $this->db->like('vendors.name', $filters['name']);
        }
        
        if (!empty($filters['payout_type'])) {
            $this->db->where('vendors.payout_type', $filters['payout_type']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('vendors.status', $filters['status']);
        }
        
        return $this->db->order_by('vendors.name', 'ASC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result();
    }
    
    // Count all vendors
    public function count_all_vendors($filters = array()) {
        // Apply filters
        if (!empty($filters['name'])) {
            $this->db->like('name', $filters['name']);
        }
        
        if (!empty($filters['payout_type'])) {
            $this->db->where('payout_type', $filters['payout_type']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        
        return $this->db->count_all_results($this->table);
    }
    
    // Get vendor by ID
    public function get_vendor_by_id($id) {
        return $this->db->select('vendors.*, users.first_name as creator_first_name, users.last_name as creator_last_name')
                        ->from($this->table)
                        ->join('users', 'users.id = vendors.created_by')
                        ->where('vendors.id', $id)
                        ->get()
                        ->row();
    }
    
    // Get vendor by name
    public function get_vendor_by_name($name) {
        return $this->db->where('name', $name)->get($this->table)->row();
    }
    
    // Update vendor
    public function update_vendor($id, $data) {
        $vendor_data = array(
            'name' => $data['name'],
            'payout_type' => $data['payout_type'],
            'flat_rate_inr' => $data['payout_type'] === 'flat' ? $data['flat_rate_inr'] : NULL,
            'percentage_rate' => $data['payout_type'] === 'percentage' ? $data['percentage_rate'] : NULL,
            'percentage_inr_rate' => $data['payout_type'] === 'percentage' ? $data['percentage_inr_rate'] : NULL,
            'status' => $data['status']
        );
        
        return $this->db->where('id', $id)->update($this->table, $vendor_data);
    }
    
    // Delete vendor
    public function delete_vendor($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }
    
    // Get active vendors for dropdown
    public function get_active_vendors() {
        return $this->db->where('status', 'active')
                        ->order_by('name', 'ASC')
                        ->get($this->table)
                        ->result();
    }
    
    // Get payout types
    public function get_payout_types() {
        return array('flat', 'percentage');
    }
    
    // Get vendor statuses
    public function get_vendor_statuses() {
        return array('active', 'inactive');
    }
    
    // Calculate vendor payout
    public function calculate_vendor_payout($vendor_id, $amount_usd) {
        $vendor = $this->get_vendor_by_id($vendor_id);
        
        if (!$vendor || $vendor->status !== 'active') {
            return 0;
        }
        
        if ($vendor->payout_type === 'flat') {
            // Flat payout: $1 = flat_rate_inr INR
            return $amount_usd * $vendor->flat_rate_inr;
        } else {
            // Percentage payout: amount * percentage_rate% * percentage_inr_rate
            return ($amount_usd * ($vendor->percentage_rate / 100)) * $vendor->percentage_inr_rate;
        }
    }
    
    // Calculate your system profit
    public function calculate_system_profit($amount_usd) {
        // Your system gets 88% at $1 = 82 INR
        $system_rate = 82.00; // INR per USD
        $system_percentage = 88.00; // 88%
        
        return ($amount_usd * ($system_percentage / 100)) * $system_rate;
    }
    
    // Get vendor summary
    public function get_vendor_summary() {
        $sql = "SELECT 
                    payout_type,
                    COUNT(*) as total_vendors,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_vendors,
                    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_vendors
                FROM vendors 
                GROUP BY payout_type";
        
        return $this->db->query($sql)->result();
    }
    
    // Search vendors
    public function search_vendors($search_term, $limit = 50, $offset = 0) {
        $this->db->select('vendors.*, users.first_name as creator_first_name, users.last_name as creator_last_name')
                 ->from($this->table)
                 ->join('users', 'users.id = vendors.created_by')
                 ->like('vendors.name', $search_term);
        
        return $this->db->order_by('vendors.name', 'ASC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result();
    }
    
    // Count search results
    public function count_search_results($search_term) {
        $this->db->like('name', $search_term);
        return $this->db->count_all_results($this->table);
    }

    // Get all payments for a specific vendor
    public function get_vendor_payments($vendor_id, $limit = 50, $offset = 0, $filters = array()) {
        $this->db->select('vendor_payments.*, users.first_name as creator_first_name, users.last_name as creator_last_name')
                 ->from('vendor_payments')
                 ->join('users', 'users.id = vendor_payments.created_by')
                 ->where('vendor_payments.vendor_id', $vendor_id);
        
        // Apply filters
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
    
    // Count payments for a specific vendor
    public function count_vendor_payments($vendor_id, $filters = array()) {
        $this->db->where('vendor_id', $vendor_id);
        
        // Apply filters
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        
        if (!empty($filters['mode'])) {
            $this->db->where('mode', $filters['mode']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('date >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('date <=', $filters['date_to']);
        }
        
        return $this->db->count_all_results('vendor_payments');
    }
    
    // Get vendor payment summary
    public function get_vendor_payment_summary($vendor_id) {
        $sql = "SELECT 
                    COUNT(*) as total_payments,
                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved_count,
                    SUM(CASE WHEN status = 'Declined' THEN 1 ELSE 0 END) as declined_count,
                    SUM(CASE WHEN status = 'Pending' THEN amount ELSE 0 END) as pending_amount,
                    SUM(CASE WHEN status = 'Approved' THEN amount ELSE 0 END) as approved_amount,
                    SUM(CASE WHEN status = 'Declined' THEN amount ELSE 0 END) as declined_amount,
                    SUM(amount) as total_amount
                FROM vendor_payments 
                WHERE vendor_id = ?";
        
        return $this->db->query($sql, array($vendor_id))->row();
    }
} 