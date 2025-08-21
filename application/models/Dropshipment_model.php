<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dropshipment_model extends CI_Model {
    
    private $table = 'dropshipment_orders';
    private $centers_table = 'centers';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Generate unique order number
    public function generate_order_number() {
        $prefix = 'DS';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 4));
        return $prefix . $date . $random;
    }
    
    // Add new drop shipment order
    public function add_order($data) {
        $order_data = array(
            'order_number' => $this->generate_order_number(),
            'customer_name' => $data['customer_name'],
            'customer_address' => $data['customer_address'],
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'center' => $data['center'],
            'status' => 'Pending',
            'notes' => $data['notes'] ?? NULL,
            'created_by' => $data['created_by']
        );
        
        $this->db->insert($this->table, $order_data);
        return $this->db->insert_id();
    }
    
    // Get all orders with pagination and filters
    public function get_all_orders($limit = 50, $offset = 0, $filters = array()) {
        $this->db->select('dropshipment_orders.*, 
                          creator.first_name as creator_first_name, creator.last_name as creator_last_name,
                          processor.first_name as processor_first_name, processor.last_name as processor_last_name')
                 ->from($this->table)
                 ->join('users as creator', 'creator.id = dropshipment_orders.created_by', 'left')
                 ->join('users as processor', 'processor.id = dropshipment_orders.processed_by', 'left');
        
        // Apply filters
        if (!empty($filters['status'])) {
            $this->db->where('dropshipment_orders.status', $filters['status']);
        }
        
        if (!empty($filters['center'])) {
            $this->db->where('dropshipment_orders.center', $filters['center']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('dropshipment_orders.created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('dropshipment_orders.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('dropshipment_orders.order_number', $filters['search']);
            $this->db->or_like('dropshipment_orders.customer_name', $filters['search']);
            $this->db->or_like('dropshipment_orders.product_name', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->order_by('dropshipment_orders.created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result();
    }
    
    // Count all orders with filters
    public function count_all_orders($filters = array()) {
        // Apply filters
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
        
        if (!empty($filters['center'])) {
            $this->db->where('center', $filters['center']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('order_number', $filters['search']);
            $this->db->or_like('customer_name', $filters['search']);
            $this->db->or_like('product_name', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results($this->table);
    }
    
    // Get order by ID
    public function get_order_by_id($id) {
        return $this->db->select('dropshipment_orders.*, 
                                 creator.first_name as creator_first_name, creator.last_name as creator_last_name,
                                 processor.first_name as processor_first_name, processor.last_name as processor_last_name')
                        ->from($this->table)
                        ->join('users as creator', 'creator.id = dropshipment_orders.created_by', 'left')
                        ->join('users as processor', 'processor.id = dropshipment_orders.processed_by', 'left')
                        ->where('dropshipment_orders.id', $id)
                        ->get()
                        ->row();
    }
    
    // Update order
    public function update_order($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }
    
    // Process order (admin only)
    public function process_order($id, $price, $processed_by) {
        $data = array(
            'status' => 'Processed',
            'price' => $price,
            'processed_by' => $processed_by,
            'processed_at' => date('Y-m-d H:i:s')
        );
        return $this->db->where('id', $id)->update($this->table, $data);
    }
    
    // Update tracking info
    public function update_tracking($id, $tracking_data) {
        $data = array(
            'tracking_number' => $tracking_data['tracking_number'],
            'tracking_carrier' => $tracking_data['tracking_carrier'],
            'tracking_url' => $tracking_data['tracking_url']
        );
        return $this->db->where('id', $id)->update($this->table, $data);
    }
    
    // Update order status
    public function update_status($id, $status) {
        return $this->db->where('id', $id)->update($this->table, array('status' => $status));
    }
    
    // Delete order
    public function delete_order($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }
    
    // Get order summary
    public function get_order_summary($filters = array()) {
        $this->db->select('COUNT(*) as total_orders,
                          SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending_count,
                          SUM(CASE WHEN status = "Processed" THEN 1 ELSE 0 END) as processed_count,
                          SUM(CASE WHEN status = "Shipped" THEN 1 ELSE 0 END) as shipped_count,
                          SUM(CASE WHEN status = "Delivered" THEN 1 ELSE 0 END) as delivered_count,
                          SUM(CASE WHEN status = "Cancelled" THEN 1 ELSE 0 END) as cancelled_count,
                          SUM(CASE WHEN status = "Processed" THEN price ELSE 0 END) as total_processed_amount,
                          SUM(CASE WHEN status IN ("Processed", "Shipped", "Delivered") THEN price ELSE 0 END) as total_amount')
                 ->from($this->table);
        
        // Apply filters
        if (!empty($filters['center'])) {
            $this->db->where('center', $filters['center']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        return $this->db->get()->row();
    }
    
    // Get center summary
    public function get_center_summary($filters = array()) {
        $this->db->select('center,
                          COUNT(*) as total_orders,
                          SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending_count,
                          SUM(CASE WHEN status = "Processed" THEN 1 ELSE 0 END) as processed_count,
                          SUM(CASE WHEN status = "Shipped" THEN 1 ELSE 0 END) as shipped_count,
                          SUM(CASE WHEN status = "Delivered" THEN 1 ELSE 0 END) as delivered_count,
                          SUM(CASE WHEN status = "Cancelled" THEN 1 ELSE 0 END) as cancelled_count,
                          SUM(CASE WHEN status = "Processed" THEN price ELSE 0 END) as total_processed_amount,
                          SUM(CASE WHEN status IN ("Processed", "Shipped", "Delivered") THEN price ELSE 0 END) as total_amount')
                 ->from($this->table)
                 ->group_by('center');
        
        // Apply filters
        if (!empty($filters['date_from'])) {
            $this->db->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        return $this->db->order_by('total_amount', 'DESC')->get()->result();
    }
    
    // Get all centers
    public function get_all_centers($show_all = false) {
        if (!$show_all) {
            // For non-admin users, only show active centers
            $this->db->where('status', 'active');
        }
        // For admin users, show all centers (active and inactive)
        return $this->db->order_by('name', 'ASC')
                        ->get($this->centers_table)
                        ->result();
    }
    
    // Get order statuses
    public function get_order_statuses() {
        return array('Pending', 'Processed', 'Shipped', 'Delivered', 'Cancelled');
    }
    
    // Get tracking carriers
    public function get_tracking_carriers() {
        return array(
            'FedEx' => 'FedEx',
            'UPS' => 'UPS',
            'USPS' => 'USPS',
            'DHL' => 'DHL',
            'Amazon Logistics' => 'Amazon Logistics',
            'Other' => 'Other'
        );
    }
    
    // Center management methods
    public function get_center_by_id($id) {
        return $this->db->where('id', $id)->get($this->centers_table)->row();
    }
    
    public function add_center($data) {
        return $this->db->insert($this->centers_table, $data);
    }
    
    public function update_center($id, $data) {
        return $this->db->where('id', $id)->update($this->centers_table, $data);
    }
    
    public function delete_center($id) {
        return $this->db->where('id', $id)->delete($this->centers_table);
    }
    
    public function count_orders_by_center($center_name) {
        return $this->db->where('center', $center_name)->count_all_results($this->table);
    }
    
    public function get_orders_by_center($center_name) {
        return $this->db->select('dropshipment_orders.*, 
                                 creator.first_name as creator_first_name, creator.last_name as creator_last_name,
                                 processor.first_name as processor_first_name, processor.last_name as processor_last_name')
                        ->from($this->table)
                        ->join('users as creator', 'creator.id = dropshipment_orders.created_by', 'left')
                        ->join('users as processor', 'processor.id = dropshipment_orders.processed_by', 'left')
                        ->where('dropshipment_orders.center', $center_name)
                        ->order_by('dropshipment_orders.created_at', 'DESC')
                        ->get()
                        ->result();
    }
} 