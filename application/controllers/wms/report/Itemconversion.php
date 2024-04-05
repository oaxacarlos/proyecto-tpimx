<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemconversion extends CI_Controller{

  function __construct(){
      parent::__construct();
      $this->load->database();

      $this->load->model('model_report','',TRUE);
  }
  //--

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'itemconversion'])){
          $this->load->view('view_home');
      }
      else{
          $result = $this->model_report->item_conversion();
          $data["var_report"] = assign_data($result); 
          $this->load->view('wms/report/itemconversion/v_index', $data);
      }
  }
  //--
}
