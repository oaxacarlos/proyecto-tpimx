<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_user extends CI_Model{
      var $id, $name, $valuee;

      function get_user_by_id($user_id){
          $db = $this->load->database('default_client', true);
          $query_temp = "SELECT user_id, name, email FROM `user` u where user_id='".$user_id."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_user_all_point($user_id){
          $db = $this->load->database('default_client', true);
          $query_temp = "select point_all from user where user_id='".$user_id."';";
          $query = $db->query($query_temp);
          $point_all = $query->row()->point_all;
          return $point_all;
      }
      //--
}

?>
