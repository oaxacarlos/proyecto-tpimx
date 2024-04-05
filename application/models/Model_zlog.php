<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_zlog extends CI_Model{
      var $id_log, $user_id, $ip_address, $datetime, $activity;

      function insert($activity){
          $session_data = $this->session->userdata('z_tpimx_logged_in');
          $user_id = $session_data['z_tpimx_user_id'];

          $db = $this->load->database('default', true);
          $data = array(
              "user_id" => $user_id,
              "ip_address" => get_ip_address(),
              "datetime" => get_datetime_now(),
              "activity" => $activity,
          );

          $result = $this->db->insert('zlog', $data);
          if($result) return true; else return false;
      }
}

?>
