<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_adjust_doc_d extends CI_Model{

    function insert_batch($doc_no, $doc_no_edited, $line_no_edited, $item_code, $description, $qty_to_ship, $qty_minus, $qty_result, $pick_no, $pick_line_no, $so_no, $cust_code, $cust_name){
      $db = $this->load->database('default', true);

      $line_no=1;
      $query_temp = array();
      for($i=0;$i<count($doc_no_edited);$i++){
          $query_temp2 = array(
              "doc_no" => $doc_no,
              "line_no" => $line_no,
              "doc_no_edited" => $doc_no_edited[$i],
              "line_no_edited" => $line_no_edited[$i],
              "item_code" => $item_code[$i],
              "description" => $description[$i],
              "qty_to_ship" => $qty_to_ship[$i],
              "qty_minus" => $qty_minus[$i],
              "qty_result" => $qty_result[$i],
              "picking_no" => $pick_no[$i],
              "picking_line_no" => $pick_line_no[$i],
              "so_no" => $so_no[$i],
              "cust_code" => $cust_code[$i],
              "cust_name" => $cust_name[$i],
          );

          $query_temp[] = $query_temp2;
          $line_no++;
      }

      $query = $db->insert_batch('tsc_adjust_doc_d',$query_temp);
      return true;
    }
    //---

    function get_list($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM tsc_adjust_doc_d t where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }

}

?>
