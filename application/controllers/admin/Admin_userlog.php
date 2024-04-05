<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_userlog extends CI_Controller{

  function __construct(){
    parent::__construct();
      $this->load->database();
      $this->load->model('model_admin','',TRUE);
  }
  //-------------
  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('admin_folder').'admin_userlog'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('admin/log/v_index');
      }
  }
  //-------------------

  function get_data(){
      $date_from  = $_POST["date_from"];
      $date_to    = $_POST["date_to"];

      $result = $this->model_admin->get_user_log_data($date_from, $date_to);
      $data["var_report"] = assign_data($result);

      $this->load->view('admin/log/v_report',$data);
  }
  //---

}


?>
