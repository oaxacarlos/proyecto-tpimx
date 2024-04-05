<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_packing_h extends CI_Model{
      var $doc_no,$created_datetime, $doc_datetime, $doc_type, $src_location_code, $all_finished_datetime, $created_user, $external_document, $statuss, $doc_date, $text1, $dest_no, $dest_addr, $dest_addr2, $dest_name, $dest_contact, $dest_county, $dest_country, $dest_post_code, $dest_city, $src_no;

      function call_store_procedure_newpacking(){
        $db = $this->load->database('default', true);
        $query_temp = "call NEWPACKING('".$this->prefix_code."', '".$this->created_datetime."', '".$this->doc_datetime."','".$this->doc_type."','".$this->src_location_code."','".$this->created_user."','".$this->external_document."', '".$this->statuss."',
        '".$this->doc_date."', '".$this->text1."','".$this->src_no."')";

        $query = $db->query($query_temp)->row();
        return $query->trsc_no;
      }
      //---

      function list_by_status($status){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT h.doc_no as doc_no, h.created_datetime, doc_datetime, h.src_location_code as src_location_code,
        h.created_user, u.name as uname, h.assign_user,statuss, sts.name as sts_name, sum(d.qty_to_packed) as qty, d.uom as uom, text1
        FROM tsc_pack_h h
        inner join tsc_in_out_bound_h_status sts on(h.statuss = sts.code)
        inner join user u on(u.user_id=h.created_user)
        inner join tsc_pack_d d on(h.doc_no=d.doc_no) ";

        // where condition
        $query_temp.=" where statuss in( ";
        foreach($status as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";

        $user_plant = get_plant_code_user();// 2023-03-02 WH3
        $query_temp.=" and src_location_code in(".$user_plant.") "; // 2023-03-02 WH3
        //---

        $query_temp.=" group by h.doc_no, h.created_datetime, doc_datetime, h.src_location_code, h.created_user, u.name, u2.name,statuss, sts.name, text1;";
        $query = $db->query($query_temp);
        return $query->result_array();
      }

      //---
      function update_dest(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_pack_h set dest_no='".$this->dest_no."', dest_name='".$this->dest_name."', dest_addr='".$this->dest_addr."', dest_addr2='".$this->dest_addr2."', dest_city='".$this->dest_city."', dest_contact='".$this->dest_contact."', dest_post_code='".$this->dest_post_code."', dest_county='".$this->dest_county."', dest_country='".$this->dest_country."'
        where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      function get_info_by_doc_no(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT * FROM tsc_pack_h t where doc_no='".$this->doc_no."'";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      function get_list_group_by_dest(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no, doc_date, dest_no, dest_addr, dest_addr2, dest_name, dest_contact, dest_county, dest_country, dest_post_code, dest_city FROM tsc_pack_h t where console is null or console='0'
          group by dest_no, dest_addr, dest_addr2, dest_name, dest_contact, dest_county, dest_country, dest_post_code, dest_city, doc_no;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function get_list_group_by_dest_with_no_console(){
          $db = $this->load->database('default', true);
          $query_temp = "select * from(
            SELECT doc_no, doc_date, dest_no, dest_addr, dest_addr2, dest_name, dest_contact, dest_county, dest_country, dest_post_code, dest_city
            FROM tsc_pack_h t where console is null or console='0'
            group by dest_no, dest_addr, dest_addr2, dest_name, dest_contact, dest_county, dest_country, dest_post_code, dest_city, doc_no) as tbl_pack
            where doc_no not in (SELECT src_no FROM tsc_console_d);";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_status(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_pack_h set statuss='".$this->statuss."' where doc_no='".$this->doc_no."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---
}

?>
