<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_packing_d extends CI_Model{
      var $doc_no, $line_no, $src_location_code, $src_no, $src_line_no, $item_code, $qty_to_packed, $uom,
      $completely_packed, $packed_datetime, $created_datetime, $desc;

      function insert(){
          $db = $this->load->database('default', true);
          $data = array(
              "doc_no" => $this->doc_no,
              "line_no" => $this->line_no,
              "src_location_code" => $this->src_location_code,
              "src_no" => $this->src_no,
              "src_line_no" => $this->src_line_no,
              "item_code" => $this->item_code,
              "uom" => $this->uom,
              "qty_to_packed" => $this->qty_to_packed,
              "description" => $this->desc,
              "created_datetime" => $this->created_datetime,
          );

          $result = $this->db->insert('tsc_pack_d', $data);
          if($result) return true; else return false;
      }

      //---

      function get_list_pack(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT d.doc_no as doc_no,h.created_datetime, h.doc_datetime, doc_date, h.src_location_code, sum(d.qty_to_packed) as qty_to_packed, d.uom,
          h.dest_no, dest_name, dest_addr, dest_addr2, dest_city, dest_contact, dest_post_code, dest_county, dest_country, out_d.src_no as so_no
          FROM tsc_pack_h h inner join tsc_pack_d d on(h.doc_no=d.doc_no)
          inner join tsc_in_out_bound_d out_d on(d.src_no=out_d.doc_no and d.src_line_no=out_d.line_no)
          where d.src_no='".$this->src_no."' group by d.doc_no, created_datetime, h.doc_datetime, h.src_location_code order by d.doc_no;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_info_by_doc_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_pack_d t where doc_no='".$this->doc_no."'";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      function get_info_by_multiple_doc_no($doc_no){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_pack_d t where doc_no in ";
          $query_temp.="(";
          foreach($doc_no as $row){
            $query_temp.="'".$row."',";
          }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=");";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2022-11-04
      function get_pack_doc_no_by_src_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no from tsc_pack_d where src_no='".$this->src_no."' group by doc_no";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2022-11-04
      function get_list(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no, src_no, src_line_no, item_code, qty_to_packed, uom FROM tsc_pack_d
          where src_no='".$this->src_no."' ;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2022-12-03
      function get_list_pack_group_by_src_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no FROM tsc_pack_d where src_no='".$this->src_no."' group by doc_no;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // 2022-12-03
      function delete_packing_d_by_doc_no(){
          $db = $this->load->database('default', true);
          $query_temp = "delete FROM tsc_pack_d where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--
}

?>
