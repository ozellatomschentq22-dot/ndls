<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support_ticket_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tickets_table = 'support_tickets';
        $this->replies_table = 'ticket_replies';
    }

    public function get_ticket_by_id($id) {
        return $this->db->where('id', $id)->get($this->tickets_table)->row();
    }

    public function get_ticket_details($id, $user_id = null) {
        $this->db->select('support_tickets.*, users.first_name, users.last_name, users.email, assigned.first_name as assigned_first_name, assigned.last_name as assigned_last_name')
                 ->from($this->tickets_table)
                 ->join('users', 'users.id = support_tickets.user_id')
                 ->join('users as assigned', 'assigned.id = support_tickets.assigned_to', 'left')
                 ->where('support_tickets.id', $id);
        
        if ($user_id) {
            $this->db->where('support_tickets.user_id', $user_id);
        }
        
        return $this->db->get()->row();
    }

    public function get_tickets_by_user($user_id, $limit = null) {
        $this->db->select('support_tickets.*, users.first_name, users.last_name, users.email, 
                          (SELECT COUNT(*) FROM ticket_replies WHERE ticket_replies.ticket_id = support_tickets.id) as reply_count')
                 ->from($this->tickets_table)
                 ->join('users', 'users.id = support_tickets.user_id')
                 ->where('support_tickets.user_id', $user_id)
                 ->order_by('support_tickets.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    public function get_all_tickets($status = null, $priority = null, $limit = null) {
        $this->db->select('support_tickets.*, users.first_name, users.last_name, users.email, 
                          assigned.first_name as assigned_first_name, assigned.last_name as assigned_last_name,
                          (SELECT COUNT(*) FROM ticket_replies WHERE ticket_replies.ticket_id = support_tickets.id) as reply_count')
                 ->from($this->tickets_table)
                 ->join('users', 'users.id = support_tickets.user_id')
                 ->join('users as assigned', 'assigned.id = support_tickets.assigned_to', 'left')
                 ->order_by('support_tickets.created_at', 'DESC');
        
        if ($status) {
            $this->db->where('support_tickets.status', $status);
        }
        
        if ($priority) {
            $this->db->where('support_tickets.priority', $priority);
        }
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    public function create_ticket($data) {
        $this->db->insert($this->tickets_table, $data);
        return $this->db->insert_id();
    }

    public function update_ticket($id, $data) {
        return $this->db->where('id', $id)->update($this->tickets_table, $data);
    }

    public function delete_ticket($id) {
        return $this->db->where('id', $id)->delete($this->tickets_table);
    }

    public function get_ticket_replies($ticket_id) {
        $this->db->select('ticket_replies.*, users.first_name, users.last_name, users.role')
                 ->from($this->replies_table)
                 ->join('users', 'users.id = ticket_replies.user_id')
                 ->where('ticket_replies.ticket_id', $ticket_id)
                 ->order_by('ticket_replies.created_at', 'ASC');
        return $this->db->get()->result();
    }

    public function add_reply($data) {
        $this->db->insert($this->replies_table, $data);
        return $this->db->insert_id();
    }

    public function assign_ticket($ticket_id, $assigned_to) {
        return $this->db->where('id', $ticket_id)->update($this->tickets_table, ['assigned_to' => $assigned_to]);
    }

    public function update_ticket_status($ticket_id, $status) {
        return $this->db->where('id', $ticket_id)->update($this->tickets_table, ['status' => $status]);
    }

    public function count_tickets($status = null, $priority = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        if ($priority) {
            $this->db->where('priority', $priority);
        }
        return $this->db->count_all_results($this->tickets_table);
    }

    public function count_tickets_by_user($user_id) {
        return $this->db->where('user_id', $user_id)->count_all_results($this->tickets_table);
    }

    public function get_ticket_summary() {
        $this->db->select('COUNT(*) as total_tickets')
                 ->select('COUNT(CASE WHEN status = "open" THEN 1 END) as open_tickets')
                 ->select('COUNT(CASE WHEN status = "in_progress" THEN 1 END) as in_progress_tickets')
                 ->select('COUNT(CASE WHEN status = "closed" THEN 1 END) as closed_tickets')
                 ->select('COUNT(CASE WHEN priority = "high" THEN 1 END) as high_priority_tickets')
                 ->from($this->tickets_table);
        return $this->db->get()->row();
    }

    public function search_tickets($search) {
        $this->db->select('support_tickets.*, users.first_name, users.last_name, users.email,
                          (SELECT COUNT(*) FROM ticket_replies WHERE ticket_replies.ticket_id = support_tickets.id) as reply_count')
                 ->from($this->tickets_table)
                 ->join('users', 'users.id = support_tickets.user_id')
                 ->group_start()
                 ->like('support_tickets.ticket_number', $search)
                 ->or_like('support_tickets.subject', $search)
                 ->or_like('users.first_name', $search)
                 ->or_like('users.last_name', $search)
                 ->group_end()
                 ->order_by('support_tickets.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Count all tickets
    public function count_all_tickets() {
        return $this->db->count_all_results($this->tickets_table);
    }

    // Count tickets by status
    public function count_tickets_by_status($status) {
        return $this->db->where('status', $status)->count_all_results($this->tickets_table);
    }

    // Get recent tickets
    public function get_recent_tickets($limit = 5) {
        $this->db->select('support_tickets.*, users.first_name, users.last_name, users.email')
                 ->from($this->tickets_table)
                 ->join('users', 'users.id = support_tickets.user_id')
                 ->order_by('support_tickets.created_at', 'DESC')
                 ->limit($limit);
        return $this->db->get()->result();
    }
    
    // Get tickets with pagination
    public function get_tickets($limit = 20, $offset = 0, $status = null, $priority = null) {
        $this->db->select('support_tickets.*, users.first_name, users.last_name, users.email, 
                          assigned.first_name as assigned_first_name, assigned.last_name as assigned_last_name,
                          (SELECT COUNT(*) FROM ticket_replies WHERE ticket_replies.ticket_id = support_tickets.id) as reply_count')
                 ->from($this->tickets_table)
                 ->join('users', 'users.id = support_tickets.user_id')
                 ->join('users as assigned', 'assigned.id = support_tickets.assigned_to', 'left')
                 ->order_by('support_tickets.created_at', 'DESC')
                 ->limit($limit, $offset);
        
        if ($status) {
            $this->db->where('support_tickets.status', $status);
        }
        
        if ($priority) {
            $this->db->where('support_tickets.priority', $priority);
        }
        
        return $this->db->get()->result();
    }
} 