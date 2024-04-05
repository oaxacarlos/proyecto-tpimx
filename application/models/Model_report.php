<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_report extends CI_Model{

    function report_gen_report_doc_released($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select tbl_user_picked.assign_user as assign_user, u.name as username,tbl_user_picked.src_no, doc_date, tbl_user_picked.qty_picked as  qty_picked, tbl_user_picked.line_no as line
              from (
              SELECT assign_user, src_no,doc_date, sum(qty_to_picked) as qty_picked, count(line_no) as line_no
              FROM tsc_pick_h h inner join tsc_pick_d d on(h.doc_no=d.doc_no) where all_finished_datetime is not null and date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."'
              group by assign_user, src_no ) as tbl_user_picked inner join user u on(u.user_id=tbl_user_picked.assign_user) order by username,doc_date,src_no;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function report_gen_report_doc_released2($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select tbl_user_picked.assign_user as assign_user, u.name as username, doc_date, tbl_user_picked.qty_picked as  qty_picked, tbl_user_picked.line_no as line
              from (
              SELECT assign_user, src_no,doc_date, sum(qty_to_picked) as qty_picked, count(line_no) as line_no
              FROM tsc_pick_h h inner join tsc_pick_d d on(h.doc_no=d.doc_no) where all_finished_datetime is not null and date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."'
              group by assign_user, doc_date ) as tbl_user_picked inner join user u on(u.user_id=tbl_user_picked.assign_user) order by username,doc_date;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function report_user_picked_qty($date_from, $date_to){
      $db = $this->load->database('default', true);
      $query_temp = "select assign_user,username, sum(qty_picked) as qty_picked
        from (
        select tbl_user_picked.assign_user as assign_user, u.name as username,tbl_user_picked.src_no, tbl_user_picked.qty_picked as qty_picked, tbl_user_picked.line_no as line
        from (
        SELECT assign_user, src_no, sum(qty_to_picked) as qty_picked, count(line_no) as line_no
        FROM tsc_pick_h h inner join tsc_pick_d d on(h.doc_no=d.doc_no) where all_finished_datetime is not null and
        date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."'
        group by assign_user, src_no ) as tbl_user_picked inner join user u on(u.user_id=tbl_user_picked.assign_user)) as tbl group by assign_user order by qty_picked desc";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function report_user_picked_line($date_from, $date_to){
      $db = $this->load->database('default', true);
      $query_temp = "select assign_user,username, sum(line) as line
        from (
        select tbl_user_picked.assign_user as assign_user, u.name as username,tbl_user_picked.src_no, tbl_user_picked.qty_picked as qty_picked, tbl_user_picked.line_no as line
        from (
        SELECT assign_user, src_no, sum(qty_to_picked) as qty_picked, count(line_no) as line_no
        FROM tsc_pick_h h inner join tsc_pick_d d on(h.doc_no=d.doc_no) where all_finished_datetime is not null and
        date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."'
        group by assign_user, src_no ) as tbl_user_picked inner join user u on(u.user_id=tbl_user_picked.assign_user)) as tbl group by assign_user order by line desc";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function report_gen_report_doc_released_put($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select tbl_user_put.assign_user as assign_user, u.name as username,tbl_user_put.src_no, doc_date, tbl_user_put.qty_put as  qty_put, tbl_user_put.line_no as line
              from (
              SELECT assign_user, src_no,doc_date, sum(qty_to_put) as qty_put, count(line_no) as line_no
              FROM tsc_put_away_h h inner join tsc_put_away_d d on(h.doc_no=d.doc_no)
              where statuss='7' and date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."'
              group by assign_user, src_no ) as tbl_user_put inner join user u on(u.user_id=tbl_user_put.assign_user) order by username,doc_date,src_no;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function report_gen_report_doc_released2_put($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select tbl_user_put.assign_user as assign_user, u.name as username, doc_date, tbl_user_put.qty_put as  qty_put, tbl_user_put.line_no as line
              from (
              SELECT assign_user, src_no,doc_date, sum(qty_to_put) as qty_put, count(line_no) as line_no
              FROM tsc_put_away_h h inner join tsc_put_away_d d on(h.doc_no=d.doc_no)
              where statuss='7' and date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."'
              group by assign_user, doc_date ) as tbl_user_put inner join user u on(u.user_id=tbl_user_put.assign_user) order by username,doc_date;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function report_user_put_qty($date_from, $date_to){
      $db = $this->load->database('default', true);
      $query_temp = "select assign_user,username, sum(qty_put) as qty_put
        from (
        select tbl_user_put.assign_user as assign_user, u.name as username,tbl_user_put.src_no, tbl_user_put.qty_put as qty_put, tbl_user_put.line_no as line
        from (
        SELECT assign_user, src_no, sum(qty_to_put) as qty_put, count(line_no) as line_no
        FROM tsc_put_away_h h inner join tsc_put_away_d d on(h.doc_no=d.doc_no) where statuss='7' and
        date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."'
        group by assign_user, src_no ) as tbl_user_put inner join user u on(u.user_id=tbl_user_put.assign_user)) as tbl group by assign_user order by qty_put desc";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function report_user_put_line($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select assign_user,username, sum(line) as line
          from (
          select tbl_user_put.assign_user as assign_user, u.name as username,tbl_user_put.src_no, tbl_user_put.qty_put as qty_put, tbl_user_put.line_no as line
          from (
          SELECT assign_user, src_no, sum(qty_to_put) as qty_put, count(line_no) as line_no
          FROM tsc_put_away_h h inner join tsc_put_away_d d on(h.doc_no=d.doc_no) where statuss='7' and
          date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."'
          group by assign_user, src_no ) as tbl_user_put inner join user u on(u.user_id=tbl_user_put.assign_user)) as tbl group by assign_user order by line desc";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function report_putaway_time_by_user($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select assign_user,u.name as name,doc_no,doc_datetime, start_datetime, all_finished_datetime, created_to_start,start_to_finish, created_to_finish,
        cal_created_to_start,cal_start_to_finish, cal_created_to_finish
          from (
          SELECT doc_no,assign_user, doc_datetime, start_datetime, all_finished_datetime, timediff(start_datetime,doc_datetime) as created_to_start,
          timediff(all_finished_datetime,start_datetime) as start_to_finish,
          timediff(all_finished_datetime,doc_datetime) as created_to_finish,
          TIME_TO_SEC(timediff(start_datetime,doc_datetime)) as cal_created_to_start,
          TIME_TO_SEC(timediff(all_finished_datetime,start_datetime)) as cal_start_to_finish,
          TIME_TO_SEC(timediff(all_finished_datetime,doc_datetime)) as cal_created_to_finish
          FROM tsc_put_away_h t where date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."') as tbl
          inner join user u on(tbl.assign_user=u.user_id) order by name, doc_no";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function report_pick_time_by_user($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select assign_user,u.name as name,doc_no,doc_datetime, start_datetime, all_finished_datetime, created_to_start,start_to_finish, created_to_finish,
        cal_created_to_start,cal_start_to_finish, cal_created_to_finish
          from (
          SELECT doc_no,assign_user, doc_datetime, start_datetime, all_finished_datetime, timediff(start_datetime,doc_datetime) as created_to_start,
          timediff(all_finished_datetime,start_datetime) as start_to_finish,
          timediff(all_finished_datetime,doc_datetime) as created_to_finish,
          TIME_TO_SEC(timediff(start_datetime,doc_datetime)) as cal_created_to_start,
          TIME_TO_SEC(timediff(all_finished_datetime,start_datetime)) as cal_start_to_finish,
          TIME_TO_SEC(timediff(all_finished_datetime,doc_datetime)) as cal_created_to_finish
          FROM tsc_pick_h t where date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."') as tbl
          inner join user u on(tbl.assign_user=u.user_id) order by name, doc_no";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function report_outstanding_amount_from_nav(){
        $db = $this->load->database('sql_server_live', true);
        $query_temp="select
          case when [WH Pick Status] = '1' then 'total_in_WMS'
          when [WH Pick Status] = '2' then 'total_finised_WMS_not_invoiced'  end as total_text
          ,FORMAT(sum(amount),'N', 'en-us') as total
          from (
          select shipd.[Source No_],shipd.[Item No_],shipd.[Qty_ to Ship], shipd.[Shipment Date],
          salesd.[Unit Price],shipd.[Qty_ to Ship]*salesd.[Unit Price] as amount, [WH Pick Status]
          from [".$this->config->item('sqlserver_live')."Warehouse Shipment Line] shipd
          inner join [".$this->config->item('sqlserver_live')."Warehouse Shipment Header] as shiph on(shiph.[No_]=shipd.[No_])
          inner join [".$this->config->item('sqlserver_live')."Sales Line] as salesd
          on(salesd.[Document No_] = shipd.[Source No_] and salesd.[No_]=shipd.[Item No_]
          and salesd.[Line No_]=shipd.[Source Line No_])) as tbl group by [WH Pick Status]";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function total_in_out_from_to($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select tbl_total_in.total, tbl_total_in.total_in, tbl_total_out.total_out
          from (
          SELECT 'total',count(doc_no) as total_in FROM tsc_in_out_bound_h t where doc_type='2' and doc_date between '".$date_from."' and '".$date_to."') as tbl_total_in
          left join(
          SELECT 'total',count(doc_no) as total_out FROM tsc_in_out_bound_h t where doc_type='2' and date_format(submitted_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."'
          and submitted='1'
          ) as tbl_total_out on(tbl_total_out.total = tbl_total_in.total)";
        $query = $db->query($query_temp)->row();
        $data["total_in"] = $query->total_in;
        $data["total_out"] = $query->total_out;
        return $data;
    }
    //---

    // 2023-04-11
    function get_doc_no_picking_by_user($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select assign_user, name as username, doc_no
          from (
          select assign_user, count(doc_no) as doc_no from (
          select distinct(src_no) as doc_no, assign_user
          FROM tsc_pick_h h inner join tsc_pick_d d on(h.doc_no=d.doc_no) where all_finished_datetime is not null and
                  date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."') as tbl_a group by assign_user) as tbl_a inner join user u on(u.user_id=tbl_a.assign_user) order by doc_no desc";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-04-11
    function report_gen_report_doc_picking_by_day($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select assign_user, name as username, doc_date, doc_no from (
          select assign_user, doc_date, count(doc_no) as doc_no from (
          select distinct(src_no) as doc_no, assign_user,h.doc_date
          FROM tsc_pick_h h inner join tsc_pick_d d on(h.doc_no=d.doc_no) where all_finished_datetime is not null and
                  date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."') as tbl_a group by assign_user, doc_date) as tbl_a
          inner join user u on(u.user_id=tbl_a.assign_user) order by assign_user, doc_date;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-05-22
    function report_gen_report_pick_consume_time_by_user($date_from, $date_to){
        $db = $this->load->database('default', true);

        $query_temp = "select assign_user,name as username, sum(diff_time)/60 as consume_time
          from (
          SELECT doc_no, assign_user, created_datetime, start_datetime, all_finished_datetime, timediff(all_finished_datetime,start_datetime) as diff_time
          FROM tsc_pick_h t where all_finished_datetime is not null and date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."') as tbl_a
          inner join user u on(u.user_id=tbl_a.assign_user)
          group by assign_user order by consume_time desc;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-22
    function report_gen_report_pick_consume_time_by_user_day($date_from, $date_to){
        $db = $this->load->database('default', true);

        $query_temp = "select doc_date, assign_user, username, sum(consume_time) as consume_time
        from (
        select doc_no, doc_date, assign_user, name as username, created_datetime, start_datetime, all_finished_datetime, timediff(all_finished_datetime,start_datetime)/60 as consume_time
                FROM tsc_pick_h t inner join user u on(u.user_id=t.assign_user)
                where all_finished_datetime is not null and date_format(doc_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."') as tbl_a group by assign_user, doc_date order by assign_user, doc_date;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-22
    function get_doc_no_qc_by_user($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select userid, name as username, total_doc as doc_no
          from (
          SELECT userid, count(doc1) as total_doc
          FROM tsc_doc_history t where status1='13' and doc1 like 'TPM-WSHIP-%' and userid is not null
          and date_format(created_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."'
          group by userid) as tbl_a
          inner join user u on(u.user_id=tbl_a.userid) order by total_doc desc;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-22
    function get_doc_no_qc_by_user_day($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select userid, name as username, doc_date, total_doc as doc_no
          from (
          select userid, date_format(created_datetime,'%Y-%m-%d') as doc_date,count(doc1) as total_doc
          FROM tsc_doc_history t where status1='13' and doc1 like 'TPM-WSHIP-%' and userid is not null
          and date_format(created_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."' group by userid,date_format(created_datetime,'%Y-%m-%d')) as tbl_a inner join user u on(u.user_id=tbl_a.userid)
          order by userid, doc_date";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-22
    function get_qty_qc_by_user($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select userid, name as username,sum(qty) as qty
          from (
          select doc1, created_datetime, userid,qty
          from (
          SELECT doc1, created_datetime, userid
          FROM tsc_doc_history t where status1='13' and doc1 like 'TPM-WSHIP-%' and userid is not null
          and date_format(created_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."') as tbl_doc_hist

          left join (
          SELECT doc_no, sum(qty_to_ship) as qty FROM tsc_in_out_bound_d t where doc_no in(
          SELECT doc1
          FROM tsc_doc_history t where status1='13' and doc1 like 'TPM-WSHIP-%' and userid is not null
          and date_format(created_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."') group by doc_no) as tbl_d on(tbl_doc_hist.doc1=tbl_d.doc_no)) as tbl_a
          inner join user u on(u.user_id=tbl_a.userid)
          group by userid order by qty desc;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-22
    function get_qty_qc_by_user_day($date_from, $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select userid, name as username, date_format(created_datetime,'%Y-%m-%d') as doc_date, sum(qty) as qty
          from (
          SELECT doc1, created_datetime, userid
          FROM tsc_doc_history t where status1='13' and doc1 like 'TPM-WSHIP-%' and userid is not null
          and date_format(created_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."') as tbl_doc_hist

          left join (
          SELECT doc_no, sum(qty_to_ship) as qty FROM tsc_in_out_bound_d t where doc_no in(
          SELECT doc1
          FROM tsc_doc_history t where status1='13' and doc1 like 'TPM-WSHIP-%' and userid is not null
          and date_format(created_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."') group by doc_no) as tbl_d on(tbl_doc_hist.doc1=tbl_d.doc_no)

          inner join user u on(u.user_id=tbl_doc_hist.userid)

          group by userid,name,date_format(created_datetime,'%Y-%m-%d') order by userid, doc_date";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-06-07
    function report_in_finished_detail($date_from , $date_to){
        $db = $this->load->database('default', true);
        $query_temp = "select tbl_a.doc_date, total_in, total_out
          from(
          SELECT distinct(doc_date) as doc_date FROM tsc_in_out_bound_h t
          where doc_type='2' and doc_no like 'TPM-WSHIP-%' and doc_date between '".$date_from."' and '".$date_to."' and canceled='0') as tbl_a

          left join(
          SELECT doc_date,count(doc_no) as total_in FROM tsc_in_out_bound_h t
          where doc_type='2' and doc_no like 'TPM-WSHIP-%' and doc_date between '".$date_from."' and '".$date_to."' and canceled='0' group by doc_date
          ) as tbl_b on(tbl_b.doc_date=tbl_a.doc_date)

          left join(
          SELECT date_format(submitted_datetime,'%Y-%m-%d') as doc_date,count(doc_no) as total_out FROM tsc_in_out_bound_h t
          where doc_type='2' and doc_no like 'TPM-WSHIP-%' and date_format(submitted_datetime,'%Y-%m-%d') between '".$date_from."' and '".$date_to."' and submitted='1' and canceled='0' group by date_format(submitted_datetime,'%Y-%m-%d')
          ) as tbl_c on(tbl_c.doc_date=tbl_a.doc_date);";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_detail_inoutbound($date_from, $date_to,$doc_type, $loc, $canceled, $internal){
        $db = $this->load->database('default', true);

        if($canceled == 1){
          $query_temp = "select h.doc_no, h.created_datetime,doc_date,doc_location_code, line_no,src_no,item_code,description,qty_to_ship,uom,dest_no,qty, hstatus.name as status_name, canceled
            FROM tsc_in_out_bound_h h
            inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
            left join tsc_in_out_bound_h_status hstatus on(hstatus.code=h.status1)
            where doc_date between '".$date_from."' and '".$date_to."' and doc_type='".$doc_type."' and doc_location_code in (".$loc.") and h.doc_no like '".$internal."%';";
        }
        else{
          $query_temp = "select h.doc_no, h.created_datetime,doc_date,doc_location_code, line_no,src_no,item_code,description,qty_to_ship,uom,dest_no,qty, hstatus.name as status_name, canceled
            FROM tsc_in_out_bound_h h
            inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
            left join tsc_in_out_bound_h_status hstatus on(hstatus.code=h.status1)
            where doc_date between '".$date_from."' and '".$date_to."' and doc_type='".$doc_type."' and doc_location_code in (".$loc.") and h.doc_no like '".$internal."%' and canceled='0';";
        }

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-07-20
    function item_conversion(){
      $db = $this->load->database('default', true);

      $query_temp = "select * FROM mst_item_uom_conv m;";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---
}

?>
