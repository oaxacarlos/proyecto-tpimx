<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_config extends CI_Model{
      var $id, $name, $valuee;

      function get_value_by_id(){
        $db = $this->load->database('default_client', true);
        $query_temp = "SELECT valuee FROM config c where id='".$this->id."';";
        $query = $db->query($query_temp)->row();
        return $query->valuee;
      }
      //---

      function get_value_by_setting_name(){
        $db = $this->load->database('default_client', true);
        $query_temp = "SELECT valuee FROM config where name='".$this->name."';";
        $query = $db->query($query_temp)->row();
        return $query->valuee;
      }
      //--

      function update_barcode_last_used(){
          $db = $this->load->database('default_client', true);
          $query_temp = "update config set valuee='".$this->valuee."'  where name='barcode_last_used'";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function update_value(){
          $db = $this->load->database('default_client', true);
          $query_temp = "update config set valuee='".$this->valuee."'  where name='".$this->name."'";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_email_detail(){
          $db = $this->load->database('default_client', true);

          unset($data);

          $this->name = "email_user";
          $data["email_user"] = $this->get_value_by_setting_name();			// email user

          $this->name = "email_pass";
          $data["email_pass"] = $this->get_value_by_setting_name();			// email pass

          $this->name = "email_host";
          $data["email_host"] = $this->get_value_by_setting_name();			// email host

          $this->name = "email_from_info";
          $data["email_from_info"] = $this->get_value_by_setting_name(); // email from info

          $this->name = "email_user2";
          $data["email_user2"] = $this->get_value_by_setting_name();			// email user2

          return $data;
      }
      //--
}

?>
