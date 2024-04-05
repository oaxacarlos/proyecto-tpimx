<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verified extends CI_Controller{

  function __construct(){
    parent::__construct();
       $this->load->model('loyalty/model_verified','loyalty_model_verified');
       $this->load->model('loyalty/model_config','loyalty_model_config');
       $this->load->model('loyalty/model_email','loyalty_model_email');
       $this->load->model('loyalty/model_user','loyalty_model_user');
       $this->load->library('MY_PHPMailerClient');
  }

  function index(){
      $this->load->view('templates/navigation');

      // get loyalty not verified
      $result = $this->loyalty_model_verified->get_data_not_verified();

      if(count($result)==0) $data["var_data"] = 0;
      else $data["var_data"] = assign_data($result);

      $this->loyalty_model_config->name = "link";
      $link = $this->loyalty_model_config->get_value_by_setting_name();

      $data["var_link"] = $link;

      $this->load->view('sales/loyalty/verified/v_index', $data);
  }
  //--

  function save(){
      $doc_no = $_POST["doc_no"];
      $line   = $_POST["line"];
      $userid = $_POST["userid"];
      $point  = $_POST["point"];
      $message  = "";
      $status   = 0;
      $datetime = get_datetime_now();

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];

      if($this->loyalty_model_verified->get_status_verified($doc_no, $line)){ // check if already update
          $message  = "The point on this document already Verified";
          $status   = 0;
      }
      else{
          $result1 = $this->loyalty_model_verified->update_status_verified($doc_no, $line, "1", $datetime, $userid); // update to verified
          $result2 = $this->loyalty_model_verified->add_point_user($userid, $point); // update user's point
          $result2 = $this->loyalty_model_verified->add_point_all_user($userid, $point); // update user's point all

          //$this->loyalty_model_verified->insert_email($to, $cc, $subject, $from, $body, $datetime); // insert into email sending

          if($result1 && $result2){
              $response['status'] = "1";
              $response['msg'] = "The Point has been added to the USER";
              echo json_encode($response);
          }
          else{
              if($message == "") $message = "Error";

              $response['status'] = "0";
              $response['msg'] = $message;
              echo json_encode($response);
          }
      }
  }
  //--

  function reject(){
      $doc_no = $_POST["doc_no"];
      $line   = $_POST["line"];
      $userid = $_POST["userid"];
      $point  = $_POST["point"];
      $message  = "";
      $status   = 0;
      $datetime = get_datetime_now();

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];

      if($this->loyalty_model_verified->get_status_verified($doc_no, $line)){ // check if already update
          $message  = "The point on this document already Verified";
          $status   = 0;
      }
      else{
          $result1 = $this->loyalty_model_verified->update_status_rejected($doc_no, $line, "0", $datetime, $userid); // update to verified

          if($result1){
              $response['status'] = "1";
              $response['msg'] = "The Point has Rejected and wouldn't add to the USER";
              echo json_encode($response);
          }
          else{
              if($message == "") $message = "Error";

              $response['status'] = "0";
              $response['msg'] = $message;
              echo json_encode($response);
          }
      }
  }
  //--

  function save_doc(){
      $doc_no = $_POST["doc_no"];
      $message  = "";
      $status   = 0;
      $datetime = get_datetime_now();

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];

      if($this->loyalty_model_verified->get_status_verified_header($doc_no)){ // check if already update
          $response['status'] = "0";
          $response['msg'] = "The Document already Verified or Rejected, please refresh the page";
      }
      else{
          $result_h = assign_data_one($this->loyalty_model_verified->get_data_header($doc_no));
          //$result_d = assign_data($this->loyalty_model_verified->get_data_detail($doc_no));

          $point = $this->loyalty_model_verified->get_point_doc_detail($doc_no);
          foreach($result_d as $row){ $point += $row["point"]; }

          $result = $this->loyalty_model_verified->add_point_user($result_h["created_by"], $point); // update user's point
          $result = $this->loyalty_model_verified->add_point_all_user($result_h["created_by"], $point); // update user's point all

          $result1 = $this->loyalty_model_verified->update_verified_by_doc_header($doc_no, $datetime, $user);
          $result2 = $this->loyalty_model_verified->update_verified_by_doc_detail($doc_no, $datetime, $user);

          /*$result1 = $this->loyalty_model_verified->update_status_verified($doc_no, $line, "1", $datetime, $userid); // update to verified
          $result2 = $this->loyalty_model_verified->add_point_user($userid, $point); // update user's point
          $result2 = $this->loyalty_model_verified->add_point_all_user($userid, $point); // update user's point all*/
      }

      if($result1 && $result2){
          // add point history
          $this->loyalty_model_verified->insert_user_points_hist($result_h["created_by"], $doc_no, $point, "", "", $datetime);
          //---

          // send email
          $result_user = $this->loyalty_model_user->get_user_by_id($result_h["created_by"]);
          $result_user = assign_data_one($result_user);
          $this->insert_email_to_db($doc_no,$result_user["email"]);
          //---

          // run bonus
          $this->check_bonus($result_h["created_by"], $datetime); // check bonus rank level
          //--

          $response['status'] = "1";
          $response['msg'] = "The Point has been added to the USER";
          echo json_encode($response);
      }
      else{
          echo json_encode($response);
      }
  }
  //--

  function reject_doc(){
      $doc_no = $_POST["doc_no"];
      $message = $_POST["message"];
      $status   = 0;
      $datetime = get_datetime_now();

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];

      if(!$this->loyalty_model_verified->get_status_can_reject($doc_no)){ // check if already update
          $response['status'] = "0";
          $response['msg'] = "The Document already Verified or Rejected, You can not Reject, please refresh the page";
      }
      else{
          $result_h = assign_data_one($this->loyalty_model_verified->get_data_header($doc_no));
          $result1 = $this->loyalty_model_verified->update_status_rejected_header($doc_no, $datetime, $user, $message);
          $result2 = $this->loyalty_model_verified->update_status_rejected_detail($doc_no, $datetime, $user);
      }

      if($result1 && $result2){
          $result_user = $this->loyalty_model_user->get_user_by_id($result_h["created_by"]);
          $result_user = assign_data_one($result_user);
          $this->insert_reject_email_to_db($doc_no,$result_user["email"],$message);

          $response['status'] = "1";
          $response['msg'] = "The Document has been rejected...";
          echo json_encode($response);
      }
      else{
          echo json_encode($response);
      }
  }
  //---

  function pointreport(){
    $this->load->view('templates/navigation');
    $this->load->view('sales/loyalty/reportpoint/v_index', $data);
  }
  //---

  function get_report_history(){
      $from = $_POST["date_from"];
      $to   = $_POST["date_to"];

      $result = $this->loyalty_model_verified->get_loyalty_header($from, $to);
      if(count($result) > 0) $data["var_report"] = assign_data($result);
      else $data["var_report"] = 0;

      $this->load->view('sales/loyalty/reportpoint/v_report', $data);
  }
  //---

  function get_detail_invc_d(){
      $doc_no = $_POST["id"];
      $result = $this->loyalty_model_verified->get_detail_invc_d($doc_no);

      if(count($result) > 0) $data["var_detail"] = assign_data($result);
      else $data["var_detail"] = 0;

      $this->load->view('sales/loyalty/reportpoint/v_detail', $data);
  }
  //--

  function insert_email_to_db($doc_no,$email_to){
      $datetime = get_datetime_now();

      $email_config = $this->loyalty_model_config->get_email_detail(); // get email config
      $subject = "Aprobó su factura ".$doc_no; // email subject

      $this->loyalty_model_config->name = "link";
      $link = $this->loyalty_model_config->get_value_by_setting_name();

      $body = $this->my_phpmailerclient->new_invoice_approved($link);

      $this->loyalty_model_email->insert($email_to, "", $subject, $email_config["email_user"], $body, $datetime); // insert into email sending
  }
  //---

  function insert_reject_email_to_db($doc_no,$email_to, $remark2){
      $datetime = get_datetime_now();

      $email_config = $this->loyalty_model_config->get_email_detail(); // get email config
      $subject = "Factura rechazada ".$doc_no; // email subject

      $this->loyalty_model_config->name = "link";
      $link = $this->loyalty_model_config->get_value_by_setting_name();

      $body = $this->my_phpmailerclient->new_invoice_reject($link, $remark2);

      $this->loyalty_model_email->insert($email_to, "", $subject, $email_config["email_user2"], $body, $datetime); // insert into email sending
  }
  //---

  function check_bonus($user,$datetime){
      // check get silver bonus
      $doc_no = "get bonus when reach 1000";
      $result_bonus = $this->loyalty_model_verified->get_mst_bonus_by_name($doc_no,"1");
      if(count($result_bonus) > 0){
          $result_bonus = assign_data_one($result_bonus);

          $point_all = $this->loyalty_model_user->get_user_all_point($user);
          $bronze_point = $this->loyalty_model_verified->get_mst_point_lvl("BRONZE");

          if($point_all >= $bronze_point){
              $get_bronze_bonus = $this->loyalty_model_verified->check_history_point($doc_no, $user);
              if($get_bronze_bonus == 0){
                  $result = $this->loyalty_model_verified->add_point_user($user, $result_bonus["points"]); // update user's point
                  $result = $this->loyalty_model_verified->add_point_all_user($user, $result_bonus["points"]); // update user's point all
                  $this->loyalty_model_verified->insert_user_points_hist($user, $doc_no, $result_bonus["points"], "", "", $datetime);
              }
          }
      }

      // check get gold bonus
      $doc_no = "get bonus when reach 2500";
      $result_bonus = $this->loyalty_model_verified->get_mst_bonus_by_name($doc_no,"1");
      if(count($result_bonus) > 0){
          $result_bonus = assign_data_one($result_bonus);

          $point_all = $this->loyalty_model_user->get_user_all_point($user);
          $gold_point = $this->loyalty_model_verified->get_mst_point_lvl("GOLD");

          if($point_all >= $gold_point){
              $get_gold_bonus = $this->loyalty_model_verified->check_history_point($doc_no, $user);
              if($get_gold_bonus == 0){
                  $result = $this->loyalty_model_verified->add_point_user($user, $result_bonus["points"]); // update user's point
                  $result = $this->loyalty_model_verified->add_point_all_user($user, $result_bonus["points"]); // update user's point all
                  $this->loyalty_model_verified->insert_user_points_hist($user, $doc_no, $result_bonus["points"], "", "", $datetime);
              }
          }
      }
  }
  //---

}
