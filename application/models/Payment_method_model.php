<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_method_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'payment_methods';
    }

    public function get_all_methods($active_only = true) {
        $this->db->order_by('sort_order', 'ASC');
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->table)->result();
    }

    public function get_method_by_key($method_key) {
        return $this->db->where('method_key', $method_key)
                        ->where('is_active', 1)
                        ->get($this->table)->row();
    }

    public function get_method_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function create_method($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_method($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_method($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function toggle_status($id) {
        $method = $this->get_method_by_id($id);
        if ($method) {
            $new_status = $method->is_active ? 0 : 1;
            return $this->update_method($id, array('is_active' => $new_status));
        }
        return false;
    }

    public function update_sort_order($id, $sort_order) {
        return $this->update_method($id, array('sort_order' => $sort_order));
    }

    public function get_methods_for_recharge() {
        $methods = $this->get_all_methods(true);
        $formatted_methods = array();
        
        foreach ($methods as $method) {
            $formatted_methods[$method->method_key] = array(
                'title' => $method->title,
                'icon' => $method->icon,
                'instructions' => json_decode($method->instructions, true),
                'additional_info' => $method->additional_info
            );
        }
        
        return $formatted_methods;
    }

    public function count_methods($active_only = false) {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->count_all_results($this->table);
    }

    public function search_methods($search) {
        $this->db->like('display_name', $search)
                 ->or_like('method_key', $search)
                 ->or_like('title', $search);
        return $this->db->get($this->table)->result();
    }
} 