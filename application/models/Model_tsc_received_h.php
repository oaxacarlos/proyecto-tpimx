<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Tsc_received_h extends CI_Model{
      var $doc_no,$in_bound_no, $created_datetime, $doc_datetime, $doc_location_code, $created_user, $external_document, $doc_date;
      var $putaway_finished, $prefix_code, $status_h, $text, $transfer_from_wh, $transfer_to_wh, $from_wh, $to_wh, $print_barcode, $print_master_barcode;

      function call_store_procedure_newreceived(){
        $db = $this->load->database('default', true);
        $query_temp = "call NEWRECEIVED('".$this->prefix_code."', '".$this->in_bound_no."', '".$this->created_datetime."', '".$this->doc_datetime."',
        '".$this->doc_location_code."','".$this->created_user."','".$this->external_document."','".$this->doc_date."', '".$this->status_h."','".$this->transfer_from_wh."', '".$this->transfer_to_wh."', '".$this->from_wh."', '".$this->to_wh."', '".$this->print_barcode."',
        '".$this->print_master_barcode."')";

        $query = $db->query($query_temp)->row();

        return $query->trsc_no;
      }
      //---

      function list_received_h_by_status($status){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no as doc_no, in_bound_no, created_datetime, doc_datetime, doc_location_code, h.created_user, u.name as uname, doc_date, status_h, sts.name as sts_name, sum(d.qty) as qty, d.uom as uom, text, print_barcode, print_master_barcode, transfer_from_wh, transfer_to_wh, from_wh, to_wh
        FROM tsc_received_h h
        inner join tsc_in_out_bound_h_status sts on(h.status_h = sts.code)
        inner join user u on(u.user_id=h.created_user)
        inner join tsc_received_d d on(h.doc_no=d.doc_no) ";

        // where condition
        $query_temp.=" where status_h in( ";
        foreach($status as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";

        $user_plant = get_plant_code_user();// 2023-03-02 WH3
        $query_temp.=" and h.doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3
        //---

        $query_temp.=" group by h.doc_no,in_bound_no, created_datetime, doc_datetime, doc_location_code, h.created_user, u.name, doc_date, status_h, sts.name, text;";
        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      function update_status(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_received_h set status_h='".$this->status_h."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      function update_text(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_received_h set text='".$this->text."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      // 2023-02-07
      function update_print_barcode($value, $doc_no){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_received_h set print_barcode=print_barcode + ".$value." where doc_no='".$doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      // 2023-02-07
      function update_print_master_barcode($value, $doc_no){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_received_h set print_master_barcode=print_master_barcode + ".$value." where doc_no='".$doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function list_received_h_by_status_with_limit($status, $limit){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no as doc_no, in_bound_no, created_datetime, doc_datetime, doc_location_code, h.created_user, u.name as uname, doc_date, status_h, sts.name as sts_name, sum(d.qty) as qty, d.uom as uom, text, print_barcode, print_master_barcode, transfer_from_wh, transfer_to_wh, from_wh, to_wh
        FROM tsc_received_h h
        inner join tsc_in_out_bound_h_status sts on(h.status_h = sts.code)
        inner join user u on(u.user_id=h.created_user)
        inner join tsc_received_d d on(h.doc_no=d.doc_no) ";

        // where condition
        $query_temp.=" where status_h in( ";
        foreach($status as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";

        $user_plant = get_plant_code_user();// 2023-03-02 WH3
        $query_temp.=" and h.doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3
        //---

        $query_temp.=" group by h.doc_no,in_bound_no, created_datetime, doc_datetime, doc_location_code, h.created_user, u.name, doc_date, status_h, sts.name, text order by doc_no desc limit ".$limit.";";
        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---
}

?>
