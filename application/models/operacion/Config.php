<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config extends CI_Model{
      var $id, $name, $valuee;

      function get_value_by_setting_name(){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "SELECT valuee FROM config where name='".$this->name."';";
        $query = $db->query($query_temp)->row();
        return $query->valuee;
      }
      //--

      function update_value(){
          $db = $this->load->database('default_oprc', true);
          $query_temp = "update config set valuee='".$this->valuee."'  where name='".$this->name."'";
          $query = $db->query($query_temp);
          return true;
      }
      //---
}

?>
