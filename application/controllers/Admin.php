<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

  var $TPL;

  public function __construct()
  {
    parent::__construct();
    // Your own constructor code
    $_SESSION['page'] = "admin";
    $this->TPL['loggedin'] = $this->userauth->loggedin();
    $this->load->model('Login_auth');
    $this->TPL['users'] = $this->Login_auth->getData();
    $this->TPL['active'] = array('home' => false,
                                'members'=>false,
                                'editors'=>false,
                                'admin' => true,
                                'login'=>false);
  }

  public function index()
  {
    $this->template->show('admin', $this->TPL);
  }

  public function deleteuser($userid)
  {
    $this->Login_auth->delete($userid);
    redirect($_SESSION['basepage']);
  }

  public function createuser()
  {
    $this->form_validation->set_rules('username', 'Username','required|is_unique[userslab4.username]',array('is_unique' => "A user with that username already exists!"));
    $this->form_validation->set_rules('password', 'Password','required',array());
    $this->form_validation->set_rules('accesslevel', 'Accesslevel','required|callback_validate_access',array('validate_access' => "Access level must be either member or admin."));
      

      
    if ($this->form_validation->run() == FALSE)
    {
      $this->template->show('admin', $this->TPL);
    }
    else
    {
      $userData = array();
      $userData['username'] = $this->input->post('username');
      $userData['password'] = $this->input->post('password');
      $userData['accesslevel'] = $this->input->post('accesslevel');
      $userData['freeze'] = 'N';
      $this->Login_auth->create($userData);
      redirect($_SESSION['basepage']);
    }
  }

  public function freezeuser($userid)
  {
    $userData = array();
    $row_array = $this->Login_auth->getFreezedValue($userid);
    if($row_array['freeze'] == 'Y')
    {
      $userData['freeze'] = 'N';
    }
    elseif ($row_array['freeze'] == 'N') 
    {
      $userData['freeze'] = 'Y';
    }
    $this->Login_auth->update($userid,$userData);
    redirect($_SESSION['basepage']);
  }

  public function validate_access($string)
  {
     return $this->Login_auth->validate($string);

  }

}