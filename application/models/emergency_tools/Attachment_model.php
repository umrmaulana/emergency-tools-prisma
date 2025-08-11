<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Attachment_model extends CI_Model
{
    private $table = 'tr_attachments';

    public function __construct()
    {
        parent::__construct();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function insert_batch($data)
    {
        return $this->db->insert_batch($this->table, $data);
    }

    public function get_by_inspection_detail($inspection_detail_id)
    {
        return $this->db->get_where($this->table, ['inspection_detail_id' => $inspection_detail_id])->result();
    }

    public function get_by_inspection($inspection_id)
    {
        $this->db->select('a.*, id.checksheet_item_id');
        $this->db->from($this->table . ' a');
        $this->db->join('tr_inspection_details id', 'id.id = a.inspection_detail_id');
        $this->db->where('id.inspection_id', $inspection_id);
        return $this->db->get()->result();
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function delete_by_inspection_detail($inspection_detail_id)
    {
        return $this->db->where('inspection_detail_id', $inspection_detail_id)->delete($this->table);
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    // Get attachments for specific inspection with details
    public function get_inspection_attachments($inspection_id)
    {
        $this->db->select('a.*, id.checksheet_item_id, ct.item_name');
        $this->db->from($this->table . ' a');
        $this->db->join('tr_inspection_details id', 'id.id = a.inspection_detail_id');
        $this->db->join('tm_checksheet_templates ct', 'ct.id = id.checksheet_item_id', 'left');
        $this->db->where('id.inspection_id', $inspection_id);
        $this->db->order_by('a.created_at', 'ASC');
        return $this->db->get()->result();
    }

    // Get attachment statistics
    public function get_attachment_stats($inspection_id = null)
    {
        if ($inspection_id) {
            $this->db->select('COUNT(*) as total_attachments, 
                              SUM(file_size) as total_size,
                              COUNT(DISTINCT inspection_detail_id) as items_with_attachments');
            $this->db->from($this->table . ' a');
            $this->db->join('tr_inspection_details id', 'id.id = a.inspection_detail_id');
            $this->db->where('id.inspection_id', $inspection_id);
        } else {
            $this->db->select('COUNT(*) as total_attachments, 
                              SUM(file_size) as total_size');
            $this->db->from($this->table);
        }

        return $this->db->get()->row();
    }
}
