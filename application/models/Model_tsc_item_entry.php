<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_item_entry extends CI_Model{

    var $item_code, $qty, $src_no, $type, $text, $serial_number, $text2, $description, $created_datetime;

    function insert_with_bulk($data_insert){

        //foreach($data_insert as $row){
            $db = $this->load->database('default', true);
            $result = $db->insert_batch('tsc_item_entry', $data_insert);

            /*$data = array(
                "item_code" => $row['item_code'],
                "qty" => $row['qty'],
                "src_no" => $row['src_no'],
                "type" => $row['type'],
                "text" => $row['text'],
                "serial_number" => $row['serial_number'],
                "text2" => $row['text2'],
                "description" => $row['description'],
                "created_datetime" => $row['created_datetime'],
            );*/

        //}
    }
    //---

    function get_inbound_by_periode_limit($date_from, $date_to, $limit){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT item_code, sum(qty) as qty
          FROM tsc_item_entry t where created_datetime between '".$date_from."' and '".$date_to."' and
          src_no like 'RCV%' group by item_code order by qty desc limit ".$limit;
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----

    function get_outbound_by_periode_limit($date_from, $date_to, $limit){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT item_code, sum(qty)*-1 as qty
          FROM tsc_item_entry t where created_datetime between '".$date_from."' and '".$date_to."' and
          src_no like 'TPM-WSHIP-%' group by item_code order by qty desc limit ".$limit;
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----
}

?>
