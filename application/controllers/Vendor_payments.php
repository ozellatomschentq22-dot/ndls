<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_payments extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in and has admin or staff role
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        $user_role = $this->session->userdata('role');
        if ($user_role !== 'admin' && $user_role !== 'staff') {
            show_404();
        }
        
        // Load required models
        $this->load->model('Vendor_payments_model');
        $this->load->model('Vendor_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        // Load additional models for staff sidebar
        if ($user_role === 'staff') {
            $this->load->model('User_model');
            $this->load->model('Order_model');
            $this->load->model('Product_model');
            $this->load->model('Support_ticket_model');
            $this->load->model('Recharge_request_model');
            $this->load->model('Lead_model');
            $this->load->model('Customer_reminder_model');
            $this->load->model('Notification_model');
            $this->load->model('Admin_message_model');
            $this->load->model('Wallet_model');
        }
    }
    
    /**
     * Set notification counts for staff sidebar
     */
    private function set_notification_counts(&$data) {
        $user_id = $this->session->userdata('user_id');
        
        // Set notification counts for sidebar
        $data['new_leads'] = $this->Lead_model->count_leads_by_status('new');
        $data['pending_tickets'] = $this->Support_ticket_model->count_tickets_by_status('open');
        $data['pending_recharge_requests'] = $this->Recharge_request_model->count_requests_by_status('pending');
        $data['active_reminders'] = $this->Customer_reminder_model->count_reminders_by_status('active');
        $data['unread_messages'] = $this->Admin_message_model->get_unread_count();
        $data['unread_notifications'] = $this->Notification_model->get_unread_count($user_id, 'staff');
    }
    
    // Main listing page
    public function index() {
        $data['title'] = 'Vendor Payments';
        $data['active_page'] = 'vendor_payments';
        
        // Set notification counts for staff sidebar
        if ($this->session->userdata('role') === 'staff') {
            $this->set_notification_counts($data);
        }
        
        // Get filters from URL parameters
        $filters = array(
            'vendor' => $this->input->get('vendor'),
            'status' => $this->input->get('status'),
            'mode' => $this->input->get('mode'),
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to')
        );
        
        // Pagination configuration
        $config['base_url'] = base_url('vendor_payments');
        $config['total_rows'] = $this->Vendor_payments_model->count_all_payments($filters);
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
        
        // Get payments data
        $data['payments'] = $this->Vendor_payments_model->get_all_payments($config['per_page'], $offset, $filters);
        $data['vendor_summary'] = $this->Vendor_payments_model->get_vendor_summary();
        $data['overall_summary'] = $this->Vendor_payments_model->get_overall_summary();
        $data['vendors'] = $this->Vendor_payments_model->get_unique_vendors();
        $data['modes'] = $this->Vendor_payments_model->get_payment_modes();
        $data['statuses'] = $this->Vendor_payments_model->get_payment_statuses();
        $data['filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        
        // Load views based on user role
        if ($this->session->userdata('role') === 'admin') {
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/includes/sidebar');
            $this->load->view('vendor_payments/list', $data);
            $this->load->view('admin/includes/footer');
        } else {
            $this->load->view('staff/includes/header', $data);
            $this->load->view('staff/includes/sidebar');
            $this->load->view('vendor_payments/list', $data);
            $this->load->view('staff/includes/footer');
        }
    }
    
    // Add new payment form
    public function add() {
        $data['title'] = 'Add Vendor Payment';
        $data['active_page'] = 'vendor_payments';
        $data['modes'] = $this->Vendor_payments_model->get_payment_modes();
        $data['statuses'] = $this->Vendor_payments_model->get_payment_statuses();
        $data['vendors'] = $this->Vendor_model->get_active_vendors();
        
        // Set notification counts for staff sidebar
        if ($this->session->userdata('role') === 'staff') {
            $this->set_notification_counts($data);
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('date', 'Date', 'required');
            $this->form_validation->set_rules('sender', 'Sender', 'required|trim');
            $this->form_validation->set_rules('receiver', 'Receiver', 'required|trim');
            $this->form_validation->set_rules('mode', 'Payment Mode', 'required|in_list[Zelle,Cash App,Venmo]');
            $this->form_validation->set_rules('amount', 'Amount', 'required|trim');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[Pending,Approved,Declined]');
            $this->form_validation->set_rules('vendor_id', 'Vendor', 'required|numeric');
            
            if ($this->form_validation->run() == TRUE) {
                $screenshot_path = NULL;
                
                // Handle file upload
                if (!empty($_FILES['screenshot']['name'])) {
                    $config['upload_path'] = './uploads/vendor_payments/';
                    $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                    $config['max_size'] = 2048; // 2MB
                    $config['encrypt_name'] = TRUE;
                    
                    $this->load->library('upload', $config);
                    
                    if ($this->upload->do_upload('screenshot')) {
                        $upload_data = $this->upload->data();
                        $screenshot_path = 'uploads/vendor_payments/' . $upload_data['file_name'];
                    } else {
                        $this->session->set_flashdata('error', 'Screenshot upload failed: ' . $this->upload->display_errors());
                        redirect('vendor_payments/add');
                        return;
                    }
                }
                
                $payment_data = array(
                    'date' => $this->input->post('date'),
                    'sender' => $this->input->post('sender'),
                    'receiver' => $this->input->post('receiver'),
                    'mode' => $this->input->post('mode'),
                    'amount' => $this->input->post('amount'),
                    'status' => $this->input->post('status'),
                    'vendor_id' => $this->input->post('vendor_id'),
                    'screenshot' => $screenshot_path,
                    'created_by' => $this->session->userdata('user_id')
                );
                
                $payment_id = $this->Vendor_payments_model->add_payment($payment_data);
                
                if ($payment_id) {
                    $this->session->set_flashdata('success', 'Vendor payment added successfully.');
                    redirect('vendor_payments');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add vendor payment.');
                }
            }
        }
        
        // Load views based on user role
        if ($this->session->userdata('role') === 'admin') {
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/includes/sidebar');
            $this->load->view('vendor_payments/form', $data);
            $this->load->view('admin/includes/footer');
        } else {
            $this->load->view('staff/includes/header', $data);
            $this->load->view('staff/includes/sidebar');
            $this->load->view('vendor_payments/form', $data);
            $this->load->view('staff/includes/footer');
        }
    }
    
    // Edit payment
    public function edit($id) {
        $data['title'] = 'Edit Vendor Payment';
        $data['active_page'] = 'vendor_payments';
        $data['payment'] = $this->Vendor_payments_model->get_payment_by_id($id);
        $data['modes'] = $this->Vendor_payments_model->get_payment_modes();
        $data['statuses'] = $this->Vendor_payments_model->get_payment_statuses();
        $data['vendors'] = $this->Vendor_model->get_active_vendors();
        
        // Set notification counts for staff sidebar
        if ($this->session->userdata('role') === 'staff') {
            $this->set_notification_counts($data);
        }
        
        if (!$data['payment']) {
            $this->session->set_flashdata('error', 'Payment record not found.');
            redirect('vendor_payments');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('date', 'Date', 'required');
            $this->form_validation->set_rules('sender', 'Sender', 'required|trim');
            $this->form_validation->set_rules('receiver', 'Receiver', 'required|trim');
            $this->form_validation->set_rules('mode', 'Payment Mode', 'required|in_list[Zelle,Cash App,Venmo]');
            $this->form_validation->set_rules('amount', 'Amount', 'required|trim');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[Pending,Approved,Declined]');
            $this->form_validation->set_rules('vendor_id', 'Vendor', 'required|numeric');
            
            if ($this->form_validation->run() == TRUE) {
                $screenshot_path = $data['payment']->screenshot; // Keep existing screenshot
                
                // Handle file upload if new file is provided
                if (!empty($_FILES['screenshot']['name'])) {
                    $config['upload_path'] = './uploads/vendor_payments/';
                    $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                    $config['max_size'] = 2048; // 2MB
                    $config['encrypt_name'] = TRUE;
                    
                    $this->load->library('upload', $config);
                    
                    if ($this->upload->do_upload('screenshot')) {
                        $upload_data = $this->upload->data();
                        $screenshot_path = 'uploads/vendor_payments/' . $upload_data['file_name'];
                        
                        // Delete old screenshot if exists
                        if ($data['payment']->screenshot && file_exists('./' . $data['payment']->screenshot)) {
                            unlink('./' . $data['payment']->screenshot);
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Screenshot upload failed: ' . $this->upload->display_errors());
                        redirect('vendor_payments/edit/' . $id);
                        return;
                    }
                }
                
                $payment_data = array(
                    'date' => $this->input->post('date'),
                    'sender' => $this->input->post('sender'),
                    'receiver' => $this->input->post('receiver'),
                    'mode' => $this->input->post('mode'),
                    'amount' => $this->input->post('amount'),
                    'status' => $this->input->post('status'),
                    'vendor_id' => $this->input->post('vendor_id'),
                    'screenshot' => $screenshot_path
                );
                
                if ($this->Vendor_payments_model->update_payment($id, $payment_data)) {
                    $this->session->set_flashdata('success', 'Vendor payment updated successfully.');
                    redirect('vendor_payments');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update vendor payment.');
                }
            }
        }
        
        // Load views based on user role
        if ($this->session->userdata('role') === 'admin') {
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/includes/sidebar');
            $this->load->view('vendor_payments/form', $data);
            $this->load->view('admin/includes/footer');
        } else {
            $this->load->view('staff/includes/header', $data);
            $this->load->view('staff/includes/sidebar');
            $this->load->view('vendor_payments/form', $data);
            $this->load->view('staff/includes/footer');
        }
    }
    
    // Delete payment
    public function delete($id) {
        // Only admin can delete payments
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Access denied. Only administrators can delete payment records.');
            redirect('vendor_payments');
        }
        
        $payment = $this->Vendor_payments_model->get_payment_by_id($id);
        
        if (!$payment) {
            $this->session->set_flashdata('error', 'Payment record not found.');
        } else {
            // Delete screenshot file if exists
            if ($payment->screenshot && file_exists('./' . $payment->screenshot)) {
                unlink('./' . $payment->screenshot);
            }
            
            if ($this->Vendor_payments_model->delete_payment($id)) {
                $this->session->set_flashdata('success', 'Vendor payment deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete vendor payment.');
            }
        }
        
        redirect('vendor_payments');
    }
    
    // View payment details
    public function view($id) {
        $data['title'] = 'View Vendor Payment';
        $data['active_page'] = 'vendor_payments';
        $data['payment'] = $this->Vendor_payments_model->get_payment_by_id($id);
        
        // Set notification counts for staff sidebar
        if ($this->session->userdata('role') === 'staff') {
            $this->set_notification_counts($data);
        }
        
        if (!$data['payment']) {
            $this->session->set_flashdata('error', 'Payment record not found.');
            redirect('vendor_payments');
        }
        
        // Load views based on user role
        if ($this->session->userdata('role') === 'admin') {
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/includes/sidebar');
            $this->load->view('vendor_payments/view', $data);
            $this->load->view('admin/includes/footer');
        } else {
            $this->load->view('staff/includes/header', $data);
            $this->load->view('staff/includes/sidebar');
            $this->load->view('vendor_payments/view', $data);
            $this->load->view('staff/includes/footer');
        }
    }
    
    // Export to CSV
    public function export() {
        $filters = array(
            'vendor' => $this->input->get('vendor'),
            'status' => $this->input->get('status'),
            'mode' => $this->input->get('mode'),
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to')
        );
        
        $payments = $this->Vendor_payments_model->get_all_payments(1000, 0, $filters);
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="vendor_payments_' . date('Y-m-d') . '.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, array('Date', 'Sender', 'Receiver', 'Mode', 'Amount', 'Status', 'Vendor', 'Created By', 'Created At'));
        
        // Add data rows
        foreach ($payments as $payment) {
            fputcsv($output, array(
                $payment->date,
                $payment->sender,
                $payment->receiver,
                $payment->mode,
                '$' . number_format($payment->amount, 2),
                $payment->status,
                $payment->vendor,
                $payment->creator_first_name . ' ' . $payment->creator_last_name,
                $payment->created_at
            ));
        }
        
        fclose($output);
        exit;
    }

    // Update payment status (AJAX)
    public function update_status() {
        // Check if it's an AJAX request
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $payment_id = $this->input->post('payment_id');
        $status = $this->input->post('status');
        
        // Validate inputs
        if (!$payment_id || !$status) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Missing required parameters']));
            return;
        }
        
        // Validate status
        $valid_statuses = ['Pending', 'Approved', 'Declined'];
        if (!in_array($status, $valid_statuses)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Invalid status']));
            return;
        }
        
        // Get the payment record
        $payment = $this->Vendor_payments_model->get_payment_by_id($payment_id);
        if (!$payment) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Payment not found']));
            return;
        }
        
        // Update the payment status
        $update_data = array('status' => $status);
        if ($this->Vendor_payments_model->update_payment($payment_id, $update_data)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => 'Payment status updated successfully']));
        } else {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Failed to update payment status']));
        }
    }

    // Import payments from CSV (Admin only)
    public function import() {
        // Check if user is admin
        if ($this->session->userdata('role') !== 'admin') {
            show_404();
        }
        
        if ($this->input->post()) {
            // Check if file was uploaded
            if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
                $this->session->set_flashdata('error', 'Please select a valid CSV file.');
                redirect('vendor_payments');
                return;
            }
            
            $file = $_FILES['csv_file'];
            
            // Validate file type
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($file_extension !== 'csv') {
                $this->session->set_flashdata('error', 'Please upload a CSV file.');
                redirect('vendor_payments');
                return;
            }
            
            // Read CSV file
            $handle = fopen($file['tmp_name'], 'r');
            if (!$handle) {
                $this->session->set_flashdata('error', 'Unable to read the uploaded file.');
                redirect('vendor_payments');
                return;
            }
            
            // Get headers
            $headers = fgetcsv($handle);
            if (!$headers) {
                fclose($handle);
                $this->session->set_flashdata('error', 'Invalid CSV format.');
                redirect('vendor_payments');
                return;
            }
            
            // Debug: Log found headers
            log_message('debug', 'CSV Headers found: ' . implode(', ', $headers));
            
            // Validate required headers
            $required_headers = ['date', 'vendor_name', 'sender', 'receiver', 'mode', 'amount', 'status'];
            $header_map = array_flip(array_map('strtolower', $headers));
            
            // Check for missing headers and provide helpful error message
            $missing_headers = array();
            foreach ($required_headers as $required) {
                if (!isset($header_map[$required])) {
                    $missing_headers[] = $required;
                }
            }
            
            if (!empty($missing_headers)) {
                fclose($handle);
                $error_msg = 'Missing required columns: ' . implode(', ', $missing_headers) . '<br><br>';
                $error_msg .= 'Required columns: ' . implode(', ', $required_headers) . '<br>';
                $error_msg .= 'Found columns: ' . implode(', ', array_map('strtolower', $headers));
                $this->session->set_flashdata('error', $error_msg);
                redirect('vendor_payments');
                return;
            }
            
            // Get import options
            $skip_duplicates = $this->input->post('skip_duplicates') == '1';
            $set_pending = $this->input->post('set_pending') == '1';
            
            $imported = 0;
            $skipped = 0;
            $errors = array();
            $row_number = 1; // Start from 1 since we already read headers
            
            // Process each row
            while (($row = fgetcsv($handle)) !== FALSE) {
                $row_number++;
                
                try {
                    // Map data
                    $date = trim($row[$header_map['date']]);
                    $vendor_name = trim($row[$header_map['vendor_name']]);
                    $sender = trim($row[$header_map['sender']]);
                    $receiver = trim($row[$header_map['receiver']]);
                    $mode = trim($row[$header_map['mode']]);
                    $amount = trim($row[$header_map['amount']]);
                    $status = trim($row[$header_map['status']]);
                    $notes = isset($header_map['notes']) ? trim($row[$header_map['notes']]) : '';
                    
                    // Validate data
                    if (empty($date) || empty($vendor_name) || empty($sender) || empty($receiver) || empty($mode) || empty($amount)) {
                        $errors[] = "Row $row_number: Missing required data";
                        continue;
                    }
                    
                    // Validate date format
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                        $errors[] = "Row $row_number: Invalid date format (use YYYY-MM-DD)";
                        continue;
                    }
                    
                    // Validate payment mode
                    $valid_modes = ['Zelle', 'Cash App', 'Venmo'];
                    if (!in_array($mode, $valid_modes)) {
                        $errors[] = "Row $row_number: Invalid payment mode (must be: " . implode(', ', $valid_modes) . ")";
                        continue;
                    }
                    
                    // Validate amount
                    $amount = str_replace(['$', ','], '', $amount);
                    if (!is_numeric($amount) || $amount <= 0) {
                        $errors[] = "Row $row_number: Invalid amount";
                        continue;
                    }
                    
                    // Validate status
                    $valid_statuses = ['Pending', 'Approved', 'Declined'];
                    if (!in_array($status, $valid_statuses)) {
                        $errors[] = "Row $row_number: Invalid status (must be: " . implode(', ', $valid_statuses) . ")";
                        continue;
                    }
                    
                    // Get vendor ID
                    $vendor = $this->Vendor_model->get_vendor_by_name($vendor_name);
                    if (!$vendor) {
                        $errors[] = "Row $row_number: Vendor '$vendor_name' not found in system";
                        continue;
                    }
                    
                    // Check for duplicates if enabled
                    if ($skip_duplicates) {
                        $existing = $this->Vendor_payments_model->check_duplicate_payment($date, $sender, $receiver, $amount, $vendor->id);
                        if ($existing) {
                            $skipped++;
                            continue;
                        }
                    }
                    
                    // Prepare payment data
                    $payment_data = array(
                        'date' => $date,
                        'vendor_id' => $vendor->id,
                        'sender' => $sender,
                        'receiver' => $receiver,
                        'mode' => $mode,
                        'amount' => $amount,
                        'status' => $set_pending ? 'Pending' : $status,
                        'created_by' => $this->session->userdata('user_id')
                    );
                    
                    // Add payment
                    if ($this->Vendor_payments_model->add_payment($payment_data)) {
                        $imported++;
                    } else {
                        $errors[] = "Row $row_number: Failed to add payment";
                    }
                    
                } catch (Exception $e) {
                    $errors[] = "Row $row_number: " . $e->getMessage();
                }
            }
            
            fclose($handle);
            
            // Set flash message
            $message = "Import completed: $imported payments imported";
            if ($skipped > 0) {
                $message .= ", $skipped duplicates skipped";
            }
            if (!empty($errors)) {
                $message .= ", " . count($errors) . " errors";
                $this->session->set_flashdata('import_errors', $errors);
            }
            
            $this->session->set_flashdata('success', $message);
            redirect('vendor_payments');
        }
    }

    // Download CSV template
    public function download_template() {
        // Check if user is admin
        if ($this->session->userdata('role') !== 'admin') {
            show_404();
        }
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="vendor_payments_template.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, array('date', 'vendor_name', 'sender', 'receiver', 'mode', 'amount', 'status', 'notes'));
        
        // Add sample data
        fputcsv($output, array('2024-01-15', 'Sample Vendor', 'John Doe', 'Jane Smith', 'Zelle', '1500.00', 'Pending', 'Sample payment'));
        fputcsv($output, array('2024-01-16', 'Another Vendor', 'Alice Johnson', 'Bob Wilson', 'Cash App', '2500.50', 'Approved', ''));
        
        fclose($output);
        exit;
    }
} 