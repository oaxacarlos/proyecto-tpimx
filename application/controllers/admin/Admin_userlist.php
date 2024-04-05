<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_userlist extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();
  }
  //-------------
  function index(){
     $this->load->view('templates/navigation');

     if(!isset($_SESSION['menus_list_user'][$this->config->item('admin_folder').'admin_userlist'])){
       $this->load->view('view_home');
     }
     else{

       $this->load->model('model_admin','',TRUE);

       $session_data = $this->session->userdata('z_tpimx_logged_in');
       $user_level   = $session_data['z_tpimx_user_level'];

       //$result = $this->model_admin->list_all_user();
       $result = $this->model_admin->list_user_by_userlevel($user_level);

       foreach($result as $row){
          $data['v_list_user'][] = array(
                                    "name" => $row['name'],
                                    "email" => $row['email'],
                                    "depart_name" => $row['depart_name'],
                                    "user_id" => $row['user_id'],
                                    "active" => $row['active'],
                                    "depart_code" => $row['depart_code'],
                                    "plant_code" => $row['plant_code'],
                                    );
       }

       $result = $this->model_admin->list_department();
       foreach($result as $row){
         $data['v_list_department'][] = array(
                                   "depart_code" => $row['depart_code'],
                                   "depart_name" => $row['depart_name'],
                                   "depart_text" => $row['depart_text']
                                   );
       }

       $this->load->view('admin/view_admin_userlist',$data);
     }

  }
  //--------------

  function edit_user(){
      $name   = $_POST['name'];
      $email  = $_POST['email'];
      $depart = $_POST['depart'];
      $userid = $_POST['userid'];
      $plant  = $_POST['plant'];

      $this->load->model('model_admin','',TRUE);
      $result = $this->model_admin->edit_user($name,$email,$depart,$userid,$plant);

      if($result) echo "1";
      else echo "0";
  }
  //-----------------

  function edit_password(){
      $userid = $_POST['userid'];
      $password = $_POST['password'];

      $this->load->model('model_admin','',TRUE);
      $result = $this->model_admin->edit_password($userid,md5($password));

      if($result) echo "1";
      else echo "0";
  }
  //-----------------

  function edit_active(){
      $userid = $_POST['userid'];
      $status = $_POST['status'];

      if($status == "Y") $status = "N";
      else if($status == "N") $status = "Y";

      $this->load->model('model_admin','',TRUE);
      $result = $this->model_admin->edit_active($userid,$status);

      if($result) echo "1";
      else echo "0";
  }
  //---------------
  function edit_password_navigation(){
      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $userid = $session_data['z_tpimx_user_id'];
      $password = $_POST['password'];

      $this->load->model('model_admin','',TRUE);

      // check password, should not same with before
      $result_check = $this->model_admin->check_password_user_if_same_with_previous($userid, md5($password));

      if($result_check == 0) $result = $this->model_admin->edit_password($userid,md5($password));
      else $result = 0;

      if($result) echo "1";
      else echo "0";
  }
  //-----------------

}
