<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_mst_location extends CI_Model{
      var $code, $name;

      function get_data(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT code, name FROM mst_location";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      function get_data2(){
        $db = $this->load->database('default', true);

        $user_plant = get_plant_code_user();// 2023-03-02 WH3

        $query_temp = "select code, name FROM mst_location where code in(".$user_plant.")";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---
}

?>
