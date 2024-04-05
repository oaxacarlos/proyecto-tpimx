<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Navigation extends CI_Model{

      function list_menu1_by_user($user){
          $db = $this->load->database('default', true);
          $query = $db->query("SELECT distinct(m1.menu1_code),menu1_name,menu1_initial,menu1_link
                    FROM acl_user_menu a
                    inner join acl_menu3 m3 on(a.menu3_code=m3.menu3_code)
                    inner join acl_menu2 m2 on(m2.menu2_code=m3.menu2_code)
                    inner join acl_menu1 m1 on(m1.menu1_code=m2.menu1_code)
                    where login_user='".$user."' order by menu1_order;");
          return $query->result_array();
      }
      //-------------

      function list_menu2_by_user($user,$menu1_code){
          $db = $this->load->database('default', true);
        $query = $db->query("SELECT distinct(m2.menu2_code),menu2_name,menu2_initial,menu2_link
                    FROM acl_user_menu a
                    inner join acl_menu3 m3 on(a.menu3_code=m3.menu3_code)
                    inner join acl_menu2 m2 on(m2.menu2_code=m3.menu2_code)
                    inner join acl_menu1 m1 on(m1.menu1_code=m2.menu1_code)
                    where login_user='".$user."' and m2.menu1_code='".$menu1_code."'
                    order by menu2_code;");
          return $query->result_array();
      }
      //-------------

      function list_menu3_by_user($user,$menu2_code){
          $db = $this->load->database('default', true);
          $query = $db->query("SELECT distinct(m3.menu3_code),menu3_name,menu3_initial,menu3_link,line
                    FROM acl_user_menu a
                    inner join acl_menu3 m3 on(a.menu3_code=m3.menu3_code)
                    inner join acl_menu2 m2 on(m2.menu2_code=m3.menu2_code)
                    inner join acl_menu1 m1 on(m1.menu1_code=m2.menu1_code)
                    where login_user='".$user."' and m3.menu2_code='".$menu2_code."'
                    order by menu3_order;");
          return $query->result_array();
      }
      //-------------

      function get_acl_user_permis($user){
          $db = $this->load->database('default', true);
          $query = $db->query("SELECT * FROM acl_user_permis where login_user='".$user."';");
          return $query->result_array();
      }
      //--
}


?>
