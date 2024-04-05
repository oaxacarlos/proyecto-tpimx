<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Redeem extends CI_Controller{

  function __construct(){
    parent::__construct();
       $this->load->model('loyalty/model_redeem','loyalty_model_redeem');
       $this->load->model('loyalty/model_email','loyalty_model_email');
       $this->load->model('loyalty/model_config','loyalty_model_config');
       $this->load->library('MY_PHPMailerClient');
  }

  function index(){
      $this->load->view('templates/navigation');

      // get loyalty not verified
      $result = $this->loyalty_model_redeem->get_redeem_not_completed_yet();

      if(count($result)==0) $data["var_data"] = 0;
      else $data["var_data"] = assign_data($result);

      $this->load->view('sales/loyalty/redeem/v_index', $data);
  }
  //----

  function update_buy(){
      $doc_no = $_POST["doc_no"];
      $date   = $_POST["date"];
      $remark = $_POST["remark"];
      $datetime = get_datetime_now();

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];

      $result = $this->loyalty_model_redeem->update_buy($datetime, $remark, $user, $date, $doc_no);

      if($result){
          $response['status'] = "1";
          $response['msg'] = "Bought information has been update";
          $response['date'] = $date;
          echo json_encode($response);
      }
      else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          $response['date'] = "";
          echo json_encode($response);
      }
  }
  //--

  function update_sent(){
      $doc_no = $_POST["doc_no"];
      $date   = $_POST["date"];
      $remark = $_POST["remark"];
      $datetime = get_datetime_now();

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];

      $result = $this->loyalty_model_redeem->update_sent($datetime, $remark, $user, $date, $doc_no);

      if($result){

          $result_redeem_h = $this->loyalty_model_redeem->get_redeem_detail_by_doc_no($doc_no);
          if(count($result_redeem_h) > 0){
            $result_redeem_h = assign_data_one($result_redeem_h);
            $this->insert_email_send_redeem($doc_no, $result_redeem_h["email"], $result_redeem_h["link_image"], $result_redeem_h["desc"]); // send email
          }

          $response['status'] = "1";
          $response['msg'] = "Sent information has been update";
          $response['date'] = $date;
          echo json_encode($response);
      }
      else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          $response['date'] = "";
          echo json_encode($response);
      }
  }
  //--

  function update_delivered(){
      $doc_no = $_POST["doc_no"];
      $date   = $_POST["date"];
      $datetime = get_datetime_now();

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];

      $result = $this->loyalty_model_redeem->update_delivered($datetime, $user, $date, $doc_no);

      if($result){
          $response['status'] = "1";
          $response['msg'] = "Delivered information has been update";
          $response['date'] = $date;
          echo json_encode($response);
      }
      else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          $response['date'] = "";
          echo json_encode($response);
      }
  }
  //--

  function redeemreport(){
    $this->load->view('templates/navigation');
    $this->load->view('sales/loyalty/reportredeem/v_index', $data);
  }
  //---

  function get_redeem_report(){
      $from = $_POST["date_from"];
      $to   = $_POST["date_to"];

      $result = $this->loyalty_model_redeem->get_redeem_report($from, $to);
      if(count($result) > 0) $data["var_report"] = assign_data($result);
      else $data["var_report"] = 0;

      $this->load->view('sales/loyalty/reportredeem/v_report', $data);
  }
  //---

  function insert_email_send_redeem($doc_no, $email_to, $product, $desc){
      $datetime = get_datetime_now();

      $email_config = $this->loyalty_model_config->get_email_detail(); // get email config
      $subject = "Su artículo ha sido enviado ".$doc_no; // email subject

      $this->loyalty_model_config->name = "link";
      $link = $this->loyalty_model_config->get_value_by_setting_name();

      $link_pic = $link.$this->config->item("product_client_image").$product;

      $body = $this->my_phpmailerclient->new_send_item($link, $link_pic, $desc);

      $this->loyalty_model_email->insert($email_to, "", $subject, $email_config["email_user"], $body, $datetime); // insert into email sending
  }
  //---

}

?>
