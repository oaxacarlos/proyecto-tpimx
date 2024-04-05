<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itr_apprv extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['itr_apprv'])){
        $this->load->view('view_home');
      }
      else{
        $this->load->model('model_itr','',TRUE);

        $session_data = $this->session->userdata('z_tpimx_logged_in');

        // get who is last approval person
        $last_approval_code = $this->model_itr->get_last_approval_status_from_setting();
        $check_this_user_is_last_approval = 0;
        $data['v_is_last_approval'] = 0;

        $result = $this->model_itr->get_approval_level_user($session_data['z_tpimx_user_id']);
        if($result){
          unset($itr_approval_code_user);
          foreach($result as $row){
              $itr_approval_code_user[] = $row['itr_approval_code'];

              if($last_approval_code == $row['itr_approval_code']){
                $check_this_user_is_last_approval = 1;
                $data['v_is_last_approval'] = 1;
              }
          }
        }
        else{
            $itr_approval_code_user = 0;
        }

        // if last this user last person, get the itr data last approval
        if($check_this_user_is_last_approval == 1){
          $array_last_approval[] = $last_approval_code;
          $result = $this->model_itr->list_approval_sap($session_data['z_tpimx_user_id'],$array_last_approval);
          if($result){
            foreach($result as $row){
               $data['v_list_itr_apprv'][] = array(
                                         "itr_h_code" => $row['itr_h_code'],
                                         "itr_h_created_date" => $row['itr_h_created_date'],
                                         "itr_h_created_datetime" => $row['itr_h_created_datetime'],
                                         "itr_h_created_user" => $row['itr_h_created_user'],
                                         "itr_h_doc_date" => $row['itr_h_doc_date'],
                                         "itr_status" => $row['itr_status'],
                                         "itr_status_name" => $row['itr_status_name'],
                                         "itr_h_text1" => $row['itr_h_text1'],
                                         "itr_type_code" => $row['itr_type_code'],
                                         "itr_type_name" => $row['itr_type_name'],
                                         "depart_code" => $row['depart_code'],
                                         "depart_name" => $row['depart_name'],
                                         "itr_approval_code" => $row['itr_approval_code'],
                                         "email_user" => $row['email_user'],
                                         "gl_id" => $row['gl_id'],
                                         "gl_code" => $row['gl_code'],
                                         "gl_name" => $row['gl_name'],
                                         "gl_text1" => $row['gl_text1'],
                                         "gl_text2" => $row['gl_text2'],
                                         "costcenter_code" => $row['costcenter_code'],
                                         "costcenter_name" => $row['costcenter_name'],
                                         "plant_code" => $row['plant_code'],
                                         "plant_name" => $row['plant_name'],
                                         "name" => $row['name'],
                                         "email" => $row['email'],
                                         "attachment" => $row['attachment'],
                                         "customer_text" => $row['customer_text'],
                                         "itr_project_code" => $row['itr_project_code'],
                                         "itr_project_name" => $row['itr_project_name'],
                                         );
            }
          }
          else $data['v_list_itr_apprv'] = 0;
        }
        //-----------

        // get if remaining not last approval
        $result = $this->model_itr->list_approval($session_data['z_tpimx_user_id'],$session_data['z_tpimx_depart_code'],$itr_approval_code_user);
        if($result){
          foreach($result as $row){
             $data['v_list_itr_apprv'][] = array(
                                       "itr_h_code" => $row['itr_h_code'],
                                       "itr_h_created_date" => $row['itr_h_created_date'],
                                       "itr_h_created_datetime" => $row['itr_h_created_datetime'],
                                       "itr_h_created_user" => $row['itr_h_created_user'],
                                       "itr_h_doc_date" => $row['itr_h_doc_date'],
                                       "itr_status" => $row['itr_status'],
                                       "itr_status_name" => $row['itr_status_name'],
                                       "itr_h_text1" => $row['itr_h_text1'],
                                       "itr_type_code" => $row['itr_type_code'],
                                       "itr_type_name" => $row['itr_type_name'],
                                       "depart_code" => $row['depart_code'],
                                       "depart_name" => $row['depart_name'],
                                       "itr_approval_code" => $row['itr_approval_code'],
                                       "email_user" => $row['email_user'],
                                       "gl_id" => $row['gl_id'],
                                       "gl_code" => $row['gl_code'],
                                       "gl_name" => $row['gl_name'],
                                       "gl_text1" => $row['gl_text1'],
                                       "gl_text2" => $row['gl_text2'],
                                       "costcenter_code" => $row['costcenter_code'],
                                       "costcenter_name" => $row['costcenter_name'],
                                       "plant_code" => $row['plant_code'],
                                       "plant_name" => $row['plant_name'],
                                       "name" => $row['name'],
                                       "email" => $row['email'],
                                       "attachment" => $row['attachment'],
                                       "customer_text" => $row['customer_text'],
                                       "itr_project_code" => $row['itr_project_code'],
                                       "itr_project_name" => $row['itr_project_name'],
                                       );
          }
        }
        else{
            if(isset($data['v_list_itr_apprv'])){
                if(!$data['v_list_itr_apprv']) $data['v_list_itr_apprv'] = 0;
            }
            else{
                 $data['v_list_itr_apprv'] = 0;
            }
        }

        // get cross department with same approval level
        $result = $this->model_itr->list_department_cross_approval($session_data['z_tpimx_user_id'],$session_data['z_tpimx_depart_code'],$itr_approval_code_user);
        if(!$result){
            $list_depart[] = $session_data['z_tpimx_depart_code'];
        }
        else{
          $list_depart[] = $session_data['z_tpimx_depart_code'];
          foreach($result as $row){
              $list_depart[] = $row['depart_code'];
          }
        }
        //------------

        // list approval cross department
        $result = $this->model_itr->list_approval_cross_department($session_data['z_tpimx_user_id'],$list_depart,$itr_approval_code_user);

        if(!$result){
            if(isset($data['v_list_itr_apprv'])){
                if(!$data['v_list_itr_apprv']) $data['v_list_itr_apprv'] = 0;
            }
            else{
                 $data['v_list_itr_apprv'] = 0;
            }
        }
        else{
          foreach($result as $row){
             if($data['v_list_itr_apprv'] == 0) unset($data['v_list_itr_apprv']);
             $data['v_list_itr_apprv'][] = array(
                                       "itr_h_code" => $row['itr_h_code'],
                                       "itr_h_created_date" => $row['itr_h_created_date'],
                                       "itr_h_created_datetime" => $row['itr_h_created_datetime'],
                                       "itr_h_created_user" => $row['itr_h_created_user'],
                                       "itr_h_doc_date" => $row['itr_h_doc_date'],
                                       "itr_status" => $row['itr_status'],
                                       "itr_status_name" => $row['itr_status_name'],
                                       "itr_h_text1" => $row['itr_h_text1'],
                                       "itr_type_code" => $row['itr_type_code'],
                                       "itr_type_name" => $row['itr_type_name'],
                                       "depart_code" => $row['depart_code'],
                                       "depart_name" => $row['depart_name'],
                                       "itr_approval_code" => $row['itr_approval_code'],
                                       "email_user" => $row['email_user'],
                                       "gl_id" => $row['gl_id'],
                                       "gl_code" => $row['gl_code'],
                                       "gl_name" => $row['gl_name'],
                                       "gl_text1" => $row['gl_text1'],
                                       "gl_text2" => $row['gl_text2'],
                                       "costcenter_code" => $row['costcenter_code'],
                                       "costcenter_name" => $row['costcenter_name'],
                                       "plant_code" => $row['plant_code'],
                                       "plant_name" => $row['plant_name'],
                                       "name" => $row['name'],
                                       "email" => $row['email'],
                                       "attachment" => $row['attachment'],
                                       "customer_text" => $row['customer_text'],
                                       "itr_project_code" => $row['itr_project_code'],
                                       "itr_project_name" => $row['itr_project_name'],
                                       );
          }
        }
        // delete duplicate
        if($data['v_list_itr_apprv']!=0){
          $data['v_list_itr_apprv'] = $this->delete_array_duplicate($data['v_list_itr_apprv'],"itr_h_code");
          $data['v_list_itr_apprv'] = $this->aasort($data['v_list_itr_apprv'], "itr_h_created_datetime");
        }

        $this->load->view('itr/view_itr_apprv',$data);
      }
  }
  //----------------

  function show_itr_detail(){
      $itr_code = $_POST['itr_code'];
      $data['v_is_last_approval'] = $_POST['is_last_approval'];

      $this->load->model('model_itr','',TRUE);

      // get header
      $result = $this->model_itr->list_approval_by_itr_code($itr_code);
      if($result){
        foreach($result as $row){
           $data['v_list_itr_apprv_detail_h'][] = array(
                                     "itr_h_code" => $row['itr_h_code'],
                                     "itr_h_created_date" => $row['itr_h_created_date'],
                                     "itr_h_created_datetime" => $row['itr_h_created_datetime'],
                                     "itr_h_created_user" => $row['itr_h_created_user'],
                                     "itr_h_doc_date" => $row['itr_h_doc_date'],
                                     "itr_status" => $row['itr_status'],
                                     "itr_status_name" => $row['itr_status_name'],
                                     "itr_h_text1" => $row['itr_h_text1'],
                                     "itr_type_code" => $row['itr_type_code'],
                                     "itr_type_name" => $row['itr_type_name'],
                                     "depart_code" => $row['depart_code'],
                                     "depart_name" => $row['requestor_depart_name'],
                                     "itr_approval_code" => $row['itr_approval_code'],
                                     "email_user" => $row['email_user'],
                                     "gl_id" => $row['gl_id'],
                                     "gl_code" => $row['gl_code'],
                                     "gl_name" => $row['gl_name'],
                                     "gl_text1" => $row['gl_text1'],
                                     "gl_text2" => $row['gl_text2'],
                                     "costcenter_code" => $row['costcenter_code'],
                                     "costcenter_name" => $row['costcenter_name'],
                                     "plant_code" => $row['plant_code'],
                                     "plant_name" => $row['plant_name'],
                                     "name" => $row['name'],
                                     "email" => $row['email'],
                                     "gl_depart_name" => $row['gl_depart_name'],
                                     "attachment" => $row['attachment'],
                                     "customer_text" => $row['customer_text'],
                                     "itr_project_code" => $row['itr_project_code'],
                                     "itr_project_name" => $row['itr_project_name'],
                                     );
        }
      }
      else $data['v_list_itr_apprv_detail_h'] = 0;

      // get detail
      if($result){
        $result1 = $this->model_itr->list_itr_d_by_code($itr_code);
        if($result1){
          foreach($result1 as $row){
             $data['v_list_itr_apprv_detail_d'][] = array(
                                       "itr_h_code" => $row['itr_h_code'],
                                       "mat_id" => $row['mat_id'],
                                       "qty" => $row['qty'],
                                       "uom" => $row['d_uom'],
                                       "posnr" => $row['posnr'],
                                       "mat_desc" => $row['mat_desc'],
                                       "mat_type" => $row['mat_type'],
                                       "itr_d_text1" => $row['itr_d_text1'],
                                       );
          }
        }
        else $data['v_list_itr_apprv_detail_d'] = 0;
      }

      // get approval
      if($result){
          //$result3 = $this->model_itr->list_itr_h_approval($itr_code);
          $result3 = $this->model_itr->list_itr_h_approval_with_approval_list($itr_code);
          if(!$result3){
              $data['v_list_itr_apprv_detail_approval'] = 0;
          }
          else{
            foreach($result3 as $row){
               $data['v_list_itr_apprv_detail_approval'][] = array(
                                         "itr_h_code" => $row['itr_h_code'],
                                         "itr_approval_code" => $row['itr_approval_code'],
                                         "approval_datetime" => $row['approval_datetime'],
                                         "itr_h_approval_text1" => $row['itr_h_approval_text1'],
                                         "email_approval" => $row['email_approval'],
                                         "user_id" => $row['user_id'],
                                         "name" => $row['name'],
                                         "itr_approval_name" => $row['itr_approval_name'],
                                         );
            }
          }
      }
      else{
         $data['v_list_itr_apprv_detail_approval'] = 0;
      }
      //------------------------

      // get count detail
      $data['count_detail'] = $this->model_itr->count_detail_by_itr_code($itr_code);
      //----------------

      $this->load->view('itr/view_itr_apprv_detail',$data);
  }
  //--------------

  function itr_rejected(){
      $itr_code = $_POST['itr_code'];
      $remarks = str_replace("'","",$_POST['remarks']);
      $this->load->model('model_itr','',TRUE);

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user_id = $session_data['z_tpimx_user_id'];
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

      if($result) echo "1";
      else echo "0";
  }
  //-------------------

  function itr_approval(){
      $itr_code       = $_POST['itr_code'];
      $remarks        = str_replace("'","",$_POST['remarks']);
      $approval_code  = $_POST['itr_approval_code'];
      $itr_gl_id_edit = $_POST['itr_gl_id_edit'];

      $this->load->model('model_itr','',TRUE);

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user_id = $session_data['z_tpimx_user_id'];
      $date = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');

      // check approval level
      $level_up_approve = $this->model_itr->get_approval_one_level_up($approval_code);

      // update approval itr
      if($level_up_approve){
          // get if next is last approval
          $last_next_approval = $this->model_itr->get_last_approval_status_from_setting();
          if($last_next_approval == $level_up_approve){   // if next is last approval
              $last_user_id_temp = $this->model_itr->get_last_approval_userid_from_setting(); // get last approval user
              $last_user_id = explode(";",$last_user_id_temp);  // possible more than one.. explode by ;

              $itr_status = $this->model_itr->get_status_from_approval_code($level_up_approve);
              $result = $this->model_itr->update_status_itr_to_approval($itr_code,$level_up_approve ,$itr_status);

              $this->model_itr->itr_h_code        = $itr_code;
              $this->model_itr->itr_approval_code = $approval_code;
              $this->model_itr->approval_date     = $date;
              $this->model_itr->approval_datetime = $datetime;
              $this->model_itr->itr_h_approval_text1 = $remarks;
              //$this->model_itr->email_user       = $last_email;
              $this->model_itr->email_user       = $session_data['z_tpimx_email'];
              $result = $this->model_itr->insert_itr_h_approval($user_id);

              // update gl_id
              $this->model_itr->itr_h_code = $itr_code;
              $this->model_itr->gl_id      = $itr_gl_id_edit;
              $this->model_itr->update_itr_h_gl_id();
              //-------------------

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
                      "approval_datetime" => $datetime,
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

              $this->load->library('MY_phpmailer');
              foreach($last_user_id as $row){
                  $body = $this->my_phpmailer->email_body_approval_itr($itr_code,$remarks,$table_itr_h,$table_material);
                  $last_email = $this->model_itr->get_email_from_userid($row);
                  $to = $last_email;
                  $subject = "ITR Request Approval";
                  $from_info = "ITR Euromega";
                  $altbody = "";
                  $cc = "";
                  $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
              }
			  

              if($result){
                  //echo "success";
                  $status_execute['status'] = 1;
                  $status_execute['message'] = "ITR has been Approved";
              }
              else{
                  //echo "nosuccess";
                  $status_execute['status'] = 0;
                  $status_execute['message'] = "ITR was not succesful be approved";
              }
              echo json_encode($status_execute);
          }
          else{
              $itr_status = $this->model_itr->get_status_from_approval_code($level_up_approve);
              $result = $this->model_itr->update_status_itr_to_approval($itr_code,$level_up_approve ,$itr_status);
              $this->model_itr->itr_h_code = $itr_code;
              $this->model_itr->itr_approval_code = $approval_code;
              $this->model_itr->approval_date = $date;
              $this->model_itr->approval_datetime = $datetime;
              $this->model_itr->itr_h_approval_text1 = $remarks;
              //$this->model_itr->email_user = $last_email;
              $this->model_itr->email_user       = $session_data['z_tpimx_email'];
              $result = $this->model_itr->insert_itr_h_approval($user_id);

              // update gl_id
              $this->model_itr->itr_h_code = $itr_code;
              $this->model_itr->gl_id      = $itr_gl_id_edit;
              $this->model_itr->update_itr_h_gl_id();
              //-------------------

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
                      "approval_datetime" => $datetime,
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

              // send email
              $this->model_itr->depart_code = $session_data['z_tpimx_depart_code'];
              $this->model_itr->itr_approval_code = $itr_approval_code_lvl1;
              $result2 = $this->model_itr->get_approval_person();

              $this->load->library('MY_phpmailer');
              foreach($result2 as $row){
                $body = $this->my_phpmailer->email_body_approval_itr($itr_code,$remarks,$table_itr_h,$table_material);
                $to = $row['email'];
                $subject = "ITR Request Approval";
                $from_info = "ITR Euromega";
                $altbody = "";
                $cc = "";
                $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
              }

              if($result){
                  //echo "success";
                  $status_execute['status'] = 1;
                  $status_execute['message'] = "ITR has been Approved";
              }
              else{
                  //echo "nosuccess";
                  $status_execute['status'] = 0;
                  $status_execute['message'] = "ITR was not succesful be approved";
              }
              echo json_encode($status_execute);
          }
      }
      else{ // if last level send data to SAP

          // get approval code
          $level_up_approve = $this->model_itr->get_last_approval_status_from_setting();
          $approval_code_done = $this->model_itr->get_approval_code_done_from_setting();

          // update gl_id
          $this->model_itr->itr_h_code = $itr_code;
          $this->model_itr->gl_id      = $itr_gl_id_edit;
          $this->model_itr->update_itr_h_gl_id();
          //-------------------

          // get ITR Header
          $result = $this->model_itr->list_approval_by_itr_code($itr_code);
          foreach($result as $row){
              $sap_gl_account = $row['gl_code'];
              $sap_cost_center = $row['costcenter_code'];
              $sap_depot = $row['plant_code'];
              $sap_itr_type = $row['itr_type_code'];
              $sap_name_person = $row['name'];
              $sap_department_name =$row['requestor_depart_name'];
              $sap_itr_code = $itr_code;
          }

          // get ITR Detail
          $result = $this->model_itr->list_itr_d_by_code($itr_code);
          unset($sap_table_material);
          foreach($result as $row){
              $sap_table_material[] = array(
                "MATNR" => $row['mat_id'],
                "ERFMG" => $row['qty'],
                "ERFME" => $row['d_uom'],
                "SGTXT" => $row['itr_d_text1'],
                "ITRPS" => $row['posnr'],
              );
          }

          // send to SAP
          $sap = $this->config->item('sap300');
          $result_sap = $sap->callFunction("ZFN_MMI_001",
          array(
            array("IMPORT","PI_WERKS",$sap_depot),
            array("IMPORT","PI_SAKNR",$sap_gl_account),
            array("IMPORT","PI_KOSTL",$sap_cost_center),
            array("IMPORT","PI_BWART",$sap_itr_type),
            array("IMPORT","PI_ERNAM",$sap_name_person),
            array("IMPORT","PI_DEPNM",$sap_department_name),
            array("IMPORT","PI_REQNR",$itr_code),
            array("TABLE","PT_DATASOURCE",$sap_table_material),
            array("EXPORT","PE_RESULT_MSG"),
            array("EXPORT","PE_RESULT_ERR"),
            array("EXPORT","PE_RESULT_ST"),
          ));

          unset($status_execute);
          if ($sap->getStatus() == SAPRFC_OK) {           // if reservation succesful created
              if($result_sap['PE_RESULT_ST'] == '1'){
                  $status_execute['status'] = 1;
                  $status_execute['message'] = "The ITR has been approved and created in SAP with
                  Reservation Number = ".$result_sap['PE_RESULT_MSG'];

                  // get this list first because we dont want send email to last person
                  $result2 = $this->model_itr->list_email_participant($itr_code);

                  // update approval status
                  $itr_status = $this->model_itr->get_status_from_approval_code($approval_code_done);
                  $result = $this->model_itr->update_status_itr_to_approval($itr_code,$approval_code_done,$itr_status);
                  $this->model_itr->itr_h_code = $itr_code;
                  $this->model_itr->itr_approval_code = $level_up_approve;
                  $this->model_itr->approval_date = $date;
                  $this->model_itr->approval_datetime = $datetime;
                  $this->model_itr->itr_h_approval_text1 = $remarks;
                  $this->model_itr->email_user = $session_data['z_tpimx_email'];
                  $result = $this->model_itr->insert_itr_h_approval($user_id);    // insert approval header

                  // update sap number to itr_h
                  $this->model_itr->update_sap_no_itr_h($itr_code,$result_sap['PE_RESULT_MSG']);
                  //-------------------------

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
                          "approval_datetime" => $datetime,
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

                  // send email to all participant
                  $this->load->library('MY_phpmailer');
                  foreach($result2 as $row){
                    $body = $this->my_phpmailer->email_body_approval_sap_itr($itr_code,$result_sap['PE_RESULT_MSG'],$remarks,$table_itr_h,$table_material);
                    $to = $row['email'];
                    $subject = "ITR Request Approval to Reservation was DONE";
                    $from_info = "ITR Euromega";
                    $altbody = "";
                    $cc = "";
                    $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);

                    $email_user_sent_already[$row['email']] = 1;
                  }

                  // send to last person should get notif after send to SAP
                  $result2 = $this->model_itr->list_email_last_person_get_notif_send_to_sap_emt001();
                  if($result){
                      foreach($result2 as $row){
                        if(!isset($email_user_sent_already[$row['email']])){
                            $body = $this->my_phpmailer->email_body_approval_sap_itr($itr_code,$result_sap['PE_RESULT_MSG'],$remarks,$table_itr_h,$table_material);
                            $to = $row['email'];
                            $subject = "ITR Request Approval to Reservation was DONE";
                            $from_info = "ITR Euromega";
                            $altbody = "";
                            $cc = "";
                            $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
                            $email_user_sent_already[$row['email']] = 1;
                        }
                      }
                  }
                  //-------------------------

                  // send to depot---
                  $this->model_itr->plant_code = $table_itr_h['plant_code'];
                  $result2 = $this->model_itr->list_email_last_person_get_notif_send_to_sap_emt002();
                  if($result){
                      foreach($result2 as $row){
                        if(!isset($email_user_sent_already[$row['email']])){
                            $body = $this->my_phpmailer->email_body_approval_sap_itr($itr_code,$result_sap['PE_RESULT_MSG'],$remarks,$table_itr_h,$table_material);
                            $to = $row['email'];
                            $subject = "ITR Request Approval to Reservation was DONE";
                            $from_info = "ITR Euromega";
                            $altbody = "";
                            $cc = "";
                            $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
                            $email_user_sent_already[$row['email']] = 1;
                        }
                      }
                  }
                  //-----------------
              }
              else if($result_sap['PE_RESULT_ST'] == '0'){      // if not succesful created
                  $status_execute['status'] = 0;
                  $status_execute['message'] = $result_sap['PE_RESULT_ERR'];
              }
          }
          else{           // if can't connect to SAP
            $status_execute['status'] = 0;
            $status_execute['message'] = "Can't Connect to SAP";
          }
          //$sap->logoff();
          echo json_encode($status_execute);
      }
  }
  //-----------------

  function delete_array_duplicate($array, $key){
      $temp_array = array();
      $i = 0;
      $key_array = array();

      foreach($array as $val) {
          if (!in_array($val[$key], $key_array)) {
              $key_array[$i] = $val[$key];
              $temp_array[$i] = $val;
          }
          $i++;
      }
      return $temp_array;
  }
  //----------------

  function aasort (&$array, $key) {
      $sorter=array();
      $ret=array();
      reset($array);
      foreach ($array as $ii => $va) {
          $sorter[$ii]=$va[$key];
      }
      asort($sorter);
      foreach ($sorter as $ii => $va) {
          $ret[$ii]=$array[$ii];
      }
      $array=$ret;

      return $array;
  }
  //-------------------

  function show_gl(){
      $this->load->model('model_itr','',TRUE);

      // load gl
      $result = $this->model_itr->list_all_gl();
      foreach($result as $row){
         $data['v_list_gl'][] = array(
                                   "gl_id" => $row['gl_id'],
                                   "gl_code" => $row['gl_code'],
                                   "depart_code" => $row['depart_code'],
                                   "gl_text1" => $row['gl_text1'],
                                   "gl_text2" => $row['gl_text2'],
                                   "gl_name" => $row['gl_name'],
                                   "active" => $row['active'],
                                   "depart_name" => $row['depart_name'],
                                   "gl_code_view" => $row['gl_code_view'],
                                   );
      }
      $this->load->view('itr/view_itr_apprv_gl',$data);
  }
  //----------------------

  function super(){
      $this->load->view('templates/navigation');
      if(!isset($_SESSION['menus_list_user']['itr_apprv/super'])){
        $this->load->view('view_home');
      }
      else{
        $this->load->view('itr/view_itr_apprv_super');
      }
  }

  //--------------------------

  function get_approval_super_generate(){
        $from = $_POST['date_from'];
        $to   = $_POST['date_to'];

        $this->load->model('model_itr','',TRUE);
        $result = $this->model_itr->list_itr_show_all_without_cancel($from,$to);

        foreach($result as $row){
            $data['v_list_itr_approval_super'][] = array(
                  "itr_h_code"              => $row['itr_h_code'],
                  "itr_h_created_datetime"  => $row['itr_h_created_datetime'],
                  "name"                    => $row['name'],
                  "email"                   => $row['email'],
                  "depart_name"             => $row['requestor_depart_name'],
                  "itr_type_code"           => $row['itr_type_code'],
                  "gl_code"                 => $row['gl_code'],
                  "costcenter_code"         => $row['costcenter_code'],
                  "plant_code"              => $row['plant_code'],
                  "sap_no"                  => $row['sap_no'],
                  "itr_status_code"         => $row['itr_status_code'],
                  "itr_status_name"         => $row['itr_status_name'],
                  "customer_text"           => $row['customer_text'],
                  "itr_project_code"        => $row['itr_project_code'],
                  "itr_project_name"        => $row['itr_project_name'],
                  "itr_approval_code"       => $row['itr_approval_code'],
            );
        }
        $this->load->view('itr/view_itr_apprv_super_generate',$data);
  }
  //-------------------

  function show_itr_detail_super(){
      $itr_code = $_POST['itr_code'];
      $data['v_is_last_approval'] = $_POST['is_last_approval'];
      $data['status'] = $_POST['status'];
      $data['approval_code'] = $_POST['approval_code'];

      $this->load->model('model_itr','',TRUE);

      // get header
      $result = $this->model_itr->list_approval_by_itr_code($itr_code);
      if($result){
        foreach($result as $row){
           $data['v_list_itr_apprv_detail_h'][] = array(
                                     "itr_h_code" => $row['itr_h_code'],
                                     "itr_h_created_date" => $row['itr_h_created_date'],
                                     "itr_h_created_datetime" => $row['itr_h_created_datetime'],
                                     "itr_h_created_user" => $row['itr_h_created_user'],
                                     "itr_h_doc_date" => $row['itr_h_doc_date'],
                                     "itr_status" => $row['itr_status'],
                                     "itr_status_name" => $row['itr_status_name'],
                                     "itr_h_text1" => $row['itr_h_text1'],
                                     "itr_type_code" => $row['itr_type_code'],
                                     "itr_type_name" => $row['itr_type_name'],
                                     "depart_code" => $row['depart_code'],
                                     "depart_name" => $row['requestor_depart_name'],
                                     "itr_approval_code" => $row['itr_approval_code'],
                                     "email_user" => $row['email_user'],
                                     "gl_id" => $row['gl_id'],
                                     "gl_code" => $row['gl_code'],
                                     "gl_name" => $row['gl_name'],
                                     "gl_text1" => $row['gl_text1'],
                                     "gl_text2" => $row['gl_text2'],
                                     "costcenter_code" => $row['costcenter_code'],
                                     "costcenter_name" => $row['costcenter_name'],
                                     "plant_code" => $row['plant_code'],
                                     "plant_name" => $row['plant_name'],
                                     "name" => $row['name'],
                                     "email" => $row['email'],
                                     "gl_depart_name" => $row['gl_depart_name'],
                                     "attachment" => $row['attachment'],
                                     "customer_text" => $row['customer_text'],
                                     "itr_project_code" => $row['itr_project_code'],
                                     "itr_project_name" => $row['itr_project_name'],
                                     );
        }
      }
      else $data['v_list_itr_apprv_detail_h'] = 0;

      // get detail
      if($result){
        $result1 = $this->model_itr->list_itr_d_by_code($itr_code);
        if($result1){
          foreach($result1 as $row){
             $data['v_list_itr_apprv_detail_d'][] = array(
                                       "itr_h_code" => $row['itr_h_code'],
                                       "mat_id" => $row['mat_id'],
                                       "qty" => $row['qty'],
                                       "uom" => $row['d_uom'],
                                       "posnr" => $row['posnr'],
                                       "mat_desc" => $row['mat_desc'],
                                       "mat_type" => $row['mat_type'],
                                       "itr_d_text1" => $row['itr_d_text1'],
                                       );
          }
        }
        else $data['v_list_itr_apprv_detail_d'] = 0;
      }

      // get approval
      if($result){
          //$result3 = $this->model_itr->list_itr_h_approval($itr_code);
          $result3 = $this->model_itr->list_itr_h_approval_with_approval_list($itr_code);
          if(!$result3){
              $data['v_list_itr_apprv_detail_approval'] = 0;
          }
          else{
            foreach($result3 as $row){
               $data['v_list_itr_apprv_detail_approval'][] = array(
                                         "itr_h_code" => $row['itr_h_code'],
                                         "itr_approval_code" => $row['itr_approval_code'],
                                         "approval_datetime" => $row['approval_datetime'],
                                         "itr_h_approval_text1" => $row['itr_h_approval_text1'],
                                         "email_approval" => $row['email_approval'],
                                         "user_id" => $row['user_id'],
                                         "name" => $row['name'],
                                         "itr_approval_name" => $row['itr_approval_name'],
                                         );
            }
          }
      }
      else{
         $data['v_list_itr_apprv_detail_approval'] = 0;
      }

      $this->load->view('itr/view_itr_apprv_detail_super',$data);
  }
  //--------------

  function approval_super_process(){
      $itr_code       = $_POST['itr_code'];
      $remarks        = str_replace("'","",$_POST['remarks']);
      $approval_code  = $_POST['itr_approval_code'];
      $itr_gl_id_edit = $_POST['itr_gl_id_edit'];

      $this->load->model('model_itr','',TRUE);

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user_id = $session_data['z_tpimx_user_id'];
      $date = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');


      if(($_POST['itr_approval_code'] == "ITRAP000") || $_POST['itr_approval_code'] == "ITRAP002"){
        // update gl_id
        $this->model_itr->itr_h_code = $itr_code;
        $this->model_itr->gl_id      = $itr_gl_id_edit;
        $this->model_itr->update_itr_h_gl_id();
        //-------------------

        // get ITR Header
        $result = $this->model_itr->list_approval_by_itr_code($itr_code);
        foreach($result as $row){
            $sap_gl_account = $row['gl_code'];
            $sap_cost_center = $row['costcenter_code'];
            $sap_depot = $row['plant_code'];
            $sap_itr_type = $row['itr_type_code'];
            $sap_name_person = $row['name'];
            $sap_department_name =$row['requestor_depart_name'];
            $sap_itr_code = $itr_code;
        }

        // get ITR Detail
        $result = $this->model_itr->list_itr_d_by_code($itr_code);
        unset($sap_table_material);
        foreach($result as $row){
            $sap_table_material[] = array(
              "MATNR" => $row['mat_id'],
              "ERFMG" => $row['qty'],
              "ERFME" => $row['d_uom'],
              "SGTXT" => $row['itr_d_text1'],
              "ITRPS" => $row['posnr'],
            );
        }

        // send to SAP
        $sap = $this->config->item('sap300');
        $result_sap = $sap->callFunction("ZFN_MMI_001",
        array(
          array("IMPORT","PI_WERKS",$sap_depot),
          array("IMPORT","PI_SAKNR",$sap_gl_account),
          array("IMPORT","PI_KOSTL",$sap_cost_center),
          array("IMPORT","PI_BWART",$sap_itr_type),
          array("IMPORT","PI_ERNAM",$sap_name_person),
          array("IMPORT","PI_DEPNM",$sap_department_name),
          array("IMPORT","PI_REQNR",$itr_code),
          array("TABLE","PT_DATASOURCE",$sap_table_material),
          array("EXPORT","PE_RESULT_MSG"),
          array("EXPORT","PE_RESULT_ERR"),
          array("EXPORT","PE_RESULT_ST"),
        ));

        unset($status_execute);
        if ($sap->getStatus() == SAPRFC_OK) {           // if reservation succesful created
            if($result_sap['PE_RESULT_ST'] == '1'){
                $status_execute['status'] = 1;
                $status_execute['message'] = "The ITR has been approved and created in SAP with
                Reservation Number = ".$result_sap['PE_RESULT_MSG'];

                // get this list first because we dont want send email to last person
                $result2 = $this->model_itr->list_email_participant($itr_code);

                // update approval status
                $itr_status = $this->model_itr->get_status_from_approval_code($approval_code_done);
                $result = $this->model_itr->update_status_itr_to_approval($itr_code,$approval_code_done,$itr_status);
                $this->model_itr->itr_h_code = $itr_code;
                $this->model_itr->itr_approval_code = $level_up_approve;
                $this->model_itr->approval_date = $date;
                $this->model_itr->approval_datetime = $datetime;
                $this->model_itr->itr_h_approval_text1 = $remarks;
                $this->model_itr->email_user = $session_data['z_tpimx_email'];
                $result = $this->model_itr->insert_itr_h_approval($user_id);    // insert approval header

                // update sap number to itr_h
                $this->model_itr->update_sap_no_itr_h($itr_code,$result_sap['PE_RESULT_MSG']);
                //-------------------------

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
                        "approval_datetime" => $datetime,
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

                // send email to all participant
                $this->load->library('MY_phpmailer');
                foreach($result2 as $row){
                  $body = $this->my_phpmailer->email_body_approval_sap_itr($itr_code,$result_sap['PE_RESULT_MSG'],$remarks,$table_itr_h,$table_material);
                  $to = $row['email'];
                  $subject = "ITR Request Approval to Reservation was DONE";
                  $from_info = "ITR Euromega";
                  $altbody = "";
                  $cc = "";
                  $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
                }

                // send to last person should get notif after send to SAP
                $result2 = $this->model_itr->list_email_last_person_get_notif_send_to_sap_emt001();
                if($result){
                    //$this->load->library('MY_phpmailer');
                    foreach($result2 as $row){
                      $body = $this->my_phpmailer->email_body_approval_sap_itr($itr_code,$result_sap['PE_RESULT_MSG'],$remarks,$table_itr_h,$table_material);
                      $to = $row['email'];
                      $subject = "ITR Request Approval to Reservation was DONE";
                      $from_info = "ITR Euromega";
                      $altbody = "";
                      $cc = "";
                      $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
                    }
                }
                //-------------------------
            }
            else if($result_sap['PE_RESULT_ST'] == '0'){      // if not succesful created
                $status_execute['status'] = 0;
                $status_execute['message'] = $result_sap['PE_RESULT_ERR'];
            }
      }
      else if($_POST['itr_approval_code'] == "ITRAP001"){
        // check approval level
        $level_up_approve = $this->model_itr->get_approval_one_level_up($approval_code);

        // get if next is last approval
        $last_next_approval = $this->model_itr->get_last_approval_status_from_setting();
        if($last_next_approval == $level_up_approve){   // if next is last approval
            $last_user_id_temp = $this->model_itr->get_last_approval_userid_from_setting(); // get last approval user
            $last_user_id = explode(";",$last_user_id_temp);  // possible more than one.. explode by ;

            $itr_status = $this->model_itr->get_status_from_approval_code($level_up_approve);
            $result = $this->model_itr->update_status_itr_to_approval($itr_code,$level_up_approve ,$itr_status);

            $this->model_itr->itr_h_code        = $itr_code;
            $this->model_itr->itr_approval_code = $approval_code;
            $this->model_itr->approval_date     = $date;
            $this->model_itr->approval_datetime = $datetime;
            $this->model_itr->itr_h_approval_text1 = $remarks;
            //$this->model_itr->email_user       = $last_email;
            $this->model_itr->email_user       = $session_data['z_tpimx_email'];
            $result = $this->model_itr->insert_itr_h_approval($user_id);

            // update gl_id
            $this->model_itr->itr_h_code = $itr_code;
            $this->model_itr->gl_id      = $itr_gl_id_edit;
            $this->model_itr->update_itr_h_gl_id();
            //-------------------

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
                    "approval_datetime" => $datetime,
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

            $this->load->library('MY_phpmailer');
            foreach($last_user_id as $row){
                $body = $this->my_phpmailer->email_body_approval_itr($itr_code,$remarks,$table_itr_h,$table_material);
                $last_email = $this->model_itr->get_email_from_userid($row);
                $to = $last_email;
                $subject = "ITR Request Approval";
                $from_info = "ITR Euromega";
                $altbody = "";
                $cc = "";
                $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
            }

            if($result){
                //echo "success";
                $status_execute['status'] = 1;
                $status_execute['message'] = "ITR has been Approved";
            }
            else{
                //echo "nosuccess";
                $status_execute['status'] = 0;
                $status_execute['message'] = "ITR was not succesful be approved";
            }
            echo json_encode($status_execute);
          }
        }
      }
      //----------------------------
  }
  //--------------------------

}

?>
