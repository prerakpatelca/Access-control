<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

  var $TPL;

  public function __construct()
  {
    parent::__construct();
    // Your own constructor code
    $_SESSION['page'] = "home";
    $this->TPL['loggedin'] = $this->userauth->validSessionExists();
    $this->TPL['active'] = array('home' => true,
                                 'members'=>false,
                                 'editors'=>false,
                                 'admin' => false,
                                 'login'=>false);

  }

  public function index()
  {
    $this->TPL['displayData'] = $this->login_auth->getData();
    $this->template->show('home', $this->TPL);
  }
}