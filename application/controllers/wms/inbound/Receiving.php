<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Receiving extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();

    $this->load->model('model_zlog','',TRUE);
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'receiving'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - Receiving"); // insert log

          $this->load->view('wms/inbound/v_receiving');
      }
  }
  //---

  function get_receiving_list(){
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      $this->model_tsc_in_out_bound_h->doc_type = '1';
      $status = array("1","2");

      $result = $this->model_tsc_in_out_bound_h->list_with_doc_type_one_and_qty($status);
      $data['var_receiving'] = assign_data($result);

      $this->load->view('wms/inbound/v_receiving_list',$data);
  }
  //----

  function get_in_out_bound_list_d(){
      $id      = $_POST['id'];
      $return_link = $_POST['link'];
      $loc_code = $_POST['loc_code'];
      $from_wh = $_POST["from_wh"];
      $transfer_from_wh = $_POST["transfer_from_wh"];

      $this->load->model('model_tsc_in_out_bound_d','',TRUE);
      unset($doc_no);
      $doc_no[] = $id;
      $result = $this->model_tsc_in_out_bound_d->get_list($doc_no);
      $data['var_receiving_detail'] = assign_data($result);
      $data['doc_no_h'] = $id;
      $data['loc_code_h'] = $loc_code;
      $data["transfer_from_wh"] = $transfer_from_wh;
      $data["from_wh"] = $from_wh;

      $this->load->view($return_link,$data);
  }
  //----

  function received_process(){
      $this->model_zlog->insert("Warehouse - Received Process"); // insert log

      $item_code = json_decode(stripslashes($_POST['item_code']));
      $qty_process = json_decode(stripslashes($_POST['qty_process']));
      $desc = json_decode(stripslashes($_POST['desc']));
      $line_no = json_decode(stripslashes($_POST['line_no']));
      $location_code = json_decode(stripslashes($_POST['location_code']));
      $doc_no = json_decode(stripslashes($_POST['doc_no']));
      $doc_no_h = $_POST['doc_no_h'];
      $loc_code_h = $_POST['loc_code_h'];
      $total_qty = $_POST['total_qty'];
      $total_rem_qty = $_POST['total_rem_qty'];
      $uom = json_decode(stripslashes($_POST['uom']));
      $master_barcode = json_decode(stripslashes($_POST['master_barcode'])); // master barcode 2023-01-17
      $message = $_POST['message'];
      $valuee_per_pcs = json_decode(stripslashes($_POST['valuee_per_pcs'])); // master barcode 2023-01-17
      $from_wh = $_POST['from_wh'];  // 2023-03-02 WH3
      $transfer_from_wh = $_POST['transfer_from_wh']; // 2023-03-02 WH3

      $this->load->model('model_config','',TRUE);
      $this->load->model('model_tsc_received_h','',TRUE);
      $this->load->model('model_tsc_received_d','',TRUE);
      $this->load->model('model_tsc_in_out_bound_d','',TRUE);
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);

      // initial
      $datetime = date("Y-m-d H:i:s");
      $date     = date("Y-m-d");
      $session_data = $this->session->userdata('z_tpimx_logged_in');

      // get prefix
      $this->model_config->name = "pref_received";
      $this->model_tsc_received_h->prefix_code = $this->model_config->get_value_by_setting_name();
      //---

      // check if partially receiving..
      $is_received_partially = $this->check_received_partial($item_code, $qty_process, $total_rem_qty);
      //--

      if($is_received_partially == 0){ $status_in_out_h = '3'; }
      else{ $status_in_out_h = '2'; }

      // create new received document header
      $this->model_tsc_received_h->in_bound_no = $doc_no_h;
      $this->model_tsc_received_h->created_datetime = $datetime;
      $this->model_tsc_received_h->doc_datetime = $datetime;
      $this->model_tsc_received_h->doc_location_code = $loc_code_h;
      $this->model_tsc_received_h->created_user = $session_data['z_tpimx_user_id'];
      $this->model_tsc_received_h->external_document = "";
      $this->model_tsc_received_h->doc_date = $date;
      $this->model_tsc_received_h->status_h = '3';
      $this->model_tsc_received_h->transfer_from_wh = $transfer_from_wh;
      $this->model_tsc_received_h->transfer_to_wh = "";
      $this->model_tsc_received_h->from_wh = $from_wh;
      $this->model_tsc_received_h->to_wh = "";
      $this->model_tsc_received_h->print_barcode = "0";
      $this->model_tsc_received_h->print_master_barcode = "0";
      $received_doc_no = $this->model_tsc_received_h->call_store_procedure_newreceived();

      // insert detail
      for($i=0;$i<count($item_code);$i++){
          $this->model_tsc_received_d->doc_no = $received_doc_no;
          $this->model_tsc_received_d->line_no = ($i+1);
          $this->model_tsc_received_d->src_location_code = $loc_code_h;
          $this->model_tsc_received_d->src_no = $doc_no_h;
          $this->model_tsc_received_d->src_line_no = $line_no[$i];
          $this->model_tsc_received_d->item_code = $item_code[$i];
          $this->model_tsc_received_d->qty_outstanding = 0;
          $this->model_tsc_received_d->uom = $uom[$i];
          $this->model_tsc_received_d->dest_no = "";
          $this->model_tsc_received_d->description = $desc[$i];
          $this->model_tsc_received_d->qty = $qty_process[$i];
          $this->model_tsc_received_d->qty_outstanding = $qty_process[$i];
          $this->model_tsc_received_d->created_datetime_d = $datetime;
          $this->model_tsc_received_d->master_barcode = $master_barcode[$i]; // master barcode 2023-01-17
          $this->model_tsc_received_d->valuee = round($valuee_per_pcs[$i] * $qty_process[$i]);
          $this->model_tsc_received_d->valuee_per_pcs = $valuee_per_pcs[$i];
          $result_d = $this->model_tsc_received_d->insert_d();
      }
      //---

      // update in_out_doc_d
      for($i=0;$i<count($item_code);$i++){
          $this->model_tsc_in_out_bound_d->doc_no = $doc_no_h;
          $this->model_tsc_in_out_bound_d->line_no = $line_no[$i];
          $qty_received = $this->model_tsc_in_out_bound_d->get_lastest_qty_received();
          $new_qty_received = $qty_received + $qty_process[$i];
          $this->model_tsc_in_out_bound_d->qty_received = $new_qty_received;
          $result = $this->model_tsc_in_out_bound_d->update_qty_received();
      }
      //---

      // update in_out_doc_h
      $this->model_tsc_in_out_bound_h->doc_no = $doc_no_h;
      $this->model_tsc_in_out_bound_h->status = $status_in_out_h;
      $this->model_tsc_in_out_bound_h->update_status();
      //---

      // update text
      $this->model_tsc_received_h->text = $message;
      $this->model_tsc_received_h->doc_no = $received_doc_no;
      $this->model_tsc_received_h->update_text();
      //---

      // insert doc history
      $this->model_tsc_doc_history->insert($received_doc_no,$doc_no_h,"",$status_in_out_h,"",$datetime, $message,"");
      //--

      if($received_doc_no){
          $response['status'] = "1";
          $response['msg'] = "Document Received has created = ".$received_doc_no;
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }

  }
  //---

  function check_received_partial($item_code, $qty_process, $total_rem_qty){
      $total_qty_received = 0;
      for($i=0;$i<count($item_code);$i++){
          $total_qty_received += $qty_process[$i];
      }

      if($total_qty_received == $total_rem_qty) return 0;
      else return 1;
  }
  //---

  function submitnav(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'receiving/submitnav'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - InBound Submitnav"); // insert log

          $this->load->view('wms/inbound/v_receiving_submitnav');
      }
  }
  //---

  function get_receiving_submitnav_list(){
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      $this->refresh_status_in_bound();

      $this->model_tsc_in_out_bound_h->status='8';
      $result = $this->model_tsc_in_out_bound_h->list_with_doc_type_one_and_qty_by_status();
      $data['var_receiving'] = assign_data($result);

      $this->load->view('wms/inbound/v_receiving_submitnav_list',$data);
  }
  //----

  function refresh_status_in_bound(){
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $result = $this->model_tsc_in_out_bound_h->get_list_inbound_done();
      $data_inbound_done = assign_data($result);

      if(count($result)>0){
        foreach($data_inbound_done as $row){
            $this->model_tsc_in_out_bound_h->status = '8';
            $this->model_tsc_in_out_bound_h->doc_no = $row['inout_doc_no'];
            $this->model_tsc_in_out_bound_h->update_status();
        }
      }
  }
  //---

  function cancel_received(){}
  //--

  function check_doc_locked(){
      $id = $_POST['id'];
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $this->model_tsc_in_out_bound_h->doc_no = $id;
      $result = $this->model_tsc_in_out_bound_h->check_doc_locked();

      if($result["locked"]=="1") echo "1"; else echo "0";

      /*if($result["locked"]=="0") echo "0";
      else if($result["locked"]=="1" || $result["user_locked"] == $session_data['z_tpimx_user_id']) echo "0";
      else if($result["locked"]=="1" || $result["user_locked"] != $session_data['z_tpimx_user_id']) echo "1";*/
  }
  //---

  function doc_locked(){
      $id = $_POST['id'];
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      // update locked
      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $this->model_tsc_in_out_bound_h->doc_no = $id;
      $result = $this->model_tsc_in_out_bound_h->update_doc_to_locked($session_data['z_tpimx_user_id']);
      if($result) echo "1"; else echo "0";
  }
  //---

  function doc_unlocked(){
      $id = $_POST['id'];
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      // update locked
      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $this->model_tsc_in_out_bound_h->doc_no = $id;
      $result = $this->model_tsc_in_out_bound_h->update_doc_to_unlocked();
      if($result) echo "1"; else echo "0";
  }
  //---

  function submitnav_process(){
      $doc_no = $_POST['doc_no'];
      $message = $_POST['message'];
      $whs = $_POST['whs']; // WH3

      $datetime = get_datetime_now();

      $this->load->model('model_inbound','',TRUE);
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);
      $this->load->model('model_tsc_in_out_bound_d','',TRUE); // 2023-05-29

      $result = $this->model_inbound->whrcpt_update_status($doc_no, "2");

      if($result){
        // update status in out bound h
        $this->model_tsc_in_out_bound_h->status = '16';
        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $result2 = $this->model_tsc_in_out_bound_h->update_status();
        //---

        // update submitted and submitted text
        $this->model_tsc_in_out_bound_h->submitted = "1";
        $this->model_tsc_in_out_bound_h->submitted_datetime = $datetime;
        $this->model_tsc_in_out_bound_h->submitted_text = $message;
        $this->model_tsc_in_out_bound_h->update_submitted_text();
        //---

        // insert doc history
        $this->model_tsc_doc_history->insert($doc_no,$doc_no,"","16","",$datetime,$message,"");
        //--

        // get detail 2023-05-29
        //$doc_no_temp[] = $doc_no;
        //$result_detail = assign_data($this->model_tsc_in_out_bound_d->get_list($doc_no_temp));
        //---

        if($result2){
            $response['status'] = "1";
            $response['msg'] = "Document has been submitted to Navision";

            // send email
            //$this->send_email_notification($doc_no, $message, $datetime, $whs, $result_detail);
            $this->insert_into_email($doc_no, $message,$datetime, $whs);
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
        $this->model_config->name = "email_whrcpt_nav_to";
        $send_to = $this->model_config->get_value_by_setting_name();
        //---

        $this->load->library('MY_phpmailer');

        // get send cc
        unset($cc);
        $this->model_config->name = "email_whrcpt_nav_cc";
        $cc_temp = $this->model_config->get_value_by_setting_name();
        $cc = explode("|",$cc_temp);
        //--

        $body = $this->my_phpmailer->email_body_whsreceipt_submitnav($doc_no,$datetime,$message, $result_detail);
        $to = $send_to;
        $subject = $whs." - Warehouse Reciept Ready to Post (".$doc_no.")";
        $from_info = "WMS TPI-MX";
        $altbody = "";
        $result = $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
  }
  //---

  function insert_into_email($doc_no, $message, $datetime, $whs){
      $this->load->model('model_config','',TRUE);
      $this->load->model('model_tsc_email','',TRUE);

      // get send to
      $this->model_config->name = "email_whrcpt_nav_to";
      $send_to = $this->model_config->get_value_by_setting_name();
      //---

      // get send cc
      unset($cc);
      $this->model_config->name = "email_whrcpt_nav_cc";
      $cc = $this->model_config->get_value_by_setting_name();
      //$cc = explode("|",$cc_temp);
      //--

      $to = $send_to;
      $subject = $whs." - Warehouse Reciept Ready to Post (".$doc_no.")";
      $from_info = "WMS TPI-MX";
      $altbody = "";

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $created_user = $session_data['z_tpimx_user_id'];

      $this->model_tsc_email->insert("1", $doc_no, $to, $cc, $subject,"notification@toyopower.com", $datetime, $from_info, $message, $created_user);
  }
  //--
}

?>
