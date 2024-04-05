<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_putaway_d2 extends CI_Model{
      var $src_no, $line_no, $src_line_no, $item_code, $qty, $uom, $completely_put, $location_code, $zone_code, $area_code, $rack_code, $bin_code, $created_datetime, $doc_no, $description, $startput_datetime;

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
              "location_code" => $this->location_code,
              "zone_code" => $this->zone_code,
              "area_code" => $this->area_code,
              "rack_code" => $this->rack_code,
              "bin_code" => $this->bin_code,
              "created_datetime" => $this->created_datetime,
              "description" => $this->description,
          );

          $result = $this->db->insert('tsc_put_away_d2', $data);
          if($result) return true; else return false;
      }
      //---

      function get_list_data(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no, line_no, src_line_no, src_no, item_code, qty, uom, completely_put, location_code, zone_code, area_code, rack_code, bin_code, created_datetime, description
          FROM tsc_put_away_d2 t where doc_no='".$this->doc_no."' and src_line_no='".$this->src_line_no."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      function get_list_data_by_doc_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no, line_no, src_line_no, src_no, item_code, qty, uom, completely_put, location_code, zone_code, area_code, rack_code, bin_code, created_datetime, description
          FROM tsc_put_away_d2 t where doc_no='".$this->doc_no."'";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_start_finish_time(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_d2 set startput_datetime='".$this->startput_datetime."', completely_put='".$this->completely_put."' where doc_no='".$this->doc_no."' and src_line_no='".$this->src_line_no."' and line_no='".$this->line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_start_finish_by_docno_srcline(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT min(startput_datetime) as starttime, max(completely_put) as finishtime FROM tsc_put_away_d2 t
          where doc_no='".$this->doc_no."' and src_line_no='".$this->src_line_no."' order by doc_no,src_line_no,line_no;";
          $query = $db->query($query_temp)->row();
          $result["starttime"] = $query->starttime;
          $result["finishtime"] = $query->finishtime;
          return $result;
      }
      //--

      // 2022-11-16 master barcode
      function insert_v2($doc_no, $src_no, $line_no, $src_line_no, $item_code, $qty, $uom, $loc, $zone, $area, $rack, $bin, $datetime, $desc, $sn2){
            $db = $this->load->database('default', true);
            $query_temp = array();

            for($i=0;$i<count($doc_no);$i++){
                $query_temp2 = array(
                    "doc_no" => $doc_no[$i],
                    "src_no" => $src_no[$i],
                    "line_no" => $line_no[$i],
                    "src_line_no" => $src_line_no[$i],
                    "item_code" => $item_code[$i],
                    "qty" => $qty[$i],
                    "uom" => $uom[$i],
                    "location_code" => $loc[$i],
                    "zone_code" => $zone[$i],
                    "area_code" => $area[$i],
                    "rack_code" => $rack[$i],
                    "bin_code" => $bin[$i],
                    "created_datetime" => $datetime[$i],
                    "description" => $desc[$i],
                    "sn2" => $sn2[$i],
                );

                  $query_temp[] = $query_temp2;
            }

            $query = $db->insert_batch('tsc_put_away_d2',$query_temp);
            return true;
      }
      //--

      // 2022-11-17 master barcode
      function get_list_data_by_location_and_item(){
          $db = $this->load->database('default', true);
          $query_temp = "select location_code, zone_code, area_code, rack_code, bin_code, item_code, description, sum(qty) as qty, doc_no, line_no, src_line_no, src_no, created_datetime, completely_put
          FROM tsc_put_away_d2 t where doc_no='".$this->doc_no."'
          group by location_code, zone_code, area_code, rack_code, bin_code, item_code, src_line_no order by location_code, zone_code, area_code, rack_code, bin_code, item_code, doc_no, src_line_no, src_no;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2022-11-17 master barcode
      function update_startput_datetime($doc_no, $src_line_no, $src_no, $item_code, $loc, $zone, $area, $rack, $bin ,$datetime){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_d2 set startput_datetime='".$datetime."' where doc_no='".$doc_no."' and src_line_no='".$src_line_no."'  and item_code='".$item_code."' and location_code='".$loc."' and zone_code='".$zone."' and area_code='".$area."' and rack_code='".$rack."' and bin_code='".$bin."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      // 2022-11-18 master barcode
      function update_completely_datetime($doc_no, $src_line_no, $src_no, $item_code, $loc, $zone, $area, $rack, $bin ,$datetime){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_d2 set completely_put='".$datetime."' where doc_no='".$doc_no."' and src_line_no='".$src_line_no."'  and item_code='".$item_code."' and location_code='".$loc."' and zone_code='".$zone."' and area_code='".$area."' and rack_code='".$rack."' and bin_code='".$bin."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      // 2023-02-10
      function check_if_complete_put_all_not_null($doc_no, $src_line_no, $src_no, $item_code, $loc, $zone, $area, $rack, $bin){
          $db = $this->load->database('default', true);
          $query_temp = "select * from tsc_put_away_d2 where doc_no='".$doc_no."' and src_line_no='".$src_line_no."'  and item_code='".$item_code."' and location_code='".$loc."' and zone_code='".$zone."' and area_code='".$area."' and rack_code='".$rack."' and bin_code='".$bin."';";
          $query = $db->query($query_temp)->row();
          if(is_null($query->put_datetime) or $query->put_datetime == "") return 1;
          else return 0;
      }
      //---
}

?>
