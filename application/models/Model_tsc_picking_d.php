<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_picking_d extends CI_Model{
      var $doc_no, $line_no, $src_location_code, $src_no, $src_line_no, $item_code, $qty_to_picked, $uom,
      $completely_picked, $picked_datetime, $created_datetime, $desc, $location_code, $zone_code, $area_code, $rack_code, $bin_code;

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
              "qty_to_picked" => $this->qty_to_picked,
              "description" => $this->desc,
              "created_datetime" => $this->created_datetime,
              "location_code" => $this->location_code,
              "zone_code" => $this->zone_code,
              "area_code" => $this->area_code,
              "rack_code" => $this->rack_code,
              "bin_code" => $this->bin_code,
          );

          $result = $this->db->insert('tsc_pick_d', $data);
          if($result) return true; else return false;
      }

      //---

      function get_list_data(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no, line_no, src_location_code, src_no, src_line_no, item_code, qty_to_picked, uom, completely_picked, picked_datetime, created_datetime, description, location_code, zone_code, area_code, rack_code, bin_code
          FROM tsc_pick_d t where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_start_finish_time(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_pick_d set picked_datetime='".$this->picked_datetime."', completely_picked='".$this->completely_picked."' where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";

          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_list_serial_number_by_src_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT d.src_no as src_no, d.doc_no as doc_no, d.item_code, serial_number_pick,sn2_pick, d2.qty as qty_d2,
          d.line_no as dline,d2.line_no as d2line,
            location_code_pick, zone_code_pick, area_code_pick, rack_code_pick, bin_code_pick, d2.line_no, d2.src_line_no, d2.src_no as pick_doc, d2.master_barcode, location_code_scan, zone_code_scan, area_code_scan, rack_code_scan, bin_code_scan,sn2_pick, sn2_scan, serial_number_scan
            FROM tsc_pick_d d
            inner join tsc_pick_d2 d2 on(d.doc_no=d2.src_no and d.line_no=d2.src_line_no)
            where d.src_no='".$this->src_no."' order by item_code, dline, d2line, serial_number_pick";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_list_pick_for_pack_by_src_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT d.item_code,qty_to_picked,d.uom,d.description,serial_number_scan,d.doc_no FROM tsc_pick_d d
          inner join tsc_pick_d2 d2 on(d.doc_no=d2.src_no and d.line_no=d2.src_line_no)
          where d.src_no='".$this->src_no."' order by item_code, serial_number_pick;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_pick_serial_number_scan_by_whship(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT pick_d2.item_code as item_code, pick_d2.serial_number_scan as serial_number FROM tsc_pick_d pick_d
            inner join tsc_pick_d2 pick_d2 on(pick_d.doc_no=pick_d2.src_no and pick_d.line_no=pick_d2.src_line_no)
            where pick_d.src_no='".$this->src_no."' order by pick_d2.item_code, serial_number_pick;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_pick_serial_number_scan_by_whship_temp(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT pick_d2.item_code as item_code, pick_d2.serial_number_pick as serial_number,sn2_pick as sn2 FROM tsc_pick_d pick_d
            inner join tsc_pick_d2 pick_d2 on(pick_d.doc_no=pick_d2.src_no and pick_d.line_no=pick_d2.src_line_no)
            where pick_d.src_no='".$this->src_no."' order by pick_d2.item_code, serial_number_pick;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function insert_v2($data){
          $db = $this->load->database('default', true);
          $this->db->insert_batch('tsc_pick_d', $data);
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_picking_no_grup_by_doc_no($doc_no, $line_no, $item_code){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no, src_line_no,line_no,qty_to_picked FROM tsc_pick_d t where doc_no='".$doc_no."' and line_no='".$line_no."' and item_code='".$item_code."' group by doc_no;";

          $query = $db->query($query_temp);
          return $query->result_array();

      }
      //---

      function update_qty_to_picked(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_pick_d set qty_to_picked='".$this->qty_to_picked."' where doc_no='".$this->doc_no."'
          and line_no='".$this->line_no."' and item_code='".$this->item_code."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function delete_line(){
          $db = $this->load->database('default', true);
          $query_temp = "delete from tsc_pick_d where doc_no='".$this->doc_no."'
          and line_no='".$this->line_no."' and item_code='".$this->item_code."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      // 2022-11-04
      function get_pick_doc_no_by_src_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no FROM tsc_pick_d where src_no='".$this->src_no."' group by doc_no;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-02-10
      function insert_v3($doc_no,$line_no,$src_location_code, $src_no, $src_line_no, $item_code, $qty_to_picked, $uom, $location_code, $zone_code, $area_code, $rack_code, $bin_code, $description, $created_datetime){
          $db = $this->load->database('default', true);

          $insert_by_number_row = 500;
          $row_insert = 1;

          $query_temp="insert into tsc_pick_d(doc_no,line_no,src_location_code, src_no, src_line_no, item_code, qty_to_picked, uom, location_code, zone_code, area_code, rack_code, bin_code, description, created_datetime) values";
          for($i=0;$i<count($doc_no);$i++){
              if($row_insert > $insert_by_number_row ){
                    $query_temp = substr($query_temp,0,-1);
                    $query = $db->query($query_temp);

                    $query_temp="insert into tsc_pick_d(doc_no,line_no,src_location_code, src_no, src_line_no, item_code, qty_to_picked, uom, location_code, zone_code, area_code, rack_code, bin_code, description, created_datetime) values";
                    $row_insert = 1;
              }

              $query_temp.="('".$doc_no[$i]."','".$line_no[$i]."','".$src_location_code[$i]."','".$src_no[$i]."',
              '".$src_line_no[$i]."', '".$item_code[$i]."', '".$qty_to_picked[$i]."', '".$uom[$i]."','".$location_code[$i]."', '".$zone_code[$i]."', '".$area_code[$i]."',
              '".$rack_code[$i]."', '".$bin_code[$i]."', '".$description[$i]."','".$created_datetime[$i]."'),";

              $row_insert++;
          }
          $query_temp = substr($query_temp,0,-1);
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_list_serial_number_by_src_no_and_item(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT d.src_no as src_no, d.doc_no as doc_no, d.item_code, serial_number_pick,sn2_pick, d2.qty as qty_d2,
          d.line_no as dline,d2.line_no as d2line,
            location_code_pick, zone_code_pick, area_code_pick, rack_code_pick, bin_code_pick, d2.line_no, d2.src_line_no, d2.src_no as pick_doc, d2.master_barcode, location_code_scan, zone_code_scan, area_code_scan, rack_code_scan, bin_code_scan,sn2_pick, sn2_scan, serial_number_scan
            FROM tsc_pick_d d
            inner join tsc_pick_d2 d2 on(d.doc_no=d2.src_no and d.line_no=d2.src_line_no)
            where d.src_no='".$this->src_no."' and d.item_code='".$this->item_code."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // WH3 2023-05-18
      function get_pick_sn2_pick_by_whship_temp(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT pick_d2.item_code as item_code, sn2_pick as sn2
            FROM tsc_pick_d pick_d
            inner join tsc_pick_d2 pick_d2 on(pick_d.doc_no=pick_d2.src_no and pick_d.line_no=pick_d2.src_line_no)
            where pick_d.src_no='".$this->src_no."' and master_barcode='1' group by sn2_pick, item_code;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---
}

?>
