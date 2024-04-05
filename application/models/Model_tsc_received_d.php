<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Tsc_received_d extends CI_Model{
      var $doc_no, $line_no, $src_location_code, $src_no, $src_line_no, $item_code, $qty_outstanding, $uom, $dest_no, $qty, $description, $complete_putaway, $created_datetime_d, $valuee, $valuee_per_pcs;

      var $master_barcode; // master barcode 2023-01-17

      function insert_d(){
          $db = $this->load->database('default', true);
          $data = array(
              "doc_no" => $this->doc_no,
              "line_no" => $this->line_no,
              "src_location_code" => $this->src_location_code,
              "src_no" => $this->src_no,
              "src_line_no" => $this->src_line_no,
              "item_code" => $this->item_code,
              "uom" => $this->uom,
              "description" => $this->description,
              "qty" => $this->qty,
              "qty_outstanding" => $this->qty_outstanding,
              "dest_no" => $this->dest_no,
              "created_datetime_d" => $this->created_datetime_d,
              "master_barcode" => $this->master_barcode,
              "valuee" => $this->valuee,
              "valuee_per_pcs" => $this->valuee_per_pcs,
          );

          $result = $this->db->insert('tsc_received_d', $data);
          if($result) return true; else return false;
      }
      //----

      function get_list($doc_no){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no, line_no, src_location_code, src_no, src_line_no, item_code, qty_outstanding, uom, dest_no, qty, description, completely_putaway, master_barcode,valuee, valuee_per_pcs
          FROM tsc_received_d d ";

          // where condition
          $query_temp.=" where doc_no in( ";
          foreach($doc_no as $row){ $query_temp.="'".$row."',";}
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=" ) order by item_code ;";
          //---

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_list_outstanding(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no, line_no, src_location_code, src_no, src_line_no, item_code, qty_outstanding, uom, dest_no, qty, description, completely_putaway
          FROM tsc_received_d d where qty_outstanding > 0";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_qty_outstanding(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_received_d set qty_outstanding='".$this->qty_outstanding."' where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      function get_list_outstanding_and_has_gen_sn($whs){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT d.doc_no, line_no, src_location_code, src_no, src_line_no, item_code, qty_outstanding, uom, dest_no, qty, description, completely_putaway
          FROM tsc_received_d d inner join tsc_received_h h on(d.doc_no=h.doc_no)
          where qty_outstanding > 0 and h.status_h='5' and src_location_code='".$whs."';";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_qty_received_shipped($received_doc, $shipped_doc){
          $db = $this->load->database('default', true);
          $query_temp = "select tbl_received.item_code, qty_received, qty_shipped
              from (
              SELECT item_code, sum(qty) as qty_received
              FROM tsc_received_d t where doc_no='".$received_doc."' group by item_code) as tbl_received

              inner join (
              SELECT item_code, sum(qty_to_ship) as qty_shipped
              FROM tsc_in_out_bound_d t where doc_no='".$shipped_doc."' group by item_code) as tbl_shipped on(tbl_received.item_code=tbl_shipped.item_code);";

          $query = $db->query($query_temp);
          return $query->result_array();

      }
      //---

      function get_qty_shipped_received($shipped_doc, $received_doc){
          $db = $this->load->database('default', true);
          $query_temp = "select tbl_shipped.item_code, qty_shipped, qty_received
            from (
            SELECT item_code, sum(qty_to_ship) as qty_shipped
            FROM tsc_in_out_bound_d t where doc_no='".$shipped_doc."' group by item_code) as tbl_shipped

            left join
            (SELECT item_code, sum(qty) as qty_received
            FROM tsc_received_d t where doc_no='".$received_doc."' group by item_code) as tbl_received on(tbl_received.item_code=tbl_shipped.item_code);";

          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_total_qty_by_doc_no(){
          $db = $this->load->database('default', true);
          $query_temp = "select sum(qty) as qty_received FROM tsc_received_d t where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp)->row();
          return $query->qty_received;
      }
}

?>
