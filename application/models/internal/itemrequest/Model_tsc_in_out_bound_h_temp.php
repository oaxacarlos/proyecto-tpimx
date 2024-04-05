<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_tsc_in_out_bound_h_temp extends CI_Model{

    function insert($doc_no, $created_datetime, $doc_datetime, $doc_type, $doc_location_code, $created_user, $external_document, $status, $doc_date, $canceled, $approval_level, $cust_no, $ship_to_code, $name, $address, $address2, $city, $contact, $country_region_code, $post_code, $county){
      $db = $this->load->database('default', true);
      $data = array(
          "doc_no" => $doc_no,
          "created_datetime" => $created_datetime,
          "doc_datetime" => $doc_datetime,
          "doc_type" => $doc_type,
          "doc_location_code" => $doc_location_code,
          "created_user" => $created_user,
          "external_document" => $external_document,
          "status1" => $status,
          "doc_date" => $doc_date,
          "text" => $text,
          "canceled" => $canceled,
          "approval_level" => $approval_level,
          "cust_no" => $cust_no,
          "ship_to_code" => $ship_to_code,
          "name" => $name,
          "address" => $address,
          "address2" => $address2,
          "city" => $city,
          "contact" => $contact,
          "country_region_code" => $country_region_code,
          "post_code" => $post_code,
          "county" => $county,
      );

      $result = $this->db->insert('tsc_in_out_bound_h_temp', $data);
      if($result) return true; else return false;
    }
    //--

    function get_data($status){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT doc_no, created_datetime, doc_datetime, doc_type, doc_type.name as doc_typename, doc_location_code, created_user, u.name as username, external_document,
      status1, stat.name as status_name, doc_date, approval_level, cust_no, ship_to_code, h.name as cust_name,h.address,h.address2, h.city,h.contact, h.country_region_code,
      h.post_code, h.county,ref_doc
      FROM tsc_in_out_bound_h_temp h
      inner join user u on(u.user_id=h.created_user)
      inner join mst_in_out_bound_type doc_type on(doc_type.code=h.doc_type)
      inner join tsc_in_out_bound_h_status stat on(stat.code=h.status1) where status1 = '".$status."';";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //--

    function update_status($status, $doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_h_temp set status1='".$status."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_status($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT status1 FROM tsc_in_out_bound_h_temp h where doc_no='".$doc_no."';";
        $query = $db->query($query_temp)->row();
        return $query->status1;
    }
    //---

    function get_data_by_doc_no($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM tsc_in_out_bound_h_temp h where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update_ref_doc($doc_no, $ref_doc){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_h_temp set ref_doc='".$ref_doc."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_data_by_period($status,$from,$to){
      $db = $this->load->database('default', true);
      $query_temp = "select h.doc_no, created_datetime, doc_datetime, doc_type, doc_type.name as doc_typename, doc_location_code, created_user, u.name as username, external_document,
      status1, stat.name as status_name, doc_date, approval_level, cust_no, ship_to_code, h.name as cust_name,h.address,h.address2, h.city,h.contact, h.country_region_code,ref_doc,
      h.post_code, h.county, sum(qty_edited) as qty_edited
      FROM tsc_in_out_bound_h_temp h
      inner join user u on(u.user_id=h.created_user)
      inner join mst_in_out_bound_type doc_type on(doc_type.code=h.doc_type)
      inner join tsc_in_out_bound_h_status stat on(stat.code=h.status1)
      inner join tsc_in_out_bound_d_temp d on(h.doc_no=d.doc_no)
      where status1 in (".$status.") and doc_date between '".$from."' and '".$to."'
      group by h.doc_no,created_datetime, doc_datetime, doc_type, doc_type.name, doc_location_code, created_user
      ;";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //--

    function cancel_doc($doc_no, $canceled_datetime, $canceled_by, $canceled_text){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_h_temp set status1='0', canceled_datetime='".$canceled_datetime."', canceled_by='".$canceled_by."', canceled_text='".$canceled_text."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---
}
