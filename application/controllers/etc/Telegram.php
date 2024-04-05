<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Telegram extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }
  //----

  function sp_list(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['telegram/sp_list'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('telegram/v_sp_list');
      }
  }
  //---

  function sp_target(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['telegram/sp_target'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('telegram/v_sp_target');
      }
  }
  //---

  function sp_si_vs_target(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['telegram/sp_si_vs_target'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('telegram/v_sp_si_vs_target');
      }
  }
  //---
  
    function sp_message(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['telegram/sp_message'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('telegram/v_sp_message');
      }
  }
  //---
  
  function user_list(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['telegram/user_list'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('telegram/v_user_list');
      }
  }
  //---

  function user_target(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['telegram/user_target'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('telegram/v_user_target');
      }
  }
  //---

  function user_si_vs_target(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['telegram/user_si_vs_target'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('telegram/v_user_si_vs_target');
      }
  }
  //---
  
  function read_bot_message(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['telegram/read_bot_message'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('telegram/v_read_bot_message');
      }
  }
  //---

}

?>
