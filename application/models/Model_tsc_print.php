<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_print extends CI_Model{

      function insert($doc_no, $datetime, $doc_type){
        $db = $this->load->database('default', true);
        $data = array(
            "doc_no" => $doc_no,
            "doc_datetime" => $datetime,
            "doc_type" => $doc_type,
        );

        $result = $this->db->insert('tsc_print', $data);
        if($result) return true; else return false;
      }
      //--
}

?>
