<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detailinout2 extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'detailinout2'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('wms/report/detailinout2/v_index', $data);
        }
    }
    //---

    function get_data(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];
        $doc_type   = $_POST["doc_type"];
        $loc        = $_POST["loc"];
        $canceled   = $_POST["canceled"];
        $internal   = $_POST["internal"];

        $this->load->model('model_report','',TRUE);

        if($canceled == 1) $canceled = 1; else $canceled = 0; // canceled

        // internal
        if($internal == 0){
            if($doc_type == 1) $internal = "TPM-WREC-";
            else if($doc_type == 2) $internal = "TPM-WSHIP-";
        }
        else{ $internal = ""; }
        //---

        // inbound = qty, outbound = qty_to_ship
        if($doc_type == "1") $qty = "qty";
        else if($doc_type == "2") $qty = "qty_to_ship";
        //---

        $result = $this->model_report->get_detail_inoutbound($date_from, $date_to,$doc_type, $loc, $canceled, $internal);

        $data["var_report"] = assign_data($result);
        $data["qty"] = $qty;

        $this->load->view('wms/report/detailinout2/v_report', $data);
    }
    //---


}
