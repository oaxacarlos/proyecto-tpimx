<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Salesmanweekly extends CI_Controller{

  function __construct(){
    parent::__construct();
       $this->load->model('model_sales_report','',TRUE);
       $this->load->model('model_zlog','',TRUE);
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'salesmanweekly'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Salesman Weekly"); // insert log

          $this->load->view('sales/report/salesmanweekly/v_index', $data);
      }
  }
  //---

  function report(){
      $this->model_zlog->insert("Gen Salesman Weekly Data"); // insert log

      $year = $_POST["year"];
      $month = $_POST["month"];

      if($year == "2023" && $month == "01"){
          $period[] = array("from" => '2023-01-01', "to" => '2023-01-07' );
          $period[] = array("from" => '2023-01-08', "to" => '2023-01-14' );
          $period[] = array("from" => '2023-01-15', "to" => '2023-01-21' );
          $period[] = array("from" => '2023-01-22', "to" => '2023-01-28' );
          $period[] = array("from" => '2023-01-29', "to" => '2023-01-31' );
      }
      else{
          $period = get_week_in_month($year, $month);
      }

      $result = $this->model_sales_report->get_salesman_sales_weekly($year, $month, $period);
      $data["var_report"] = assign_data($result);
      $data["var_period"] = $period;
      $this->load->view('sales/report/salesmanweekly/v_report', $data);
  }

}
