<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Tsc_item_sn2 extends CI_Model{

    function insert($sn2, $status, $datetime){
        $db = $this->load->database('default', true);

        $query_temp = array();
        foreach($sn2 as $row){
            $query_temp2 = array(
                "created_datetime" => $datetime,
                "sn2" => $row,
                "statuss" => $status
            );

            $query_temp[] = $query_temp2;
        }

        $query = $db->insert_batch('tsc_item_sn2',$query_temp);
        return true;
    }
    //--

    function update_status_scan_datetime_v2($status,$datetime,$sn2,$total_row){
        $db = $this->load->database('default', true);

        $query_temp = array();
        for($i=0;$i<$total_row;$i++){
            $query_temp2 = array(
                "scan_datetime" => $datetime,
                "statuss_change" => $datetime,
                "statuss" => $status,
                "sn2" => $sn2[$i],
            );

            $query_temp[] = $query_temp2;
        }

        $query = $db->update_batch('tsc_item_sn2',$query_temp,'sn2');
        return true;
    }
    //---

    function update_putaway_datetime_v2($status,$datetime,$sn2,$total_row){
        $db = $this->load->database('default', true);

        $query_temp = array();
        for($i=0;$i<$total_row;$i++){
            $query_temp2 = array(
                "putaway_datetime" => $datetime,
            );

            $query_temp[] = $query_temp2;
        }

        $query = $db->update_batch('tsc_item_sn2',$query_temp,'sn2');
        return true;
    }
    //---

    function update_pick_datetime_v2($status,$datetime,$sn2,$total_row){
        $db = $this->load->database('default', true);

        $query_temp = array();
        for($i=0;$i<$total_row;$i++){
            $query_temp2 = array(
                "pick_datetime" => $datetime,
            );

            $query_temp[] = $query_temp2;
        }

        $query = $db->update_batch('tsc_item_sn2',$query_temp,'sn2');
        return true;
    }
    //---
}

?>
