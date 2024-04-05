<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stockracktwo extends CI_Controller{

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

          $result = $this->model_mst_bin->get_data();
          $data['var_bin'] = assign_data($result);

          $this->load->view('wms/report/stockracktwo/v_index', $data);
      }
  }

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

      $result = $this->model_tsc_item_sn->get_item_by_location2();

      $data["var_report"] = assign_data($result);
      $data["location"]   = $location;

      $this->load->view('wms/report/stockracktwo/v_report', $data);
  }
  //---

}

?>
