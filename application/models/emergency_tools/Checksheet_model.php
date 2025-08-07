<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checksheet_model extends CI_Model
{
    private $table = 'tm_checksheet_templates';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $this->db->select('ct.*, et.equipment_name, et.equipment_type');
        $this->db->from($this->table . ' ct');
        $this->db->join('tm_master_equipment_types et', 'et.id = ct.equipment_type_id', 'left');
        $this->db->order_by('ct.equipment_type_id', 'ASC');
        $this->db->order_by('ct.order_number', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_equipment_type($equipment_type_id)
    {
        $this->db->where('equipment_type_id', $equipment_type_id);
        $this->db->order_by('order_number', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_with_equipment_type()
    {
        $this->db->select('ct.*, et.equipment_name, et.equipment_type');
        $this->db->from($this->table . ' ct');
        $this->db->join('tm_master_equipment_types et', 'et.id = ct.equipment_type_id', 'left');
        $this->db->order_by('et.equipment_name', 'ASC');
        $this->db->order_by('ct.order_number', 'ASC');
        return $this->db->get()->result();
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

    public function get_max_order_number($equipment_type_id)
    {
        $this->db->select_max('order_number');
        $this->db->where('equipment_type_id', $equipment_type_id);
        $result = $this->db->get($this->table)->row();
        return $result->order_number ? $result->order_number : 0;
    }
}
