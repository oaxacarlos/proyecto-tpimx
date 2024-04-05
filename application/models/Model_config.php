<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_config extends CI_Model{
      var $id, $name, $valuee;

      function get_value_by_id(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT valuee FROM config c where id='".$this->id."';";
        $query = $db->query($query_temp)->row();
        return $query->valuee;
      }
      //---

      function get_value_by_setting_name(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT valuee FROM config where name='".$this->name."';";
        $query = $db->query($query_temp)->row();
        return $query->valuee;
      }
      //--

      function update_barcode_last_used(){
          $db = $this->load->database('default', true);
          $query_temp = "update config set valuee='".$this->valuee."'  where name='barcode_last_used'";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function update_value(){
          $db = $this->load->database('default', true);
          $query_temp = "update config set valuee='".$this->valuee."'  where name='".$this->name."'";
          $query = $db->query($query_temp);
          return true;
      }
      //---
}

?>
