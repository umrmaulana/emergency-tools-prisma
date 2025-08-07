<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Equipment_model extends CI_Model
{
    private $table = 'tm_equipments';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_all_with_details()
    {
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->where('e.status', 'active');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_id_with_details($id)
    {
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->where('e.id', $id);
        return $this->db->get()->row();
    }

    public function get_by_code($equipment_code)
    {
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->where('e.equipment_code', $equipment_code);
        return $this->db->get()->row();
    }

    public function get_by_qrcode($qrcode)
    {
        // Trim whitespace and convert to uppercase for consistency
        $qrcode = strtoupper(trim($qrcode));

        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');

        // First, try exact match with QR code path
        $this->db->where('e.qrcode', $qrcode);
        $result = $this->db->get()->row();

        if ($result) {
            return $result;
        }

        // Reset query for next attempt
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');

        // Second, try to find by equipment_code (case insensitive)
        $this->db->where('UPPER(e.equipment_code)', $qrcode);
        $result = $this->db->get()->row();

        if ($result) {
            return $result;
        }

        // Third, try to find equipment_code that contains the QR code
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->like('UPPER(e.equipment_code)', $qrcode);
        $result = $this->db->get()->row();

        if ($result) {
            return $result;
        }

        // Fourth, try to extract equipment code from QR path
        if (strpos($qrcode, 'qr_') !== false) {
            // Extract from filename like: qr_test_003_1754548121.png -> TEST-003
            $parts = explode('_', $qrcode);
            if (count($parts) >= 3) {
                $extracted_code = strtoupper(implode('-', array_slice($parts, 1, -1)));

                $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type');
                $this->db->from($this->table . ' e');
                $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
                $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
                $this->db->where('UPPER(e.equipment_code)', $extracted_code);
                $result = $this->db->get()->row();

                if ($result) {
                    return $result;
                }
            }
        }

        return null;
    }

    public function update_last_check($id)
    {
        $data = [
            'last_check_date' => date('Y-m-d H:i:s'),
            'update_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
