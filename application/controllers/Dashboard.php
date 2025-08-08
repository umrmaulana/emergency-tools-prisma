<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load models
        $this->load->model(['User_model']);

        // Load libraries
        $this->load->library(['session', 'form_validation']);

        // Load helpers
        $this->load->helper(['url', 'form', 'html']);

        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->User_model->get_by_id($this->session->userdata('user_id'));
        $this->load->view('dashboard/index', $data);
    }

    public function emergency_tools()
    {
        // Redirect to emergency tools index page
        $level = $this->session->userdata('level');

        if ($level == 'inspector') {
            redirect('emergency_tools');
        } else {
            $this->session->set_flashdata('error', 'Access denied for emergency tools.');
            redirect('dashboard');
        }
    }

    public function ppic()
    {
        $data['title'] = 'PPIC';
        $data['user'] = $this->User_model->get_by_id($this->session->userdata('user_id'));
        $data['message'] = 'PPIC module is under development';
        $this->load->view('dashboard/coming_soon', $data);
    }

    public function delivery()
    {
        $data['title'] = 'Delivery';
        $data['user'] = $this->User_model->get_by_id($this->session->userdata('user_id'));
        $data['message'] = 'Delivery module is under development';
        $this->load->view('dashboard/coming_soon', $data);
    }
}
