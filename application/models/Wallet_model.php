<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->wallet_table = 'wallets';
        $this->transactions_table = 'wallet_transactions';
    }

    public function get_wallet_by_user_id($user_id) {
        return $this->db->where('user_id', $user_id)->get($this->wallet_table)->row();
    }

    public function create_wallet($user_id) {
        // Check if wallet already exists
        $existing_wallet = $this->get_wallet_by_user_id($user_id);
        if ($existing_wallet) {
            return $existing_wallet->id; // Return existing wallet ID
        }
        
        $data = array(
            'user_id' => $user_id,
            'balance' => 0.00
        );
        $this->db->insert($this->wallet_table, $data);
        return $this->db->insert_id();
    }

    public function update_balance($user_id, $amount) {
        return $this->db->where('user_id', $user_id)
                        ->set('balance', 'balance + ' . $amount, FALSE)
                        ->update($this->wallet_table);
    }

    public function get_balance($user_id) {
        $wallet = $this->get_wallet_by_user_id($user_id);
        return $wallet ? $wallet->balance : null;
    }

    public function add_transaction($user_id, $type, $amount, $description, $reference_id = null, $reference_type = 'manual', $balance_after = null) {
        $data = array(
            'user_id' => $user_id,
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'reference_id' => $reference_id,
            'reference_type' => $reference_type
        );
        
        // Add balance_after if provided
        if ($balance_after !== null) {
            $data['balance_after'] = $balance_after;
        }
        
        $this->db->insert($this->transactions_table, $data);
        return $this->db->insert_id();
    }

    public function get_transactions($user_id, $limit = null) {
        $this->db->where('user_id', $user_id)
                 ->order_by('created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get($this->transactions_table)->result();
    }

    public function credit_wallet($user_id, $amount, $description = 'Manual credit', $reference_id = null, $reference_type = 'manual') {
        $this->db->trans_start();
        
        // Create wallet if it doesn't exist
        $wallet = $this->get_wallet_by_user_id($user_id);
        if (!$wallet) {
            $this->create_wallet($user_id);
        }
        
        // Get current balance
        $current_balance = $this->get_balance($user_id);
        $new_balance = $current_balance + $amount;
        
        // Update wallet balance
        $this->update_balance($user_id, $amount);
        
        // Add transaction record with balance_after
        $this->add_transaction($user_id, 'credit', $amount, $description, $reference_id, $reference_type, $new_balance);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function debit_wallet($user_id, $amount, $description = 'Manual debit', $reference_id = null, $reference_type = 'manual') {
        // Create wallet if it doesn't exist
        $wallet = $this->get_wallet_by_user_id($user_id);
        if (!$wallet) {
            $this->create_wallet($user_id);
        }
        
        // Get current balance
        $current_balance = $this->get_balance($user_id);
        $new_balance = $current_balance - $amount;
        
        $this->db->trans_start();
        
        // Update wallet balance
        $this->update_balance($user_id, -$amount);
        
        // Add transaction record with balance_after
        $this->add_transaction($user_id, 'debit', $amount, $description, $reference_id, $reference_type, $new_balance);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_all_wallets() {
        $this->db->select('wallets.*, users.username, users.first_name, users.last_name, users.email')
                 ->from($this->wallet_table)
                 ->join('users', 'users.id = wallets.user_id')
                 ->order_by('wallets.balance', 'DESC');
        return $this->db->get()->result();
    }

    public function get_wallet_summary() {
        $this->db->select('SUM(balance) as total_balance, COUNT(*) as total_users')
                 ->from($this->wallet_table);
        return $this->db->get()->row();
    }

    public function get_transaction_summary($user_id, $period = 'month') {
        $this->db->select('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_credits')
                 ->select('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_debits')
                 ->from($this->transactions_table)
                 ->where('user_id', $user_id);
        
        if ($period == 'month') {
            $this->db->where('created_at >=', date('Y-m-01'));
        } elseif ($period == 'week') {
            $this->db->where('created_at >=', date('Y-m-d', strtotime('-7 days')));
        }
        
        return $this->db->get()->row();
    }
    
    // Count all wallets
    public function count_all_wallets() {
        return $this->db->count_all_results($this->wallet_table);
    }
    
    // Get wallets with pagination
    public function get_wallets($limit = 20, $offset = 0) {
        $this->db->select('wallets.*, users.username, users.first_name, users.last_name, users.email')
                 ->from($this->wallet_table)
                 ->join('users', 'users.id = wallets.user_id')
                 ->order_by('wallets.balance', 'DESC')
                 ->limit($limit, $offset);
        return $this->db->get()->result();
    }
    
    // Get customer wallets with pagination (for staff)
    public function get_customer_wallets($limit = 20, $offset = 0) {
        $this->db->select('wallets.*, users.username, users.first_name, users.last_name, users.email')
                 ->from($this->wallet_table)
                 ->join('users', 'users.id = wallets.user_id')
                 ->where('users.role', 'customer')
                 ->order_by('wallets.balance', 'DESC')
                 ->limit($limit, $offset);
        return $this->db->get()->result();
    }
    
    // Count customer wallets
    public function count_customer_wallets() {
        $this->db->select('wallets.id')
                 ->from($this->wallet_table)
                 ->join('users', 'users.id = wallets.user_id')
                 ->where('users.role', 'customer');
        return $this->db->count_all_results();
    }
    
    // Get customer wallet summary
    public function get_customer_wallet_summary() {
        $this->db->select('SUM(wallets.balance) as total_balance, COUNT(wallets.id) as total_users')
                 ->from($this->wallet_table)
                 ->join('users', 'users.id = wallets.user_id')
                 ->where('users.role', 'customer');
        return $this->db->get()->row();
    }
    
    // Get wallet by ID
    public function get_wallet_by_id($id) {
        $this->db->select('wallets.*, users.username, users.first_name, users.last_name, users.email, users.role')
                 ->from($this->wallet_table)
                 ->join('users', 'users.id = wallets.user_id')
                 ->where('wallets.id', $id);
        return $this->db->get()->row();
    }
    
    // Get wallet transactions
    public function get_wallet_transactions($wallet_id, $limit = null) {
        $this->db->select('wallet_transactions.*')
                 ->from($this->transactions_table)
                 ->join('wallets', 'wallets.user_id = wallet_transactions.user_id')
                 ->where('wallets.id', $wallet_id)
                 ->order_by('wallet_transactions.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }
} 