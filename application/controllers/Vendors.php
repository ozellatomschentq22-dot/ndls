<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in and has admin role
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        $user_role = $this->session->userdata('role');
        if ($user_role !== 'admin') {
            show_404();
        }
        
        // Load required models
        $this->load->model('Vendor_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }
    
    // Main listing page
    public function index() {
        $data['title'] = 'Manage Vendors';
        $data['active_page'] = 'vendors';
        
        // Get filters from URL parameters
        $filters = array(
            'name' => $this->input->get('name'),
            'payout_type' => $this->input->get('payout_type'),
            'status' => $this->input->get('status')
        );
        
        // Pagination configuration
        $config['base_url'] = base_url('vendors');
        $config['total_rows'] = $this->Vendor_model->count_all_vendors($filters);
        $config['per_page'] = 20;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        
        // Pagination styling
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['anchor_class'] = 'page-link';
        
        $this->pagination->initialize($config);
        
        // Get current page
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        $offset = $page * $config['per_page'];
        
        // Get vendors data
        $data['vendors'] = $this->Vendor_model->get_all_vendors($config['per_page'], $offset, $filters);
        $data['vendor_summary'] = $this->Vendor_model->get_vendor_summary();
        $data['payout_types'] = $this->Vendor_model->get_payout_types();
        $data['statuses'] = $this->Vendor_model->get_vendor_statuses();
        $data['filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('vendors/list', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Add new vendor form
    public function add() {
        $data['title'] = 'Add Vendor';
        $data['active_page'] = 'vendors';
        $data['payout_types'] = $this->Vendor_model->get_payout_types();
        $data['statuses'] = $this->Vendor_model->get_vendor_statuses();
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Vendor Name', 'required|trim|is_unique[vendors.name]');
            $this->form_validation->set_rules('payout_type', 'Payout Type', 'required|in_list[flat,percentage]');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
            
            // Conditional validation based on payout type
            if ($this->input->post('payout_type') === 'flat') {
                $this->form_validation->set_rules('flat_rate_inr', 'Flat Rate (INR)', 'required|numeric|greater_than[0]');
            } else {
                $this->form_validation->set_rules('percentage_rate', 'Percentage Rate', 'required|numeric|greater_than[0]|less_than_equal_to[100]');
                $this->form_validation->set_rules('percentage_inr_rate', 'Percentage INR Rate', 'required|numeric|greater_than[0]');
            }
            
            if ($this->form_validation->run() == TRUE) {
                $vendor_data = array(
                    'name' => $this->input->post('name'),
                    'payout_type' => $this->input->post('payout_type'),
                    'flat_rate_inr' => $this->input->post('flat_rate_inr'),
                    'percentage_rate' => $this->input->post('percentage_rate'),
                    'percentage_inr_rate' => $this->input->post('percentage_inr_rate'),
                    'status' => $this->input->post('status'),
                    'created_by' => $this->session->userdata('user_id')
                );
                
                $vendor_id = $this->Vendor_model->add_vendor($vendor_data);
                
                if ($vendor_id) {
                    $this->session->set_flashdata('success', 'Vendor added successfully.');
                    redirect('vendors');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add vendor.');
                }
            }
        }
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('vendors/form', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Edit vendor
    public function edit($id) {
        $data['title'] = 'Edit Vendor';
        $data['active_page'] = 'vendors';
        $data['vendor'] = $this->Vendor_model->get_vendor_by_id($id);
        $data['payout_types'] = $this->Vendor_model->get_payout_types();
        $data['statuses'] = $this->Vendor_model->get_vendor_statuses();
        
        if (!$data['vendor']) {
            $this->session->set_flashdata('error', 'Vendor not found.');
            redirect('vendors');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Vendor Name', 'required|trim');
            $this->form_validation->set_rules('payout_type', 'Payout Type', 'required|in_list[flat,percentage]');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
            
            // Check if name is unique (excluding current vendor)
            $existing_vendor = $this->Vendor_model->get_vendor_by_name($this->input->post('name'));
            if ($existing_vendor && $existing_vendor->id != $id) {
                $this->form_validation->set_rules('name', 'Vendor Name', 'required|trim|is_unique[vendors.name]');
            }
            
            // Conditional validation based on payout type
            if ($this->input->post('payout_type') === 'flat') {
                $this->form_validation->set_rules('flat_rate_inr', 'Flat Rate (INR)', 'required|numeric|greater_than[0]');
            } else {
                $this->form_validation->set_rules('percentage_rate', 'Percentage Rate', 'required|numeric|greater_than[0]|less_than_equal_to[100]');
                $this->form_validation->set_rules('percentage_inr_rate', 'Percentage INR Rate', 'required|numeric|greater_than[0]');
            }
            
            if ($this->form_validation->run() == TRUE) {
                $vendor_data = array(
                    'name' => $this->input->post('name'),
                    'payout_type' => $this->input->post('payout_type'),
                    'flat_rate_inr' => $this->input->post('flat_rate_inr'),
                    'percentage_rate' => $this->input->post('percentage_rate'),
                    'percentage_inr_rate' => $this->input->post('percentage_inr_rate'),
                    'status' => $this->input->post('status')
                );
                
                if ($this->Vendor_model->update_vendor($id, $vendor_data)) {
                    $this->session->set_flashdata('success', 'Vendor updated successfully.');
                    redirect('vendors');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update vendor.');
                }
            }
        }
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('vendors/form', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Delete vendor
    public function delete($id) {
        $vendor = $this->Vendor_model->get_vendor_by_id($id);
        
        if (!$vendor) {
            $this->session->set_flashdata('error', 'Vendor not found.');
        } else {
            if ($this->Vendor_model->delete_vendor($id)) {
                $this->session->set_flashdata('success', 'Vendor deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete vendor.');
            }
        }
        
        redirect('vendors');
    }
    
    // View vendor details
    public function view($id) {
        $data['title'] = 'View Vendor';
        $data['active_page'] = 'vendors';
        $data['vendor'] = $this->Vendor_model->get_vendor_by_id($id);
        
        if (!$data['vendor']) {
            $this->session->set_flashdata('error', 'Vendor not found.');
            redirect('vendors');
        }
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('vendors/view', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // View vendor profile with payments
    public function profile($id) {
        $data['title'] = 'Vendor Profile';
        $data['active_page'] = 'vendors';
        $data['vendor'] = $this->Vendor_model->get_vendor_by_id($id);
        
        if (!$data['vendor']) {
            $this->session->set_flashdata('error', 'Vendor not found.');
            redirect('vendors');
        }
        
        // Get filters from URL parameters
        $filters = array(
            'status' => $this->input->get('status'),
            'mode' => $this->input->get('mode'),
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to')
        );
        
        // Pagination configuration
        $config['base_url'] = base_url('vendors/profile/' . $id);
        $config['total_rows'] = $this->Vendor_model->count_vendor_payments($id, $filters);
        $config['per_page'] = 20;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        
        // Pagination styling
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['anchor_class'] = 'page-link';
        
        $this->pagination->initialize($config);
        
        // Get current page
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        $offset = $page * $config['per_page'];
        
        // Get vendor payments data
        $data['payments'] = $this->Vendor_model->get_vendor_payments($id, $config['per_page'], $offset, $filters);
        $data['payment_summary'] = $this->Vendor_model->get_vendor_payment_summary($id);
        $data['filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        
        // Load payment modes and statuses for filters
        $this->load->model('Vendor_payments_model');
        $data['modes'] = $this->Vendor_payments_model->get_payment_modes();
        $data['statuses'] = $this->Vendor_payments_model->get_payment_statuses();
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('vendors/profile', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Calculate payout preview (AJAX)
    public function calculate_payout() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $vendor_id = $this->input->post('vendor_id');
        $amount_usd = $this->input->post('amount_usd');
        
        if (!$vendor_id || !$amount_usd) {
            echo json_encode(['error' => 'Invalid parameters']);
            return;
        }
        
        $vendor_payout = $this->Vendor_model->calculate_vendor_payout($vendor_id, $amount_usd);
        $system_profit = $this->Vendor_model->calculate_system_profit($amount_usd);
        
        echo json_encode([
            'vendor_payout' => number_format($vendor_payout, 2),
            'system_profit' => number_format($system_profit, 2),
            'amount_usd' => number_format($amount_usd, 2)
        ]);
    }
} 