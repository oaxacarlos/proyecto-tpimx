<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stockcount extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();

    $this->load->model('model_mst_bin','',TRUE);
    $this->load->model('model_tsc_item_sn','',TRUE);
    $this->load->model('model_mst_item','',TRUE);
    $this->load->model('model_tsc_stock_count','',TRUE);
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'stockcount'])){
          $this->load->view('view_home');
      }
      else{
        $result = $this->model_mst_bin->get_data();
        $data['var_bin'] = assign_data($result);

        $result = $this->model_tsc_stock_count->get_stock_count_user();
        $data['var_user'] = assign_data($result);

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $data["var_user_login"] = $session_data['z_tpimx_user_id'];

        $this->load->view('wms/report/stockcount/v_index', $data);
      }
  }
  //---

  function get_report(){
      $location = $_POST["inp_rack"];

      $new_location = explode("-",$location);

      $user_plant = get_plant_code_user();// 2023-03-02 WH3

      $temp2 = explode(",",$user_plant);
      if(count($temp2) > 1){
        if($new_location[0] == "WH3") $temp = "WH3";
        else $temp = "WH2";
      }
      else{
        $temp = ltrim($user_plant,"'");
        $temp = rtrim($temp,"'");
      }

      if($new_location[0]!="WH2" && $new_location[0]!="WH3"){
          $location_temp = $temp;
          $zone_temp = $new_location[0];
          $area_temp = $new_location[1];
          $rack_temp = $new_location[2];
          $bin_temp = $new_location[3];
      }
      else{
        $location_temp = $new_location[0];
        $zone_temp = $new_location[1];
        $area_temp = $new_location[2];
        $rack_temp = $new_location[3];
        $bin_temp = $new_location[4];
      }

      $this->model_tsc_item_sn->location_code = $location_temp;
      $this->model_tsc_item_sn->zone_code     = $zone_temp;
      $this->model_tsc_item_sn->area_code     = $area_temp ;
      $this->model_tsc_item_sn->rack_code     = $rack_temp ;
      $this->model_tsc_item_sn->bin_code      = $bin_temp ;

      $result = $this->model_tsc_item_sn->get_item_by_location();

      $data["var_report"] = assign_data($result);
      $data["location"]   = combine_location($location_temp, $zone_temp, $area_temp, $rack_temp,$bin_temp);

      $data["var_item"] = assign_data($this->model_mst_item->get_data()); // get item

      $this->load->view('wms/report/stockcount/v_report', $data);
  }
  //---

  function save(){

      $data_item  = json_decode(stripslashes($_POST['data_item']));
      $data_qty   = json_decode(stripslashes($_POST['data_qty']));
      $position   = $_POST["rack"];
      $type = $_POST['type']; // 2023-09-12

      $location = split_location($position,"-");

      // initial
      unset($h_doc_date); unset($h_created_at); unset($h_item_code);
      unset($h_location); unset($h_zone); unset($h_area); unset($h_rack); unset($h_bin);
      unset($h_qty); unset($h_user);

      $date = get_date_now();
      $datetime = get_datetime_now();
      $counter = 0;

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];

      for($i=0; $i<count($data_item); $i++){
          $h_doc_date[$counter]   = $date;
          $h_created_at[$counter] = $datetime;
          $h_item_code[$counter]  = $data_item[$i];
          $h_location[$counter]   = $location[0];
          $h_zone[$counter]   = $location[1];
          $h_area[$counter]   = $location[2];
          $h_rack[$counter]   = $location[3];
          $h_bin[$counter]    = $location[4];
          $h_qty[$counter] = $data_qty[$i];
          $h_user[$counter] = $user;
          $h_type[$counter] = $type; // 2023-09-12
          $counter++;
      }

      $result = $this->model_tsc_stock_count->insert($h_doc_date, $h_created_at, $h_item_code, $h_location, $h_zone, $h_area, $h_rack, $h_bin, $h_qty, $h_user, $h_type);

      if($result){
          $response['status'] = "1";
          $response['msg'] = "Inserted Successfull";
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
      }

      echo json_encode($response);
  }
  //---

  function get_data(){
      $date_from  = $_POST["from"];
      $date_to    = $_POST["to"];
      $user       = $_POST["user"];

      if($user == "all") $user = "";

      $result = $this->model_tsc_stock_count->get_data($date_from, $date_to,$user);
      $data["var_report"] = assign_data($result);

      $this->load->view('wms/report/stockcount/v_stockcount_data', $data);
  }
  //--

  function delete(){
      $id = $_POST["id"];
      $result = $this->model_tsc_stock_count->delete_id($id);
      if($result){
          $response['status'] = "1";
          $response['msg'] = "Deleted Successfull";
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
      }

      echo json_encode($response);
  }
  //--

  function get_data_duplicate(){
      $date_from  = $_POST["from"];
      $date_to    = $_POST["to"];
      $user       = $_POST["user"];

      if($user == "all") $user = "";

      $result = $this->model_tsc_stock_count->get_data_duplicate($date_from, $date_to,$user);
      $data["var_report"] = assign_data($result);

      $this->load->view('wms/report/stockcount/v_stockcount_data_duplicate', $data);
  }
  //--

}
