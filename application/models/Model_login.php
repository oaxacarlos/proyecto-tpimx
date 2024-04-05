<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Login extends CI_Model{

  function login($username,$password){
      $this->db->select('user_id,password,name,email,active,depart_code,user_level,plant_code, change_pass, userid_1');
      $this->db->from('user');
      $this->db->where('email',$username);
      $this->db->where('password',md5($password));
      //$this->db->where('active','Y');

    /*  $query = "select user_id,password,name,email,active,depart_code from user
      where email='".$username."' and password='".md5($password)."'";

      $filename = "hasil.txt";
      $myfile = fopen($filename, "a");
      fwrite($myfile,$query."\r\n");
      fclose($myfile);*/

      $this->db->limit(1);
      $query = $this->db->get();
      if($query->num_rows() == 1) return $query->result();
      else return false;
  }

  function get_user_depart_code($user_id){
      $db = $this->load->database('default', true);
      $query = $db->query("select depart_name from user u
                inner join mst_department dpt on(u.depart_code=dpt.depart_code)
                where user_id='".$user_id."'");

      return $query->result();
  }
  //----------------------

  function check_user_active($user_id){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT active FROM `user` u where user_id='".$user_id."';")->row();
      return $query->active;
  }
  //--------------------

  function get_user_list(){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT user_id, name, email FROM `user` u where active='Y';";
      $query = $db->query($query_temp);
      return $query->result_array();
  }
  //---

  function get_user_list_by_department($depart){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT user_id, name, email FROM `user` u where active='Y' ";

      // depart code
      $query_temp.= " and depart_code in(";
      foreach($depart as $row){ $query_temp.="'".$row."',";}
      $query_temp = substr($query_temp,0,-1);
      $query_temp.=" ) order by name";
      //--

      $query = $db->query($query_temp);
      return $query->result_array();
  }
  //---

  function get_user_list_by_department_and_whs($depart, $whs){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT user_id, name, email FROM `user` u where active='Y' ";

      // depart code
      $query_temp.= " and depart_code in(";
      foreach($depart as $row){ $query_temp.="'".$row."',";}
      $query_temp = substr($query_temp,0,-1);
      $query_temp.=" ) ";
      //--

      $query_temp.=" and plant_code like '%''".$whs."''%'";

      $query = $db->query($query_temp);
      return $query->result_array();
  }
  //---

  // 2023-07-13
  function get_user_list_by_department_and_whs_qc($depart, $whs){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT user_id, name, email FROM `user` u where active='Y' ";

      // depart code
      $query_temp.= " and depart_code in(";
      foreach($depart as $row){ $query_temp.="'".$row."',";}
      $query_temp = substr($query_temp,0,-1);
      $query_temp.=" ) ";
      //--

      $query_temp.=" and plant_code like '%''".$whs."''%' and qc='1'";

      $query = $db->query($query_temp);
      return $query->result_array();
  }
  //---

  // 2023-07-19
  function get_user_color($type){
      $db = $this->load->database('default', true);

      $query_temp = "select * FROM user_color where typee='".$type."';";
      $query = $db->query($query_temp);
      return $query->result_array();
  }
  //---
}

?>
