<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_general extends CI_Model{

    function get_last_approval_status_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM setting i where setting_name='empc_last_approval_code';")->row();
      return $query->value1;
    }
    //-------------

    function get_last_approval_userid_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM empc_setting i where empc_setting_name='last_approval_user_id';")->row();
      return $query->value1;
    }
    //-------------

    function get_approval_code_done_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM empc_setting i where empc_setting_name='approval_code_done';")->row();
      return $query->value1;
    }
    //-------------

    function get_user_can_see_all_depart_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM empc_setting i
                    where empc_setting_name='user_can_see_all_depart';")->row();
      return $query->value1;
    }
    //-------------

    function get_user_can_see_only_his_depart_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM empc_setting i
                    where empc_setting_name='user_can_see_only_his_depart';")->row();
      return $query->value1;
    }
    //-------------

}
