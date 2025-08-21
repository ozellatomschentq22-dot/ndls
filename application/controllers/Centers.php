<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Centers extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in and has admin role
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        if ($this->session->userdata('role') !== 'admin') {
            show_404();
        }
        
        // Load required models
        $this->load->model('Dropshipment_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }
    
    // Main listing page
    public function index() {
        $data['title'] = 'Manage Centers';
        $data['active_page'] = 'centers';
        
        // Get centers data - show all centers (active and inactive) for admin
        $data['centers'] = $this->Dropshipment_model->get_all_centers(true);
        
        // Get center usage statistics
        $data['center_stats'] = $this->Dropshipment_model->get_center_summary();
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('centers/list', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Add new center
    public function add() {
        $data['title'] = 'Add Center';
        $data['active_page'] = 'centers';
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Center Name', 'required|trim|is_unique[centers.name]');
            $this->form_validation->set_rules('location', 'Location', 'trim');
            
            if ($this->form_validation->run() == TRUE) {
                $center_data = array(
                    'name' => $this->input->post('name'),
                    'location' => $this->input->post('location'),
                    'status' => 'active'
                );
                
                if ($this->Dropshipment_model->add_center($center_data)) {
                    $this->session->set_flashdata('success', 'Center added successfully.');
                    redirect('centers');
                } else {
                    $this->session->set_flashdata('error', 'Failed to add center.');
                }
            }
        }
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('centers/form', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Edit center
    public function edit($id) {
        $data['title'] = 'Edit Center';
        $data['active_page'] = 'centers';
        $data['center'] = $this->Dropshipment_model->get_center_by_id($id);
        
        if (!$data['center']) {
            $this->session->set_flashdata('error', 'Center not found.');
            redirect('centers');
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Center Name', 'required|trim');
            $this->form_validation->set_rules('location', 'Location', 'trim');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
            
            if ($this->form_validation->run() == TRUE) {
                $center_data = array(
                    'name' => $this->input->post('name'),
                    'location' => $this->input->post('location'),
                    'status' => $this->input->post('status')
                );
                
                if ($this->Dropshipment_model->update_center($id, $center_data)) {
                    $this->session->set_flashdata('success', 'Center updated successfully.');
                    redirect('centers');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update center.');
                }
            }
        }
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('centers/form', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // View center details
    public function view($id) {
        $data['title'] = 'Center Details';
        $data['active_page'] = 'centers';
        $data['center'] = $this->Dropshipment_model->get_center_by_id($id);
        
        if (!$data['center']) {
            $this->session->set_flashdata('error', 'Center not found.');
            redirect('centers');
        }
        
        // Get orders for this center
        $data['orders'] = $this->Dropshipment_model->get_orders_by_center($data['center']->name);
        
        // Get center statistics
        $center_stats = $this->Dropshipment_model->get_center_summary();
        $data['stats'] = null;
        foreach ($center_stats as $stat) {
            if ($stat->center === $data['center']->name) {
                $data['stats'] = $stat;
                break;
            }
        }
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('centers/view', $data);
        $this->load->view('admin/includes/footer');
    }
    
    // Delete center
    public function delete($id) {
        $center = $this->Dropshipment_model->get_center_by_id($id);
        
        if (!$center) {
            $this->session->set_flashdata('error', 'Center not found.');
        } else {
            // Check if center has any orders
            $orders_count = $this->Dropshipment_model->count_orders_by_center($center->name);
            
            if ($orders_count > 0) {
                $this->session->set_flashdata('error', 'Cannot delete center. It has ' . $orders_count . ' associated orders.');
            } else {
                if ($this->Dropshipment_model->delete_center($id)) {
                    $this->session->set_flashdata('success', 'Center deleted successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to delete center.');
                }
            }
        }
        
        redirect('centers');
    }
    
    // Toggle center status
    public function toggle_status($id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $center = $this->Dropshipment_model->get_center_by_id($id);
        
        if (!$center) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Center not found']));
            return;
        }
        
        $new_status = $center->status === 'active' ? 'inactive' : 'active';
        
        if ($this->Dropshipment_model->update_center($id, ['status' => $new_status])) {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => true, 'message' => 'Center status updated successfully']));
        } else {
            $this->output->set_content_type('application/json')
                         ->set_output(json_encode(['success' => false, 'message' => 'Failed to update center status']));
        }
    }
} 