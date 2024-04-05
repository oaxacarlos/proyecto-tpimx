<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_consolidate_h extends CI_Model{
      var $doc_no,$created_datetime, $doc_datetime, $created_user, $external_document, $doc_date, $text1, $dest_no, $dest_addr, $dest_addr2, $dest_name, $dest_contact, $dest_county, $dest_country, $dest_post_code, $dest_city, $src_no;

      function call_store_procedure_console(){
        $db = $this->load->database('default', true);
        $query_temp = "call NEWCONSOLE('".$this->prefix_code."', '".$this->created_datetime."', '".$this->doc_datetime."','".$this->doc_type."','".$this->src_location_code."','".$this->created_user."','".$this->external_document."', '".$this->statuss."',
        '".$this->doc_date."', '".$this->text1."','".$this->src_no."')";

        $query = $db->query($query_temp)->row();
        return $query->trsc_no;
      }
      //---

      

}

?>
