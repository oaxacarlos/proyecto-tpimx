<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_useradd extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();
  }
  //-------------
  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('admin_folder').'admin_useradd'])){
        $this->load->view('view_home');
      }
      else{
        $this->load->model('model_admin','',TRUE);

        /*$result1 = $this->model_admin->list_so();
        foreach($result1 as $row){
          $data['v_list_so'][] = array(
                                    "No" => $row['No'],
                                    "pdf" => $row['pdf'],
                                    );
        }*/

        // get department list
        $result = $this->model_admin->list_department();

        foreach($result as $row){
          $data['v_list_department'][] = array(
                                    "depart_code" => $row['depart_code'],
                                    "depart_name" => $row['depart_name'],
                                    "depart_text" => $row['depart_text']
                                    );
        }
        //----------------

        // get level list
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user_level   = $session_data['z_tpimx_user_level'];

        $result = $this->model_admin->list_userlevel_by_level($user_level);
        foreach($result as $row){
          $data['v_list_level'][] = array(
                                    "id_user_level"   => $row['id_user_level'],
                                    "user_level_name" => $row['user_level_name'],
                                    );
        }
        //-------------

        // get plant list
        /*$result = $this->model_admin->list_plant();
        foreach($result as $row){
          $data['v_list_plant'][] = array(
                                    "plant_code" => $row['plant_code'],
                                    "plant_name" => $row['plant_name'],
                                    "plant_info" => $row['plant_info']
                                    );
        }*/

        $this->load->view('admin/view_admin_useradd',$data);
      }
  }
  //-------------------
  function user_add(){
      $name   = $_POST['name'];
      $email  = $_POST['email'];
      $password = $_POST['password'];
      $depart = $_POST['depart'];
      $userlevel = $_POST['userlevel'];
      //$plant_code = $_POST['plant'];
      $change_pass = $_POST["change_pass"];

      // check email
      $this->load->model('model_admin','',TRUE);
      $result = $this->model_admin->check_email($email);

      if($change_pass == "true") $change_pass = "1";
      else $change_pass = "0";

      if($result){
          $result1 = $this->model_admin->user_add($email,$name,md5($password),$depart,$userlevel,'', $change_pass);
          if($result1) echo "success";
          else echo "nosuccess";
      }
      else echo "Email already exist";
  }

}


?>
