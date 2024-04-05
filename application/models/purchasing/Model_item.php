<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_item extends CI_Model{
    function get_data(){
        $db = $this->load->database('tpimx_purchasing', true);
        $query_temp = "SELECT item_code, description, 'PZA' as uom FROM item_code i
        union
        SELECT item_code, description, uom FROM purchase_request_doc_d group by item_code, description;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
}