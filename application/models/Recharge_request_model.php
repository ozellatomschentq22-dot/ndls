<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recharge_request_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'recharge_requests';
    }

    public function get_request_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function get_request_details($id) {
        $this->db->select('recharge_requests.*, users.first_name, users.last_name, users.email, approved.first_name as approved_first_name, approved.last_name as approved_last_name')
                 ->from($this->table)
                 ->join('users', 'users.id = recharge_requests.user_id')
                 ->join('users as approved', 'approved.id = recharge_requests.approved_by', 'left')
                 ->where('recharge_requests.id', $id);
        return $this->db->get()->row();
    }

    public function get_requests_by_user($user_id, $status = null) {
        $this->db->select('recharge_requests.*, users.first_name, users.last_name')
                 ->from($this->table)
                 ->join('users', 'users.id = recharge_requests.user_id')
                 ->where('recharge_requests.user_id', $user_id)
                 ->order_by('recharge_requests.created_at', 'DESC');
        
        if ($status) {
            $this->db->where('recharge_requests.status', $status);
        }
        
        return $this->db->get()->result();
    }

    public function get_pending_requests($user_id = null) {
        $this->db->select('recharge_requests.*, users.first_name, users.last_name, users.email')
                 ->from($this->table)
                 ->join('users', 'users.id = recharge_requests.user_id')
                 ->where('recharge_requests.status', 'pending')
                 ->order_by('recharge_requests.created_at', 'ASC');
        
        if ($user_id) {
            $this->db->where('recharge_requests.user_id', $user_id);
        }
        
        return $this->db->get()->result();
    }

    public function get_pending_requests_by_user($user_id) {
        return $this->get_pending_requests($user_id);
    }

    public function get_all_requests($status = null, $limit = null) {
        $this->db->select('recharge_requests.*, users.first_name, users.last_name, users.email, approved.first_name as approved_first_name, approved.last_name as approved_last_name')
                 ->from($this->table)
                 ->join('users', 'users.id = recharge_requests.user_id')
                 ->join('users as approved', 'approved.id = recharge_requests.approved_by', 'left')
                 ->order_by('recharge_requests.created_at', 'DESC');
        
        if ($status) {
            $this->db->where('recharge_requests.status', $status);
        }
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    public function create_request($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_request($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function approve_request($id, $approved_by) {
        $data = array(
            'status' => 'approved',
            'approved_by' => $approved_by,
            'approved_at' => date('Y-m-d H:i:s')
        );
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function reject_request($id, $approved_by, $notes = '') {
        $data = array(
            'status' => 'rejected',
            'approved_by' => $approved_by,
            'approved_at' => date('Y-m-d H:i:s'),
            'notes' => $notes
        );
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_request($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function count_requests($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results($this->table);
    }

    public function get_request_summary() {
        $this->db->select('COUNT(*) as total_requests')
                 ->select('COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_requests')
                 ->select('COUNT(CASE WHEN status = "approved" THEN 1 END) as approved_requests')
                 ->select('COUNT(CASE WHEN status = "rejected" THEN 1 END) as rejected_requests')
                 ->select('SUM(CASE WHEN status = "approved" THEN amount ELSE 0 END) as total_approved_amount')
                 ->from($this->table);
        return $this->db->get()->row();
    }

    public function search_requests($search) {
        $this->db->select('recharge_requests.*, users.first_name, users.last_name')
                 ->from($this->table)
                 ->join('users', 'users.id = recharge_requests.user_id')
                 ->group_start()
                 ->like('recharge_requests.transaction_id', $search)
                 ->or_like('users.first_name', $search)
                 ->or_like('users.last_name', $search)
                 ->group_end()
                 ->order_by('recharge_requests.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Count all requests
    public function count_all_requests() {
        return $this->db->count_all_results($this->table);
    }

    // Count requests by status
    public function count_requests_by_status($status) {
        return $this->db->where('status', $status)->count_all_results($this->table);
    }
    
    // Get requests with pagination
    public function get_requests($limit = 20, $offset = 0, $status = null) {
        $this->db->select('recharge_requests.*, users.first_name, users.last_name, users.email, approved.first_name as approved_first_name, approved.last_name as approved_last_name')
                 ->from($this->table)
                 ->join('users', 'users.id = recharge_requests.user_id')
                 ->join('users as approved', 'approved.id = recharge_requests.approved_by', 'left')
                 ->order_by('recharge_requests.created_at', 'DESC')
                 ->limit($limit, $offset);
        
        if ($status) {
            $this->db->where('recharge_requests.status', $status);
        }
        
        return $this->db->get()->result();
    }
} 