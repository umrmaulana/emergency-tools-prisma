<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inspection_model extends CI_Model
{
    private $table = 'tr_inspections';
    private $detail_table = 'tr_inspection_details';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $this->db->select('i.*, u.name as inspector_name, e.equipment_code, l.location_name, t.equipment_name');
        $this->db->from($this->table . ' i');
        $this->db->join('users u', 'u.id = i.user_id', 'left');
        $this->db->join('tm_equipments e', 'e.id = i.equipment_id', 'left');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->order_by('i.inspection_date', 'DESC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_user($user_id)
    {
        $this->db->select('i.*, e.equipment_code, l.location_name, t.equipment_name');
        $this->db->from($this->table . ' i');
        $this->db->join('tm_equipments e', 'e.id = i.equipment_id', 'left');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->where('i.user_id', $user_id);
        $this->db->order_by('i.inspection_date', 'DESC');
        return $this->db->get()->result();
    }

    public function get_with_details($inspection_id)
    {
        $this->db->select('i.*, u.name as inspector_name, e.equipment_code, l.location_name, t.equipment_name');
        $this->db->from($this->table . ' i');
        $this->db->join('users u', 'u.id = i.user_id', 'left');
        $this->db->join('tm_equipments e', 'e.id = i.equipment_id', 'left');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->where('i.id', $inspection_id);
        return $this->db->get()->row();
    }

    public function get_details($inspection_id)
    {
        $this->db->select('id.*, ct.item_name, ct.standar_condition');
        $this->db->from($this->detail_table . ' id');
        $this->db->join('tm_checksheet_templates ct', 'ct.id = id.checksheet_item_id', 'left');
        $this->db->where('id.inspection_id', $inspection_id);
        $this->db->order_by('ct.order_number', 'ASC');
        return $this->db->get()->result();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function insert_detail($data)
    {
        $this->db->insert($this->detail_table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function update_approval_status($id, $status, $approved_by = null)
    {
        $data = [
            'approval_status' => $status,
            'approved_by' => $approved_by,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        // Delete details first due to foreign key
        $this->db->where('inspection_id', $id)->delete($this->detail_table);
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function get_pending_inspections()
    {
        $this->db->select('i.*, u.name as inspector_name, e.equipment_code, l.location_name, t.equipment_name');
        $this->db->from($this->table . ' i');
        $this->db->join('users u', 'u.id = i.user_id', 'left');
        $this->db->join('tm_equipments e', 'e.id = i.equipment_id', 'left');
        $this->db->join('tm_locations l', 'l.id = e.location_id', 'left');
        $this->db->join('tm_master_equipment_types t', 't.id = e.equipment_type_id', 'left');
        $this->db->where('i.approval_status', 'pending');
        $this->db->order_by('i.inspection_date', 'DESC');
        return $this->db->get()->result();
    }

    public function count_by_status($status = null)
    {
        if ($status) {
            $this->db->where('approval_status', $status);
        }
        return $this->db->count_all_results($this->table);
    }
}
