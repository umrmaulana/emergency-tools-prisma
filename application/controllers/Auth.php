<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    /**
     * Halaman login
     */
    public function index()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role();
            return;
        }

        $this->load->view('auth/login');
    }

    /**
     * Proses login
     */
    public function login()
    {
        // Set validation rules
        $this->form_validation->set_rules('username', 'Username/NPK', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Cek login
            $user = $this->User_model->check_login($username, $password);

            if ($user) {
                // Set session
                $session_data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'npk' => $user->npk,
                    'name' => $user->name,
                    'level' => $user->level,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($session_data);

                // Update last login
                $this->User_model->update_last_login($user->id);

                // Redirect berdasarkan role
                $this->_redirect_by_role();
            } else {
                $this->session->set_flashdata('error', 'Username/NPK atau Password salah!');
                redirect('auth');
            }
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }

    /**
     * Redirect berdasarkan role user
     */
    private function _redirect_by_role()
    {
        $level = $this->session->userdata('level');

        // Semua user diarahkan ke dashboard utama
        // Dashboard akan menangani akses berdasarkan level
        redirect('dashboard');
    }
}
