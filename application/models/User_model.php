<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'users';
    }

    public function get_user_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function get_user_by_username($username) {
        return $this->db->where('username', $username)->get($this->table)->row();
    }

    public function get_user_by_email($email) {
        return $this->db->where('email', $email)->get($this->table)->row();
    }

    public function create_user($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_user($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_user($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function get_all_users($role = null, $status = null) {
        if ($role) {
            $this->db->where('role', $role);
        }
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->order_by('created_at', 'DESC')->get($this->table)->result();
    }

    public function get_users_by_role($role) {
        if (is_array($role)) {
            $this->db->where_in('role', $role);
        } else {
            $this->db->where('role', $role);
        }
        return $this->db->where('status', 'active')->get($this->table)->result();
    }

    public function get_all_users_by_role($role) {
        if (is_array($role)) {
            $this->db->where_in('role', $role);
        } else {
            $this->db->where('role', $role);
        }
        return $this->db->order_by('created_at', 'DESC')->get($this->table)->result();
    }

    public function update_reset_token($user_id, $token) {
        return $this->db->where('id', $user_id)->update($this->table, ['reset_token' => $token]);
    }

    public function get_user_by_reset_token($token) {
        return $this->db->where('reset_token', $token)->get($this->table)->row();
    }

    public function count_users_by_role($role) {
        return $this->db->where('role', $role)->count_all_results($this->table);
    }

    public function search_users($search) {
        $this->db->group_start();
        $this->db->like('username', $search);
        $this->db->or_like('email', $search);
        $this->db->or_like('first_name', $search);
        $this->db->or_like('last_name', $search);
        $this->db->group_end();
        return $this->db->get($this->table)->result();
    }

    public function get_owner_by_id($id) {
        return $this->db->where('id', $id)
                        ->where_in('role', ['admin', 'staff'])
                        ->where('status', 'active')
                        ->get($this->table)
                        ->row();
    }

    public function get_owner_name_by_id($id) {
        $user = $this->get_owner_by_id($id);
        return $user ? $user->first_name . ' ' . $user->last_name : null;
    }

    // Get recent customers
    public function get_recent_customers($limit = 5) {
        return $this->db->where('role', 'customer')
                        ->order_by('created_at', 'DESC')
                        ->limit($limit)
                        ->get($this->table)
                        ->result();
    }
    
    // Count all users
    public function count_all_users($role = null) {
        if ($role) {
            $this->db->where('role', $role);
        }
        return $this->db->count_all_results($this->table);
    }
    
    // Get users with pagination
    public function get_users($limit = 20, $offset = 0, $role = null) {
        if ($role) {
            $this->db->where('role', $role);
        }
        return $this->db->order_by('created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get($this->table)
                        ->result();
    }
    
    // Get all customers (for dropdowns)
    public function get_all_customers() {
        return $this->db->where('role', 'customer')
                        ->where('status', 'active')
                        ->order_by('first_name', 'ASC')
                        ->get($this->table)
                        ->result();
    }
} 