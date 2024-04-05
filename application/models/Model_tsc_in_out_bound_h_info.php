<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Tsc_in_out_bound_h_info extends CI_Model{

    var $doc_no,$nav_datetime;

    function insert(){
        $db = $this->load->database('default', true);
        $data = array(
            "doc_no" => $this->doc_no,
            "nav_datetime" => $this->nav_datetime,
        );

        $insert_query = $this->db->insert_string('tsc_in_out_bound_h_info', $data);
        $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
        $result = $this->db->query($insert_query);
        if($result) return true; else return false;
    }
    //----

}


?>
