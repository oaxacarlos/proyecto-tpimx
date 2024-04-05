<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller{

  function __construct(){
      parent::__construct();
      $this->load->model('internal/itemrequest/model_tsc_in_out_bound_h_temp','model_tsc_in_out_bound_h_temp');
      $this->load->model('internal/itemrequest/model_tsc_in_out_bound_d_temp','model_tsc_in_out_bound_d_temp');

  }
  //--

  function itemrequest(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'internal/requestitem'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sales/internal/requestitem/report/v_index',$data);
      }
  }
  //--

  function itemrequest_report(){
    $date_from = $_POST["date_from"];
    $date_to = $_POST["date_to"];

    $status = "'17','18','19'";
    $result = $this->model_tsc_in_out_bound_h_temp->get_data_by_period($status, $date_from, $date_to);
    if(count($result) == 0){
        $data["var_report"] = 0;
    }
    else{
        $data["var_report"] = assign_data($result) ;
    }

    $this->load->view('sales/internal/requestitem/report/v_report',$data);
  }
  //---

  function itemrequest_detail(){

    $doc_no = $_POST["doc_no"];
    $data["cust_no"] = $_POST["cust_no"];
    $data["cust_name"] = $_POST["cust_name"];
    $data["address"] = $_POST["address"];
    $data["address2"] = $_POST["address2"];
    $data["city"] = $_POST["city"];
    $data["contact"] = $_POST["contact"];
    $data["country_region_code"] = $_POST["country_region_code"];
    $data["post_code"] = $_POST["post_code"];
    $data["county"] = $_POST["county"];
    $data["status"] = $_POST["status"];
    $data["idx_row"] = $_POST["idx_row"];

    $result = $this->model_tsc_in_out_bound_d_temp->get_data_by_doc_no($doc_no);
    if(count($result) > 0){
        $data["var_in_out_d"] = assign_data($result);
    }
    else $data["var_in_out_d"] = 0;

    $data["doc_no"] = $doc_no;

    $this->load->view('sales/internal/requestitem/report/v_detail',$data);
  }
  //---

  function itemrequest_print(){

      $doc_no = $_GET["docno"];

      $data["var_h"] = assign_data_one($this->model_tsc_in_out_bound_h_temp->get_data_by_doc_no($doc_no));
      $data["var_d"] = assign_data($this->model_tsc_in_out_bound_d_temp->get_data_by_doc_no($doc_no));

      $this->load->view('sales/internal/requestitem/report/v_print',$data);
  }
  //--
}

?>
