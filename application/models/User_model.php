<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Cek login user berdasarkan username/npk dan password
     */
    public function check_login($username, $password)
    {
        $this->db->where('username', $username);
        $this->db->or_where('npk', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            $user = $query->row();
            // Verifikasi password (gunakan password_verify jika menggunakan hash)
            // Untuk sekarang assumsi password plaintext
            if ($user->password === $password) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Get user by ID
     */
    public function get_user($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    /**
     * Get user by ID (alias untuk kompatibilitas)
     */
    public function get_by_id($id)
    {
        return $this->get_user($id);
    }

    /**
     * Update last login
     */
    public function update_last_login($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->update('users', array('update_at' => date('Y-m-d H:i:s')));
    }
}
