<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_putaway_h extends CI_Model{
      var $doc_no,$created_datetime, $doc_datetime, $doc_type, $src_location_code, $all_finished_datetime, $created_user, $external_document, $statuss, $doc_date, $assign_user, $start_datetime, $text;

      function call_store_procedure_newputaway(){
        $db = $this->load->database('default', true);
        $query_temp = "call NEWPUTAWAY('".$this->prefix_code."', '".$this->created_datetime."', '".$this->doc_datetime."','".$this->doc_type."','".$this->src_location_code."','".$this->created_user."','".$this->external_document."', '".$this->statuss."','".$this->doc_date."', '".$this->assign_user."')";

        $query = $db->query($query_temp)->row();
        return $query->trsc_no;
      }
      //---

      function list_by_status($status){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no as doc_no, h.created_datetime, doc_datetime, h.src_location_code as src_location_code, h.created_user, u.name as uname, h.assign_user, u2.name as assign_name ,statuss, sts.name as sts_name, sum(d.qty_to_put) as qty, d.uom as uom, text
        FROM tsc_put_away_h h
        inner join tsc_in_out_bound_h_status sts on(h.statuss = sts.code)
        inner join user u on(u.user_id=h.created_user)
        inner join tsc_put_away_d d on(h.doc_no=d.doc_no)
        inner join user u2 on(u2.user_id=h.assign_user) ";

        // where condition
        $query_temp.=" where statuss in( ";
        foreach($status as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";

        $user_plant = get_plant_code_user();// 2023-03-02 WH3
        $query_temp.=" and h.src_location_code in(".$user_plant.") "; // 2023-03-02 WH3
        //---

        $query_temp.=" group by h.doc_no, h.created_datetime, doc_datetime, h.src_location_code, h.created_user, u.name, h.assign_user, u2.name,statuss, sts.name, text;";
        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      function update_status(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_put_away_h set statuss='".$this->statuss."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      function update_start_finish_time(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_h set start_datetime='".$this->start_datetime."', all_finished_datetime='".$this->all_finished_datetime."' where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_doc_status(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT statuss FROM tsc_put_away_h t where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp)->row();
          return $query->statuss;
      }
      //----

      function update_text(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_put_away_h set text='".$this->text."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      function dsh_outstanding_putaway(){
          $db = $this->load->database('default', true);
          $query_temp = "select if(sum(qty_to_put) is null,0,sum(qty_to_put)) as outstand_putaway
            from(
            SELECT h.doc_no,sum(qty_to_put) as qty_to_put
            FROM tsc_put_away_h h inner join tsc_put_away_d d on(h.doc_no=d.doc_no) where statuss in('6') group by h.doc_no) as tbl;";
          $query = $db->query($query_temp)->row();
          return $query->outstand_putaway;
      }
      //---

      function list_by_status_and_user($status,$user){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no as doc_no, h.created_datetime, doc_datetime, h.src_location_code as src_location_code, h.created_user, u.name as uname, h.assign_user, u2.name as assign_name ,statuss, sts.name as sts_name, sum(d.qty_to_put) as qty, d.uom as uom, text
        FROM tsc_put_away_h h
        inner join tsc_in_out_bound_h_status sts on(h.statuss = sts.code)
        inner join user u on(u.user_id=h.created_user)
        inner join tsc_put_away_d d on(h.doc_no=d.doc_no)
        inner join user u2 on(u2.user_id=h.assign_user) ";

        // where condition
        $query_temp.=" where statuss in( ";
        foreach($status as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";

        $user_plant = get_plant_code_user();// 2023-03-02 WH3
        $query_temp.=" and h.src_location_code in(".$user_plant.") "; // 2023-03-02 WH3

        $query_temp.= " and assign_user='".$user."' ";
        //---

        $query_temp.=" group by h.doc_no, h.created_datetime, doc_datetime, h.src_location_code, h.created_user, u.name, h.assign_user, u2.name,statuss, sts.name, text;";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      // 2022-11-09
      function change_assign_user(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_put_away_h set assign_user='".$this->assign_user."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //--

      // 2022-11-18
      function update_start_time($doc_no, $datetime){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_h set start_datetime='".$datetime."' where doc_no='".$doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      // 2022-11-18
      function update_finish_time($doc_no, $datetime){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_h set all_finished_datetime='".$datetime."' where doc_no='".$doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---
}

?>
