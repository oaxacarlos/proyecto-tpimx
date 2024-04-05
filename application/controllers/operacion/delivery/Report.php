<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller{

    function __construct(){
      parent::__construct();
         $this->load->model('operacion/delivery/mst/delv/delv_part','model_operacion_mst_delv_part');
         $this->load->model('operacion/delivery/mst/delv/delv_status','model_operacion_mst_delv_status');
         $this->load->model('operacion/delivery/mst/domicili','model_operacion_mst_domicili');
         $this->load->model('operacion/delivery/mst/vendor','model_operacion_mst_vendor');
         $this->load->model('operacion/delivery/mst/city','model_operacion_mst_city');
         $this->load->model('operacion/delivery/mst/driver','model_operacion_mst_driver');
         $this->load->model('operacion/delivery/mst/state','model_operacion_mst_state');
         $this->load->model('operacion/delivery/mst/payment/payment_terms','model_operacion_mst_payment_terms');
         $this->load->model('operacion/delivery/mst/payment/payment_status','model_operacion_mst_payment_status');
         $this->load->model('operacion/delivery/tsc/delivery/delivery_h','model_operacion_tsc_delivery_h');
         $this->load->model('operacion/delivery/tsc/delivery/delivery_d','model_operacion_tsc_delivery_d');
         $this->load->model('operacion/config','model_operacion_config');
         $this->load->model('operacion/delivery/tsc/doc/edited','model_operacion_doc_edited');
         $this->load->model('operacion/delivery/tsc/delivery/delivery_history','model_operacion_tsc_delivery_history');
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_folder').'delivery/report'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('operacion/delivery/report/v_index',$data);
        }
    }
    //---

    function report(){
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];

        $data["var_data_h"] = assign_data($this->model_operacion_tsc_delivery_h->get_data_by_period($date_from, $date_to));
        $data["var_data_d"] = assign_data($this->model_operacion_tsc_delivery_d->get_data_by_period($date_from, $date_to));

        $this->load->view('operacion/delivery/report/v_report',$data);
    }
    //--

    function detail(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_folder').'delivery/report/detail'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('operacion/delivery/report2/v_index',$data);
        }
    }
    //---

    function detail_process(){
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];

        $data["var_report"] = $this->model_operacion_tsc_delivery_d->get_detail_report($date_from, $date_to);

        $this->load->view('operacion/delivery/report2/v_report',$data);
    }
    //---

    function ontime(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_folder').'delivery/report/ontime'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('operacion/delivery/reports/ontime/v_index',$data);
        }
    }
    //--

    function ontimeinvc_data(){
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];

        $data["var_report"] = $this->model_operacion_tsc_delivery_h->get_delivery_ontime_report($date_from, $date_to);

        $this->load->view('operacion/delivery/reports/ontime/v_report_invc',$data);
    }
    //--

    function ontimedelv_data(){
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];

        $data["var_report"] = $this->model_operacion_tsc_delivery_h->get_delivery_ontime_report_by_delv_doc($date_from, $date_to);

        $this->load->view('operacion/delivery/reports/ontime/v_report_delv',$data);
    }
    //--
}

?>
