<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_h extends CI_Model{

    function insert($doc_no, $created_at, $delv_date, $destination, $state, $driver, $vendor_no, $tracking_no, $box, $pallet, $domicili, $payment_term, $subtotal, $delv_status, $total, $tax, $created_by, $remark1, $doc_date, $folio){

      $db = $this->load->database('default_oprc', true);
      $data = array(
          "doc_no" => $doc_no,
          "created_at" => $created_at,
          "delv_date" => $delv_date,
          "destination" => $destination,
          "state" => $state,
          "driver" => $driver,
          "vendor_no" => $vendor_no,
          "tracking_no" => $tracking_no,
          "box" => $box,
          "pallet" => $pallet,
          "domicili" => $domicili,
          "payment_term" => $payment_term,
          "subtotal" => $subtotal,
          "delv_status" => $delv_status,
          "total" => $total,
          "tax" => $tax,
          "created_by" => $created_by,
          "remark1" => $remark1,
          "doc_date" => $doc_date,
          "canceled" => 0,
          "statuss" => 2,
          "folio" => $folio, // 2023-10-13
      );

      $result = $db->insert('tsc_delivery_h', $data);
      if($result) return true; else return false;
    }
    //---

    function update_arrived($doc_no, $delv_status, $receiv_date, $receiv_person){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "update tsc_delivery_h set delv_status='".$delv_status."', receiv_date='".$receiv_date."', receiv_person='".$receiv_person."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_invoice_wship($from, $to){
        $db = $this->load->database('default', true);

        /*$query_temp = "select h.no as doc_no, h.posting_date as doc_date, h.bill_to_customer_no as bill_cust_no,
          h.bill_to_name as bill_cust_name, h.ship_to_address as ship_to_addr, h.ship_to_address2 as ship_to_addr2,
          ship_to_city, ship_to_post_code,
          ship_to_county, sell_to_country_region_code as ship_to_ctry_region_code,
          sum(d.quantity) as qty, round(sum(d.amount),2) as amount_without_tax,
          round(sum(d.amount_including_vat),2) as amount_including_vat, external_document_no as external_document
          FROM tpimx_nav.sales_invoice_header h
          inner join tpimx_nav.sales_invoice_line d on(d.document_no = h.no)
          where h.posting_date between '".$from."' and '".$to."' and h.sell_to_customer_no not in('1190027','1190033')
          group by h.no,h.posting_date,h.bill_to_customer_no,h.bill_to_name,h.ship_to_address,h.ship_to_address2,h.ship_to_city,h.ship_to_post_code

          union

          SELECT h.doc_no, doc_date, bill_cust_no, bill_cust_name, ship_to_addr, ship_to_addr2, ship_to_city, ship_to_post_code, ship_to_county, ship_to_ctry_region_code,
          sum(qty_to_ship) as qty,'0' as amount_without_tax,'0' as amount_including_vat, external_document
          FROM tpimx_wms.tsc_in_out_bound_h h
          inner join tpimx_wms.tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
          left join tpimx_wms.tsc_so so on(d.src_no=so.so_no)
          where doc_type='2' and doc_date between '".$from."' and '".$to."' and status1 in('13','14') and (bill_cust_no in('1190027','1190033') or h.doc_no like 'WMS-WSHIP-%')
          group by doc_no,doc_posting_date,src_no,bill_cust_no

          order by doc_no,doc_date";*/

        $query_temp = "select h.no as doc_no, h.posting_date as doc_date, h.bill_to_customer_no as bill_cust_no,
          h.bill_to_name as bill_cust_name, h.ship_to_address as ship_to_addr, h.ship_to_address2 as ship_to_addr2,
          ship_to_city, ship_to_post_code,
          ship_to_county, sell_to_country_region_code as ship_to_ctry_region_code,
          sum(d.quantity) as qty, round(sum(d.amount),2) as amount_without_tax,
          round(sum(d.amount_including_vat),2) as amount_including_vat, external_document_no as external_document
          FROM tpimx_nav.sales_invoice_header h
          inner join tpimx_nav.sales_invoice_line d on(d.document_no = h.no)
          where h.posting_date between '".$from."' and '".$to."' and h.sell_to_customer_no not in('1190027','1190033')
          group by h.no,h.posting_date,h.bill_to_customer_no,h.bill_to_name,h.ship_to_address,h.ship_to_address2,h.ship_to_city,h.ship_to_post_code

          order by doc_no,doc_date";

          //debug($query_temp);

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_cust_name($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "select bill_to_name FROM tpimx_nav.sales_invoice_header where no='".$doc_no."'";
        $query = $db->query($query_temp)->row();
        return $query->bill_to_name;
    }
    //---

    function get_data_by_payment_status_null($status){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "select * FROM tsc_delivery_h h
        left join mst_vendor v on(h.vendor_no=v.vendor_code)
        inner join tsc_status sts on(sts.id=h.statuss)
        where (payment_status is null or payment_status='') and statuss in(".$status.") and canceled='0';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_data_by_docno($doc_no){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "SELECT * FROM tsc_delivery_h h left join mst_vendor v on(h.vendor_no=v.vendor_code) where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update_status($doc_no, $status){
      $db = $this->load->database('default_oprc', true);
      $query_temp = "update tsc_delivery_h set statuss='".$status."' where doc_no='".$doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //--

    function update($doc_no, $delv_date, $destination, $state, $driver, $vendor_no, $tracking_no, $box, $pallet, $domicili, $payment_term, $subtotal, $delv_status, $total, $tax, $created_by, $remark1, $last_modified_at,$folio){
      $db = $this->load->database('default_oprc', true);

      $query_temp = array();
      $query_temp2 = array(
          "doc_no" => $doc_no,
          "last_modified_at" => $last_modified_at,
          "last_modified_by" => $created_by,
          "delv_date" => $delv_date,
          "destination" => $destination,
          "state" => $state,
          "driver" => $driver,
          "vendor_no" => $vendor_no,
          "tracking_no" => $tracking_no,
          "box" => $box,
          "pallet" => $pallet,
          "domicili" => $domicili,
          "payment_term" => $payment_term,
          "subtotal" => $subtotal,
          "delv_status" => $delv_status,
          "total" => $total,
          "tax" => $tax,
          "created_by" => $created_by,
          "remark1" => $remark1,
          "folio" => $folio,
      );

      $query_temp[] = $query_temp2;

      $query = $db->update_batch('tsc_delivery_h',$query_temp,'doc_no');
      return true;
    }
    //---

    function update_approved($doc_no, $datetime, $user){
      $db = $this->load->database('default_oprc', true);
      $query_temp = "update tsc_delivery_h set approved_at='".$datetime."', approved_by='".$user."' where doc_no='".$doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //--

    function payment_update($doc_no, $payment_status, $payment_date, $invc_vendor_no, $invc_vendor_date, $invc_vendor_subtotal, $invc_vendor_total,$invc_vendor_remarks, $datetime, $user, $uuid){
      $db = $this->load->database('default_oprc', true);
      $query_temp = "update tsc_delivery_h set payment_status='".$payment_status."', payment_date='".$payment_date."',invc_vendor_no='".$invc_vendor_no."', invc_vendor_date='".$invc_vendor_date."',invc_vendor_subtotal='".$invc_vendor_subtotal."', invc_vendor_total='".$invc_vendor_total."', remark2='".$invc_vendor_remarks."', invc_vendor_updated_by='".$user."', invc_vendor_updated_at='".$datetime."',uuid='".$uuid."' where doc_no='".$doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //---

    function cancel_doc($doc_no, $canceled_datetime, $canceled_by, $canceled_remark){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "update tsc_delivery_h set canceled='1', canceled_datetime='".$canceled_datetime."', canceled_by='".$canceled_by."', canceled_remark='".$canceled_remark."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_data_by_period($from, $to){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "select doc_no, created_at, delv_date, destination, state, driver, vendor_no, vendor_name, tracking_no, folio, box, pallet, domicili, payment_term, subtotal, total, delv_status, receiv_date, receiv_person,
        tax, payment_status, payment_date,remark1, remark2, invc_vendor_no, invc_vendor_date, invc_vendor_subtotal, invc_vendor_tax, invc_vendor_total, doc_date, statuss, statuss_name, u.name as created_by, u2.name as approved_by FROM tsc_delivery_h h left join mst_vendor v on(h.vendor_no=v.vendor_code) inner join tsc_status sts on(sts.id=h.statuss)
        left join tpimx_wms.user u on(u.user_id=h.created_by)
        left join tpimx_wms.user u2 on(u2.user_id=h.approved_by)
        where doc_date between '".$from."' and '".$to."' order by doc_no;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    //2023-07-07
    function payment_update2($doc_no, $payment_status, $payment_approve_date, $user){
      $db = $this->load->database('default_oprc', true);
      $query_temp = "update tsc_delivery_h set payment_status='".$payment_status."', payment_approve_date='".$payment_approve_date."', payment_approve_by='".$user."'  where doc_no='".$doc_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //---

    // 2023-07-10
    function update_received_date($doc_no, $receiv_date){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "update tsc_delivery_h set receiv_date='".$receiv_date."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    // 2023-07-12
    function get_wship_consigment($from, $to){
        $db = $this->load->database('default', true);
        $query_temp = "select h.doc_no, h.doc_date,h.external_document, bill_cust_no , bill_cust_name,ship_to_addr, ship_to_addr2, ship_to_city, ship_to_county, ship_to_post_code,ship_to_ctry_region_code, sum(d.qty_to_ship) as qty
          FROM tsc_in_out_bound_h h
          inner join tsc_in_out_bound_d d on(h.doc_no=d.doc_no)
          left join tsc_so so on(so.so_no=d.src_no)
          where doc_type='2' and h.doc_no like 'TPM-WSHIP-%' and bill_cust_no in('1190027','1190033')
          and doc_date between '".$from."' and '".$to."'
          group by h.doc_no;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-07-14
    function get_data_by_payment_status_null_with_percentage($status){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "select *,round(invc_vendor_subtotal / sum(d.subtotal)*100,2) as percentage_cost,
        sum(d.subtotal) as subtotal2, sum(d.total) as total2,  d.invc_cust_name
        FROM tsc_delivery_h h
        inner join tsc_delivery_d d on(d.doc_no=h.doc_no)
        left join mst_vendor v on(h.vendor_no=v.vendor_code)
        inner join tsc_status sts on(sts.id=h.statuss)
        where (payment_status is null or payment_status='') and statuss in(".$status.") and canceled='0' group by h.doc_no;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-08-21
    function check_trackingno_not_existing($no){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "select tracking_no, doc_no from tsc_delivery_h  where tracking_no='".$no."' and statuss!='4';";
        $query = $db->query($query_temp)->row();
        $data["tracking_no"] = $query->tracking_no;
        $data["doc_no"] = $query->doc_no;

        return $data;
    }
    //---

    // 2023-10-18
    function get_delivery_ontime_report($datefrom, $dateto){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "select tbl_invc.no as doc_no,date_format(posting_date,'%Y-%m-%d') as doc_date,delv_h.receiv_date, datediff(delv_h.receiv_date,delv_h.delv_date) as leadtime,
          lead_time.lead_days,
          delv_h.destination,delv_h.state,
          sell_to_customer_no, ship_to_name, ship_to_name2, ship_to_address, ship_to_address2, ship_to_city,ship_to_post_code,
          ship_to_county, delv_d.doc_no as delv_doc_no,delv_date,  delv_h.driver, delv_h.vendor_no, v.vendor_name,delv_h.tracking_no,delv_h.domicili,
          delv_h.receiv_person
          from (
          SELECT * FROM tpimx_nav.sales_invoice_header s where posting_date between '".$datefrom."' and '".$dateto."') as tbl_invc
          inner join tpimx_oprc.tsc_delivery_d delv_d on(delv_d.invc_doc_no=tbl_invc.no)
          inner join tpimx_oprc.tsc_delivery_h delv_h on(delv_h.doc_no=delv_d.doc_no)
          inner join tpimx_oprc.mst_vendor v on(v.vendor_code=delv_h.vendor_no)
          left join tpimx_oprc.mst_lead_time lead_time on(lead_time.city=delv_h.destination);";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-10-20
    function get_delivery_ontime_report_by_delv_doc($datefrom, $dateto){
        $db = $this->load->database('default_oprc', true);
        $query_temp = "select h.doc_no, delv_date,receiv_date,datediff(receiv_date,delv_date) as leadtime,lead_time.lead_days,
          invc_doc_no,invc_cust_name, destination, h.state, driver, vendor_no, tracking_no, domicili, receiv_person,v.vendor_name
          FROM tpimx_oprc.tsc_delivery_h h
          inner join tpimx_oprc.tsc_delivery_d d on(h.doc_no=d.doc_no)
          inner join tpimx_oprc.mst_vendor v on(v.vendor_code=h.vendor_no)
          left join tpimx_oprc.mst_lead_time lead_time on(lead_time.city=h.destination)
          where delv_date between '".$datefrom."' and '".$dateto."' and canceled='0';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-11-06
    function update_payment_upload($doc_no, $subtotal, $total, $invoice_vendor_no, $invoice_vendor_date, $payment_date, $uuid, $remarks, $status){
        $db = $this->load->database('default_oprc', true);

        $query_temp = array();
        for($i=0;$i<count($doc_no);$i++){
            $query_temp2 = array(
                "doc_no" => $doc_no[$i],
                "subtotal" => $subtotal[$i],
                "total" => $total[$i],
                "invc_vendor_no" => $invoice_vendor_no[$i],
                "invc_vendor_date" => $invoice_vendor_date[$i],
                "payment_date" => $payment_date[$i],
                "uuid" => $uuid[$i],
                "remark2" => $remarks[$i],
                "statuss" => $status,
            );

            $query_temp[] = $query_temp2;
        }

        $query = $db->update_batch('tsc_delivery_h',$query_temp,'doc_no');
        return true;
    }
    //---
}

?>
