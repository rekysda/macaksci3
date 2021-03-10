<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_mahasiswa extends CI_Model
{

    public function getMahasiswa($limit, $start, $k = null)
    {
        if ($k) {
            return $this->db->like('nama', $k)->or_like('alamat', $k)->or_like('nim', $k)->get('mahasiswa', $limit, $start, $k)->result();
        }
        return $this->db->get('mahasiswa', $limit, $start)->result();
    }

    public function addMahasiswa()
    {
        // $data = [
        //     'nim' => $this->input->post('nim', true),
        //     'nama' => $this->input->post('nama', true),
        //     'email' => $this->input->post('email', true),
        //     'alamat' => $this->input->post('alamat', true)
        // ];
        $data = $this->input->post(['nim', 'nama', 'email', 'alamat']);
        $this->db->insert('mahasiswa', $data);
    }

    public function getMahasiswaById($id)
    {
        return $this->db->get_where('mahasiswa', ['id' => $id])->row();
    }

    public function updateMahasiswa($id)
    {
        $data = $this->input->post(['nim', 'nama', 'email', 'alamat']);
        $this->db->where('id', $id)->update('mahasiswa', $data);
    }

    public function deleteMahasiswa($id)
    {
        $this->db->where('id', $id)->delete('mahasiswa');
    }
}

/* End of file M_mahasiswa.php */
