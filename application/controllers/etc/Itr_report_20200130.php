<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itr_report extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['itr_report'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('itr/view_itr_report');
      }
  }
  //------------

  function get_itr_report_detail(){
      $this->load->model('model_itr','',TRUE);

      $date_from  = $_POST['date_from'];
      $date_to    = $_POST['date_to'];

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $output_text = "";

      $last_approval_code = $this->model_itr->get_last_approval_status_from_setting();      // last approval code
      $last_approval_userid_temp = $this->model_itr->get_last_approval_userid_from_setting(); // last user id
      $last_approval_userid = explode(";",$last_approval_userid_temp);

      $check = 0;
      foreach($last_approval_userid as $row){
          if($row == $session_data['z_tpimx_user_id']){
            $check = 1; break;
          }
      }

      if($check == 1){ $report_show_all = 1; }    // if last person show all report
      else $report_show_all = 0;                  // if not last person

      // check this user can see all report
      $user_can_see_all = $this->model_itr->get_user_can_see_all_depart_from_setting();
      $user_can_see_all = explode(";",$user_can_see_all);
      if (in_array($session_data['z_tpimx_user_id'], $user_can_see_all)) $is_user_can_see_all = 1;
      else $is_user_can_see_all = 0;
      //----------------------------

      if($report_show_all or $is_user_can_see_all){       // show all report
          $result_table = $this->model_itr->report_itr_show_all($date_from,$date_to);
      }
      else if($session_data['z_tpimx_depart_code'] == 'DPT002'){
          if($session_data['z_tpimx_plant_code'] == ''){
              $result_table = $this->model_itr->report_itr_h_by_department($session_data['z_tpimx_depart_code'],$date_from,$date_to,'');
          }
          else{
              $result_table = $this->model_itr->report_itr_h_by_department('',$date_from,$date_to,$session_data['z_tpimx_plant_code']);
          }
      }
      else{
          $result = $this->model_itr->get_approval_level_user($session_data['z_tpimx_user_id']);

          if($result){ $is_this_user_approve = 1; } // check if the user can approve
          else{ $is_this_user_approve = 0; }

          if($is_this_user_approve){      // if user can approve, show his request and his subordinate
              $result_table = $this->model_itr->report_itr_h_by_department($session_data['z_tpimx_depart_code'],$date_from,$date_to,'');
          }
          else{
              $user_can_see_only_his_depart = $this->model_itr->get_user_can_see_only_his_depart_from_setting();
              $user_can_see_only_his_depart = explode(";",$user_can_see_only_his_depart);

              if (in_array($session_data['z_tpimx_user_id'], $user_can_see_only_his_depart)) $is_user_can_see_only_his_depart = 1;
              else $is_user_can_see_only_his_depart = 0;

              if($is_user_can_see_only_his_depart){ // the user only can see his department
                  $result_table = $this->model_itr->report_itr_h_by_department($session_data['z_tpimx_depart_code'],$date_from,$date_to,'');
              }
              else{  // the user only can see this ITR
                  $result_table = $this->model_itr->report_itr_h_by_user($session_data['z_tpimx_user_id'],$date_from,$date_to);
              }
          }
      }

      // print output report
      if(!$result_table){
          $data['v_itr_report_generate'] = 0;
      }
      else{
          foreach($result_table as $row){
              $data['v_itr_report_generate'][] = array(
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
                    "itr_project_code"         => $row['itr_project_code'],
                    "itr_project_name"         => $row['itr_project_name'],
              );
          }
      }
      $this->load->view('itr/view_itr_report_generate',$data);
  }
  //--------------------

  function show_itr_detail(){
      $itr_code = $_POST['itr_code'];
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
                                     "itr_h_text2" => $row['itr_h_text2'],
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
                                     "canceled" => $row['canceled'],
                                     "attachment" => $row['attachment'],
                                     "customer_text" => $row['customer_text'],
                                     "itr_project_code"         => $row['itr_project_code'],
                                     "itr_project_name"         => $row['itr_project_name'],
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

      $this->load->view('itr/view_itr_report_detail',$data);
  }
  //------------

  function track(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['itr_report/track'])){
          $this->load->view('view_home');
      }
      else{
        $this->load->view('itr/view_itr_report_track');
      }
  }
  //----------------

  function get_itr_report_track_generate(){
      $this->load->model('model_itr','',TRUE);

      $date_from  = $_POST['date_from'];
      $date_to    = $_POST['date_to'];

      $session_data = $this->session->userdata('z_tpimx_logged_in');

      // check this user can see all report
      $user_can_see_all = $this->model_itr->get_user_can_see_all_depart_from_setting();
      $user_can_see_all = explode(";",$user_can_see_all);
      if (in_array($session_data['z_tpimx_user_id'], $user_can_see_all)) $is_user_can_see_all = 1;
      else $is_user_can_see_all = 0;
      //----------------------------

      if($is_user_can_see_all){   // user can see all the report
        $result = $this->model_itr->report_itr_tracking($date_from,$date_to);
      }
      else if($session_data['z_tpimx_depart_code'] == 'DPT002'){   // if the user is logistic department
          if($session_data['z_tpimx_plant_code'] == ''){   // if no depot, show all
              $result = $this->model_itr->report_itr_tracking($date_from,$date_to);
          }
          else{   // only can see his depot
              $result = $this->model_itr->report_itr_tracking_by_plant_code($date_from,$date_to,$session_data['z_tpimx_plant_code']);
          }
      }
      else{  // if the user is not logistic department
          $result = $this->model_itr->report_itr_tracking_by_depart_code($date_from,$date_to,$session_data['z_tpimx_depart_code']);
      }

      // result
      if(!$result){
          $data['v_list_itr_tracking'] = 0;
      }
      else{
          foreach($result as $row){
              $data['v_list_itr_tracking'][] = array(
                      "itr_h_code_itr"      => $row['itr_h_code_itr'],
                      "itr_h_created_date"  => $row['itr_h_created_date'],
                      "itr_type_code"       => $row['itr_type_code'],
                      "name"                => $row['name'],
                      "sap_no"              => $row['sap_no'],
                      "itr_project_code"    => $row['itr_project_code'],
                      "itr_project_name"    => $row['itr_project_name'],
                      "mat_id"              => $row['mat_id'],
                      "qty"                 => $row['itr_d_qty'],
                      "uom"                 => $row['itr_d_uom'],
                      "sap_matdoc"          => $row['sap_matdoc'],
                      "sap_matdoc_date"     => $row['sap_matdoc_date'],
                      "sap_matid"           => $row['sap_matid'],
                      "rsv_qty"             => $row['rsv_qty'],
                      "rsv_uom"             => $row['rsv_uom'],
                      "tbnum"               => $row['tbnum'],
                      "lgnum"               => $row['lgnum'],
                      "qdatu"               => $row['qdatu'],
                      "depart_name_user"    => $row['depart_name_user'],
                      "itr_type_name"       => $row['itr_type_name'],
                      "plant_name"          => $row['plant_name'],
                      "tanum"               => $row['tanum'],
                      "plant_code"          => $row['plant_code'],
                      "dmbtr"               => $row['dmbtr'],
                      "waers"               => $row['waers'],
                      "bwart_mat"           => $row['bwart_mat'],
              );
          }

      }
      $this->load->view('itr/view_itr_report_track_generate',$data);
  }
  //--------------

  function trackvalue(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['itr_report/trackvalue'])){
          $this->load->view('view_home');
      }
      else{
        $this->load->view('itr/view_itr_report_trackvalue');
      }
  }
  //----------------

  function get_itr_report_trackvalue_generate(){
      $this->load->model('model_itr','',TRUE);

      $date_from  = $_POST['date_from'];
      $date_to    = $_POST['date_to'];

      $result = $this->model_itr->report_itr_tracking($date_from,$date_to);

      if(!$result){
          $data['v_list_itr_tracking'] = 0;
      }
      else{
          foreach($result as $row){
              $data['v_list_itr_tracking'][] = array(
                      "itr_h_code_itr"      => $row['itr_h_code_itr'],
                      "itr_h_created_date"  => $row['itr_h_created_date'],
                      "itr_type_code"       => $row['itr_type_code'],
                      "name"                => $row['name'],
                      "sap_no"              => $row['sap_no'],
                      "itr_project_code"    => $row['itr_project_code'],
                      "itr_project_name"    => $row['itr_project_name'],
                      "mat_id"              => $row['mat_id'],
                      "qty"                 => $row['itr_d_qty'],
                      "uom"                 => $row['itr_d_uom'],
                      "sap_matdoc"          => $row['sap_matdoc'],
                      "sap_matdoc_date"     => $row['sap_matdoc_date'],
                      "sap_matid"           => $row['sap_matid'],
                      "rsv_qty"             => $row['rsv_qty'],
                      "rsv_uom"             => $row['rsv_uom'],
                      "tbnum"               => $row['tbnum'],
                      "lgnum"               => $row['lgnum'],
                      "qdatu"               => $row['qdatu'],
                      "depart_name_user"    => $row['depart_name_user'],
                      "itr_type_name"       => $row['itr_type_name'],
                      "plant_name"          => $row['plant_name'],
                      "tanum"               => $row['tanum'],
                      "plant_code"          => $row['plant_code'],
                      "dmbtr"               => $row['dmbtr'],
                      "waers"               => $row['waers'],
                      "bwart_mat"           => $row['bwart_mat'],
              );
          }

      }
      $this->load->view('itr/view_itr_report_trackvalue_generate',$data);
  }
  //--------------

  function balance(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['itr_report/balance'])){
          $this->load->view('view_home');
      }
      else{
        $this->load->view('itr/view_itr_report_balance');
      }
  }
  //----------------

  function get_itr_report_balance_generate(){
      $this->load->model('model_itr','',TRUE);

      $date_from  = $_POST['date_from'];
      $date_to    = $_POST['date_to'];

      $session_data = $this->session->userdata('z_tpimx_logged_in');

      // check this user can see all report
      $user_can_see_all = $this->model_itr->get_user_can_see_all_depart_from_setting();
      $user_can_see_all = explode(";",$user_can_see_all);
      if (in_array($session_data['z_tpimx_user_id'], $user_can_see_all)) $is_user_can_see_all = 1;
      else $is_user_can_see_all = 0;
      //----------------------------

      if($is_user_can_see_all){   // user can see all the report
        $result = $this->model_itr->report_itr_balance($date_from,$date_to,"","");
      }
      else if($session_data['z_tpimx_depart_code'] == 'DPT002'){   // if the user is logistic department
          if($session_data['z_tpimx_plant_code'] == ''){     // if no depot, show all
              $result = $this->model_itr->report_itr_balance($date_from,$date_to,"","");
          }
          else{ // only can see his depot
            $result = $this->model_itr->report_itr_balance($date_from,$date_to,$session_data['z_tpimx_depart_code'],$session_data['z_tpimx_plant_code']);
          }
      }
      else{  // if the user is not logistic department
          $result = $this->model_itr->report_itr_balance($date_from,$date_to,$session_data['z_tpimx_depart_code'],"");
      }

      // result
      if(!$result){
          $data['v_list_itr_balance'] = 0;
      }
      else{
          foreach($result as $row){
              $data['v_list_itr_balance'][] = array(
                      "itr_h_code"          => $row['itr_code'],
                      "itr_h_created_date"  => $row['itr_h_created_date'],
                      "itr_type_name"       => $row['itr_type_name'],
                      "itr_project_name"    => $row['itr_project_name'],
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
                      "itrps"               => $row['itrps'],
              );
          }

      }
      $this->load->view('itr/view_itr_report_balance_generate',$data);
  }
  //--------------

}
?>
