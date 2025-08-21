<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Center_payments_model extends CI_Model {
    
    private $table = 'center_payments';
    private $orders_table = 'dropshipment_orders';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Add new bulk payment record
    public function add_payment($data) {
        $payment_data = array(
            'center_name' => $data['center_name'],
            'amount_paid' => $data['amount_paid'],
            'payment_date' => $data['payment_date'],
            'payment_method' => $data['payment_method'],
            'reference_number' => $data['reference_number'] ?? NULL,
            'notes' => $data['notes'] ?? NULL,
            'status' => $data['status'] ?? 'Pending',
            'created_by' => $data['created_by']
        );
        
        $this->db->insert($this->table, $payment_data);
        return $this->db->insert_id();
    }
    
    // Get all payments with pagination and filters
    public function get_all_payments($limit = 50, $offset = 0, $filters = array()) {
        $this->db->select('center_payments.*, 
                          creator.first_name as creator_first_name, creator.last_name as creator_last_name')
                 ->from($this->table)
                 ->join('users as creator', 'creator.id = center_payments.created_by', 'left');
        
        // Apply filters
        if (!empty($filters['center_name'])) {
            $this->db->where('center_payments.center_name', $filters['center_name']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('center_payments.status', $filters['status']);
        }
        
        if (!empty($filters['payment_method'])) {
            $this->db->where('center_payments.payment_method', $filters['payment_method']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('center_payments.payment_date >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('center_payments.payment_date <=', $filters['date_to']);
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('center_payments.center_name', $filters['search']);
            $this->db->or_like('center_payments.reference_number', $filters['search']);
            $this->db->or_like('center_payments.notes', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->order_by('center_payments.payment_date', 'DESC')
                        ->order_by('center_payments.created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result();
    }
    
    // Count all payments with filters
    public function count_all_payments($filters = array()) {
        $this->db->from($this->table);
        
        // Apply filters
        if (!empty($filters['center_name'])) {
            $this->db->where('center_name', $filters['center_name']);
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        
        if (!empty($filters['payment_method'])) {
            $this->db->where('payment_method', $filters['payment_method']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('payment_date >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('payment_date <=', $filters['date_to']);
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('center_name', $filters['search']);
            $this->db->or_like('reference_number', $filters['search']);
            $this->db->or_like('notes', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }
    
    // Get payment by ID
    public function get_payment_by_id($id) {
        return $this->db->select('center_payments.*, 
                                 creator.first_name as creator_first_name, creator.last_name as creator_last_name')
                        ->from($this->table)
                        ->join('users as creator', 'creator.id = center_payments.created_by', 'left')
                        ->where('center_payments.id', $id)
                        ->get()
                        ->row();
    }
    
    // Get payments by center
    public function get_payments_by_center($center_name) {
        return $this->db->select('center_payments.*, 
                                 creator.first_name as creator_first_name, creator.last_name as creator_last_name')
                        ->from($this->table)
                        ->join('users as creator', 'creator.id = center_payments.created_by', 'left')
                        ->where('center_payments.center_name', $center_name)
                        ->order_by('center_payments.payment_date', 'DESC')
                        ->get()
                        ->result();
    }
    
    // Update payment
    public function update_payment($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }
    
    // Delete payment
    public function delete_payment($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }
    
    // Get center outstanding amount (total order value - total payments)
    public function get_center_outstanding($center_name) {
        // Get total order value for the center
        $total_orders = $this->db->select('SUM(price) as total_value, COUNT(*) as total_orders')
                                 ->where('center', $center_name)
                                 ->where_in('status', array('Processed', 'Shipped', 'Delivered'))
                                 ->where('price IS NOT NULL')
                                 ->get($this->orders_table)
                                 ->row();
        
        // Get total payments from the center
        $total_payments = $this->db->select('SUM(amount_paid) as total_paid')
                                   ->where('center_name', $center_name)
                                   ->where('status', 'Completed')
                                   ->get($this->table)
                                   ->row();
        
        $total_value = $total_orders->total_value ?? 0;
        $total_paid = $total_payments->total_paid ?? 0;
        $outstanding = $total_value - $total_paid;
        
        return array(
            'total_orders' => $total_orders->total_orders ?? 0,
            'total_value' => $total_value,
            'total_paid' => $total_paid,
            'outstanding' => $outstanding
        );
    }
    
    // Get payment summary
    public function get_payment_summary($filters = array()) {
        $this->db->select('COUNT(*) as total_payments,
                          SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending_count,
                          SUM(CASE WHEN status = "Completed" THEN 1 ELSE 0 END) as completed_count,
                          SUM(CASE WHEN status = "Failed" THEN 1 ELSE 0 END) as failed_count,
                          SUM(CASE WHEN status = "Refunded" THEN 1 ELSE 0 END) as refunded_count,
                          SUM(CASE WHEN status = "Completed" THEN amount_paid ELSE 0 END) as total_amount_paid,
                          SUM(amount_paid) as total_amount')
                 ->from($this->table);
        
        // Apply filters
        if (!empty($filters['center_name'])) {
            $this->db->where('center_name', $filters['center_name']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('payment_date >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('payment_date <=', $filters['date_to']);
        }
        
        return $this->db->get()->row();
    }
    
    // Get center payment summary with outstanding amounts
    public function get_center_payment_summary($filters = array()) {
        // Get all centers
        $centers = $this->db->select('name')->from('centers')->where('status', 'active')->get()->result();
        
        $summary = array();
        foreach ($centers as $center) {
            $outstanding = $this->get_center_outstanding($center->name);
            
            // Get payment stats for this center
            $payment_stats = $this->db->select('COUNT(*) as total_payments,
                                              SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending_count,
                                              SUM(CASE WHEN status = "Completed" THEN 1 ELSE 0 END) as completed_count,
                                              SUM(CASE WHEN status = "Failed" THEN 1 ELSE 0 END) as failed_count,
                                              SUM(CASE WHEN status = "Completed" THEN amount_paid ELSE 0 END) as total_amount_paid,
                                              SUM(amount_paid) as total_amount')
                                   ->where('center_name', $center->name)
                                   ->get($this->table)
                                   ->row();
            
            $summary[] = (object) array(
                'center_name' => $center->name,
                'total_orders' => $outstanding['total_orders'],
                'total_value' => $outstanding['total_value'],
                'total_paid' => $outstanding['total_paid'],
                'outstanding' => $outstanding['outstanding'],
                'total_payments' => $payment_stats->total_payments ?? 0,
                'pending_count' => $payment_stats->pending_count ?? 0,
                'completed_count' => $payment_stats->completed_count ?? 0,
                'failed_count' => $payment_stats->failed_count ?? 0,
                'total_amount_paid' => $payment_stats->total_amount_paid ?? 0
            );
        }
        
        // Sort by outstanding amount (highest first)
        usort($summary, function($a, $b) {
            return $b->outstanding <=> $a->outstanding;
        });
        
        return $summary;
    }
    
    // Get payment methods
    public function get_payment_methods() {
        return array(
            'Cash' => 'Cash',
            'Bank Transfer' => 'Bank Transfer',
            'Check' => 'Check',
            'Online Payment' => 'Online Payment',
            'Other' => 'Other'
        );
    }
    
    // Get payment statuses
    public function get_payment_statuses() {
        return array(
            'Pending' => 'Pending',
            'Completed' => 'Completed',
            'Failed' => 'Failed',
            'Refunded' => 'Refunded'
        );
    }
} 