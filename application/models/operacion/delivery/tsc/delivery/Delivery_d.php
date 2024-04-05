<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_d extends CI_Model{

    function insert($new_doc_no, $doc_no, $doc_date, $so_ref, $cust_no, $subtotal, $total, $remarks, $cust_name, $address, $address2, $city, $state, $post_code, $country, $qty, $created_by, $created_at, $doc_type, $required_remark1){

      $db = $this->load->database('default_oprc', true);

      $query_temp = array();
      $line_no = 1;
      for($i=0; $i<count($doc_no); $i++){
          $query_temp2 = array(
              "doc_no" => $new_doc_no,
              "line_no" => $line_no,
              "invc_doc_no" => $doc_no[$i],
              "invc_doc_date" => $doc_date[$i],
              "so_ref" => $so_ref[$i],
              "invc_cust_no" => $cust_no[$i],
              "invc_cust_name" => replace_string($cust_name[$i]),
              "invc_address" => $address[$i],
              "invc_address2" => $address2[$i],
              "invc_city" => $city[$i],
              "invc_state" => $state[$i],
              "invc_post_code" => $post_code[$i],
              "invc_country" => $country[$i],
              "qty" => $qty[$i],
              "subtotal" => $subtotal[$i],
              "total" => $total[$i],
              "created_by" => $created_by,
              "created_at" => $created_at,
              "remark1" => $remarks[$i],
              "doc_type" => $doc_type[$i],
              "required_remark1" => $required_remark1[$i],
          );

          $query_temp[] = $query_temp2;
          $line_no++;
      }

      $query = $db->insert_batch('tsc_delivery_d',$query_temp);
      return true;

    }
    //---

    function get_data_by_docno($doc_no){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "SELECT * FROM tsc_delivery_d d where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update($doc_no_h, $doc_no, $doc_date, $so_ref, $cust_no, $subtotal, $total, $remarks, $cust_name, $address, $address2, $city, $state, $post_code, $country, $qty, $created_by, $created_at, $doc_type, $required_remark1){
        $db = $this->load->database('default_oprc', true);

        $update_rows = array();
        $multipleWhere = array();

        for($i=0;$i<$total_row;$i++){
            $update_rows_temp = array(
                "doc_no" => $doc_no_h,
                "line_no" => $line_no[$i],
                "invc_doc_no" => $doc_no[$i],
                "invc_doc_date" => $doc_date[$i],
                "so_ref" => $so_ref[$i],
                "invc_cust_no" => $cust_no[$i],
                "invc_cust_name" => replace_string($cust_name[$i]),
                "invc_address" => $address[$i],
                "invc_address2" => $address2[$i],
                "invc_city" => $city[$i],
                "invc_state" => $state[$i],
                "invc_post_code" => $post_code[$i],
                "invc_country" => $country[$i],
                "qty" => $qty[$i],
                "subtotal" => $subtotal[$i],
                "total" => $total[$i],
                "created_by" => $created_by,
                "created_at" => $created_at,
                "remark1" => $remarks[$i],
                "doc_type" => $doc_type[$i],
                "required_remark1" => $required_remark1[$i],
            );
            $update_rows[] = $update_rows_temp;

            $multipleWhere_temp = array('doc_no' => $doc_no_h, 'line_no' => $line_no[$i] );
            $multipleWhere[] = $multipleWhere_temp;
        }

        for($i=0;$i<count($multipleWhere);$i++){
          $this->db->where($multipleWhere[$i]);
          $this->db->update('tsc_pick_d2', $update_rows[$i]);
        }

        return true;
    }
    //--

    function delete_doc_no($doc_no){
      $db = $this->load->database('default_oprc', true);
      $query_temp = "delete FROM tsc_delivery_d where doc_no='".$doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //---

    function check_invc_has_applied($doc_no){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "select d.doc_no FROM tsc_delivery_d d inner join tsc_delivery_h h on(d.doc_no=h.doc_no) where invc_doc_no = '".$doc_no."' and canceled=0;";
        $query = $db->query($query_temp);
        $row = $query->result_array();
        if(count($row) > 0) return true; else false;
    }
    //---

    function get_data_by_period($from, $to){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "SELECT *, d.subtotal as d_subtotal,d.total as d_total FROM tsc_delivery_d d inner join tsc_delivery_h h on(d.doc_no=h.doc_no) where h.doc_date between '".$from."' and '".$to."' order by d.doc_no, d.line_no;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_detail_report($datefrom, $dateto){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "select h.doc_no, h.created_at, delv_date, destination, state, driver,vendor_no, vendor_name, tracking_no,folio,box,pallet, domicili, payment_term, h.subtotal as subtotal_delv, delv_status, receiv_date,
          receiv_person, h.total as total_delv, h.tax as delv_tax,u.name as created_by_name, payment_status, payment_date, h.remark1 as delv_remark1, h.remark2 as delv_remark2, invc_vendor_no, invc_vendor_date,
          invc_doc_no, invc_doc_date, so_ref, invc_cust_no, d.subtotal as invc_subtotal, d.total as invc_total, d.remark1 as invc_remark1, invc_cust_name, invc_address, invc_address2, invc_city, invc_state, invc_post_code, invc_country,
          d.qty as invc_qty, doc_type as invc_doc_type, uuid FROM tsc_delivery_h h
          left join tsc_delivery_d d on(h.doc_no=d.doc_no)
          left join mst_vendor v on(v.vendor_code=h.vendor_no)
          left join tpimx_wms.user u on(u.user_id=h.created_by)
          where delv_date between '".$datefrom."' and '".$dateto."' and h.canceled=0;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---
}
