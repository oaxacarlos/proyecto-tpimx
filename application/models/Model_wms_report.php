<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_wms_report extends CI_Model{

    function stock_invt(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT item_code, itm.name, available, picking, picked, packing FROM tsc_item_invt invt inner join mst_item itm on(invt.item_code=itm.code)");
        return $query->result_array();
    }
    //---

    function stock_invt_by_code(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT item_code, itm.name, available, picking, picked, packing FROM tsc_item_invt invt inner join mst_item itm on(invt.item_code=itm.code)");
        return $query->result_array();
    }
    //---

}
