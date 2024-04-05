<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Submitnav extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();

      $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'submitnav'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - Outbound Submitnav"); // insert log

            $this->load->view('wms/outbound/submitnav/v_index');
        }
    }
    //---

    function get_list(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $status[] = "13";
        $status[] = "14";
        $this->model_tsc_in_out_bound_h->doc_type = "2";
        $result = $this->model_tsc_in_out_bound_h->list_with_doc_type_one_and_qty_month_end_submitted_null($status,"1");
        $data["var_submitnav"] = assign_data($result);
        $this->load->view('wms/outbound/submitnav/v_list',$data);
    }
    //---

    function submit(){
        $this->model_zlog->insert("Warehouse - Outbound Process Submitnav"); // insert log

        $doc_no = $_POST['doc_no'];
        $message = $_POST['message'];
        $whs = $_POST["whs"]; // WH3

        $datetime = get_datetime_now();

        $this->load->model('model_outbound','',TRUE);
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_tsc_doc_history','',TRUE);
        $this->load->model('model_tsc_in_out_bound_d','',TRUE); // 2023-05-29

        $result = $this->model_outbound->whship_update_status($doc_no, "2");

        if($result){

          $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
          if(!$this->model_tsc_in_out_bound_h->check_month_end_and_not_submitted()){
            // update status in out bound h
            $this->model_tsc_in_out_bound_h->doc_no = $doc_no;

            if($this->model_tsc_in_out_bound_h->get_status()!="14"){
              $this->model_tsc_in_out_bound_h->status = '16';
              $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
              $result1 = $this->model_tsc_in_out_bound_h->update_status();
            }
            //---
          }

          // update submitted and submitted text
          $this->model_tsc_in_out_bound_h->submitted = "1";
          $this->model_tsc_in_out_bound_h->submitted_datetime = $datetime;
          $this->model_tsc_in_out_bound_h->submitted_text = $message;
          $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
          $result2 = $this->model_tsc_in_out_bound_h->update_submitted_text();
          //---

          // insert doc history
          $this->model_tsc_doc_history->insert($doc_no,$doc_no,"","16","",$datetime,$message,"");
          //--

          // get detail 2023-05-29
          //$doc_no_temp[] = $doc_no;
          //$result_detail = assign_data($this->model_tsc_in_out_bound_d->get_list_with_so_cs($doc_no_temp));
          //---

          if($result2){
              $response['status'] = "1";
              $response['msg'] = "Document has been submitted to Navision";

              // send email
              //$this->send_email_notification($doc_no, $message, $datetime, $whs, $result_detail);
              $this->insert_into_email($doc_no, $message, $datetime, $whs);
              //---

              echo json_encode($response);
          }
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //---

    function send_email_notification($doc_no, $message, $datetime, $whs, $result_detail){

          $this->load->model('model_config','',TRUE);

          // get send to
          $this->model_config->name = "email_whship_nav_to";
          $send_to = $this->model_config->get_value_by_setting_name();
          //---

          $this->load->library('MY_phpmailer');

          // get send cc
          unset($cc);
          $this->model_config->name = "email_whship_nav_cc";
          $cc_temp = $this->model_config->get_value_by_setting_name();
          $cc = explode("|",$cc_temp);
          //--

          $body = $this->my_phpmailer->email_body_whship_submitnav($doc_no,$datetime,$message, $result_detail);
          $to = $send_to;
          $subject = $whs." - Warehouse Shipment Ready to Post (".$doc_no.")";
          $from_info = "WMS TPI-MX";
          $altbody = "";
          $result = $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);

    }
    //---

    function insert_into_email($doc_no, $message, $datetime, $whs){
        $this->load->model('model_config','',TRUE);
        $this->load->model('model_tsc_email','',TRUE);

        // get send to
        $this->model_config->name = "email_whship_nav_to";
        $send_to = $this->model_config->get_value_by_setting_name();
        //---

        // get send cc
        unset($cc);
        $this->model_config->name = "email_whship_nav_cc";
        $cc = $this->model_config->get_value_by_setting_name();
        //$cc = explode("|",$cc_temp);
        //--

        $to = $send_to;
        $subject = $whs." - Warehouse Shipment Ready to Post (".$doc_no.")";
        $from_info = "WMS TPI-MX";
        $altbody = "";

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];

        $this->model_tsc_email->insert("2", $doc_no, $to, $cc, $subject,"notification@toyopower.com", $datetime, $from_info, $message, $created_user);
    }
    //--
}

?>
