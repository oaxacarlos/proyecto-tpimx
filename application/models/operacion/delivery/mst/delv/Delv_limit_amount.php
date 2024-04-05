<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delv_limit_amount extends CI_Model{

    function get_data_by_state($state){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "select percentage from mst_delv_limit_amount where state='".$state."';";
        $query = $db->query($query_temp)->row();
        return $query->percentage;
    }
}

?>
