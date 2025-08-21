<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_address_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'customer_addresses';
    }

    public function get_addresses_by_user_id($user_id) {
        return $this->db->where('user_id', $user_id)
                        ->order_by('is_default', 'DESC')
                        ->order_by('created_at', 'ASC')
                        ->get($this->table)->result();
    }

    public function get_address_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function get_default_address($user_id) {
        return $this->db->where('user_id', $user_id)
                        ->where('is_default', 1)
                        ->get($this->table)->row();
    }

    public function create_address($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_address($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_address($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function set_default_address($user_id, $address_id) {
        // First, remove default from all addresses for this user
        $this->db->where('user_id', $user_id)->update($this->table, ['is_default' => 0]);
        
        // Then set the specified address as default
        return $this->db->where('id', $address_id)->update($this->table, ['is_default' => 1]);
    }

    public function format_address($address) {
        $formatted = $address->full_name . "\n";
        $formatted .= $address->address_line1;
        
        if ($address->address_line2) {
            $formatted .= "\n" . $address->address_line2;
        }
        
        $formatted .= "\n" . $address->city . ", " . $address->state . " " . $address->postal_code;
        $formatted .= "\n" . $address->country;
        
        if ($address->phone) {
            $formatted .= "\nPhone: " . $address->phone;
        }
        
        return $formatted;
    }

    public function count_addresses($user_id) {
        return $this->db->where('user_id', $user_id)->count_all_results($this->table);
    }
} 