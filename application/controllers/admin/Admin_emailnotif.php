<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_emailnotif extends CI_Controller{

  function __construct(){
    parent::__construct();
      $this->load->database();
      $this->load->model('model_admin','',TRUE);
      $this->load->model('model_tsc_email','',TRUE);
  }
  //-------------

  function index(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user'][$this->config->item('admin_folder').'admin_emailnotif'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('admin/emailnotif/v_index');
    }
  }
  //-------------------

  function get_data(){
      $date_from = $_POST["date_from"];
      $date_to = $_POST["date_to"];

      $result = $this->model_tsc_email->get_data_by_period($date_from, $date_to);
      $data["var_report"] = assign_data($result);

      $this->load->view('admin/emailnotif/v_report',$data);
  }
  //---

  function update_to_unsent(){

      $id = $_POST["id"];
      $result = $this->model_tsc_email->update_sent("0", "", $id);

      if($result) $response['status'] = "1";
      else $response['status'] = "0";

      echo json_encode($response);
  }



}


?>
