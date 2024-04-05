<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_Tsc_in_out_bound_h extends CI_Model{

    var $doc_no,$created_datetime,$doc_datetime,$doc_type,$doc_location_code,$month_end,$created_user,$external_document;
    var $pick_finished_datetime, $pack_finished_datetime, $status, $doc_date, $putaway_finished, $doc_posting_date;
    var $prefix_code, $text, $submitted_text, $submitted, $submitted_datetime, $transfer_from_wh, $transfer_to_wh, $from_wh, $to_wh;

    function insert_h(){
        $db = $this->load->database('default', true);
        $data = array(
            "doc_no" => $this->doc_no,
            "created_datetime" => $this->created_datetime,
            "doc_datetime" => $this->doc_datetime,
            "doc_type" => $this->doc_type,
            "doc_location_code" => $this->doc_location_code,
            "month_end" => $this->month_end,
            "created_user" => $this->created_user,
            "external_document" => $this->external_document,
            "pick_finished_datetime" => $this->pick_finished_datetime,
            "pack_finished_datetime" => $this->pack_finished_datetime,
            "status1" => $this->status,
            "doc_date" => $this->doc_date,
            "doc_posting_date" => $this->doc_posting_date,
            "transfer_from_wh" => $this->transfer_from_wh,
            "transfer_to_wh" => $this->transfer_to_wh,
            "from_wh" => $this->from_wh,
            "to_wh" => $this->to_wh,
        );

        $result = $this->db->insert('tsc_in_out_bound_h', $data);
        if($result) return true; else return false;
    }
    //----

    function call_store_procedure_new_in_out_bound(){
        $db = $this->load->database('default', true);
        $query = $db->query("call NEWINOUTBOUND('".$this->prefix_code."','".$this->doc_type."','".$this->doc_location_code."','".$this->month_end."', '".$this->created_user."', '".$this->external_document."', '".$this->status."')")->row();

        return $query->trsc_no;
    }
    //------------------

    function check_h_by_doc_no($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "select doc_no from tsc_in_out_bound_h ";

        // where condition
        $query_temp.=" where doc_no in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" );";
        //---

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function list_with_doc_type_one_and_qty($status){
      $db = $this->load->database('default', true);

      $query_temp = "SELECT h.doc_no as doc_no, doc_datetime as doc_datetime, doc_location_code as doc_location_code, created_user as user, u.name as uname, sum(d.qty) as qty,sum(d.qty_received) as qty_received,sum(d.qty_to_ship) as qty_to_ship , sum(d.qty_outstanding) as qty_outstanding,
       sum(d.qty_to_picked) as qty_to_picked, status1 ,sts.name as sts_name, d.uom as uom, h.doc_date as doc_date, text, month_end, external_document, d.src_no as so_no, so.bill_cust_no,so.bill_cust_name,so.ship_to_city, transfer_from_wh, transfer_to_wh, from_wh, to_wh
      FROM tsc_in_out_bound_h h
      inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
      inner join user u on(h.created_user=u.user_id)
      inner join tsc_in_out_bound_h_status sts on(h.status1=sts.code)
      left join tsc_so so on(d.src_no=so.so_no)
      where h.doc_type='".$this->doc_type."' and h.canceled='0' ";

      // where condition
      $query_temp.=" and  status1 in( ";
      foreach($status as $row){ $query_temp.="'".$row."',";}
      $query_temp = substr($query_temp,0,-1);
      $query_temp.=" ) ";

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp.=" and doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3
      //---

      $query_temp.=" group by doc_no,doc_datetime, doc_location_code, created_user,u.name,status1,sts.name,doc_date";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function update_status(){
      $db = $this->load->database('default', true);
      $query_temp = "update tsc_in_out_bound_h t set status1='".$this->status."' where doc_no='".$this->doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //---

    function get_list_inbound_done(){
        $db = $this->load->database('default', true);

        $user_plant = get_plant_code_user();// 2023-03-02 WH3

        $query_temp = "
        select * from (
        select inout_doc_no, sum(qty) as qty, sum(qty_to_put) as qty_to_put,
        if(qty = qty_to_put,'finished','not finished yet') as statuss
        from (
        select tbl_inout.doc_no as inout_doc_no, tbl_recv.doc_no as recv_doc_no, tbl_putaway.doc_no as putaway_no ,qty_outstanding, tbl_recv.qty as qty,
        if(qty_to_put is null,0,qty_to_put) as qty_to_put
        from (
        SELECT d.doc_no,sum(qty) as qty FROM tsc_in_out_bound_h h inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no) where status1='3' and canceled='0' and doc_location_code in(".$user_plant.") group by d.doc_no) as tbl_inout

        left join
        (SELECT h.doc_no, in_bound_no,sum(qty_outstanding) as qty_outstanding,sum(qty) as qty
        FROM tsc_received_h h inner join tsc_received_d d on(h.doc_no=d.doc_no) where h.status_h='5' group by h.doc_no, in_bound_no) as tbl_recv
        on(tbl_recv.in_bound_no=tbl_inout.doc_no)

        left join
        (SELECT d.doc_no, src_no, sum(qty_to_put) as qty_to_put
        FROM tsc_put_away_h h inner join tsc_put_away_d d on(h.doc_no=d.doc_no) where statuss='7'  group by d.doc_no, src_no) as tbl_putaway
        on(tbl_putaway.src_no=tbl_recv.doc_no)) as tbl_all group by inout_doc_no) as tbl_all2 where statuss='finished'";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----

    function list_with_doc_type_one_and_qty_by_status(){
      $db = $this->load->database('default', true);

      $user_plant = get_plant_code_user();// 2023-03-02 WH3

      $query_temp = "SELECT h.doc_no as doc_no, doc_datetime as doc_datetime, doc_location_code as doc_location_code, created_user as user, u.name as uname, sum(d.qty) as qty,sum(d.qty_to_ship) as qty_to_ship, sts.name as sts_name, d.uom as uom, putaway_finished
      FROM tsc_in_out_bound_h h
      inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
      inner join user u on(h.created_user=u.user_id)
      inner join tsc_in_out_bound_h_status sts on(h.status1=sts.code)
      where h.doc_type='1' and status1='".$this->status."' and h.canceled='0' ";

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp.=" and doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3

      $query_temp.=" group by doc_no,doc_datetime, doc_location_code, created_user,u.name,status1,sts.name";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function update_put_away_finished(){
      $db = $this->load->database('default', true);
      $query_temp = "update tsc_in_out_bound_h t set putaway_finished='".$this->putaway_finished."' where doc_no='".$this->doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //---

    function get_one_doc($doc_type,$status){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT h.doc_no as doc_no, doc_datetime as doc_datetime, doc_location_code as doc_location_code, created_user as user, u.name as uname, sts.name as sts_name, putaway_finished, doc_date
      FROM tsc_in_out_bound_h h
      inner join user u on(h.created_user=u.user_id)
      inner join tsc_in_out_bound_h_status sts on(h.status1=sts.code)
      where h.doc_type='".$doc_type."' and status1='".$status."' ";

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp.=" and doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function update_message(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_h set text='".$this->text."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //--

    function get_status(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT status1 FROM tsc_in_out_bound_h where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp)->row();
        return $query->status1;
    }
    //---

    function get_list_outbound_done(){
        $db = $this->load->database('default', true);

        $user_plant = get_plant_code_user();// 2023-03-02 WH3

        $query_temp = "
        select * from (
        select inout_doc_no, sum(qty) as qty, sum(qty_to_pick) as qty_to_pick,
        if(qty = qty_to_pick,'finished','not finished yet') as statuss
        from (
        select tbl_inout.doc_no as inout_doc_no, tbl_recv.doc_no as recv_doc_no, tbl_pick.doc_no as putaway_no ,qty_outstanding, tbl_recv.qty as qty,
        if(qty_to_pick is null,0,qty_to_pick) as qty_to_pick
        from (
        SELECT d.doc_no,sum(qty) as qty FROM tsc_in_out_bound_h h inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no) where status1='3' and h.canceled='0' and doc_location_code in(".$user_plant.") group by d.doc_no) as tbl_inout

        left join
        (SELECT h.doc_no, in_bound_no,sum(qty_outstanding) as qty_outstanding,sum(qty) as qty
        FROM tsc_received_h h inner join tsc_received_d d on(h.doc_no=d.doc_no) group by h.doc_no, in_bound_no) as tbl_recv
        on(tbl_recv.in_bound_no=tbl_inout.doc_no)

        left join
        (SELECT d.doc_no, src_no, sum(qty_to_picked) as qty_to_pick
        FROM tsc_pick_h h inner join tsc_pick_d d on(h.doc_no=d.doc_no) where statuss='12'  group by d.doc_no, src_no) as tbl_pick
        on(tbl_pick.src_no=tbl_recv.doc_no)) as tbl_all group by inout_doc_no) as tbl_all2 where statuss='finished'";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----

    function update_pick_finished(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_h t set pick_finished_datetime='".$this->pick_finished."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_data_whship_with_qty_has_picked(){
        $db = $this->load->database('default', true);

        $user_plant = get_plant_code_user();// 2023-03-02 WH3
        $query_temp.=" and doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3

        $query_temp = "select * from(
        SELECT h.doc_no as doc_no, doc_datetime as doc_datetime, doc_location_code as doc_location_code, created_user as user, u.name as uname, sum(d.qty) as qty,sum(d.qty_received) as qty_received,sum(d.qty_to_ship) as qty_to_ship , sum(d.qty_outstanding) as qty_outstanding,
               sum(d.qty_to_picked) as qty_to_picked, status1 ,sts.name as sts_name, d.uom as uom, h.doc_date as doc_date, text,
               d.src_no as so_no, so.bill_cust_no,so.bill_cust_name,so.ship_to_city, skip_scan, external_document, month_end, urgent, h.qc_user, uqc.name as qc_name, h.locked
              FROM tsc_in_out_bound_h h
              inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
              inner join user u on(h.created_user=u.user_id)
              inner join tsc_in_out_bound_h_status sts on(h.status1=sts.code)
              left join tsc_so so on(d.src_no=so.so_no)
              left join user uqc on(h.qc_user=uqc.user_id)
              where h.doc_type='2' and  status1 in('1') and h.canceled='0' and h.doc_location_code in(".$user_plant.")

         group by doc_no,doc_datetime, doc_location_code, created_user,u.name,status1,sts.name,doc_date) as tbl_out

        left join(
        select tbl_pick.src_no as src_no, tbl_pick.statuss as statuss, tbl_pick.qty_has_picked as qty_has_picked,tbl_pick.assign_user,u.name as assign_name
        from(
        SELECT src_no, statuss,assign_user,
        sum(if(completely_picked is not null,qty_to_picked,0)) as qty_has_picked
        FROM tsc_pick_h h left join tsc_pick_d d on(h.doc_no=d.doc_no)
        group by src_no) as tbl_pick
        inner join user u on(tbl_pick.assign_user=u.user_id)
        inner join tsc_in_out_bound_h h  on(h.doc_no=tbl_pick.src_no)
        where h.status1='1' and h.doc_location_code in(".$user_plant.")) as tbl_pick on(tbl_out.doc_no=tbl_pick.src_no)

        left join(
          SELECT h.doc_no as doc_pick_d2, count(d2.item_code) as qty_picked_d2
FROM tsc_pick_d2 d2
inner join tsc_pick_d d on(d.doc_no=d2.src_no and d.line_no=d2.src_line_no)
inner join tsc_in_out_bound_h h on(d.src_no=h.doc_no)
where h.status1='1' group by h.doc_no) as tbl_pick_d2 on(tbl_pick_d2.doc_pick_d2=tbl_out.doc_no)

        order by urgent desc, doc_datetime;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update_month_end(){
      $db = $this->load->database('default', true);
      $query_temp = "update tsc_in_out_bound_h t set month_end='".$this->month_end."' where doc_no='".$this->doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //---

    function list_with_doc_type_one_and_qty_month_end_submitted_null($status,$month_end){
      $db = $this->load->database('default', true);

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp.=" and doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3

      $query_temp = "select h.doc_no as doc_no, doc_datetime as doc_datetime, doc_location_code as doc_location_code, created_user as user, u.name as uname, sum(d.qty) as qty,sum(d.qty_received) as qty_received,sum(d.qty_to_ship) as qty_to_ship , sum(d.qty_outstanding) as qty_outstanding,
       sum(d.qty_to_picked) as qty_to_picked, status1 ,sts.name as sts_name, d.uom as uom, h.doc_date as doc_date, text, month_end, submitted,
       so.bill_cust_no,so.bill_cust_name,so.ship_to_city
      FROM tsc_in_out_bound_h h
      inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
      inner join user u on(h.created_user=u.user_id)
      inner join tsc_in_out_bound_h_status sts on(h.status1=sts.code)
      left join tsc_so so on(d.src_no=so.so_no)
      where h.doc_type='".$this->doc_type."' and (submitted is null or submitted='') and h.canceled='0' ";

      // where condition
      $query_temp.=" and  status1 in( ";
      foreach($status as $row){ $query_temp.="'".$row."',"; }
      $query_temp = substr($query_temp,0,-1);
      $query_temp.=" ) ";

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp.=" and doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3
      //---

      $query_temp.=" group by doc_no,doc_datetime, doc_location_code, created_user,u.name,status1,sts.name,doc_date ";
      $query_temp.=" union ";

      $query_temp.= " SELECT h.doc_no as doc_no, doc_datetime as doc_datetime, doc_location_code as doc_location_code, created_user as user, u.name as uname, sum(d.qty) as qty,sum(d.qty_received) as qty_received,sum(d.qty_to_ship) as qty_to_ship , sum(d.qty_outstanding) as qty_outstanding,
       sum(d.qty_to_picked) as qty_to_picked, status1 ,sts.name as sts_name, d.uom as uom, h.doc_date as doc_date, text, month_end, submitted,
       so.bill_cust_no,so.bill_cust_name,so.ship_to_city
      FROM tsc_in_out_bound_h h
      inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
      inner join user u on(h.created_user=u.user_id)
      inner join tsc_in_out_bound_h_status sts on(h.status1=sts.code)
      inner join tsc_so so on(d.src_no=so.so_no)
      where h.doc_type='".$this->doc_type."' and month_end='".$month_end."' and status1 not in('13','16') and (submitted is null or submitted='') ";

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp.=" and doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3

      $query_temp.=" group by doc_no,doc_datetime, doc_location_code, created_user,u.name,status1,sts.name,doc_date ";
      $query_temp.=" order by month_end ";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function check_doc_locked(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT if(locked is null or locked='0',0,1) as locked, user_locked FROM tsc_in_out_bound_h t where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp)->row();
        $result["locked"] = $query->locked;
        $result["user_locked"] = $query->user_locked;
        return $result;
    }
    //---

    function update_doc_to_locked($user){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_h t set locked='1', user_locked='".$user."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function update_doc_to_unlocked(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_h t set locked='0', user_locked='0' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_locked_document($user){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc_no, user_locked, u.name FROM tsc_in_out_bound_h h inner join user u on(h.user_locked=u.user_id) where locked='1' and user_locked like '".$user."%';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update_submitted_text(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_h t set submitted_text='".$this->submitted_text."', submitted='".$this->submitted."', submitted_datetime='".$this->submitted_datetime."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function dsh_received_outstanding(){
        $db = $this->load->database('default', true);
        $query_temp = "select sum(qty)-sum(qty_received) as qty_outstand_not_received from(
              select h.doc_no, sum(qty) as qty, sum(qty_received) as qty_received
              from tsc_in_out_bound_h h inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
              where h.status1 in('1','2') and doc_type='1' group by h.doc_no) as tbl;";
        $query = $db->query($query_temp)->row();
        return $query->qty_outstand_not_received;
    }
    //---

    function dsh_received(){
        $db = $this->load->database('default', true);
        $query_temp = "select sum(qty_received) as qty_received from(
            select h.doc_no, sum(qty) as qty, sum(qty_received) as qty_received
            from tsc_in_out_bound_h h inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
            where h.status1 in('3') and doc_type='1' and h.canceled='0' group by h.doc_no) as tbl;";
        $query = $db->query($query_temp)->row();
        return $query->qty_received;
    }
    //---

    function dsh_outstanding_whship(){
        $db = $this->load->database('default', true);
        $query_temp = "select sum(qty_outstanding) as outstanding_whship from (
          SELECT h.doc_no, sum(qty_to_ship) as qty_to_ship, sum(qty_outstanding) as qty_outstanding
          FROM tsc_in_out_bound_h h inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
          where status1 in('1') and doc_type='2' and h.canceled='0' group by h.doc_no) as tbl;";
        $query = $db->query($query_temp)->row();
        return $query->outstanding_whship;
    }
    //---

    function dsh_outstanding_qc(){
        $db = $this->load->database('default', true);
        $query_temp = "select sum(qty_has_picked) as outstanding_qc
        from(
        SELECT h.doc_no as doc_no, doc_datetime as doc_datetime, doc_location_code as doc_location_code, created_user as user, u.name as uname, sum(d.qty) as qty,sum(d.qty_received) as qty_received,sum(d.qty_to_ship) as qty_to_ship , sum(d.qty_outstanding) as qty_outstanding,
               sum(d.qty_to_picked) as qty_to_picked, status1 ,sts.name as sts_name, d.uom as uom, h.doc_date as doc_date, text
              FROM tsc_in_out_bound_h h
              inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
              inner join user u on(h.created_user=u.user_id)
              inner join tsc_in_out_bound_h_status sts on(h.status1=sts.code)
              where h.doc_type='2' and  status1 in('1') and h.canceled='0'

         group by doc_no,doc_datetime, doc_location_code, created_user,u.name,status1,sts.name,doc_date) as tbl_out

        left join(
        select tbl_pick.src_no as src_no, tbl_pick.statuss as statuss, tbl_pick.qty_has_picked as qty_has_picked,tbl_pick.created_user,u.name as assign_name
        from(
        SELECT src_no, statuss,created_user,
        sum(if(completely_picked is not null,qty_to_picked,0)) as qty_has_picked
        FROM tsc_pick_h h left join tsc_pick_d d on(h.doc_no=d.doc_no)
        group by src_no) as tbl_pick
        inner join user u on(tbl_pick.created_user=u.user_id)
        inner join tsc_in_out_bound_h h  on(h.doc_no=tbl_pick.src_no)
        where h.status1='1') as tbl_pick on(tbl_out.doc_no=tbl_pick.src_no) where qty_to_ship = qty_has_picked;";
        $query = $db->query($query_temp)->row();
        return $query->outstanding_qc;
    }
    //---

    function dsh_outstanding_packed(){
        $db = $this->load->database('default', true);
        $query_temp = "select sum(qty_to_ship-qty_to_packed) as outstanding_packed
            from(
            select doc_no,
            if(qty_to_ship is null,0, qty_to_ship) as qty_to_ship,
            if(qty_to_packed is null,0, qty_to_packed) as qty_to_packed
            from (
            SELECT h.doc_no, sum(qty_to_ship) as qty_to_ship
            FROM tsc_in_out_bound_h h inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no) where status1 in ('13') and doc_type='2' and h.canceled='0' group by h.doc_no) as tbl_whship

            left join (
            SELECT h.src_no, sum(qty_to_packed) as qty_to_packed
            FROM tsc_pack_h h inner join tsc_pack_d d on(h.doc_no=d.doc_no) group by h.src_no) as tbl_pack
            on (tbl_whship.doc_no=tbl_pack.src_no)) as tbl;";
        $query = $db->query($query_temp)->row();
        return $query->outstanding_packed;
    }
    //---

    function dsh_outstanding_whship_doc(){
        $db = $this->load->database('default', true);
        $query_temp = "select count(doc_no) as outstanding_doc from (
          SELECT h.doc_no, sum(qty_to_ship) as qty_to_ship, sum(qty_outstanding) as qty_outstanding
          FROM tsc_in_out_bound_h h inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
          where status1 in('1') and doc_type='2' and h.canceled='0' group by h.doc_no) as tbl;";
        $query = $db->query($query_temp)->row();
        return $query->outstanding_doc;
    }
    //---

    function dsh_outstanding_qc_doc(){
        $db = $this->load->database('default', true);
        $query_temp = "select count(doc_no) as outstanding_qc_doc from(
          select doc_no
        from(
        SELECT h.doc_no as doc_no, doc_datetime as doc_datetime, doc_location_code as doc_location_code, created_user as user, u.name as uname, sum(d.qty) as qty,sum(d.qty_received) as qty_received,sum(d.qty_to_ship) as qty_to_ship , sum(d.qty_outstanding) as qty_outstanding,
               sum(d.qty_to_picked) as qty_to_picked, status1 ,sts.name as sts_name, d.uom as uom, h.doc_date as doc_date, text
              FROM tsc_in_out_bound_h h
              inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
              inner join user u on(h.created_user=u.user_id)
              inner join tsc_in_out_bound_h_status sts on(h.status1=sts.code)
              where h.doc_type='2' and  status1 in('1') and h.canceled='0'

         group by doc_no,doc_datetime, doc_location_code, created_user,u.name,status1,sts.name,doc_date) as tbl_out

        left join(
        select tbl_pick.src_no as src_no, tbl_pick.statuss as statuss, tbl_pick.qty_has_picked as qty_has_picked,tbl_pick.created_user,u.name as assign_name
        from(
        SELECT src_no, statuss,created_user,
        sum(if(completely_picked is not null,qty_to_picked,0)) as qty_has_picked
        FROM tsc_pick_h h left join tsc_pick_d d on(h.doc_no=d.doc_no)
        group by src_no) as tbl_pick
        inner join user u on(tbl_pick.created_user=u.user_id)
        inner join tsc_in_out_bound_h h  on(h.doc_no=tbl_pick.src_no)
        where h.status1='1') as tbl_pick on(tbl_out.doc_no=tbl_pick.src_no) where qty_to_ship = qty_has_picked group by doc_no) as tbl;";
        $query = $db->query($query_temp)->row();
        return $query->outstanding_qc_doc;
    }
    //---

    function dsh_outstanding_packed_doc(){
        $db = $this->load->database('default', true);
        $query_temp = "select count(doc_no) as outstanding_packed_doc
            from(
            select doc_no,
            if(qty_to_ship is null,0, qty_to_ship) as qty_to_ship,
            if(qty_to_packed is null,0, qty_to_packed) as qty_to_packed
            from (
            SELECT h.doc_no, sum(qty_to_ship) as qty_to_ship
            FROM tsc_in_out_bound_h h inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no) where status1 in ('13') and doc_type='2' and h.canceled='0' group by h.doc_no) as tbl_whship

            left join (
            SELECT h.src_no, sum(qty_to_packed) as qty_to_packed
            FROM tsc_pack_h h inner join tsc_pack_d d on(h.doc_no=d.doc_no) group by h.src_no) as tbl_pack
            on (tbl_whship.doc_no=tbl_pack.src_no)) as tbl where qty_to_ship!=qty_to_packed;";
        $query = $db->query($query_temp)->row();
        return $query->outstanding_packed_doc;
    }
    //---

    function get_whrcpt_doc_datetime_one_doc(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc_datetime as doc_datetime  FROM tsc_in_out_bound_h t where doc_no='".$this->doc_no."' and doc_type='".$this->doc_type."';";
        $query = $db->query($query_temp)->row();
        return $query->doc_datetime;
    }
    //----

    function is_exist_inout_bound(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc_no as doc_datetime  FROM tsc_in_out_bound_h t where doc_no='".$this->doc_no."' and doc_type='".$this->doc_type."';";
        $query = $db->query($query_temp);
        $row = $query->result_array();
        if($row > 0) return true;
        else return false;
    }
    //----

    function get_list_in_out_bound_report($date_from, $date_to){
        $db = $this->load->database('default', true);

        //$user_plant = get_plant_code_user();// 2023-03-02 WH3
        //$query_temp2=" and doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3
        $query_temp2 = "";

        $query_temp = "SELECT h.doc_no, h.created_datetime, h.doc_datetime, h.doc_type, h.doc_location_code, h.month_end, h.status1, hstatus.name as status_name, h.text, h. submitted, h.submitted_text, h. submitted_datetime,
        h.doc_posting_date, sum(qty) as qty, sum(qty_received) as qty_received, sum(qty_to_ship) as qty_to_ship, d.uom,
        d.src_no as so_no, so.bill_cust_no,so.bill_cust_name, pack_finished_datetime, month_end, canceled, canceled_datetime, canceled_text, ship_to_post_code
        FROM tsc_in_out_bound_h h
        inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
        inner join tsc_in_out_bound_h_status hstatus on(hstatus.code=h.status1)
        left join tsc_so so on(d.src_no=so.so_no)
        where doc_type in ('1','2') and date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."' ".$query_temp2."
        group by h.doc_no, h.created_datetime, h.doc_datetime, h.doc_type, h.doc_location_code, h.month_end, h.status1, hstatus.name, h.text, h.submitted, h.submitted_text, h. submitted_datetime,
        h.doc_posting_date, d.uom, so.so_no";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function check_month_end_and_not_submitted(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM tsc_in_out_bound_h t where doc_no='".$this->doc_no."' and submitted is null and month_end='1'";
        $query = $db->query($query_temp);
        $row = $query->result_array();
        if($row > 0) return true;
        else return false;
    }
    //---

    function check_month_end_and_submitted(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM tsc_in_out_bound_h t where doc_no='".$this->doc_no."' and submitted is not null and month_end='1'";
        $query = $db->query($query_temp);
        $row = $query->result_array();
        if($row > 0) return true;
        else return false;
    }
    //---

    function get_one_doc_h(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM tsc_in_out_bound_h t where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update_pack_finished($datetime,$doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_h t set pack_finished_datetime='".$datetime."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    // 2022-11-04
    function canceled_doc($datetime, $doc_no, $text){
      $db = $this->load->database('default', true);
      $query_temp = "update tsc_in_out_bound_h t set canceled_datetime='".$datetime."', canceled=1, canceled_text='".$text."' where doc_no='".$doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //---

    // 2022-11-04
    function check_if_doc_has_been_canceled(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM tsc_in_out_bound_h t where doc_no='".$this->doc_no."' and canceled=1;";
        $query = $db->query($query_temp);
        $result = $query->result_array();
        if(count($result) > 0) return true;
        else return false;
    }
    //--

    // 2022-11-04
    function skip_scan_doc($doc_no, $status){
      $db = $this->load->database('default', true);
      $query_temp = "update tsc_in_out_bound_h t set skip_scan='".$status."' where doc_no='".$doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //---

    // 2022-11-24
    function list_with_doc_type_one_and_qty_finished_pack($status,$status_pack){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT h.doc_no as doc_no, doc_datetime as doc_datetime, doc_location_code as doc_location_code, created_user as user, u.name as uname, sum(d.qty) as qty,sum(d.qty_received) as qty_received,sum(d.qty_to_ship) as qty_to_ship , sum(d.qty_outstanding) as qty_outstanding,
       sum(d.qty_to_picked) as qty_to_picked, status1 ,sts.name as sts_name, d.uom as uom, h.doc_date as doc_date, text, month_end, external_document, d.src_no as so_no, so.bill_cust_no,so.bill_cust_name,so.ship_to_city
      FROM tsc_in_out_bound_h h
      inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
      inner join user u on(h.created_user=u.user_id)
      inner join tsc_in_out_bound_h_status sts on(h.status1=sts.code)
      left join tsc_so so on(d.src_no=so.so_no)
      where h.doc_type='".$this->doc_type."' and h.canceled='0' ";

      // where condition
      $query_temp.=" and  status1 in( ";
      foreach($status as $row){ $query_temp.="'".$row."',";}
      $query_temp = substr($query_temp,0,-1);
      $query_temp.=" ) ";

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp.=" and doc_location_code in(".$user_plant.") "; // 2023-03-02 WH3
      //---

      if($status_pack == "pack") $query_temp.=" and (pack_finished_datetime is null) ";
      else if($status_pack == "finish_pack") $query_temp.=" and (pack_finished_datetime is not null) ";

      $query_temp.=" group by doc_no,doc_datetime, doc_location_code, created_user,u.name,status1,sts.name,doc_date";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    // 2023-02-28
    function get_wship_with_detail_zero(){
        $db = $this->load->database('default', true);

        $query_temp = "select * from ( SELECT h.doc_no, count(d.doc_no) as total_line FROM tsc_in_out_bound_h h left join tsc_in_out_bound_d d on(h.doc_no=d.doc_no) where doc_type='2' group by h.doc_no) as tbl where total_line <= 0;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-02-28
    function delete_doc($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "delete from tsc_in_out_bound_h where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    // 2023-02-28
    function insert_in_out_bound_deleted_from_h($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "INSERT INTO tsc_in_out_bound_h_deleted
            SELECT *
            FROM tsc_in_out_bound_h
            WHERE doc_no='".$doc_no."';";

        $query = $db->query($query_temp);
        return true;
    }
    //---

    // 2023-03-13
    function urgent($doc_no, $urgent){
      $db = $this->load->database('default', true);
      $query_temp = "update tsc_in_out_bound_h t set urgent='".$urgent."' where doc_no='".$doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //---

    // 2023-07-12
    function update_user_qc($doc_no, $qc_user){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_h t set qc_user='".$qc_user."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---


}


?>
