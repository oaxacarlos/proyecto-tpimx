<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bypass extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();

      $this->load->model('model_outbound','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);
      $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'bypass'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - OutBound ByPass"); // insert log

            $this->load->view('wms/outbound/bypass/v_index');
        }
    }
    //----

    function process(){
        $doc_no = $_POST["doc_no"];
        $message = $_POST["message"];

        $datetime = get_datetime_now();

        $result = $this->model_outbound->whship_update_status($doc_no, "2");
        $this->model_tsc_doc_history->insert($doc_no,$doc_no,"","20","",$datetime,$message,"");

        if($result){
            $response['status'] = "1";
            $response['msg'] = "Document No = ".$doc_no." has been ByPassed";
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
        }

        echo json_encode($response);
    }
    //--

}
