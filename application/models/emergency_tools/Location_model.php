<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Location_model extends CI_Model
{
    private $table = 'tm_locations';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $this->db->order_by('location_name', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_by_code($location_code)
    {
        return $this->db->get_where($this->table, ['location_code' => $location_code])->row();
    }

    public function get_with_equipment_count()
    {
        $this->db->select('l.*, COUNT(e.id) as equipment_count');
        $this->db->from($this->table . ' l');
        $this->db->join('tm_equipments e', 'e.location_id = l.id', 'left');
        $this->db->group_by('l.id');
        $this->db->order_by('l.location_name', 'ASC');
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
}
