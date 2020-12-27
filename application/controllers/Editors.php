<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editors extends CI_Controller {

  var $TPL;

  public function __construct()
  {
    parent::__construct();
    // Your own constructor code
    $_SESSION['page'] = "editors";
   $this->TPL['loggedin'] = $this->userauth->loggedin();
   $this->TPL['active'] = array('home' => false,
                                'members'=>false,
                                'editors'=>true,
                                'admin' => false,
                                'login'=>false);

  }

  public function index()
  {
    $this->template->show('editors', $this->TPL);

  }
}