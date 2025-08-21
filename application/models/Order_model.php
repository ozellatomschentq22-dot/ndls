<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'orders';
    }

    public function get_order_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function get_order_details($id, $user_id = null) {
        $this->db->select('orders.*, products.name as product_name, products.strength, products.quantity as product_quantity, users.first_name, users.last_name, users.email')
                 ->from($this->table)
                 ->join('products', 'products.id = orders.product_id')
                 ->join('users', 'users.id = orders.user_id')
                 ->where('orders.id', $id);
        
        if ($user_id) {
            $this->db->where('orders.user_id', $user_id);
        }
        
        return $this->db->get()->row();
    }

    public function get_orders_by_user($user_id, $limit = null) {
        $this->db->select('orders.*, products.name as product_name, products.strength, products.quantity as product_quantity')
                 ->from($this->table)
                 ->join('products', 'products.id = orders.product_id')
                 ->where('orders.user_id', $user_id)
                 ->order_by('orders.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    public function get_orders_by_user_id($user_id, $limit = null) {
        return $this->get_orders_by_user($user_id, $limit);
    }

    public function get_all_orders($status = null, $limit = null) {
        $this->db->select('orders.*, products.name as product_name, products.brand, products.strength, products.quantity as product_quantity, users.first_name, users.last_name, users.email')
                 ->from($this->table)
                 ->join('products', 'products.id = orders.product_id')
                 ->join('users', 'users.id = orders.user_id')
                 ->order_by('orders.created_at', 'DESC');
        
        if ($status) {
            $this->db->where('orders.status', $status);
        }
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    public function create_order($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_order($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function cancel_order($id) {
        return $this->db->where('id', $id)->update($this->table, ['status' => 'cancelled']);
    }

    public function delete_order($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function get_orders_by_status($status) {
        return $this->db->where('status', $status)
                        ->order_by('created_at', 'DESC')
                        ->get($this->table)->result();
    }

    public function count_orders($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results($this->table);
    }

    public function count_orders_by_user($user_id) {
        return $this->db->where('user_id', $user_id)->count_all_results($this->table);
    }

    public function get_order_summary() {
        $this->db->select('COUNT(*) as total_orders')
                 ->select('COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_orders')
                 ->select('COUNT(CASE WHEN status = "processing" THEN 1 END) as processing_orders')
                 ->select('COUNT(CASE WHEN status = "shipped" THEN 1 END) as shipped_orders')
                 ->select('COUNT(CASE WHEN status = "delivered" THEN 1 END) as delivered_orders')
                 ->select('COUNT(CASE WHEN status = "cancelled" THEN 1 END) as cancelled_orders')
                 ->from($this->table);
        return $this->db->get()->row();
    }

    public function search_orders($search) {
        $this->db->select('orders.*, products.name as product_name, users.first_name, users.last_name')
                 ->from($this->table)
                 ->join('products', 'products.id = orders.product_id')
                 ->join('users', 'users.id = orders.user_id')
                 ->group_start()
                 ->like('orders.order_number', $search)
                 ->or_like('users.first_name', $search)
                 ->or_like('users.last_name', $search)
                 ->or_like('products.name', $search)
                 ->group_end()
                 ->order_by('orders.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Tracking Methods
    public function update_tracking($order_id, $tracking_data) {
        $data = [
            'tracking_number' => $tracking_data['tracking_number'],
            'tracking_url' => $tracking_data['tracking_url'],
            'carrier' => $tracking_data['carrier'],
            'shipped_at' => isset($tracking_data['shipped_at']) ? $tracking_data['shipped_at'] : date('Y-m-d H:i:s'),
            'status' => 'shipped'
        ];
        
        return $this->db->where('id', $order_id)->update($this->table, $data);
    }

    public function mark_as_delivered($order_id) {
        $data = [
            'status' => 'delivered',
            'delivered_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->where('id', $order_id)->update($this->table, $data);
    }

    public function get_tracking_updates($order_id) {
        return $this->db->where('order_id', $order_id)
                        ->order_by('tracking_date', 'DESC')
                        ->order_by('created_at', 'DESC')
                        ->get('order_tracking_updates')
                        ->result();
    }

    public function add_tracking_update($order_id, $update_data) {
        $data = [
            'order_id' => $order_id,
            'status' => $update_data['status'],
            'location' => isset($update_data['location']) ? $update_data['location'] : null,
            'description' => isset($update_data['description']) ? $update_data['description'] : null,
            'tracking_date' => isset($update_data['tracking_date']) ? $update_data['tracking_date'] : date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert('order_tracking_updates', $data);
    }

    public function get_orders_with_tracking($user_id = null) {
        $this->db->select('orders.*, products.name as product_name, users.first_name, users.last_name, users.email')
                 ->from($this->table)
                 ->join('products', 'products.id = orders.product_id')
                 ->join('users', 'users.id = orders.user_id')
                 ->where('orders.tracking_number IS NOT NULL')
                 ->order_by('orders.created_at', 'DESC');
        
        if ($user_id) {
            $this->db->where('orders.user_id', $user_id);
        }
        
        return $this->db->get()->result();
    }

    public function get_order_with_tracking($order_id, $user_id = null) {
        $this->db->select('orders.*, products.name as product_name, users.first_name, users.last_name, users.email')
                 ->from($this->table)
                 ->join('products', 'products.id = orders.product_id')
                 ->join('users', 'users.id = orders.user_id')
                 ->where('orders.id', $order_id);
        
        if ($user_id) {
            $this->db->where('orders.user_id', $user_id);
        }
        
        $order = $this->db->get()->row();
        
        if ($order) {
            $order->tracking_updates = $this->get_tracking_updates($order_id);
        }
        
        return $order;
    }

    // Count all orders
    public function count_all_orders() {
        return $this->db->count_all_results($this->table);
    }

    // Get recent orders
    public function get_recent_orders($limit = 5) {
        return $this->db->select('orders.*, products.name as product_name, users.first_name, users.last_name')
                        ->from($this->table)
                        ->join('products', 'products.id = orders.product_id')
                        ->join('users', 'users.id = orders.user_id')
                        ->order_by('orders.created_at', 'DESC')
                        ->limit($limit)
                        ->get()
                        ->result();
    }
    
    // Get orders with pagination
    public function get_orders($limit = 20, $offset = 0, $status = null) {
        $this->db->select('orders.*, products.name as product_name, products.brand, products.strength, products.quantity as product_quantity, users.first_name, users.last_name, users.email')
                 ->from($this->table)
                 ->join('products', 'products.id = orders.product_id')
                 ->join('users', 'users.id = orders.user_id')
                 ->order_by('orders.created_at', 'DESC')
                 ->limit($limit, $offset);
        
        if ($status) {
            $this->db->where('orders.status', $status);
        }
        
        return $this->db->get()->result();
    }
} 