<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stockrack extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'stockrack'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->model('model_tsc_item_invt','',TRUE);

            $data["var_stock_invt"] = $this->model_tsc_item_invt->stock_invt();
            $this->load->view('wms/report/stockrack/v_index', $data);
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

    function get_rack_item(){
        $item_code = $_POST['id'];

        $this->load->model('model_tsc_item_sn','',TRUE);
        $this->load->model('model_mst_location','',TRUE);

        $this->model_tsc_item_sn->item_code = $item_code;
        $result = $this->model_tsc_item_sn->list_item_location_by_item_code_and_status_one_two();
        $data['var_item_loc'] = assign_data($result);

        $result = $this->model_mst_location->get_data2();
        $data["var_location"] = assign_data($result);

        $data["item_code"] = $item_code;

        $this->load->view('wms/report/stockrack/v_rack', $data);
    }
    //---
}
