<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_auth extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
      // Your own constructor code
    }

    public function getData()
    {
        $this->load->database();
        return $this->db->get('userslab4')->result_array();
    }

    public function delete($userid)
    {
        $this->load->database();
        $this->db->where('compid',$userid);
        $this->db->delete('userslab4');
    }

    public function create($userData)
    {
        $this->db->insert('userslab4', $userData);
    }

    public function update($userid,$userData)
    {
        $this->load->database();
        $this->db->where('compid',$userid);
        $this->db->update('userslab4',$userData);
    }

    public function validate($string)
    {
        if($string == "member")
        {
            return true;
        }
        elseif ($string == "admin") {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getFreezedValue($userid)
    {
        $this->load->database();
        $this->db->where('compid',$userid);
        $row_array = $this->db->get('userslab4')->row_array();
        return $row_array;
    }
}
