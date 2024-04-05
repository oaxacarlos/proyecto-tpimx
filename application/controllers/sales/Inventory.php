<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller{

  function __construct(){
    parent::__construct();
       $this->load->model('model_sales_report','',TRUE);
       $this->load->model('model_zlog','',TRUE);
  }
  //--

  function avalbincom(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'inventory/avalbincom'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Available Inventory Incoming"); // insert log

          $result = $this->model_sales_report->get_invt_available_incoming();
          $data["var_report"] = assign_data($result);
          $this->load->view('sales/inventory/v_avalbincom', $data);
      }
  }
  //---

  function incoming(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'inventory/incoming'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Inventory Incoming"); // insert log

          $result = $this->model_sales_report->get_inventory_incoming();
          $data["var_report"] = assign_data($result);
          $this->load->view('sales/inventory/v_invincoming', $data);
      }
  }
  //---
}
