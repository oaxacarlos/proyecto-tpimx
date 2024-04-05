<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_usermenu extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();
  }

  function index(){
     $this->load->view('templates/navigation');

     if(!isset($_SESSION['menus_list_user'][$this->config->item('admin_folder').'admin_usermenu'])){
          $this->load->view('view_home');
     }
     else{
         $this->load->model('model_admin','',TRUE);
         $result = $this->model_admin->list_all_user();
         foreach($result as $row){
            $data['v_list_user'][] = array(
                                      "name" => $row['name'],
                                      "email" => $row['email'],
                                      "depart_name" => $row['depart_name'],
                                      "user_id" => $row['user_id']
                                      );
         }
         $this->load->view('admin/view_admin_usermenu',$data);
     }
  }
  //------------------
  function modal_menu(){
      $userid = $_POST['user'];

      $this->load->model('model_admin','',TRUE);
      $result = $this->model_admin->list_menu_by_user($userid);

      foreach($result as $row){
         $data['v_list_menu_by_user'][] = array(
                                   "menu1_code" => $row['menu1_code'],
                                   "menu1_name" => $row['menu1_name'],
                                   "menu2_code" => $row['menu2_code'],
                                   "menu2_name" => $row['menu2_name'],
                                   "menu3_code" => $row['menu3_code'],
                                   "menu3_name" => $row['menu3_name'],
                                   "menu3_initial" => $row['menu3_initial'],
                                   "menu3_link" => $row['menu3_link'],
                                   "menu_status" => $row['menu_status'],
                                   );
      }

      $data['userid'] = $userid;

      $this->load->view('admin/view_admin_usermenu_select',$data);
  }
  //-------------

  function assign_menu(){
      $user = $_POST['user'];
      $checked = json_decode(stripslashes($_POST['checked']));

      $this->load->model('model_admin','',TRUE);

      // delete all menu in this user
      $result = $this->model_admin->delete_acl_user_menu_by_user($user);

      // insert menu
      for($i=0;$i<count($checked);$i++){
            $result1 = $this->model_admin->insert_acl_user_menu($user,$checked[$i]);
      }

      if($result) echo "1";
      else echo "0";
  }
  //---------------------

  function show_user(){
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
                                   "user_id" => $row['user_id']
                                   );
      }

      $this->load->view('admin/view_admin_usermenu_user',$data);
  }
  //-----------------

}

?>
