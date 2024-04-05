<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_transferbin_d extends CI_Model{
      var $doc_no, $line_no, $item_code, $qty, $uom, $location_code_from, $zone_code_from, $area_code_from, $rack_code_from, $bin_code_from,
      $location_code_to, $zone_code_to, $area_code_to, $rack_code_to, $bin_code_to, $desc, $created_datetime, $picked_datetime, $putaway_datetime, $confirmed;

      function insert(){
          $db = $this->load->database('default', true);
          $data = array(
              "doc_no" => $this->doc_no,
              "line_no" => $this->line_no,
              "item_code" => $this->item_code,
              "qty" => $this->qty,
              "uom" => $this->uom,
              "location_code_from" => $this->location_code_from,
              "zone_code_from" => $this->zone_code_from,
              "area_code_from" => $this->area_code_from,
              "rack_code_from" => $this->rack_code_from,
              "bin_code_from" => $this->bin_code_from,
              "location_code_to" => $this->location_code_to,
              "zone_code_to" => $this->zone_code_to,
              "area_code_to" => $this->area_code_to,
              "rack_code_to" => $this->rack_code_to,
              "bin_code_to" => $this->bin_code_to,
              "description" => $this->desc,
              "created_datetime" => $this->created_datetime,
              "confirmed" => 0,
          );

          $result = $this->db->insert('tsc_transferbin_d', $data);
          if($result) return true; else return false;
      }

      //---

      function get_list_by_doc_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_transferbin_d t where doc_no='".$this->doc_no."' order by location_code_from, zone_code_from, area_code_from, rack_code_from, bin_code_from ;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_list_by_doc_no_and_line_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_transferbin_d t where doc_no='".$this->doc_no."' and line_no='".$this->line_no."' order by location_code_from, zone_code_from, area_code_from, rack_code_from, bin_code_from ;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_pick_datetime(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_d set pick_datetime='".$this->pick_datetime."' where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      function update_put_datetime(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_d set putaway_datetime='".$this->putaway_datetime."' where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      function check_already_pick_and_put(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_transferbin_d t where doc_no='".$this->doc_no."' and (pick_datetime is null or putaway_datetime is null);";
          $query = $db->query($query_temp);
          $result = $query->result_array();

          if(count($result) > 0 ) return false;
          else return true;
      }
      //---

      function update_confirmed_by_docno_and_line_no(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_d set confirmed='".$this->confirmed."' where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      function check_all_confirmed(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_transferbin_d t where doc_no='".$this->doc_no."' and confirmed=0;";
          $query = $db->query($query_temp);
          $result = $query->result_array();
          if(count($result) > 0 ) return false;
          else return true;
      }
      //---

      function insert_v2($doc_no,$item_code,$qty,$uom,$from_loc,$from_zone, $from_area, $from_rack, $from_bin, $to_loc,$to_zone, $to_area, $to_rack, $to_bin,$desc,$created_datetime,$line_no){

        $db = $this->load->database('default', true);

        $query_temp = array();
        for($i=0;$i<count($item_code);$i++){
            $query_temp2 = array(
                "doc_no" => $doc_no,
                "line_no" => $line_no[$i],
                "item_code" => $item_code[$i],
                "qty" => $qty[$i],
                "uom" => $uom[$i],
                "location_code_from" => $from_loc[$i],
                "zone_code_from" => $from_zone[$i],
                "area_code_from" => $from_area[$i],
                "rack_code_from" => $from_rack[$i],
                "bin_code_from" => $from_bin[$i],
                "location_code_to" => $to_loc[$i],
                "zone_code_to" => $to_zone[$i],
                "area_code_to" => $to_area[$i],
                "rack_code_to" => $to_rack[$i],
                "bin_code_to" => $to_bin[$i],
                "description" => $desc[$i],
                "created_datetime" => $created_datetime,
                "confirmed" => 0,
            );

            $query_temp[] = $query_temp2;
        }

        $query = $db->insert_batch('tsc_transferbin_d',$query_temp);
        return true;
      }
      //--

      // 2023-02-14
      function update_pick_datetime2(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_d set pick_datetime='".$this->pick_datetime."' where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      // 2023-02-14
      function update_put_datetime2(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_d set putaway_datetime='".$this->putaway_datetime."' where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      // 2023-10-19
      function insert_v3($doc_no,$item_code,$qty,$uom,$from_loc,$from_zone, $from_area, $from_rack, $from_bin, $to_loc,$to_zone, $to_area, $to_rack, $to_bin,$desc,$created_datetime,$line_no, $confirmed){

        $db = $this->load->database('default', true);

        $query_temp = array();
        for($i=0;$i<count($item_code);$i++){
            $query_temp2 = array(
                "doc_no" => $doc_no,
                "line_no" => $line_no[$i],
                "item_code" => $item_code[$i],
                "qty" => $qty[$i],
                "uom" => $uom[$i],
                "location_code_from" => $from_loc[$i],
                "zone_code_from" => $from_zone[$i],
                "area_code_from" => $from_area[$i],
                "rack_code_from" => $from_rack[$i],
                "bin_code_from" => $from_bin[$i],
                "location_code_to" => $to_loc[$i],
                "zone_code_to" => $to_zone[$i],
                "area_code_to" => $to_area[$i],
                "rack_code_to" => $to_rack[$i],
                "bin_code_to" => $to_bin[$i],
                "description" => $desc[$i],
                "created_datetime" => $created_datetime,
                "confirmed" => $confirmed,
            );

            $query_temp[] = $query_temp2;
        }

        $query = $db->insert_batch('tsc_transferbin_d',$query_temp);
        return true;
      }
      //--

      // 2023-10-26
      function get_doc_d_d2($doc_no){
          $db = $this->load->database('default', true);
          $query_temp = "select serial_number,location_code_to, zone_code_to, area_code_to, rack_code_to, bin_code_to,
          location_code_from, zone_code_from, area_code_from, rack_code_from, bin_code_from, d.item_code,d.line_no,d.description, d.doc_no FROM tsc_transferbin_d d inner join tsc_transferbin_d2 d2 on(d.doc_no=d2.doc_no and d.line_no=d2.src_line_no) where d.doc_no='".$doc_no."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      //2023-10-26
      function update_confirmed_by_docno(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_d set confirmed='".$this->confirmed."' where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--
}

?>
