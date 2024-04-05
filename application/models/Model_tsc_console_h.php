<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_console_h extends CI_Model{
      var $doc_no,$created_datetime, $doc_datetime, $created_user, $external_document, $doc_date, $text1, $dest_no, $dest_addr, $dest_addr2, $dest_name, $dest_contact, $dest_county, $dest_country, $dest_post_code, $dest_city, $src_no;

      function call_store_procedure_newconsole(){
        $db = $this->load->database('default', true);
        $query_temp = "call NEWCONSOLE('".$this->prefix_code."', '".$this->created_datetime."', '".$this->doc_datetime."', '".$this->created_user."',  '".$this->doc_date."', '".$this->text1."','".$this->dest_no."','".$this->dest_addr."','".$this->dest_addr2."',
        '".$this->dest_name."','".$this->dest_contact."','".$this->dest_county."','".$this->dest_country."','".$this->dest_post_code."','".$this->dest_city."')";

        $query = $db->query($query_temp)->row();
        return $query->trsc_no;
      }
      //---

      function get_list_by_desc(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT h.doc_no as doc_no,h.created_datetime, h.doc_datetime, h.doc_date,
          h.dest_no, dest_name, dest_addr, dest_addr2, dest_city, dest_contact, dest_post_code, dest_county, dest_country, h.created_user, u.name, text1, count(h.doc_no) as pack FROM tsc_console_h h
          inner join tsc_console_d d on(h.doc_no=d.doc_no)
          inner join user u on(u.user_id=h.created_user)
          group by h.doc_no order by h.doc_no desc";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

}

?>
