<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Printbarcode extends CI_Controller{

    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->model('model_tsc_item_sn','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'printbarcode'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('wms/report/printbarcode/v_index', $data);
        }
    }
    //---

    function get_barcode_master(){
        $id = $_POST["id"];

        $sn2[] = $id;
        $result = $this->model_tsc_item_sn->get_sn_by_sn2_ver2($sn2);

        if(count($result) == 0){
            $data["sn2"] = 0;
        }
        else{
            $data["sn2"] = $id;
            $data["sn"] = assign_data($result);
        }

        $this->load->view('wms/report/printbarcode/v_barcode_master', $data);
    }
    //---

    function get_barcode_sn(){
        $id = $_POST["id"];

        $this->model_tsc_item_sn->serial_number = $id;
        $result = $this->model_tsc_item_sn->get_data_by_serial_number_by_status();

        if(count($result) == 0){
            $data["sn"] = 0;
        }
        else{
            $data["sn"] = assign_data_one($result);
        }

        $this->load->view('wms/report/printbarcode/v_barcode_sn', $data);
    }
    //---
}

?>
