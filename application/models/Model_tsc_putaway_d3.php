<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_putaway_d3 extends CI_Model{
      var $doc_no, $line_no, $src_line_no, $src_no, $item_code, $qty, $uom, $completely_put, $location_code_put, $zone_code_put, $area_code_put, $bin_code_put, $serial_number_put,  $location_code_scan, $zone_code_scan, $area_code_scan, $bin_code_scan, $serial_number_scan, $description, $created_datetime, $src_line_no_d2, $put_datetime;

      function insert(){
          $db = $this->load->database('default', true);
          $data = array(
              "doc_no" => $this->doc_no,
              "src_no" => $this->src_no,
              "line_no" => $this->line_no,
              "src_line_no" => $this->src_line_no,
              "item_code" => $this->item_code,
              "qty" => $this->qty,
              "uom" => $this->uom,
              "location_code_put" => $this->location_code_put,
              "zone_code_put" => $this->zone_code_put,
              "area_code_put" => $this->area_code_put,
              "rack_code_put" => $this->rack_code_put,
              "bin_code_put" => $this->bin_code_put,
              "serial_number_put" => $this->serial_number_put,
              "created_datetime" => $this->created_datetime,
              "description" => $this->description,
              "src_line_no_d2" => $this->src_line_no_d2,
          );

          $result = $this->db->insert('tsc_put_away_d3', $data);
          if($result) return true; else return false;
      }
      //---

      function get_list_data(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no, src_no, line_no, src_line_no, src_line_no_d2, item_code, qty, uom,
          location_code_put, zone_code_put, area_code_put, rack_code_put, bin_code_put,serial_number_put, created_datetime, description, completely_put
          FROM tsc_put_away_d3 t where doc_no='".$this->doc_no."' and src_line_no_d2='".$this->src_line_no_d2."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_start_finish_time(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_d3 set put_datetime='".$this->put_datetime."', completely_put='".$this->completely_put."' where doc_no='".$this->doc_no."' and src_no='".$this->src_no."' and src_line_no_d2='".$this->src_line_no_d2."' and src_line_no='".$this->src_line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      function insert_v2($doc_no, $src_no, $line_no, $src_line_no, $item_code, $qty, $uom, $location, $zone, $area, $rack, $bin, $sn, $datetime, $desc, $src_line_d2, $sn2){
          $db = $this->load->database('default', true);

          $query_temp = "insert into tsc_put_away_d3(doc_no, src_no, line_no, src_line_no, src_line_no_d2, item_code, qty, uom,
          location_code_put,zone_code_put, area_code_put, rack_code_put, bin_code_put, serial_number_put, created_datetime, description, sn2_put ) values";

          for($i=0;$i<count($sn);$i++){
              $query_temp.="('".$doc_no."','".$src_no[$i]."','".$line_no[$i]."','".$src_line_no."',
              '".$src_line_d2."','".$item_code[$i]."','".$qty."', '".$uom[$i]."','".$location[$i]."','".$zone[$i]."',
              '".$area[$i]."','".$rack[$i]."','".$bin[$i]."','".$sn[$i]."','".$datetime."','".$desc[$i]."','".$sn2[$i]."'),";
          }
          $query_temp = substr($query_temp,0,-1);
          $query = $db->query($query_temp);
          return true;

          /*$query_temp = array();
          for($i=0;$i<count($sn);$i++){
              $query_temp2 = array(
                  "doc_no" => $doc_no,
                  "src_no" => $src_no[$i],
                  "line_no" => $line_no[$i],
                  "src_line_no" => $src_line_no,
                  "src_line_no_d2" => $src_line_d2,
                  "item_code" => $item_code[$i],
                  "qty" => $qty,
                  "uom" => $uom[$i],
                  "location_code_put" => $location[$i],
                  "zone_code_put" => $zone[$i],
                  "area_code_put" => $area[$i],
                  "rack_code_put" => $rack[$i],
                  "bin_code_put" => $bin[$i],
                  "serial_number_put" => $sn[$i],
                  "created_datetime" => $datetime,
                  "description" => $desc[$i],
                  "sn2_put" => $sn2[$i],
              );
              $query_temp[] = $query_temp2;
          }

          $query = $db->insert_batch('tsc_put_away_d3',$query_temp);
          return true;*/
      }
      //--

      // 2022-11-17 master barcode
      function get_data_by_sn2_sn_group_by($doc_no, $src_no, $src_line_no, $item_code, $loc, $zone, $area, $rack, $bin){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT item_code, sn2_put, serial_number_put, count(item_code) as qty
              FROM tsc_put_away_d3 t where doc_no='".$doc_no."' and src_no='".$src_no."' and src_line_no='".$src_line_no."' and item_code='".$item_code."'
              and location_code_put='".$loc."' and zone_code_put='".$zone."' and area_code_put='".$area."' and rack_code_put='".$rack."' and bin_code_put='".$bin."'
              group by sn2_put, item_code, doc_no, src_no,src_line_no,src_line_no_d2;";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // 2022-11-17 master barcode
      function update_put_datetime($doc_no, $src_no, $src_line_no, $item_code, $loc, $zone, $area, $rack, $bin, $datetime){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_d3 set put_datetime='".$datetime."' where doc_no='".$doc_no."' and src_no='".$src_no."' and src_line_no='".$src_line_no."' and item_code='".$item_code."'
          and location_code_put='".$loc."' and zone_code_put='".$zone."' and area_code_put='".$area."' and rack_code_put='".$rack."' and bin_code_put='".$bin."'";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      // 2022-11-17 master barcode
      function update_completely_datetime($doc_no, $src_no, $src_line_no, $item_code, $loc, $zone, $area, $rack, $bin, $datetime){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_d3 set completely_put='".$datetime."' where doc_no='".$doc_no."' and src_no='".$src_no."' and src_line_no='".$src_line_no."' and item_code='".$item_code."' and location_code_put='".$loc."' and zone_code_put='".$zone."' and area_code_put='".$area."' and rack_code_put='".$rack."' and bin_code_put='".$bin."'";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      // 2022-11-18 master barcode
      function check_if_complete_put_all_not_null($doc_no, $src_line_no, $src_no, $item_code){
          $db = $this->load->database('default', true);
          $query_temp = "select count(item_code) as total from tsc_put_away_d3 where doc_no='".$doc_no."' and src_line_no='".$src_line_no."'  and item_code='".$item_code."' and src_no='".$src_no."' and completely_put is null;";
          $query = $db->query($query_temp)->row();
          if($query->total == 0) return 1;
          else return 0;
      }
      //---

      // 2023-02-10
      function get_sn_by_location_doc($doc_no, $src_no, $src_line_no, $item_code, $loc, $zone, $area, $rack, $bin){
          $db = $this->load->database('default', true);
          $query_temp = "select serial_number_put,item_code, sn2_put, location_code_put, zone_code_put, area_code_put, rack_code_put, bin_code_put,completely_put from tsc_put_away_d3 where doc_no='".$doc_no."' and src_no='".$src_no."' and src_line_no='".$src_line_no."' and item_code='".$item_code."'
          and location_code_put='".$loc."' and zone_code_put='".$zone."' and area_code_put='".$area."' and rack_code_put='".$rack."' and bin_code_put='".$bin."'";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // 2023-02-10
      function insert_v3($doc_no, $src_no, $line_no, $src_line_no, $item_code, $qty, $uom, $location, $zone, $area, $rack, $bin, $sn, $datetime, $desc, $src_line_d2, $sn2){
          $db = $this->load->database('default', true);

          $query_temp = array();
          for($i=0;$i<count($sn);$i++){
            /*  debug($doc_no."=".$src_no[$i]."=".$line_no[$i]."=".$src_line_no."=".$src_line_d2."=".$item_code[$i]."=".$qty.$uom[$i].
              "=".$location[$i]."=".$zone[$i]."=".$area[$i]."=".$rack[$i]."=".$bin[$i]."=".$sn[$i]."=".$datetime."=".$desc[$i]."=".$sn2[$i]);*/

              $query_temp2 = array(
                  "doc_no" => $doc_no,
                  "src_no" => $src_no[$i],
                  "line_no" => $line_no[$i],
                  "src_line_no" => $src_line_no[$i],
                  "src_line_no_d2" => $src_line_d2[$i],
                  "item_code" => $item_code[$i],
                  "qty" => $qty,
                  "uom" => $uom[$i],
                  "location_code_put" => $location[$i],
                  "zone_code_put" => $zone[$i],
                  "area_code_put" => $area[$i],
                  "rack_code_put" => $rack[$i],
                  "bin_code_put" => $bin[$i],
                  "serial_number_put" => $sn[$i],
                  "created_datetime" => $datetime,
                  "description" => $desc[$i],
                  "sn2_put" => $sn2[$i],
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->insert_batch('tsc_put_away_d3',$query_temp);
          return true;
      }
      //---
}

?>
