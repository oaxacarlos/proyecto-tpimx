<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Tsc_item_sn extends CI_Model{
      var $item_code, $serial_number, $location_code, $zone_code, $area_code, $rack_code, $bin_code, $created_datetime, $put_away_datetime;
      var $picked_datetime, $status, $prefix, $sn2;

      function call_store_procedure_newsn(){
        $db = $this->load->database('default', true);
        $query_temp = "call NEWSN('".$this->prefix."','".$this->item_code."','".$this->created_datetime."')";

        $query = $db->query($query_temp)->row();

        return $query->trsc_no;
      }
      //---

      function update_status(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_item_sn set statuss='".$this->status."' where serial_number='".$this->serial_number."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      function update_location(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_item_sn set location_code='".$this->location_code."', zone_code='".$this->zone_code."', area_code='".$this->area_code."', rack_code='".$this->rack_code."', bin_code='".$this->bin_code."', put_away_datetime='".$this->put_away_datetime."', statuss='".$this->status."'  where serial_number='".$this->serial_number."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function list_item_location_by_item_code_and_status($whs){
          $db = $this->load->database('default', true);
          /*$query_temp = "SELECT item_code,location_code,zone_code, area_code, rack_code,bin_code,count(item_code) as total
          FROM tsc_item_sn t where item_code='".$this->item_code."' and statuss='".$this->status."'
          group by item_code, location_code,zone_code, area_code, rack_code, bin_code
          order by item_code, location_code,zone_code, area_code, rack_code, bin_code";*/

          $query_temp = "select item_code,location_code,zone_code, area_code, rack_code,bin_code,count(item_code) as total from(
            SELECT item_code,location_code,zone_code, area_code, rack_code,bin_code, serial_number
            FROM tsc_item_sn t where item_code='".$this->item_code."' and statuss='".$this->status."' and location_code='".$whs."' order by serial_number) as tbl
            group by item_code, location_code,zone_code, area_code, rack_code, bin_code";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_list_sn_with_status_limit($limit){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT serial_number,sn2 FROM tsc_item_sn t
          where statuss='".$this->status."' and item_code='".$this->item_code."' and location_code='".$this->location_code."' and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."' and bin_code='".$this->bin_code."'
          order by created_datetime,serial_number limit ".$limit.";";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_picked_datetime(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_item_sn set picked_datetime='".$this->picked_datetime."' where serial_number='".$this->serial_number."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_data_by_serial_number(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT item_code, serial_number,statuss, location_code, zone_code, area_code, rack_code, bin_code,sn2
          FROM tsc_item_sn t where serial_number='".$this->serial_number."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_status_picked_datetime(){
          $db = $this->load->database('default', true);

          if($this->picked_datetime == 'NULL'){
              $query_temp = "update tsc_item_sn set statuss='".$this->status."', picked_datetime=".$this->picked_datetime." where serial_number='".$this->serial_number."';";
          }
          else{
              $query_temp = "update tsc_item_sn set statuss='".$this->status."', picked_datetime='".$this->picked_datetime."' where serial_number='".$this->serial_number."';";
          }

          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_qty_by_loc_zone_area_rack_bin_status(){
          $db = $this->load->database('default', true);
          $query_temp = " select location_code, zone_code, area_code, rack_code, bin_code,item_code, total, name as description, uom
            from(
            SELECT location_code, zone_code, area_code, rack_code, bin_code,item_code, count(item_code) as total
            FROM tsc_item_sn t where statuss='".$this->status."' and location_code='".$this->location_code."' and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."' and bin_code='".$this->bin_code."'
            group by location_code, zone_code, area_code, rack_code, bin_code,item_code order by item_code ) as tbl_item
            left join mst_item itm on(tbl_item.item_code=itm.code);";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function check_if_qty_enough($qty){
          $db = $this->load->database('default', true);
          $query_temp = "select if(qty >= ".$qty.",1,0) as qty_ok from( SELECT item_code,count(item_code) as qty
            FROM tsc_item_sn t where statuss='1' and item_code='".$this->item_code."' and location_code='".$this->location_code."'
            and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."'
            and bin_code='".$this->bin_code."') as tbl;";

          $query = $db->query($query_temp)->row();
          return $query->qty_ok;
      }
      //---

      function get_list_sn_with_status_limit_order_by_serial_number($limit){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT serial_number FROM tsc_item_sn t
          where statuss='".$this->status."' and item_code='".$this->item_code."' and location_code='".$this->location_code."' and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."' and bin_code='".$this->bin_code."'
          order by serial_number limit ".$limit.";";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_location_and_status(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_item_sn set location_code='".$this->location_code."', zone_code='".$this->zone_code."', area_code='".$this->area_code."', rack_code='".$this->rack_code."', bin_code='".$this->bin_code."', statuss='".$this->status."'  where serial_number='".$this->serial_number."';";

          $query = $db->query($query_temp);
          return true;
      }
      //---

      function list_item_location_by_item_code_and_status_one_two(){
          $db = $this->load->database('default', true);
          /*$query_temp = "SELECT item_code,location_code,zone_code, area_code, rack_code,bin_code,count(item_code) as total
          FROM tsc_item_sn t where item_code='".$this->item_code."' and statuss='".$this->status."'
          group by item_code, location_code,zone_code, area_code, rack_code, bin_code
          order by item_code, location_code,zone_code, area_code, rack_code, bin_code";*/

          $query_temp = "select * from(
          select item_code,location_code,zone_code, area_code, rack_code,bin_code,statuss,count(item_code) as total from(
                      SELECT item_code,location_code,zone_code, area_code, rack_code,bin_code, serial_number, statuss
                      FROM tsc_item_sn t where item_code='".$this->item_code."' and statuss in ('0','1','2','4') order by serial_number) as tbl
                      group by item_code, location_code,zone_code, area_code, rack_code, bin_code, statuss
          union
          SELECT d.item_code,'' as location_code,'' as zone_code,'' as area_code,'' as rack_code,'' as bin_code,'-1' as statuss,sum(d.qty) as total
          from tsc_received_h h
          inner join tsc_received_d d on(d.doc_no=h.doc_no)
          where d.item_code='".$this->item_code."' and status_h in ('3','4')) as tbl where item_code is not null
          order by statuss,location_code,zone_code, area_code, rack_code, bin_code";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_location_v3($data){
          $db = $this->load->database('default', true);

          $query_temp = array();
          foreach($data as $row){
              $query_temp2 = array(
                  "serial_number" => $row['serial_number_put'],
                  "location_code" => $row['location_code_put'],
                  "zone_code" => $row['zone_code_put'],
                  "area_code" => $row['area_code_put'],
                  "rack_code" => $row['rack_code_put'],
                  "bin_code" => $row['bin_code_put'],
                  "put_away_datetime" => $row['completely_put'],
                  "statuss" => '1',
                  "picked_datetime" => null, // 2023-03-08 WH3
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->update_batch('tsc_item_sn',$query_temp,'serial_number');
          return true;
      }
      //---

      function update_status_v2($serial_number, $status){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_item_sn set statuss='".$status."' where serial_number in ( ";

        foreach($serial_number as $row){
            $query_temp.="'".$row["serial_number"]."',";
        }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=")";

        $query = $db->query($query_temp);
        return true;
      }
      //---

      function update_status_v3($serial_number, $status){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_item_sn set statuss='".$status."' where serial_number in ( ";

          foreach($serial_number as $row){
              $query_temp.="'".$row."',";
          }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=" ) ";

          $query = $db->query($query_temp);
          return true;
      }
      //---

      function update_location_and_status_v2($data, $location, $zone, $area, $rack, $bin,$status){
          $db = $this->load->database('default', true);

          $query_temp = array();
          foreach($data as $row){
              $query_temp2 = array(
                  "serial_number" => $row['serial_number'],
                  "location_code" => $location,
                  "zone_code" => $zone,
                  "area_code" => $area,
                  "rack_code" => $rack,
                  "bin_code" => $bin,
                  "statuss" => $status,
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->update_batch('tsc_item_sn',$query_temp,'serial_number');
          return true;
      }
      //---

      function update_status_picked_datetime_v2($status,$datetime,$sn_scan,$total_row){
          $db = $this->load->database('default', true);

          $query_temp = array();
          for($i=0;$i<$total_row;$i++){
              $query_temp2 = array(
                  "serial_number" => $sn_scan[$i],
                  "picked_datetime" => $datetime,
                  "statuss" => $status,
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->update_batch('tsc_item_sn',$query_temp,'serial_number');
          return true;
      }
      //---

      function update_picked_datetime_v2($datetime, $serial_number){
          $db = $this->load->database('default', true);

          $query_temp = array();
          foreach($serial_number as $row){
              $query_temp2 = array(
                  "serial_number" => $row["serial_number_pick"],
                  "picked_datetime" => $datetime,
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->update_batch('tsc_item_sn',$query_temp,'serial_number');
          return true;
      }
      //---

      function insert_v2($item_code, $sn, $datetime){
          $db = $this->load->database('default', true);

          $query_temp = array();
          foreach($sn as $row){
              $query_temp2 = array(
                  "item_code" => $item_code,
                  "serial_number" => $row,
                  "created_datetime" => $datetime,
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->insert_batch('tsc_item_sn',$query_temp);
          return true;
      }
      //---

      function get_data_by_serial_number_and_item_code(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT item_code, serial_number,statuss, location_code, zone_code, area_code, rack_code, bin_code
          FROM tsc_item_sn t where serial_number='".$this->serial_number."' and item_code='".$this->item_code."' and statuss='1';";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // 2022-11-05
      function get_data_by_item_code_and_status_and_loc(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT item_code, serial_number,statuss, location_code, zone_code, area_code, rack_code, bin_code
          FROM tsc_item_sn t where item_code='".$this->item_code."' and statuss='".$this->status."'
          and location_code='".$this->location_code."'
          and zone_code='".$this->zone_code."'
          and area_code = '".$this->area_code."'
          and rack_code='".$this->rack_code."'
          and bin_code='".$this->bin_code."';";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // 2022-11-11
      function get_data_by_serial_number_and_status($in_status){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT item_code, serial_number,statuss, location_code, zone_code, area_code, rack_code, bin_code
          FROM tsc_item_sn t where serial_number='".$this->serial_number."' and statuss in(".$in_status.");";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2022-11-14 master barcode
      function insert_v2_master_barcode($item_code, $sn, $datetime, $sn_master){
          $db = $this->load->database('default', true);

          $query_temp = array();
          $j=0;
          foreach($sn as $row){
              $query_temp2 = array(
                  "item_code" => $item_code,
                  "serial_number" => $row,
                  "created_datetime" => $datetime,
                  "sn2" => $sn_master[$j],
              );

              $query_temp[] = $query_temp2;
              $j++;
          }

          $query = $db->insert_batch('tsc_item_sn',$query_temp);
          return true;
      }
      //---
      //--

      // 2022-12-07 master barcode
      function get_sn_sn2_already_pcs_with_limit($item_code, $qty, $loc, $zone, $area, $rack, $bin){
          $db = $this->load->database('default', true);
          $query_temp = "select * FROM tsc_item_sn t where sn2 in(
            select sn2 from (
            SELECT item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code,
            count(item_code) as qty
            FROM tsc_item_sn t where item_code='".$item_code."' and statuss='1' and location_code='".$loc."' and zone_code='".$zone."' and area_code='".$area."' and rack_code='".$rack."' and bin_code='".$bin."' group by item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code) as tbl_sn
            left join mst_item_uom_conv conv on(conv.item_code = tbl_sn.item_code) where qty < pcs) and statuss='1' order by serial_number limit ".$qty." ;";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2022-12-07 master barcode
      function get_sn_sn2_still_have_master_code($item_code, $qty, $loc, $zone, $area, $rack, $bin){
          $db = $this->load->database('default', true);
          $query_temp="select sn2 from (
            SELECT item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code,
            count(item_code) as qty
            FROM tsc_item_sn t where item_code='".$item_code."' and statuss='1' and location_code='".$loc."' and zone_code='".$zone."' and area_code='".$area."' and rack_code='".$rack."' and bin_code='".$bin."' group by item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code) as tbl_sn
            left join mst_item_uom_conv conv on(conv.item_code = tbl_sn.item_code) where qty = pcs order by sn2 limit ".$qty.";";

          $query = $db->query($query_temp);
          return $query->result_array();

      }
      //---

      // 2022-12-07 master barcode
      function get_sn_by_sn2($sn2){
          $db = $this->load->database('default', true);
          $query_temp = "select * from tsc_item_sn where sn2 in ( ";
          foreach($sn2 as $row){ $query_temp.= "'".$row."',"; }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=" );";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_list_sn_with_status_limit_exclude_sn2($limit,$sn_exclude){
          $db = $this->load->database('default', true);

          $query_temp = "SELECT serial_number,sn2 FROM tsc_item_sn t
          where statuss='".$this->status."' and item_code='".$this->item_code."' and location_code='".$this->location_code."' and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."' and bin_code='".$this->bin_code."' ";

          if(count($sn_exclude) > 0){
            $query_temp.=" and sn2 not in( ";
            foreach($sn_exclude as $row){ $query_temp.="'".$row."',"; }
            $query_temp = substr($query_temp,0,-1);
            $query_temp.=" ) ";
          }

          $query_temp.=" order by created_datetime,serial_number limit ".$limit.";";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2022-12-16 master barcode
      function get_data_by_sn_sn2_and_status($in_status){
          $db = $this->load->database('default', true);

          if(strpos($this->serial_number, "M-") !== false){
              $query_temp = "select item_code, serial_number,statuss, location_code, zone_code, area_code, rack_code, bin_code,sn2
              FROM tsc_item_sn t where (serial_number='".$this->serial_number."' or sn2='".$this->sn2."') and statuss in(".$in_status.");";
          } else{
              $query_temp = "select item_code, serial_number,statuss, location_code, zone_code, area_code, rack_code, bin_code,sn2
              FROM tsc_item_sn t where (serial_number='".$this->serial_number."') and statuss in(".$in_status.");";
          }


          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-01-20 master barcode
      function get_data_sn2_sn_by_item_rack($item_code,$loc, $zone, $area, $rack, $bin){
        $db = $this->load->database('default', true);

        $query_temp = " select tbl_sn.item_code, sn2, serial_number,location_code, zone_code, area_code, rack_code, bin_code, qty, ctn, pcs, name from(
          select tbl_sn.item_code, sn2,'0' as serial_number,location_code, zone_code, area_code, rack_code, bin_code, qty, ctn, pcs from (
          SELECT item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code,
          count(item_code) as qty
          FROM tsc_item_sn t where item_code='".$item_code."' and statuss='1'
          and location_code='".$loc."' and zone_code='".$zone."' and area_code='".$area."' and rack_code='".$rack."'
          and bin_code='".$bin."' group by item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code) as tbl_sn
          left join mst_item_uom_conv conv on(conv.item_code = tbl_sn.item_code) where qty = pcs
          union
          select item_code, sn2, serial_number,location_code, zone_code, area_code, rack_code, bin_code,'1' as qty,'0' as ctn,'0' as pcs
          FROM tsc_item_sn t where sn2 in(
                      select sn2 from (
                      SELECT item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code,
                      count(item_code) as qty
                      FROM tsc_item_sn t
                      where item_code='".$item_code."' and statuss='1'
                      and location_code='".$loc."' and zone_code='".$zone."' and area_code='".$area."' and rack_code='".$rack."'
          and bin_code='".$bin."' group by item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code) as tbl_sn
                      left join mst_item_uom_conv conv on(conv.item_code = tbl_sn.item_code) where qty < pcs) and statuss='1' order by qty desc,sn2, serial_number) as tbl_sn
          left join mst_item item on(item.code=tbl_sn.item_code)";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      // 2023-01-20 master barcode
      function get_total_qty_by_sn2($sn2){
          $db = $this->load->database('default', true);
          $query_temp = "select count(item_code) as qty FROM tsc_item_sn t where statuss in('1') and sn2 in( ";

          foreach($sn2 as $row){ $query_temp.="'".$row."',"; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" );";
          $query = $db->query($query_temp)->row();
          return $query->qty;
      }
      //---

      // 2023-01-20 master barcode
      function get_total_qty_by_serial_number($sn){
          $db = $this->load->database('default', true);
          $query_temp = "select count(item_code) as qty FROM tsc_item_sn t where statuss in('1') and serial_number in( ";

          foreach($sn as $row){ $query_temp.="'".$row."',"; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" );";
          $query = $db->query($query_temp)->row();
          return $query->qty;
      }
      //---

      // 2023-01-20 master barcode
      function get_sn2_not_completed($sn2){
          $db = $this->load->database('default', true);

          $query_temp = "select tbl_sn2.item_code, sn2, qty, pcs
              from(
              SELECT item_code,sn2, count(sn2) as qty FROM tsc_item_sn t where statuss in('1') and sn2 in( ";

          foreach($sn2 as $row){ $query_temp.="'".$row."',"; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" ) group by item_code,sn2) as tbl_sn2
          inner join mst_item_uom_conv as conv on(conv.item_code = tbl_sn2.item_code) where qty!=pcs;";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // 2023-01-20 master barcode
      function get_sn_not_status_available($sn){
          $db = $this->load->database('default', true);

          $query_temp = "select serial_number FROM tsc_item_sn t where serial_number in( ";

          foreach($sn as $row){ $query_temp.="'".$row."',"; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" ) and statuss!='1';";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-02-01
      function get_sn_by_sn2_v2($sn2){
          $db = $this->load->database('default', true);
          $query_temp = "select * from tsc_item_sn where sn2 = '".$sn2."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-02-01
      function insert_v2_with_value($item_code, $sn, $datetime, $valuee){
          $db = $this->load->database('default', true);

          $query_temp = array();
          foreach($sn as $row){
              $query_temp2 = array(
                  "item_code" => $item_code,
                  "serial_number" => $row,
                  "created_datetime" => $datetime,
                  "valuee" => $valuee,
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->insert_batch('tsc_item_sn',$query_temp);
          return true;
      }
      //---

      // 2022-11-14 master barcode
      function insert_v2_master_barcode_with_value($item_code, $sn, $datetime, $sn_master, $valuee){
          $db = $this->load->database('default', true);

          $query_temp = array();
          $j=0;
          foreach($sn as $row){
              $query_temp2 = array(
                  "item_code" => $item_code,
                  "serial_number" => $row,
                  "created_datetime" => $datetime,
                  "sn2" => $sn_master[$j],
                  "valuee" => $valuee,
              );

              $query_temp[] = $query_temp2;
              $j++;
          }

          $query = $db->insert_batch('tsc_item_sn',$query_temp);
          return true;
      }
      //---

      // 2023-02-01
      function get_sn_with_limit_without_location($item_code, $status, $limit){
          $db = $this->load->database('default', true);
          $query_temp = "select serial_number, valuee from tsc_item_sn where item_code='".$item_code."' and statuss='".$status."' order by serial_number limit ".$limit.";";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-03-09
      function get_item_by_location(){
          $db = $this->load->database('default', true);
          $query_temp = "select item_code, statuss, name, qty
            from (
            SELECT item_code, statuss, count(item_code) as qty
            FROM tsc_item_sn t where location_code='".$this->location_code."' and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."' and bin_code='".$this->bin_code."' and statuss in('1','2') group by item_code, statuss) as tbl_item_sn
            left join mst_item_sn_status sn_status on(tbl_item_sn.statuss = sn_status.code)";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2022-12-07 master barcode
      function get_sn_by_sn2_ver2($sn2){
          $db = $this->load->database('default', true);
          $query_temp = "select *from (select * from tsc_item_sn where sn2 in ( ";
          foreach($sn2 as $row){ $query_temp.= "'".$row."',"; }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=" ) ) as tbl_item_sn inner join mst_item_sn_status sn_status on(sn_status.code=tbl_item_sn.statuss) ;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-03-09
      function get_sn2_item_by_sn2($sn2){
        $db = $this->load->database('default', true);
        $query_temp = "select sn2,item_code FROM tsc_item_sn t where sn2='".$sn2."' group by sn2, item_code;";
        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //--

      // 2023-05-03 master barcode
      function get_sn_sn2_already_pcs_with_limit_not_certain_rack($item_code, $qty, $loc, $zone, $area, $rack, $bin){
          $db = $this->load->database('default', true);
          $query_temp = "select * FROM tsc_item_sn t where sn2 in(
            select sn2 from (
            SELECT item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code,
            count(item_code) as qty
            FROM tsc_item_sn t where item_code='".$item_code."' and statuss='1' and location_code!='".$loc."' and zone_code!='".$zone."' and area_code!='".$area."' and rack_code!='".$rack."' and bin_code!='".$bin."' group by item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code) as tbl_sn
            left join mst_item_uom_conv conv on(conv.item_code = tbl_sn.item_code) where qty < pcs) and statuss='1' order by serial_number limit ".$qty." ;";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // WH3 2023-05-17
      function get_qty_by_location_item($item_code,$status){
          $db = $this->load->database('default', true);
          $query_temp = "select item_code,location_code,count(item_code) as qty FROM tsc_item_sn t where item_code='".$item_code."' and statuss='".$status."'  group by item_code,location_code;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // WH3 2023-05-18
      function get_sn2_by_sn_bulk($sn){
          $db = $this->load->database('default', true);

          $query_temp = "select serial_number,sn2 FROM tsc_item_sn t where serial_number in( ";

          foreach($sn as $row){ $query_temp.="'".$row."',"; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" );";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // 2023-07-25 master barcode
      function get_data_sn2_sn_by_item_rack_ver2($item_code,$loc, $zone, $area, $rack, $bin){
        $db = $this->load->database('default', true);

        $query_temp = " select tbl_sn.item_code, sn2, serial_number,location_code, zone_code, area_code, rack_code, bin_code, qty, ctn, pcs, name from(
          select tbl_sn.item_code, sn2,serial_number as serial_number,location_code, zone_code, area_code, rack_code, bin_code, qty, ctn, pcs from (
          SELECT item_code, sn2,serial_number, location_code, zone_code, area_code, rack_code, bin_code,
          count(item_code) as qty
          FROM tsc_item_sn t where item_code='".$item_code."' and statuss='1'
          and location_code='".$loc."' and zone_code='".$zone."' and area_code='".$area."' and rack_code='".$rack."'
          and bin_code='".$bin."' group by item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code) as tbl_sn
          left join mst_item_uom_conv conv on(conv.item_code = tbl_sn.item_code) where qty = pcs
          union
          select item_code, sn2, serial_number,location_code, zone_code, area_code, rack_code, bin_code,'1' as qty,'0' as ctn,'0' as pcs
          FROM tsc_item_sn t where sn2 in(
                      select sn2 from (
                      SELECT item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code,
                      count(item_code) as qty
                      FROM tsc_item_sn t
                      where item_code='".$item_code."' and statuss='1'
                      and location_code='".$loc."' and zone_code='".$zone."' and area_code='".$area."' and rack_code='".$rack."'
          and bin_code='".$bin."' group by item_code, sn2, location_code, zone_code, area_code, rack_code, bin_code) as tbl_sn
                      left join mst_item_uom_conv conv on(conv.item_code = tbl_sn.item_code) where qty < pcs) and statuss='1' order by qty desc,sn2, serial_number) as tbl_sn
          left join mst_item item on(item.code=tbl_sn.item_code)";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      // 2023-10-13
      function get_item_sn2_by_rack(){
          $db = $this->load->database('default', true);
          $query_temp = "select sn2, statuss,sts.name as sts_name, count(sn2) as qty
          FROM tsc_item_sn t inner join mst_item_sn_status sts on(sts.code=t.statuss)
          where location_code='".$this->location_code."' and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."' and bin_code='".$this->bin_code."' group by sn2, statuss;";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_data_by_serial_number_by_status(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT item_code, serial_number,statuss, location_code, zone_code, area_code, rack_code, bin_code,sn2, sts.name as sts_name
          FROM tsc_item_sn t inner join mst_item_sn_status sts on(t.statuss=sts.code) where serial_number='".$this->serial_number."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-10-19
      function get_sn2_by_status($sn2, $status){
          $db = $this->load->database('default', true);

          $query_temp = "select serial_number, sn2, item_code,item.name as item_name, statuss,sts.name as sts_name, location_code, zone_code, area_code, rack_code, bin_code
          FROM tsc_item_sn t inner join mst_item_sn_status sts on(t.statuss=sts.code)
          inner join mst_item item on(item.code=t.item_code)
          where sn2='".$sn2."' and statuss in(".$status.");";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // 2023-10-19
      function get_sn_by_status($sn, $status){
          $db = $this->load->database('default', true);

          $query_temp = "select serial_number, sn2, item_code,item.name as item_name , statuss,sts.name as sts_name, location_code, zone_code, area_code, rack_code, bin_code
          FROM tsc_item_sn t inner join mst_item_sn_status sts on(t.statuss=sts.code)
          inner join mst_item item on(item.code=t.item_code)
          where serial_number='".$sn."' and statuss in(".$status.");";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // 2023-10-19
      function update_location_v4($data, $location, $zone, $area, $rack, $bin){
          $db = $this->load->database('default', true);

          $query_temp = array();
          foreach($data as $row){
              $query_temp2 = array(
                  "serial_number" => $row,
                  "location_code" => $location,
                  "zone_code" => $zone,
                  "area_code" => $area,
                  "rack_code" => $rack,
                  "bin_code" => $bin,
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->update_batch('tsc_item_sn',$query_temp,'serial_number');
          return true;
      }
      //---

      // 2023-10-20
      function get_data_by_item_code_and_status_and_loc_group_by_sn2(){
          $db = $this->load->database('default', true);
          $query_temp = "select tbl_sn.item_code, tbl_sn.sn2, tbl_sn.qty, item_conv.pcs
            from (
            SELECT item_code, sn2, count(sn2) as qty
                      FROM tsc_item_sn t where item_code='".$this->item_code."' and statuss='".$this->status."'
                      and location_code='".$this->location_code."'
                      and zone_code='".$this->zone_code."'
                      and area_code = '".$this->area_code."'
                      and rack_code='".$this->rack_code."'
                      and bin_code='".$this->bin_code."' group by sn2) as tbl_sn
                      inner join mst_item_uom_conv as item_conv on(item_conv.item_code=tbl_sn.item_code) where qty=pcs;";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      // 2023-10-20
      function get_item_sn2_by_rack_by_status($status){
          $db = $this->load->database('default', true);
          $query_temp = "select sn2, tbl_item_sn.item_code,statuss, sts_name,qty, item.name as item_name from (
            select sn2, statuss,item_code,sts.name as sts_name, count(sn2) as qty
          FROM tsc_item_sn t inner join mst_item_sn_status sts on(sts.code=t.statuss)
          where location_code='".$this->location_code."' and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."' and bin_code='".$this->bin_code."' and statuss in(".$status.") group by sn2, statuss) as tbl_item_sn
          left join mst_item item on(item.code=tbl_item_sn.item_code);";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-10-26
      function get_not_status_by_multiple_sn($sn, $status){
        $db = $this->load->database('default', true);

        $query_temp = "select count(serial_number) as total from ( SELECT serial_number, statuss FROM tsc_item_sn t where statuss='".$status."' and serial_number in( ";

        foreach($sn as $row){ $query_temp.="'".$row."',"; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" )) as tbl where statuss!='".$status."';";

        $query = $db->query($query_temp)->row();
        if($query->total == 0) return true;
        else return false;
      }
      //--

      // 2023-10-26
      function update_location_v5($data){
          $db = $this->load->database('default', true);

          $query_temp = array();
          foreach($data as $row){
              $query_temp2 = array(
                  "serial_number" => $row["sn"],
                  "location_code" => $row["location"],
                  "zone_code" => $row["zone"],
                  "area_code" => $row["area"],
                  "rack_code" => $row["rack"],
                  "bin_code" => $row["bin"],
                  "statuss" => $row["status"],
              );

              $query_temp[] = $query_temp2;
          }

          $query = $db->update_batch('tsc_item_sn',$query_temp,'serial_number');
          return true;
      }
      //---

      // 2023-10-31
      function get_item_by_location2(){
          $db = $this->load->database('default', true);
          $query_temp = "select item_code, statuss, sn_status.name as status_name, qty, item.name as item_name
            from (
            SELECT item_code, statuss, count(item_code) as qty
            FROM tsc_item_sn t where location_code='".$this->location_code."' and zone_code='".$this->zone_code."' and area_code='".$this->area_code."' and rack_code='".$this->rack_code."' and bin_code='".$this->bin_code."' and statuss in('1','2') group by item_code, statuss) as tbl_item_sn
            left join mst_item_sn_status sn_status on(tbl_item_sn.statuss = sn_status.code)
            left join mst_item item on(item.code=tbl_item_sn.item_code)";


          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function list_item_location_by_item_code_and_status_ver_three($whs){
          $db = $this->load->database('default', true);

          $query_temp = "select * from (
            select item_code,location_code,zone_code, area_code, rack_code,bin_code,count(item_code) as total from(
                        SELECT item_code,location_code,zone_code, area_code, rack_code,bin_code, serial_number
                        FROM tsc_item_sn t where item_code='".$this->item_code."' and statuss='".$this->status."' and location_code='".$whs."' order by serial_number) as tbl
                        group by item_code, location_code,zone_code, area_code, rack_code, bin_code) tbl_sn_loc
            inner join(
            select item_code, location_code, zone_code, area_code, rack_code, bin_code, sum(qty) as qty, group_concat(masterr) as masterr
            from (
            select sn2,tbl_sn_sn2.item_code,location_code,zone_code,area_code,rack_code, bin_code, qty, pcs, if(qty=pcs,1,0) as masterr
            from (
            SELECT sn2,item_code,location_code,zone_code, area_code, rack_code, bin_code, count(item_code) as qty
            FROM tsc_item_sn t where item_code='".$this->item_code."' and statuss='".$this->status."' and location_code='".$whs."'
            group by sn2, location_code,zone_code, area_code, rack_code, bin_code) as tbl_sn_sn2
            left join mst_item_uom_conv conv on(conv.item_code=tbl_sn_sn2.item_code)) as tbl_master group by
            item_code, location_code, zone_code, area_code, rack_code, bin_code) as tbl_master
            on(tbl_master.item_code=tbl_sn_loc.item_code
            and tbl_master.location_code=tbl_sn_loc.location_code
            and tbl_master.zone_code=tbl_sn_loc.zone_code
            and tbl_master.area_code=tbl_sn_loc.area_code
            and tbl_master.rack_code=tbl_sn_loc.rack_code
            and tbl_master.bin_code=tbl_sn_loc.bin_code)";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---
}

?>
