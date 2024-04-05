<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Report extends CI_Controller{

    function __construct(){
      parent::__construct();
         $this->load->model('model_operacion_report','',TRUE);
    }

    function dashboard(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_report_folder').'dashboard'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('operacion/report/dashboard/v_index', $data);
        }
    }
    //--

    function dsh_maps_report_data(){
        $from = $_POST['from'];
        $to = $_POST['to'];
        $by = $_POST['by'];

        $result = $this->model_operacion_report->get_total_qty_value_by_postcode_from_to($from, $to);
        $result = assign_data($result);

        if(count($result) < 0){ $response = 0; }
        else{
            if($by == "qty") $metric = "qty";
            else if($by == "value") $metric = "amount";

            foreach($result as $row){
                $response[] = array($row['id'],(int)$row[$metric]);
            }
        }

        echo json_encode($response);
    }
    //---

    function consigment(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_report_folder').'consigment'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('operacion/report/consigment/v_index', $data);
        }
    }
    //---

    function get_consigment_data(){
        $date_from = $_POST['date_from'];
        $date_to = $_POST['date_to'];
        $consign = $_POST['consign'];

        if($consign == "MX_CSG_MTK"){
            $concat = "OI_";
            $customer = "1190027";
        }
        else if($consign == "MX_CSG_SGM"){
            $concat = "";
            $customer = "1190033";
        }

        $period = get_period_from_period($date_from, $date_to);
        $result = $this->model_operacion_report->get_consigment_data_period($period, $date_from, $date_to, $consign, $concat, $customer);
        $data["var_report"] = assign_data($result);

        $data["period"] = $period;
        $data["date_from"] = $date_from;
        $data["date_to"] = $date_to;
        $this->load->view('operacion/report/consigment/v_report', $data);
    }
    //---

    function consigmentvalue(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_report_folder').'consigmentvalue'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('operacion/report/consigment/v_value_index', $data);
        }
    }
    //---

    function get_consigmentvalue_data(){
        $consign = $_POST['consign'];

        if($consign == "MX_CSG_MTK") $cust_code = "1190027";
        else if($consign == "MX_CSG_SGM") $cust_code = "1190033";

        $result = $this->model_operacion_report->get_consigment_value($consign, $cust_code);
        $data["var_report"] = assign_data($result);

        $this->load->view('operacion/report/consigment/v_value_report', $data);
    }
    //---

    
}
