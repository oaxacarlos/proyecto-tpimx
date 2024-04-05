<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empc extends CI_Controller{

    function __construct(){
      parent::__construct();
      //$this->load->database();
    }

    function request_new(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user']['empc/request_new'])){
          $this->load->view('view_home');
        }
        else{
            // load material
            $this->load->model('model_itr','',TRUE);
            $result = $this->model_itr->list_all_material('Y');
            foreach($result as $row){
               $data['v_list_material'][] = array(
                                         "mat_id" => $row['mat_id'],
                                         "mat_desc" => $row['mat_desc'],
                                         "mat_type" => $row['mat_type'],
                                         "uom" => $row['uom'],
                                         "lab_office" => $row['lab_office'],
                                         );
            }

            // load itr type
            $this->load->model('model_mster_data','',TRUE);
            $result = $this->model_mster_data->list_all_movement_type();
            foreach($result as $row){
               if($row['movt_type_code'] != "202"){
                 $data['v_list_empc_type'][] = array(
                                           "movt_type_code" => $row['movt_type_code'],
                                           "movt_type_name" => $row['movt_type_name'],
                                           "movt_type_text" => $row['movt_type_text'],
                                           "active" => $row['active']
                                           );
               }
            }

            // load uom
            $this->load->model('model_itr','',TRUE);
            $result = $this->model_itr->list_all_uom();
            foreach($result as $row){
               $data['v_list_uom'][] = array(
                                         "uom_code" => $row['uom_code'],
                                         "uom_name" => $row['uom_name'],
                                         "order"    => $row['order']
                                         );
            }

            //load employee
            $this->load->model('model_mster_data',TRUE);
            $result = $this->model_mster_data->list_all_employee();
            foreach($result as $row){
                $data['v_list_employee'][] = array(
                    "employee_code"     => $row['employee_code'],
                    "employee_name"     => $row['employee_name'],
                    "employee_address"  => $row['employee_address'],
                    "phone1"            => $row['phone1'],
                );
            }
        }

        $this->load->view('empc/view_empc_request_new',$data);
    }
    //----

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
        $this->load->view('empc/view_empc_request_gl',$data);
    }
    //----------------------

    function show_costcenter(){
        $this->load->model('model_itr','',TRUE);

        $plant_code = $_POST['plant'];

        // load costcenter
        $result = $this->model_itr->list_costcenter_bydepot($plant_code);
        foreach($result as $row){
           $data['v_list_costcenter'][] = array(
                                     "costcenter_code" => $row['costcenter_code'],
                                     "costcenter_name" => $row['costcenter_name'],
                                     "costcenter_text" => $row['costcenter_text'],
                                     "active" => $row['active']
                                     );
        }
        $this->load->view('empc/view_empc_request_costcenter',$data);
    }
    //-----------------

    function show_plant(){
        $this->load->model('model_itr','',TRUE);
        // load costcenter
        $result = $this->model_itr->list_all_plant();
        foreach($result as $row){
           $data['v_list_plant'][] = array(
                                     "plant_code" => $row['plant_code'],
                                     "plant_name" => $row['plant_name'],
                                     "plant_info" => $row['plant_info'],
                                     "active" => $row['active']
                                     );
        }
        $this->load->view('empc/view_empc_request_plant',$data);
    }
    //-----------------

    function empc_request_new_add(){
        $plant      = $_POST['plant'];
        $empc_type  = $_POST['empc_type'];
        $gl         = $_POST['gl'];
        $costcenter = $_POST['costcenter'];
        $remarks    = str_replace("'","",$_POST['remarks']);
        $customer   = $_POST['customer'];
        $empc_attachment    = $_POST['empc_attachment'];
        $matid      = json_decode(stripslashes($_POST['matid']));
        $qty        = json_decode(stripslashes($_POST['qty']));
        $uom        = json_decode(stripslashes($_POST['uom']));
        $text1      = json_decode(stripslashes($_POST['text1']));
        $date       = date('Y-m-d');
        $datetime   = date('Y-m-d H:i:s');
        $datecode   = date("Ymd");

        if($empc_type == "201") $prefix_code = "EMC";
        //else if($empc_type == "202") $prefix_code = "RTR";

        $session_data = $this->session->userdata('z_tpimx_logged_in');

        // get approval level 1
        $this->load->model('model_empc');

        $result = $this->model_empc->get_approval_level1();
        foreach($result as $row){
            $empc_approval_code_lvl1 = $row->empc_approval_code;
        }

        // insert table empc_h
        $this->model_empc->prefix_code = $prefix_code;
        $this->model_empc->empc_h_created_user = $session_data['z_tpimx_user_id'];
        $this->model_empc->empc_status = "EMPCST001";
        $this->model_empc->empc_h_text1 = $remarks;
        $this->model_empc->empc_h_text2 = "";
        $this->model_empc->empc_h_text3 = "";
        $this->model_empc->empc_type_code = $empc_type;
        $this->model_empc->depart_code = $session_data['z_tpimx_depart_code'];
        $this->model_empc->empc_approval_code = $empc_approval_code_lvl1;
        $this->model_empc->email_user = $session_data['z_tpimx_email'];
        $this->model_empc->gl_id = $gl;
        $this->model_empc->costcenter_code = $costcenter;
        $this->model_empc->plant_code = $plant;
        $this->model_empc->customer = $customer;
        $this->model_empc->project = "";
        $this->model_empc->attachment = $empc_attachment;
        $this->load->model('model_empc','',TRUE);
        $new_code = $this->model_empc->call_store_procedure_new_empc();

        // insert detail empc_d
        $this->model_empc->empc_h_code = $new_code;
        unset($table_empc_d);
        for($i=0;$i<count($matid);$i++){
            $table_empc_d[] = array(
                "matid"       => $matid[$i],
                "qty"         => $qty[$i],
                "uom"         => $uom[$i],
                "empc_d_text1" => str_replace("'","",$text1[$i]),
                "posnr"       => $i+1,
            );
        }
        $this->load->model('model_empc','',TRUE);
        $result1 = $this->model_empc->insert_empc_d_version2($table_empc_d);
        //-----------------------

        // get his person approval
        $this->model_empc->depart_code = $session_data['z_tpimx_depart_code'];
        $this->model_empc->empc_approval_code = $empc_approval_code_lvl1;
        $this->load->model('model_empc','',TRUE);
        $result2 = $this->model_empc->get_approval_person();

        // get the list empc Header and Detail for sending email
        //--- get empc Header
        $this->load->model('model_empc','',TRUE);
        $result_empc_header = $this->model_empc->list_approval_by_empc_code($new_code);
        unset($table_empc_h);
        foreach($result_empc_header as $row){
            $table_empc_h = array(
                "created_datetime"  => $row['empc_h_created_datetime'],
                "plant_code"        => $row['plant_code'],
                "plant_name"        => $row['plant_name'],
                "name"              => $row['name'],
                "depart_name"       => $row['requestor_depart_name'],
                "empc_status_name"  => $row['empc_status_name'],
                "employee_code"     => $row['employee_code'],
                "employee_name"     => $row['employee_name'],
            );
        }

        //---- get empc Detail
        $this->load->model('model_empc','',TRUE);
        $result_empc_detail = $this->model_empc->list_empc_d_by_code($new_code);
        unset($table_material);
        foreach($result_empc_detail as $row){
            $table_material[] = array(
              "MATNR"   => $row['mat_id'],
              "MATDESC" => $row['mat_desc'],
              "ERFMG"   => $row['qty'],
              "ERFME"   => $row['d_uom'],
              "POSNR"   => $row['posnr'],
            );
        }
        //------------------
		
						$filename = "hasil.txt";
	$myfile = fopen($filename, "a");
	fwrite($myfile,"sblm email\r\n");
	fclose($myfile);
		
        // send email
        $this->load->library('MY_phpmailer');
        foreach($result2 as $row){
          $body = $this->my_phpmailer->email_body_new_empc($new_code,$remarks,$table_empc_h,$table_material);
          $to = $row['email'];
          $subject = "EMC Request New";
          $from_info = "EMC Euromega";
          $altbody = "";
          $cc = "";
          $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
        }
        //--------------------

				$filename = "hasil.txt";
	$myfile = fopen($filename, "a");
	fwrite($myfile,"check email\r\n");
	fclose($myfile);
		
        if($result) echo $new_code;
        else echo "nosuccess";
    }
    //-----------

    function empc_request_new_uploadfile(){
        $src = $_FILES['file']['tmp_name'];
        //$empc_code = $_POST['empc_code'];

        $target_file = $this->config->item('upload_path_empc');
        $temp = explode(".", $_FILES["file"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        $targ = $target_file.$newfilename;

        $result["status"] = move_uploaded_file($src, $targ);

        if($result["status"] == 1) $result["filename"] = $newfilename;

        echo json_encode($result);

        //$this->model_empc->empc_h_code = $empc_code;
        //$this->model_empc->update_empc_h_attachment($newfilename); // update attachment file
    }
    //---------------------

    function approval(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user']['empc/approval'])){
          $this->load->view('view_home');
        }
        else{
              $session_data = $this->session->userdata('z_tpimx_logged_in');

              // get last approval code
              $this->load->model('model_general','',TRUE);
              $last_approval_code = $this->model_general->get_last_approval_status_from_setting();
              $check_this_user_is_last_approval = 0;
              $data['v_is_last_approval'] = 0;


              // get level approval user
              $this->load->model('model_empc','',TRUE);
              $result = $this->model_empc->get_approval_level_user($session_data['z_tpimx_user_id']);
              if($result){
                unset($empc_approval_code_user);
                foreach($result as $row){
                    $empc_approval_code_user[] = $row['empc_approval_code'];

                    if($last_approval_code == $row['empc_approval_code']){
                      $check_this_user_is_last_approval = 1;
                      $data['v_is_last_approval'] = 1;
                    }
                }
              }
              else{ $empc_approval_code_user = 0; }
              //-----------------

              // get EMPC data with user approval level
              $this->load->model('model_empc','',TRUE);
              $result = $this->model_empc->list_approval($session_data['z_tpimx_user_id'],$session_data['z_tpimx_depart_code'],$empc_approval_code_user);
              if($result){
                foreach($result as $row){
                   $data['v_list_empc_apprv'][] = array(
                                             "empc_h_code" => $row['empc_h_code'],
                                             "empc_h_created_date" => $row['empc_h_created_date'],
                                             "empc_h_created_datetime" => $row['empc_h_created_datetime'],
                                             "empc_h_created_user" => $row['empc_h_created_user'],
                                             "empc_h_doc_date" => $row['empc_h_doc_date'],
                                             "empc_status" => $row['empc_status'],
                                             "empc_status_name" => $row['empc_status_name'],
                                             "empc_h_text1" => $row['empc_h_text1'],
                                             "empc_type_code" => $row['empc_type_code'],
                                             "empc_type_name" => $row['empc_type_name'],
                                             "depart_code" => $row['depart_code'],
                                             "depart_name" => $row['depart_name'],
                                             "empc_approval_code" => $row['empc_approval_code'],
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
                                             "employee_code" => $row['employee_code'],
                                             "employee_name" => $row['employee_name'],
                                             "customer_text" => $row['customer_text'],
                                             );
                }
              }
              else{
                  if(isset($data['v_list_empc_apprv'])){
                      if(!$data['v_list_empc_apprv']) $data['v_list_empc_apprv'] = 0;
                  }
                  else{
                       $data['v_list_empc_apprv'] = 0;
                  }
              }
              //-------------------

              $this->load->view('empc/view_empc_approval',$data);
        }

    }
    //--------------

    function show_empc_detail(){
        $empc_code = $_POST['empc_code'];
        $data['v_is_last_approval'] = $_POST['is_last_approval'];

        $this->load->model('model_empc','',TRUE);
        $result = $this->model_empc->list_approval_by_empc_code($empc_code);
        if($result){
          foreach($result as $row){
             $data['v_list_empc_apprv_detail_h'][] = array(
                                       "empc_h_code" => $row['empc_h_code'],
                                       "empc_h_created_date" => $row['empc_h_created_date'],
                                       "empc_h_created_datetime" => $row['empc_h_created_datetime'],
                                       "empc_h_created_user" => $row['empc_h_created_user'],
                                       "empc_h_doc_date" => $row['empc_h_doc_date'],
                                       "empc_status" => $row['empc_status'],
                                       "empc_status_name" => $row['empc_status_name'],
                                       "empc_h_text1" => $row['empc_h_text1'],
                                       "empc_type_code" => $row['empc_type_code'],
                                       "movt_type_name" => $row['movt_type_name'],
                                       "depart_code" => $row['depart_code'],
                                       "depart_name" => $row['requestor_depart_name'],
                                       "empc_approval_code" => $row['empc_approval_code'],
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
                                       "employee_code" => $row['employee_code'],
                                       "employee_name" => $row['employee_name'],
                                       "customer_text" => $row['customer_text'],
                                       );
          }
        }
        else $data['v_list_empc_apprv_detail_h'] = 0;

        // get detail
        $this->load->model('model_empc','',TRUE);
        if($result){
          $result1 = $this->model_empc->list_empc_d_by_code($empc_code);
          if($result1){
            foreach($result1 as $row){
               $data['v_list_empc_apprv_detail_d'][] = array(
                                         "empc_h_code" => $row['empc_h_code'],
                                         "mat_id" => $row['mat_id'],
                                         "qty" => $row['qty'],
                                         "uom" => $row['d_uom'],
                                         "posnr" => $row['posnr'],
                                         "mat_desc" => $row['mat_desc'],
                                         "mat_type" => $row['mat_type'],
                                         "empc_d_text1" => $row['empc_d_text1'],
                                         );
            }
          }
          else $data['v_list_empc_apprv_detail_d'] = 0;
        }

        // get approval
        if($result){
            //$result3 = $this->model_itr->list_itr_h_approval($itr_code);
            $result3 = $this->model_empc->list_empc_h_approval_with_approval_list($empc_code);
            if(!$result3){
                $data['v_list_empc_apprv_detail_approval'] = 0;
            }
            else{
              foreach($result3 as $row){
                 $data['v_list_empc_apprv_detail_approval'][] = array(
                                           "empc_h_code" => $row['empc_h_code'],
                                           "empc_approval_code" => $row['empc_approval_code'],
                                           "approval_datetime" => $row['approval_datetime'],
                                           "empc_h_approval_text1" => $row['empc_h_approval_text1'],
                                           "email_approval" => $row['email_approval'],
                                           "user_id" => $row['user_id'],
                                           "name" => $row['name'],
                                           "empc_approval_name" => $row['empc_approval_name'],
                                           );
              }
            }
        }
        else{
           $data['v_list_empc_apprv_detail_approval'] = 0;
        }
        //------------------------

        // get count detail
        $this->load->model('model_empc','',TRUE);
        $data['count_detail'] = $this->model_empc->count_detail_by_empc_code($empc_code);
        //----------------

        $this->load->view('empc/view_empc_approval_detail',$data);
    }
    //-----------------

    function show_gl_approval(){
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
        $this->load->view('empc/view_empc_approval_gl',$data);
    }
    //----------------------

    function empc_rejected(){
        $empc_code = $_POST['empc_code'];
        $remarks = str_replace("'","",$_POST['remarks']);


        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user_id = $session_data['z_tpimx_user_id'];
        $date = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');

        $this->load->model('model_empc','',TRUE);
        $result = $this->model_empc->update_status_empc_to_canceled($user_id,$date,$datetime,$empc_code,$remarks);

        // get the list empc Header and Detail for sending email
        //--- get empc Header
        $this->load->model('model_empc','',TRUE);
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

        //---- get empc Detail
        $this->load->model('model_empc','',TRUE);
        $result_empc_detail = $this->model_empc->list_empc_d_by_code($empc_code);
        unset($table_material);
        foreach($result_empc_detail as $row){
            $table_material[] = array(
              "MATNR"   => $row['mat_id'],
              "MATDESC" => $row['mat_desc'],
              "ERFMG"   => $row['qty'],
              "ERFME"   => $row['d_uom'],
              "POSNR"   => $row['posnr'],
            );
        }
        //------------------

        // get this list first because we dont want send email to last person
        $this->load->model('model_empc','',TRUE);
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

        if($result) echo "1";
        else echo "0";
    }
    //-------------------

    function empc_approval_process(){
        $empc_code       = $_POST['empc_code'];
        $remarks        = str_replace("'","",$_POST['remarks']);
        $approval_code  = $_POST['empc_approval_code'];
        $empc_gl_id_edit = $_POST['empc_gl_id_edit'];

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user_id = $session_data['z_tpimx_user_id'];
        $date = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');

        // check approval level
        $this->load->model('model_empc','',TRUE);
        $level_up_approve = $this->model_empc->get_approval_one_level_up($approval_code);

        if($level_up_approve){    // update the EMPC
            $empc_status = $this->model_empc->get_status_from_approval_code($level_up_approve);

            $result = $this->model_empc->update_status_empc_to_approval($empc_code,$level_up_approve ,$empc_status);

            $this->model_empc->empc_h_code            = $empc_code;
            $this->model_empc->empc_approval_code     = $approval_code;
            $this->model_empc->approval_date          = $date;
            $this->model_empc->approval_datetime      = $datetime;
            $this->model_empc->empc_h_approval_text1  = $remarks;
            $this->model_empc->email_user             = $session_data['z_tpimx_email'];
            $result = $this->model_empc->insert_empc_h_approval($user_id);

            // update gl_id
            $this->model_empc->empc_h_code = $empc_code;
            $this->model_empc->gl_id      = $empc_gl_id_edit;
            $this->model_empc->update_empc_h_gl_id();
            //-------------------

            // get the list empc Header and Detail for sending email
            //--- get empc Header
            $result_empc_header = $this->model_empc->list_approval_by_empc_code($empc_code);

            unset($table_empc_h);
            foreach($result_empc_header as $row){
                $table_empc_h = array(
                    "created_datetime"  => $row['empc_h_created_datetime'],
                    "plant_code"        => $row['plant_code'],
                    "plant_name"        => $row['plant_name'],
                    "name"              => $row['name'],
                    "depart_name"       => $row['requestor_depart_name'],
                    "empc_status_name"   => $row['empc_status_name'],
                    "approval_datetime" => $datetime,
                );
            }

            //---- get empc Detail
            $result_empc_detail = $this->model_empc->list_empc_d_by_code($empc_code);
            unset($table_material);
            foreach($result_empc_detail as $row){
                $table_material[] = array(
                  "MATNR"   => $row['mat_id'],
                  "MATDESC" => $row['mat_desc'],
                  "ERFMG"   => $row['qty'],
                  "ERFME"   => $row['d_uom'],
                  "POSNR"   => $row['posnr'],
                );
            }
            //------------------

            // send email
            $this->load->model('model_empc','',TRUE);
            $result2 = $this->model_empc->list_email_empcapp($level_up_approve);

            $this->load->library('MY_phpmailer');
            foreach($result2 as $row){
              $body = $this->my_phpmailer->email_body_approval_empc($empc_code,$remarks,$table_empc_h,$table_material);
              $to = $row['email'];
              $subject = "EMC Request Approval";
              $from_info = "EMC Euromega";
              $altbody = "";
              $cc = "";
              $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
            }

            if($result){
                //echo "success";
                $status_execute['status'] = 1;
                $status_execute['message'] = "EMC has been Approved";
            }
            else{
                //echo "nosuccess";
                $status_execute['status'] = 0;
                $status_execute['message'] = "EMC was not succesful be approved";
            }
            echo json_encode($status_execute);
        }
        else{ // if last approval and send to SAP

            // get approval code
            $level_up_approve = $this->model_empc->get_last_approval_status_from_setting();
            $approval_code_done = $this->model_empc->get_approval_code_done_from_setting();

            // update gl_id
            $this->model_empc->empc_h_code  = $empc_code;
            $this->model_empc->gl_id        = $empc_gl_id_edit;
            $this->model_empc->update_empc_h_gl_id();
            //-------------------

            // get EMPC Header
            $result = $this->model_empc->list_approval_by_empc_code($empc_code);

            foreach($result as $row){
                $sap_gl_account       = $row['gl_code'];
                $sap_cost_center      = $row['costcenter_code'];
                $sap_depot            = $row['plant_code'];
                $sap_empc_type        = $row['empc_type_code'];
                $sap_name_person      = $row['name'];
                $sap_department_name  = $row['requestor_depart_name'];
                $sap_empc_code        = $empc_code;
            }

            // get EMPC Detail
            $result = $this->model_empc->list_empc_d_by_code($empc_code);

            unset($sap_table_material);
            foreach($result as $row){
                $sap_table_material[] = array(
                  "MATNR" => $row['mat_id'],
                  "ERFMG" => $row['qty'],
                  "ERFME" => $row['d_uom'],
                  "SGTXT" => $row['empc_d_text1'],
                  "ITRPS" => $row['posnr'],
                );
            }

            // send to SAP
            $sap = $this->config->item('sap300');
            $result_sap = $sap->callFunction("ZFN_MMI_002",
            array(
              array("IMPORT","PI_WERKS",$sap_depot),
              array("IMPORT","PI_SAKNR",$sap_gl_account),
              array("IMPORT","PI_KOSTL",$sap_cost_center),
              array("IMPORT","PI_BWART",$sap_empc_type),
              array("IMPORT","PI_ERNAM",$sap_name_person),
              array("IMPORT","PI_DEPNM",$sap_department_name),
              array("IMPORT","PI_REQNR",$empc_code),
              array("TABLE","PT_DATASOURCE",$sap_table_material),
              array("EXPORT","PE_RESULT_MSG"),
              array("EXPORT","PE_RESULT_ERR"),
              array("EXPORT","PE_RESULT_ST"),
            ));

            unset($status_execute);
            if ($sap->getStatus() == SAPRFC_OK) {
              if($result_sap['PE_RESULT_ST'] == '1'){
                  $status_execute['status'] = 1;
                  $status_execute['message'] = "The EMC has been approved and created in SAP with
                  Reservation Number = ".$result_sap['PE_RESULT_MSG'];

                  // get this list first because we dont want send email to last person
                  $result2 = $this->model_empc->list_email_participant($empc_code);

                  // update approval status
                  $empc_status = $this->model_empc->get_status_from_approval_code($approval_code_done);
                  $result = $this->model_empc->update_status_empc_to_approval($empc_code,$approval_code_done,$empc_status);

                  $this->model_empc->empc_h_code = $empc_code;
                  $this->model_empc->empc_approval_code = $level_up_approve;
                  $this->model_empc->approval_date = $date;
                  $this->model_empc->approval_datetime = $datetime;
                  $this->model_empc->empc_h_approval_text1 = $remarks;
                  $this->model_empc->email_user = $session_data['z_tpimx_email'];
                  $result = $this->model_empc->insert_empc_h_approval($user_id);    // insert approval header

                  // update sap number to empc_h
                  $this->model_empc->update_sap_no_empc_h($empc_code,$result_sap['PE_RESULT_MSG']);
                  //-------------------------

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
                          "empc_status_name"   => $row['empc_status_name'],
                          "approval_datetime" => $datetime,
                      );
                  }

                  //---- get EMPC Detail
                  $result_empc_detail = $this->model_empc->list_empc_d_by_code($empc_code);
                  unset($table_material);
                  foreach($result_empc_detail as $row){
                      $table_material[] = array(
                        "MATNR"   => $row['mat_id'],
                        "MATDESC" => $row['mat_desc'],
                        "ERFMG"   => $row['qty'],
                        "ERFME"   => $row['d_uom'],
                        "POSNR"   => $row['posnr'],
                      );
                  }
                  //------------------

                  // send email to all participant
                  $this->load->library('MY_phpmailer');
                  foreach($result2 as $row){
                    $body = $this->my_phpmailer->email_body_approval_sap_empc($empc_code,$result_sap['PE_RESULT_MSG'],$remarks,$table_empc_h,$table_material);
					          $to = $row['email'];
                    $subject = "EMC Request Approval to Reservation was DONE";
                    $from_info = "EMC Euromega";
                    $altbody = "";
                    $cc = "";
                    $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);

                    $email_user_sent_already[$row['email']] = 1;
                  }

                  // send to last person should get notif after send to SAP
                  $result2 = $this->model_empc->list_email_last_person_get_notif_send_to_sap_emt001();

                  if($result){
                      foreach($result2 as $row){
                        if(!isset($email_user_sent_already[$row['email']])){
                            $body = $this->my_phpmailer->email_body_approval_sap_empc($empc_code,$result_sap['PE_RESULT_MSG'],$remarks,$table_empc_h,$table_material);
                            $to = $row['email'];
                            $subject = "EMC Request Approval to Reservation was DONE";
                            $from_info = "EMC Euromega";
                            $altbody = "";
                            $cc = "";
                            $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
                            $email_user_sent_already[$row['email']] = 1;
                        }
                      }
                  }
                  //-------------------------

                }
                else if($result_sap['PE_RESULT_ST'] == '0'){      // if not succesful created
                    $status_execute['status'] = 0;
                    $status_execute['message'] = $result_sap['PE_RESULT_ERR'];
                  //  $sap->logoff();
                }
              }
              else{           // if can't connect to SAP
                $status_execute['status'] = 0;
                $status_execute['message'] = "Can't Connect to SAP";
                //$sap->logoff();
              }
              //$sap->logoff();
              echo json_encode($status_execute);

            }
    }
    //--------------

    function empc_report(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user']['empc/empc_report'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('empc/view_empc_report');
        }
    }
    //-----------------

    function get_empc_report_detail(){
        $this->load->model('model_empc','',TRUE);

        $date_from  = $_POST['date_from'];
        $date_to    = $_POST['date_to'];

        $session_data = $this->session->userdata('z_tpimx_logged_in');

        // seperate only by depot
        if($session_data['z_tpimx_depart_code'] == 'DPT002'){
            $result_table = $this->model_empc->report_empc_h_by_department('',$date_from,$date_to,$session_data['z_tpimx_plant_code']);
        }
        else{
            $result_table = $this->model_empc->report_empc_show_all($date_from,$date_to);
        }

        // print output report
        if(!$result_table){
            $data['v_empc_report_generate'] = 0;
        }
        else{
            foreach($result_table as $row){
                $data['v_empc_report_generate'][] = array(
                      "empc_h_code"               => $row['empc_h_code'],
                      "empc_h_created_datetime"   => $row['empc_h_created_datetime'],
                      "name"                      => $row['name'],
                      "email"                     => $row['email'],
                      "depart_name"               => $row['requestor_depart_name'],
                      "empc_type_code"            => $row['empc_type_code'],
                      "gl_code"                   => $row['gl_code'],
                      "costcenter_code"           => $row['costcenter_code'],
                      "plant_code"                => $row['plant_code'],
                      "sap_no"                    => $row['sap_no'],
                      "empc_status_code"          => $row['empc_status_code'],
                      "empc_status_name"          => $row['empc_status_name'],
                      "customer_code"             => $row['customer_code'],
                      "empc_project_code"         => $row['empc_project_code'],
                      "empc_project_name"         => $row['empc_project_name'],
                      "employee_code"             => $row['employee_code'],
                      "employee_name"             => $row['employee_name'],
                      "customer_text"             => $row['customer_text'],
                );
            }
        }
        $this->load->view('empc/view_empc_report_generate',$data);

    }
    //------------

    function show_empc_detail_report(){
        $empc_code = $_POST['empc_code'];
        $this->load->model('model_empc','',TRUE);

        // get header
        $result = $this->model_empc->list_approval_by_empc_code($empc_code);
        if($result){
          foreach($result as $row){
             $data['v_list_empc_apprv_detail_h'][] = array(
                                       "empc_h_code" => $row['empc_h_code'],
                                       "empc_h_created_date" => $row['empc_h_created_date'],
                                       "empc_h_created_datetime" => $row['empc_h_created_datetime'],
                                       "empc_h_created_user" => $row['empc_h_created_user'],
                                       "empc_h_doc_date" => $row['empc_h_doc_date'],
                                       "empc_status" => $row['empc_status'],
                                       "empc_status_name" => $row['empc_status_name'],
                                       "empc_h_text1" => $row['empc_h_text1'],
                                       "empc_h_text2" => $row['empc_h_text2'],
                                       "empc_type_code" => $row['empc_type_code'],
                                       "empc_type_name" => $row['movt_type_name'],
                                       "depart_code" => $row['depart_code'],
                                       "depart_name" => $row['requestor_depart_name'],
                                       "empc_approval_code" => $row['empc_approval_code'],
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
                                       "canceled" => $row['canceled'],
                                       "attachment" => $row['attachment'],
                                       "customer_code" => $row['customer_code'],
                                       "employee_code"             => $row['employee_code'],
                                       "employee_name"             => $row['employee_name'],
                                       "customer_text"             => $row['customer_text'],
                                       );
          }
        }
        else $data['v_list_empc_apprv_detail_h'] = 0;

        // get detail
        if($result){
          $result1 = $this->model_empc->list_empc_d_by_code($empc_code);
          if($result1){
            foreach($result1 as $row){
               $data['v_list_empc_apprv_detail_d'][] = array(
                                         "empc_h_code"  => $row['empc_h_code'],
                                         "mat_id"       => $row['mat_id'],
                                         "qty"          => $row['qty'],
                                         "uom"          => $row['d_uom'],
                                         "posnr"        => $row['posnr'],
                                         "mat_desc"     => $row['mat_desc'],
                                         "mat_type"     => $row['mat_type'],
                                         "empc_d_text1" => $row['empc_d_text1'],
                                         );
            }
          }
          else $data['v_list_empc_apprv_detail_d'] = 0;
        }

        // get approval
        if($result){
            //$result3 = $this->model_empc->list_empc_h_approval($empc_code);
            $result3 = $this->model_empc->list_empc_h_approval_with_approval_list($empc_code);
            if(!$result3){
                $data['v_list_empc_apprv_detail_approval'] = 0;
            }
            else{
              foreach($result3 as $row){
                 $data['v_list_empc_apprv_detail_approval'][] = array(
                                           "empc_h_code"            => $row['empc_h_code'],
                                           "empc_approval_code"     => $row['empc_approval_code'],
                                           "approval_datetime"      => $row['approval_datetime'],
                                           "empc_h_approval_text1"  => $row['empc_h_approval_text1'],
                                           "email_approval"         => $row['email_approval'],
                                           "user_id"                => $row['user_id'],
                                           "name"                   => $row['name'],
                                           "empc_approval_name"     => $row['empc_approval_name'],
                                           );
              }
            }
        }
        else{
           $data['v_list_empc_apprv_detail_approval'] = 0;
        }

        $this->load->view('empc/view_empc_report_detail',$data);
    }
    //------------

    function tracking_report(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user']['empc/tracking_report'])){
            $this->load->view('view_home');
        }
        else{
          $this->load->view('empc/view_empc_report_track');
        }
    }
    //--------------

    function get_empc_report_track_generate(){
        $this->load->model('model_empc','',TRUE);

        $date_from  = $_POST['date_from'];
        $date_to    = $_POST['date_to'];

        $session_data = $this->session->userdata('z_tpimx_logged_in');

        if($session_data['z_tpimx_depart_code'] == 'DPT002'){
            $result = $this->model_empc->report_empc_tracking_by_plant_code($date_from,$date_to,$session_data['z_tpimx_plant_code']);
        }
        else{
            $result = $this->model_empc->report_empc_tracking($date_from,$date_to);
        }

        // result
        if(!$result){
            $data['v_list_empc_tracking'] = 0;
        }
        else{
            foreach($result as $row){
                $data['v_list_empc_tracking'][] = array(
                        "empc_h_code_empc"      => $row['empc_h_code_empc'],
                        "empc_h_created_date"  => $row['empc_h_created_date'],
                        "empc_type_code"       => $row['empc_type_code'],
                        "empc_type_name"       => $row['movt_type_name'],
                        "name"                => $row['name'],
                        "sap_no"              => $row['sap_no'],
                        "mat_id"              => $row['mat_id'],
                        "qty"                 => $row['empc_d_qty'],
                        "uom"                 => $row['empc_d_uom'],
                        "sap_matdoc"          => $row['sap_matdoc'],
                        "sap_matdoc_date"     => $row['sap_matdoc_date'],
                        "sap_matid"           => $row['sap_matid'],
                        "rsv_qty"             => $row['rsv_qty'],
                        "rsv_uom"             => $row['rsv_uom'],
                        "tbnum"               => $row['tbnum'],
                        "lgnum"               => $row['lgnum'],
                        "qdatu"               => $row['qdatu'],
                        "depart_name_user"    => $row['depart_name_user'],
                        "plant_name"          => $row['plant_name'],
                        "tanum"               => $row['tanum'],
                        "plant_code"          => $row['plant_code'],
                        "dmbtr"               => $row['dmbtr'],
                        "waers"               => $row['waers'],
                        "bwart_mat"           => $row['bwart_mat'],
                        "employee_code"       => $row['employee_code'],
                        "employee_name"       => $row['employee_name'],
                        "customer_text"       => $row['customer_text'],
                );
            }

        }
        $this->load->view('empc/view_empc_report_track_generate',$data);
    }
    //-----------

    function tracking_value_report(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user']['empc/tracking_value_report'])){
            $this->load->view('view_home');
        }
        else{
          $this->load->view('empc/view_empc_report_trackvalue');
        }
    }
    //------------------

    function get_empc_report_trackvalue_generate(){
        $this->load->model('model_empc','',TRUE);

        $date_from  = $_POST['date_from'];
        $date_to    = $_POST['date_to'];

        $result = $this->model_empc->report_empc_tracking($date_from,$date_to);

        if(!$result){
            $data['v_list_empc_tracking'] = 0;
        }
        else{
            foreach($result as $row){
                $data['v_list_empc_tracking'][] = array(
                        "empc_h_code_empc"    => $row['empc_h_code_empc'],
                        "empc_h_created_date" => $row['empc_h_created_date'],
                        "empc_type_code"      => $row['empc_type_code'],
                        "empc_type_name"      => $row['movt_type_name'],
                        "name"                => $row['name'],
                        "sap_no"              => $row['sap_no'],
                        "mat_id"              => $row['mat_id'],
                        "qty"                 => $row['empc_d_qty'],
                        "uom"                 => $row['empc_d_uom'],
                        "sap_matdoc"          => $row['sap_matdoc'],
                        "sap_matdoc_date"     => $row['sap_matdoc_date'],
                        "sap_matid"           => $row['sap_matid'],
                        "rsv_qty"             => $row['rsv_qty'],
                        "rsv_uom"             => $row['rsv_uom'],
                        "tbnum"               => $row['tbnum'],
                        "lgnum"               => $row['lgnum'],
                        "qdatu"               => $row['qdatu'],
                        "depart_name_user"    => $row['depart_name_user'],
                        "plant_name"          => $row['plant_name'],
                        "tanum"               => $row['tanum'],
                        "plant_code"          => $row['plant_code'],
                        "dmbtr"               => $row['dmbtr'],
                        "waers"               => $row['waers'],
                        "bwart_mat"           => $row['bwart_mat'],
                );
            }

        }
        $this->load->view('empc/view_empc_report_trackvalue_generate',$data);
    }
    //--------------

    function balance_report(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user']['empc/balance_report'])){
            $this->load->view('view_home');
        }
        else{
          $this->load->view('empc/view_empc_report_balance');
        }
    }
    //----------------

    function get_empc_report_balance_generate(){
        $this->load->model('model_empc','',TRUE);

        $date_from  = $_POST['date_from'];
        $date_to    = $_POST['date_to'];

        $session_data = $this->session->userdata('z_tpimx_logged_in');

        // seperate only by depot
        if($session_data['z_tpimx_depart_code'] == 'DPT002'){
            $result = $this->model_empc->report_empc_balance($date_from,$date_to,$session_data['z_tpimx_depart_code'],$session_data['z_tpimx_plant_code']);
        }
        else{
            $result = $this->model_empc->report_empc_balance($date_from,$date_to,"","");
        }

        // result
        if(!$result){
            $data['v_list_empc_balance'] = 0;
        }
        else{
            foreach($result as $row){
                $data['v_list_empc_balance'][] = array(
                        "empc_h_code"          => $row['empc_code'],
                        "empc_h_created_date"  => $row['empc_h_created_date'],
                        "empc_type_name"       => $row['movt_type_name'],
                        "name"                => $row['name'],
                        "mat_id"              => $row['mat_id'],
                        "qty"                 => $row['qty'],
                        "uom"                 => $row['uom'],
                        "plant_name"          => $row['plant_name'],
                        "plant_code"          => $row['plant_code'],
                        "sap_no"              => $row['sap_no'],
                        "sap_matid"           => $row['sap_matid'],
                        "gi_qty"              => $row['gi_qty'],
                        "gi_uom"              => $row['gi_uom'],
                        "balance"             => $row['balance'],
                        "depart_code"         => $row['depart_code'],
                        "depart_name"         => $row['depart_name'],
                        "posnr"               => $row['posnr'],
                        "empcps"               => $row['empcps'],
                );
            }

        }
        $this->load->view('empc/view_empc_report_balance_generate',$data);
    }
    //------------


}
