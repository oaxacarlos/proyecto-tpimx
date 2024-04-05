<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_mst_ship_to extends CI_Model{
      var $cust_no, $code, $name, $name2, $address, $address2, $city, $contact, $phone_no, $country_region_code;
      var $location_code, $post_code, $county;

      function get_list_by_ship_to_code(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM mst_ship_to m where code='".$this->code."'";
        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      function insert(){
        $db = $this->load->database('default', true);
        $data = array(
            "cust_no" => $this->cust_no,
            "code" => $this->code,
            "name" => $this->name,
            "name2" => $this->name2,
            "address" => $this->address,
            "address2" => $this->address2,
            "city" => $this->city,
            "contact" => $this->contact,
            "phone_no" => $this->phone_no,
            "country_region_code" => $this->country_region_code,
            "location_code" => $this->location_code,
            "post_code" => $this->post_code,
            "county" => $this->county,
        );

        $result = $this->db->insert('mst_ship_to', $data);
        if($result) return true; else return false;
      }
      //---

      function truncate_table_shipto(){
          $db = $this->load->database('default', true);
          $query_temp = "truncate table mst_ship_to;";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      function get_list_without_mecanica_old(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT cust_no, code as ship_to_code,name, address, address2, city, contact, country_region_code, post_code, county
          FROM mst_ship_to m where cust_no!='1190027' or code like 'MTK%'

          union

          SELECT cust_no, cust_no as ship_to_code, name, address, address2, city, contact, country_region_code, post_code, county
          FROM mst_cust m where cust_no not in(
          SELECT cust_no FROM mst_ship_to m where cust_no!='1190027' or code like 'MTK%') order by cust_no, county;";
        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---
}

?>
