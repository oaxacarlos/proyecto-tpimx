<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_so extends CI_Model{
      var $so_no, $sell_cust_no, $bill_cust_no, $bill_cust_name, $bill_to_addr, $bill_to_addr2, $bill_to_city, $bill_to_contact,
      $bill_to_post_code, $bill_to_county, $bill_to_ctry_region_code, $ship_to_name, $ship_to_addr, $ship_to_addr2, $ship_to_city,
      $ship_to_contact, $ship_to_post_code, $ship_to_county, $ship_to_ctry_code, $sell_to_cust_name, $sell_to_cust_addr, $sell_to_cust_addr2,
      $sell_to_city, $sell_to_contact, $sell_to_post_code, $sell_to_county, $location_code;

      function insert(){
          $db = $this->load->database('default', true);
          $data = array(
              "so_no" => $this->so_no,
              "sell_cust_no" => $this->sell_cust_no,
              "bill_cust_name" => $this->bill_cust_name,
              "bill_cust_no" => $this->bill_cust_no,
              "bill_to_addr" => $this->bill_to_addr,
              "bill_to_addr2" => $this->bill_to_addr2,
              "bill_to_city" => $this->bill_to_city,
              "bill_to_contact" => $this->bill_to_contact,
              "bill_to_post_code" => $this->bill_to_post_code,
              "bill_to_county" => $this->bill_to_county,
              "bill_to_ctry_region_code" => $this->bill_to_ctry_region_code,
              "ship_to_name" => $this->ship_to_name,
              "ship_to_addr" => $this->ship_to_addr,
              "ship_to_addr2" => $this->ship_to_addr2,
              "ship_to_city" => $this->ship_to_city,
              "ship_to_contact" => $this->ship_to_contact,
              "ship_to_post_code" => $this->ship_to_post_code,
              "ship_to_county" => $this->ship_to_county,
              "ship_to_ctry_region_code" => $this->ship_to_ctry_region_code,
              "sell_to_cust_name" => $this->sell_to_cust_name,
              "sell_to_cust_addr" => $this->sell_to_cust_addr,
              "sell_to_cust_addr2" => $this->sell_to_cust_addr2,
              "sell_to_city" => $this->sell_to_city,
              "sell_to_contact" => $this->sell_to_contact,
              "sell_to_post_code" => $this->sell_to_post_code,
              "sell_to_county" => $this->sell_to_county,
              "location_code" => $this->location_code,
          );

          $result = $this->db->insert('tsc_so', $data);
          if($result) return true; else return false;
      }
      //----

      function is_exist($code){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT count(so_no) as total FROM tsc_so where so_no='".$code."';";
          $query = $db->query($query_temp)->row();
          if($query->total > 0) return true; else return false;
      }
      //---

      function get_data_by_doc_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_so t where so_no='".$this->so_no."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_data_by_multiple_doc_no($docno){
          $db = $this->load->database('default', true);
          $query_temp = "select * FROM tsc_so t where so_no in ( ";
          foreach($docno as $row){ $query_temp.="'".$row."',"; }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=")";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-06-22
      function so_information_from_whsip_and_line($doc_no, $line_no){
          $db = $this->load->database('default', true);
          $query_temp = "select so_no,sell_cust_no, sell_to_cust_name
              FROM tsc_in_out_bound_d d
              inner join tsc_so so on(d.src_no=so.so_no)
              where doc_no='".$doc_no."' and line_no='".$line_no."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---
}

?>
