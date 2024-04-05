<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor extends CI_Model{

    function get_data(){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "select * from mst_vendor order by vendor_name";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
}

?>
