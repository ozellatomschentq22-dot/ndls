<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in and is admin
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') !== 'admin') {
            redirect('auth/login');
        }
        
        // Load models
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $this->load->model('Order_model');
        $this->load->model('Wallet_model');
        $this->load->model('Payment_method_model');
        $this->load->model('Recharge_request_model');
        $this->load->model('Support_ticket_model');
        $this->load->model('Customer_address_model');
        $this->load->model('Admin_message_model');
        $this->load->model('Notification_model');
        $this->load->model('Lead_model');
        $this->load->model('Customer_reminder_model');
        
        // Load form validation library
        $this->load->library('form_validation');
    }

    public function index() {
        redirect('admin/dashboard');
    }

    public function dashboard() {
        $data['title'] = 'Admin Dashboard';
        $data['user'] = $this->session->userdata();
        
        // Get dashboard statistics
        $data['total_users'] = $this->User_model->count_users_by_role('customer');
        $data['total_staff'] = $this->User_model->count_users_by_role('staff');
        $data['wallet_summary'] = $this->Wallet_model->get_wallet_summary();
        
        // Get additional statistics
        $data['total_orders'] = $this->Order_model->count_all_orders();
        $data['total_products'] = $this->Product_model->count_all_products();
        $data['total_leads'] = $this->Lead_model->count_all_leads();
        $data['new_leads'] = $this->Lead_model->count_leads_by_status('new');
        $data['total_tickets'] = $this->Support_ticket_model->count_all_tickets();
        $data['pending_tickets'] = $this->Support_ticket_model->count_tickets_by_status('open');
        $data['total_recharge_requests'] = $this->Recharge_request_model->count_all_requests();
        $data['pending_recharge_requests'] = $this->Recharge_request_model->count_requests_by_status('pending');
        
        // Get reminder statistics
        $reminder_stats = $this->Customer_reminder_model->get_reminder_stats();
        $data['total_reminders'] = $reminder_stats['total'];
        $data['active_reminders'] = isset($reminder_stats['active']) ? $reminder_stats['active'] : 0;
        $data['overdue_reminders'] = $reminder_stats['overdue'];
        $data['due_today_reminders'] = $reminder_stats['due_today'];
        
        // Get recent data
        $data['recent_orders'] = $this->Order_model->get_recent_orders(5);
        $data['recent_customers'] = $this->User_model->get_recent_customers(5);
        $data['recent_leads'] = $this->Lead_model->get_recent_leads(5);
        $data['recent_tickets'] = $this->Support_ticket_model->get_recent_tickets(5);
        $data['recent_reminders'] = $this->Customer_reminder_model->get_all_reminders(5, 0, 'active');
        
        $this->load->view('admin/dashboard', $data);
    }

    // Users Management
    public function users() {
        $data['title'] = 'Admin & Staff Management';
        $data['users'] = $this->User_model->get_users_by_role(['admin', 'staff']);
        $data['total_users'] = count($data['users']);
        $data['active_users'] = count(array_filter($data['users'], function($user) {
            return isset($user->status) && $user->status === 'active';
        }));
        $data['inactive_users'] = $data['total_users'] - $data['active_users'];
        
        $this->load->view('admin/users', $data);
    }

    public function customers() {
        $data['title'] = 'Customer Management';
        $data['customers'] = $this->User_model->get_all_users_by_role('customer');
        $data['total_customers'] = count($data['customers']);
        $data['active_customers'] = count(array_filter($data['customers'], function($customer) {
            return isset($customer->status) && $customer->status === 'active';
        }));
        $data['inactive_customers'] = $data['total_customers'] - $data['active_customers'];
        
        $this->load->view('admin/customers', $data);
    }

    public function add_user() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[100]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('username', 'Username', 'required|max_length[50]|is_unique[users.username]|alpha_dash');
            $this->form_validation->set_rules('phone', 'Phone', 'max_length[20]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,staff]');
            
            if ($this->form_validation->run() == TRUE) {
                $user_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'username' => $this->input->post('username'),
                    'phone' => $this->input->post('phone'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'role' => $this->input->post('role'),
                    'status' => $this->input->post('status') ? 'active' : 'inactive',
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->User_model->create_user($user_data)) {
                    $this->session->set_flashdata('success', 'User created successfully.');
                    redirect('admin/users');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create user.');
                }
            }
        }
        
        $data['title'] = 'Add New User';
        $this->load->view('admin/add_user', $data);
    }

    public function edit_user($id) {
        $user = $this->User_model->get_user_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[100]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('phone', 'Phone', 'max_length[20]');
            
            if ($this->form_validation->run() == TRUE) {
                $update_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'role' => $this->input->post('role'),
                    'status' => $this->input->post('status')
                );
                
                if ($this->User_model->update_user($id, $update_data)) {
                    $this->session->set_flashdata('success', 'User updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update user.');
                }
                redirect('admin/users');
            }
        }
        
        $data['title'] = 'Edit User';
        $data['user'] = $user;
        $this->load->view('admin/edit_user', $data);
    }

    public function toggle_user($id) {
        $user = $this->User_model->get_user_by_id($id);
        if ($user && $user->role !== 'admin') {
            $new_status = $user->status === 'active' ? 'inactive' : 'active';
            $this->User_model->update_user($id, array('status' => $new_status));
            $this->session->set_flashdata('success', 'User status updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Cannot modify admin user or user not found.');
        }
        redirect('admin/users');
    }

    public function delete_user($id) {
        $user = $this->User_model->get_user_by_id($id);
        if ($user && $user->role !== 'admin') {
            if ($this->User_model->delete_user($id)) {
                $this->session->set_flashdata('success', 'User deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete user.');
            }
        } else {
            $this->session->set_flashdata('error', 'Cannot delete admin user or user not found.');
        }
        redirect('admin/users');
    }

    // Wallet Management
    public function wallets() {
        $data['title'] = 'Manage Wallets';
        $data['wallets'] = $this->Wallet_model->get_all_wallets();
        
        $this->load->view('admin/wallets', $data);
    }

    public function credit_wallet($user_id = null) {
        // If no user_id provided, redirect to wallets page
        if ($user_id === null) {
            $this->session->set_flashdata('error', 'Please select a user to credit their wallet.');
            redirect('admin/wallets');
        }
        
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/wallets');
        }
        
        $wallet = $this->Wallet_model->get_wallet_by_user_id($user_id);
        if (!$wallet) {
            // Create wallet if it doesn't exist
            $this->Wallet_model->create_wallet($user_id);
            $wallet = $this->Wallet_model->get_wallet_by_user_id($user_id);
        }
        
        if ($this->input->post()) {
            $amount = $this->input->post('amount');
            $reason = $this->input->post('reason');
            $notes = $this->input->post('notes');
            
            $description = ucwords(str_replace('_', ' ', $reason));
            if ($notes) {
                $description .= ': ' . $notes;
            }
            
            if ($this->Wallet_model->credit_wallet($user_id, $amount, $description, null, 'manual')) {
                $this->session->set_flashdata('success', 'Wallet credited successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to credit wallet.');
            }
            redirect('admin/wallets');
        }
        
        $data['title'] = 'Credit Wallet';
        $data['user'] = $user;
        $data['wallet'] = $wallet;
        $data['recent_transactions'] = $this->Wallet_model->get_transactions($user_id, 5);
        
        $this->load->view('admin/credit_wallet', $data);
    }

    public function debit_wallet($user_id = null) {
        // If no user_id provided, redirect to wallets page
        if ($user_id === null) {
            $this->session->set_flashdata('error', 'Please select a user to debit their wallet.');
            redirect('admin/wallets');
        }
        
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/wallets');
        }
        
        $wallet = $this->Wallet_model->get_wallet_by_user_id($user_id);
        if (!$wallet) {
            // Create wallet if it doesn't exist
            $this->Wallet_model->create_wallet($user_id);
            $wallet = $this->Wallet_model->get_wallet_by_user_id($user_id);
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('description', 'Description', 'required|max_length[255]');
            
            if ($this->form_validation->run() == TRUE) {
                $amount = $this->input->post('amount');
                $description = $this->input->post('description');
                $reference_type = $this->input->post('reference_type');
                $reference_id = $this->input->post('reference_id');
                
                if ($this->Wallet_model->debit_wallet($user_id, $amount, $description, $reference_id, $reference_type)) {
                    $this->session->set_flashdata('success', 'Wallet debited successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to debit wallet.');
                }
                redirect('admin/wallets');
            }
        }
        
        $data['title'] = 'Debit Wallet';
        $data['user'] = $user;
        $data['wallet'] = $wallet;
        $data['recent_transactions'] = $this->Wallet_model->get_transactions($user_id, 5);
        
        $this->load->view('admin/debit_wallet', $data);
    }

    public function wallet_transactions($user_id = null) {
        // If no user_id provided, redirect to wallets page
        if ($user_id === null) {
            $this->session->set_flashdata('error', 'Please select a user to view their wallet transactions.');
            redirect('admin/wallets');
        }
        
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/wallets');
        }
        
        $wallet = $this->Wallet_model->get_wallet_by_user_id($user_id);
        if (!$wallet) {
            // Create wallet if it doesn't exist
            $this->Wallet_model->create_wallet($user_id);
            $wallet = $this->Wallet_model->get_wallet_by_user_id($user_id);
        }
        
        $data['title'] = 'Wallet Transactions';
        $data['user'] = $user;
        $data['wallet'] = $wallet;
        $data['transactions'] = $this->Wallet_model->get_transactions($user_id);
        
        $this->load->view('admin/wallet_transactions', $data);
    }

    // Payment Methods Management
    public function payment_methods() {
        $data['title'] = 'Manage Payment Methods';
        $data['payment_methods'] = $this->Payment_method_model->get_all_methods(false);
        
        $this->load->view('admin/payment_methods', $data);
    }

    public function add_payment_method() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('display_name', 'Display Name', 'required|max_length[100]');
            $this->form_validation->set_rules('method_key', 'Payment Mode', 'required|max_length[50]');
            $this->form_validation->set_rules('icon', 'Icon', 'required|max_length[100]');
            $this->form_validation->set_rules('title', 'Title', 'max_length[255]');
            $this->form_validation->set_rules('description', 'Description', 'max_length[500]');
            $this->form_validation->set_rules('instructions', 'Instructions', 'max_length[500]');
            $this->form_validation->set_rules('sort_order', 'Sort Order', 'integer');
            
            if ($this->form_validation->run() == TRUE) {
                $method_data = array(
                    'display_name' => $this->input->post('display_name'),
                    'method_key' => $this->input->post('method_key'),
                    'icon' => $this->input->post('icon'),
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'instructions' => $this->input->post('instructions'),
                    'sort_order' => $this->input->post('sort_order') ?: 0,
                    'is_active' => $this->input->post('is_active') ? 1 : 0,
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Payment_method_model->create_method($method_data)) {
                    $this->session->set_flashdata('success', 'Payment method created successfully.');
                    redirect('admin/payment_methods');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create payment method.');
                }
            }
        }
        
        $data['title'] = 'Add Payment Method';
        $this->load->view('admin/add_payment_method', $data);
    }

    public function edit_payment_method($id) {
        $method = $this->Payment_method_model->get_method_by_id($id);
        if (!$method) {
            $this->session->set_flashdata('error', 'Payment method not found.');
            redirect('admin/payment_methods');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('display_name', 'Display Name', 'required|max_length[100]');
            $this->form_validation->set_rules('icon', 'Icon', 'required|max_length[100]');
            $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
            $this->form_validation->set_rules('additional_info', 'Additional Info', 'max_length[500]');
            
            if ($this->form_validation->run() == TRUE) {
                $update_data = array(
                    'display_name' => $this->input->post('display_name'),
                    'icon' => $this->input->post('icon'),
                    'title' => $this->input->post('title'),
                    'instructions' => $this->input->post('instructions'),
                    'additional_info' => $this->input->post('additional_info'),
                    'sort_order' => $this->input->post('sort_order'),
                    'is_active' => $this->input->post('is_active') ? 1 : 0
                );
                
                if ($this->Payment_method_model->update_method($id, $update_data)) {
                    $this->session->set_flashdata('success', 'Payment method updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update payment method.');
                }
                redirect('admin/payment_methods');
            }
        }
        
        $data['title'] = 'Edit Payment Method';
        $data['method'] = $method;
        
        $this->load->view('admin/edit_payment_method', $data);
    }

    public function toggle_payment_method($id) {
        if ($this->Payment_method_model->toggle_status($id)) {
            $this->session->set_flashdata('success', 'Payment method status updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update payment method status.');
        }
        redirect('admin/payment_methods');
    }

    public function delete_payment_method($id) {
        if ($this->Payment_method_model->delete_method($id)) {
            $this->session->set_flashdata('success', 'Payment method deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete payment method.');
        }
        redirect('admin/payment_methods');
    }

    // Recharge Requests Management
    public function recharge_requests() {
        $data['title'] = 'Recharge Requests';
        $data['recharge_requests'] = $this->Recharge_request_model->get_all_requests();
        
        $this->load->view('admin/recharge_requests', $data);
    }

    public function approve_recharge($id) {
        $request = $this->Recharge_request_model->get_request_by_id($id);
        if ($request && $request->status === 'pending') {
            // Update request status
            $this->Recharge_request_model->update_request($id, array('status' => 'approved'));
            
            // Credit user's wallet
            $this->Wallet_model->credit_wallet($request->user_id, $request->amount, 'Recharge approved - ' . $request->payment_mode);
            
            // Add notification for customer
            $this->Notification_model->add_notification(
                $request->user_id,
                'customer',
                'Recharge Request Approved',
                'Your recharge request for $' . number_format($request->amount, 2) . ' has been approved and your wallet has been credited.',
                'success',
                'wallet'
            );
            
            $this->session->set_flashdata('success', 'Recharge request approved and wallet credited.');
        } else {
            $this->session->set_flashdata('error', 'Invalid recharge request or already processed.');
        }
        redirect('admin/recharge_requests');
    }

    public function reject_recharge($id) {
        $request = $this->Recharge_request_model->get_request_by_id($id);
        if ($request && $request->status === 'pending') {
            $this->Recharge_request_model->update_request($id, array('status' => 'rejected'));
            
            // Add notification for customer
            $this->Notification_model->add_notification(
                $request->user_id,
                'customer',
                'Recharge Request Rejected',
                'Your recharge request for $' . number_format($request->amount, 2) . ' has been rejected. Please contact support for more information.',
                'danger',
                'wallet'
            );
            
            $this->session->set_flashdata('success', 'Recharge request rejected.');
        } else {
            $this->session->set_flashdata('error', 'Invalid recharge request or already processed.');
        }
        redirect('admin/recharge_requests');
    }

    public function view_recharge($id) {
        $data['title'] = 'View Recharge Request';
        $data['request'] = $this->Recharge_request_model->get_request_by_id($id);
        
        if (!$data['request']) {
            $this->session->set_flashdata('error', 'Recharge request not found.');
            redirect('admin/recharge_requests');
        }
        
        // Get customer data for the view
        $data['customer'] = $this->User_model->get_user_by_id($data['request']->user_id);
        
        // Get wallet data
        $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($data['request']->user_id);
        
        // Get payment method data
        $data['payment_method'] = $this->Payment_method_model->get_method_by_key($data['request']->payment_mode);
        
        $this->load->view('admin/view_recharge', $data);
    }

    // Orders Management
    public function orders() {
        $data['title'] = 'Manage Orders';
        $data['orders'] = $this->Order_model->get_all_orders();
        
        $this->load->view('admin/orders', $data);
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
        redirect('admin/orders');
    }

    public function view_order($id) {
        $data['title'] = 'View Order';
        $data['order'] = $this->Order_model->get_order_by_id($id);
        
        if (!$data['order']) {
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('admin/orders');
        }
        
        // Get customer and product data for the view
        $data['customer'] = $this->User_model->get_user_by_id($data['order']->user_id);
        $data['product'] = $this->Product_model->get_product_by_id($data['order']->product_id);
        
        // Get tracking updates if tracking number exists
        if ($data['order']->tracking_number) {
            $data['tracking_updates'] = $this->Order_model->get_tracking_updates($id);
        }
        
        $this->load->view('admin/view_order', $data);
    }

    public function update_tracking($order_id) {
        $order = $this->Order_model->get_order_by_id($order_id);
        
        if (!$order) {
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('admin/orders');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('tracking_number', 'Tracking Number', 'required|max_length[100]');
            $this->form_validation->set_rules('carrier', 'Carrier', 'required|max_length[100]');
            $this->form_validation->set_rules('tracking_url', 'Tracking URL', 'valid_url');
            
            if ($this->form_validation->run() == TRUE) {
                $tracking_data = array(
                    'tracking_number' => $this->input->post('tracking_number'),
                    'tracking_url' => $this->input->post('tracking_url'),
                    'carrier' => $this->input->post('carrier'),
                    'shipped_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Order_model->update_tracking($order_id, $tracking_data)) {
                    $this->session->set_flashdata('success', 'Tracking information updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update tracking information.');
                }
                redirect('admin/view_order/' . $order_id);
            }
        }
        
        redirect('admin/view_order/' . $order_id);
    }

    public function create_order() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('user_id', 'Customer', 'required|numeric');
            $this->form_validation->set_rules('product_id', 'Product', 'required|numeric');
            $this->form_validation->set_rules('address_id', 'Shipping Address', 'required|numeric');
            $this->form_validation->set_rules('notes', 'Notes', 'max_length[500]');
            
            if ($this->form_validation->run() == TRUE) {
                // Get product details to calculate total amount
                $product = $this->Product_model->get_product_by_id($this->input->post('product_id'));
                if (!$product) {
                    $this->session->set_flashdata('error', 'Selected product not found.');
                    redirect('admin/create_order');
                }
                
                // Get customer-specific price
                $customer_id = $this->input->post('user_id');
                $customer_price = $this->Product_model->get_customer_price($this->input->post('product_id'), $customer_id);
                
                // Get address details
                $address = $this->Customer_address_model->get_address_by_id($this->input->post('address_id'));
                if (!$address) {
                    $this->session->set_flashdata('error', 'Selected address not found.');
                    redirect('admin/create_order');
                }
                
                // Format shipping address
                $shipping_address = $this->Customer_address_model->format_address($address);
                
                // Generate unique order number
                $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
                
                $order_data = array(
                    'order_number' => $order_number,
                    'user_id' => $this->input->post('user_id'),
                    'product_id' => $this->input->post('product_id'),
                    'total_amount' => $customer_price, // Use customer-specific price
                    'status' => 'pending',
                    'shipping_address' => $shipping_address,
                    'notes' => $this->input->post('notes')
                );
                
                if ($this->Order_model->create_order($order_data)) {
                    // Get customer details for notification
                    $customer = $this->User_model->get_user_by_id($this->input->post('user_id'));
                    
                    // Add notification for customer
                    $this->Notification_model->add_notification(
                        $this->input->post('user_id'),
                        'customer',
                        'Order Created by Admin',
                        'An order #' . $order_number . ' has been created for you by admin. It is currently pending.',
                        'info',
                        'order'
                    );
                    
                    $this->session->set_flashdata('success', 'Order created successfully. Order #' . $order_number);
                } else {
                    $this->session->set_flashdata('error', 'Failed to create order.');
                }
                redirect('admin/orders');
            }
        }
        
        $data['title'] = 'Create Order';
        $data['customers'] = $this->User_model->get_users_by_role('customer');
        $data['products'] = $this->Product_model->get_all_products();
        
        $this->load->view('admin/create_order', $data);
    }

    public function get_customer_addresses($user_id) {
        // Verify user exists and is a customer
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user || $user->role !== 'customer') {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['error' => 'Invalid customer']));
            return;
        }
        
        // Get customer addresses
        $addresses = $this->Customer_address_model->get_addresses_by_user_id($user_id);
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['addresses' => $addresses]));
    }

    public function get_customer_price($product_id, $customer_id) {
        // Verify user exists and is a customer
        $user = $this->User_model->get_user_by_id($customer_id);
        if (!$user || $user->role !== 'customer') {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['error' => 'Invalid customer']));
            return;
        }
        
        // Get product details
        $product = $this->Product_model->get_product_by_id($product_id);
        if (!$product) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['error' => 'Product not found']));
            return;
        }
        
        // Get customer-specific price
        $customer_price = $this->Product_model->get_customer_price($product_id, $customer_id);
        $has_discount = ($customer_price < $product->price);
        
        $response = array(
            'price' => $customer_price,
            'original_price' => $product->price,
            'has_discount' => $has_discount,
            'discount_amount' => $product->price - $customer_price
        );
        
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($response));
    }

    public function create_customer_address() {
        // Verify user exists and is a customer
        $user_id = $this->input->post('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        if (!$user || $user->role !== 'customer') {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'error' => 'Invalid customer']));
            return;
        }
        
        // Validate required fields
        $this->form_validation->set_rules('address_name', 'Address Name', 'required|max_length[100]');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|max_length[100]');
        $this->form_validation->set_rules('address_line1', 'Address Line 1', 'required|max_length[255]');
        $this->form_validation->set_rules('city', 'City', 'required|max_length[100]');
        $this->form_validation->set_rules('state', 'State', 'required|max_length[100]');
        $this->form_validation->set_rules('postal_code', 'Postal Code', 'required|max_length[20]');
        $this->form_validation->set_rules('country', 'Country', 'required|max_length[100]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'error' => validation_errors()]));
            return;
        }
        
        // USA-specific validation
        $country = $this->input->post('country');
        if ($country === 'USA') {
            $validation_error = $this->validate_usa_address();
            if ($validation_error) {
                $this->output->set_content_type('application/json')
                             ->set_output(json_encode(['success' => false, 'error' => $validation_error]));
                return;
            }
        }
        
        // Prepare address data
        $address_data = array(
            'user_id' => $user_id,
            'address_name' => $this->input->post('address_name'),
            'full_name' => $this->input->post('full_name'),
            'address_line1' => $this->input->post('address_line1'),
            'address_line2' => $this->input->post('address_line2'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'postal_code' => $this->input->post('postal_code'),
            'country' => $this->input->post('country'),
            'phone' => $this->input->post('phone'),
            'is_default' => $this->input->post('is_default') ? 1 : 0
        );
        
        // If this is the first address or marked as default, handle default setting
        if ($address_data['is_default']) {
            $this->Customer_address_model->set_default_address($user_id, 0); // Will be set after creation
        }
        
        // Create the address
        $address_id = $this->Customer_address_model->create_address($address_data);
        
        if ($address_id) {
            // If this was marked as default, set it now
            if ($address_data['is_default']) {
                $this->Customer_address_model->set_default_address($user_id, $address_id);
            }
            
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'address_id' => $address_id]));
        } else {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'error' => 'Failed to create address']));
        }
    }
    
    private function validate_usa_address() {
        // Validate ZIP Code format
        $postal_code = $this->input->post('postal_code');
        if (!preg_match('/^\d{5}(-\d{4})?$/', $postal_code)) {
            return 'Please enter a valid ZIP code in format: 12345 or 12345-6789';
        }
        
        // Validate State (must be a valid US state abbreviation)
        $state = $this->input->post('state');
        $valid_states = array(
            'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA',
            'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM',
            'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA',
            'WV', 'WI', 'WY'
        );
        if (!in_array($state, $valid_states)) {
            return 'Please select a valid US state';
        }
        
        // Validate Address Line 1 (minimum length)
        $address_line1 = $this->input->post('address_line1');
        if (strlen(trim($address_line1)) < 5) {
            return 'Address Line 1 must be at least 5 characters long';
        }
        
        // Validate City (minimum length)
        $city = $this->input->post('city');
        if (strlen(trim($city)) < 2) {
            return 'City name must be at least 2 characters long';
        }
        
        // Validate Full Name (minimum length)
        $full_name = $this->input->post('full_name');
        if (strlen(trim($full_name)) < 2) {
            return 'Full name must be at least 2 characters long';
        }
        
        // Validate Phone Number (if provided)
        $phone = $this->input->post('phone');
        if (!empty($phone)) {
            $clean_phone = preg_replace('/[\s\-\(\)]/', '', $phone);
            if (!preg_match('/^[\+]?[1-9][\d]{0,15}$/', $clean_phone) || strlen($clean_phone) < 10) {
                return 'Please enter a valid phone number (at least 10 digits)';
            }
        }
        
        return null; // No validation errors
    }

    // Products Management
    public function products() {
        $data['title'] = 'Manage Products';
        $data['products'] = $this->Product_model->get_all_products();
        
        $this->load->view('admin/products', $data);
    }

    public function add_product() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Product Name', 'required|max_length[255]');
            $this->form_validation->set_rules('brand', 'Brand', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer|greater_than_equal_to[0]');
            
            if ($this->form_validation->run() == TRUE) {
                $product_data = array(
                    'name' => $this->input->post('name'),
                    'brand' => $this->input->post('brand'),
                    'strength' => $this->input->post('strength'),
                    'price' => $this->input->post('price'),
                    'quantity' => $this->input->post('quantity'),
                    'status' => $this->input->post('status') ? 'active' : 'inactive'
                );
                
                if ($this->Product_model->create_product($product_data)) {
                    $this->session->set_flashdata('success', 'Product created successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create product.');
                }
                redirect('admin/products');
            }
        }
        
        $data['title'] = 'Add Product';
        $this->load->view('admin/add_product', $data);
    }

    public function edit_product($id) {
        $product = $this->Product_model->get_product_by_id($id);
        if (!$product) {
            $this->session->set_flashdata('error', 'Product not found.');
            redirect('admin/products');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Product Name', 'required|max_length[255]');
            $this->form_validation->set_rules('brand', 'Brand', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer|greater_than_equal_to[0]');
            
            if ($this->form_validation->run() == TRUE) {
                $update_data = array(
                    'name' => $this->input->post('name'),
                    'brand' => $this->input->post('brand'),
                    'strength' => $this->input->post('strength'),
                    'price' => $this->input->post('price'),
                    'quantity' => $this->input->post('quantity'),
                    'status' => $this->input->post('status') ? 'active' : 'inactive'
                );
                
                if ($this->Product_model->update_product($id, $update_data)) {
                    $this->session->set_flashdata('success', 'Product updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update product.');
                }
                redirect('admin/products');
            }
        }
        
        $data['title'] = 'Edit Product';
        $data['product'] = $product;
        $this->load->view('admin/edit_product', $data);
    }

    public function toggle_product($id) {
        $product = $this->Product_model->get_product_by_id($id);
        if ($product) {
            $new_status = $product->status === 'active' ? 'inactive' : 'active';
            $this->Product_model->update_product($id, array('status' => $new_status));
            $this->session->set_flashdata('success', 'Product status updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Product not found.');
        }
        redirect('admin/products');
    }

    public function delete_product($id) {
        if ($this->Product_model->delete_product($id)) {
            $this->session->set_flashdata('success', 'Product deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete product.');
        }
        redirect('admin/products');
    }
    
    public function duplicate_product($id) {
        // Get the original product
        $original_product = $this->Product_model->get_product_by_id($id);
        
        if (!$original_product) {
            $this->session->set_flashdata('error', 'Product not found.');
            redirect('admin/products');
        }
        
        // Convert object to array and copy ALL fields
        $duplicate_data = (array) $original_product;
        
        // Remove the ID field (we don't want to copy the primary key)
        unset($duplicate_data['id']);
        
        // Set status to inactive and update timestamps
        $duplicate_data['status'] = 'inactive';
        $duplicate_data['created_at'] = date('Y-m-d H:i:s');
        $duplicate_data['updated_at'] = date('Y-m-d H:i:s');
        
        if ($this->Product_model->create_product($duplicate_data)) {
            $this->session->set_flashdata('success', 'Product duplicated successfully. The copy has been set to inactive status.');
        } else {
            $this->session->set_flashdata('error', 'Failed to duplicate product.');
        }
        redirect('admin/products');
    }

    // Support Tickets Management
    public function tickets() {
        $data['title'] = 'Support Tickets';
        $data['tickets'] = $this->Support_ticket_model->get_all_tickets();
        
        $this->load->view('admin/tickets', $data);
    }

    public function update_ticket_status($id, $status) {
        $ticket = $this->Support_ticket_model->get_ticket_by_id($id);
        if ($ticket) {
            $this->Support_ticket_model->update_ticket($id, array('status' => $status));
            $this->session->set_flashdata('success', 'Ticket status updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Ticket not found.');
        }
        redirect('admin/tickets');
    }

    public function view_ticket($id) {
        $data['title'] = 'View Ticket';
        $data['ticket'] = $this->Support_ticket_model->get_ticket_by_id($id);
        
        if (!$data['ticket']) {
            $this->session->set_flashdata('error', 'Ticket not found.');
            redirect('admin/tickets');
        }
        
        // Get customer data for the view
        $data['customer'] = $this->User_model->get_user_by_id($data['ticket']->user_id);
        
        // Get ticket replies
        $data['replies'] = $this->Support_ticket_model->get_ticket_replies($id);
        
        $this->load->view('admin/view_ticket', $data);
    }

    public function add_ticket_reply($ticket_id) {
        $ticket = $this->Support_ticket_model->get_ticket_by_id($ticket_id);
        
        if (!$ticket) {
            $this->session->set_flashdata('error', 'Ticket not found.');
            redirect('admin/tickets');
        }
        
        if ($ticket->status === 'closed') {
            $this->session->set_flashdata('error', 'Cannot add reply to closed ticket.');
            redirect('admin/view_ticket/' . $ticket_id);
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('message', 'Message', 'required|min_length[10]|max_length[1000]');
            
            if ($this->form_validation->run() == TRUE) {
                $reply_data = array(
                    'ticket_id' => $ticket_id,
                    'user_id' => $this->session->userdata('user_id'),
                    'message' => $this->input->post('message'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                if ($this->Support_ticket_model->add_reply($reply_data)) {
                    // Update ticket's updated_at timestamp
                    $this->Support_ticket_model->update_ticket($ticket_id, [
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    // Add notification for customer
                    $this->Notification_model->add_notification(
                        $ticket->user_id,
                        'customer',
                        'Support Ticket Reply',
                        'You have received a reply to your support ticket #' . $ticket->ticket_number . '.',
                        'info',
                        'support'
                    );
                    
                    $this->session->set_flashdata('success', 'Reply added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add reply.');
                }
            }
        }
        
        redirect('admin/view_ticket/' . $ticket_id);
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
                redirect('admin/customers');
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
                    'assigned_to' => $this->session->userdata('user_id'), // Assign to current admin
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                $ticket_id = $this->Support_ticket_model->create_ticket($ticket_data);
                
                if ($ticket_id) {
                    // Add the initial message as a reply
                    $reply_data = array(
                        'ticket_id' => $ticket_id,
                        'user_id' => $this->session->userdata('user_id'), // Admin who created the ticket
                        'message' => $this->input->post('message'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    
                    $this->Support_ticket_model->add_reply($reply_data);
                    
                    // Send notification to customer
                    $this->Notification_model->create_notification(
                        $this->input->post('customer_id'),
                        'Support Ticket Created',
                        'A new support ticket #' . $ticket_number . ' has been created for you.'
                    );
                    
                    $this->session->set_flashdata('success', 'Support ticket created successfully');
                    
                    // Redirect to appropriate page
                    if ($redirect_url) {
                        redirect($redirect_url);
                    } else {
                        redirect('admin/tickets');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Failed to create support ticket');
                }
            }
        }
        
        // Get all customers for dropdown
        $customers = $this->User_model->get_all_customers();
        
        $data['title'] = 'Create Support Ticket';
        $data['customer'] = $customer;
        $data['customers'] = $customers;
        $data['redirect_url'] = $redirect_url;
        
        $this->load->view('admin/create_ticket', $data);
    }

    public function toggle_customer($customer_id) {
        $customer = $this->User_model->get_user_by_id($customer_id);
        
        if (!$customer || $customer->role !== 'customer') {
            $this->session->set_flashdata('error', 'Invalid customer');
            redirect('admin/customers');
        }
        
        $new_status = ($customer->status === 'active') ? 'inactive' : 'active';
        $this->User_model->update_user($customer_id, ['status' => $new_status]);
        
        $status_text = ($new_status === 'active') ? 'activated' : 'deactivated';
        $this->session->set_flashdata('success', "Customer has been {$status_text} successfully");
        
        redirect('admin/customers');
    }

    public function view_customer($customer_id) {
        $customer = $this->User_model->get_user_by_id($customer_id);
        
        if (!$customer || $customer->role !== 'customer') {
            $this->session->set_flashdata('error', 'Customer not found');
            redirect('admin/customers');
        }
        
        // Get customer's wallet
        $wallet = $this->Wallet_model->get_wallet_by_user_id($customer_id);
        
        // Get customer's orders
        $orders = $this->Order_model->get_orders_by_user_id($customer_id);
        
        // Get customer's addresses
        $addresses = $this->Customer_address_model->get_addresses_by_user_id($customer_id);
        
        // Get lead data for this customer
        $leads = $this->Lead_model->get_leads_by_email($customer->email);
        $leads_count = count($leads);
        $converted_leads = $this->Lead_model->count_converted_leads_by_email($customer->email);
        
        // Get customer reminders
        $reminders = $this->Customer_reminder_model->get_reminders_by_customer($customer_id);
        $active_reminders = $this->Customer_reminder_model->get_reminders_by_customer($customer_id, 'active');
        
        $data['title'] = 'Customer Details';
        $data['customer'] = $customer;
        $data['wallet'] = $wallet;
        $data['orders'] = $orders;
        $data['addresses'] = $addresses;
        $data['leads'] = $leads;
        $data['leads_count'] = $leads_count;
        $data['converted_leads'] = $converted_leads;
        $data['reminders'] = $reminders;
        $data['active_reminders'] = $active_reminders;
        
        $this->load->view('admin/view_customer', $data);
    }

    public function edit_customer($customer_id) {
        $customer = $this->User_model->get_user_by_id($customer_id);
        
        if (!$customer || $customer->role !== 'customer') {
            $this->session->set_flashdata('error', 'Customer not found');
            redirect('admin/customers');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[100]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[100]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[100]');
            $this->form_validation->set_rules('phone', 'Phone', 'max_length[20]');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
            
            if ($this->form_validation->run() == TRUE) {
                $update_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'status' => $this->input->post('status')
                );
                
                $this->User_model->update_user($customer_id, $update_data);
                $this->session->set_flashdata('success', 'Customer updated successfully');
                redirect('admin/customers');
            }
        }
        
        $data['title'] = 'Edit Customer';
        $data['customer'] = $customer;
        
        $this->load->view('admin/edit_customer', $data);
    }

    public function export_customers() {
        $customers = $this->User_model->get_all_users_by_role('customer');
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="customers_' . date('Y-m-d') . '.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, array('ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Status', 'Joined Date'));
        
        // Add customer data
        foreach ($customers as $customer) {
            fputcsv($output, array(
                $customer->id,
                $customer->first_name,
                $customer->last_name,
                $customer->email,
                isset($customer->phone) ? $customer->phone : '',
                isset($customer->status) ? $customer->status : '',
                date('Y-m-d', strtotime($customer->created_at))
            ));
        }
        
        fclose($output);
        exit;
    }

    public function import_customers() {
        if (!$this->input->post()) {
            $this->session->set_flashdata('error', 'No file uploaded.');
            redirect('admin/customers');
        }

        // Check if file was uploaded
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $this->session->set_flashdata('error', 'Please select a valid CSV file.');
            redirect('admin/customers');
        }

        $file = $_FILES['csv_file'];
        $skip_duplicates = $this->input->post('skip_duplicates') ? true : false;

        // Validate file type
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($file_extension !== 'csv') {
            $this->session->set_flashdata('error', 'Please upload a CSV file.');
            redirect('admin/customers');
        }

        // Read CSV file
        $handle = fopen($file['tmp_name'], 'r');
        if (!$handle) {
            $this->session->set_flashdata('error', 'Unable to read the uploaded file.');
            redirect('admin/customers');
        }

        // Read headers
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            $this->session->set_flashdata('error', 'Invalid CSV format.');
            redirect('admin/customers');
        }

        // Validate required headers
        $required_headers = ['first_name', 'last_name', 'email'];
        $header_map = array_flip($headers);
        
        foreach ($required_headers as $required) {
            if (!isset($header_map[$required])) {
                fclose($handle);
                $this->session->set_flashdata('error', 'Missing required column: ' . $required);
                redirect('admin/customers');
            }
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        // Process each row
        $row_number = 1; // Start from 1 since we already read headers
        while (($row = fgetcsv($handle)) !== false) {
            $row_number++;
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Map data
            $data = array_combine($headers, $row);
            
            // Validate required fields
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
                $errors[] = "Row $row_number: Missing required fields";
                continue;
            }

            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row $row_number: Invalid email format";
                continue;
            }

            // Check for duplicate email
            $existing_user = $this->User_model->get_user_by_email($data['email']);
            if ($existing_user) {
                if ($skip_duplicates) {
                    $skipped++;
                    continue;
                } else {
                    $errors[] = "Row $row_number: Email already exists";
                    continue;
                }
            }

            // Prepare user data
            $user_data = array(
                'first_name' => trim($data['first_name']),
                'last_name' => trim($data['last_name']),
                'email' => trim($data['email']),
                'username' => trim($data['email']), // Use email as username
                'phone' => isset($data['phone']) ? trim($data['phone']) : '',
                'password' => isset($data['password']) && !empty($data['password']) ? 
                             password_hash(trim($data['password']), PASSWORD_DEFAULT) : 
                             password_hash('welcome123', PASSWORD_DEFAULT),
                'role' => 'customer',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            );

            // Create user
            $user_id = $this->User_model->create_user($user_data);
            if ($user_id) {
                // Create wallet for the new customer
                $this->Wallet_model->create_wallet($user_id);
                $imported++;
            } else {
                $errors[] = "Row $row_number: Failed to create user";
            }
        }

        fclose($handle);

        // Set flash message
        $message = "Import completed: $imported customers imported";
        if ($skipped > 0) {
            $message .= ", $skipped duplicates skipped";
        }
        if (!empty($errors)) {
            $message .= ", " . count($errors) . " errors occurred";
        }

        if (!empty($errors)) {
            $this->session->set_flashdata('error', $message . '. Check the logs for details.');
            log_message('error', 'Customer import errors: ' . implode(', ', $errors));
        } else {
            $this->session->set_flashdata('success', $message);
        }

        redirect('admin/customers');
    }

    public function download_customer_template() {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="customer_import_template.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, array('first_name', 'last_name', 'email', 'phone', 'password'));
        
        // Add example rows
        fputcsv($output, array('John', 'Doe', 'john@example.com', '555-1234', 'password123'));
        fputcsv($output, array('Jane', 'Smith', 'jane@example.com', '555-5678', ''));
        fputcsv($output, array('Bob', 'Johnson', 'bob@example.com', '', 'securepass'));
        
        fclose($output);
        exit;
    }

    // Customer Pricing Management
    public function customer_pricing($customer_id = null) {
        if ($customer_id) {
            // Show specific customer pricing
            $data['customer'] = $this->User_model->get_user_by_id($customer_id);
            if (!$data['customer'] || $data['customer']->role !== 'customer') {
                $this->session->set_flashdata('error', 'Customer not found.');
                redirect('admin/customers');
            }
            
            $data['title'] = 'Customer Pricing - ' . $data['customer']->first_name . ' ' . $data['customer']->last_name;
            $data['products'] = $this->Product_model->get_all_products('active');
            $data['customer_prices'] = $this->Product_model->get_customer_prices($customer_id);
            
            // Create a map of customer prices for easy lookup
            $data['price_map'] = array();
            foreach ($data['customer_prices'] as $cp) {
                $data['price_map'][$cp->product_id] = $cp->custom_price;
            }
            
            $this->load->view('admin/customer_pricing', $data);
        } else {
            // Show list of customers with pricing summary
            $data['title'] = 'Customer Pricing Management';
            $data['customers'] = $this->User_model->get_all_users_by_role('customer');
            
            // Get pricing summary for each customer
            foreach ($data['customers'] as $customer) {
                $customer->custom_prices_count = $this->Product_model->get_customer_prices($customer->id);
                $customer->custom_prices_count = count($customer->custom_prices_count);
            }
            
            $this->load->view('admin/customer_pricing_list', $data);
        }
    }

    public function set_customer_price() {
        // Log the request for debugging
        log_message('debug', 'set_customer_price called with POST data: ' . json_encode($_POST));
        
        $customer_id = $this->input->post('customer_id');
        $product_id = $this->input->post('product_id');
        $price = $this->input->post('price');
        
        log_message('debug', "set_customer_price: customer_id=$customer_id, product_id=$product_id, price=$price");
        
        // Validate inputs
        if (empty($customer_id) || empty($product_id) || empty($price)) {
            log_message('debug', 'set_customer_price: Missing required fields');
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json');
                $this->output->set_output(json_encode(array(
                    'success' => false,
                    'message' => 'All fields are required.'
                )));
            } else {
                $this->session->set_flashdata('error', 'All fields are required.');
                redirect('admin/customer_pricing/' . $customer_id);
            }
            return;
        }
        
        // Validate price
        if (!is_numeric($price) || $price < 0) {
            log_message('debug', 'set_customer_price: Invalid price');
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json');
                $this->output->set_output(json_encode(array(
                    'success' => false,
                    'message' => 'Price must be a positive number.'
                )));
            } else {
                $this->session->set_flashdata('error', 'Price must be a positive number.');
                redirect('admin/customer_pricing/' . $customer_id);
            }
            return;
        }
        
        // Set the customer price
        $result = $this->Product_model->set_customer_price($customer_id, $product_id, $price);
        
        log_message('debug', "set_customer_price: Result = " . ($result ? 'true' : 'false'));
        
        if ($result) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json');
                $this->output->set_output(json_encode(array(
                    'success' => true,
                    'message' => 'Customer price updated successfully!'
                )));
            } else {
                $this->session->set_flashdata('success', 'Customer price updated successfully!');
                redirect('admin/customer_pricing/' . $customer_id);
            }
        } else {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json');
                $this->output->set_output(json_encode(array(
                    'success' => false,
                    'message' => 'Failed to update customer price.'
                )));
            } else {
                $this->session->set_flashdata('error', 'Failed to update customer price.');
                redirect('admin/customer_pricing/' . $customer_id);
            }
        }
    }

    public function remove_customer_price() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $customer_id = $this->input->post('customer_id');
        $product_id = $this->input->post('product_id');

        if ($this->Product_model->remove_customer_price($customer_id, $product_id)) {
            echo json_encode(array(
                'success' => true,
                'message' => 'Customer-specific price removed successfully'
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Failed to remove customer-specific price'
            ));
        }
    }

    // Admin Chat Methods
    public function admin_messages() {
        $data['title'] = 'Admin Messages';
        $data['unread_messages'] = $this->Admin_message_model->get_unread_messages();
        $data['all_messages'] = $this->Admin_message_model->get_all_messages(50);
        $data['unread_count'] = $this->Admin_message_model->get_unread_count();
        
        $this->load->view('admin/admin_messages', $data);
    }

    public function view_customer_messages($customer_id) {
        $data['title'] = 'Customer Messages';
        $data['customer'] = $this->User_model->get_user_by_id($customer_id);
        $data['messages'] = $this->Admin_message_model->get_messages_by_customer($customer_id);
        
        if (!$data['customer']) {
            $this->session->set_flashdata('error', 'Customer not found.');
            redirect('admin/admin_messages');
        }
        
        // Mark messages as read
        $this->Admin_message_model->mark_customer_messages_as_read($customer_id);
        
        $this->load->view('admin/view_customer_messages', $data);
    }

    public function mark_message_read() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $message_id = $this->input->post('message_id');
        
        if ($this->Admin_message_model->mark_as_read($message_id)) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false));
        }
    }

    public function delete_message() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $message_id = $this->input->post('message_id');
        
        if ($this->Admin_message_model->delete_message($message_id)) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false));
        }
    }

    public function get_unread_count() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $unread_count = $this->Admin_message_model->get_unread_count();
        echo json_encode(array('unread_count' => $unread_count));
    }

    public function get_new_customer_messages() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $customer_id = $this->input->get('customer_id');
        $last_id = $this->input->get('last_id', 0);
        
        // Get new customer messages since last_id
        $new_messages = $this->db->where('customer_id', $customer_id)
                                 ->where('id >', $last_id)
                                 ->where('is_admin_reply', FALSE)
                                 ->order_by('created_at', 'ASC')
                                 ->get('admin_messages')
                                 ->result();
        
        echo json_encode(array(
            'success' => true,
            'messages' => $new_messages
        ));
    }

    public function send_admin_reply() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $customer_id = $this->input->post('customer_id');
        $message = trim($this->input->post('message'));
        $admin_id = $this->session->userdata('user_id');
        $admin_name = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');

        if (empty($message)) {
            echo json_encode(array('success' => false, 'message' => 'Message cannot be empty'));
            return;
        }

        if (empty($customer_id)) {
            echo json_encode(array('success' => false, 'message' => 'Customer ID is required'));
            return;
        }

        try {
            $message_id = $this->Admin_message_model->add_admin_reply($customer_id, $admin_id, $admin_name, $message);
            
            if ($message_id) {
                // Add notification for customer
                $this->Notification_model->add_notification(
                    $customer_id,
                    'customer',
                    'Admin Reply',
                    'You have received a reply from admin.',
                    'info',
                    'chat'
                );
                
                echo json_encode(array(
                    'success' => true,
                    'message_id' => $message_id,
                    'message' => 'Reply sent successfully'
                ));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Failed to send reply'));
            }
        } catch (Exception $e) {
            echo json_encode(array('success' => false, 'message' => 'Database error: ' . $e->getMessage()));
        }
    }

    // Notification methods
    public function get_notifications() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->session->userdata('user_id');
        $notifications = $this->Notification_model->get_notifications($user_id, 'admin', 20);
        
        echo json_encode(array(
            'success' => true,
            'notifications' => $notifications
        ));
    }

    public function get_unread_notifications() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->session->userdata('user_id');
        $notifications = $this->Notification_model->get_unread_notifications($user_id, 'admin', 10);
        $unread_count = $this->Notification_model->get_unread_count($user_id, 'admin');
        
        echo json_encode(array(
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unread_count
        ));
    }

    public function mark_notification_read() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $notification_id = $this->input->post('notification_id');
        $user_id = $this->session->userdata('user_id');

        if (!$notification_id || !$user_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        // Update notification as read
        $result = $this->db->where('id', $notification_id)
                           ->where('user_id', $user_id)
                           ->where('user_type', 'admin')
                           ->update('notifications', ['is_read' => TRUE]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update notification']);
        }
    }

    public function mark_all_notifications_read() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->session->userdata('user_id');

        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        // Mark all notifications as read
        $result = $this->db->where('user_id', $user_id)
                           ->where('user_type', 'admin')
                           ->where('is_read', FALSE)
                           ->update('notifications', ['is_read' => TRUE]);

        if ($result !== false) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update notifications']);
        }
    }

    public function notifications() {
        if (!$this->session->userdata('user_id') || $this->session->userdata('role') !== 'admin') {
            redirect('auth/login');
        }

        $user_id = $this->session->userdata('user_id');
        $data['notifications'] = $this->Notification_model->get_notifications($user_id, 'admin', 50);
        $data['unread_count'] = $this->Notification_model->get_unread_count($user_id, 'admin');
        
        $this->load->view('admin/notifications', $data); // Removed header/footer loading from here
    }

    // ==================== LEADS MANAGEMENT ====================
    
    public function leads() {
        $status = $this->input->get('status');
        $search = $this->input->get('search');
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $per_page = 20; // Number of leads per page
        $offset = ($page - 1) * $per_page;
        
        // Exclude converted leads from main leads page
        if ($search) {
            $data['leads'] = $this->Lead_model->search_leads($search, false); // false = exclude converted
            $total_leads = count($data['leads']);
        } else {
            $data['leads'] = $this->Lead_model->get_leads($per_page, $offset, $status, false); // false = exclude converted
            $total_leads = $this->Lead_model->count_leads_by_status($status, false); // false = exclude converted
        }
        
        // Pagination configuration
        $this->load->library('pagination');
        $config['base_url'] = base_url('admin/leads');
        $config['total_rows'] = $total_leads;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['anchor_class'] = 'page-link';
        
        // Add query parameters to pagination
        $query_params = array();
        if ($status) $query_params['status'] = $status;
        if ($search) $query_params['search'] = $search;
        if (!empty($query_params)) {
            $config['suffix'] = '&' . http_build_query($query_params);
            $config['first_url'] = $config['base_url'] . '?' . http_build_query($query_params);
        }
        
        $this->pagination->initialize($config);
        
        $data['stats'] = $this->Lead_model->get_lead_stats(false); // false = exclude converted
        $data['current_status'] = $status;
        $data['search_term'] = $search;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_leads'] = $total_leads;
        $data['current_page'] = $page;
        $data['per_page'] = $per_page;
        
        $this->load->view('admin/leads', $data);
    }

    public function converted_leads() {
        $search = $this->input->get('search');
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $per_page = 20; // Number of leads per page
        $offset = ($page - 1) * $per_page;
        
        // Get only converted leads
        if ($search) {
            $data['leads'] = $this->Lead_model->search_leads($search, true); // true = only converted
            $total_leads = count($data['leads']);
        } else {
            $data['leads'] = $this->Lead_model->get_leads($per_page, $offset, 'converted', true); // true = only converted
            $total_leads = $this->Lead_model->count_leads_by_status('converted', true); // true = only converted
        }
        
        // Pagination configuration
        $this->load->library('pagination');
        $config['base_url'] = base_url('admin/converted_leads');
        $config['total_rows'] = $total_leads;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['anchor_class'] = 'page-link';
        
        // Add query parameters to pagination
        $query_params = array();
        if ($search) $query_params['search'] = $search;
        if (!empty($query_params)) {
            $config['suffix'] = '&' . http_build_query($query_params);
            $config['first_url'] = $config['base_url'] . '?' . http_build_query($query_params);
        }
        
        $this->pagination->initialize($config);
        
        $data['stats'] = $this->Lead_model->get_lead_stats(true); // true = only converted
        $data['search_term'] = $search;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_leads'] = $total_leads;
        $data['current_page'] = $page;
        $data['per_page'] = $per_page;
        
        $this->load->view('admin/converted_leads', $data);
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
                redirect('admin/leads');
            }
        }
        
        $this->load->view('admin/add_lead');
    }
    
    public function edit_lead($id) {
        $lead = $this->Lead_model->get_lead_by_id($id);
        if (!$lead) {
            $this->session->set_flashdata('error', 'Lead not found.');
            redirect('admin/leads');
        }
        
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
                    'status' => $this->input->post('status'),
                    'notes' => $this->input->post('notes'),
                    'source' => $this->input->post('source')
                );
                
                if ($this->Lead_model->update_lead($id, $lead_data)) {
                    $this->session->set_flashdata('success', 'Lead updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update lead.');
                }
                redirect('admin/leads');
            }
        }
        
        $data['lead'] = $lead;
        $this->load->view('admin/edit_lead', $data);
    }
    
    public function view_lead($id) {
        $lead = $this->Lead_model->get_lead_by_id($id);
        if (!$lead) {
            $this->session->set_flashdata('error', 'Lead not found.');
            redirect('admin/leads');
        }
        
        $data['lead'] = $lead;
        $this->load->view('admin/view_lead', $data);
    }
    
    public function delete_lead($id) {
        if ($this->Lead_model->delete_lead($id)) {
            $this->session->set_flashdata('success', 'Lead deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete lead.');
        }
        redirect('admin/leads');
    }

    public function update_lead_status() {
        // Check if it's an AJAX request
        if (!$this->input->is_ajax_request()) {
            $this->output->set_status_header(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $lead_id = $this->input->post('lead_id');
        $status = $this->input->post('status');

        // Validate inputs
        if (!$lead_id || !$status) {
            $this->output->set_status_header(400);
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        // Validate status
        $valid_statuses = ['new', 'contacted', 'qualified', 'converted', 'lost'];
        if (!in_array($status, $valid_statuses)) {
            $this->output->set_status_header(400);
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            return;
        }

        // Update the lead status
        $update_data = ['status' => $status];
        if ($this->Lead_model->update_lead($lead_id, $update_data)) {
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
    }
    
    public function convert_lead_to_customer($lead_id) {
        $lead = $this->Lead_model->get_lead_by_id($lead_id);
        if (!$lead) {
            $this->session->set_flashdata('error', 'Lead not found.');
            redirect('admin/leads');
        }
        
        // Check if user already exists
        $existing_user = $this->User_model->get_user_by_email($lead->email);
        if ($existing_user) {
            $this->session->set_flashdata('error', 'A customer with this email already exists.');
            redirect('admin/leads');
        }
        
        // Create user account
        $user_data = array(
            'username' => $lead->email, // Use email as username
            'first_name' => $lead->first_name,
            'last_name' => $lead->last_name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'password' => password_hash('changeme123', PASSWORD_DEFAULT), // Default password
            'role' => 'customer',
            'status' => 'active'
        );
        
        $user_id = $this->User_model->create_user($user_data);
        
        if ($user_id) {
            // Create customer address
            $address_data = array(
                'user_id' => $user_id,
                'address_name' => 'Primary Address',
                'full_name' => $lead->first_name . ' ' . $lead->last_name,
                'address_line1' => $lead->address_line1,
                'address_line2' => $lead->address_line2,
                'city' => $lead->city,
                'state' => $lead->state,
                'postal_code' => $lead->postal_code,
                'country' => $lead->country,
                'phone' => $lead->phone,
                'is_default' => TRUE
            );
            
            $this->Customer_address_model->create_address($address_data);
            
            // Create wallet
            $this->Wallet_model->create_wallet($user_id);
            
            // Mark lead as converted
            $this->Lead_model->convert_lead_to_customer($lead_id, $user_id);
            
            // Add notification for admin
            $this->Notification_model->add_notification(
                $this->session->userdata('user_id'),
                'admin',
                'Lead Converted to Customer',
                'Lead ' . $lead->first_name . ' ' . $lead->last_name . ' has been converted to a customer.',
                'success',
                'system'
            );
            
            $this->session->set_flashdata('success', 'Lead converted to customer successfully. Customer ID: ' . $user_id);
        } else {
            $this->session->set_flashdata('error', 'Failed to convert lead to customer.');
        }
        
        redirect('admin/leads');
    }
    
    public function import_leads() {
        if ($this->input->post()) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'csv';
            $config['max_size'] = 2048;
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('csv_file')) {
                $upload_data = $this->upload->data();
                $file_path = $upload_data['full_path'];
                
                // Read CSV file
                $csv_data = array();
                if (($handle = fopen($file_path, "r")) !== FALSE) {
                    $headers = fgetcsv($handle);
                    while (($data = fgetcsv($handle)) !== FALSE) {
                        $row = array_combine($headers, $data);
                        $csv_data[] = $row;
                    }
                    fclose($handle);
                }
                
                // Import leads
                $result = $this->Lead_model->import_leads_from_csv($csv_data);
                
                // Clean up uploaded file
                unlink($file_path);
                
                if ($result['imported'] > 0) {
                    $this->session->set_flashdata('success', $result['imported'] . ' leads imported successfully.');
                    if (!empty($result['errors'])) {
                        $this->session->set_flashdata('warning', 'Some errors occurred: ' . implode(', ', $result['errors']));
                    }
                } else {
                    $this->session->set_flashdata('error', 'No leads were imported.');
                }
                
                redirect('admin/leads');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
            }
        }
        
        $this->load->view('admin/import_leads');
    }
    
    public function export_leads() {
        $status = $this->input->get('status');
        $leads = $this->Lead_model->export_leads_to_csv($status);
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="leads_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, array(
            'ID', 'First Name', 'Last Name', 'Phone', 'Email', 'Address Line 1', 'Address Line 2',
            'City', 'Postal Code', 'State', 'Country', 'Product Interest', 'Payment Method',
            'Payment Details', 'Status', 'Notes', 'Source', 'Created At'
        ));
        
        // Add data
        foreach ($leads as $lead) {
            fputcsv($output, array(
                $lead->id,
                $lead->first_name,
                $lead->last_name,
                $lead->phone,
                $lead->email,
                $lead->address_line1,
                $lead->address_line2,
                $lead->city,
                $lead->postal_code,
                $lead->state,
                $lead->country,
                $lead->product_interest,
                $lead->payment_method,
                $lead->payment_details,
                $lead->status,
                $lead->notes,
                $lead->source,
                $lead->created_at
            ));
        }
        
        fclose($output);
    }

    // Customer Reminders Management
    public function customer_reminders() {
        $status = $this->input->get('status');
        $priority = $this->input->get('priority');
        $search = $this->input->get('search');
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;
        
        if ($search) {
            $data['reminders'] = $this->Customer_reminder_model->search_reminders($search, $per_page, $offset);
            $total_reminders = count($data['reminders']);
        } else {
            $data['reminders'] = $this->Customer_reminder_model->get_all_reminders($per_page, $offset, $status, $priority);
            $total_reminders = $this->Customer_reminder_model->count_reminders_by_status($status);
        }
        
        // Pagination configuration
        $this->load->library('pagination');
        $config['base_url'] = base_url('admin/customer_reminders');
        $config['total_rows'] = $total_reminders;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['anchor_class'] = 'page-link';
        
        // Add query parameters to pagination
        $query_params = array();
        if ($status) $query_params['status'] = $status;
        if ($priority) $query_params['priority'] = $priority;
        if ($search) $query_params['search'] = $search;
        if (!empty($query_params)) {
            $config['suffix'] = '&' . http_build_query($query_params);
            $config['first_url'] = $config['base_url'] . '?' . http_build_query($query_params);
        }
        
        $this->pagination->initialize($config);
        
        $data['stats'] = $this->Customer_reminder_model->get_reminder_stats();
        $data['current_status'] = $status;
        $data['current_priority'] = $priority;
        $data['search_term'] = $search;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_reminders'] = $total_reminders;
        $data['current_page'] = $page;
        $data['per_page'] = $per_page;
        
        $this->load->view('admin/customer_reminders', $data);
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
                $redirect_url = $this->input->post('redirect_url') ?: 'admin/customer_reminders';
                redirect($redirect_url);
            }
        }
        
        $data['customers'] = $this->User_model->get_all_users_by_role('customer');
        $data['selected_customer_id'] = $customer_id;
        $data['redirect_url'] = $this->input->get('redirect_url');
        
        $this->load->view('admin/add_customer_reminder', $data);
    }

    public function edit_customer_reminder($id) {
        $reminder = $this->Customer_reminder_model->get_reminder_by_id($id);
        if (!$reminder) {
            $this->session->set_flashdata('error', 'Reminder not found.');
            redirect('admin/customer_reminders');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
            $this->form_validation->set_rules('content', 'Content', 'required');
            $this->form_validation->set_rules('priority', 'Priority', 'required|in_list[low,medium,high,urgent]');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,completed,archived]');
            
            if ($this->form_validation->run() == TRUE) {
                $reminder_data = array(
                    'title' => $this->input->post('title'),
                    'content' => $this->input->post('content'),
                    'priority' => $this->input->post('priority'),
                    'status' => $this->input->post('status'),
                    'due_date' => $this->input->post('due_date') ?: NULL
                );
                
                if ($this->Customer_reminder_model->update_reminder($id, $reminder_data)) {
                    $this->session->set_flashdata('success', 'Reminder updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update reminder.');
                }
                
                $redirect_url = $this->input->post('redirect_url') ?: 'admin/customer_reminders';
                redirect($redirect_url);
            }
        }
        
        $data['reminder'] = $reminder;
        $data['redirect_url'] = $this->input->get('redirect_url');
        
        $this->load->view('admin/edit_customer_reminder', $data);
    }

    public function delete_customer_reminder($id) {
        $reminder = $this->Customer_reminder_model->get_reminder_by_id($id);
        if (!$reminder) {
            $this->session->set_flashdata('error', 'Reminder not found.');
        } else {
            if ($this->Customer_reminder_model->delete_reminder($id)) {
                $this->session->set_flashdata('success', 'Reminder deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete reminder.');
            }
        }
        
        $redirect_url = $this->input->get('redirect_url') ?: 'admin/customer_reminders';
        redirect($redirect_url);
    }

    public function mark_reminder_completed($id) {
        if ($this->Customer_reminder_model->mark_completed($id)) {
            $this->session->set_flashdata('success', 'Reminder marked as completed.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update reminder status.');
        }
        
        $redirect_url = $this->input->get('redirect_url') ?: 'admin/customer_reminders';
        redirect($redirect_url);
    }

    public function mark_reminder_archived($id) {
        if ($this->Customer_reminder_model->mark_archived($id)) {
            $this->session->set_flashdata('success', 'Reminder archived successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to archive reminder.');
        }
        
        $redirect_url = $this->input->get('redirect_url') ?: 'admin/customer_reminders';
        redirect($redirect_url);
    }

    public function reactivate_reminder($id) {
        if ($this->Customer_reminder_model->reactivate($id)) {
            $this->session->set_flashdata('success', 'Reminder reactivated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to reactivate reminder.');
        }
        
        $redirect_url = $this->input->get('redirect_url') ?: 'admin/customer_reminders';
        redirect($redirect_url);
    }

    public function import_products() {
        if (!$this->input->post()) {
            $this->session->set_flashdata('error', 'No file uploaded.');
            redirect('admin/products');
        }

        // Check if file was uploaded
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $this->session->set_flashdata('error', 'Please select a valid CSV file.');
            redirect('admin/products');
        }

        $file = $_FILES['csv_file'];
        $skip_duplicates = $this->input->post('skip_duplicates') ? true : false;
        $set_inactive = $this->input->post('set_inactive') ? true : false;

        // Validate file type
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($file_extension !== 'csv') {
            $this->session->set_flashdata('error', 'Please upload a CSV file.');
            redirect('admin/products');
        }

        // Read CSV file
        $handle = fopen($file['tmp_name'], 'r');
        if (!$handle) {
            $this->session->set_flashdata('error', 'Unable to read the uploaded file.');
            redirect('admin/products');
        }

        // Read headers
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            $this->session->set_flashdata('error', 'Invalid CSV format.');
            redirect('admin/products');
        }

        // Validate required headers
        $required_headers = ['name', 'price', 'quantity'];
        $header_map = array_flip($headers);
        
        foreach ($required_headers as $required) {
            if (!isset($header_map[$required])) {
                fclose($handle);
                $this->session->set_flashdata('error', 'Missing required column: ' . $required);
                redirect('admin/products');
            }
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        // Process each row
        $row_number = 1; // Start from 1 since we already read headers
        while (($row = fgetcsv($handle)) !== false) {
            $row_number++;
            
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Map data
            $data = array_combine($headers, $row);
            
            // Validate required fields
            if (empty($data['name']) || empty($data['price']) || empty($data['quantity'])) {
                $errors[] = "Row $row_number: Missing required fields";
                continue;
            }

            // Validate numeric fields
            if (!is_numeric($data['price']) || $data['price'] <= 0) {
                $errors[] = "Row $row_number: Invalid price";
                continue;
            }

            if (!is_numeric($data['quantity']) || $data['quantity'] <= 0) {
                $errors[] = "Row $row_number: Invalid quantity";
                continue;
            }

            // Check for duplicate names
            $existing_product = $this->Product_model->get_product_by_name($data['name']);
            if ($existing_product) {
                if ($skip_duplicates) {
                    $skipped++;
                    continue;
                } else {
                    $errors[] = "Row $row_number: Product name already exists";
                    continue;
                }
            }

            // Prepare product data
            $product_data = array(
                'name' => trim($data['name']),
                'price' => floatval($data['price']),
                'quantity' => intval($data['quantity']),
                'strength' => isset($data['strength']) ? trim($data['strength']) : '',
                'description' => isset($data['description']) ? trim($data['description']) : '',
                'status' => $set_inactive ? 'inactive' : (isset($data['status']) ? trim($data['status']) : 'inactive'),
                'created_at' => date('Y-m-d H:i:s')
            );

            // Validate status
            if (!in_array($product_data['status'], ['active', 'inactive'])) {
                $product_data['status'] = 'inactive';
            }

            // Create product
            $product_id = $this->Product_model->create_product($product_data);
            if ($product_id) {
                $imported++;
            } else {
                $errors[] = "Row $row_number: Failed to create product";
            }
        }

        fclose($handle);

        // Set flash message
        $message = "Import completed: $imported products imported";
        if ($skipped > 0) {
            $message .= ", $skipped duplicates skipped";
        }
        if (!empty($errors)) {
            $message .= ", " . count($errors) . " errors occurred";
        }

        if (!empty($errors)) {
            $this->session->set_flashdata('error', $message . '. Check the logs for details.');
            log_message('error', 'Product import errors: ' . implode(', ', $errors));
        } else {
            $this->session->set_flashdata('success', $message);
        }

        redirect('admin/products');
    }

    public function download_product_template() {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="product_import_template.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, array('name', 'price', 'quantity', 'strength', 'description', 'status'));
        
        // Add example rows
        fputcsv($output, array('Aspirin', '9.99', '30', '500mg', 'Pain relief medication', 'inactive'));
        fputcsv($output, array('Ibuprofen', '12.50', '60', '200mg', 'Anti-inflammatory', 'inactive'));
        fputcsv($output, array('Acetaminophen', '8.75', '45', '500mg', 'Fever reducer', 'inactive'));
        
        fclose($output);
        exit;
    }

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