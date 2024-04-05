<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verify_Login extends CI_Controller{

  function __construct(){
    parent::__construct();
       $this->load->model('model_login','',TRUE);
       $this->load->model('model_navigation','',TRUE);
       $this->load->model('model_zlog','',TRUE);
  }

    function index(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'username','trim|required');
        $this->form_validation->set_rules('password', 'password','trim|required|callback_check_database');

        if($this->form_validation->run() == FALSE){
           //Field validation failed.  User redirected to login page
           $this->load->view('view_login');
           //header("Location:".base_url());
        }
        else{
           //Go to private area
           redirect('home', 'refresh');
        }
    }


function check_database()
{
   //Field validation succeeded.  Validate against database
   $username = $this->input->post('username');
   $password = $this->input->post('password');

   //query the database
   $result = $this->model_login->login($username, $password);

   if($result)
   {

      // check active
      foreach($result as $row)
      {
        $user_active = $this->model_login->check_user_active($row->user_id);
        if($user_active == 'N'){
            $this->form_validation->set_message('check_database', 'Your user was blocked!');
            return false;
        }
      }

      // if everything is oke put on the session
       $sess_array = array();

       foreach($result as $row)
       {
         //$result1 = $this->model_login->get_user_depart_code($row->user_id);
         //foreach ($result1 as $row1) {
         //  $depart_name = $row1->depart_name;
         //}

         /*$sess_array = array(
           'z_tpimx_user_id' => $row->user_id,
           'z_tpimx_email' => $row->email,
           'z_tpimx_depart_code' => $row->depart_code,
           'z_tpimx_depart_name' => $depart_name,
           'z_tpimx_name' => $row->name,
           'z_tpimx_user_level' => $row->user_level,
           'z_tpimx_plant_code' => $row->plant_code,
         );*/
         $sess_array = array(
            'z_tpimx_user_id' => $row->user_id,
            'z_tpimx_name' => $row->name,
            'z_tpimx_user_level' => $row->user_level,
            'z_tpimx_plant_code' => $row->plant_code,
            'z_tpimx_change_pass' => $row->change_pass,
            'z_tpimx_userid_1' => $row->userid_1,
          );

         $this->session->set_userdata('z_tpimx_logged_in', $sess_array);
         $_SESSION['refresh_home'] = 0;

         // session for user permissions
         unset($_SESSION['user_permis']);
         $session_data = $this->session->userdata('z_tpimx_logged_in');
         $userid = $session_data['z_tpimx_user_id'];
         $acl_user_permis = $this->model_navigation->get_acl_user_permis($userid);
         if(count($acl_user_permis) > 0){
            foreach($acl_user_permis as $row){
                $_SESSION['user_permis'][$row["id_permis"]]="1";
            }

         }
         //--

         $this->model_zlog->insert("Login"); // insert log
       }

       return TRUE;
   }
   else
   {
      $this->form_validation->set_message('check_database', 'Invalid username or password!');
      return false;
   }
   $this->db->close();

}

}

?>
