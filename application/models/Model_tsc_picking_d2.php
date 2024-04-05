<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_picking_d2 extends CI_Model{
      var $src_no, $line_no, $src_line_no, $item_code, $qty, $uom, $completely_picked, $description, $created_datetime;
      var $location_code_pick, $zone_code_pick, $area_code_pick, $rack_code_pick, $bin_code_pick, $serial_number_pick;
      var $location_code_scan, $zone_code_scan, $area_code_scan, $rack_code_scan, $bin_code_scan, $serial_number_scan;
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
              "location_code_pick" => $this->location_code_pick,
              "zone_code_pick" => $this->zone_code_pick,
              "area_code_pick" => $this->area_code_pick,
              "rack_code_pick" => $this->rack_code_pick,
              "bin_code_pick" => $this->bin_code_pick,
              "serial_number_pick" => $this->serial_number_pick,
              "description" => $this->description,
              "created_datetime" => $this->created_datetime,
          );

          $result = $this->db->insert('tsc_pick_d2', $data);
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
          $query_temp = "update tsc_pick_d2 set pick_datetime='".$this->pick_datetime."', completely_picked='".$this->completely_picked."' where src_line_no='".$this->src_line_no."' and src_no='".$this->src_no."';";
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

      function get_pickig_with_packed_null($whship,$wship_line_no,$limit){

          $db = $this->load->database('default', true);
          $query_temp = "SELECT serial_number_scan, packed, packed_datetime FROM tsc_in_out_bound_d whship
            inner join tsc_pick_d pickd on(whship.doc_no=pickd.src_no and whship.line_no=pickd.src_line_no)
            inner join tsc_pick_d2 pickd2 on(pickd.doc_no=pickd2.src_no and pickd.line_no=pickd2.src_line_no)
            where whship.doc_no='".$whship."' and whship.line_no='".$wship_line_no."' and (packed is null or packed='' or packed='0') order by picked_datetime limit ".$limit.";";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_scan_by_serial_number_v2($location,$zone,$area,$rack,$bin,$sn_scan,$datetime,$sn_pick, $total_row){
          $db = $this->load->database('default', true);

          $query_temp = array();
          for($i=0;$i<$total_row;$i++){
              $query_temp2 = array(
                  "serial_number_scan" => $sn_scan[$i],
                  "location_code_scan" => $location[$i],
                  "zone_code_scan" => $zone[$i],
                  "area_code_scan" => $area[$i],
                  "rack_code_scan" => $rack[$i],
                  "bin_code_scan" => $bin[$i],
                  "serial_number_pick" => $sn_pick[$i],
                  "scan_datetime" => $datetime,
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->update_batch('tsc_pick_d2',$query_temp,'serial_number_pick');
          return true;
      }
      //---

      function insert_v2($src_no,$src_line_no, $item_code, $uom, $desc, $created_datetime, $location_code_pick, $zone_code_pick, $area_code_pick, $rack_code_pick, $bin_code_pick, $result_sn, $qty){

            $db = $this->load->database('default', true);

            $insert_by_number_row = 500;
            $row_insert = 1;
            $i = 1;

            $query_temp="insert into tsc_pick_d2(src_no, line_no, src_line_no, item_code, qty, uom, description, created_datetime, location_code_pick, zone_code_pick, area_code_pick, rack_code_pick, bin_code_pick, serial_number_pick) values";
            foreach($result_sn as $row){
                if($row_insert > $insert_by_number_row ){
                      $query_temp = substr($query_temp,0,-1);
                      $query = $db->query($query_temp);

                      $query_temp="insert into tsc_pick_d2(src_no, line_no, src_line_no, item_code, qty, uom, description, created_datetime, location_code_pick, zone_code_pick, area_code_pick, rack_code_pick, bin_code_pick, serial_number_pick) values";
                      $row_insert = 1;
                }

                $query_temp.="('".$src_no."','".$i."','".$src_line_no."','".$item_code."','".$qty."','".$uom."', '".$desc."', '".$created_datetime."','".$location_code_pick."', '".$zone_code_pick."', '".$area_code_pick."',
                '".$rack_code_pick."', '".$bin_code_pick."', '".$row["serial_number"]."'),";

                $row_insert++;
                $i++;
            }
            $query_temp = substr($query_temp,0,-1);
            $query = $db->query($query_temp);
            return true;
      }
      //---

      function update_packed_by_serial_number_v2($result_sn,$packed,$datetime){
          $db = $this->load->database('default', true);

          $query_temp = array();
          foreach($result_sn as $row){
              $query_temp2 = array(
                  "packed" => $packed,
                  "packed_datetime" => $datetime,
                  "serial_number_scan" => $row["serial_number_scan"],
              );

              $query_temp[] = $query_temp2;
          }

          $db->update_batch('tsc_pick_d2',$query_temp,'serial_number_scan');
          return true;

      }
      //---

      function get_list_data_by_docno_srclineno($limit){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_pick_d2 t where src_no='".$this->src_no."' and src_line_no='".$this->src_line_no."'
          order by serial_number_pick desc limit ".$limit." ;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function delete_pick_d2_by_srcno_lineno_srclineno_itemcode_serialnumberpick(){
          $db = $this->load->database('default', true);
          $query_temp = "delete from tsc_pick_d2 where
          src_no='".$this->src_no."' and line_no='".$this->line_no."' and src_line_no='".$this->src_line_no."'
          and item_code='".$this->item_code."' and serial_number_pick='".$this->serial_number_pick."'
          ;";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_list_data_by_doc_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT src_no, src_no, line_no, src_line_no, item_code, qty, uom,
          location_code_pick, zone_code_pick, area_code_pick, rack_code_pick, bin_code_pick,serial_number_pick,
          created_datetime, description, completely_picked
          FROM tsc_pick_d2 t where src_no='".$this->doc_no."'";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2022-11-11
      function update_scan_by_serial_number_v3($location,$zone,$area,$rack,$bin,$sn_scan,$datetime,$sn_pick, $total_row, $line_no, $src_line_no, $pick_doc, $sn2_scan){
          $db = $this->load->database('default', true);

          $update_rows = array();
          $multipleWhere = array();

          for($i=0;$i<$total_row;$i++){
              $update_rows_temp = array(
                  "serial_number_scan" => $sn_scan[$i],
                  "location_code_scan" => $location[$i],
                  "zone_code_scan" => $zone[$i],
                  "area_code_scan" => $area[$i],
                  "rack_code_scan" => $rack[$i],
                  "bin_code_scan" => $bin[$i],
                  "scan_datetime" => $datetime,
                  "sn2_scan" => $sn2_scan[$i],
              );
              $update_rows[] = $update_rows_temp;

              $multipleWhere_temp = array('src_no' => $pick_doc[$i], 'line_no' => $line_no[$i], 'src_line_no' => $src_line_no[$i] );
              $multipleWhere[] = $multipleWhere_temp;
          }

          for($i=0;$i<count($multipleWhere);$i++){
            $this->db->where($multipleWhere[$i]);
            $this->db->update('tsc_pick_d2', $update_rows[$i]);
          }

          return true;
      }
      //---

      // 2023-02-10
      function insert_v3($src_no,$src_line_no, $item_code, $uom, $desc, $created_datetime, $location_code_pick, $zone_code_pick, $area_code_pick, $rack_code_pick, $bin_code_pick, $sn, $sn2, $qty, $line_no, $master_barcode){
          $db = $this->load->database('default', true);
          $query_temp = array();
          for($i=0;$i<count($src_no);$i++){
              $query_temp2 = array(
                  "src_no" => $src_no[$i],
                  "line_no" => $line_no[$i],
                  "src_line_no" => $src_line_no[$i],
                  "item_code" => $item_code[$i],
                  "description" => $desc[$i],
                  "qty" => $qty,
                  "uom" => $uom[$i],
                  "location_code_pick" => $location_code_pick[$i],
                  "zone_code_pick" => $zone_code_pick[$i],
                  "area_code_pick" => $area_code_pick[$i],
                  "rack_code_pick" => $rack_code_pick[$i],
                  "bin_code_pick" => $bin_code_pick[$i],
                  "serial_number_pick" => $sn[$i],
                  "sn2_pick" => $sn2[$i],
                  "created_datetime" => $created_datetime[$i],
                  "master_barcode" => $master_barcode[$i]
              );
              $query_temp[] = $query_temp2;
          }

          $query = $db->insert_batch('tsc_pick_d2',$query_temp);
          return true;
      }
      //---

      // 2023-02-10
      function get_list_data_with_location_sn2_pcs_conv(){
          $db = $this->load->database('default', true);
          /*$query_temp = "select tbl_item.item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick, serial_number_pick,tbl_item.sn2_pick, qty, item_conv.pcs
            from (
            SELECT item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick,serial_number_pick,sn2_pick,count(sn2_pick) as qty
            FROM tsc_pick_d2 t where src_no='".$this->doc_no."' group by item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick,sn2_pick) as tbl_item
            left join mst_item_uom_conv item_conv on(item_conv.item_code=tbl_item.item_code) order by item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick;";
          */

          $query_temp = "select tbl_item.item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick, serial_number_pick,tbl_item.sn2_pick, qty, item_conv.pcs
            from (
            SELECT item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick,serial_number_pick,sn2_pick,count(sn2_pick) as qty
            FROM tsc_pick_d2 t where src_no='".$this->doc_no."' group by item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick,sn2_pick) as tbl_item
            left join mst_item_uom_conv item_conv on(item_conv.item_code=tbl_item.item_code) where qty=pcs

            union

            SELECT item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick,serial_number_pick,sn2_pick,'1',''
            FROM tsc_pick_d2 t where src_no='".$this->doc_no."' and sn2_pick in (select tbl_item.sn2_pick
            from (
            SELECT item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick,serial_number_pick,sn2_pick,count(sn2_pick) as qty
            FROM tsc_pick_d2 t where src_no='".$this->doc_no."' group by item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick,sn2_pick) as tbl_item
            left join mst_item_uom_conv item_conv on(item_conv.item_code=tbl_item.item_code) where qty!=pcs order by tbl_item.item_code,location_code_pick, zone_code_pick, area_code_pick,rack_code_pick,bin_code_pick);";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      function get_total_qty(){
          $db = $this->load->database('default', true);
          $query_temp = "select count(d2.item_code) as qty_shipped FROM tsc_pick_d2 d2 inner join tsc_pick_d d on(d2.src_no=d.doc_no and d2.src_line_no=d.line_no) where d.src_no = '".$this->doc_no."';";
          $query = $db->query($query_temp)->row();
          return $query->qty_shipped;
      }
}

?>
