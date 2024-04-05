<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Whshipment extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();
  }
  //----

  function send_notif(){

      $data = $this->check_data_in_nav(); // check data in navision

      if(!is_null($data)){
          if(count($data) > 0){ // if there is data not process yet

              $this->insert_to_in_out_bound_h_info($data);

              $result = $this->send_email($data);
              if($result) echo "Notification has been sent...";
          }
          else{
            echo "No Data Yet.. All Data has been proceed..";
          }
      }
      else{
          echo "No Data Yet.. All Data has been proceed..";
      }

  }
  //---

  function check_data_in_nav(){
      $this->load->model('model_outbound','',TRUE);
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      // get header
      $result = $this->model_outbound->whship_list_h();
      unset($data['var_whship_list_h']);
      if($result) $data['var_whship_list_h'] = assign_data($result);

      //---

      if($result){
          // check if already in tsc_in_out_bound_h
          unset($doc_no);
          foreach($data['var_whship_list_h'] as $row){
              $doc_no[] = $row['no'];
          }
          $result_in_out_bound_h = $this->model_tsc_in_out_bound_h->check_h_by_doc_no($doc_no);
          //----

          // remove data already proceed to in_out_bound_h
          if(count($result_in_out_bound_h ) > 0){
              $data['var_whship_list_h'] = $this->remove_already_process_from_whship_to_in_out_bound($data['var_whship_list_h'], $result_in_out_bound_h);
          }
          //----
      }

      return $data['var_whship_list_h'];
  }
  //---

    function remove_already_process_from_whship_to_in_out_bound($whship_h, $in_out_bound_h){
      $temp = $whship_h;
      unset($whship_h);
      foreach($temp as $key => $row){
          $is_there = 0;
          foreach($in_out_bound_h as $row2){
              if($row['no'] == $row2['doc_no']){
                  $is_there = 1; break;
              }
          }

          if($is_there == 0) $whship_h[] = $temp[$key];
      }

      return $whship_h;
    }
    //----

  function send_email($data){
      $this->load->model('model_config','',TRUE);
      $this->load->library('MY_phpmailer');

      // get send to
      $this->model_config->name = "email_whship_nav_no_process_to";
      $send_to = $this->model_config->get_value_by_setting_name();
      //---

      // get send cc
      unset($cc);
      $this->model_config->name = "email_whship_nav_no_process_cc";
      $cc_temp = $this->model_config->get_value_by_setting_name();
      $cc = explode("|",$cc_temp);
      //--

      $body = $this->my_phpmailer->email_body_whshipment_not_process($data);
      $to = $send_to;
      $subject = "Warehouse Shipment not proceed yet..";
      $from_info = "WMS TPI-MX";
      $altbody = "";
      $result = $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);

      return $result;
  }
  //---

  function insert_to_in_out_bound_h_info($data){
      $this->load->model('model_tsc_in_out_bound_h_info','',TRUE);
      $datetime = get_datetime_now();

      foreach($data as $row){
          $this->model_tsc_in_out_bound_h_info->doc_no = $row['no'];
          $this->model_tsc_in_out_bound_h_info->nav_datetime = $datetime;
          $this->model_tsc_in_out_bound_h_info->insert();
      }
  }
  //---
}
