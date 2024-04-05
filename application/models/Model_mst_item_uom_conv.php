<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_mst_item_uom_conv extends CI_Model{
    var $item_code, $ctn, $pcs;

    function get_converter(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM mst_item_uom_conv m where item_code='".$this->item_code."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function check_item_has_converter(){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT * FROM mst_item_uom_conv m where item_code='".$this->item_code."';";
      $query = $db->query($query_temp);
      $result = $query->result_array();
      if(count($result) > 0) return true; else false;
    }
    //--

    function get_pcs(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM mst_item_uom_conv m where item_code='".$this->item_code."';";
        $query = $db->query($query_temp)->row();
        return $query->pcs;
    }
    //--

    function get_pcs_multiple_item($item){
        $db = $this->load->database('default', true);
        $query_temp = "select * FROM mst_item_uom_conv m where item_code in ( ";
        foreach($item as $row){ $query_temp.="'".$row."',"; }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) order by item_code ";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--
}

?>
