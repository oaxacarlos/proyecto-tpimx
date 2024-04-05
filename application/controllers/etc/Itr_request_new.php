<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itr_request_new extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['itr_request_new'])){
        $this->load->view('view_home');
      }
      else{
        $this->load->model('model_itr','',TRUE);

        // load material
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
        $result = $this->model_itr->list_all_itr_type();
        foreach($result as $row){
           $data['v_list_itr_type'][] = array(
                                     "itr_type_code" => $row['itr_type_code'],
                                     "itr_type_name" => $row['itr_type_name'],
                                     "itr_type_text" => $row['itr_type_text'],
                                     "active" => $row['active']
                                     );
        }

        // load uom
        $result = $this->model_itr->list_all_uom();
        foreach($result as $row){
           $data['v_list_uom'][] = array(
                                     "uom_code" => $row['uom_code'],
                                     "uom_name" => $row['uom_name'],
                                     "order"    => $row['order']
                                     );
        }


        
      }
  }
  //--------------

  function show_cust_sap(){
      $this->load->model('model_itr','',TRUE);

      // load customer
      $result = $this->model_itr->list_cust_sap();
      foreach($result as $row){
         $data['v_list_cust_sap'][] = array(
                                   "custno" => $row['custno'],
                                   "custname" => $row['custname'],
								   "region" => $row['distrikname'],
                                   );
      }
      $this->load->view('itr/view_itr_request_customer_sap',$data);
  }

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
      $this->load->view('itr/view_itr_request_gl',$data);
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
      $this->load->view('itr/view_itr_request_costcenter',$data);
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
      $this->load->view('itr/view_itr_request_plant',$data);
  }
  //-----------------

  function show_project(){
      $this->load->model('model_itr','',TRUE);
      // load costcenter
      $result = $this->model_itr->list_all_project();
      foreach($result as $row){
         $data['v_list_project'][] = array(
                                   "itr_project_code" => $row['itr_project_code'],
                                   "itr_project_name" => $row['itr_project_name'],
                                   "itr_project_text1" => $row['itr_project_text1'],
                                   "active" => $row['active']
                                   );
      }
      $this->load->view('itr/view_itr_request_project',$data);
  }
  //-----------------

  function itr_request_new_add(){
      $plant      = $_POST['plant'];
      $itr_type   = $_POST['itr_type'];
      $gl         = $_POST['gl'];
      $costcenter = $_POST['costcenter'];
      $remarks    = str_replace("'","",$_POST['remarks']);
      $customer   = $_POST['customer'];
      $project    = $_POST['project'];
      $itr_attachment    = $_POST['itr_attachment'];
      $matid      = json_decode(stripslashes($_POST['matid']));
      $qty        = json_decode(stripslashes($_POST['qty']));
      $uom        = json_decode(stripslashes($_POST['uom']));
      $text1      = json_decode(stripslashes($_POST['text1']));
      $date       = date('Y-m-d');
      $datetime   = date('Y-m-d H:i:s');
      $datecode   = date("Ymd");

      if($itr_type == "201") $prefix_code = "ITR";
      else if($itr_type == "202") $prefix_code = "RTR";

      $session_data = $this->session->userdata('z_tpimx_logged_in');

      // load CI function
      $this->load->model('model_itr','',TRUE);
      //-------------

      // get approval level 1
      $result = $this->model_itr->get_approval_level1();
      foreach($result as $row){
          $itr_approval_code_lvl1 = $row->itr_approval_code;
      }

      // insert table itr_h
      $this->model_itr->prefix_code = $prefix_code;
      $this->model_itr->itr_h_created_user = $session_data['z_tpimx_user_id'];
      $this->model_itr->itr_status = "ITRST001";
      $this->model_itr->itr_h_text1 = $remarks;
      $this->model_itr->itr_h_text2 = "";
      $this->model_itr->itr_h_text3 = "";
      $this->model_itr->itr_type_code = $itr_type;
      $this->model_itr->depart_code = $session_data['z_tpimx_depart_code'];
      $this->model_itr->itr_approval_code = $itr_approval_code_lvl1;
      $this->model_itr->email_user = $session_data['z_tpimx_email'];
      $this->model_itr->gl_id = $gl;
      $this->model_itr->costcenter_code = $costcenter;
      $this->model_itr->plant_code = $plant;
      $this->model_itr->customer = str_replace("'","",$customer);
      $this->model_itr->project = $project;
      $this->model_itr->attachment = $itr_attachment;
      $new_code = $this->model_itr->call_store_procedure_new_itr();

      // insert detail itr_d
      $this->model_itr->itr_h_code = $new_code;
      unset($table_itr_d);
      for($i=0;$i<count($matid);$i++){
          $table_itr_d[] = array(
              "matid"       => $matid[$i],
              "qty"         => $qty[$i],
              "uom"         => $uom[$i],
              "itr_d_text1" => str_replace("'","",$text1[$i]),
              "posnr"       => $i+1,
          );
      }
      $result1 = $this->model_itr->insert_itr_d_version2($table_itr_d);

      //-----------------------

      // get his person approval
      $this->model_itr->depart_code = $session_data['z_tpimx_depart_code'];
      $this->model_itr->itr_approval_code = $itr_approval_code_lvl1;
      $result2 = $this->model_itr->get_approval_person();

      // get the list ITR Header and Detail for sending email
      //--- get ITR Header
      $result_itr_header = $this->model_itr->list_approval_by_itr_code($new_code);
      unset($table_itr_h);
      foreach($result_itr_header as $row){
          $table_itr_h = array(
              "created_datetime"  => $row['itr_h_created_datetime'],
              "plant_code"        => $row['plant_code'],
              "plant_name"        => $row['plant_name'],
              "name"              => $row['name'],
              "depart_name"       => $row['requestor_depart_name'],
              "itr_status_name"   => $row['itr_status_name'],
          );
      }

      //---- get ITR Detail
      $result_itr_detail = $this->model_itr->list_itr_d_by_code($new_code);
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
      $this->load->library('MY_phpmailer');
      foreach($result2 as $row){
        $body = $this->my_phpmailer->email_body_new_itr($new_code,$remarks,$table_itr_h,$table_material);
        $to = $row['email'];
        $subject = "ITR Request New";
        $from_info = "ITR Euromega";
        $altbody = "";
        $cc = "";
        $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
      }
      //--------------------

      // send email cross department
      $this->model_itr->depart_code = $session_data['z_tpimx_depart_code'];
      $this->model_itr->itr_approval_code = $itr_approval_code_lvl1;
      $result3 = $this->model_itr->get_approval_person_cross();
      $this->load->library('my_phpmailer');
      foreach($result3 as $row){
        $body = $this->my_phpmailer->email_body_new_itr($new_code,$remarks,$table_itr_h,$table_material);
        $to = $row['email'];
        $subject = "ITR Request New";
        $from_info = "ITR Euromega";
        $altbody = "";
        $cc = "";
        $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
      }
      //-------------------------

      if($result) echo $new_code;
      else echo "nosuccess";
  }
  //-----------

  function itr_request_new_uploadfile(){
      //$this->load->model('model_itr','',TRUE);

      $src = $_FILES['file']['tmp_name'];
      //$itr_code = $_POST['itr_code'];

      $target_file = $this->config->item('upload_path_itr');

      $temp = explode(".", $_FILES["file"]["name"]);
      $newfilename = round(microtime(true)) . '.' . end($temp);

      $targ = $target_file.$newfilename;
      $result["status"] = move_uploaded_file($src, $targ);

      if($result["status"] == 1) $result["filename"] = $newfilename;

      echo json_encode($result);

      //$this->model_itr->itr_h_code = $itr_code;
      //$this->model_itr->update_itr_h_attachment($newfilename); // update attachment file
  }
  //---------------------

  function itr_request_new_list_by_user(){
      $today = date("Y-m-d");
      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user_id = $session_data['z_tpimx_user_id'];

      $this->load->model('model_itr','',TRUE);
      $result = $this->model_itr->list_itr_complete_by_date_user($user_id,$today);

      if(!$result){
          $data['v_list_itr_new_complete'] = 0;
      }
      else{
          foreach($result as $row){
            $data['v_list_itr_new_complete'][] = array(
                    "itr_h_code"          => $row['itr_h_code'],
                    "itr_h_created_date"      => $row['itr_h_created_date'],
                    "itr_h_created_datetime"  => $row['itr_h_created_datetime'],
                    "count_detail"            => $row['count_detail'],
                    "attachment"              => $row['attachment'],
                    "email_user"              => $row['email_user'],
                    "customer_text"           => $row['customer_text'],
                    "itr_status"              => $row['itr_status'],
                    "itr_status_name"         => $row['itr_status_name'],
            );
          }
      }



      $this->load->view('itr/view_itr_request_new_list_complete',$data);
  }
  //------------

}

?>
