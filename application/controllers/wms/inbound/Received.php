<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Received extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();

    $this->load->model('model_zlog','',TRUE);
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'received'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - Received"); // insert log

          $this->load->view('wms/inbound/v_received');
      }
  }
  //---

  function get_received_list(){
      $this->load->model('model_tsc_received_h','',TRUE);

      $status = ["3"];
      $result = $this->model_tsc_received_h->list_received_h_by_status($status);
      $data['var_received'] = assign_data($result);

      $this->load->view('wms/inbound/v_received_list',$data);
  }
  //----

  function get_received_list_d(){
      $id      = $_POST['id'];
      $return_link = $_POST['link'];
      $loc_code = $_POST['loc_code'];

      $this->load->model('model_tsc_received_d','',TRUE);

      unset($doc_no);
      $doc_no[] = $id;
      $result = $this->model_tsc_received_d->get_list($doc_no);
      $data['var_received_detail'] = assign_data($result);
      $data['doc_no_h'] = $id;
      $data['loc_code_h'] = $loc_code;

      $this->load->view($return_link,$data);
  }
  //----

  function tranf_to_gen_sn(){
      $this->model_zlog->insert("Warehouse - Tranf Gen SN"); // insert log

      $id = $_POST['id'];
      $this->load->model('model_tsc_received_h','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);

      $datetime = date("Y-m-d H:i:s");

      // add to inventory
      $this->update_inventory_available($id);
      //---

      // add to item entry
      $this->insert_item_entry($id);
      //---

      $this->model_tsc_received_h->doc_no = $id;
      $this->model_tsc_received_h->status_h = '4';
      $result = $this->model_tsc_received_h->update_status();

      // insert doc history
      $this->model_tsc_doc_history->insert($id,$id,"","4","",$datetime,"Transf to Gen Serial Number","");
      //--

      if($result){
          $response['status'] = "1";
          $response['msg'] = "Document ready to Generate Serial Number";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //---

  function gen_sn(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'received/gen_sn'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - Gen SN"); // insert log

          $this->load->view('wms/inbound/v_gen_sn');
      }
  }
  //---

  function get_gen_sn_list(){
      $this->load->model('model_tsc_received_h','',TRUE);
      $this->load->model('model_config','',TRUE);

      $status = ["4","5"];
      $result = $this->model_tsc_received_h->list_received_h_by_status_with_limit($status,"10");
      $data['var_received'] = assign_data($result);

      // get config transfer wh
      $this->model_config->name = "wh_transfer_no_gen_sn";
      $data["wh_transfer_no_gen_sn"] = $this->model_config->get_value_by_setting_name();

      $this->load->view('wms/inbound/v_gen_sn_list',$data);
  }
  //----

  function get_gen_sn_list_d(){
      $id      = $_POST['id'];
      $return_link = $_POST['link'];
      $loc_code = $_POST['loc_code'];
      $gen = $_POST['gen'];
      $whship_no = $_POST['whship_no'];

      $this->load->model('model_tsc_received_d','',TRUE);

      unset($doc_no);
      $doc_no[] = $id;
      $result = $this->model_tsc_received_d->get_list($doc_no);
      $data['var_received_detail'] = assign_data($result);
      $data['doc_no_h'] = $id;
      $data['loc_code_h'] = $loc_code;
      $data['gen'] = $gen;
      $data['whship_no'] = $whship_no;

      $this->load->view($return_link,$data);
  }
  //----

  function generating_sn(){
      $this->model_zlog->insert("Warehouse - Generating SN"); // insert log

      $id = $_POST['id'];

      $this->load->model('model_tsc_received_h','',TRUE);
      $this->load->model('model_tsc_received_d','',TRUE);
      $this->load->model('model_tsc_received_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);
      $this->load->model('model_config','',TRUE);

      $datetime = date("Y-m-d H:i:s");

      // get item
      $doc_no[] = $id;
      $result = $this->model_tsc_received_d->get_list($doc_no);
      $data = assign_data($result);
      //--

      // looping all items
      foreach($data as $row){
          // get digit
          $this->model_config->name = "barcode_digit";
          $barcode_digit = $this->model_config->get_value_by_setting_name();
          //---

          // get last number
          $this->model_config->name = "barcode_last_used";
          $barcode_last_used = $this->model_config->get_value_by_setting_name();
          //---

          $barcode_start = $barcode_last_used+1;
          $barcode_end   = $barcode_last_used+$row['qty'];

          $this->model_config->valuee = $barcode_end;
          $this->model_config->update_barcode_last_used();

          // barcode child
          unset($sn);
          for($i=$barcode_start; $i<=$barcode_end; $i++){ $sn[] = sprintf("%0".$barcode_digit."d", $i); }
          //--

           // insert into tsc_item_sn
           $this->model_tsc_item_sn->insert_v2($row['item_code'],$sn,$datetime);
           //$this->model_tsc_item_sn->insert_v2_with_value($row['item_code'],$sn,$datetime,$row['valuee_per_pcs']);

          // insert into received d2 version 2
          $insert_by_number_row = 500;
          $row_insert = 1;
          unset($sn_temp);
          for($i=0;$i<count($sn);$i++){
              if($row_insert > $insert_by_number_row){ // insert to database
                  $this->model_tsc_received_d2->insert_d_v2($row['line_no'],$row['doc_no'],'0',$sn_temp);
                  $row_insert = 1;
                  unset($sn_temp);
                  $sn_temp[] = $sn[$i];
              }
              else{
                  $sn_temp[] = $sn[$i];
              }

              $row_insert++;
          }
          $this->model_tsc_received_d2->insert_d_v2($row['line_no'],$row['doc_no'],'0',$sn_temp); // insert last rows
          //-----

      }
      //---

      // update status received h
      $this->model_tsc_received_h->doc_no = $id;
      $this->model_tsc_received_h->status_h = '5';
      $result = $this->model_tsc_received_h->update_status();
      //---

      // insert doc history
      $this->model_tsc_doc_history->insert($id,$id,"","5","",$datetime,"Generated Serial Number","");
      //--

      if($result){
          $response['status'] = "1";
          $response['msg'] = "Serial Number have been Generated";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }

  }
  //----

  function cancel_received(){
      $id = $_POST['id'];

      $this->load->model('model_tsc_received_h','',TRUE);
      $this->load->model('model_tsc_received_d','',TRUE);
      $this->load->model('model_tsc_in_out_bound_d','',TRUE);
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      $this->db->trans_begin();
      $doc_no[] = $id;
      $result = $this->model_tsc_received_d->get_list($doc_no);
      $result_d = assign_data($result);
      foreach($result_d as $row){
          $this->model_tsc_in_out_bound_d->doc_no = $row['src_no'];
          $this->model_tsc_in_out_bound_d->line_no = $row['src_line_no'];
          $qty_received = $this->model_tsc_in_out_bound_d->get_lastest_qty_received();

          $qty_received_updated = $qty_received - $row['qty']; // calculate qty received

          // rollback qty in in_out_bound_d
          $this->model_tsc_in_out_bound_d->qty_received = $qty_received_updated;
          $this->model_tsc_in_out_bound_d->doc_no = $row['src_no'];
          $this->model_tsc_in_out_bound_d->line_no = $row['src_line_no'];
          $this->model_tsc_in_out_bound_d->update_qty_received();
          //---
      }

      // update status on received_h
      $this->model_tsc_received_h->doc_no = $id;
      $this->model_tsc_received_h->status_h = '0';
      $result_h = $this->model_tsc_received_h->update_status();
      //---

      // update in-out bound h status
      $this->model_tsc_in_out_bound_h->doc_no = $row['src_no'];
      $this->model_tsc_in_out_bound_h->status = "2";
      $this->model_tsc_in_out_bound_h->update_status();
      //---

      $this->db->trans_complete();
      //---

      if($result_h){
          $response['status'] = "1";
          $response['msg'] = "Received Document has been Canceled";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //--

  function update_inventory_available($id){
      $this->load->model('model_tsc_received_d','',TRUE);
      $this->load->model('model_tsc_item_invt','',TRUE);

      $this->db->trans_begin();
      $doc_no[] = $id;
      $result = $this->model_tsc_received_d->get_list($doc_no); // get received document detail
      foreach($result as $row){
          // get lastest available
          /*$this->model_tsc_item_invt->item_code = $row['item_code'];
          $qty_avail = $this->model_tsc_item_invt->get_lasted_available();
          //---

          // update lastest available
          $qty_avail_update = $qty_avail + $row['qty'];
          $this->model_tsc_item_invt->item_code = $row['item_code'];
          $this->model_tsc_item_invt->available = $qty_avail_update;
          $this->model_tsc_item_invt->update_available();*/
          //--

          // update available
          //$this->model_tsc_item_invt->item_code = $row['item_code'];
          //$this->model_tsc_item_invt->available = $row['qty'];
          //$this->model_tsc_item_invt->update_available2();

          $this->model_tsc_item_invt->extraction = $row['qty']*-1;
          $this->model_tsc_item_invt->available  = $row['qty'];
          $this->model_tsc_item_invt->item_code  = $row['item_code'];
          $this->model_tsc_item_invt->update_invt3();
      }
      //--

      $this->db->trans_complete();
  }
  //---

  function insert_item_entry($id){
      $this->load->model('model_tsc_received_d','',TRUE);
      $this->load->model('model_tsc_item_entry','',TRUE);

      $this->db->trans_begin();
      $doc_no[] = $id;
      $result = $this->model_tsc_received_d->get_list($doc_no); // get received document detail
      $datetime = get_datetime_now();
      unset($data);
      foreach($result as $row){
        $data[] = array(
          "item_code" => $row["item_code"],
          "qty" => $row['qty'],
          "src_no" => $row['doc_no'],
          "type" => "1",
          "text" => $row['line_no']."|".$row['src_no']."|".$row['src_line_no'],
          "serial_number" => "",
          "text2" => "",
          "description" => $row['description'],
          "created_datetime" => $datetime,
          "location_code" => $row['src_location_code'], // WH3 2023-05-12
        );
      }
      $this->model_tsc_item_entry->insert_with_bulk($data);

      $this->db->trans_complete();
  }
  //---

  // 2022-11-14 for master code
  function generating_sn_ver_master_barcode(){
      $this->model_zlog->insert("Warehouse - Generating SN"); // insert log

      $id = $_POST['id'];

      $this->load->model('model_tsc_received_h','',TRUE);
      $this->load->model('model_tsc_received_d','',TRUE);
      $this->load->model('model_tsc_received_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);
      $this->load->model('model_config','',TRUE);
      $this->load->model('model_mst_item_uom_conv','',TRUE);
      $this->load->model('model_tsc_item_sn2','',TRUE);

      $datetime = date("Y-m-d H:i:s");

      // get item
      $doc_no[] = $id;
      $result = $this->model_tsc_received_d->get_list($doc_no);
      $data = assign_data($result);
      //--

      // looping all items
      foreach($data as $row){
          // get digit
          $this->model_config->name = "barcode_digit";
          $barcode_digit = $this->model_config->get_value_by_setting_name();
          //---

          // get last number
          $this->model_config->name = "barcode_last_used";
          $barcode_last_used = $this->model_config->get_value_by_setting_name();
          //---

          $barcode_start = $barcode_last_used+1;
          $barcode_end   = $barcode_last_used+$row['qty'];

          $this->model_config->valuee = $barcode_end;
          $this->model_config->update_barcode_last_used();

          // get master barcode prefix
          $this->model_config->name = "pref_master_code";
          $pref_master_barcode = $this->model_config->get_value_by_setting_name();
          //--

          // get converter uom - pcs
          $this->model_mst_item_uom_conv->item_code = $row['item_code'];
          $item_convert = assign_data_one($this->model_mst_item_uom_conv->get_converter());
          //--

          // barcode child
          unset($sn_master_temp);
          if($row['qty'] < $item_convert["pcs"] || $item_convert["pcs"]==1 || $row["master_barcode"] == 0){
              for($i=$barcode_start; $i<=$barcode_end; $i++){ $sn_master_temp[] = $pref_master_barcode.$i."-".$i; }
          }
          else{
              $total_master_barcode = floor($row['qty'] / $item_convert["pcs"]);

              $master_barcode_from = $barcode_start;
              for($i=0; $i<$total_master_barcode; $i++){
                  $master_barcode_to = $master_barcode_from + $item_convert["pcs"] - 1;
                  for($j=1;$j<=$item_convert["pcs"];$j++){
                      $sn_master_temp[] = $pref_master_barcode.$master_barcode_from."-".$master_barcode_to;
                  }
                  $master_barcode_from = $master_barcode_to+1;
              }

              $total_master_barcode_rest = $row['qty'] % $item_convert["pcs"];
              if($total_master_barcode_rest > 0){
                  for($i=$master_barcode_from; $i<=$barcode_end; $i++){ $sn_master_temp[] = $pref_master_barcode.$i."-".$i; }
              }

          }

          unset($sn); unset($sn_master);
          $j=0;
          for($i=$barcode_start; $i<=$barcode_end; $i++){
            $sn[] = sprintf("%0".$barcode_digit."d", $i);
            $sn_master[] = $sn_master_temp[$j];
            $j++;
          }
          //--

          // insert into tsc_item_sn
          //$this->model_tsc_item_sn->insert_v2_master_barcode($row['item_code'],$sn,$datetime,$sn_master);
          $this->model_tsc_item_sn->insert_v2_master_barcode_with_value($row['item_code'],$sn,$datetime,$sn_master, $row["valuee_per_pcs"]);

          // insert into tsc_item_sn2
          $sn_master2 = array_unique($sn_master);
          $this->model_tsc_item_sn2->insert($sn_master2,"1", $datetime);

          // insert into received d2 version 2
          $insert_by_number_row = 500;
          $row_insert = 1;
          unset($sn_temp); unset($sn2_temp);
          for($i=0;$i<count($sn);$i++){
              if($row_insert > $insert_by_number_row){ // insert to database
                  $this->model_tsc_received_d2->insert_d_v2_master_barcode($row['line_no'],$row['doc_no'],'0',$sn_temp,$sn2_temp,$row["valuee_per_pcs"]);
                  $row_insert = 1;
                  unset($sn_temp); unset($sn2_temp);
                  $sn_temp[] = $sn[$i];
                  $sn2_temp[] = $sn_master[$i];
              }
              else{
                  $sn_temp[] = $sn[$i];
                  $sn2_temp[] = $sn_master[$i];
              }

              $row_insert++;
          }
          $this->model_tsc_received_d2->insert_d_v2_master_barcode($row['line_no'],$row['doc_no'],'0',$sn_temp,$sn2_temp,$row["valuee_per_pcs"]); // insert last rows
          //-----

      }
      //---

      // update status received h
      $this->model_tsc_received_h->doc_no = $id;
      $this->model_tsc_received_h->status_h = '5';
      $result = $this->model_tsc_received_h->update_status();
      //---

      // insert doc history
      $this->model_tsc_doc_history->insert($id,$id,"","5","",$datetime,"Generated Serial Number","");
      //--

      if($result){
          $response['status'] = "1";
          $response['msg'] = "Serial Number have been Generated";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }

  }
  //----

  // 2022-11-14 for master code
  function check_item_already_has_converter(){
      $id = $_POST['id'];

      $this->load->model('model_tsc_received_d','',TRUE);
      $this->load->model('model_mst_item_uom_conv','',TRUE);

      $doc_no[] = $id;
      $result = $this->model_tsc_received_d->get_list($doc_no);
      $data = assign_data($result);

      $check = 1;
      foreach($data as $row){
          $this->model_mst_item_uom_conv->item_code = $row["item_code"];
          if(!$this->model_mst_item_uom_conv->check_item_has_converter()){
              $check = 0;
              $item_error = $row["item_code"];
              break;
          }
      }

      if($check == 1){
          $response['status'] = "1";
          $response['item_error'] = "";
      }
      else{
        $response['status'] = "0";
        $response['msg'] = $item_error;
      }

      echo json_encode($response);
  }
  //---

  function transfer_sn_between_wh(){
      $this->model_zlog->insert("Warehouse - Transfer SN"); // insert log

      $doc = $_POST['id'];
      $whship_no = $_POST["whship_no"];

      $this->load->model('model_tsc_picking_d','',TRUE);
      $this->load->model('model_tsc_received_d','',TRUE);
      $this->load->model('model_tsc_received_h','',TRUE);
      $this->load->model('model_tsc_received_d2','',TRUE);
      $this->load->model('model_tsc_picking_d2','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE); // WH3

      // check if doc received equal with doc shipped
      $result = $this->model_tsc_received_d->get_qty_received_shipped($doc, $whship_no);   // check received vs shipped
      $check = 1;
      foreach($result as $row){
          if($row["qty_received"] != $row["qty_shipped"]){
              $check = 0;
          }
      }

      if($check == 1){
          $result = $this->model_tsc_received_d->get_qty_shipped_received($whship_no, $doc); // check shipped vs received
          foreach($result as $row){
              if($row["qty_received"] != $row["qty_shipped"]){
                  $check = 0;
              }
          }
      }

      if($check == 1){
          $this->model_tsc_picking_d2->doc_no = $whship_no;
          $qty_shipped = $this->model_tsc_picking_d2->get_total_qty();

          $this->model_tsc_received_d->doc_no = $doc;
          $qty_received = $this->model_tsc_received_d->get_total_qty_by_doc_no();

          if($qty_shipped != $qty_received) $check = 0;
      }
      //--

      if($check == 0){
          $response['status'] = "0";
          $msg = "Item Received & Item Transfer didn't same";
      }
      else{
          $doc_no[] = $doc;
          $result_d = $this->model_tsc_received_d->get_list($doc_no); // get received list

          $datetime = get_datetime_now();

          unset($line_no); unset($doc_no_temp); unset($status); unset($sn); unset($sn2); unset($value);

          foreach($result_d as $row_d){
              $this->model_tsc_picking_d->src_no = $whship_no;
              $this->model_tsc_picking_d->item_code = $row_d["item_code"];
              $result_pick_d = $this->model_tsc_picking_d->get_list_serial_number_by_src_no_and_item(); // read the SN from wh shipment

              // get SN2 in item_sn
              unset($sn_bulk);
              foreach($result_pick_d as $row_pick_d){
                  $sn_bulk[] = $row_pick_d["serial_number_scan"];
              }

              $result_sn_bulk = $this->model_tsc_item_sn->get_sn2_by_sn_bulk($sn_bulk);
              //--

              foreach($result_sn_bulk as $row_sn_bulk){
                  //debug($row_d["line_no"]." | ".$doc." | ".$row_pick_d["serial_number_scan"]." | ".$row_pick_d["sn2_scan"]);
                  $line_no[] = $row_d["line_no"];
                  $doc_no_temp[] = $doc;
                  $status[] = 0;
                  $sn[] = $row_sn_bulk["serial_number"];
                  $sn2[] = $row_sn_bulk["sn2"];
                  $value[] = 0;
              }

              /*foreach($result_pick_d as $row_pick_d){
                  //debug($row_d["line_no"]." | ".$doc." | ".$row_pick_d["serial_number_scan"]." | ".$row_pick_d["sn2_scan"]);
                  $line_no[] = $row_d["line_no"];
                  $doc_no_temp[] = $doc;
                  $status[] = 0;
                  $sn[] = $row_pick_d["serial_number_scan"];
                  $sn2[] = $row_pick_d["sn2_scan"];
                  $value[] = 0;
              }*/
          }

          $this->model_tsc_received_d2->insert_d_v3_master_barcode($line_no,$doc_no_temp,$status,$sn,$sn2,$value,$datetime);
      }
      //--

      if($check == 1){
          // update status received h
          $this->model_tsc_received_h->doc_no = $doc;
          $this->model_tsc_received_h->status_h = '5';
          $result = $this->model_tsc_received_h->update_status();
          //---

          // insert doc history
          $this->model_tsc_doc_history->insert($doc,$doc,"","5","",$datetime,"Transfered between Warehouse","");
          //--

          $response['status'] = "1";
          $response['msg'] = "Transfer Item from Warehouse has been proceed";
      }
      else{
        $response['status'] = "0";
        $response['msg'] = $msg;
      }

      echo json_encode($response);
  }
  //---


}

?>
