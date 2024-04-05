<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detailinout extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'detailinout'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('wms/report/detailinout/v_index', $data);
        }
    }
    //---

    function get_data(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        $result = $this->model_tsc_in_out_bound_h->get_list_in_out_bound_report($date_from, $date_to);
        $data["var_report"] = assign_data($result);
        $this->load->view('wms/report/detailinout/v_report', $data);
    }
    //---

    function get_detail(){
        $id      = $_POST['id'];
        $return_link = $_POST['link'];
        $loc_code = $_POST['loc_code'];
        $doc_type = $_POST['doc_type'];

        $this->load->model('model_tsc_in_out_bound_d','',TRUE);
        unset($doc_no);
        $doc_no[] = $id;
        $result = $this->model_tsc_in_out_bound_d->get_list($doc_no);
        $data['var_detail'] = assign_data($result);
        $data['doc_no_h'] = $id;
        $data['loc_code_h'] = $loc_code;
        $data['doc_type'] = $doc_type;

        $this->load->view($return_link,$data);
    }
    //----

}
