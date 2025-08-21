<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in and is customer
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'customer') {
            redirect('auth/login');
        }
        
        $this->load->model('User_model');
        $this->load->model('Wallet_model');
        $this->load->model('Product_model');
        $this->load->model('Order_model');
        $this->load->model('Support_ticket_model');
        $this->load->model('Recharge_request_model');
        $this->load->model('Customer_address_model');
        $this->load->model('Admin_message_model');
        $this->load->model('Notification_model');
        
        // Check for negative balance on all pages
        $this->check_negative_balance();
    }
    
    private function check_negative_balance() {
        $user_id = $this->session->userdata('user_id');
        $wallet = $this->Wallet_model->get_wallet_by_user_id($user_id);
        
        if (!$wallet) {
            $this->Wallet_model->create_wallet($user_id);
            $wallet = $this->Wallet_model->get_wallet_by_user_id($user_id);
        }
        
        $wallet_balance = $wallet ? $wallet->balance : 0;
        
        // Set negative balance data for all views
        $this->load->vars([
            'has_negative_balance' => $wallet_balance < 0,
            'negative_balance_amount' => abs($wallet_balance)
        ]);
    }

    public function index() {
        redirect('customer/dashboard');
    }

    public function dashboard() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Customer Dashboard';
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['active_page'] = 'dashboard';
        
        // Check for missing required information
        $data['missing_info'] = array();
        
        // Check if phone number is missing
        if (empty($data['user']->phone)) {
            $data['missing_info'][] = 'phone';
        }
        
        // Check if address is missing
        $addresses = $this->Customer_address_model->get_addresses_by_user_id($user_id);
        if (empty($addresses)) {
            $data['missing_info'][] = 'address';
        }
        
        // Get customer data
        $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        
        // Create wallet if it doesn't exist
        if (!$data['wallet']) {
            $this->Wallet_model->create_wallet($user_id);
            $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        }
        
        // Get wallet balance
        $data['wallet_balance'] = $data['wallet'] ? $data['wallet']->balance : 0;
        
        // Check for negative balance
        $data['has_negative_balance'] = $data['wallet_balance'] < 0;
        $data['negative_balance_amount'] = abs($data['wallet_balance']);
        
        // Get counts
        $data['total_orders'] = $this->Order_model->count_orders_by_user($user_id);
        $data['total_tickets'] = $this->Support_ticket_model->count_tickets_by_user($user_id);
        $data['pending_recharges'] = count($this->Recharge_request_model->get_pending_requests_by_user($user_id));
        
        // Get recent activities (combine recent orders, tickets, and transactions)
        $recent_activities = array();
        
        // Add recent orders
        $recent_orders = $this->Order_model->get_orders_by_user($user_id, 3);
        foreach ($recent_orders as $order) {
            $recent_activities[] = (object) array(
                'title' => 'Order Placed',
                'description' => 'Order #' . $order->order_number . ' for $' . number_format($order->total_amount, 2),
                'created_at' => $order->created_at
            );
        }
        
        // Add recent tickets
        $recent_tickets = $this->Support_ticket_model->get_tickets_by_user($user_id, 3);
        foreach ($recent_tickets as $ticket) {
            $recent_activities[] = (object) array(
                'title' => 'Support Ticket',
                'description' => 'Ticket #' . $ticket->ticket_number . ' - ' . $ticket->subject,
                'created_at' => $ticket->created_at
            );
        }
        
        // Add recent transactions
        $recent_transactions = $this->Wallet_model->get_transactions($user_id, 3);
        foreach ($recent_transactions as $transaction) {
            $recent_activities[] = (object) array(
                'title' => 'Wallet Transaction',
                'description' => $transaction->description . ' - $' . number_format($transaction->amount, 2),
                'created_at' => $transaction->created_at
            );
        }
        
        // Sort by creation date (newest first) and limit to 5
        usort($recent_activities, function($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });
        
        $data['recent_activities'] = array_slice($recent_activities, 0, 5);
        
        $this->load->view('customer/dashboard', $data);
    }

    public function wallet() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'My Wallet';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'wallet';
        $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        
        // Create wallet if it doesn't exist
        if (!$data['wallet']) {
            $this->Wallet_model->create_wallet($user_id);
            $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        }
        
        $data['transactions'] = $this->Wallet_model->get_transactions($user_id);
        
        $this->load->view('customer/wallet', $data);
    }

    public function recharge() {
        $user_id = $this->session->userdata('user_id');
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'required');
            $this->form_validation->set_rules('transaction_id', 'Transaction ID', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $recharge_data = array(
                    'user_id' => $user_id,
                    'amount' => $this->input->post('amount'),
                    'payment_mode' => $this->input->post('payment_mode'),
                    'transaction_id' => $this->input->post('transaction_id'),
                    'notes' => $this->input->post('notes'),
                    'status' => 'pending'
                );
                
                if ($this->Recharge_request_model->create_request($recharge_data)) {
                    // Add notification for customer
                    $this->Notification_model->add_notification(
                        $user_id,
                        'customer',
                        'Recharge Request Submitted',
                        'Your recharge request for $' . number_format($this->input->post('amount'), 2) . ' has been submitted and is pending approval.',
                        'info',
                        'wallet'
                    );
                    
                    // Add notification for admin
                    $this->Notification_model->add_notification(
                        1,
                        'admin',
                        'New Recharge Request',
                        'A new recharge request for $' . number_format($this->input->post('amount'), 2) . ' has been submitted by ' . $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
                        'info',
                        'wallet'
                    );
                    
                    $this->session->set_flashdata('success', 'Recharge request submitted successfully. It will be reviewed by admin.');
                    redirect('customer/wallet');
                } else {
                    $this->session->set_flashdata('error', 'Failed to submit recharge request.');
                }
            }
        }
        
        $data['title'] = 'Request Wallet Recharge';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'recharge';
        $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        
        // Create wallet if it doesn't exist
        if (!$data['wallet']) {
            $this->Wallet_model->create_wallet($user_id);
            $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        }
        
        // Load payment instructions from database
        $this->load->model('Payment_method_model');
        $methods = $this->Payment_method_model->get_all_methods(true);
        $data['payment_instructions'] = array();
        
        foreach ($methods as $method) {
            $data['payment_instructions'][] = (object) [
                'id' => $method->method_key,
                'name' => $method->display_name,
                'instructions' => $method->instructions,
                'icon' => $method->icon,
                'additional_info' => $method->additional_info
            ];
        }
        
        $this->load->view('customer/recharge', $data);
    }

    public function products() {
        $user_id = $this->session->userdata('user_id');
        
        if (!$user_id) {
            redirect('auth/login');
        }
        
        $data['title'] = 'Products';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'products';
        
        // Get products with customer-specific pricing
        $data['grouped_products'] = $this->Product_model->get_products_grouped_with_prices($user_id);
        
        // Get customer info for display
        $data['customer'] = $this->User_model->get_user_by_id($user_id);
        
        $this->load->view('customer/products', $data);
    }

    public function place_order() {
        $user_id = $this->session->userdata('user_id');
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('product_id', 'Product', 'required|numeric');
            $this->form_validation->set_rules('shipping_address', 'Shipping Address', 'required|numeric');
            
            if ($this->form_validation->run() == TRUE) {
                $product_id = $this->input->post('product_id');
                $address_id = $this->input->post('shipping_address');
                
                // Get the selected address
                $selected_address = $this->Customer_address_model->get_address_by_id($address_id, $user_id);
                if (!$selected_address) {
                    $this->session->set_flashdata('error', 'Selected address not found.');
                    redirect('customer/products');
                }
                
                $shipping_address = $this->Customer_address_model->format_address($selected_address);
                
                // Get product details
                $product = $this->Product_model->get_product_by_id($product_id);
                if (!$product) {
                    $this->session->set_flashdata('error', 'Product not found.');
                    redirect('customer/products');
                }
                
                // Get customer-specific price
                $customer_price = $this->Product_model->get_customer_price($product_id, $user_id);
                $total_amount = $customer_price; // Price is per variant, no quantity multiplication needed
                
                // Check wallet balance
                $wallet_balance = $this->Wallet_model->get_balance($user_id);
                if ($wallet_balance === null) {
                    // Create wallet if it doesn't exist
                    $this->Wallet_model->create_wallet($user_id);
                    $wallet_balance = 0;
                }
                
                // Create order
                $order_data = array(
                    'order_number' => 'ORD-' . date('Ymd') . '-' . rand(1000, 9999),
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'total_amount' => $total_amount,
                    'shipping_address' => $shipping_address,
                    'notes' => $this->input->post('notes'),
                    'status' => 'pending'
                );
                
                $order_id = $this->Order_model->create_order($order_data);
                
                if ($order_id) {
                    // Always deduct from wallet (allows negative balance)
                    $this->Wallet_model->debit_wallet($user_id, $total_amount, "Order #{$order_data['order_number']}", $order_id, 'order');
                    
                    // Add notification for customer
                    $this->Notification_model->add_notification(
                        $user_id,
                        'customer',
                        'Order Placed Successfully',
                        'Your order #' . $order_data['order_number'] . ' has been placed successfully and is being processed.',
                        'success',
                        'order'
                    );
                    
                    // Add notification for admin (user_id 1 for admin)
                    $this->Notification_model->add_notification(
                        1,
                        'admin',
                        'New Order Received',
                        'A new order #' . $order_data['order_number'] . ' has been placed by ' . $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
                        'info',
                        'order'
                    );
                    
                    if ($wallet_balance >= $total_amount) {
                        $this->session->set_flashdata('success', 'Order placed successfully! Order #' . $order_data['order_number']);
                    } else {
                        $this->session->set_flashdata('error', 'Order placed successfully! Order #' . $order_data['order_number'] . ' - PAYMENT REQUIRED: You must recharge your wallet with $' . number_format($total_amount, 2) . ' to process this order!');
                    }
                    redirect('customer/orders');
                } else {
                    $this->session->set_flashdata('error', 'Failed to place order.');
                }
            }
        }
        
        $data['title'] = 'Place Order';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'place_order';
        $data['products'] = $this->Product_model->get_active_products();
        
        // Get customer-specific prices for all products
        $data['customer_prices'] = array();
        foreach ($data['products'] as $product) {
            $data['customer_prices'][$product->id] = $this->Product_model->get_customer_price($product->id, $user_id);
        }
        
        // Get selected product_id from URL parameter
        $data['selected_product_id'] = $this->input->get('product_id');
        
        // Get wallet balance for JavaScript
        $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        if (!$data['wallet']) {
            $this->Wallet_model->create_wallet($user_id);
            $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        }
        
        // Get customer addresses
        $data['addresses'] = $this->Customer_address_model->get_addresses_by_user_id($user_id);
        $data['default_address'] = $this->Customer_address_model->get_default_address($user_id);
        
        $this->load->view('customer/place_order', $data);
    }

    public function orders() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'My Orders';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'orders';
        $data['orders'] = $this->Order_model->get_orders_by_user($user_id);
        
        $this->load->view('customer/orders', $data);
    }

    public function order_details($order_id) {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Order Details';
        $data['user'] = $this->session->userdata();
        $data['order'] = $this->Order_model->get_order_details($order_id, $user_id);
        
        if (!$data['order']) {
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('customer/orders');
        }
        
        // Get tracking updates if tracking number exists
        if ($data['order']->tracking_number) {
            $data['tracking_updates'] = $this->Order_model->get_tracking_updates($order_id);
        }
        
        // Get shipping address if order has one
        $data['shipping_address'] = null;
        if (isset($data['order']->shipping_address) && !empty($data['order']->shipping_address)) {
            // Parse the shipping address text into an object for consistency
            $address_lines = explode("\n", $data['order']->shipping_address);
            $data['shipping_address'] = (object) [
                'full_name' => isset($address_lines[0]) ? trim($address_lines[0]) : '',
                'address_line1' => isset($address_lines[1]) ? trim($address_lines[1]) : '',
                'address_line2' => isset($address_lines[2]) && !empty(trim($address_lines[2])) ? trim($address_lines[2]) : '',
                'city_state_zip' => isset($address_lines[3]) ? trim($address_lines[3]) : '',
                'country' => isset($address_lines[4]) ? trim($address_lines[4]) : '',
                'phone' => isset($address_lines[5]) ? str_replace('Phone: ', '', trim($address_lines[5])) : ''
            ];
        }
        
        $this->load->view('customer/order_details', $data);
    }

    public function cancel_order($order_id) {
        $user_id = $this->session->userdata('user_id');
        
        $order = $this->Order_model->get_order_details($order_id, $user_id);
        
        if (!$order) {
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('customer/orders');
        }
        
        if ($order->status !== 'pending') {
            $this->session->set_flashdata('error', 'Only pending orders can be cancelled.');
            redirect('customer/orders');
        }
        
        // Cancel order and refund
        if ($this->Order_model->cancel_order($order_id)) {
            $this->Wallet_model->credit_wallet($user_id, $order->total_amount, "Refund for cancelled order #{$order->order_number}", $order_id, 'refund');
            $this->session->set_flashdata('success', 'Order cancelled and refunded successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to cancel order.');
        }
        
        redirect('customer/orders');
    }

    public function support_tickets() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Support Tickets';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'support_tickets';
        $data['tickets'] = $this->Support_ticket_model->get_tickets_by_user($user_id);
        
        $this->load->view('customer/support_tickets', $data);
    }

    public function create_ticket() {
        $user_id = $this->session->userdata('user_id');
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('subject', 'Subject', 'required|max_length[255]');
            $this->form_validation->set_rules('message', 'Message', 'required');
            $this->form_validation->set_rules('category', 'Category', 'required');
            $this->form_validation->set_rules('priority', 'Priority', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $ticket_data = array(
                    'ticket_number' => 'TKT-' . date('Ymd') . '-' . rand(1000, 9999),
                    'user_id' => $user_id,
                    'subject' => $this->input->post('subject'),
                    'message' => $this->input->post('message'),
                    'category' => $this->input->post('category'),
                    'priority' => $this->input->post('priority'),
                    'status' => 'open'
                );
                
                if ($this->Support_ticket_model->create_ticket($ticket_data)) {
                    // Add notification for customer
                    $this->Notification_model->add_notification(
                        $user_id,
                        'customer',
                        'Support Ticket Created',
                        'Your support ticket has been created successfully. We will respond soon.',
                        'info',
                        'support'
                    );
                    
                    // Add notification for admin
                    $this->Notification_model->add_notification(
                        1,
                        'admin',
                        'New Support Ticket',
                        'A new support ticket has been created by ' . $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'),
                        'warning',
                        'support'
                    );
                    
                    $this->session->set_flashdata('success', 'Support ticket created successfully.');
                    redirect('customer/support_tickets');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create support ticket.');
                }
            }
        }
        
        $data['title'] = 'Create Support Ticket';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'create_ticket';
        
        $this->load->view('customer/create_ticket', $data);
    }

    public function ticket_details($ticket_id) {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Ticket Details';
        $data['user'] = $this->session->userdata();
        $data['ticket'] = $this->Support_ticket_model->get_ticket_details($ticket_id, $user_id);
        $data['replies'] = $this->Support_ticket_model->get_ticket_replies($ticket_id);
        
        if (!$data['ticket']) {
            $this->session->set_flashdata('error', 'Ticket not found.');
            redirect('customer/support_tickets');
        }
        
        $this->load->view('customer/ticket_details', $data);
    }

    public function reply_ticket($ticket_id) {
        $user_id = $this->session->userdata('user_id');
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('message', 'Message', 'required');
            
            if ($this->form_validation->run() == TRUE) {
                $reply_data = array(
                    'ticket_id' => $ticket_id,
                    'user_id' => $user_id,
                    'message' => $this->input->post('message')
                );
                
                if ($this->Support_ticket_model->add_reply($reply_data)) {
                    // Add notification for admin
                    $this->Notification_model->add_notification(
                        1,
                        'admin',
                        'Support Ticket Reply',
                        'Customer ' . $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name') . ' has replied to support ticket #' . $ticket_id . '.',
                        'warning',
                        'support'
                    );
                    
                    $this->session->set_flashdata('success', 'Reply added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add reply.');
                }
            }
        }
        
        redirect('customer/ticket_details/' . $ticket_id);
    }

    public function profile() {
        $user_id = $this->session->userdata('user_id');
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[50]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[50]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('phone', 'Phone', 'max_length[20]');
            
            if ($this->form_validation->run() == TRUE) {
                $update_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone')
                );
                
                if ($this->User_model->update_user($user_id, $update_data)) {
                    // Update session data
                    $this->session->set_userdata($update_data);
                    $this->session->set_flashdata('success', 'Profile updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update profile.');
                }
            }
        }
        
        $data['title'] = 'My Profile';
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['active_page'] = 'profile';
        
        // Get wallet data
        $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        
        // Create wallet if it doesn't exist
        if (!$data['wallet']) {
            $this->Wallet_model->create_wallet($user_id);
            $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        }
        
        // Get counts for statistics
        $data['total_orders'] = $this->Order_model->count_orders_by_user($user_id);
        $data['total_tickets'] = $this->Support_ticket_model->count_tickets_by_user($user_id);
        $data['total_addresses'] = $this->Customer_address_model->count_addresses($user_id);
        $data['wallet_balance'] = $data['wallet'] ? $data['wallet']->balance : 0;
        
        $this->load->view('customer/profile', $data);
    }

    public function change_password() {
        $user_id = $this->session->userdata('user_id');
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('current_password', 'Current Password', 'required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
            
            if ($this->form_validation->run() == TRUE) {
                $user = $this->User_model->get_user_by_id($user_id);
                
                if (password_verify($this->input->post('current_password'), $user->password)) {
                    $update_data = array(
                        'password' => password_hash($this->input->post('new_password'), PASSWORD_DEFAULT)
                    );
                    
                    if ($this->User_model->update_user($user_id, $update_data)) {
                        $this->session->set_flashdata('success', 'Password changed successfully.');
                        redirect('customer/profile');
                    } else {
                        $this->session->set_flashdata('error', 'Failed to change password.');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Current password is incorrect.');
                }
            }
        }
        
        // Show the change password form
        $data['title'] = 'Change Password';
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['active_page'] = 'profile';
        
        $this->load->view('customer/change_password', $data);
    }

    public function update_phone() {
        // Check if it's an AJAX request
        if (!$this->input->is_ajax_request() && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array(
                'success' => false,
                'message' => 'Invalid request type.'
            )));
            return;
        }
        
        $user_id = $this->session->userdata('user_id');
        $phone = $this->input->post('phone');
        
        // Validate phone number
        if (empty($phone)) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array(
                'success' => false,
                'message' => 'Phone number is required.'
            )));
            return;
        }
        
        // Update phone number
        $result = $this->User_model->update_user($user_id, array('phone' => $phone));
        
        if ($result) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array(
                'success' => true,
                'message' => 'Phone number updated successfully!'
            )));
        } else {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array(
                'success' => false,
                'message' => 'Failed to update phone number. Please try again.'
            )));
        }
    }

    public function addresses() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'My Addresses';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'addresses';
        $data['addresses'] = $this->Customer_address_model->get_addresses_by_user_id($user_id);
        
        $this->load->view('customer/addresses', $data);
    }

    public function add_address() {
        $user_id = $this->session->userdata('user_id');
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('address_name', 'Address Name', 'required|max_length[100]');
            $this->form_validation->set_rules('full_name', 'Full Name', 'required|max_length[100]');
            $this->form_validation->set_rules('address_line1', 'Address Line 1', 'required|max_length[255]');
            $this->form_validation->set_rules('city', 'City', 'required|max_length[100]');
            $this->form_validation->set_rules('state', 'State', 'required|max_length[100]');
            $this->form_validation->set_rules('postal_code', 'Postal Code', 'required|max_length[20]');
            $this->form_validation->set_rules('phone', 'Phone', 'max_length[20]');
            
            if ($this->form_validation->run() == TRUE) {
                $address_data = array(
                    'user_id' => $user_id,
                    'address_name' => $this->input->post('address_name'),
                    'full_name' => $this->input->post('full_name'),
                    'address_line1' => $this->input->post('address_line1'),
                    'address_line2' => $this->input->post('address_line2'),
                    'city' => $this->input->post('city'),
                    'state' => $this->input->post('state'),
                    'postal_code' => $this->input->post('postal_code'),
                    'country' => $this->input->post('country') ?: 'USA',
                    'phone' => $this->input->post('phone'),
                    'is_default' => $this->input->post('is_default') ? 1 : 0
                );
                
                if ($this->Customer_address_model->create_address($address_data)) {
                    $this->session->set_flashdata('success', 'Address added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add address.');
                }
                redirect('customer/addresses');
            }
        }
        
        $data['title'] = 'Add New Address';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'add_address';
        
        $this->load->view('customer/add_address', $data);
    }

    public function edit_address($id) {
        $user_id = $this->session->userdata('user_id');
        
        $address = $this->Customer_address_model->get_address_by_id($id);
        if (!$address || $address->user_id != $user_id) {
            $this->session->set_flashdata('error', 'Address not found.');
            redirect('customer/addresses');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('address_name', 'Address Name', 'required|max_length[100]');
            $this->form_validation->set_rules('full_name', 'Full Name', 'required|max_length[100]');
            $this->form_validation->set_rules('address_line1', 'Address Line 1', 'required|max_length[255]');
            $this->form_validation->set_rules('city', 'City', 'required|max_length[100]');
            $this->form_validation->set_rules('state', 'State', 'required|max_length[100]');
            $this->form_validation->set_rules('postal_code', 'Postal Code', 'required|max_length[20]');
            $this->form_validation->set_rules('phone', 'Phone', 'max_length[20]');
            
            if ($this->form_validation->run() == TRUE) {
                $address_data = array(
                    'address_name' => $this->input->post('address_name'),
                    'full_name' => $this->input->post('full_name'),
                    'address_line1' => $this->input->post('address_line1'),
                    'address_line2' => $this->input->post('address_line2'),
                    'city' => $this->input->post('city'),
                    'state' => $this->input->post('state'),
                    'postal_code' => $this->input->post('postal_code'),
                    'country' => $this->input->post('country') ?: 'USA',
                    'phone' => $this->input->post('phone'),
                    'is_default' => $this->input->post('is_default') ? 1 : 0
                );
                
                if ($this->Customer_address_model->update_address($id, $address_data)) {
                    $this->session->set_flashdata('success', 'Address updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update address.');
                }
                redirect('customer/addresses');
            }
        }
        
        $data['title'] = 'Edit Address';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'edit_address';
        $data['address'] = $address;
        
        $this->load->view('customer/edit_address', $data);
    }

    public function delete_address($id) {
        $user_id = $this->session->userdata('user_id');
        
        $address = $this->Customer_address_model->get_address_by_id($id);
        if (!$address || $address->user_id != $user_id) {
            $this->session->set_flashdata('error', 'Address not found.');
            redirect('customer/addresses');
        }
        
        if ($this->Customer_address_model->delete_address($id)) {
            $this->session->set_flashdata('success', 'Address deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete address.');
        }
        
        redirect('customer/addresses');
    }

    public function set_default_address($id) {
        $user_id = $this->session->userdata('user_id');
        
        $address = $this->Customer_address_model->get_address_by_id($id);
        if (!$address || $address->user_id != $user_id) {
            $this->session->set_flashdata('error', 'Address not found.');
            redirect('customer/addresses');
        }
        
        if ($this->Customer_address_model->set_default_address($user_id, $id)) {
            $this->session->set_flashdata('success', 'Default address updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update default address.');
        }
        
        redirect('customer/addresses');
    }

    public function pending_recharges() {
        $user_id = $this->session->userdata('user_id');
        
        $data['title'] = 'Pending Recharge Requests';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'pending_recharges';
        
        // Get pending recharge requests for this user
        $data['pending_recharges'] = $this->Recharge_request_model->get_pending_requests_by_user($user_id);
        
        // Get wallet info
        $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        if (!$data['wallet']) {
            $this->Wallet_model->create_wallet($user_id);
            $data['wallet'] = $this->Wallet_model->get_wallet_by_user_id($user_id);
        }
        
        $this->load->view('customer/pending_recharges', $data);
    }

    public function create_customer_address() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $this->form_validation->set_rules('address_name', 'Address Name', 'required|max_length[255]');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|max_length[255]');
        $this->form_validation->set_rules('address_line1', 'Address Line 1', 'required|max_length[255]');
        $this->form_validation->set_rules('city', 'City', 'required|max_length[255]');
        $this->form_validation->set_rules('state', 'State', 'required|max_length[255]');
        $this->form_validation->set_rules('postal_code', 'Postal Code', 'required|max_length[20]');
        $this->form_validation->set_rules('country', 'Country', 'required|max_length[255]');

        if ($this->form_validation->run() == TRUE) {
            $user_id = $this->session->userdata('user_id');
            
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

            if ($address_data['is_default']) {
                // Remove default from other addresses
                $this->Customer_address_model->remove_default_address($user_id);
            }

            $address_id = $this->Customer_address_model->create_address($address_data);
            
            if ($address_id) {
                echo json_encode(array(
                    'success' => true,
                    'address_id' => $address_id,
                    'address_name' => $address_data['address_name'],
                    'full_name' => $address_data['full_name'],
                    'address_line1' => $address_data['address_line1'],
                    'city' => $address_data['city'],
                    'state' => $address_data['state'],
                    'postal_code' => $address_data['postal_code'],
                    'message' => 'Address added successfully!'
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Failed to add address. Please try again.'
                ));
            }
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => validation_errors()
            ));
        }
    }

    // Chat with Admin Methods
    public function chat_with_admin() {
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        
        $data['title'] = 'Chat with Admin';
        $data['user'] = $user;
        $data['active_page'] = 'chat_with_admin';
        $data['messages'] = $this->Admin_message_model->get_messages_by_customer($user_id);
        
        $this->load->view('customer/chat_with_admin', $data);
    }

    public function send_message_to_admin() {
        // Add debugging
        log_message('debug', 'send_message_to_admin called');
        log_message('debug', 'POST data: ' . print_r($_POST, true));
        log_message('debug', 'Session data: ' . print_r($this->session->userdata(), true));
        log_message('debug', 'Request method: ' . $this->input->method());
        log_message('debug', 'Is AJAX: ' . ($this->input->is_ajax_request() ? 'YES' : 'NO'));
        log_message('debug', 'HTTP_X_REQUESTED_WITH: ' . (isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : 'NOT SET'));
        
        // Check if this is an AJAX request or has the proper header
        $is_ajax = $this->input->is_ajax_request();
        $has_header = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        $is_post = $this->input->method() === 'post';
        
        if (!$is_ajax && !$has_header && !$is_post) {
            log_message('debug', 'Not an AJAX request - rejecting');
            show_404();
        }

        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_user_by_id($user_id);
        $message = trim($this->input->post('message'));

        log_message('debug', 'User ID: ' . $user_id);
        log_message('debug', 'User: ' . print_r($user, true));
        log_message('debug', 'Message: ' . $message);

        if (empty($message)) {
            echo json_encode(array('success' => false, 'message' => 'Message cannot be empty'));
            return;
        }

        if (!$user) {
            echo json_encode(array('success' => false, 'message' => 'User not found'));
            return;
        }

        $customer_name = $user->first_name . ' ' . $user->last_name;
        
        try {
            $message_id = $this->Admin_message_model->add_customer_message($user_id, $customer_name, $message);
            
            log_message('debug', 'Message ID returned: ' . $message_id);
            
            if ($message_id) {
                // Add notification for admin
                $this->Notification_model->add_notification(
                    1,
                    'admin',
                    'New Customer Message',
                    'Customer ' . $customer_name . ' has sent you a new message.',
                    'info',
                    'chat'
                );
                
                echo json_encode(array(
                    'success' => true,
                    'message_id' => $message_id,
                    'message' => 'Message sent successfully'
                ));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Failed to add message to database'));
            }
        } catch (Exception $e) {
            log_message('error', 'Exception in send_message_to_admin: ' . $e->getMessage());
            echo json_encode(array('success' => false, 'message' => 'Database error: ' . $e->getMessage()));
        }
    }

    public function check_admin_response() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->session->userdata('user_id');
        $has_unread = $this->Admin_message_model->customer_has_unread($user_id);
        
        echo json_encode(array(
            'success' => true,
            'has_unread' => $has_unread
        ));
    }

    public function get_new_messages() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->session->userdata('user_id');
        $last_id = $this->input->get('last_id', 0);
        
        // Get new messages since last_id
        $new_messages = $this->db->where('customer_id', $user_id)
                                 ->where('id >', $last_id)
                                 ->where('is_admin_reply', TRUE)
                                 ->order_by('created_at', 'ASC')
                                 ->get('admin_messages')
                                 ->result();
        
        // Mark messages as read
        if (!empty($new_messages)) {
            $this->Admin_message_model->mark_customer_messages_as_read($user_id);
        }
        
        echo json_encode(array(
            'success' => true,
            'messages' => $new_messages
        ));
    }

    // Notification methods
    public function get_notifications() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->session->userdata('user_id');
        $notifications = $this->Notification_model->get_notifications($user_id, 'customer', 20);
        
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
        $notifications = $this->Notification_model->get_unread_notifications($user_id, 'customer', 10);
        $unread_count = $this->Notification_model->get_unread_count($user_id, 'customer');
        
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
        
        // Verify the notification belongs to this user
        $notification = $this->db->where('id', $notification_id)
                                 ->where('user_id', $user_id)
                                 ->where('user_type', 'customer')
                                 ->get('notifications')
                                 ->row();
        
        if ($notification) {
            $this->Notification_model->mark_as_read($notification_id);
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Notification not found'));
        }
    }

    public function mark_all_notifications_read() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->session->userdata('user_id');
        $this->Notification_model->mark_all_as_read($user_id, 'customer');
        
        echo json_encode(array('success' => true));
    }

    public function notifications() {
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Notifications';
        $data['user'] = $this->session->userdata();
        $data['active_page'] = 'notifications';
        $data['notifications'] = $this->Notification_model->get_notifications($user_id, 'customer', 50);
        $data['unread_count'] = $this->Notification_model->get_unread_count($user_id, 'customer');
        
        $this->load->view('customer/notifications', $data);
    }
} 