<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Jobs extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();
  }
  //----

  function insert_ship_to_address(){
      $this->load->model('model_outbound','',TRUE);
      $this->load->model('model_mst_ship_to','',TRUE);

      $result_nav = $this->model_outbound->get_ship_to_address_from_nav(); // get shipto data from nav

      $this->model_mst_ship_to->truncate_table_shipto(); // truncate table shipto

      // insert to shipto
      foreach($result_nav as $row){
          $this->model_mst_ship_to->cust_no = $row['cust_no'];
          $this->model_mst_ship_to->code = $row['code'];
          $this->model_mst_ship_to->name = $row['namee'];
          $this->model_mst_ship_to->name2 = $row['name2'];
          $this->model_mst_ship_to->address = $row['addresss'];
          $this->model_mst_ship_to->address2 = $row['address2'];
          $this->model_mst_ship_to->city = $row['city'];
          $this->model_mst_ship_to->contact = $row['contact'];
          $this->model_mst_ship_to->phone_no = $row['phone'];
          $this->model_mst_ship_to->country_region_code = $row['country_region_code'];
          $this->model_mst_ship_to->location_code = $row['location_code'];
          $this->model_mst_ship_to->post_code = $row['post_code'];
          $this->model_mst_ship_to->county = $row['county'];
          $result = $this->model_mst_ship_to->insert();
      }
  }
  //----

  function insert_sales_invoice_nav_to_local(){
      $this->load->model('model_jobs','',TRUE);

      $date_from = date("Y-m-d");
      $date_to = date("Y-m-d");

      //$date_from = "2019-01-01";
      //$date_to = "2019-12-31";

      // header
      $result = $this->model_jobs->get_sales_invoice_header_nav($date_from, $date_to); // get data from navision
      $this->model_jobs->insert_into_sales_invoice_header_nav_local($result);
      //--

      // line
      $result = $this->model_jobs->get_sales_invoice_line_nav($date_from, $date_to); // get data from navision
      $this->model_jobs->insert_into_sales_invoice_line_nav_local($result);
      //--
  }
  //---

  function insert_sales_cm_nav_to_local(){
      $this->load->model('model_jobs','',TRUE);

      $date_from = date("Y-m-d");
      $date_to = date("Y-m-d");

      //$date_from = "2019-01-01";
      //$date_to = "2019-12-31";

      // header
      $result = $this->model_jobs->get_sales_cm_header_nav($date_from, $date_to); // get data from navision
      $this->model_jobs->insert_into_sales_cm_header_nav_local($result);
      //--

      // line
      $result = $this->model_jobs->get_sales_cm_line_nav($date_from, $date_to); // get data from navision
      $this->model_jobs->insert_into_sales_cm_line_nav_local($result);
      //--
  }
  //---

  function insert_customer_to_local(){
      $this->load->model('model_jobs','',TRUE);

      $result = $this->model_jobs->get_customer_nav(); // get data from navision
      $this->model_jobs->truncate_customer_local(); // truncate customer table
      $this->model_jobs->insert_into_customer_nav_local($result); // insert
      //--

  }
  //---

  function insert_cust_ledger_entry_to_local(){
      $this->load->model('model_jobs','',TRUE);

      $lastest_entry_no = $this->model_jobs->get_lastest_entry_no_cust_ledger_entry();
      $lastest_entry_no+=1;

      $result = $this->model_jobs->get_customer_ledger_entry($lastest_entry_no); // get data from navision
      $this->model_jobs->insert_customer_ledger_entry($result);

  }
  //---

  function insert_cust_ledger_entry_detail_to_local(){
      $this->load->model('model_jobs','',TRUE);

      $lastest_entry_no = $this->model_jobs->get_lastest_entry_no_cust_ledger_entry_detail();
      $lastest_entry_no+=1;
      $result = $this->model_jobs->get_customer_ledger_entry_detail($lastest_entry_no); // get data from navision
      $this->model_jobs->insert_customer_ledger_entry_detail($result);
  }
  //---

  function insert_gl_entry_transporter_to_local(){
      $this->load->model('model_jobs','',TRUE);

      $lastest_entry_no = $this->model_jobs->get_lastest_gl_entry();
      $lastest_entry_no+=1;
      $result = $this->model_jobs->get_gl_payment_transporter($lastest_entry_no);  // get data from navision
      $this->model_jobs->insert_gl_payment_transporter($result);
  }
  //---

  function insert_backorder_nav(){
      $this->load->model('model_jobs','',TRUE);

      $total_row = $this->model_jobs->get_total_row_backorder();

     if($total_row > 0){
          $this->model_jobs->truncate_table_backorder_local();

          $offset = 0;
          $limit = 1000;
          while($offset < $total_row){
              if(($total_row - $offset)>=$limit) $top = $limit;
              else $top = $total_row - $offset;

              $result = $this->model_jobs->get_backorder_nav($top, $offset);
              $this->model_jobs->insert_backorder($result);

              $offset+=$top;
          }
      }
  }
  //---

  function insert_item_invt_nav(){
      $this->load->model('model_jobs','',TRUE);

      /*logs(get_datetime_now()." | Start get data from Nav - insert item invt nav");
      $result = $this->model_jobs->get_remaining_qty_nav();

      $this->model_jobs->truncate_table_item_invt_nav_local();
      $datetime = get_datetime_now();
      $this->model_jobs->insert_item_invt_nav($result, $datetime);
      logs(get_datetime_now()." | Finished get data from Nav - insert item invt nav");*/

      logs(get_datetime_now()." | Start get data from Nav - insert item invt nav");
      $total_row = $this->model_jobs->get_row_invt_nav();

      if($total_row > 0){
          $offset = 0;
          $limit = 1000;
          $result = array();
          while($offset < $total_row){

              if(($total_row - $offset)>=$limit) $top = $limit;
              else $top = $total_row - $offset;

              $result2 = $this->model_jobs->get_remaining_qty_nav($top,$offset);

              if(count($result) == 0)$result = $result2;
              else $result = array_merge($result, $result2);

              $offset+=$top;
          }

          $datetime = get_datetime_now();
          $this->model_jobs->truncate_table_item_invt_nav_local();
          $this->model_jobs->insert_item_invt_nav($result, $datetime);
      }

      logs(get_datetime_now()." | Finished get data from Nav - insert item invt nav");
  }
  //----

  function insert_item(){
      $this->load->model('model_jobs','',TRUE);

      logs(get_datetime_now()." | Start get data from Nav - insert item nav");
      $total_row = $this->model_jobs->get_total_row_items();

      if($total_row > 0){
        $this->model_jobs->truncate_table_item_local();

        $offset = 0;
        $limit = 1000;
        while($offset < $total_row){

            if(($total_row - $offset)>=$limit) $top = $limit;
            else $top = $total_row - $offset;

            $result = $this->model_jobs->get_item_nav($top, $offset);
            $this->model_jobs->insert_item($result);
            $this->model_jobs->insert_item_invt_wms($result);

            $offset+=$top;
        }
      }

      logs(get_datetime_now()." | Finished get data from Nav - insert item nav");

  }
  //----

  function insert_warehouse_entry(){
      $this->load->model('model_jobs','',TRUE);
      logs(get_datetime_now()." | Start get data from Nav - insert warehouse entry");

      $date = get_date_now();
      //$date = "2022-10-05";

      $result = $this->model_jobs->get_warehouse_entry_nav($date);
      $this->model_jobs->insert__warehouse_entry($result);

      logs(get_datetime_now()." | Finished get data from Nav - insert warehouse entry");
  }
  //---

  function insert_sales_price(){
      $this->load->model('model_jobs','',TRUE);

      $date = get_date_now();
      $result = $this->model_jobs->get_sales_price_from_nav($date);

      if(count($result) > 0){
          $this->model_jobs->truncate_table_sales_price();   // truncate
          $this->model_jobs->insert_sales_price($result);
      }
  }
  //---

  function insert_po_outstand_transfill(){
      $this->load->model('model_jobs','',TRUE);

      $total_row = $this->model_jobs->get_total_row_purchase_order_outstanding_transfill_from_nav();

      if($total_row > 0){
          $this->model_jobs->truncate_table_purchase_order_local();

          $offset = 0;
          $limit = 200;
          while($offset < $total_row){
              if(($total_row - $offset)>=$limit) $top = $limit;
              else $top = $total_row - $offset;

              $result = $this->model_jobs->get_purchase_order_outstanding_transfill_from_nav($top, $offset);
              $this->model_jobs->insert_po($result);

              $offset+=$top;
          }
      }
  }
  //---

  function insert_fixed_asset(){
      $this->load->model('model_jobs','',TRUE);

      $total_row = $this->model_jobs->get_total_row_fixed_asset_from_nav();

      if($total_row > 0){
          $this->model_jobs->truncate_table_fixed_asset_local();

          $offset = 0;
          $limit = 50;
          while($offset < $total_row){
              if(($total_row - $offset)>=$limit) $top = $limit;
              else $top = $total_row - $offset;

              $result = $this->model_jobs->get_fixed_asset_from_nav();
              $this->model_jobs->insert_fixed_asset($result);

              $offset+=$top;
          }
      }
  }
  //---

  // 2022-11-29
  function get_credit_note_from_nav_to_wms(){

      $this->load->model('model_mst_location','',TRUE);
      $this->load->model('model_jobs','',TRUE);

      $date_from  = get_date_now();
      $date_to    = get_date_now();

      //$date_from  = "2022-10-25";
      //$date_to    = "2022-10-25";

      $result_loc = $this->model_mst_location->get_data();

      foreach($result_loc as $row){
          $result_cn = $this->model_jobs->get_credit_note_from_nav($date_from, $date_to, $row["code"]);
          if(count($result_cn) > 0){
              $message = "Credit Note = ".$date_to;
              $doc_no = $this->insert_tsc_in_out_bound_h($row["code"],"8",$message); // insert header
              if($doc_no != false){ // insert detail
                  $this->insert_tsc_in_out_bound_d($doc_no,$result_cn); // insert detail
              }
          }
      }
  }
  //--

  // 2022-11-29
  function insert_tsc_in_out_bound_h($loc,$user,$external_doc){
      $this->load->model('model_jobs','',TRUE);
      $this->load->model('model_config','',TRUE);

      // get config for document no
      $this->model_config->name = "adjust_posv_doc_pref";
      $prefix = $this->model_config->get_value_by_setting_name();

      $this->model_config->name = "adjust_posv_doc_no";
      $last_doc_no = $this->model_config->get_value_by_setting_name();

      $new_doc_no = $last_doc_no+1;

      $this->model_config->name = "adjust_posv_doc_no";
      $this->model_config->valuee = $new_doc_no;
      $this->model_config->update_value();

      $this->model_config->name = "adjust_posv_doc_digit";
      $digit = $this->model_config->get_value_by_setting_name();

      $doc_no = $prefix.sprintf("%0".$digit."d", $new_doc_no);
      //---

      // insert header
      $datetime = get_datetime_now();
      $date = get_date_now();
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);

      $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
      $this->model_tsc_in_out_bound_h->doc_datetime = $datetime;
      $this->model_tsc_in_out_bound_h->created_datetime = $datetime;
      $this->model_tsc_in_out_bound_h->doc_type = "1";
      $this->model_tsc_in_out_bound_h->doc_location_code = $loc;
      $this->model_tsc_in_out_bound_h->month_end = 0;
      $this->model_tsc_in_out_bound_h->created_user = $user;
      $this->model_tsc_in_out_bound_h->status = "1";
      $this->model_tsc_in_out_bound_h->doc_date = $date;
      $this->model_tsc_in_out_bound_h->doc_posting_date = $datetime;
      $this->model_tsc_in_out_bound_h->external_document = $external_doc;
      $result = $this->model_tsc_in_out_bound_h->insert_h();
      //---

      // insert doc history
      $this->model_tsc_doc_history->insert($doc_no,"","","1","",$datetime, $external_doc,"");
      //--

      if($result) return $doc_no;
      else return false;
  }
  //---

  // 2022-11-29
  function insert_tsc_in_out_bound_d($doc_no,$data){
      $datetime = get_datetime_now();
      $date = get_date_now();
      $this->load->model('model_tsc_in_out_bound_d','',TRUE);

      $line_no_add = 10000;
      $line_no = 10000;

      foreach($data as $row){
          $this->model_tsc_in_out_bound_d->doc_no = $doc_no;
          $this->model_tsc_in_out_bound_d->line_no = $line_no;
          $this->model_tsc_in_out_bound_d->src_location_code = $row["Location Code"];
          $this->model_tsc_in_out_bound_d->src_no = $row["doc_no"];
          $this->model_tsc_in_out_bound_d->src_line_no = $row["Line No_"];
          $this->model_tsc_in_out_bound_d->item_code = $row["item_code"];
          $this->model_tsc_in_out_bound_d->qty = $row["Quantity"];
          $this->model_tsc_in_out_bound_d->uom = $row["Unit of Measure"];
          $this->model_tsc_in_out_bound_d->description = $row["Description"];
          $this->model_tsc_in_out_bound_d->dest_no = "";
          $this->model_tsc_in_out_bound_d->master_barcode = "1"; // master barcode 2023-01-17
          $this->model_tsc_in_out_bound_d->valuee = "0"; // valuee 2023-01-30
          $this->model_tsc_in_out_bound_d->valuee_per_pcs = "0"; // valuee 2023-01-30
          $result = $this->model_tsc_in_out_bound_d->insert_d();
          $line_no += $line_no_add;
      }
  }
  //---

  // 2023-02-28
  function delete_wship_failed_retreive_from_nav(){
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      $result = $this->model_tsc_in_out_bound_h->get_wship_with_detail_zero();
      if(count($result) > 0){
          foreach($result as $row){
              $this->model_tsc_in_out_bound_h->insert_in_out_bound_deleted_from_h($row["doc_no"]);
              $this->model_tsc_in_out_bound_h->delete_doc($row["doc_no"]);
              echo "deleted = ".$row["doc_no"]."<br>";
          }
      }
  }
  //---

  // 2023-03-23
  function send_item_to_customer_online_filter(){
      $this->load->model('model_jobs','',TRUE);

      $datetime = get_datetime_now();
      $type = "'F'";

      if($_GET["getnav"] == "1"){

          $items = $this->model_jobs->get_item_online($type,""); // get items online

          // get item remaining
          $result_invt = $this->model_jobs->get_remaining_qty_nav_certain_items($items); // get inventory with items online
          $this->model_jobs->truncate_table_item_invt_temp_nav_local(); // truncate table item invt temp
          $this->model_jobs->insert_item_invt_nav_temp($result_invt, $datetime);  // insert table item invt temp
          //--

          // get reservation
          $result_resv = $this->model_jobs->get_reservation_certain_items($items);
          $this->model_jobs->truncate_table_item_resv_nav_local();
          $this->model_jobs->insert_item_resv_nav($result_resv, $datetime);
          //---
      }

      $result = $this->model_jobs->get_qty_item_online($type);

      $location = "uploads/excel/itemonline/";
      $filename = "TPIMX-Inventario ";
      $date = get_date_now();
      $ext = ".xlsx";
      $fullfilename = $location.$filename.$date.$ext;
      $body_text = $filename.$date.$ext;
      $this->convert_qty_item_online_to_excel($result, $fullfilename);
      $this->send_email_item_online_filter($fullfilename,$body_text);
  }
  //--

  // 2023-03-23
  function convert_qty_item_online_to_excel($data, $fullfilename){

      require "assets/PHPXLSXWriter/xlsxwriter.class.php";

      $header = array(
        'SKU'=>'string',//text
        'Cantidad'=>'integer',//text
      );

      $rows = array();

      foreach($data as $row){
        $rows[] = array($row["item_code2"],$row["qty_final"]);
      }

      $writer = new XLSXWriter();

      $writer->writeSheetHeader('Sheet1', $header);
      foreach($rows as $row)
      	$writer->writeSheetRow('Sheet1', $row);

      $writer->writeToFile($fullfilename);

  }
  //--

  // 2023-03-23
  function send_email_item_online_filter($fullfilename, $body_text){

      $this->load->model('model_config','',TRUE);

      $from_info_text = "TPI-MX";

      // 1010124 *****
      $this->load->library('MY_phpmailer');
      $this->model_config->name = "email_item_online_to_1010124";  // get send to
      $send_to = $this->model_config->get_value_by_setting_name();
      $send_to = explode("|",$send_to);

      unset($cc);
      $this->model_config->name = "email_item_online_cc_1010124"; // get send cc
      $cc_temp = $this->model_config->get_value_by_setting_name();
      $cc = explode("|",$cc_temp);

      $body = "MAYOREO LOPEZ DIAZ ".$body_text;
      $to = $send_to;
      $subject = "MAYOREO LOPEZ DIAZ ".$body_text;
      $from_info = $from_info_text;
      $altbody = "";
      $result = $this->my_phpmailer->send2($to,$subject,$body,$altbody,$cc,$from_info,$fullfilename);
      //--- ***

      // 1150030 *****
      $this->load->library('MY_phpmailer');
      $this->model_config->name = "email_item_online_to_1150030"; // get send to 1150030
      $send_to = $this->model_config->get_value_by_setting_name();
      $send_to = explode("|",$send_to);

      unset($cc);
      $this->model_config->name = "email_item_online_cc_1150030"; // get send cc 1150030
      $cc_temp = $this->model_config->get_value_by_setting_name();
      $cc = explode("|",$cc_temp);

      $body = "REFACCIONARIA ARBOLEDAS SA DE CV ".$body_text;
      $to = $send_to;
      $subject = "REFACCIONARIA ARBOLEDAS SA DE CV ".$body_text;
      $from_info = $from_info_text;
      $altbody = "";
      $result = $this->my_phpmailer->send2($to,$subject,$body,$altbody,$cc,$from_info,$fullfilename);
      //-- *******


      // 1210052 ***
      $this->load->library('MY_phpmailer');
      $this->model_config->name = "email_item_online_to_1210052"; // get send to 1210052
      $send_to = $this->model_config->get_value_by_setting_name();
      $send_to = explode("|",$send_to);

      unset($cc);
      $this->model_config->name = "email_item_online_cc_1210052"; // get send cc 1210052
      $cc_temp = $this->model_config->get_value_by_setting_name();
      $cc = explode("|",$cc_temp);

      $body = "AUTOREFAX ".$body_text;
      $to = $send_to;
      $subject = "AUTOREFAX ".$body_text;
      $from_info = $from_info_text;
      $altbody = "";
      $result = $this->my_phpmailer->send2($to,$subject,$body,$altbody,$cc,$from_info,$fullfilename);
      //-- ****
  }
  //--

  // 2023-03-27
  function send_item_to_customer_online_filter_banda(){
      $this->load->model('model_jobs','',TRUE);

      $datetime = get_datetime_now();
      $type = "'F','B'";

      if($_GET["getnav"] == "1"){

          $items = $this->model_jobs->get_item_online($type,""); // get items online

          // get item remaining
          $result_invt = $this->model_jobs->get_remaining_qty_nav_certain_items($items); // get inventory with items online
          $this->model_jobs->truncate_table_item_invt_temp_nav_local(); // truncate table item invt temp
          $this->model_jobs->insert_item_invt_nav_temp($result_invt, $datetime);  // insert table item invt temp
          //--

          // get reservation
          $result_resv = $this->model_jobs->get_reservation_certain_items($items);
          $this->model_jobs->truncate_table_item_resv_nav_local();
          $this->model_jobs->insert_item_resv_nav($result_resv, $datetime);
          //---
      }

      $result = $this->model_jobs->get_qty_item_online($type);

      $location = "uploads/excel/itemonline/";
      $filename = "TPIMX-Inventario ";
      $date = get_date_now();
      $ext = ".xlsx";
      $fullfilename = $location.$filename.$date.$ext;
      $body_text = $filename.$date.$ext;
      $this->convert_qty_item_online_to_excel($result, $fullfilename);
      $this->send_email_item_online_filter_banda($fullfilename,$body_text);
  }
  //--

  // 2023-03-27
  function send_email_item_online_filter_banda($fullfilename, $body_text){

      $this->load->model('model_config','',TRUE);

      $from_info_text = "TPI-MX";

      // 1210047 ****
      $this->load->library('MY_phpmailer');
      $this->model_config->name = "email_item_online_to_1210047"; // get send to 1210047
      $send_to = $this->model_config->get_value_by_setting_name();
      $send_to = explode("|",$send_to);

      unset($cc);
      $this->model_config->name = "email_item_online_cc_1210047"; // get send cc 1210047
      $cc_temp = $this->model_config->get_value_by_setting_name();
      $cc = explode("|",$cc_temp);

      $body = "MASLUZ.MX S DE RL DE CV ".$body_text;
      $to = $send_to;
      $subject = "MASLUZ.MX S DE RL DE CV ".$body_text;
      $from_info = $from_info_text;
      $altbody = "";
      $result = $this->my_phpmailer->send2($to,$subject,$body,$altbody,$cc,$from_info,$fullfilename);
      //-- *****
  }
  //--

  // 2023-05-05
  function insert_so_monthly(){
      $this->load->model('model_jobs','',TRUE);

      // get last month period
      $today = date("Y-m-d");
      $date_from  = get_last_month_first_day($today);
      $date_to    = get_last_month_last_day($today);
      //---

      $total_row = $this->model_jobs->get_row_so_monthly_nav($date_from, $date_to);

      if($total_row > 0){
        $offset = 0;
        $limit = 1000;
        while($offset < $total_row){

            if(($total_row - $offset)>=$limit) $top = $limit;
            else $top = $total_row - $offset;

            $result = $this->model_jobs->get_so_monthly_nav($date_from, $date_to, $limit, $offset);
            $this->model_jobs->insert_so_monthly_local($result);
            $offset+=$top;
        }
      }
  }
  //----

  // 2023-05-05
  function insert_shipment_so_monthly(){
      $this->load->model('model_jobs','',TRUE);

      // get last month period
      $today = date("Y-m-d");
      $date_from  = get_last_month_first_day($today);
      $date_to    = get_last_month_last_day($today);
      //---

      $total_row = $this->model_jobs->get_row_sls_shipment_so_monthly_nav($date_from, $date_to);

      if($total_row > 0){
        $offset = 0;
        $limit = 1000;
        while($offset < $total_row){

            if(($total_row - $offset)>=$limit) $top = $limit;
            else $top = $total_row - $offset;

            $result = $this->model_jobs->get_sls_shipment_so_monthly_nav($date_from, $date_to, $limit, $offset);
            $this->model_jobs->insert_sls_shipment_so_monthly_local($result);
            $offset+=$top;
        }
      }
  }
  //----

  // 2023-05-25
  function send_item_to_customer_online_filter_autotodo(){
      $this->load->model('model_jobs','',TRUE);

      $datetime = get_datetime_now();
      $type = "'F'";

      if($_GET["getnav"] == "1"){

          $items = $this->model_jobs->get_item_online($type,"1210055"); // get items online

          // get item remaining
          $result_invt = $this->model_jobs->get_remaining_qty_nav_certain_items($items); // get inventory with items online
          $this->model_jobs->truncate_table_item_invt_temp_nav_local(); // truncate table item invt temp
          $this->model_jobs->insert_item_invt_nav_temp($result_invt, $datetime);  // insert table item invt temp
          //--

          // get reservation
          $result_resv = $this->model_jobs->get_reservation_certain_items($items);
          $this->model_jobs->truncate_table_item_resv_nav_local();
          $this->model_jobs->insert_item_resv_nav($result_resv, $datetime);
          //---
      }

      $result = $this->model_jobs->get_qty_item_online_autotodo($type,"1210055");

      $location = "uploads/excel/itemonline/";
      $filename = "TPIMX-Inventario AUTO-TODO ";
      $date = get_date_now();
      $ext = ".xlsx";
      $fullfilename = $location.$filename.$date.$ext;
      $body_text = $filename.$date.$ext;
      $this->convert_qty_item_online_to_excel_autotodo($result, $fullfilename);
      $this->send_email_item_online_filter_autotodo($fullfilename,$body_text);
  }
  //--

  // 2023-05-25
  function send_email_item_online_filter_autotodo($fullfilename, $body_text){

      $this->load->model('model_config','',TRUE);

      $from_info_text = "TPI-MX";

      // 1210055 *****
      $this->load->library('MY_phpmailer');
      $this->model_config->name = "email_item_online_to_1210055";  // get send to
      $send_to = $this->model_config->get_value_by_setting_name();
      $send_to = explode("|",$send_to);

      unset($cc);
      $this->model_config->name = "email_item_online_cc_1210055"; // get send cc
      $cc_temp = $this->model_config->get_value_by_setting_name();
      $cc = explode("|",$cc_temp);

      $body = "AUTO-TODO ".$body_text;
      $to = $send_to;
      $subject = "AUTO-TODO ".$body_text;
      $from_info = $from_info_text;
      $altbody = "";
      $result = $this->my_phpmailer->send2($to,$subject,$body,$altbody,$cc,$from_info,$fullfilename);
      //--- ***
  }
  //--

  // 2023-05-25
  function convert_qty_item_online_to_excel_autotodo($data, $fullfilename){

      require "assets/PHPXLSXWriter/xlsxwriter.class.php";

      $header = array(
        'Codigo'=>'string',//text
        'Status'=>'string',//text
        'Incoming'=>'string',//text
      );

      $rows = array();

      foreach($data as $row){
        $rows[] = array($row["item_code"],$row["qty_avail"],$row["estimation_arrived"]);
      }

      $writer = new XLSXWriter();

      $writer->writeSheetHeader('Sheet1', $header);
      foreach($rows as $row)
      	$writer->writeSheetRow('Sheet1', $row);

      $writer->writeToFile($fullfilename);

  }
  //--

  // 2023-06-01
  function insert_item_nav_month_end(){
    $this->load->model('model_jobs','',TRUE);

    $date = date("Y-m-d");
    $total_row = $this->model_jobs->get_row_invt_nav();

    if($total_row > 0){
        $offset = 0;
        $limit = 1000;
        $result = array();
        while($offset < $total_row){

            if(($total_row - $offset)>=$limit) $top = $limit;
            else $top = $total_row - $offset;

            $result2 = $this->model_jobs->get_remaining_qty_nav($top,$offset);

            if(count($result) == 0)$result = $result2;
            else $result = array_merge($result, $result2);

            $offset+=$top;
        }

        $datetime = get_datetime_now();
        $this->model_jobs->truncate_table_item_invt_nav_local();
        $this->model_jobs->insert_item_invt_nav_month_end($result, $datetime, $date);
    }
  }
  //--

  function send_email(){
      $this->load->model('model_tsc_email','',TRUE);
      $this->load->model('model_tsc_in_out_bound_d','',TRUE);
      $this->load->model('model_tsc_adjust_doc_d','',TRUE);

      $result_email = $this->model_tsc_email->get_email_not_sent();

      if(count($result_email) > 0){
          unset($doc_no);
          foreach($result_email as $row){
              $doc_no[] = $row["doc_no"];

              $this->load->library('MY_phpmailer');

              // get body
              if($row["email_type"] == 1){
                $result_detail = $this->model_tsc_in_out_bound_d->get_list($doc_no); // get detail from whship
                $body = $this->my_phpmailer->email_body_whsreceipt_submitnav($row["doc_no"],$row["added_at"],$row["message"], $result_detail);
              }
              else if($row["email_type"] == 2){
                $result_detail = $this->model_tsc_in_out_bound_d->get_list_with_so_cs($doc_no); // get detail from whship
                $body = $this->my_phpmailer->email_body_whship_submitnav($row["doc_no"],$row["added_at"],$row["message"], $result_detail);
              }
              else if($row["email_type"] == 3){
                $result_detail = $this->model_tsc_adjust_doc_d->get_list($row["doc_no"]); // get detail from whship
                $body = $this->my_phpmailer->email_body_whship_edit_from_picker($row["doc_no"],$row["added_at"],$row["message"], $result_detail);
              }
              else if($row["email_type"] == 4){
                $result_detail = $this->model_tsc_adjust_doc_d->get_list($row["doc_no"]); // get detail from whship
                $body = $this->my_phpmailer->email_body_whship_edit($row["doc_no"],$row["added_at"],$row["message"], $result_detail);
              }
              else if($row["email_type"] == 5){
                $result_detail = $this->model_tsc_adjust_doc_d->get_list($row["doc_no"]); // get detail from whship
                $body = $this->my_phpmailer->email_body_whship_edit($row["doc_no"],$row["added_at"],$row["message"], $result_detail);
              }
              //---

              $cc = explode("|",$row["cc"]); // breakdown CC

              $result = $this->my_phpmailer->send($row["to"],$row["subject"],$body,"",$cc,$row["from_info"]);
              $datetime = get_datetime_now();
              $this->model_tsc_email->update_sent("1", $datetime, $row["id"]);
              unset($doc_no);
          }
      }
  }
  //---

  //2023-10-17
  function insert_sls_shipment_to_local(){
      $this->load->model('model_jobs','',TRUE);

      $date_from = date("Y-m-d");
      $date_to = date("Y-m-d");

      $date_from = "2023-09-01";
      $date_to = "2023-09-30";

      // header
      $result = $this->model_jobs->get_sls_shipment_header_from_nav($date_from, $date_to); // get data from navision
      $this->model_jobs->insert_sls_shipment_header_to_local($result);
      //--

      // line
      $result = $this->model_jobs->get_sls_shipment_line_from_nav($date_from, $date_to); // get data from navision
      $this->model_jobs->insert_sls_shipment_line_to_local($result);
      //--
  }
  //---
}
