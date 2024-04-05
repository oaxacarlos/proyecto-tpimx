<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_transferbin_d2 extends CI_Model{
      var $doc_no, $line_no, $src_line_no, $item_code, $qty, $uom, $serial_number, $created_datetime, $pick_datetime, $putaway_datetime;


      function insert(){
          $db = $this->load->database('default', true);
          $data = array(
              "doc_no" => $this->doc_no,
              "line_no" => $this->line_no,
              "src_line_no" => $this->src_line_no,
              "item_code" => $this->item_code,
              "qty" => $this->qty,
              "uom" => $this->uom,
              "serial_number" => $this->serial_number,
              "created_datetime" => $this->created_datetime,
          );

          $result = $this->db->insert('tsc_transferbin_d2', $data);
          if($result) return true; else return false;
      }

      //---

      function update_pick_datetime(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_d2 set pick_datetime='".$this->pick_datetime."' where doc_no='".$this->doc_no."' and src_line_no='".$this->src_line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      function update_put_datetime(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_d2 set putaway_datetime='".$this->putaway_datetime."' where doc_no='".$this->doc_no."' and src_line_no='".$this->src_line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      function get_list_by_doc_no_and_src_line_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_transferbin_d2 t where doc_no='".$this->doc_no."' and src_line_no='".$this->src_line_no."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function insert_d2_v2($doc_no, $k_temp, $line_no , $item,$qty,$uom,$serial_number,$datetime){
          $db = $this->load->database('default', true);

          $query_temp = "insert into tsc_transferbin_d2(doc_no, line_no, src_line_no, item_code, qty, uom, serial_number, created_datetime) values";
          for($i=0;$i<count($serial_number);$i++){
              $query_temp.="('".$doc_no."','".$k_temp[$i]."','".$line_no."','".$item."','".$qty."','".$uom."','".$serial_number[$i]."','".$datetime."'),";
          }
          $query_temp = substr($query_temp,0,-1);
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_list_by_doc_no_print_barcode(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_transferbin_d2 t where doc_no='".$this->doc_no."' order by item_code, serial_number;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function insert_v3($doc_no, $src_line_no, $item_code, $qty, $uom, $sn, $created_datetime, $sn2, $line_no){
          $db = $this->load->database('default', true);

          $query_temp = array();
          for($i=0;$i<count($item_code);$i++){
              $query_temp2 = array(
                  "doc_no"      => $doc_no[$i],
                  "line_no"     => $line_no[$i],
                  "src_line_no" => $src_line_no[$i],
                  "item_code"   => $item_code[$i],
                  "qty"         => $qty[$i],
                  "uom"         => $uom[$i],
                  "serial_number" => $sn[$i],
                  "sn2"         => $sn2[$i],
                  "created_datetime" => $created_datetime
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->insert_batch('tsc_transferbin_d2',$query_temp);
          return true;
      }
      //--

      function get_list_by_doc_no_print_master_barcode(){
          $db = $this->load->database('default', true);
          $query_temp = "select sn2, item_code FROM tsc_transferbin_d2 t where doc_no='".$this->doc_no."' group by sn2, item_code;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-02-14
      function update_pick_datetime2(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_d2 set pick_datetime='".$this->pick_datetime."' where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      // 2023-02-14
      function update_put_datetime2(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_d2 set putaway_datetime='".$this->putaway_datetime."' where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--
}

?>
