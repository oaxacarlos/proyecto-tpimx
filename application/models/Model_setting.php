<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Setting extends CI_Model{

    var $setting_id, $setting_name, $value1, $text1;

    function get_value_by_setting_name(){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT value1 FROM setting s where setting_name='".$this->setting_name."';";
      $query = $db->query($query_temp)->row();
      return $query->value1;
    }
    //--

}

?>
