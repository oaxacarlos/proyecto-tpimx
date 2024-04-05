<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Application extends CI_Controller{

  function __construct(){
    parent::__construct();
       $this->load->model('external/application/model_application','model_external_application');
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('external_folder').'reports/application'])){
          $this->load->view('view_home');
      }
      else{
          $result = $this->model_external_application->get_make();
          $data["var_make"] = assign_data($result);

          $this->load->view('external/reports/application/v_index', $data);
      }
  }
  //---

  function get_model(){
     $make = $_POST["make"];

     $result = $this->model_external_application->get_model_by_make($make);
     foreach($result as $row){
          $response["data"][] = array(
              "model" => $row["model"]
          );
     }

     echo json_encode($response);
  }
  //--

  function get_year(){
      $make = $_POST["make"];
      $model = $_POST["model"];

      $result = $this->model_external_application->get_year_by_make_model($make, $model);
      foreach($result as $row){
           $response["data"][] = array(
               "year" => $row["year"]
           );
      }

      echo json_encode($response);
  }
  //---

  function get_engine(){
      $make = $_POST["make"];
      $model = $_POST["model"];
      $year = $_POST["year"];

      $result = $this->model_external_application->get_engine_by_make_model_year($make, $model, $year);
      foreach($result as $row){
           $response["data"][] = array(
               "enginee" => $row["enginee"]
           );
      }

      echo json_encode($response);
  }
  //---

  function get_report_appsearch(){
      $make = $_POST["make"];
      $model = $_POST["model"];
      $year = $_POST["year"];
      $engine = $_POST["engine"];

      // seperate engine
      $engine2 = explode(" ",$engine);
      $enginecylinder = $engine2[0];
      $engineblock = $engine2[1];
      $engineliters = $engine2[2];
      $hp = $engine2[4];
      //---

      $result = $this->model_external_application->get_application($make, $model, $year, $enginecylinder, $engineblock, $engineliters, $hp);
      $data["var_report"] = assign_data($result);

      $this->load->view('external/reports/application/v_appsearch_report', $data);
  }
  //--

  function get_report_codesearch(){
      $search = $_POST["search"];

      $result = $this->model_external_application->get_code_search($search, $search);
      $data["var_report"] = assign_data($result);

      $this->load->view('external/reports/application/v_codesearch_report', $data);
  }
  //--
}
