<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_mst_bin extends CI_Model{
      var $code, $name, $type, $location_code, $zone_code, $area_code, $rack_code, $active, $description;

      function get_data(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT code, name, type, location_code, zone_code, area_code, rack_code, active, description FROM mst_bin m
        where active='1' order by zone_code,area_code,rack_code,code;";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      function check_bin(){
          $db = $this->load->database('default', true);
          $query_temp = "select if(code is null or code='',0,1) as is_exist
          FROM mst_bin m where (location_code='".$this->location_code."' and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."' and code='".$this->code."') and active='".$this->active."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      function get_data_by_location($whs){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT code, name, type, location_code, zone_code, area_code, rack_code, active, description FROM mst_bin m
        where active='1' and location_code='".$whs."' order by zone_code,area_code,rack_code,code;";
        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      // 2023-06-01
      function check_bin2(){
          $db = $this->load->database('default', true);
          $query_temp = "select if(code is null or code='',0,1) as is_exist
          FROM mst_bin m where (location_code='".$this->location_code."' and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."' and code='".$this->code."') and active='".$this->active."';";
          $query = $db->query($query_temp)->row();
          return $query->is_exist;
      }
      //--
}

?>
