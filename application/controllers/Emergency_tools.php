<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Emergency_tools extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load models
        $this->load->model([
            'User_model',
            'emergency_tools/Equipment_model',
            'emergency_tools/Location_model',
            'emergency_tools/Inspection_model',
            'emergency_tools/Checksheet_model'
        ]);

        // Load libraries
        $this->load->library(['session', 'form_validation', 'upload']);

        // Load helpers
        $this->load->helper(['url', 'form', 'html']);

        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Check if user is inspector
        if ($this->session->userdata('level') !== 'inspector') {
            $this->session->set_flashdata('error', 'Access denied. Inspector access required.');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['title'] = 'Emergency Tools - Inspector';
        $data['user'] = $this->User_model->get_by_id($this->session->userdata('user_id'));
        $data['equipments'] = $this->Equipment_model->get_all();
        $this->load->view('emergency_tools/index', $data);
    }

    public function location()
    {
        $data['title'] = 'Select Location - Emergency Tools';
        $data['user'] = $this->User_model->get_by_id($this->session->userdata('user_id'));
        $data['locations'] = $this->Location_model->get_all();
        $data['equipment_types'] = $this->Equipment_model->get_all_types();
        $this->load->view('emergency_tools/location', $data);
    }

    public function checksheet($location_id = null)
    {
        if (!$location_id) {
            redirect('emergency_tools/location');
        }

        $data['title'] = 'Checksheet - Emergency Tools';
        $data['user'] = $this->User_model->get_by_id($this->session->userdata('user_id'));
        $data['location'] = $this->Location_model->get_by_id($location_id);
        $data['equipment_types'] = $this->Equipment_model->get_all_types();
        $data['equipments_by_location'] = $this->Equipment_model->get_by_location($location_id);

        if (!$data['location']) {
            $this->session->set_flashdata('error', 'Location not found');
            redirect('emergency_tools/location');
        }

        $this->load->view('emergency_tools/checksheet', $data);
    }

    public function get_equipments_by_type()
    {
        $equipment_type_id = $this->input->post('equipment_type_id');
        $location_id = $this->input->post('location_id');

        $equipments = $this->Equipment_model->get_by_type_and_location($equipment_type_id, $location_id);

        echo json_encode(['equipments' => $equipments]);
    }

    public function process_qr()
    {
        $qr_code = $this->input->post('qr_code');
        $location_id = $this->input->post('location_id');

        // Trim whitespace and validate input
        $qr_code = trim($qr_code);

        if (empty($qr_code)) {
            echo json_encode(['status' => 'error', 'message' => 'QR code is empty']);
            return;
        }

        $equipment = $this->Equipment_model->get_by_qrcode($qr_code);

        if ($equipment) {
            echo json_encode([
                'status' => 'success',
                'equipment' => $equipment,
                'redirect' => base_url('emergency_tools/inspection_form/' . $equipment->id)
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Equipment not found for QR code: ' . $qr_code
            ]);
        }
    }

    public function inspection_form($equipment_id = null)
    {
        if (!$equipment_id) {
            $equipment_id = $this->input->post('equipment_id');
        }

        if (!$equipment_id) {
            redirect('emergency_tools/location');
        }

        $data['title'] = 'Inspection Form - Emergency Tools';
        $data['user'] = $this->User_model->get_by_id($this->session->userdata('user_id'));
        $data['equipment'] = $this->Equipment_model->get_by_id_with_details($equipment_id);
        $data['checksheet_items'] = $this->Checksheet_model->get_by_equipment_type($data['equipment']->equipment_type_id);

        if (!$data['equipment']) {
            $this->session->set_flashdata('error', 'Equipment not found');
            redirect('emergency_tools/location');
        }

        $this->load->view('emergency_tools/inspection_form', $data);
    }

    public function submit_inspection()
    {
        $this->form_validation->set_rules('equipment_id', 'Equipment', 'required|integer');
        $this->form_validation->set_rules('notes', 'Notes', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('emergency_tools/location');
        }

        $equipment_id = $this->input->post('equipment_id');
        $notes = $this->input->post('notes');
        $checksheet_items = $this->input->post('checksheet_items');

        // Start transaction
        $this->db->trans_start();

        // Create inspection record
        $inspection_data = [
            'user_id' => $this->session->userdata('user_id'),
            'equipment_id' => $equipment_id,
            'inspection_date' => date('Y-m-d H:i:s'),
            'notes' => $notes,
            'approval_status' => 'pending'
        ];

        $inspection_id = $this->Inspection_model->insert($inspection_data);

        // Process checksheet items
        if ($checksheet_items && is_array($checksheet_items)) {
            foreach ($checksheet_items as $item_id => $item_data) {
                $detail_data = [
                    'inspection_id' => $inspection_id,
                    'checksheet_item_id' => $item_id,
                    'actual_condition' => $item_data['actual_condition'],
                    'note' => $item_data['note'],
                    'status' => $item_data['status']
                ];

                // Handle photo upload if exists
                if (!empty($_FILES['photo_' . $item_id]['name'])) {
                    $photo_url = $this->_upload_photo($item_id);
                    if ($photo_url) {
                        $detail_data['photo_url'] = $photo_url;
                    }
                }

                $this->Inspection_model->insert_detail($detail_data);
            }
        }

        // Update equipment last check date
        $this->Equipment_model->update_last_check($equipment_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to submit inspection');
        } else {
            $this->session->set_flashdata('success', 'Inspection submitted successfully');
        }

        redirect('emergency_tools/location');
    }

    private function _upload_photo($item_id)
    {
        $config['upload_path'] = './assets/emergency_tools/img/inspection/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 2048; // 2MB
        $config['file_name'] = 'inspection_' . $item_id . '_' . time();

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('photo_' . $item_id)) {
            $upload_data = $this->upload->data();
            return 'assets/emergency_tools/img/inspection/' . $upload_data['file_name'];
        }

        return false;
    }

    // Debug method to test QR codes
    public function debug_qr($qr_code = null)
    {
        if (!$qr_code) {
            echo "Usage: /emergency_tools/debug_qr/YOUR_QR_CODE<br>";
            echo "Available equipment codes in database:<br>";
            $all_equipment = $this->Equipment_model->get_all();
            foreach ($all_equipment as $eq) {
                echo "- Code: {$eq->equipment_code}, QR Path: {$eq->qrcode}<br>";
            }
            return;
        }

        echo "Testing QR Code: " . $qr_code . "<br><br>";

        // Test using model method
        $model_result = $this->Equipment_model->get_by_qrcode($qr_code);
        echo "Model Method Result: " . ($model_result ? "Found - " . $model_result->equipment_code : "Not found") . "<br>";
    }
}
