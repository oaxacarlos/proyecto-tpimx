<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empc_rsv_deleted extends CI_Controller{

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
              $this->load->model('model_empc','',TRUE);

              unset($empc_h_list);
              foreach ($result_sap["PT_RESULT"] as $row) {
                  $empc_h_list[] = $row['REQNR'];
              }

              $result_deleted = $this->model_empc->list_empc_selected_hasnot_canceled($empc_h_list);

              if($result_deleted){
                  foreach($result_deleted as $row){
                      $empc_code = $row['empc_h_code'];
                      $remarks = "EMC Rejected by sistem, because finance deleted the Reservation (".$row['sap_no'].")";
                      $this->load->model('model_empc','',TRUE);

                      $user_id = '8';
                      $date = date('Y-m-d');
                      $datetime = date('Y-m-d H:i:s');

                      $result = $this->model_empc->update_status_empc_to_canceled($user_id,$date,$datetime,$empc_code,$remarks);

                      // get the list EMC Header and Detail for sending email
                      //--- get EMC Header
                      $result_empc_header = $this->model_empc->list_approval_by_empc_code($empc_code);
                      unset($table_empc_h);
                      foreach($result_empc_header as $row){
                          $table_empc_h = array(
                              "created_datetime"  => $row['empc_h_created_datetime'],
                              "plant_code"        => $row['plant_code'],
                              "plant_name"        => $row['plant_name'],
                              "name"              => $row['name'],
                              "depart_name"       => $row['requestor_depart_name'],
                              "empc_status_name"  => $row['empc_status_name'],
                              "rejected_datetime" => $datetime,
                          );
                      }

                      //---- get EMC Detail
                      $result_empc_detail = $this->model_empc->list_empc_d_by_code($empc_code);
                      unset($table_material);
                      foreach($result_empc_detail as $row){
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
                      $result2 = $this->model_empc->list_email_participant($empc_code);
                      //---------------

                      // send email to all participant
                      $this->load->library('MY_phpmailer');
                      foreach($result2 as $row){
                        $body = $this->my_phpmailer->email_body_reject_empc($empc_code,$remarks,$table_empc_h,$table_material);
                        $to = $row['email'];
                        $subject = "EMC Request Rejected";
                        $from_info = "EMC Euromega";
                        $altbody = "";
                        $cc = "";
                        $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
                      }

                      // send to selected person
                      $this->load->library('MY_phpmailer');
                      $email_notif = $this->model_empc->list_email_notif_empc_rejected_by_system_emt003();
                      foreach($email_notif as $row){
                        $body = $this->my_phpmailer->email_body_reject_empc($empc_code,$remarks,$table_empc_h,$table_material);
                        $to = $row['email'];
                        $subject = "EMC Request Rejected";
                        $from_info = "EMC Euromega";
                        $altbody = "";
                        $cc = "";
                        $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
                      }
                      //--------------------

                      if($result) echo "EMC = ".$empc_code." Rejected...<br>";
                      else echo "there is error on EMC = ".$empc_code." could not be rejected...<br>";
                  }
              }
        }
    }
    //---------------------
}
