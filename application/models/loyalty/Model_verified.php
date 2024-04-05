<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_verified extends CI_Model{

    function get_data_not_verified(){
      $db = $this->load->database('default_client', true);
      $query_temp = "select h.doc_no, h.invc_no, h.created_at, h.created_by, u.name,h.remark1,h.remark2,h.remark3,d.line, d.item_code, d.desc, d.uom, qty, d.point, d.verified, d.verified_at, d.point_per_qty, h.invc_file
          FROM tsc_invc_h h
          inner join tsc_invc_d d on(h.doc_no = d.doc_no)
          inner join user u on(h.created_by = u.user_id)
          where d.verified is null order by h.doc_no, d.line;";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function get_status_verified($doc_no, $line){
        $db = $this->load->database('default_client', true);
        $query_temp = "select verified FROM tsc_invc_d t where doc_no='".$doc_no."' and line='".$line."';";
        $query = $db->query($query_temp);
        $verified = $query->row()->verified;

        if($verified == 1) return true;
        else return false;
    }
    //---

    function update_status_verified($doc_no, $line, $verified, $verified_at, $verified_by){
        $db = $this->load->database('default_client', true);
        $query_temp = "update tsc_invc_d set verified='".$verified."', verified_at='".$verified_at."', verified_by='".$verified_by."' where doc_no='".$doc_no."' and line='".$line."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function add_point_user($userid, $addpoint){
        $db = $this->load->database('default_client', true);
        $query_temp = "select point from user where user_id='".$userid."';";
        $query = $db->query($query_temp);
        $point = $query->row()->point;
        $point = $point + $addpoint;
        $query_temp = "update user set point='".$point."' where user_id='".$userid."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function insert_email($to, $cc, $subject, $from, $body, $datetime){
        $db = $this->load->database('default_client', true);
        $data = array(
          'to' => $to,
          'cc' => $cc,
          'subject' => $subject,
          'from' => $from,
          'body' => $body,
          'added_at' => $datetime,
          );

          $result = $this->db->insert('tsc_email', $data);

          if($result) return true; else return false;
    }
    //--

    function update_status_rejected($doc_no, $line, $verified, $rejected_at, $rejected_by){
        $db = $this->load->database('default_client', true);
        $query_temp = "update tsc_invc_d set verified='".$verified."', rejected_at='".$rejected_at."', rejected_by='".$rejected_by."' where doc_no='".$doc_no."' and line='".$line."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function add_point_all_user($userid, $addpoint){
        $db = $this->load->database('default_client', true);
        $query_temp = "select point_all from user where user_id='".$userid."';";
        $query = $db->query($query_temp);
        $point = $query->row()->point_all;
        $point = $point + $addpoint;
        $query_temp = "update user set point_all='".$point."' where user_id='".$userid."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_status_verified_header($doc_no){
        $db = $this->load->database('default_client', true);
        $query_temp = "select verified FROM tsc_invc_h t where doc_no='".$doc_no."'";
        $query = $db->query($query_temp);
        $verified = $query->row()->verified;
        if(is_null($verified)) return false;
        else return true;
    }
    //---

    function get_data_header($doc_no){
        $db = $this->load->database('default_client', true);
        $query_temp = "SELECT * FROM tsc_invc_h where doc_no = '".$doc_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_data_detail($doc_no){
        $db = $this->load->database('default_client', true);
        $query_temp = "SELECT * FROM tsc_invc_d where doc_no = '".$doc_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update_verified_by_doc_detail($doc_no, $verified_at, $verified_by){
        $db = $this->load->database('default_client', true);
        $query_temp = "update tsc_invc_d set verified='1', verified_at='".$verified_at."', verified_by='".$verified_by."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function update_verified_by_doc_header($doc_no, $verified_at, $verified_by){
        $db = $this->load->database('default_client', true);
        $query_temp = "update tsc_invc_h set verified='1', verified_at='".$verified_at."', verified_by='".$verified_by."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_point_doc_detail($doc_no){
        $db = $this->load->database('default_client', true);
        $query_temp = "select sum(point) as point FROM tsc_invc_d t where doc_no = '".$doc_no."';";
        $query = $db->query($query_temp);
        $point = $query->row()->point;
        return $point;
    }
    //---

    function get_status_can_reject($doc_no){
        $db = $this->load->database('default_client', true);
        $query_temp = "select doc_no FROM tsc_invc_h t where rejected is null and verified is null and doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        $doc_no = $query->row()->doc_no;

        if(is_null($doc_no) || $doc_no=="") return false;
        else return true;
    }
    //---

    function update_status_rejected_header($doc_no, $rejected_at, $rejected_by, $remark2){
        $db = $this->load->database('default_client', true);
        $query_temp = "update tsc_invc_h set verified='0', rejected='1' ,rejected_at='".$rejected_at."', rejected_by='".$rejected_by."', remark2='".$remark2."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function update_status_rejected_detail($doc_no, $rejected_at, $rejected_by){
        $db = $this->load->database('default_client', true);
        $query_temp = "update tsc_invc_d set verified='0', rejected_at='".$rejected_at."', rejected_by='".$rejected_by."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_loyalty_header($from, $to){
        $db = $this->load->database('default_client', true);
        $query_temp = "select h.doc_no, h.invc_no, h.created_at, h.created_by, u.name, sum(d.qty) as qty, sum(d.point) as points, invc_file, u.name
            FROM tsc_invc_h h
            inner join tsc_invc_d d on(h.doc_no = d.doc_no)
            inner join user u on(h.created_by = u.user_id)
            where date_format(h.created_at,'%Y-%m-%d') between '".$from."' and '".$to."'
            group by doc_no";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_detail_invc_d($doc_no){
        $db = $this->load->database('default_client', true);
        $query_temp = "SELECT * FROM tsc_invc_d t where doc_no = '".$doc_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_total_count_not_verified(){
        $db = $this->load->database('default_client', true);
        $query_temp = "SELECT count(doc_no) as total FROM tsc_invc_h t where verified is null;";
        $query = $db->query($query_temp);
        $total = $query->row()->total;
        return $total;
    }
    //--

    function insert_user_points_hist($user, $doc_no, $points, $remark1, $remark2, $created_on){
          $db = $this->load->database('default_client', true);
          $data = array(
              "user_id" => $user,
              "doc_no"  => $doc_no,
              "points"  => $points,
              "remark1" => $remark1,
              "remark2" => $remark2,
              "created_on" => $created_on,
          );

          $result = $db->insert('tsc_user_point_hist', $data);
          if($result) return true; else return false;
    }
    //---

    function get_mst_bonus_by_name($name, $active){
        $db = $this->load->database('default_client', true);
        $query_temp = "select * FROM mst_bonus where name='".$name."' and active='".$active."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function check_history_point($doc_no, $user){
        $db = $this->load->database('default_client', true);
        $query_temp = "select count(doc_no) as total FROM tsc_user_point_hist t where doc_no='".$doc_no."' and user_id='".$user."';";
        $query = $db->query($query_temp);
        $total = $query->row()->total;
        return $total;
    }
    //---

    function get_mst_point_lvl($name){
        $db = $this->load->database('default_client', true);
        $query_temp = "select point_min FROM mst_point_lvl where name='".$name."';";
        $query = $db->query($query_temp);
        $total = $query->row()->point_min;
        return $total;
    }
    //--
}

?>
