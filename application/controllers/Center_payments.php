<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Center_payments extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in and has admin role only
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        if ($this->session->userdata('role') !== 'admin') {
            show_404();
        }
        
        // Load required models
        $this->load->model('Center_payments_model');
        $this->load->model('Dropshipment_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }
    
    // Main listing page
    public function index() {
        $data['title'] = 'Center Payments';
        $data['active_page'] = 'center_payments';
        
        // Get filters
        $filters = array();
        if ($this->input->get('center_name')) $filters['center_name'] = $this->input->get('center_name');
        if ($this->input->get('status')) $filters['status'] = $this->input->get('status');
        if ($this->input->get('payment_method')) $filters['payment_method'] = $this->input->get('payment_method');
        if ($this->input->get('date_from')) $filters['date_from'] = $this->input->get('date_from');
        if ($this->input->get('date_to')) $filters['date_to'] = $this->input->get('date_to');
        if ($this->input->get('search')) $filters['search'] = $this->input->get('search');
        
        // Pagination
        $config['base_url'] = base_url('center_payments');
        $config['total_rows'] = $this->Center_payments_model->count_all_payments($filters);
        $config['per_page'] = 50;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        
        $this->pagination->initialize($config);
        
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        $data['payments'] = $this->Center_payments_model->get_all_payments($config['per_page'], $page, $filters);
        
        // Get summary data
        $data['summary'] = $this->Center_payments_model->get_payment_summary($filters);
        $data['center_summary'] = $this->Center_payments_model->get_center_payment_summary($filters);
        
        // Get filter options
        $data['centers'] = $this->Dropshipment_model->get_all_centers(true); // Show all centers for admin
        $data['payment_methods'] = $this->Center_payments_model->get_payment_methods();
        $data['payment_statuses'] = $this->Center_payments_model->get_payment_statuses();
        $data['filters'] = $filters;
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('center_payments/list', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Add new payment
    public function add() {
        $data['title'] = 'Add Center Payment';
        $data['active_page'] = 'center_payments';
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('center_name', 'Center Name', 'required|trim');
            $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
            $this->form_validation->set_rules('payment_method', 'Payment Method', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $payment_data = array(
                    'center_name' => $this->input->post('center_name'),
                    'amount_paid' => $this->input->post('amount_paid'),
                    'payment_date' => $this->input->post('payment_date'),
                    'payment_method' => $this->input->post('payment_method'),
                    'reference_number' => $this->input->post('reference_number'),
                    'notes' => $this->input->post('notes'),
                    'status' => $this->input->post('status'),
                    'created_by' => $this->session->userdata('user_id')
                );
                
                if ($this->Center_payments_model->add_payment($payment_data)) {
                    $this->session->set_flashdata('success', 'Payment recorded successfully.');
                    redirect('center_payments');
                } else {
                    $this->session->set_flashdata('error', 'Failed to record payment.');
                }
            }
        }
        
        // Get filter options
        $data['centers'] = $this->Dropshipment_model->get_all_centers(true); // Show all centers for admin
        $data['payment_methods'] = $this->Center_payments_model->get_payment_methods();
        $data['payment_statuses'] = $this->Center_payments_model->get_payment_statuses();
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('center_payments/form', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Edit payment
    public function edit($id) {
        $data['title'] = 'Edit Center Payment';
        $data['active_page'] = 'center_payments';
        $data['payment'] = $this->Center_payments_model->get_payment_by_id($id);
        
        if (!$data['payment']) {
            $this->session->set_flashdata('error', 'Payment not found.');
            redirect('center_payments');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('center_name', 'Center Name', 'required|trim');
            $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
            $this->form_validation->set_rules('payment_method', 'Payment Method', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $payment_data = array(
                    'center_name' => $this->input->post('center_name'),
                    'amount_paid' => $this->input->post('amount_paid'),
                    'payment_date' => $this->input->post('payment_date'),
                    'payment_method' => $this->input->post('payment_method'),
                    'reference_number' => $this->input->post('reference_number'),
                    'notes' => $this->input->post('notes'),
                    'status' => $this->input->post('status')
                );
                
                if ($this->Center_payments_model->update_payment($id, $payment_data)) {
                    $this->session->set_flashdata('success', 'Payment updated successfully.');
                    redirect('center_payments');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update payment.');
                }
            }
        }
        
        // Get filter options
        $data['centers'] = $this->Dropshipment_model->get_all_centers();
        $data['payment_methods'] = $this->Center_payments_model->get_payment_methods();
        $data['payment_statuses'] = $this->Center_payments_model->get_payment_statuses();
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('center_payments/form', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // View payment details
    public function view($id) {
        $data['title'] = 'Payment Details';
        $data['active_page'] = 'center_payments';
        $data['payment'] = $this->Center_payments_model->get_payment_by_id($id);
        
        if (!$data['payment']) {
            $this->session->set_flashdata('error', 'Payment not found.');
            redirect('center_payments');
        }
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('center_payments/view', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Delete payment
    public function delete($id) {
        $payment = $this->Center_payments_model->get_payment_by_id($id);
        
        if (!$payment) {
            $this->session->set_flashdata('error', 'Payment not found.');
        } else {
            if ($this->Center_payments_model->delete_payment($id)) {
                $this->session->set_flashdata('success', 'Payment deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete payment.');
            }
        }
        
        redirect('center_payments');
    }
    
    // AJAX: Get center details for payment form
    public function get_center_details() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $center_name = $this->input->post('center_name');
        
        if (!$center_name) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Center name is required']));
            return;
        }
        
        $center_outstanding = $this->Center_payments_model->get_center_outstanding($center_name);
        
        $response = array(
            'success' => true,
            'center' => array(
                'center_name' => $center_name,
                'total_orders' => $center_outstanding['total_orders'],
                'total_value' => $center_outstanding['total_value'],
                'total_paid' => $center_outstanding['total_paid'],
                'outstanding' => $center_outstanding['outstanding']
            )
        );
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($response));
    }
    
    // Export payments to CSV
    public function export_csv() {
        $filters = array();
        if ($this->input->get('center_name')) $filters['center_name'] = $this->input->get('center_name');
        if ($this->input->get('status')) $filters['status'] = $this->input->get('status');
        if ($this->input->get('date_from')) $filters['date_from'] = $this->input->get('date_from');
        if ($this->input->get('date_to')) $filters['date_to'] = $this->input->get('date_to');
        
        $payments = $this->Center_payments_model->get_all_payments(10000, 0, $filters);
        
        $filename = 'center_payments_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, array(
            'Payment ID',
            'Center Name',
            'Amount Paid',
            'Payment Date',
            'Payment Method',
            'Reference Number',
            'Status',
            'Notes',
            'Created By',
            'Created At'
        ));
        
        // CSV data
        foreach ($payments as $payment) {
            fputcsv($output, array(
                $payment->id,
                $payment->center_name,
                $payment->amount_paid,
                $payment->payment_date,
                $payment->payment_method,
                $payment->reference_number,
                $payment->status,
                $payment->notes,
                $payment->creator_first_name . ' ' . $payment->creator_last_name,
                $payment->created_at
            ));
        }
        
        fclose($output);
    }
} 