<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_mst_item extends CI_Model{

    function get_data(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT code, name, uom FROM mst_item";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_item_commercial(){
        $db = $this->load->database('default', true);
        $query_temp = "select code, name, comm_name FROM mst_item m left join mst_item_commercial item_com on(item_com.item_code=m.code)";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--
}

?>
