<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_mst_item_pack extends CI_Model{

    function get_data(){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT itempack.code, item.name,active
      FROM mst_item_pack itempack
      left join mst_item item on(itempack.code = item.code)
      where active='1';";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---
}

?>
