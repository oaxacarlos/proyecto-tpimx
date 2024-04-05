<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_packing_item extends CI_Model{

    function insert($data){
        $db = $this->load->database('default', true);
        $query = $db->insert_batch('tsc_pack_item',$data);
    }
    //---
}

?>
