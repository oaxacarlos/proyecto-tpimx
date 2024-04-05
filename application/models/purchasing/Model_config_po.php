<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_config_po extends CI_Model{
      var $id, $name, $valuee;
      function get_value_by_setting_name(){
        $db = $this->load->database('tpimx_purchasing', true);
        $query_temp = "SELECT valuee FROM config where status_type='".$this->name."';";
        $query = $db->query($query_temp)->row();
        return $query->valuee;
      }

      function update_value(){
        $db = $this->load->database('tpimx_purchasing', true);
        $query_temp = "update config set valuee='".$this->valuee."'  WHERE status_type='".$this->name."'";
        $query = $db->query($query_temp);
        return true;
    }
}