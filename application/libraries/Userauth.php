<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Userauth  { 
	  
    private $login_page = "";   
    private $logout_page = "";   
     
    private $username;
    private $password;
    private $userAccounts;
    private $accesslevel;
    private $is_freezed;

    /**
    * Turn off notices so we can have session_start run twice
    */
    function __construct() 
    {
      error_reporting(E_ALL & ~E_NOTICE);
      $this->CI=& get_instance(); 
      $this->CI->load->database();
      $this->CI->load->model('Login_auth','',TRUE);
      $this->login_page = base_url() . "index.php?/Login";
      $this->logout_page = base_url() . "index.php?/Home";
      $this->userAccounts = $this->CI->Login_auth->getData();
    }

    /**
    * @return string
    * @desc Login handling
    */
    public function login($username,$password) 
    {

      session_start();
        
      // User is already logged in if SESSION variables are good. 
      if ($this->validSessionExists() == true)
      {
        $this->redirect($_SESSION['basepage']);
      }

      // First time users don't get an error message.... 
      if ($_SERVER['REQUEST_METHOD'] == 'GET') return;
        
      // Check login form for well formedness.....if bad, send error message
      if ($this->formHasValidCharacters($username, $password) == false)
      {
         return "Username/password fields cannot be blank!";
      }
        
      // verify if form's data coresponds to database's data
      if ($this->userIsInDatabase() == false)
      {
        return 'Invalid username/password!';
      }
      elseif ($this->userIsInDatabase() && $this->checkFreezedUser()) {
        return 'Account Frozen!';      }
      else
      { 
        // We're in!
        // Redirect authenticated users to the correct landing page
        // ex: admin goes to admin, members go to members
        $this->writeSession();
        $this->redirect($_SESSION['basepage']);
      }
    }
	
    /**
    * @return void
    * @desc Validate if user is logged in
    */
    public function loggedin() 
    {

      session_start();     
   
      // Users who are not logged in are redirected out
      if ($this->validSessionExists() == false)
      {
        $this->redirect($this->login_page);
      }
       
		 
      // Access Control List checking goes here..
      // Does user have sufficient permissions to access page?
      // Ex. Can a bronze level access the Admin page?   
      // Loads a config file named blog_settings.php and assigns it to an index named "blog_settings"
      $this->CI->config->load('acl', TRUE);
      //$this->CI->load->library('acl');

      // Retrieve a config item named site_name contained within the blog_settings array
      $acl_array = $this->CI->config->item('acl');
      $access_control = $acl_array['acl'][$_SESSION['page']][$_SESSION['accesslevel']];

      if(!$access_control)
      {
        $_SESSION['access_control'] = $access_control;
        $this->redirect($_SESSION['basepage']);
      }
      
      return true;
    }
	
    /**
    * @return void
    * @desc The user will be logged out.
    */
    public function logout() 
    {
      session_start(); 
      $_SESSION = array();
      session_destroy();
      header("Location: ".$this->logout_page);
    }
    
    /**
    * @return bool
    * @desc Verify if user has got a session and if the user's IP corresonds to the IP in the session.
    */
    public function validSessionExists() 
    {
      session_start();
      if (!isset($_SESSION['username']))
      {
        return false;
      }
      else
      {
        return true;
      }
    }
    
    /**
    * @return void
    * @desc Verify if login form fields were filled out correctly
    */
    public function formHasValidCharacters($username, $password) 
    {
      // check form values for strange characters and length (3-12 characters).
      // if both values have values at this point, then basic requirements met
      if ( (empty($username) == false) && (empty($password) == false) )
      {
        $this->username = $username;
        $this->password = $password;
        return true;
      }
      else
      {
        return false;
      }
    }
	
    /**
    * @return bool
    * @desc Verify username and password with MySQL database.
    */
    public function userIsInDatabase() 
    {

      // Remember: you can get CodeIgniter instance from within a library with:
      // $CI =& get_instance();
      // And then you can access database query method with:
      // $CI->db->query()
        
      // Access database to verify username and password from database table

      
      foreach ($this->userAccounts as $value) 
      {
        if (($this->username == $value['username']) && ($this->password == $value['password']) )
        {    
          $this->accesslevel = $value['accesslevel'];
          $this->is_freezed = $value['freeze'];
          return true;
        } 
      }
    }

    public function checkFreezedUser()
    {
      if($this->is_freezed == "Y")
      {
        return true;
      }
      elseif ($this->is_freezed == "N") 
      {
        return false;
      }
    }
    
    /**
    * @return void
    * @param string $page
    * @desc Redirect the browser to the value in $page.
    */
    public function redirect($page) 
    {
        header("Location: ".$page);
        exit();
    }
    
    /**
    * @return void
    * @desc Write username and other data into the session.
    */
    public function writeSession() 
    {
        $_SESSION['username'] = $this->username;
        $_SESSION['accesslevel'] = $this->accesslevel;

        if($this->accesslevel == "member")
        {
          $this->accesslevel = "Members";
        }
        else if($this->accesslevel == "editor")
        {
          $this->accesslevel = "Editors";
        }
        else if ($this->accesslevel == "admin") 
        {
          $this->accesslevel = "Admin";
        }
        else
        {
          $this->accesslevel = "";
        }

        $_SESSION['basepage'] = base_url() . "index.php?/".$this->accesslevel;
    }
	
    /**
    * @return string
    * @desc Username getter, not necessary 
    */
    public function getUsername() 
    {
        return $_SESSION['username'];
    }
		 
}
