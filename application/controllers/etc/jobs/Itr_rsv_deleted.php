<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itr_rsv_deleted extends CI_Controller{

    function get_rsv_deleted(){
        $to = date("Ymd");
        $from = date('Ymd', strtotime('-30 days'));

        $sap = $this->config->item('sap300');
        $result_sap = $sap->callFunction("ZFN_MM_GET_RESERVATION_FLW",
        array(
          array("IMPORT","PI_SDATE",$from),
          array("IMPORT","PI_EDATE",$to),
          array("IMPORT","PI_REQNR",""),
          array("IMPORT","PI_RSNUM",""),
          array("IMPORT","PI_ISDEL","X"),
          array("TABLE","PT_RESULT",array()),
        ));

        if ($sap->getStatus() == SAPRFC_OK) {
              $this->load->model('model_itr','',TRUE);

              unset($itr_h_list);
              foreach ($result_sap["PT_RESULT"] as $row) {
                  $itr_h_list[] = $row['REQNR'];
              }

              $result_deleted = $this->model_itr->list_itr_selected_hasnot_canceled($itr_h_list);

              if($result_deleted){
                  foreach($result_deleted as $row){
                      $itr_code = $row['itr_h_code'];
                      $remarks = "ITR Rejected by sistem, because finance deleted the Reservation (".$row['sap_no'].")";
                      $this->load->model('model_itr','',TRUE);

                      $user_id = '8';
                      $date = date('Y-m-d');
                      $datetime = date('Y-m-d H:i:s');

                      $result = $this->model_itr->update_status_itr_to_canceled($user_id,$date,$datetime,$itr_code,$remarks);

                      // get the list ITR Header and Detail for sending email
                      //--- get ITR Header
                      $result_itr_header = $this->model_itr->list_approval_by_itr_code($itr_code);
                      unset($table_itr_h);
                      foreach($result_itr_header as $row){
                          $table_itr_h = array(
                              "created_datetime"  => $row['itr_h_created_datetime'],
                              "plant_code"        => $row['plant_code'],
                              "plant_name"        => $row['plant_name'],
                              "name"              => $row['name'],
                              "depart_name"       => $row['requestor_depart_name'],
                              "itr_status_name"   => $row['itr_status_name'],
                              "rejected_datetime" => $datetime,
                          );
                      }

                      //---- get ITR Detail
                      $result_itr_detail = $this->model_itr->list_itr_d_by_code($itr_code);
                      unset($table_material);
                      foreach($result_itr_detail as $row){
                          $table_material[] = array(
                            "MATNR"   => $row['mat_id'],
                            "MATDESC" => $row['mat_desc'],
                            "ERFMG"   => $row['qty'],
                            "ERFME"   => $row['d_uom'],
                            "ITRPS"   => $row['posnr'],
                          );
                      }
                      //------------------

                      // get this list first because we dont want send email to last person
                      $result2 = $this->model_itr->list_email_participant($itr_code);
                      //---------------

                      // send email to all participant
                      $this->load->library('MY_phpmailer');
                      foreach($result2 as $row){
                        $body = $this->my_phpmailer->email_body_reject_itr($itr_code,$remarks,$table_itr_h,$table_material);
                        $to = $row['email'];
                        $subject = "ITR Request Rejected";
                        $from_info = "ITR Euromega";
                        $altbody = "";
                        $cc = "";
                        $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
                      }

                      // send to selected person
                      $this->load->library('MY_phpmailer');
                      $email_notif = $this->model_itr->list_email_notif_itr_rejected_by_system_emt003();
                      foreach($email_notif as $row){
                        $body = $this->my_phpmailer->email_body_reject_itr($itr_code,$remarks,$table_itr_h,$table_material);
                        $to = $row['email'];
                        $subject = "ITR Request Rejected";
                        $from_info = "ITR Euromega";
                        $altbody = "";
                        $cc = "";
                        $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
                      }
                      //--------------------

                      if($result) echo "ITR = ".$itr_code." Rejected...<br>";
                      else echo "there is error on ITR = ".$itr_code." could not be rejected...<br>";
                  }
              }
        }
    }
    //---------------------
}
