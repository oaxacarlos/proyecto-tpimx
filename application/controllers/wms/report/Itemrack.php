<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemrack extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();

    $this->load->model('model_mst_bin','',TRUE);
    $this->load->model('model_tsc_item_sn','',TRUE);
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'stockracktwo'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('wms/report/itemrack/v_index', $data);
      }
  }
  //---

  function get_rack_item(){
      $item_code = $_POST['id'];

      $this->load->model('model_tsc_item_sn','',TRUE);

      $this->model_tsc_item_sn->item_code = $item_code;
      $result = $this->model_tsc_item_sn->list_item_location_by_item_code_and_status_one_two();
      $data['var_item_loc'] = assign_data($result);
      $data["item_code"] = $item_code;
      $this->load->view('wms/report/itemrack/v_rack', $data);
  }
  //---
}
