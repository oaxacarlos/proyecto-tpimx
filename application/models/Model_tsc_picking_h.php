<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_picking_h extends CI_Model{
      var $doc_no,$created_datetime, $doc_datetime, $doc_type, $src_location_code, $all_finished_datetime, $created_user, $external_document, $statuss, $doc_date, $assign_user, $start_datetime, $text1;

      function call_store_procedure_newpicking(){
        $db = $this->load->database('default', true);
        $query_temp = "call NEWPICKING('".$this->prefix_code."', '".$this->created_datetime."', '".$this->doc_datetime."','".$this->doc_type."','".$this->src_location_code."','".$this->created_user."','".$this->external_document."', '".$this->statuss."',
        '".$this->doc_date."', '".$this->assign_user."','".$this->text1."')";

        $query = $db->query($query_temp)->row();
        return $query->trsc_no;
      }
      //---

      function list_by_status($status){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no as doc_no, h.created_datetime, h.doc_datetime, h.src_location_code as src_location_code,
        h.created_user, u.name as uname, h.assign_user, u2.name as assign_name ,statuss, sts.name as sts_name, sum(d.qty_to_picked) as qty, d.uom as uom, text1, d.src_no, inoutd.src_no as so_no, so.bill_cust_no, so.bill_cust_name, so.ship_to_addr,so.ship_to_addr2 ,so.ship_to_city,
        sum(if(d.picked_datetime is not null,d.qty_to_picked,0)) as qty_has_picked
        FROM tsc_pick_h h
        inner join tsc_in_out_bound_h_status sts on(h.statuss = sts.code)
        inner join user u on(u.user_id=h.created_user)
        inner join tsc_pick_d d on(h.doc_no=d.doc_no)
        inner join user u2 on(u2.user_id=h.assign_user)
        left join (select doc_no,src_no from tsc_in_out_bound_d group by doc_no) inoutd on(inoutd.doc_no=d.src_no)
        left join tsc_so so on(so.so_no=inoutd.src_no)";

        // where condition
        $query_temp.=" where statuss in( ";
        foreach($status as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";

        $user_plant = get_plant_code_user();// 2023-03-02 WH3
        $query_temp.=" and h.src_location_code in(".$user_plant.") "; // 2023-03-02 WH3
        //---

        $query_temp.=" group by h.doc_no, h.created_datetime, doc_datetime, h.src_location_code, h.created_user, u.name, h.assign_user, u2.name,statuss, sts.name, text1;";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      function update_status(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_pick_h set statuss='".$this->statuss."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      function update_start_finish_time(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_pick_h set start_datetime='".$this->start_datetime."', all_finished_datetime='".$this->all_finished_datetime."' where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_doc_status(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT statuss FROM tsc_pick_h t where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp)->row();
          return $query->statuss;
      }
      //----

      function update_text(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_pick_h set text1='".$this->text1."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      function dsh_outstanding_picked(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT sum(qty_to_picked) as qty_picked_outstanding
            FROM tsc_pick_h h inner join tsc_pick_d d on(h.doc_no=d.doc_no) where statuss in('1');";
          $query = $db->query($query_temp)->row();
          return $query->qty_picked_outstanding;
      }
      //---

      function dsh_outstanding_picked_doc(){
          $db = $this->load->database('default', true);
          $query_temp = "select count(doc_no) as outstanding_picked_doc
              from(
              SELECT h.doc_no
              FROM tsc_pick_h h inner join tsc_pick_d d on(h.doc_no=d.doc_no) where completely_picked is null group by h.doc_no) as tbl;";
          $query = $db->query($query_temp)->row();
          return $query->outstanding_picked_doc;
      }
      //---

      function list_by_status_and_user($status,$user){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no as doc_no, h.created_datetime, h.doc_datetime, h.src_location_code as src_location_code,
        h.created_user, u.name as uname, h.assign_user, u2.name as assign_name ,statuss, sts.name as sts_name, sum(d.qty_to_picked) as qty,
        d.uom as uom, text1,
        d.src_no as whship_no,inouth.src_no as so_no, so.bill_cust_no,so.bill_cust_name
        FROM tsc_pick_h h
        inner join tsc_in_out_bound_h_status sts on(h.statuss = sts.code)
        inner join user u on(u.user_id=h.created_user)
        inner join tsc_pick_d d on(h.doc_no=d.doc_no)
        inner join user u2 on(u2.user_id=h.assign_user)
        inner join tsc_in_out_bound_d inouth on(inouth.doc_no=d.src_no)
        left join tsc_so so on(inouth.src_no=so.so_no) ";

        // where condition
        $query_temp.=" where statuss in( ";
        foreach($status as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";

        $user_plant = get_plant_code_user();// 2023-03-02 WH3
        $query_temp.=" and h.src_location_code in(".$user_plant.") "; // 2023-03-02 WH3

        $query_temp.= " and assign_user='".$user."' ";
        //---

        $query_temp.=" group by h.doc_no, h.created_datetime, doc_datetime, h.src_location_code, h.created_user, u.name, h.assign_user, u2.name,statuss, sts.name, text1;";
        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      function get_by_one_doc($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no as doc_no, h.created_datetime, h.doc_datetime, h.src_location_code as src_location_code,
        h.created_user, u.name as uname, h.assign_user, u2.name as assign_name ,statuss, sts.name as sts_name, sum(d.qty_to_picked) as qty, d.uom as uom, text1, d.src_no, inoutd.src_no as so_no, so.bill_cust_no, so.bill_cust_name, so.ship_to_addr,so.ship_to_addr2 ,so.ship_to_city
        FROM tsc_pick_h h
        inner join tsc_in_out_bound_h_status sts on(h.statuss = sts.code)
        inner join user u on(u.user_id=h.created_user)
        inner join tsc_pick_d d on(h.doc_no=d.doc_no)
        inner join user u2 on(u2.user_id=h.assign_user)
        left join (select doc_no,src_no from tsc_in_out_bound_d group by doc_no) inoutd on(inoutd.doc_no=d.src_no)
        left join tsc_so so on(so.so_no=inoutd.src_no)";

        // where condition
        $query_temp.=" where h.doc_no='".$doc_no."' ";
        //---

        $query_temp.=" group by h.doc_no, h.created_datetime, doc_datetime, h.src_location_code, h.created_user, u.name, h.assign_user, u2.name,statuss, sts.name, text1;";

        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      function list_by_status_and_user_by_limit($status,$user,$limit){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no as doc_no, h.created_datetime, h.doc_datetime, h.src_location_code as src_location_code,
        h.created_user, u.name as uname, h.assign_user, u2.name as assign_name ,statuss, sts.name as sts_name, sum(d.qty_to_picked) as qty,
        d.uom as uom, text1,
        d.src_no as whship_no,inouth.src_no as so_no, so.bill_cust_no,so.bill_cust_name,so.ship_to_city, outh.urgent as urgent
        FROM tsc_pick_h h
        inner join tsc_in_out_bound_h_status sts on(h.statuss = sts.code)
        inner join user u on(u.user_id=h.created_user)
        inner join tsc_pick_d d on(h.doc_no=d.doc_no)
        inner join user u2 on(u2.user_id=h.assign_user)
        inner join (select doc_no,src_no from tsc_in_out_bound_d group by doc_no) inouth on(inouth.doc_no=d.src_no)
        inner join tsc_in_out_bound_h outh on(outh.doc_no=inouth.doc_no)
        left join tsc_so so on(inouth.src_no=so.so_no) ";

        // where condition
        $query_temp.=" where statuss in( ";
        foreach($status as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";

        $user_plant = get_plant_code_user();// 2023-03-02 WH3
        $query_temp.=" and h.src_location_code in(".$user_plant.") "; // 2023-03-02 WH3

        $query_temp.= " and assign_user='".$user."' ";
        //---

        $query_temp.=" group by h.doc_no, h.created_datetime, doc_datetime, h.src_location_code, h.created_user, u.name, h.assign_user, u2.name,statuss, sts.name, text1 ";

        $query_temp.=" order by outh.urgent desc, h.created_datetime limit ".$limit;
        $query = $db->query($query_temp);
        return $query->result_array();
      }
      //---

      // 2022-11-04
      function change_assign_user(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_pick_h set assign_user='".$this->assign_user."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //--

}

?>
