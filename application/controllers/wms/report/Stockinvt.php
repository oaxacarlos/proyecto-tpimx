<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stockinvt extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'stockinvt'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->model('model_tsc_item_invt','',TRUE);

            $data["var_stock_invt"] = $this->model_tsc_item_invt->stock_invt();
            $this->load->view('wms/report/stockinvt/v_index', $data);
        }
    }
    //---

    function get_item_invt_by_code(){
        $item_code = $_POST['item_code'];
        $this->load->model('model_tsc_item_invt','',TRUE);

        $this->model_tsc_item_invt->item_code = $item_code;
        $invt_data = $this->model_tsc_item_invt->stock_invt_by_code();

        echo json_encode($invt_data);
    }
    //--

    // wH3 2023-05-17
    function get_detail_by_location(){
        $item_code = $_POST["item_code"];
        $status = $_POST["status"];

        $this->load->model('model_tsc_item_sn','',TRUE);

        $result = $this->model_tsc_item_sn->get_qty_by_location_item($item_code,$status);
        $data["var_item"] = assign_data($result);

        $this->load->view('wms/report/stockinvt/v_detail', $data);
    }
    //---

}
