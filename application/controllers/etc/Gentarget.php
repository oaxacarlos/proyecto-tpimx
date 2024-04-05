<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gentarget extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['gentarget'])){
          $this->load->view('view_home');  
      }
      else{
          $this->load->view('v_gentarget');
      }
  }

}

?>
