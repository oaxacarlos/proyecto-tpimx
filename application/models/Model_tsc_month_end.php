<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_month_end extends CI_Model{
      var $fromm, $too, $name, $id, $location_code;

      function insert(){
          $db = $this->load->database('default', true);
          $data = array(
              "fromm" => $this->fromm,
              "too" => $this->too,
              "name" => $this->name,
              "location_code" => $this->location_code,
          );

          $result = $this->db->insert('tsc_month_end', $data);
          if($result) return true; else return false;
      }
      //---

      function get_month_end(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT fromm,too FROM tsc_month_end t where fromm <= '".$this->fromm."'  and too >= '".$this->too."' limit 1;";
          $query = $db->query($query_temp)->row();
          if($query->fromm!='' && $query->too!='') return 1;
          else return 0;
      }
      //---

      function get_list(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_month_end t order by id";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function is_too(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT if(id is null or id='',0,1) as is_too FROM tsc_month_end t
          where date_format(too,'%Y-%m') between date_format('".$this->fromm."','%Y-%m') and date_format('".$this->too."','%Y-%m') and location_code='".$this->location_code."' ";
          $query = $db->query($query_temp)->row();

          return $query->is_too;
      }
      //---

      function is_fromm(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT if(id is null or id='',0,1) as is_fromm FROM tsc_month_end t
          where date_format(fromm,'%Y-%m') between date_format('".$this->fromm."','%Y-%m') and date_format('".$this->too."','%Y-%m') and location_code='".$this->location_code."';";
          $query = $db->query($query_temp)->row();

          return $query->is_fromm;
      }
      //---

      function delete_by_id(){
          $db = $this->load->database('default', true);
          $query_temp = "delete from tsc_month_end where id='".$this->id."'";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_month_end_by_whs($whs){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT fromm,too FROM tsc_month_end t where fromm <= '".$this->fromm."'  and too >= '".$this->too."' and location_code='".$whs."' limit 1;";
          $query = $db->query($query_temp)->row();
          if($query->fromm!='' && $query->too!='') return 1;
          else return 0;
      }
      //---
}

?>
