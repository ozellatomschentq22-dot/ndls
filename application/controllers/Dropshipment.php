<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dropshipment extends CI_Controller {
    
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
        $this->load->model('Dropshipment_model');
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
        $data['title'] = 'Drop Shipment Orders';
        $data['active_page'] = 'dropshipment';
        
        // Set notification counts for staff sidebar
        if ($this->session->userdata('role') === 'staff') {
            $this->set_notification_counts($data);
        }
        
        // Get filters from URL parameters
        $filters = array(
            'status' => $this->input->get('status'),
            'center' => $this->input->get('center'),
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to'),
            'search' => $this->input->get('search')
        );
        
        // Pagination configuration
        $config['base_url'] = base_url('dropshipment');
        $config['total_rows'] = $this->Dropshipment_model->count_all_orders($filters);
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
        
        // Get orders data
        $data['orders'] = $this->Dropshipment_model->get_all_orders($config['per_page'], $offset, $filters);
        $data['order_summary'] = $this->Dropshipment_model->get_order_summary($filters);
        $data['center_summary'] = $this->Dropshipment_model->get_center_summary($filters);
        $data['filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        
        // Load additional data for filters
        $data['centers'] = $this->Dropshipment_model->get_all_centers();
        $data['statuses'] = $this->Dropshipment_model->get_order_statuses();
        
        // Load views based on user role
        if ($this->session->userdata('role') === 'admin') {
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/includes/sidebar');
            $this->load->view('dropshipment/list', $data);
            $this->load->view('admin/includes/footer');
        } else {
            $this->load->view('staff/includes/header', $data);
            $this->load->view('staff/includes/sidebar');
            $this->load->view('dropshipment/list', $data);
            $this->load->view('staff/includes/footer');
        }
    }
    
    // Add new order
    public function add() {
        $data['title'] = 'Add Drop Shipment Order';
        $data['active_page'] = 'dropshipment';
        $data['centers'] = $this->Dropshipment_model->get_all_centers();
        
        // Set notification counts for staff sidebar
        if ($this->session->userdata('role') === 'staff') {
            $this->set_notification_counts($data);
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('customer_name', 'Customer Name', 'required|trim');
            $this->form_validation->set_rules('customer_address', 'Customer Address', 'trim');
            $this->form_validation->set_rules('product_name', 'Product Name', 'required|trim');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|trim');
            $this->form_validation->set_rules('center', 'Center', 'required|trim');
            
            // Custom validation for quantity
            $quantity = $this->input->post('quantity');
            if ($quantity === 'custom') {
                $this->form_validation->set_rules('custom_quantity', 'Custom Quantity', 'required|numeric|greater_than[0]');
            } else {
                // Validate that quantity is one of the predefined options
                $valid_quantities = array('30', '60', '90', '180');
                if (!in_array($quantity, $valid_quantities)) {
                    $this->form_validation->set_message('quantity', 'Please select a valid quantity.');
                    $this->form_validation->set_rules('quantity', 'Quantity', 'required|in_list[30,60,90,180,custom]');
                }
            }
            
            if ($this->form_validation->run() == TRUE) {
                // Handle custom quantity
                $quantity = $this->input->post('quantity');
                if ($quantity === 'custom') {
                    $quantity = $this->input->post('custom_quantity');
                    // Validate custom quantity
                    if (!$quantity || !is_numeric($quantity) || $quantity < 1) {
                        $this->session->set_flashdata('error', 'Please enter a valid custom quantity.');
                        redirect('dropshipment/add');
                        return;
                    }
                }
                
                $order_data = array(
                    'customer_name' => $this->input->post('customer_name'),
                    'customer_address' => $this->input->post('customer_address'),
                    'product_name' => $this->input->post('product_name'),
                    'quantity' => $quantity,
                    'center' => $this->input->post('center'),
                    'notes' => $this->input->post('notes'),
                    'created_by' => $this->session->userdata('user_id')
                );
                
                $order_id = $this->Dropshipment_model->add_order($order_data);
                
                if ($order_id) {
                    // Get the order details to show in success message
                    $order = $this->Dropshipment_model->get_order_by_id($order_id);
                    $this->session->set_flashdata('success', 'Order number <strong>' . $order->order_number . '</strong> for customer <strong>' . $order->customer_name . '</strong> has been added successfully.');
                    redirect('dropshipment');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add drop shipment order.');
                }
            }
        }
        
        // Load views based on user role
        if ($this->session->userdata('role') === 'admin') {
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/includes/sidebar');
            $this->load->view('dropshipment/form', $data);
            $this->load->view('admin/includes/footer');
        } else {
            $this->load->view('staff/includes/header', $data);
            $this->load->view('staff/includes/sidebar');
            $this->load->view('dropshipment/form', $data);
            $this->load->view('staff/includes/footer');
        }
    }
    
    // View order details
    public function view($id) {
        $data['title'] = 'View Drop Shipment Order';
        $data['active_page'] = 'dropshipment';
        $data['order'] = $this->Dropshipment_model->get_order_by_id($id);
        
        // Set notification counts for staff sidebar
        if ($this->session->userdata('role') === 'staff') {
            $this->set_notification_counts($data);
        }
        
        if (!$data['order']) {
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('dropshipment');
        }
        
        // Load additional data
        $data['statuses'] = $this->Dropshipment_model->get_order_statuses();
        $data['carriers'] = $this->Dropshipment_model->get_tracking_carriers();
        
        // Load views based on user role
        if ($this->session->userdata('role') === 'admin') {
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/includes/sidebar');
            $this->load->view('dropshipment/view', $data);
            $this->load->view('admin/includes/footer');
        } else {
            $this->load->view('staff/includes/header', $data);
            $this->load->view('staff/includes/sidebar');
            $this->load->view('dropshipment/view', $data);
            $this->load->view('staff/includes/footer');
        }
    }
    
    // Process order (admin only)
    public function process($id) {
        if ($this->session->userdata('role') !== 'admin') {
            show_404();
        }
        
        $order = $this->Dropshipment_model->get_order_by_id($id);
        if (!$order) {
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('dropshipment');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('price', 'Price', 'required|numeric');
            
            if ($this->form_validation->run() == TRUE) {
                $price = $this->input->post('price');
                $processed_by = $this->session->userdata('user_id');
                
                if ($this->Dropshipment_model->process_order($id, $price, $processed_by)) {
                    $this->session->set_flashdata('success', 'Order processed successfully.');
                    redirect('dropshipment');
                } else {
                    $this->session->set_flashdata('error', 'Failed to process order.');
                }
            }
        }
        
        $data['title'] = 'Process Order';
        $data['active_page'] = 'dropshipment';
        $data['order'] = $order;
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('dropshipment/process', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Update tracking info (AJAX)
    public function update_tracking() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $order_id = $this->input->post('order_id');
        $tracking_number = $this->input->post('tracking_number');
        $tracking_carrier = $this->input->post('tracking_carrier');
        $tracking_url = $this->input->post('tracking_url');
        
        if (!$order_id) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Order ID is required']));
            return;
        }
        
        $tracking_data = array(
            'tracking_number' => $tracking_number,
            'tracking_carrier' => $tracking_carrier,
            'tracking_url' => $tracking_url
        );
        
        if ($this->Dropshipment_model->update_tracking($order_id, $tracking_data)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => 'Tracking information updated successfully']));
        } else {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Failed to update tracking information']));
        }
    }
    
    // Update order status (AJAX)
    public function update_status() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $order_id = $this->input->post('order_id');
        $status = $this->input->post('status');
        
        if (!$order_id || !$status) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Order ID and status are required']));
            return;
        }
        
        $valid_statuses = $this->Dropshipment_model->get_order_statuses();
        if (!in_array($status, $valid_statuses)) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Invalid status']));
            return;
        }
        
        if ($this->Dropshipment_model->update_status($order_id, $status)) {
            // Set success message for when user returns to list
            $this->session->set_flashdata('success', 'Order status updated to <strong>' . $status . '</strong> successfully.');
            
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => 'Order status updated successfully']));
        } else {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Failed to update order status']));
        }
    }
    
    // Delete order (admin only)
    public function delete($id) {
        if ($this->session->userdata('role') !== 'admin') {
            show_404();
        }
        
        $order = $this->Dropshipment_model->get_order_by_id($id);
        
        if (!$order) {
            $this->session->set_flashdata('error', 'Order not found.');
        } else {
            if ($this->Dropshipment_model->delete_order($id)) {
                $this->session->set_flashdata('success', 'Drop shipment order deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete drop shipment order.');
            }
        }
        
        redirect('dropshipment');
    }
} 