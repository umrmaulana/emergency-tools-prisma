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
            'emergency_tools/Checksheet_model',
            'emergency_tools/Attachment_model'
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
        $data['equipments'] = $this->Equipment_model->get_all_with_details();
        $this->load->view('emergency_tools/index', $data);
    }

    public function location()
    {
        $data['title'] = 'Select Location - Emergency Tools';
        $data['user'] = $this->User_model->get_by_id($this->session->userdata('user_id'));
        $data['locations'] = $this->Location_model->get_all();
        $data['equipment_types'] = $this->Equipment_model->get_all_types();
        $data['all_equipments'] = $this->Equipment_model->get_all_with_details();
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
        // Set JSON content type immediately for faster response
        $this->output->set_content_type('application/json');

        $qr_code = $this->input->post('qr_code');
        $location_id = $this->input->post('location_id');

        // Trim whitespace and validate input
        $qr_code = trim($qr_code);

        if (empty($qr_code)) {
            $this->output->set_output(json_encode(['status' => 'error', 'message' => 'QR code is empty']));
            return;
        }

        // Use optimized database query
        $equipment = $this->Equipment_model->get_by_qrcode($qr_code);

        if ($equipment) {
            $this->output->set_output(json_encode([
                'status' => 'success',
                'equipment' => $equipment,
                'redirect' => base_url('emergency_tools/inspection_form/' . $equipment->id)
            ]));
        } else {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Equipment not found for QR code: ' . $qr_code
            ]));
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
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('emergency_tools');
            return;
        }

        // Validation rules
        $this->form_validation->set_rules([
            ['field' => 'equipment_id', 'label' => 'Equipment ID', 'rules' => 'required|integer'],
            ['field' => 'location_id', 'label' => 'Location ID', 'rules' => 'required|integer'],
            ['field' => 'checklist_1', 'label' => 'Condition Check', 'rules' => 'required|in_list[GOOD,DEFECT,MISSING]'],
            ['field' => 'checklist_2', 'label' => 'Position Check', 'rules' => 'required|in_list[GOOD,DEFECT,MISSING]'],
            ['field' => 'checklist_3', 'label' => 'Cleanliness Check', 'rules' => 'required|in_list[GOOD,DEFECT,MISSING]'],
            ['field' => 'overall_status', 'label' => 'Overall Status', 'rules' => 'required|in_list[GOOD,DEFECT,MISSING]'],
            ['field' => 'remarks', 'label' => 'Remarks', 'rules' => 'max_length[500]']
        ]);

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('emergency_tools');
            return;
        }

        try {
            // Prepare inspection data
            $inspection_data = [
                'equipment_id' => $this->input->post('equipment_id'),
                'user_id' => $this->session->userdata('user_id'),
                'location_id' => $this->input->post('location_id'),
                'inspection_date' => date('Y-m-d H:i:s'),
                'checklist_1' => $this->input->post('checklist_1'),
                'checklist_2' => $this->input->post('checklist_2'),
                'checklist_3' => $this->input->post('checklist_3'),
                'overall_status' => $this->input->post('overall_status'),
                'remarks' => $this->input->post('remarks') ?: null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Handle main photo upload
            if (isset($_FILES['main_photo']) && $_FILES['main_photo']['error'] === 0) {
                $upload_path = './assets/emergency_tools/img/inspections/';

                // Create directory if it doesn't exist
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }

                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => 'gif|jpg|png|jpeg',
                    'max_size' => 5120, // 5MB
                    'encrypt_name' => true
                ];

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('main_photo')) {
                    $upload_data = $this->upload->data();
                    $inspection_data['photo_path'] = 'assets/emergency_tools/img/inspections/' . $upload_data['file_name'];
                } else {
                    throw new Exception('Main photo upload failed: ' . $this->upload->display_errors());
                }
            }

            // Start transaction
            $this->db->trans_start();

            // Insert inspection record
            $inspection_id = $this->Inspection_model->insert($inspection_data);

            if (!$inspection_id) {
                throw new Exception('Failed to save inspection data');
            }

            // Handle additional photos
            $this->_process_additional_photos($inspection_id);

            // Update equipment last check date
            $this->Equipment_model->update_last_check($inspection_data['equipment_id']);

            // Commit transaction
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                throw new Exception('Transaction failed');
            }

            $this->session->set_flashdata('success', 'Inspection submitted successfully with all photos!');
            redirect('emergency_tools');

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Error saving inspection: ' . $e->getMessage());
            redirect('emergency_tools');
        }
    }

    private function _process_additional_photos($inspection_id)
    {
        // Get additional photos from JavaScript array
        $additional_photos = $this->input->post('additional_photos');

        if (empty($additional_photos) || !is_array($additional_photos)) {
            return;
        }

        $upload_path = './assets/emergency_tools/img/attachments/';

        // Create directory if it doesn't exist
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $attachment_data = [];

        foreach ($additional_photos as $index => $photo_data) {
            if (empty($photo_data) || !is_string($photo_data)) {
                continue;
            }

            // Check if it's a base64 encoded image
            if (preg_match('/^data:image\/(\w+);base64,/', $photo_data, $matches)) {
                $image_type = $matches[1];
                $image_data = substr($photo_data, strpos($photo_data, ',') + 1);
                $image_data = base64_decode($image_data);

                if ($image_data === false) {
                    continue;
                }

                // Generate unique filename
                $filename = 'additional_' . $inspection_id . '_' . $index . '_' . time() . '.' . $image_type;
                $full_path = $upload_path . $filename;

                if (file_put_contents($full_path, $image_data)) {
                    $attachment_data[] = [
                        'inspection_id' => $inspection_id,
                        'attachment_type' => 'photo',
                        'file_name' => $filename,
                        'file_path' => 'assets/emergency_tools/img/attachments/' . $filename,
                        'file_size' => strlen($image_data),
                        'mime_type' => 'image/' . $image_type,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }

        // Batch insert attachments
        if (!empty($attachment_data)) {
            $this->Attachment_model->insert_batch($attachment_data);
        }
    }

    private function _upload_photo($item_id)
    {
        // Enable error display for debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Check if file actually exists in $_FILES
        if (!isset($_FILES['photo_' . $item_id]) || $_FILES['photo_' . $item_id]['error'] !== UPLOAD_ERR_OK) {
            log_message('error', 'File upload error for item ' . $item_id . ': ' .
                (isset($_FILES['photo_' . $item_id]) ? $_FILES['photo_' . $item_id]['error'] : 'File not found'));
            return false;
        }

        $config['upload_path'] = FCPATH . 'assets/emergency_tools/img/inspection/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 5120; // 5MB
        $config['max_width'] = 2048;
        $config['max_height'] = 2048;
        $config['file_name'] = 'inspection_' . $item_id . '_' . time();
        $config['overwrite'] = TRUE;

        // Ensure upload directory exists with proper permissions
        if (!is_dir($config['upload_path'])) {
            if (!mkdir($config['upload_path'], 0755, TRUE)) {
                log_message('error', 'Failed to create upload directory: ' . $config['upload_path']);
                echo "<div style='color: red; padding: 5px; border: 1px solid red;'>Failed to create directory: {$config['upload_path']}</div>";
                return false;
            }
        }

        // Check directory permissions
        if (!is_writable($config['upload_path'])) {
            log_message('error', 'Upload directory is not writable: ' . $config['upload_path']);
            echo "<div style='color: red; padding: 5px; border: 1px solid red;'>Directory not writable: {$config['upload_path']}</div>";
            return false;
        }

        // Debug file info
        log_message('info', 'Upload config: ' . json_encode($config));
        log_message('info', 'File info for photo_' . $item_id . ': ' . json_encode($_FILES['photo_' . $item_id]));

        // Re-initialize upload library with new config
        $this->load->library('upload');
        $this->upload->initialize($config);

        if ($this->upload->do_upload('photo_' . $item_id)) {
            $upload_data = $this->upload->data();
            log_message('info', 'Photo uploaded successfully: ' . $upload_data['full_path']);
            log_message('info', 'Upload data: ' . json_encode($upload_data));

            // Verify file was actually created
            if (file_exists($upload_data['full_path'])) {
                echo "<div style='color: green; padding: 5px; border: 1px solid green;'>✓ File uploaded: {$upload_data['file_name']}</div>";
                return 'inspection/' . $upload_data['file_name'];
            } else {
                log_message('error', 'Upload reported success but file does not exist: ' . $upload_data['full_path']);
                return false;
            }
        } else {
            $error = $this->upload->display_errors('', '');
            log_message('error', 'Photo upload failed for item ' . $item_id . ': ' . $error);

            // Display error for debugging
            echo "<div style='color: red; padding: 5px; margin: 5px; border: 1px solid red;'>";
            echo "<strong>Upload Error for item $item_id:</strong><br>";
            echo $error;
            echo "<br>Config: " . json_encode($config);
            echo "<br>File info: " . json_encode($_FILES['photo_' . $item_id]);
            echo "</div>";
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

    // Test upload functionality
    public function test_upload()
    {
        // Enable error display
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        echo "<h2>Upload Test</h2>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<h3>POST Data:</h3><pre>" . print_r($_POST, true) . "</pre>";
            echo "<h3>FILES Data:</h3><pre>" . print_r($_FILES, true) . "</pre>";

            // Test directory
            $upload_dir = './assets/emergency_tools/img/inspection/';
            echo "<h3>Directory Check:</h3>";
            echo "Path: $upload_dir<br>";
            echo "Exists: " . (is_dir($upload_dir) ? 'YES' : 'NO') . "<br>";
            echo "Writable: " . (is_writable($upload_dir) ? 'YES' : 'NO') . "<br>";

            if (!empty($_FILES['test_file']['name'])) {
                $config['upload_path'] = $upload_dir;
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 5120;
                $config['file_name'] = 'test_' . time();

                $this->upload->initialize($config);

                if ($this->upload->do_upload('test_file')) {
                    $data = $this->upload->data();
                    echo "<div style='color: green;'>✓ Upload Success!</div>";
                    echo "<pre>" . print_r($data, true) . "</pre>";
                } else {
                    echo "<div style='color: red;'>✗ Upload Failed:</div>";
                    echo "<div>" . $this->upload->display_errors() . "</div>";
                }
            }
        }

        echo '<form method="POST" enctype="multipart/form-data">
            <input type="file" name="test_file" accept="image/*" required>
            <input type="hidden" name="test_data" value="hello">
            <button type="submit">Test Upload</button>
        </form>';
    }
    public function view_inspection($inspection_id = null)
    {
        if (!$inspection_id) {
            $this->session->set_flashdata('error', 'Inspection ID is required');
            redirect('emergency_tools');
        }

        $data['user'] = $this->User_model->get_by_id($this->session->userdata('user_id'));
        $data['inspection'] = $this->Inspection_model->get_with_details($inspection_id);
        $data['attachments'] = $this->Attachment_model->get_inspection_attachments($inspection_id);

        if (!$data['inspection']) {
            $this->session->set_flashdata('error', 'Inspection not found');
            redirect('emergency_tools');
        }

        $data['page_title'] = 'Inspection Details';
        $this->load->view('emergency_tools/inspection_details', $data);
    }

    public function download_attachment($attachment_id)
    {
        $attachment = $this->Attachment_model->get_by_id($attachment_id);

        if (!$attachment) {
            show_404();
        }

        $file_path = FCPATH . $attachment->file_path;

        if (!file_exists($file_path)) {
            show_404();
        }

        $this->load->helper('download');
        force_download($attachment->file_name, file_get_contents($file_path));
    }

    public function debug_inspection($inspection_id = null)
    {
        if (!$inspection_id) {
            echo "Usage: /emergency_tools/debug_inspection/INSPECTION_ID<br>";
            echo "<br>Recent Inspections:<br>";
            $inspections = $this->Inspection_model->get_all();
            foreach (array_slice($inspections, 0, 10) as $inspection) {
                echo "- Inspection ID: " . $inspection->id . " - Equipment: " . $inspection->equipment_code . " - Date: " . $inspection->inspection_date . "<br>";
            }
            return;
        }

        echo "<h2>Inspection Debug: $inspection_id</h2>";

        $inspection = $this->Inspection_model->get_with_details($inspection_id);
        $attachments = $this->Attachment_model->get_inspection_attachments($inspection_id);

        if ($inspection) {
            echo "<h3>Inspection Info:</h3>";
            echo "<pre>" . print_r($inspection, true) . "</pre>";

            echo "<h3>Inspection Attachments:</h3>";
            if ($attachments) {
                foreach ($attachments as $attachment) {
                    echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
                    echo "<h4>" . $attachment->file_name . "</h4>";
                    echo "<p>File Size: " . number_format($attachment->file_size / 1024, 2) . " KB</p>";
                    echo "<p>MIME Type: " . $attachment->mime_type . "</p>";
                    echo "<p>Created: " . $attachment->created_at . "</p>";
                    if (strpos($attachment->mime_type, 'image/') === 0) {
                        echo "<img src='" . base_url($attachment->file_path) . "' style='max-width: 200px;' />";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>No attachments found</p>";
            }
        } else {
            echo "<h3>Inspection Not Found</h3>";
        }
    }
}
