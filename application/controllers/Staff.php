<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in and is staff
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'staff') {
            redirect('auth/login');
        }
        
        // Load required models
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
        
        // Load helpers
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }
    
    public function index() {
        redirect('staff/dashboard');
    }
    
    /**
     * Set notification counts for sidebar
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
    
    public function dashboard() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Get statistics
        $data['total_users'] = $this->User_model->count_all_users();
        $data['total_orders'] = $this->Order_model->count_all_orders();
        $data['total_leads'] = $this->Lead_model->count_all_leads();
        $data['new_leads'] = $this->Lead_model->count_leads_by_status('new');
        $data['total_tickets'] = $this->Support_ticket_model->count_all_tickets();
        $data['pending_tickets'] = $this->Support_ticket_model->count_tickets_by_status('open');
        $data['total_recharge_requests'] = $this->Recharge_request_model->count_all_requests();
        $data['pending_recharge_requests'] = $this->Recharge_request_model->count_requests_by_status('pending');
        $data['total_reminders'] = $this->Customer_reminder_model->count_all_reminders();
        $data['active_reminders'] = $this->Customer_reminder_model->count_reminders_by_status('active');
        $data['overdue_reminders'] = $this->Customer_reminder_model->count_reminders_by_status('overdue');
        
        // Get wallet summary
        $data['wallet_summary'] = $this->Wallet_model->get_wallet_summary();
        
        // Get recent data
        $data['recent_orders'] = $this->Order_model->get_recent_orders(5);
        $data['recent_customers'] = $this->User_model->get_recent_customers(5);
        $data['recent_leads'] = $this->Lead_model->get_recent_leads(5);
        $data['recent_tickets'] = $this->Support_ticket_model->get_recent_tickets(5);
        $data['recent_reminders'] = $this->Customer_reminder_model->get_recent_reminders(5);
        
        $data['title'] = 'Dashboard';
        $data['active_page'] = 'dashboard';
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('shared/includes/sidebar');
        $this->load->view('staff/dashboard', $data);
        $this->load->view('admin/includes/footer');
    }
    
    public function customers() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        // Pagination - only show customers
        $config['base_url'] = base_url('staff/customers');
        $config['total_rows'] = $this->User_model->count_all_users('customer');
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['customers'] = $this->User_model->get_users($config['per_page'], $page, 'customer');
        $data['pagination'] = $this->pagination->create_links();
        
        $data['title'] = 'Customers';
        $data['active_page'] = 'customers';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/customers', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function view_customer($id) {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        $data['customer'] = $this->User_model->get_user_by_id($id);
        if (!$data['customer']) {
            show_404();
        }
        
        $data['customer_orders'] = $this->Order_model->get_orders_by_user($id);
        $data['customer_tickets'] = $this->Support_ticket_model->get_tickets_by_user($id);
        $data['customer_reminders'] = $this->Customer_reminder_model->get_reminders_by_customer($id);
        $this->load->model('Customer_address_model');
        $data['customer_addresses'] = $this->Customer_address_model->get_addresses_by_user_id($id);
        
        $data['title'] = 'View Customer';
        $data['active_page'] = 'customers';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/view_customer', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function orders() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        // Pagination
        $config['base_url'] = base_url('staff/orders');
        $config['total_rows'] = $this->Order_model->count_all_orders();
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['orders'] = $this->Order_model->get_orders($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        
        $data['title'] = 'Orders';
        $data['active_page'] = 'orders';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/orders', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function view_order($id) {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        $data['order'] = $this->Order_model->get_order_details($id);
        if (!$data['order']) {
            show_404();
        }
        
        $data['title'] = 'View Order';
        $data['active_page'] = 'orders';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/view_order', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function create_order() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        // Handle form submission
        if ($this->input->post()) {
            $this->form_validation->set_rules('user_id', 'Customer', 'required|numeric');
            $this->form_validation->set_rules('product_id', 'Product', 'required|numeric');
            $this->form_validation->set_rules('notes', 'Notes', 'max_length[500]');
            
            if ($this->form_validation->run() == TRUE) {
                // Get product details
                $product = $this->Product_model->get_product_by_id($this->input->post('product_id'));
                if (!$product) {
                    $this->session->set_flashdata('error', 'Selected product not found.');
                    redirect('staff/create_order');
                }
                
                // Create order data
                $order_data = array(
                    'user_id' => $this->input->post('user_id'),
                    'product_id' => $this->input->post('product_id'),
                    'total_amount' => $product->price,
                    'status' => 'pending',
                    'notes' => $this->input->post('notes'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                // Generate order number
                $order_data['order_number'] = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
                
                if ($this->Order_model->create_order($order_data)) {
                    $this->session->set_flashdata('success', 'Order created successfully.');
                    redirect('staff/orders');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create order.');
                }
            }
        }
        
        $data['customers'] = $this->User_model->get_all_customers();
        $data['products'] = $this->Product_model->get_all_products();
        
        $data['title'] = 'Create Order';
        $data['active_page'] = 'orders';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/create_order', $data);
        $this->load->view('staff/includes/footer');
    }
    

    
    public function leads() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        // Get lead statistics
        $data['total_leads'] = $this->Lead_model->count_all_leads();
        $data['new_leads'] = $this->Lead_model->count_leads_by_status('new');
        $data['contacted_leads'] = $this->Lead_model->count_leads_by_status('contacted');
        $data['converted_leads'] = $this->Lead_model->count_leads_by_status('converted');
        
        // Pagination
        $config['base_url'] = base_url('staff/leads');
        $config['total_rows'] = $this->Lead_model->count_all_leads();
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['leads'] = $this->Lead_model->get_leads($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        
        $data['title'] = 'Leads';
        $data['active_page'] = 'leads';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/leads', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function converted_leads() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        // Get converted lead statistics
        $data['total_converted'] = $this->Lead_model->count_all_leads(true);
        $data['converted_this_month'] = $this->Lead_model->count_leads_by_status('converted');
        $data['this_month_converted'] = $this->Lead_model->count_leads_by_status('converted');
        
        // Calculate conversion rate
        $total_leads = $this->Lead_model->count_all_leads();
        $data['conversion_rate'] = $total_leads > 0 ? round(($data['total_converted'] / $total_leads) * 100, 1) : 0;
        
        // Pagination
        $config['base_url'] = base_url('staff/converted_leads');
        $config['total_rows'] = $this->Lead_model->count_all_leads(true);
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['leads'] = $this->Lead_model->get_leads($config['per_page'], $page, true);
        $data['pagination'] = $this->pagination->create_links();
        
        $data['title'] = 'Converted Leads';
        $data['active_page'] = 'converted_leads';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/converted_leads', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function view_lead($id) {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        $data['lead'] = $this->Lead_model->get_lead_by_id($id);
        if (!$data['lead']) {
            show_404();
        }
        
        $data['title'] = 'View Lead';
        $data['active_page'] = 'leads';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/view_lead', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function add_lead() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[100]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[100]');
            $this->form_validation->set_rules('phone', 'Phone', 'required|max_length[20]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');
            $this->form_validation->set_rules('address_line1', 'Address Line 1', 'required|max_length[255]');
            $this->form_validation->set_rules('city', 'City', 'required|max_length[100]');
            $this->form_validation->set_rules('postal_code', 'Postal Code', 'required|max_length[20]');
            $this->form_validation->set_rules('state', 'State', 'required|max_length[100]');
            
            if ($this->form_validation->run() == TRUE) {
                $lead_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'address_line1' => $this->input->post('address_line1'),
                    'address_line2' => $this->input->post('address_line2'),
                    'city' => $this->input->post('city'),
                    'postal_code' => $this->input->post('postal_code'),
                    'state' => $this->input->post('state'),
                    'country' => $this->input->post('country') ?: 'USA',
                    'product_interest' => $this->input->post('product_interest'),
                    'payment_method' => $this->input->post('payment_method'),
                    'payment_details' => $this->input->post('payment_details'),
                    'status' => $this->input->post('status') ?: 'new',
                    'notes' => $this->input->post('notes'),
                    'source' => $this->input->post('source') ?: 'manual'
                );
                
                if ($this->Lead_model->add_lead($lead_data)) {
                    $this->session->set_flashdata('success', 'Lead added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add lead.');
                }
                redirect('staff/leads');
            }
        }
        
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        $data['title'] = 'Add Lead';
        $data['active_page'] = 'leads';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/add_lead', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function edit_lead($id) {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        $data['lead'] = $this->Lead_model->get_lead_by_id($id);
        if (!$data['lead']) {
            show_404();
        }
        
        $data['title'] = 'Edit Lead';
        $data['active_page'] = 'leads';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/edit_lead', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function tickets() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        // Get ticket statistics
        $data['open_tickets'] = $this->Support_ticket_model->count_tickets_by_status('open');
        $data['in_progress_tickets'] = $this->Support_ticket_model->count_tickets_by_status('in_progress');
        $data['closed_tickets'] = $this->Support_ticket_model->count_tickets_by_status('closed');
        $data['total_tickets'] = $this->Support_ticket_model->count_all_tickets();
        
        // Pagination
        $config['base_url'] = base_url('staff/tickets');
        $config['total_rows'] = $this->Support_ticket_model->count_all_tickets();
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['tickets'] = $this->Support_ticket_model->get_tickets($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        
        $data['title'] = 'Support Tickets';
        $data['active_page'] = 'tickets';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/tickets', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function view_ticket($id) {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        $data['ticket'] = $this->Support_ticket_model->get_ticket_by_id($id);
        if (!$data['ticket']) {
            show_404();
        }
        
        // Get customer information
        $customer = $this->User_model->get_user_by_id($data['ticket']->user_id);
        if ($customer) {
            $data['ticket']->customer_name = $customer->first_name . ' ' . $customer->last_name;
            $data['ticket']->customer_email = $customer->email;
        } else {
            $data['ticket']->customer_name = 'Unknown Customer';
            $data['ticket']->customer_email = 'No email available';
        }
        
        $data['replies'] = $this->Support_ticket_model->get_ticket_replies($id);
        
        // Format replies to include user information
        foreach ($data['replies'] as $reply) {
            if (isset($reply->user_id)) {
                $reply_user = $this->User_model->get_user_by_id($reply->user_id);
                if ($reply_user) {
                    $reply->replied_by = $reply_user->first_name . ' ' . $reply_user->last_name;
                } else {
                    $reply->replied_by = 'Unknown User';
                }
            } else {
                $reply->replied_by = 'Unknown User';
            }
            
            // Ensure reply message property exists
            if (!isset($reply->reply) && isset($reply->message)) {
                $reply->reply = $reply->message;
            }
        }
        
        $data['title'] = 'View Ticket';
        $data['active_page'] = 'tickets';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/view_ticket', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function create_ticket($customer_id = null) {
        // Get customer_id from URL parameter if not provided
        if (!$customer_id) {
            $customer_id = $this->input->get('customer_id');
        }
        
        // Get redirect URL for after form submission
        $redirect_url = $this->input->get('redirect_url');
        
        // If customer_id is provided, validate it
        $customer = null;
        if ($customer_id) {
            $customer = $this->User_model->get_user_by_id($customer_id);
            if (!$customer || $customer->role !== 'customer') {
                $this->session->set_flashdata('error', 'Invalid customer');
                redirect('staff/customers');
            }
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('customer_id', 'Customer', 'required|numeric');
            $this->form_validation->set_rules('subject', 'Subject', 'required|max_length[255]');
            $this->form_validation->set_rules('category', 'Category', 'required|in_list[general,order,payment,technical,refund,other]');
            $this->form_validation->set_rules('priority', 'Priority', 'required|in_list[low,medium,high,urgent]');
            $this->form_validation->set_rules('message', 'Message', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                // Generate ticket number
                $ticket_number = 'TKT-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
                
                $ticket_data = array(
                    'ticket_number' => $ticket_number,
                    'user_id' => $this->input->post('customer_id'),
                    'subject' => $this->input->post('subject'),
                    'category' => $this->input->post('category'),
                    'priority' => $this->input->post('priority'),
                    'status' => 'open',
                    'assigned_to' => $this->session->userdata('user_id'), // Assign to current staff
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                $ticket_id = $this->Support_ticket_model->create_ticket($ticket_data);
                
                if ($ticket_id) {
                    // Add the initial message as a reply
                    $reply_data = array(
                        'ticket_id' => $ticket_id,
                        'user_id' => $this->session->userdata('user_id'), // Staff who created the ticket
                        'message' => $this->input->post('message'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    
                    $this->Support_ticket_model->add_reply($reply_data);
                    
                    // Send notification to customer
                    $this->Notification_model->add_notification(
                        $this->input->post('customer_id'),
                        'customer',
                        'Support Ticket Created',
                        'A new support ticket #' . $ticket_number . ' has been created for you.',
                        'info',
                        'ticket'
                    );
                    
                    $this->session->set_flashdata('success', 'Support ticket created successfully');
                    
                    // Redirect to appropriate page
                    if ($redirect_url) {
                        redirect($redirect_url);
                    } else {
                        redirect('staff/tickets');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Failed to create support ticket');
                }
            }
        }
        
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        $data['customers'] = $this->User_model->get_all_customers();
        $data['customer'] = $customer;
        $data['redirect_url'] = $redirect_url;
        
        $data['title'] = 'Create Ticket';
        $data['active_page'] = 'tickets';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/create_ticket', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function recharge_requests() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        // Get recharge request statistics
        $data['pending_requests'] = $this->Recharge_request_model->count_requests_by_status('pending');
        $data['approved_requests'] = $this->Recharge_request_model->count_requests_by_status('approved');
        $data['rejected_requests'] = $this->Recharge_request_model->count_requests_by_status('rejected');
        $data['request_summary'] = $this->Recharge_request_model->get_request_summary();
        
        // Pagination
        $config['base_url'] = base_url('staff/recharge_requests');
        $config['total_rows'] = $this->Recharge_request_model->count_all_requests();
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['requests'] = $this->Recharge_request_model->get_requests($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        
        $data['title'] = 'Recharge Requests';
        $data['active_page'] = 'recharge_requests';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/recharge_requests', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function view_recharge($id) {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        $data['request'] = $this->Recharge_request_model->get_request_by_id($id);
        if (!$data['request']) {
            show_404();
        }
        
        $data['title'] = 'View Recharge Request';
        $data['active_page'] = 'recharge_requests';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/view_recharge', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function customer_reminders() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        // Get reminder statistics
        $data['active_reminders'] = $this->Customer_reminder_model->count_reminders_by_status('active');
        $data['completed_reminders'] = $this->Customer_reminder_model->count_reminders_by_status('completed');
        $data['archived_reminders'] = $this->Customer_reminder_model->count_reminders_by_status('archived');
        $data['overdue_reminders'] = $this->Customer_reminder_model->count_reminders_by_status('overdue');
        
        // Pagination
        $config['base_url'] = base_url('staff/customer_reminders');
        $config['total_rows'] = $this->Customer_reminder_model->count_all_reminders();
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['reminders'] = $this->Customer_reminder_model->get_reminders($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        
        $data['title'] = 'Customer Reminders';
        $data['active_page'] = 'customer_reminders';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/customer_reminders', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function add_customer_reminder($customer_id = null) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('customer_id', 'Customer', 'required|numeric');
            $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
            $this->form_validation->set_rules('content', 'Content', 'required');
            $this->form_validation->set_rules('priority', 'Priority', 'required|in_list[low,medium,high,urgent]');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,completed,archived]');
            
            if ($this->form_validation->run() == TRUE) {
                $reminder_data = array(
                    'customer_id' => $this->input->post('customer_id'),
                    'admin_id' => $this->session->userdata('user_id'),
                    'title' => $this->input->post('title'),
                    'content' => $this->input->post('content'),
                    'priority' => $this->input->post('priority'),
                    'status' => $this->input->post('status'),
                    'due_date' => $this->input->post('due_date') ?: NULL
                );
                
                if ($this->Customer_reminder_model->add_reminder($reminder_data)) {
                    $this->session->set_flashdata('success', 'Reminder added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add reminder.');
                }
                
                // Redirect based on where the reminder was added from
                $redirect_url = $this->input->post('redirect_url') ?: 'staff/customer_reminders';
                redirect($redirect_url);
            }
        }
        
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        $data['customers'] = $this->User_model->get_all_customers();
        $data['selected_customer_id'] = $customer_id;
        $data['redirect_url'] = $this->input->get('redirect_url');
        
        $data['title'] = 'Add Customer Reminder';
        $data['active_page'] = 'customer_reminders';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/add_customer_reminder', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function edit_customer_reminder($id) {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        $data['reminder'] = $this->Customer_reminder_model->get_reminder_by_id($id);
        if (!$data['reminder']) {
            show_404();
        }
        
        $data['customers'] = $this->User_model->get_all_customers();
        
        $data['title'] = 'Edit Customer Reminder';
        $data['active_page'] = 'customer_reminders';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/edit_customer_reminder', $data);
        $this->load->view('staff/includes/footer');
    }

    public function update_customer_reminder($id) {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        $reminder = $this->Customer_reminder_model->get_reminder_by_id($id);
        if (!$reminder) {
            show_404();
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('customer_id', 'Customer', 'required|numeric');
            $this->form_validation->set_rules('title', 'Title', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('content', 'Content', 'required|trim');
            $this->form_validation->set_rules('priority', 'Priority', 'required|in_list[low,medium,high,urgent]');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,completed,archived]');
            
            if ($this->form_validation->run() == TRUE) {
                $update_data = array(
                    'customer_id' => $this->input->post('customer_id'),
                    'title' => $this->input->post('title'),
                    'content' => $this->input->post('content'),
                    'priority' => $this->input->post('priority'),
                    'status' => $this->input->post('status'),
                    'due_date' => $this->input->post('due_date') ? $this->input->post('due_date') : NULL,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Customer_reminder_model->update_reminder($id, $update_data)) {
                    $this->session->set_flashdata('success', 'Customer reminder updated successfully.');
                    redirect('staff/customer_reminders');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update customer reminder.');
                }
            }
        }
        
        // If validation fails or no POST data, show the edit form
        $data['reminder'] = $reminder;
        $data['customers'] = $this->User_model->get_all_customers();
        $data['title'] = 'Edit Customer Reminder';
        $data['active_page'] = 'customer_reminders';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/edit_customer_reminder', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function notifications() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Pagination
        $config['base_url'] = base_url('staff/notifications');
        $config['total_rows'] = $this->Notification_model->count_all_notifications();
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['notifications'] = $this->Notification_model->get_all_notifications($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        
        $data['title'] = 'Notifications';
        $data['active_page'] = 'notifications';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/notifications', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function admin_messages() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Ensure the admin_messages table exists
        $this->Admin_message_model->create_table();
        
        $data['messages'] = $this->Admin_message_model->get_all_messages();
        
        // Ensure messages is always an array
        if (!is_array($data['messages'])) {
            $data['messages'] = [];
        }
        
        $data['title'] = 'Admin Messages';
        $data['active_page'] = 'admin_messages';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/admin_messages', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function view_customer_messages($customer_id) {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        $data['customer'] = $this->User_model->get_user_by_id($customer_id);
        if (!$data['customer']) {
            show_404();
        }
        
        $data['messages'] = $this->Admin_message_model->get_messages_by_customer($customer_id);
        
        $data['title'] = 'Customer Messages';
        $data['active_page'] = 'admin_messages';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/view_customer_messages', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function send_staff_reply() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $customer_id = $this->input->post('customer_id');
        $message = $this->input->post('message');
        
        if (!$customer_id || !$message) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }
        
        // Verify customer exists and is a customer
        $customer = $this->User_model->get_user_by_id($customer_id);
        if (!$customer || $customer->role !== 'customer') {
            echo json_encode(['success' => false, 'message' => 'Invalid customer']);
            return;
        }
        
        $message_data = array(
            'customer_id' => $customer_id,
            'admin_id' => $this->session->userdata('user_id'),
            'message' => $message
        );
        
        if ($this->Admin_message_model->create_message($message_data)) {
            // Send notification to customer
            $this->Notification_model->add_notification(
                $customer_id,
                'customer',
                'New Message from Staff',
                'You have received a new message from our staff team.',
                'info',
                'message'
            );
            
            echo json_encode(['success' => true, 'message_id' => $this->db->insert_id()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send message']);
        }
    }
    
    public function start_new_message($customer_id = null) {
        // Get customer_id from URL parameter if not provided
        if (!$customer_id) {
            $customer_id = $this->input->get('customer_id');
        }
        
        // If customer_id is provided, validate it
        $customer = null;
        if ($customer_id) {
            $customer = $this->User_model->get_user_by_id($customer_id);
            if (!$customer || $customer->role !== 'customer') {
                $this->session->set_flashdata('error', 'Invalid customer');
                redirect('staff/admin_messages');
            }
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('customer_id', 'Customer', 'required|numeric');
            $this->form_validation->set_rules('subject', 'Subject', 'required|max_length[255]');
            $this->form_validation->set_rules('message', 'Message', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $message_data = array(
                    'customer_id' => $this->input->post('customer_id'),
                    'admin_id' => $this->session->userdata('user_id'),
                    'subject' => $this->input->post('subject'),
                    'message' => $this->input->post('message'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Admin_message_model->create_message($message_data)) {
                    // Send notification to customer
                    $this->Notification_model->add_notification(
                        $this->input->post('customer_id'),
                        'customer',
                        'New Message from Staff',
                        'You have received a new message from our staff team.',
                        'info',
                        'message'
                    );
                    
                    $this->session->set_flashdata('success', 'Message sent successfully');
                    redirect('staff/view_customer_messages/' . $this->input->post('customer_id'));
                } else {
                    $this->session->set_flashdata('error', 'Failed to send message');
                }
            }
        }
        
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        $data['customers'] = $this->User_model->get_all_customers();
        $data['customer'] = $customer;
        
        $data['title'] = 'Start New Message';
        $data['active_page'] = 'admin_messages';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/start_new_message', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function wallets() {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        // Get customer wallet statistics
        $data['wallet_summary'] = $this->Wallet_model->get_customer_wallet_summary();
        $data['total_wallets'] = $this->Wallet_model->count_customer_wallets();
        
        // Pagination
        $config['base_url'] = base_url('staff/wallets');
        $config['total_rows'] = $this->Wallet_model->count_customer_wallets();
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['wallets'] = $this->Wallet_model->get_customer_wallets($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        
        $data['title'] = 'Wallets';
        $data['active_page'] = 'wallets';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/wallets', $data);
        $this->load->view('staff/includes/footer');
    }
    
    public function view_wallet($id) {
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        // Set notification counts for sidebar
        $this->set_notification_counts($data);
        
        $data['wallet'] = $this->Wallet_model->get_wallet_by_id($id);
        if (!$data['wallet']) {
            show_404();
        }
        
        // Security check: Ensure staff can only view customer wallets
        if ($data['wallet']->role !== 'customer') {
            show_404();
        }
        
        $data['transactions'] = $this->Wallet_model->get_wallet_transactions($id);
        
        $data['title'] = 'View Customer Wallet';
        $data['active_page'] = 'wallets';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/view_wallet', $data);
        $this->load->view('staff/includes/footer');
    }
    


    public function add_customer() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[100]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[100]');
            $this->form_validation->set_rules('username', 'Username', 'required|max_length[50]|is_unique[users.username]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]|is_unique[users.email]');
            $this->form_validation->set_rules('phone', 'Phone', 'max_length[20]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
            
            if ($this->form_validation->run() == TRUE) {
                $user_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'username' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'role' => 'customer',
                    'status' => $this->input->post('status'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->User_model->create_user($user_data)) {
                    $this->session->set_flashdata('success', 'Customer created successfully.');
                    redirect('staff/customers');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create customer.');
                }
            }
        }
        
        $data['user'] = [
            'first_name' => $this->session->userdata('first_name'),
            'last_name' => $this->session->userdata('last_name'),
            'role' => $this->session->userdata('role')
        ];
        
        $data['title'] = 'Add Customer';
        $data['active_page'] = 'customers';
        
        $this->load->view('staff/includes/header', $data);
        $this->load->view('staff/add_customer', $data);
        $this->load->view('staff/includes/footer');
    }

    public function update_order_status($id, $status) {
        $order = $this->Order_model->get_order_by_id($id);
        if ($order) {
            $this->Order_model->update_order($id, array('status' => $status));
            
            // Add notification for customer
            $this->Notification_model->add_notification(
                $order->user_id,
                'customer',
                'Order Status Updated',
                'Your order #' . $order->order_number . ' status has been updated to "' . ucfirst($status) . '".',
                'info',
                'order'
            );
            
            $this->session->set_flashdata('success', 'Order status updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Order not found.');
        }
        redirect('staff/orders');
    }

    public function mark_notification_read() {
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success' => false, 'message' => 'Invalid request'));
            return;
        }

        $notification_id = $this->input->post('notification_id');
        $user_id = $this->session->userdata('user_id');
        
        if (!$notification_id || !$user_id) {
            echo json_encode(array('success' => false, 'message' => 'Missing required parameters'));
            return;
        }
        
        // Verify the notification belongs to this user
        $notification = $this->db->where('id', $notification_id)
                                 ->where('user_id', $user_id)
                                 ->where('user_type', 'staff')
                                 ->get('notifications')
                                 ->row();
        
        if ($notification) {
            if ($this->Notification_model->mark_as_read($notification_id)) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Failed to update notification'));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Notification not found'));
        }
    }

    public function mark_all_notifications_read() {
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success' => false, 'message' => 'Invalid request'));
            return;
        }

        $user_id = $this->session->userdata('user_id');
        
        if (!$user_id) {
            echo json_encode(array('success' => false, 'message' => 'User not authenticated'));
            return;
        }
        
        // Mark all unread notifications for this user as read
        $this->db->where('user_id', $user_id)
                 ->where('user_type', 'staff')
                 ->where('is_read', FALSE)
                 ->update('notifications', array('is_read' => TRUE));
        
        $affected_rows = $this->db->affected_rows();
        
        if ($affected_rows > 0) {
            echo json_encode(array('success' => true, 'affected_rows' => $affected_rows));
        } else {
            echo json_encode(array('success' => false, 'message' => 'No unread notifications found'));
        }
    }

    public function add_ticket_reply() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('ticket_id', 'Ticket ID', 'required|numeric');
            $this->form_validation->set_rules('reply', 'Reply', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $reply_data = array(
                    'ticket_id' => $this->input->post('ticket_id'),
                    'user_id' => $this->session->userdata('user_id'),
                    'message' => $this->input->post('reply'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Support_ticket_model->add_reply($reply_data)) {
                    // Update ticket status to in_progress if it's open
                    $ticket = $this->Support_ticket_model->get_ticket_by_id($this->input->post('ticket_id'));
                    if ($ticket && $ticket->status === 'open') {
                        $this->Support_ticket_model->update_ticket($this->input->post('ticket_id'), array('status' => 'in_progress'));
                    }
                    
                    // Send notification to customer
                    $this->Notification_model->add_notification(
                        $ticket->user_id,
                        'customer',
                        'Ticket Reply',
                        'You have received a reply to your support ticket.',
                        'info',
                        'ticket'
                    );
                    
                    $this->session->set_flashdata('success', 'Reply added successfully');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add reply');
                }
                
                redirect('staff/view_ticket/' . $this->input->post('ticket_id'));
            }
        }
        
        redirect('staff/tickets');
    }

    public function update_ticket_status() {
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success' => false, 'message' => 'Invalid request'));
            return;
        }
        
        $ticket_id = $this->input->post('ticket_id');
        $status = $this->input->post('status');
        
        if (!$ticket_id || !$status) {
            echo json_encode(array('success' => false, 'message' => 'Missing required parameters'));
            return;
        }
        
        if ($this->Support_ticket_model->update_ticket_status($ticket_id, $status)) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to update ticket status'));
        }
    }
    
    public function update_lead_status() {
        if (!$this->input->is_ajax_request()) {
            echo json_encode(array('success' => false, 'message' => 'Invalid request'));
            return;
        }
        
        $lead_id = $this->input->post('lead_id');
        $status = $this->input->post('status');
        
        // Debug logging
        error_log("Staff update_lead_status called - Lead ID: " . $lead_id . ", Status: " . $status);
        
        if (!$lead_id || !$status) {
            echo json_encode(array('success' => false, 'message' => 'Missing required parameters'));
            return;
        }
        
        // Validate status
        $valid_statuses = array('new', 'contacted', 'qualified', 'converted', 'lost');
        if (!in_array($status, $valid_statuses)) {
            echo json_encode(array('success' => false, 'message' => 'Invalid status value'));
            return;
        }
        
        $result = $this->Lead_model->update_lead_status($lead_id, $status);
        error_log("Lead model update result: " . ($result ? 'true' : 'false'));
        
        if ($result) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to update lead status'));
        }
    }
    
    public function convert_lead_to_customer($lead_id) {
        // Get the lead
        $lead = $this->Lead_model->get_lead_by_id($lead_id);
        if (!$lead) {
            $this->session->set_flashdata('error', 'Lead not found.');
            redirect('staff/leads');
        }
        
        // Check if lead is already converted
        if ($lead->status === 'converted') {
            $this->session->set_flashdata('error', 'Lead is already converted.');
            redirect('staff/view_lead/' . $lead_id);
        }
        
        // Check if user with this email already exists
        $existing_user = $this->User_model->get_user_by_email($lead->email);
        if ($existing_user) {
            $this->session->set_flashdata('error', 'A customer with this email already exists.');
            redirect('staff/view_lead/' . $lead_id);
        }
        
        // Create customer account
        $user_data = array(
            'first_name' => $lead->first_name,
            'last_name' => $lead->last_name,
            'username' => $lead->email, // Use email as username
            'email' => $lead->email,
            'phone' => $lead->phone,
            'password' => password_hash('welcome123', PASSWORD_DEFAULT), // Default password
            'role' => 'customer',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        );
        
        $user_id = $this->User_model->create_user($user_data);
        
        if ($user_id) {
            // Create customer address
            $this->load->model('Customer_address_model');
            $address_data = array(
                'user_id' => $user_id,
                'address_line1' => $lead->address_line1,
                'address_line2' => $lead->address_line2,
                'city' => $lead->city,
                'state' => $lead->state,
                'postal_code' => $lead->postal_code,
                'country' => $lead->country,
                'is_default' => 1,
                'created_at' => date('Y-m-d H:i:s')
            );
            
            $this->Customer_address_model->create_address($address_data);
            
            // Update lead status to converted
            $this->Lead_model->convert_lead_to_customer($lead_id, $user_id);
            
            // Create wallet for the new customer
            $this->load->model('Wallet_model');
            $wallet_data = array(
                'user_id' => $user_id,
                'balance' => 0.00,
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->Wallet_model->create_wallet($wallet_data);
            
            $this->session->set_flashdata('success', 'Lead converted to customer successfully. Customer ID: ' . $user_id);
            redirect('staff/view_customer/' . $user_id);
        } else {
            $this->session->set_flashdata('error', 'Failed to create customer account.');
            redirect('staff/view_lead/' . $lead_id);
        }
    }

    // Reminder management methods
    public function mark_reminder_complete() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $reminder_id = $this->input->post('reminder_id');

        if (!$reminder_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        // Update reminder as completed
        $result = $this->db->where('id', $reminder_id)
                           ->where('status', 'active')
                           ->update('customer_reminders', [
                               'status' => 'completed',
                               'completed_at' => date('Y-m-d H:i:s')
                           ]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update reminder']);
        }
    }

    public function get_reminders() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // Get active reminders with customer information
        $reminders = $this->db->select('customer_reminders.*, 
                                       customer.first_name as customer_first_name, 
                                       customer.last_name as customer_last_name')
                              ->from('customer_reminders')
                              ->join('users as customer', 'customer.id = customer_reminders.customer_id')
                              ->where('customer_reminders.status', 'active')
                              ->order_by('customer_reminders.due_date', 'ASC')
                              ->limit(10)
                              ->get()
                              ->result();

        echo json_encode(['success' => true, 'reminders' => $reminders]);
    }
} 