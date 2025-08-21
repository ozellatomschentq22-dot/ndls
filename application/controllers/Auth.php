<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Wallet_model');
    }

    public function index() {
        // Redirect to login if not logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Redirect based on role
        $role = $this->session->userdata('role');
        switch ($role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'staff':
                redirect('staff/dashboard');
                break;
            case 'customer':
                redirect('customer/dashboard');
                break;
            default:
                redirect('auth/login');
        }
    }

    public function login() {
        // Check if already logged in
        if ($this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == TRUE) {
                $username = $this->input->post('username');
                $password = $this->input->post('password');

                $user = $this->User_model->get_user_by_username($username);

                if ($user && password_verify($password, $user->password)) {
                    if ($user->status == 'active') {
                        // Set session data
                        $session_data = array(
                            'user_id' => $user->id,
                            'username' => $user->username,
                            'email' => $user->email,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'role' => $user->role,
                            'logged_in' => TRUE
                        );
                        $this->session->set_userdata($session_data);

                        // Redirect based on role
                        switch ($user->role) {
                            case 'admin':
                                redirect('admin/dashboard');
                                break;
                            case 'staff':
                                redirect('staff/dashboard');
                                break;
                            case 'customer':
                                redirect('customer/dashboard');
                                break;
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Your account is inactive. Please contact administrator.');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Invalid username or password.');
                }
            }
        }

        $data['title'] = 'Login';
        $this->load->view('auth/login', $data);
    }

    public function register() {
        // Check if already logged in
        if ($this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]|min_length[3]|max_length[50]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
            $this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[50]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[50]');
            $this->form_validation->set_rules('phone', 'Phone Number', 'required|max_length[20]|regex_match[/^[\+]?[1-9][\d]{0,15}$/]');
            
            // Set custom error message for phone validation
            $this->form_validation->set_message('regex_match', 'The %s field must contain a valid phone number (e.g., +1234567890 or 1234567890).');

            if ($this->form_validation->run() == TRUE) {
                $user_data = array(
                    'username' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    'role' => 'customer',
                    'status' => 'active'
                );

                $user_id = $this->User_model->create_user($user_data);

                if ($user_id) {
                    // Create wallet for new user
                    $this->Wallet_model->create_wallet($user_id);
                    
                    $this->session->set_flashdata('success', 'Registration successful! Please login.');
                    redirect('auth/login');
                } else {
                    $this->session->set_flashdata('error', 'Registration failed. Please try again.');
                }
            }
        }

        $data['title'] = 'Register';
        $this->load->view('auth/register', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    public function forgot_password() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

            if ($this->form_validation->run() == TRUE) {
                $email = $this->input->post('email');
                $user = $this->User_model->get_user_by_email($email);

                if ($user) {
                    // Generate reset token (simplified - in production, send email)
                    $reset_token = bin2hex(random_bytes(32));
                    $this->User_model->update_reset_token($user->id, $reset_token);
                    
                    $this->session->set_flashdata('success', 'Password reset instructions sent to your email.');
                } else {
                    $this->session->set_flashdata('error', 'Email not found in our system.');
                }
            }
        }

        $data['title'] = 'Forgot Password';
        $this->load->view('auth/forgot_password', $data);
    }
} 