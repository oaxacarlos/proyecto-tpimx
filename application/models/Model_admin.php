<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_admin extends CI_Model{

    function list_all_user(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM user u
                 inner join mst_department dpt on(u.depart_code=dpt.depart_code)");
        return $query->result_array();
    }
    //-------------
    function list_menu_by_user($user){
        $db = $this->load->database('default', true);
        $query = $db->query("select menu1_code,menu1_name,menu2_code,menu2_name,
                  a.menu3_code,menu3_name,menu3_initial,menu3_link,
                  if(login_user='".$user."',1,0) as menu_status
                  from (
                  SELECT m1.menu1_code,m1.menu1_name,m2.menu2_code,m2.menu2_name,
                  m3.menu3_code,m3.menu3_name,m3.menu3_initial,m3.menu3_link
                  FROM acl_menu3 m3
                  left join acl_menu2 m2 on(m3.menu2_code=m2.menu2_code)
                  left join acl_menu1 m1 on(m2.menu1_code=m1.menu1_code) ) as a
                  left join (select * from acl_user_menu where login_user='".$user."') as b
                  on(a.menu3_code=b.menu3_code) order by menu1_code,menu2_code,menu3_code;");
        return $query->result_array();
    }
    //---------------

    function delete_acl_user_menu_by_user($user){
        $db = $this->load->database('default', true);
        $query = $db->query( "delete from acl_user_menu where login_user='".$user."'");
        return true;
    }
    //-----------------

    function insert_acl_user_menu($user,$menu3_code){
       $db = $this->load->database('default', true);
       $data = array(
 				'login_user' => $user ,
 				'menu3_code' => $menu3_code ,
 		 		);
 				$this->db->insert('acl_user_menu', $data);
 				return true;
    }
    //----------------

    function list_department(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM mst_department m");
        return $query->result_array();
    }
    //--------------

    function edit_user($name,$email,$depart_code,$userid,$plant_code){
        $db = $this->load->database('default', true);
        /*$query = $db->query("update user set
                  name = '".$name."',email='".$email."',depart_code='".$depart_code."',plant_code='".$plant_code."'
                  where user_id='".$userid."';");*/

        $data = array(
            "name" => $name,
            "email" => $email,
            "depart_code" => $depart_code,
            "plant_code" => $plant_code,
        );

        $db->where('user_id', $userid);
        $db->update("user", $data);

        return true;
    }
    //------------------

    function edit_password($userid,$password){
        $db = $this->load->database('default', true);
        $query = $db->query("update user set
                  password = '".$password."', change_pass='0' where user_id='".$userid."';");
        return true;
    }
    //------------------

    function edit_active($userid,$status){
        $db = $this->load->database('default', true);
        $query = $db->query("update user set
                  active = '".$status."'
                  where user_id='".$userid."';");
        return true;
    }
    //------------------

    function check_email($email){
        $db = $this->load->database('default', true);
        $query = $db->query("select * from user where email='".$email."'");

        if($query->num_rows() == 1) return false;
        else return true;
    }
    //-------------

    function user_add($email,$name,$password,$depart,$userlevel,$plant_code, $change_pass){
        $db = $this->load->database('default', true);
        $data = array(
          'password' => $password ,
          'name' => $name ,
          'email' => $email,
          'active' => 'Y',
          'depart_code' => $depart,
          'user_level' => $userlevel,
          'plant_code' => $plant_code,
          'change_pass' => $change_pass
          );

          $result = $this->db->insert('user', $data);

          if($result) return true;
          else return false;
    }
    //------------------

    function list_user_by_userlevel($user_level){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM user u
                 inner join mst_department dpt on(u.depart_code=dpt.depart_code)
                 where user_level >= ".$user_level."
                 ;");
        return $query->result_array();
    }
    //-------------

    function list_userlevel_by_level($user_level){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM user_level where id_user_level >= ".$user_level.";");
        return $query->result_array();
    }
    //-------------

    function list_plant(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM mst_plant where active='Y';");
        return $query->result_array();
    }
    //--------------

    function list_so(){
        $db = $this->load->database('sql_server', true);
        $var = "TPM_GoLive$";
        $query = $db->query("select top 1000 [No_] as No,[pdf_] as pdf from [".$var."Sales Invoice Header Ext] where [No_] = 'TPM-SIN-A0076911';");
        return $query->result_array();
    }
    //--------------

    function check_password_user_if_same_with_previous($user_id, $password){
        $db = $this->load->database('default', true);
        $query = $db->query("select * from user where user_id='".$user_id."' and password='".$password."'");

        if($query->num_rows() == 1) return true;
        else return false;
    }
    //---

    // 2023-07-04
    function get_user_log_data($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select u.name, ip_address, datetime, activity FROM tpimx_wms.zlog z inner join tpimx_wms.user u on(u.user_id=z.user_id) where date_format(datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."' order by datetime desc;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----
}

?>
