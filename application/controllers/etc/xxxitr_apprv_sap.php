<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class itr_apprv_sap extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

  function index(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['itr_apprv_sap'])){
      $this->load->view('view_home');
    }
    else{
      $this->load->model('model_itr','',TRUE);

      $session_data = $this->session->userdata('z_tpimx_logged_in');

      $result = $this->model_itr->get_approval_level_user($session_data['z_tpimx_user_id']);
      unset($itr_approval_code_user);
      foreach($result as $row){
          $itr_approval_code_user[] = $row['itr_approval_code'];
      }

      $result = $this->model_itr->list_approval_sap($session_data['z_tpimx_user_id'],$itr_approval_code_user);
      if($result){
        foreach($result as $row){
           $data['v_list_itr_apprv_sap'][] = array(
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
                                     );
        }
      }
      else $data['v_list_itr_apprv_sap'] = 0;

      $this->load->view('view_itr_apprv_sap',$data);
    }

  }
  //----------------
  
}

?>
