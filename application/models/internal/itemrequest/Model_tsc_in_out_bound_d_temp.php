<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_tsc_in_out_bound_d_temp extends CI_Model{

    function insert_d($doc_no, $line_no, $src_location_code, $src_no, $src_line_no, $item_code, $uom, $description, $qty, $dest_no){
        $db = $this->load->database('default', true);
        $data = array(
            "doc_no" => $doc_no,
            "line_no" => $line_no,
            "src_location_code" => $src_location_code,
            "src_no" => $src_no,
            "src_line_no" => $src_line_no,
            "item_code" => $item_code,
            "uom" => $uom,
            "description" => $description,
            "qty" => $qty,
            "dest_no" => $dest_no,
            "qty_edited" => $qty,
        );

        $result = $this->db->insert('tsc_in_out_bound_d_temp', $data);
        if($result) return true; else return false;
    }
    //----

    function get_data_by_doc_no($doc_no){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT doc_no, line_no, src_location_code, src_no,item_code, uom, dest_no,qty, description,qty_edited
      FROM tsc_in_out_bound_d_temp where doc_no='".$doc_no."';";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function update_qty_edited($doc_no, $line_no, $item_code, $qty_edited){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_d_temp set qty_edited='".$qty_edited."' where doc_no='".$doc_no."' and line_no='".$line_no."' and item_code='".$item_code."';";
        $query = $db->query($query_temp);
        return true;
    }
    //--

    function get_data_by_doc_no_stock($doc_no){
      $db = $this->load->database('default', true);
      $query_temp = "select doc_no, line_no, src_location_code, src_no,tbl_temp.item_code, uom, dest_no,qty,description,qty_edited,
        if(qty_stock is null,0,qty_stock) as qty_stock
        from(
        SELECT doc_no, line_no, src_location_code, src_no,item_code, uom, dest_no,qty, description,qty_edited
              FROM tsc_in_out_bound_d_temp where doc_no='".$doc_no."') as tbl_temp
        left join(
        SELECT item_code,count(item_code) as qty_stock FROM tsc_item_sn where statuss in('1') and item_code in(
        SELECT item_code FROM tsc_in_out_bound_d_temp where doc_no='".$doc_no."') group by item_code) as tbl_qty on(tbl_temp.item_code=tbl_qty.item_code)";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---
}

?>
