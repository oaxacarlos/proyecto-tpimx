<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_packing_d2 extends CI_Model{
      var $src_no, $line_no, $src_line_no, $item_code, $qty, $uom, $completely_packed, $description, $created_datetime;
      var $pick_datetime, $scan_datetime, $packed, $packed_datetime;

      function insert(){
          $db = $this->load->database('default', true);
          $data = array(
              "src_no" => $this->src_no,
              "line_no" => $this->line_no,
              "src_line_no" => $this->src_line_no,
              "item_code" => $this->item_code,
              "qty" => $this->qty,
              "uom" => $this->uom,
              "description" => $this->desc,
              "serial_number_pack" => $this->serial_number_pack,
              "description" => $this->description,
              "created_datetime" => $this->created_datetime,
          );

          $result = $this->db->insert('tsc_pack_d2', $data);
          if($result) return true; else return false;
      }

      //---

      function get_list_data(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT src_no, src_no, line_no, src_line_no, item_code, qty, uom,
          location_code_pick, zone_code_pick, area_code_pick, rack_code_pick, bin_code_pick,serial_number_pick,
          created_datetime, description, completely_picked
          FROM tsc_pick_d2 t where src_no='".$this->doc_no."' and src_line_no='".$this->src_line_no."';";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_start_finish_time(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_pick_d2 set pick_datetime='".$this->pick_datetime."', completely_picked='".$this->completely_picked."' where src_line_no='".$this->src_line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function update_scan_by_serial_number(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_pick_d2 set location_code_scan='".$this->location_code_scan."', zone_code_scan='".$this->zone_code_scan."', area_code_scan='".$this->area_code_scan."', rack_code_scan='".$this->rack_code_scan."', bin_code_scan='".$this->bin_code_scan."', scan_datetime='".$this->scan_datetime."', serial_number_scan='".$this->serial_number_scan."' where serial_number_pick='".$this->serial_number_pick."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function update_packed_by_serial_number(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_pick_d2 set packed='".$this->packed."', packed_datetime='".$this->packed_datetime."' where serial_number_scan='".$this->serial_number_scan."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function insert_v2($src_no, $src_line_no, $item_code,$qty,$uom, $desc, $created_datetime, $result_sn){

            $db = $this->load->database('default', true);

            $insert_by_number_row = 500;
            $row_insert = 1;
            $i = 1;

            $query_temp="insert into tsc_pack_d2(src_no, line_no, src_line_no, item_code, qty, uom, description, created_datetime, serial_number_pack) values";
            foreach($result_sn as $row){
                if($row_insert > $insert_by_number_row ){
                      $query_temp = substr($query_temp,0,-1);
                      $query = $db->query($query_temp);

                      $query_temp="insert into tsc_pack_d2(src_no, line_no, src_line_no, item_code, qty, uom, description, created_datetime, serial_number_pack) values";
                      $row_insert = 1;
                }

                $query_temp.="('".$src_no."','".$i."','".$src_line_no."','".$item_code."','".$qty."','".$uom."', '".$desc."', '".$created_datetime."', '".$row["serial_number_scan"]."'),";

                $row_insert++;
                $i++;
            }
            $query_temp = substr($query_temp,0,-1);
            $query = $db->query($query_temp);
            return true;
      }
      //---

      // 2022-12-03
      function delete_packing_d2_by_doc_no(){
          $db = $this->load->database('default', true);
          $query_temp = "delete FROM tsc_pack_d2 where src_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

}

?>
