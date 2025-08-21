<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'products';
    }

    public function get_product_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function get_product_by_name($name) {
        return $this->db->where('name', $name)->get($this->table)->row();
    }

    public function get_active_products() {
        return $this->db->where('status', 'active')
                        ->order_by('name', 'ASC')
                        ->order_by('quantity', 'ASC')
                        ->get($this->table)->result();
    }

    public function get_all_products($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->order_by('name', 'ASC')->get($this->table)->result();
    }

    public function create_product($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_product($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_product($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function search_products($search) {
        $this->db->group_start();
        $this->db->like('name', $search);
        $this->db->or_like('brand', $search);
        $this->db->or_like('strength', $search);
        $this->db->group_end();
        $this->db->where('status', 'active');
        return $this->db->get($this->table)->result();
    }

    public function get_products_by_quantity($quantity) {
        return $this->db->where('quantity', $quantity)
                        ->where('status', 'active')
                        ->get($this->table)->result();
    }

    public function count_products($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results($this->table);
    }

    // Customer-specific pricing methods
    public function get_customer_price($product_id, $customer_id) {
        // First check for custom price
        $custom_price = $this->db->where('product_id', $product_id)
                                 ->where('customer_id', $customer_id)
                                 ->get('customer_prices')
                                 ->row();
        
        if ($custom_price) {
            return $custom_price->custom_price;
        }
        
        // If no custom price, return the original product price
        $product = $this->get_product_by_id($product_id);
        return $product ? $product->price : 0;
    }

    public function get_products_with_customer_prices($customer_id) {
        // Get all active products
        $products = $this->get_active_products();
        
        foreach ($products as $product) {
            $product->customer_price = $this->get_customer_price($product->id, $customer_id);
            $product->original_price = $product->price;
            $product->has_discount = ($product->customer_price < $product->price);
            $product->discount_amount = $product->price - $product->customer_price;
            
            // Ensure all required fields are set
            if (!isset($product->quantity) || $product->quantity <= 0) {
                $product->quantity = 1; // Default quantity if not set
            }
        }
        
        return $products;
    }

    public function set_customer_price($customer_id, $product_id, $price) {
        $data = array(
            'customer_id' => $customer_id,
            'product_id' => $product_id,
            'custom_price' => $price
        );
        
        // Check if price already exists
        $existing = $this->db->where('customer_id', $customer_id)
                             ->where('product_id', $product_id)
                             ->get('customer_prices')
                             ->row();
        
        if ($existing) {
            // Update existing price
            return $this->db->where('id', $existing->id)
                           ->update('customer_prices', $data);
        } else {
            // Insert new price
            return $this->db->insert('customer_prices', $data);
        }
    }

    public function remove_customer_price($customer_id, $product_id) {
        return $this->db->where('customer_id', $customer_id)
                       ->where('product_id', $product_id)
                       ->delete('customer_prices');
    }

    public function get_customer_prices($customer_id) {
        return $this->db->select('cp.*, p.name as product_name, p.price as original_price')
                       ->from('customer_prices cp')
                       ->join('products p', 'p.id = cp.product_id')
                       ->where('cp.customer_id', $customer_id)
                       ->get()
                       ->result();
    }

    public function get_products_grouped_with_prices($customer_id) {
        $products = $this->get_products_with_customer_prices($customer_id);
        
        // Group products by name, strength, and brand
        $grouped = array();
        foreach ($products as $product) {
            $key = $product->name . '_' . $product->strength . '_' . $product->brand;
            if (!isset($grouped[$key])) {
                $grouped[$key] = array(
                    'name' => $product->name,
                    'strength' => $product->strength,
                    'brand' => $product->brand,
                    'variants' => array(),
                    'has_discount' => false
                );
            }
            
            // Add variant to group
            $grouped[$key]['variants'][] = $product;
            
            // Check if any variant has discount
            if ($product->has_discount) {
                $grouped[$key]['has_discount'] = true;
            }
        }
        
        return $grouped;
    }

    // Count all products
    public function count_all_products() {
        return $this->db->count_all_results($this->table);
    }
    
    // Get products with pagination
    public function get_products($limit = 20, $offset = 0, $status = null) {
        $this->db->select('products.*, 
                          (SELECT COUNT(*) FROM product_variants WHERE product_variants.product_id = products.id) as variant_count')
                 ->from($this->table)
                 ->order_by('products.created_at', 'DESC')
                 ->limit($limit, $offset);
        
        if ($status) {
            $this->db->where('products.status', $status);
        }
        
        return $this->db->get()->result();
    }
} 