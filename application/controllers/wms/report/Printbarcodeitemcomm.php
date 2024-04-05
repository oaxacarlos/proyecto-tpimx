<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Printbarcodeitemcomm extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->model('model_mst_item','',TRUE);
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'printbarcodeitemcomm'])){
          $this->load->view('view_home');
      }
      else{
          $data["var_item"] = $this->model_mst_item->get_item_commercial();
          $this->load->view('wms/report/printbarcodeitemcomm/v_index', $data);
      }
  }
  //---
}
