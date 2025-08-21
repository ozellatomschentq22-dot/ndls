<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_model extends CI_Model {

    private $table = 'leads';

    public function __construct() {
        parent::__construct();
    }

    public function create_table() {
        $sql = "CREATE TABLE IF NOT EXISTS leads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            email VARCHAR(255) NOT NULL,
            address_line1 VARCHAR(255) NOT NULL,
            address_line2 VARCHAR(255) NULL,
            city VARCHAR(100) NOT NULL,
            postal_code VARCHAR(20) NOT NULL,
            state VARCHAR(100) NOT NULL,
            country VARCHAR(100) DEFAULT 'USA',
            product_interest VARCHAR(500) NULL,
            payment_method VARCHAR(50) NULL,
            payment_details TEXT NULL,
            status ENUM('new', 'contacted', 'qualified', 'converted', 'lost') DEFAULT 'new',
            notes TEXT NULL,
            source VARCHAR(100) DEFAULT 'manual',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            converted_at TIMESTAMP NULL,
            converted_to_user_id INT NULL,
            INDEX idx_status (status),
            INDEX idx_email (email),
            INDEX idx_phone (phone),
            INDEX idx_created_at (created_at),
            INDEX idx_converted_to_user_id (converted_to_user_id)
        )";
        
        return $this->db->query($sql);
    }

    // Add a new lead
    public function add_lead($data) {
        $lead_data = array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address_line1' => $data['address_line1'],
            'address_line2' => isset($data['address_line2']) ? $data['address_line2'] : NULL,
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
            'state' => $data['state'],
            'country' => isset($data['country']) ? $data['country'] : 'USA',
            'product_interest' => isset($data['product_interest']) ? $data['product_interest'] : NULL,
            'payment_method' => isset($data['payment_method']) ? $data['payment_method'] : NULL,
            'payment_details' => isset($data['payment_details']) ? $data['payment_details'] : NULL,
            'status' => isset($data['status']) ? $data['status'] : 'new',
            'notes' => isset($data['notes']) ? $data['notes'] : NULL,
            'source' => isset($data['source']) ? $data['source'] : 'manual'
        );
        
        $this->db->insert($this->table, $lead_data);
        return $this->db->insert_id();
    }

    // Get all leads with pagination
    public function get_leads($limit = 50, $offset = 0, $status = null, $converted_only = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        
        // Handle converted leads filtering
        if ($converted_only === true) {
            // Only converted leads
            $this->db->where('status', 'converted');
        } elseif ($converted_only === false) {
            // Exclude converted leads
            $this->db->where('status !=', 'converted');
        }
        
        return $this->db->order_by('created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get($this->table)
                        ->result();
    }

    // Get lead by ID
    public function get_lead_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    // Get lead by email (single lead)
    public function get_lead_by_email($email) {
        return $this->db->where('email', $email)->get($this->table)->row();
    }

    // Get all leads by email (multiple leads)
    public function get_leads_by_email($email) {
        return $this->db->where('email', $email)
                        ->order_by('created_at', 'DESC')
                        ->get($this->table)
                        ->result();
    }

    // Update lead
    public function update_lead($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }
    
    // Update lead status specifically
    public function update_lead_status($id, $status) {
        error_log("Lead_model update_lead_status called - ID: " . $id . ", Status: " . $status);
        
        $data = array(
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        // If status is being set to 'converted', set the converted_at timestamp
        if ($status === 'converted') {
            $data['converted_at'] = date('Y-m-d H:i:s');
        }
        
        $result = $this->db->where('id', $id)->update($this->table, $data);
        error_log("Lead_model update result: " . ($result ? 'true' : 'false') . ", Affected rows: " . $this->db->affected_rows());
        
        if (!$result) {
            error_log("Lead_model update error: " . $this->db->error()['message']);
        }
        
        return $result;
    }

    // Delete lead
    public function delete_lead($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    // Convert lead to customer
    public function convert_lead_to_customer($lead_id, $user_id) {
        $data = array(
            'status' => 'converted',
            'converted_at' => date('Y-m-d H:i:s'),
            'converted_to_user_id' => $user_id
        );
        
        return $this->db->where('id', $lead_id)->update($this->table, $data);
    }

    // Get lead statistics
    public function get_lead_stats($converted_only = null) {
        $stats = array();
        
        // Handle converted leads filtering for stats
        if ($converted_only === true) {
            // Only converted leads
            $this->db->where('status', 'converted');
        } elseif ($converted_only === false) {
            // Exclude converted leads
            $this->db->where('status !=', 'converted');
        }
        
        // Total leads
        $stats['total'] = $this->db->count_all_results($this->table);
        
        // Reset the query for status counts
        $this->db->reset_query();
        
        // Handle converted leads filtering for status counts
        if ($converted_only === true) {
            $this->db->where('status', 'converted');
        } elseif ($converted_only === false) {
            $this->db->where('status !=', 'converted');
        }
        
        // Leads by status
        $status_counts = $this->db->select('status, COUNT(*) as count')
                                  ->group_by('status')
                                  ->get($this->table)
                                  ->result();
        
        foreach ($status_counts as $status) {
            $stats[$status->status] = $status->count;
        }
        
        // Reset the query for date-based stats
        $this->db->reset_query();
        
        // Handle converted leads filtering for date stats
        if ($converted_only === true) {
            $this->db->where('status', 'converted');
        } elseif ($converted_only === false) {
            $this->db->where('status !=', 'converted');
        }
        
        // Today's leads
        $stats['today'] = $this->db->where('DATE(created_at)', date('Y-m-d'))
                                   ->count_all_results($this->table);
        
        // Reset the query for week stats
        $this->db->reset_query();
        
        // Handle converted leads filtering for week stats
        if ($converted_only === true) {
            $this->db->where('status', 'converted');
        } elseif ($converted_only === false) {
            $this->db->where('status !=', 'converted');
        }
        
        // This week's leads
        $stats['this_week'] = $this->db->where('created_at >=', date('Y-m-d', strtotime('-7 days')))
                                       ->count_all_results($this->table);
        
        // Reset the query for month stats
        $this->db->reset_query();
        
        // Handle converted leads filtering for month stats
        if ($converted_only === true) {
            $this->db->where('status', 'converted');
        } elseif ($converted_only === false) {
            $this->db->where('status !=', 'converted');
        }
        
        // This month's leads
        $stats['this_month'] = $this->db->where('created_at >=', date('Y-m-01'))
                                        ->count_all_results($this->table);
        
        // Conversion rate
        $converted = isset($stats['converted']) ? $stats['converted'] : 0;
        $stats['conversion_rate'] = $stats['total'] > 0 ? round(($converted / $stats['total']) * 100, 2) : 0;
        
        return $stats;
    }

    // Search leads
    public function search_leads($search_term, $converted_only = null, $limit = 50, $offset = 0) {
        $this->db->group_start();
        $this->db->like('first_name', $search_term);
        $this->db->or_like('last_name', $search_term);
        $this->db->or_like('email', $search_term);
        $this->db->or_like('phone', $search_term);
        $this->db->or_like('city', $search_term);
        $this->db->or_like('product_interest', $search_term);
        $this->db->group_end();
        
        // Handle converted leads filtering
        if ($converted_only === true) {
            // Only converted leads
            $this->db->where('status', 'converted');
        } elseif ($converted_only === false) {
            // Exclude converted leads
            $this->db->where('status !=', 'converted');
        }
        
        return $this->db->order_by('created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get($this->table)
                        ->result();
    }

    // Get leads by date range
    public function get_leads_by_date_range($start_date, $end_date) {
        return $this->db->where('created_at >=', $start_date . ' 00:00:00')
                        ->where('created_at <=', $end_date . ' 23:59:59')
                        ->order_by('created_at', 'DESC')
                        ->get($this->table)
                        ->result();
    }

    // Import leads from CSV
    public function import_leads_from_csv($csv_data) {
        $imported = 0;
        $errors = array();
        
        foreach ($csv_data as $row) {
            try {
                $lead_data = array(
                    'first_name' => trim($row['first_name']),
                    'last_name' => trim($row['last_name']),
                    'phone' => trim($row['phone']),
                    'email' => trim($row['email']),
                    'address_line1' => trim($row['address_line1']),
                    'city' => trim($row['city']),
                    'postal_code' => trim($row['postal_code']),
                    'state' => trim($row['state']),
                    'product_interest' => isset($row['product_interest']) ? trim($row['product_interest']) : NULL,
                    'payment_method' => isset($row['payment_method']) ? trim($row['payment_method']) : NULL,
                    'payment_details' => isset($row['payment_details']) ? trim($row['payment_details']) : NULL,
                    'source' => 'csv_import'
                );
                
                // Check if lead already exists
                $existing = $this->get_lead_by_email($lead_data['email']);
                if (!$existing) {
                    $this->add_lead($lead_data);
                    $imported++;
                } else {
                    $errors[] = "Lead with email {$lead_data['email']} already exists";
                }
            } catch (Exception $e) {
                $errors[] = "Error importing row: " . $e->getMessage();
            }
        }
        
        return array('imported' => $imported, 'errors' => $errors);
    }

    // Export leads to CSV
    public function export_leads_to_csv($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        
        return $this->db->order_by('created_at', 'DESC')
                        ->get($this->table)
                        ->result();
    }

    // Count leads by email
    public function count_leads_by_email($email) {
        return $this->db->where('email', $email)->count_all_results($this->table);
    }

    // Count converted leads by email
    public function count_converted_leads_by_email($email) {
        return $this->db->where('email', $email)
                        ->where('status', 'converted')
                        ->count_all_results($this->table);
    }

    // Count all leads
    public function count_all_leads() {
        return $this->db->count_all_results($this->table);
    }

    // Count leads by status
    public function count_leads_by_status($status, $converted_only = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        
        // Handle converted leads filtering
        if ($converted_only === true) {
            // Only converted leads
            $this->db->where('status', 'converted');
        } elseif ($converted_only === false) {
            // Exclude converted leads
            $this->db->where('status !=', 'converted');
        }
        
        return $this->db->count_all_results($this->table);
    }

    // Get recent leads
    public function get_recent_leads($limit = 5) {
        return $this->db->order_by('created_at', 'DESC')
                        ->limit($limit)
                        ->get($this->table)
                        ->result();
    }
} 