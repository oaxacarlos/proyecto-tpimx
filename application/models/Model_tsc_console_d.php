<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_console_d extends CI_Model{
      var $doc_no, $line_no, $src_no, $created_datetime;

      function insert(){
          $db = $this->load->database('default', true);
          $data = array(
              "doc_no" => $this->doc_no,
              "line_no" => $this->line_no,
              "src_no" => $this->src_no,
              "created_datetime" => $this->created_datetime,
          );

          $result = $this->db->insert('tsc_console_d', $data);
          if($result) return true; else return false;
      }
      //---

      function get_list(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_console_d t where doc_no='".$this->doc_no."'";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--
}

?>
