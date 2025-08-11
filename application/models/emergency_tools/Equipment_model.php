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
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type, t.icon_url');
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
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type, t.icon_url');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->where('e.id', $id);
        return $this->db->get()->row();
    }

    public function get_by_code($equipment_code)
    {
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type, t.icon_url');
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

        // Use single optimized query with OR conditions for better performance
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type, t.icon_url');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');

        // Try multiple match patterns in single query
        $this->db->group_start();
        $this->db->where('e.qrcode', $qrcode);
        $this->db->or_where('UPPER(e.equipment_code)', $qrcode);
        $this->db->or_like('UPPER(e.equipment_code)', $qrcode);
        $this->db->group_end();

        $this->db->where('e.status', 'active');
        $this->db->limit(1); // Only need one result

        $result = $this->db->get()->row();

        if ($result) {
            return $result;
        }

        // Only if no result, try to extract equipment code from QR filename
        if (strpos($qrcode, 'QR_') !== false || strpos($qrcode, '_') !== false) {
            // Extract from filename like: qr_test_003_1754548121.png -> TEST-003
            $parts = explode('_', $qrcode);
            if (count($parts) >= 3) {
                $extracted_code = strtoupper(implode('-', array_slice($parts, 1, -1)));

                $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type, t.icon_url');
                $this->db->from($this->table . ' e');
                $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
                $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
                $this->db->where('UPPER(e.equipment_code)', $extracted_code);
                $this->db->where('e.status', 'active');
                $this->db->limit(1);

                return $this->db->get()->row();
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

    public function get_all_types()
    {
        return $this->db->get('tm_master_equipment_types')->result();
    }

    public function get_by_location($location_id)
    {
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type, t.icon_url');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->where('e.location_id', $location_id);
        $this->db->where('e.status', 'active');
        return $this->db->get()->result();
    }

    public function get_by_type_and_location($equipment_type_id, $location_id = null)
    {
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type, t.icon_url');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->where('e.equipment_type_id', $equipment_type_id);
        if ($location_id) {
            $this->db->where('e.location_id', $location_id);
        }
        $this->db->where('e.status', 'active');
        $this->db->order_by('e.equipment_code', 'ASC');
        return $this->db->get()->result();
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function get_equipment_status_summary()
    {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = "inactive" THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN status = "maintenance" THEN 1 ELSE 0 END) as maintenance
        ');
        $this->db->from($this->table);
        return $this->db->get()->row_array();
    }

    public function get_equipment_with_location()
    {
        $this->db->select('e.*, l.location_name, l.location_code, t.equipment_name, t.equipment_type');
        $this->db->from($this->table . ' e');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->where('e.status', 'active');
        return $this->db->get()->result();
    }
}
