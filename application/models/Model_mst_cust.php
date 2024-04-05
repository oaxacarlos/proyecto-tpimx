<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_mst_cust extends CI_Model{
      var $cust_no;

      function get_list_by_cust_no(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM mst_cust m where cust_no='".$this->cust_no."'";
        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

}

?>
