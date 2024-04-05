<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_stock_count extends CI_Model{

    function insert($doc_date, $created_at, $item_code, $location, $zone, $area, $rack, $bin, $qty, $user, $type){
        $db = $this->load->database('default', true);

        $query_temp = array();
        for($i=0; $i<count($item_code); $i++){
            $query_temp2 = array(
                "doc_date"      => $doc_date[$i],
                "created_at"    => $created_at[$i],
                "item_code"     => $item_code[$i],
                "location_code" => $location[$i],
                "zone_code"     => $zone[$i],
                "area_code"     => $area[$i],
                "rack_code"     => $rack[$i],
                "bin_code"      => $bin[$i],
                "qty"           => $qty[$i],
                "user_id"       => $user[$i],
                "typee"          => $type[$i],
            );

            $query_temp[] = $query_temp2;
        }

        $query = $db->insert_batch('tsc_stock_count',$query_temp);
        return true;
    }
    //--

    function get_data($date_from, $date_to,$user){
        $db = $this->load->database('default', true);
        $query_temp = "select id,doc_date, created_at, item_code, location_code, zone_code, area_code, rack_code, bin_code, qty, t.user_id, u.name, typee,
        if(typee='1','NORMAL','DISCREPANCY') as type_name
                  FROM tsc_stock_count t inner join user u on(t.user_id=u.user_id)
                  where doc_date between '".$date_from."' and '".$date_to."' and t.user_id like '".$user."%';";
        /*$query_temp = "select tbl_stock_count.id, doc_date, created_at, tbl_stock_count.item_code,
          tbl_stock_count.location_code,
          tbl_stock_count.zone_code,
          tbl_stock_count.area_code,
          tbl_stock_count.rack_code,
          tbl_stock_count.bin_code,
          tbl_stock_count.qty,
          tbl_stock_count.user_id,
          tbl_stock_count.name,
          if(countt is null or countt=0,'no duplicado','duplicados') as countt
          from (
          select id, doc_date, created_at, item_code,
          location_code, zone_code, area_code, rack_code, bin_code, qty, t.user_id, u.name
                            FROM tsc_stock_count t inner join user u on(t.user_id=u.user_id)
                            where doc_date between '".$date_from."' and '".$date_to."' and t.user_id like '".$user."%') as tbl_stock_count

           left join (SELECT id, location_code, zone_code, area_code, rack_code, bin_code, item_code,qty, COUNT(*) as countt
          FROM tsc_stock_count where doc_date between '".$date_from."' and '".$date_to."'
          GROUP BY location_code, zone_code, area_code, rack_code, bin_code, item_code,qty having count(*) > 1) as tbl_duplicate
          on(tbl_stock_count.id=tbl_duplicate.id);";*/

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function delete_id($id){
      $db = $this->load->database('default', true);
      $query_temp = "delete from tsc_stock_count where id='".$id."'";
      $query = $db->query($query_temp);
      return $query;
    }
    //--

    function get_stock_count_user(){
        $db = $this->load->database('default', true);
        $query_temp = "select user_id,name from user where user_id in('194','150','121','123','122','149','169','168','167','195','153') order by name";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_data_duplicate($date_from, $date_to,$user){
        $db = $this->load->database('default', true);
        $query_temp = "select doc_date,location_code, zone_code, area_code, rack_code, bin_code, item_code,qty,tbl_duplicate.user_id, name
          from (
          SELECT doc_date,location_code, zone_code, area_code, rack_code, bin_code, item_code,qty,user_id, COUNT(*)
          FROM tsc_stock_count t
          where doc_date between '".$date_from."' and '".$date_to."' and t.user_id like '".$user."%'
          GROUP BY location_code, zone_code, area_code, rack_code, bin_code, item_code,qty having count(*) > 1) as tbl_duplicate
          inner join user u on(tbl_duplicate.user_id=u.user_id)";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--
}
