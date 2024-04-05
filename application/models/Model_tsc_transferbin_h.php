<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_transferbin_h extends CI_Model{
      var $doc_no,$created_datetime, $doc_datetime, $created_user, $statuss, $doc_date, $assign_user, $start_datetime, $text1, $location_code;

      function call_store_procedure_newtransferbin(){
        $db = $this->load->database('default', true);
        $query_temp = "call NEWTRANSFERBIN('".$this->prefix_code."', '".$this->created_datetime."', '".$this->doc_datetime."', '".$this->created_user."', '".$this->statuss."', '".$this->doc_date."', '".$this->text1."','".$this->assign_user."','".$this->location_code."')";

        $query = $db->query($query_temp)->row();
        return $query->trsc_no;
      }
      //---

      function list_by_status($status){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no, h.created_datetime,h.doc_datetime, created_user, u.name as created_name, statuss, doc_date, text1, assigned_user, u2.name as assigned_name, sum(qty) as qty, uom, location_code_to, zone_code_to, area_code_to, rack_code_to,
        bin_code_to, print_barcode, print_master_barcode, location_code
          FROM tsc_transferbin_h h
          inner join user u on(u.user_id=h.created_user)
          inner join user u2 on(u2.user_id=h.assigned_user)
          inner join tsc_transferbin_d d on(h.doc_no=d.doc_no) ";

          //---
          $query_temp.=" where statuss in( ";
          foreach($status as $row){ $query_temp.="'".$row."',";}
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=" ) ";
          //---

          $user_plant = get_plant_code_user();// 2023-03-02 WH3
          $query_temp.=" and h.location_code in(".$user_plant.") "; // 2023-03-02 WH3

          $query_temp.= " group by h.doc_no, h.created_datetime,h.doc_datetime, created_user,u.name,statuss,doc_date,assigned_user,u2.name,location_code_to, zone_code_to, area_code_to, rack_code_to, bin_code_to";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      function get_doc_status(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT statuss FROM tsc_transferbin_h t where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp)->row();
        return $query->statuss;
      }
      //--

      function update_status(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_h set statuss='".$this->statuss."' where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //--

      function get_list_by_doc_no(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no, h.created_datetime,h.doc_datetime, created_user, u.name as created_name, statuss, doc_date, text1, assigned_user, u2.name as assigned_name, sum(qty) as qty, uom, location_code_to, zone_code_to, area_code_to, rack_code_to, bin_code_to
          FROM tsc_transferbin_h h
          inner join user u on(u.user_id=h.created_user)
          inner join user u2 on(u2.user_id=h.assigned_user)
          inner join tsc_transferbin_d d on(h.doc_no=d.doc_no) where h.doc_no='".$this->doc_no."';";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      // 2023-02-07
      function update_print_barcode($value, $doc_no){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_h set print_barcode=print_barcode + ".$value." where doc_no='".$doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      // 2023-02-07
      function update_print_master_barcode($value, $doc_no){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_transferbin_h set print_master_barcode=print_master_barcode + ".$value." where doc_no='".$doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function list_by_status2($status, $from, $to){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no, h.created_datetime,h.doc_datetime, created_user, u.name as created_name, statuss, doc_date, text1, assigned_user, u2.name as assigned_name, sum(qty) as qty, uom, location_code_to, zone_code_to, area_code_to, rack_code_to,
        bin_code_to, print_barcode, print_master_barcode, location_code
          FROM tsc_transferbin_h h
          inner join user u on(u.user_id=h.created_user)
          inner join user u2 on(u2.user_id=h.assigned_user)
          inner join tsc_transferbin_d d on(h.doc_no=d.doc_no) ";

          //---
          $query_temp.=" where statuss in( ";
          foreach($status as $row){ $query_temp.="'".$row."',";}
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=" ) ";
          //---

          $query_temp.=" and doc_date between '".$from."' and '".$to."' ";

          $user_plant = get_plant_code_user();// 2023-03-02 WH3
          $query_temp.=" and h.location_code in(".$user_plant.") "; // 2023-03-02 WH3

          $query_temp.= " group by h.doc_no, h.created_datetime,h.doc_datetime, created_user,u.name,statuss,doc_date,assigned_user,u2.name,location_code_to, zone_code_to, area_code_to, rack_code_to, bin_code_to";


        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---
}

?>
