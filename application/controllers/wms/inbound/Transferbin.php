<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Transferbin extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();

    $this->load->model('model_zlog','',TRUE);
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'transferbin'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - Transferbin"); // insert log

          $this->load->view('wms/inbound/transferbin/v_index');

      }
  }
  //---

  function get_list(){
      $this->load->model('model_tsc_transferbin_h','',TRUE);
      $status = array("1","2","3","4");

      $date_to = date("Y-m-d");
      $date_from = date('Y-m-d', strtotime('today - 30 days'));

      $result = $this->model_tsc_transferbin_h->list_by_status2($status, $date_from, $date_to);
      $data["var_transferbin_h"] = assign_data($result);
      $this->load->view('wms/inbound/transferbin/v_list',$data);
  }
  //----

  function new(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'transferbin'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - New Transferbin"); // insert log

          $this->load->model('model_mst_bin','',TRUE);
          $this->load->model('model_login','',TRUE);
          $this->load->model('model_mst_location','',TRUE);

          $result = $this->model_mst_bin->get_data();
          $data['var_bin'] = assign_data($result);

          $result = $this->model_login->get_user_list();
          $data['user_list'] = assign_data($result);

          $result = $this->model_mst_location->get_data2();
          $data['var_location'] = assign_data($result);

          //$this->load->view('wms/inbound/transferbin/new/v_index',$data);
          $this->load->view('wms/inbound/transferbin/new/v_index2',$data);
      }
  }
  //---

  function check_bin(){
      $id = $_POST["id"];
      $new_id = explode("-",$id);

      $this->load->model('model_mst_bin','',TRUE);

      $this->model_mst_bin->location_code = $new_id[0];
      $this->model_mst_bin->zone_code = $new_id[1];
      $this->model_mst_bin->area_code = $new_id[2];
      $this->model_mst_bin->rack_code = $new_id[3];
      $this->model_mst_bin->code = $new_id[4];
      $this->model_mst_bin->active = "1";
      $result = $this->model_mst_bin->check_bin();
      if(count($result)>0) echo "1"; else echo "0";
  }
  //---

  function getbinsrc(){
      $id = $_POST['id'];
      $inp_location = $_POST["inp_location"];
      $new_id = explode("-",$id);

      $this->load->model('model_tsc_item_sn','',TRUE);

      $this->model_tsc_item_sn->location_code = $new_id[0];
      $this->model_tsc_item_sn->zone_code = $new_id[1];
      $this->model_tsc_item_sn->area_code = $new_id[2];
      $this->model_tsc_item_sn->rack_code = $new_id[3];
      $this->model_tsc_item_sn->bin_code = $new_id[4];
      $this->model_tsc_item_sn->status = "1";
      $result = $this->model_tsc_item_sn->get_qty_by_loc_zone_area_rack_bin_status();
      $data["var_bin_src"] = assign_data($result);
      $data["inp_location"] = $inp_location;
      $this->load->view('wms/inbound/transferbin/new/v_bin_src',$data);
  }
  //---

  function create_new(){
      $this->model_zlog->insert("Warehouse - Creating Transferbin"); // insert log

      $bin_dest = $_POST['bin_dest'];
      $message = $_POST['message'];
      $doc_user = $_POST["doc_user"];
      $loc = json_decode(stripslashes($_POST['loc']));
      $zone = json_decode(stripslashes($_POST['zone']));
      $area = json_decode(stripslashes($_POST['area']));
      $rack = json_decode(stripslashes($_POST['rack']));
      $bin = json_decode(stripslashes($_POST['bin']));
      $item = json_decode(stripslashes($_POST['item']));
      $desc = json_decode(stripslashes($_POST['desc']));
      $uom = json_decode(stripslashes($_POST['uom']));
      $qty_max = json_decode(stripslashes($_POST['qty_max']));
      $qty_inp = json_decode(stripslashes($_POST['qty_max']));
      //$qty_inp = json_decode(stripslashes($_POST['qty_inp']));
      $sn = json_decode(stripslashes($_POST['sn'])); // 2023-10-26
      $sn2 = json_decode(stripslashes($_POST['sn2'])); // 2023-10-26
      $whs = $_POST["whs"]; // 2023-10-26

      $datetime = get_datetime_now();
      $date = get_date_now();
      $session_data = $this->session->userdata('z_tpimx_logged_in');

      $this->load->model('model_tsc_item_sn','',TRUE);

      // check if stock available to tranf
      //$result_avail = $this->check_if_stock_available($loc,$zone,$area,$rack,$bin,$item,$qty_max,$qty_inp);

      // check all the SN will is available
      unset($sn_temp);
      for($i=0;$i<count($loc);$i++){
          $sn_temp[] = $sn[$i];
      }

      $result_avil["result"] = $this->model_tsc_item_sn->get_not_status_by_multiple_sn($sn_temp,"1");
      //---

      if($result_avil["result"] == 0){
          $result_end = 0;
          //$message_end = "Error, Location = ".$result_avil["loc_error"]."-".$result_avil["zone_error"]."-".$result_avil["area_error"]."-".$result_avil["rack_error"]."-".$result_avil["bin_error"].", Item = ".$result_avil["item_error"].", ".$result_avil["message"];
          $message_end = "Error";
      }
      else{ // if everything is ok go to transfer

          $this->model_tsc_item_sn-update_status_v3($sn_temp, "4");

          // create header document
          $new_doc_no = $this->create_header_doc($datetime, $date, $message, $session_data['z_tpimx_user_id'], $doc_user, "1",$whs);

          // insert detail
          $this->insert_detail($loc, $zone, $area, $rack, $bin, $item ,$desc,$uom,$qty_inp,$bin_dest, $new_doc_no, $datetime);

          $result_end = 1;
          $message_end = "New Transfer Bin Document has been created with No = ".$new_doc_no;
      }


      if($result_end){
          $response['status'] = "1";
          $response['msg'] = $message_end;
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = $message_end;
        echo json_encode($response);
      }

  }
  //--

  function check_if_stock_available($loc,$zone,$area,$rack,$bin,$item,$qty_max,$qty_inp){
      $this->load->model('model_tsc_item_sn','',TRUE);

      $if_all_ok = 1;
      unset($sn); unset($arr_loc); unset($arr_zone); unset($arr_area);
      unset($arr_rack); unset($arr_bin); unset($arr_item);

      // compile data from detail to summary
      for($i=0;$i<count($loc);$i++){

      }
      //---

      for($i=0;$i<count($loc);$i++){
          $this->model_tsc_item_sn->location_code = $loc[$i];
          $this->model_tsc_item_sn->zone_code = $zone[$i];
          $this->model_tsc_item_sn->area_code = $area[$i];
          $this->model_tsc_item_sn->rack_code = $rack[$i];
          $this->model_tsc_item_sn->bin_code = $bin[$i];
          $this->model_tsc_item_sn->item_code = $item[$i];
          $result_check =  $this->model_tsc_item_sn->check_if_qty_enough($qty_inp[$i]);

          // if qty enough.. get serial number, and booked
          if($result_check == 1){
              $this->model_tsc_item_sn->location_code = $loc[$i];
              $this->model_tsc_item_sn->zone_code = $zone[$i];
              $this->model_tsc_item_sn->area_code = $area[$i];
              $this->model_tsc_item_sn->rack_code = $rack[$i];
              $this->model_tsc_item_sn->bin_code = $bin[$i];
              $this->model_tsc_item_sn->item_code = $item[$i];
              $this->model_tsc_item_sn->status = "1";
              $result_data = $this->model_tsc_item_sn->get_list_sn_with_status_limit_order_by_serial_number($qty_inp[$i]);

              $this->model_tsc_item_sn->update_status_v2($result_data, '4');

              foreach($result_data as $row){
                  //$this->model_tsc_item_sn->status = "4";
                  //$this->model_tsc_item_sn->serial_number = $row["serial_number"];
                  //$this->model_tsc_item_sn->update_status();
                  $sn[] = $row["serial_number"];
                  $arr_loc[] = $loc[$i];
                  $arr_zone[] = $zone[$i];
                  $arr_area[] = $area[$i];
                  $arr_rack[] = $rack[$i];
                  $arr_bin[] = $bin[$i];
                  $arr_item[] = $item[$i];;
              }
          }
          else{
              $if_all_ok = 0;
              $loc_error = $loc[$i];
              $zone_error = $zone[$i];
              $area_error = $area[$i];
              $rack_error = $rack[$i];
              $bin_error = $bin[$i];
              $item_error = $item[$i];
              $message = "Qty not enough";
              break;
          }
      }

      // return back booked item
      if($if_all_ok == 0){
        /*foreach($sn as $row){
            $this->model_tsc_item_sn->status = "1";
            $this->model_tsc_item_sn->serial_number = $row;
            $this->model_tsc_item_sn->update_status();
        }*/

        $this->model_tsc_item_sn->update_status_v3($sn, '1');

        unset($sn);
      }

      if($if_all_ok == 1){
          $data["result"] = 1;
          $data["sn"] = $sn;
          $data["loc"] = $arr_loc;
          $data["zone"] = $arr_zone;
          $data["area"] = $arr_area;
          $data["rack"] = $arr_rack;
          $data["bin"] = $arr_bin;
      }
      else{
          $data["result"] = 0;
          $data["loc_error"] = $loc_error;
          $data["zone_error"] = $zone_error;
          $data["area_error"] = $area_error;
          $data["rack_error"] = $rack_error;
          $data["bin_error"] = $bin_error;
          $data["item_error"] = $item_error;
          $data["message"] = "Qty not enough";
      }

      return $data;
  }
  //----

  function create_header_doc($datetime, $date, $message, $created_user, $assign_user,$status, $whs){
      $this->load->model('model_tsc_transferbin_h','',TRUE);
      $this->load->model('model_config','',TRUE);

      // get prefix from config
      $this->model_config->name = "pref_transferbin";
      $prefix = $this->model_config->get_value_by_setting_name();
      //--

      $this->model_tsc_transferbin_h->prefix_code = $prefix;
      $this->model_tsc_transferbin_h->created_datetime = $datetime;
      $this->model_tsc_transferbin_h->doc_datetime = $datetime;
      $this->model_tsc_transferbin_h->created_user = $created_user;
      $this->model_tsc_transferbin_h->statuss = $status;
      $this->model_tsc_transferbin_h->doc_date = $date;
      $this->model_tsc_transferbin_h->text1 = $message;
      $this->model_tsc_transferbin_h->assign_user = $assign_user;
      $this->model_tsc_transferbin_h->location_code = $whs; // wh3
      $result = $this->model_tsc_transferbin_h->call_store_procedure_newtransferbin();
      return $result;
  }
  //----

  function insert_detail($loc, $zone, $area, $rack, $bin, $item, $desc,$uom, $qty ,$bin_dest, $doc_no, $datetime){
      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_transferbin_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);

      $new_bin_dest = explode("-",$bin_dest);

      // insert d
      for($i=0;$i<count($loc);$i++){
          $line_no = $i+1;
          $this->model_tsc_transferbin_d->doc_no = $doc_no;
          $this->model_tsc_transferbin_d->line_no = $line_no;
          $this->model_tsc_transferbin_d->item_code = $item[$i];
          $this->model_tsc_transferbin_d->qty = $qty[$i];
          $this->model_tsc_transferbin_d->uom = $uom[$i];
          $this->model_tsc_transferbin_d->location_code_from = $loc[$i];
          $this->model_tsc_transferbin_d->zone_code_from = $zone[$i];
          $this->model_tsc_transferbin_d->area_code_from = $area[$i];
          $this->model_tsc_transferbin_d->rack_code_from = $rack[$i];
          $this->model_tsc_transferbin_d->bin_code_from = $bin[$i];
          $this->model_tsc_transferbin_d->location_code_to = $new_bin_dest[0];
          $this->model_tsc_transferbin_d->zone_code_to = $new_bin_dest[1];
          $this->model_tsc_transferbin_d->area_code_to = $new_bin_dest[2];
          $this->model_tsc_transferbin_d->rack_code_to = $new_bin_dest[3];
          $this->model_tsc_transferbin_d->bin_code_to = $new_bin_dest[4];
          $this->model_tsc_transferbin_d->desc = $desc[$i];
          $this->model_tsc_transferbin_d->created_datetime = $datetime;
          $this->model_tsc_transferbin_d->insert();

          // insert d2
          $this->model_tsc_item_sn->location_code = $loc[$i];
          $this->model_tsc_item_sn->zone_code = $zone[$i];
          $this->model_tsc_item_sn->area_code = $area[$i];
          $this->model_tsc_item_sn->rack_code = $rack[$i];
          $this->model_tsc_item_sn->bin_code = $bin[$i];
          $this->model_tsc_item_sn->item_code = $item[$i];
          $this->model_tsc_item_sn->status = "4";
          $result_sn = $this->model_tsc_item_sn->get_list_sn_with_status_limit_order_by_serial_number($qty[$i]);

          /*$k=1;
          foreach($result_sn as $row){
              $this->model_tsc_transferbin_d2->doc_no =  $doc_no;
              $this->model_tsc_transferbin_d2->line_no =  $k;
              $this->model_tsc_transferbin_d2->src_line_no =  $line_no;
              $this->model_tsc_transferbin_d2->item_code =  $item[$i];
              $this->model_tsc_transferbin_d2->qty =  1;
              $this->model_tsc_transferbin_d2->uom =  $uom[$i];
              $this->model_tsc_transferbin_d2->serial_number =  $row["serial_number"];
              $this->model_tsc_transferbin_d2->created_datetime =  $datetime;
              $this->model_tsc_transferbin_d2->insert();
              $k++;
          }*/

          // insert d2 v2
          $insert_by_number_row = 500;
          $row_insert = 1;
          $k=1;
          unset($serial_number_temp); unset($k_temp);

          foreach($result_sn as $row){
              if($row_insert > $insert_by_number_row){ // insert to database
                  $this->model_tsc_transferbin_d2->insert_d2_v2($doc_no, $k_temp, $line_no,$item[$i],1,$uom[$i],$serial_number_temp,$datetime);
                  $row_insert = 1;
                  unset($serial_number_temp);
                  unset($k_temp);
              }

              $serial_number_temp[] = $row["serial_number"];
              $k_temp[] = $k;
              $k++;
              $row_insert++;
          }

          $this->model_tsc_transferbin_d2->insert_d2_v2($doc_no, $k_temp, $line_no,$item[$i],1,$uom[$i],$serial_number_temp,$datetime);
          //---

      }

  }
  //---

  function goto(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'transferbin/goto'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - GoTo Transferbin"); // insert log

          $this->load->view('wms/inbound/transferbin/goto/v_index');
      }
  }
  //----

  function get_transferbin_goto_list(){
      $this->load->model('model_tsc_transferbin_h','',TRUE);

      $status = ["1"];
      $result = $this->model_tsc_transferbin_h->list_by_status($status);

      if(count($result) == 0) $data['var_transferbin_h'];
      else $data["var_transferbin_h"] = assign_data($result);

      $this->load->view('wms/inbound/transferbin/goto/v_list',$data);
  }
  //----

  function get_list_d(){
      $id = $_POST['id'];
      $link = $_POST['link'];
      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->model_tsc_transferbin_d->doc_no = $id;
      $result = $this->model_tsc_transferbin_d->get_list_by_doc_no();
      $data["var_detail"] = assign_data($result);
      $this->load->view($link,$data);
  }
  //---

  function goto_process(){
      $this->model_zlog->insert("Warehouse - GoTo Process Transferbin"); // insert log

      $doc_no = $_GET['docno'];

      $this->load->model('model_tsc_transferbin_h','',TRUE);
      $this->load->model('model_tsc_transferbin_d','',TRUE);

      $this->model_tsc_transferbin_h->doc_no = $doc_no;
      $status = $this->model_tsc_transferbin_h->get_doc_status();
      if($status != 1){
          $this->goto();
      }
      else{
          $this->load->view('templates/navigation');
          $this->model_tsc_transferbin_d->doc_no = $doc_no;
          $result = $this->model_tsc_transferbin_d->get_list_by_doc_no();
          $data['var_transferbin_d'] = assign_data($result);
          $data['doc_no'] = $doc_no ;

          // check pick all
          $check = 1;
          foreach($result as $row){
              if(is_null($row["pick_datetime"]) or ($row["pick_datetime"]=="")){
                  $check = 0;
                  break;
              }
          }

          if($check == 0) $data["var_pick_all"] = "0";
          else $data["var_pick_all"] = "1";
          //---

          // check put all
          $check = 1;
          foreach($result as $row){
              if(is_null($row["putaway_datetime"]) or ($row["putaway_datetime"]=="")){
                  $check = 0;
                  break;
              }
          }

          if($check == 0) $data["var_put_all"] = "0";
          else $data["var_put_all"] = "1";
          //--

          $this->load->view('wms/inbound/transferbin/goto/v_process',$data);
      }
  }
  //---

  function pick(){
      $doc_no = $_POST['doc_no'];
      $line = $_POST["line"];

      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_transferbin_d2','',TRUE);

      $datetime = get_datetime_now();
      $date = get_date_now();

      // update pick datetime d2
      $this->model_tsc_transferbin_d2->pick_datetime = $datetime;
      $this->model_tsc_transferbin_d2->doc_no = $doc_no;
      $this->model_tsc_transferbin_d2->src_line_no = $line;
      $result_d2 = $this->model_tsc_transferbin_d2->update_pick_datetime();
      //---

      // update pick dateteime d
      $this->model_tsc_transferbin_d->pick_datetime = $datetime;
      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $this->model_tsc_transferbin_d->line_no = $line;
      $result_d = $this->model_tsc_transferbin_d->update_pick_datetime();
      //---

      if($result_d && $result_d2){
          $response['status'] = "1";
          $response['msg'] = "Pick finished";
          echo json_encode($response);
      }
      else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
      }
  }
  //---

  function put(){
      $doc_no = $_POST['doc_no'];
      $line = $_POST["line"];

      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_transferbin_d2','',TRUE);

      $datetime = get_datetime_now();
      $date = get_date_now();

      // update pick datetime d2
      $this->model_tsc_transferbin_d2->putaway_datetime = $datetime;
      $this->model_tsc_transferbin_d2->doc_no = $doc_no;
      $this->model_tsc_transferbin_d2->src_line_no = $line;
      $result_d2 = $this->model_tsc_transferbin_d2->update_put_datetime();
      //---

      // update pick dateteime d
      $this->model_tsc_transferbin_d->putaway_datetime = $datetime;
      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $this->model_tsc_transferbin_d->line_no = $line;
      $result_d = $this->model_tsc_transferbin_d->update_put_datetime();
      //---

      if($result_d && $result_d2){
          $response['status'] = "1";
          $response['msg'] = "Put finished";
          echo json_encode($response);
      }
      else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
      }
  }
  //---

  function check_all_item_has_pick_put(){
      $doc_no = $_POST['doc_no'];

      $this->load->model('model_tsc_transferbin_d','',TRUE);

      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $result = $this->model_tsc_transferbin_d->check_already_pick_and_put();

      if(!$result){ echo json_encode("0"); }
      else{ echo json_encode("1"); }
  }
  //---

  function update_status(){
      $doc_no = $_POST['doc_no'];
      $status = $_POST['status'];

      $this->load->model('model_tsc_transferbin_h','',TRUE);

      $this->model_tsc_transferbin_h->statuss = $status;
      $this->model_tsc_transferbin_h->doc_no = $doc_no;
      $result = $this->model_tsc_transferbin_h->update_status();

      if($result){
          $response['status'] = "1";
          $response['msg'] = "TransferBin has been finished";
          echo json_encode($response);
      }
      else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
      }
  }
  //--

  function confirm(){
      $doc_no = $_POST['id'];
      $link = $_POST["link"];

      $this->load->model('model_tsc_transferbin_h','',TRUE);
      $this->load->model('model_tsc_transferbin_d','',TRUE);

      $this->model_tsc_transferbin_h->doc_no = $doc_no;
      $result = $this->model_tsc_transferbin_h->get_list_by_doc_no();
      $data["var_transferbin_h"] = assign_data_one($result);

      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $result = $this->model_tsc_transferbin_d->get_list_by_doc_no();
      $data["var_transferbin_d"] = assign_data($result);

      $data["var_doc_no"] = $doc_no;

      $this->load->view($link,$data);
  }
  //----

  function transfer_stocks(){
      $doc_no = $_POST['id'];
      $line_no = $_POST['line_no'];

      // update item sn (status and change the location by serial number)
      $result_item_sn = $this->update_item_sn_status_and_location($doc_no, $line_no);

      // insert item entry
      if($result_item_sn) $this->insert_item_entry($doc_no, $line_no);

      // update transfer_d confirmed = 1
      $result = $this->update_d_confirmed($doc_no, $line_no, "1");

      if($result){ echo json_encode("1"); }
      else{ echo json_encode("0"); }
  }
  //---

  function update_item_sn_status_and_location($doc_no, $line_no){
      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_transferbin_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);

      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $this->model_tsc_transferbin_d->line_no = $line_no;
      $result_d = $this->model_tsc_transferbin_d->get_list_by_doc_no_and_line_no();
      $result_d = assign_data_one($result_d);

      $this->model_tsc_transferbin_d2->doc_no = $doc_no;
      $this->model_tsc_transferbin_d2->src_line_no = $line_no;
      $result_d2 = $this->model_tsc_transferbin_d2->get_list_by_doc_no_and_src_line_no();

      $this->db->trans_begin();
      /*foreach($result_d2 as $row){
          $this->model_tsc_item_sn->location_code = $result_d["location_code_to"];
          $this->model_tsc_item_sn->zone_code = $result_d["zone_code_to"];;
          $this->model_tsc_item_sn->area_code = $result_d["area_code_to"];
          $this->model_tsc_item_sn->rack_code = $result_d["rack_code_to"];
          $this->model_tsc_item_sn->bin_code = $result_d["bin_code_to"];
          $this->model_tsc_item_sn->status = '1';
          $this->model_tsc_item_sn->serial_number = $row["serial_number"];
          $result = $this->model_tsc_item_sn->update_location_and_status();
      }*/

      $result = $this->model_tsc_item_sn->update_location_and_status_v2($result_d2,$result_d["location_code_to"], $result_d["zone_code_to"], $result_d["area_code_to"], $result_d["rack_code_to"], $result_d["bin_code_to"], 1);

      $this->db->trans_complete();

      if($result) return true; else return false;
  }
  //---

  function insert_item_entry($doc_no, $line_no){
      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_item_entry','',TRUE);

      $this->db->trans_begin();

      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $this->model_tsc_transferbin_d->line_no = $line_no;
      $result_d = $this->model_tsc_transferbin_d->get_list_by_doc_no_and_line_no();

      $datetime = get_datetime_now();
      unset($data);
      foreach($result_d as $row){
        $from = $row['location_code_from']."-".$row['zone_code_from']."-".$row['area_code_from']."-".$row['rack_code_from']."-".$row['bin_code_from'];
        $to = $row['location_code_to']."-".$row['zone_code_to']."-".$row['area_code_to']."-".$row['rack_code_to']."-".$row['bin_code_to'];

        $data[] = array(
          "item_code" => $row["item_code"],
          "qty" => $row['qty']*-1,
          "src_no" => $row['doc_no'],
          "type" => "3",
          "text" => $row['line_no']."|from: ".$from,
          "serial_number" => "",
          "text2" => "",
          "description" => $row['description'],
          "created_datetime" => $datetime,
          "location_code" => $row['location_code_from'], // WH3 2023-05-12
        );

        $data[] = array(
          "item_code" => $row["item_code"],
          "qty" => $row['qty'],
          "src_no" => $row['doc_no'],
          "type" => "3",
          "text" => $row['line_no']."|to: ".$to,
          "serial_number" => "",
          "text2" => "",
          "description" => $row['description'],
          "created_datetime" => $datetime,
          "location_code" => $row['location_code_to'], // WH3 2023-05-12
        );
      }
      $this->model_tsc_item_entry->insert_with_bulk($data);

      $this->db->trans_complete();
  }
  //---

  function update_d_confirmed($doc_no, $line_no, $confirmed){
      $this->load->model('model_tsc_transferbin_d','',TRUE);

      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $this->model_tsc_transferbin_d->line_no = $line_no;
      $this->model_tsc_transferbin_d->confirmed = $confirmed;
      $result = $this->model_tsc_transferbin_d->update_confirmed_by_docno_and_line_no();

      return $result;
  }
  //--

  function update_status3(){
      $doc_no = $_POST['doc_no'];
      $status = $_POST['status'];

      $this->load->model('model_tsc_transferbin_h','',TRUE);
      $this->load->model('model_tsc_transferbin_d','',TRUE);

      // check if all detail has been confirmed
      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $result_d = $this->model_tsc_transferbin_d->check_all_confirmed();

      if($result_d){
        $this->model_tsc_transferbin_h->statuss = $status;
        $this->model_tsc_transferbin_h->doc_no = $doc_no;
        $result = $this->model_tsc_transferbin_h->update_status();

        if($result){
            $response['status'] = "1";
            $response['msg'] = "TransferBin has been confirmed";
            echo json_encode($response);
        }
      }
      else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
      }
  }
  //--

  // 2022-11-05
  function cancel(){
      $doc_no = $_POST['id'];
      $link = $_POST["link"];

      $this->load->model('model_tsc_transferbin_h','',TRUE);
      $this->load->model('model_tsc_transferbin_d','',TRUE);

      $this->model_tsc_transferbin_h->doc_no = $doc_no;
      $result = $this->model_tsc_transferbin_h->get_list_by_doc_no();
      $data["var_transferbin_h"] = assign_data_one($result);

      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $result = $this->model_tsc_transferbin_d->get_list_by_doc_no();
      $data["var_transferbin_d"] = assign_data($result);

      $data["var_doc_no"] = $doc_no;

      $this->load->view($link,$data);
  }
  //----

  // 2022-11-05
  function return_back_the_stocks(){
      $doc_no = $_POST['id'];
      $line_no = $_POST['line_no'];

      $this->load->model('model_tsc_transferbin_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);

      $this->model_tsc_transferbin_d2->doc_no = $doc_no;
      $this->model_tsc_transferbin_d2->src_line_no = $line_no;
      $result_d2 = $this->model_tsc_transferbin_d2->get_list_by_doc_no_and_src_line_no();

      unset($sn);
      foreach($result_d2 as $row){
          $sn[] = $row["serial_number"];
      }
      $this->model_tsc_item_sn->update_status_v3($sn, "1");

      if($result){ echo json_encode("1"); }
      else{ echo json_encode("0"); }
  }
  //---

  function update_status4(){
      $doc_no = $_POST['doc_no'];
      $status = $_POST['status'];

      $this->load->model('model_tsc_transferbin_h','',TRUE);
      $this->load->model('model_tsc_transferbin_d','',TRUE);

      $this->model_tsc_transferbin_h->statuss = $status;
      $this->model_tsc_transferbin_h->doc_no = $doc_no;
      $result = $this->model_tsc_transferbin_h->update_status();

      if($result){
          $response['status'] = "1";
          $response['msg'] = "TransferBin has been canceled";
      }
      else{
          $response['status'] = "0";
          $response['msg'] = "Error";
      }

      echo json_encode($response);
  }
  //--

  // master barcode 2023-01-19
  function get_item_sn_by_master_code_and_barcode(){
      $item_code = $_POST["item"];
      $loc = $_POST["loc"];
      $zone = $_POST["zone"];
      $area = $_POST["area"];
      $rack = $_POST["rack"];
      $bin = $_POST["bin"];

      $this->load->model('model_tsc_item_sn','',TRUE);

      // filter item Banda or Filter
      $item_code_temp = substr($item_code, 0, 3);
      //--

      if($item_code_temp == "TYP") $result = $this->model_tsc_item_sn->get_data_sn2_sn_by_item_rack_ver2($item_code,$loc, $zone, $area, $rack, $bin);
      else $result = $this->model_tsc_item_sn->get_data_sn2_sn_by_item_rack($item_code,$loc, $zone, $area, $rack, $bin);

      if(count($result) == 0){
          $response["status"] = 0;
      }
      else{
          $response["status"] = 1;
          unset($response["data"]);
          foreach($result as $row){
              $response["data"][] = array(
                  "item_code" => $row["item_code"],
                  "sn2" => $row["sn2"],
                  "serial_number" => $row["serial_number"],
                  "location_code" => $row["location_code"],
                  "zone_code" => $row["zone_code"],
                  "area_code" => $row["area_code"],
                  "rack_code" => $row["rack_code"],
                  "bin_code" => $row["bin_code"],
                  "qty" => $row["qty"],
                  "ctn" => $row["ctn"],
                  "pcs" => $row["pcs"],
                  "name" => $row["name"],
              );
          }
      }

      echo json_encode($response);
  }
  //---

  // master barcode 2023-01-19
  function check_rack_from_to(){
      $total_rack_from = $_POST["total_rack_from"];
      $total_rack_to = $_POST["total_rack_to"];

      $from_loc = json_decode(stripslashes($_POST['from_loc']));
      $from_zone = json_decode(stripslashes($_POST['from_zone']));
      $from_area = json_decode(stripslashes($_POST['from_area']));
      $from_rack = json_decode(stripslashes($_POST['from_rack']));
      $from_bin = json_decode(stripslashes($_POST['from_bin']));

      $to_loc = json_decode(stripslashes($_POST['to_loc']));
      $to_zone = json_decode(stripslashes($_POST['to_zone']));
      $to_area = json_decode(stripslashes($_POST['to_area']));
      $to_rack = json_decode(stripslashes($_POST['to_rack']));
      $to_bin = json_decode(stripslashes($_POST['to_bin']));

      $this->load->model('model_mst_bin','',TRUE);

      // check from
      $check_from = 1;
      $error_loc_from = "";
      for($i=0;$i<$total_rack_from;$i++){
          $this->model_mst_bin->location_code = $from_loc[$i];
          $this->model_mst_bin->zone_code = $from_zone[$i];
          $this->model_mst_bin->area_code = $from_area[$i];
          $this->model_mst_bin->rack_code = $from_rack[$i];
          $this->model_mst_bin->code = $from_bin[$i];
          $this->model_mst_bin->active = "1";
          $result = $this->model_mst_bin->check_bin();
          if(count($result) == 0){
              $error_loc_from = combine_location($from_loc[$i], $from_zone[$i], $from_area[$i], $from_rack[$i], $from_bin[$i]);
              $check_from = 0;
              break;
          }
      }

      // check to
      $check_to = 1;
      $error_loc_to = "";
      for($i=0;$i<$total_rack_to;$i++){
          $this->model_mst_bin->location_code = $to_loc[$i];
          $this->model_mst_bin->zone_code = $to_zone[$i];
          $this->model_mst_bin->area_code = $to_area[$i];
          $this->model_mst_bin->rack_code = $to_rack[$i];
          $this->model_mst_bin->code = $to_bin[$i];
          $this->model_mst_bin->active = "1";
          $result = $this->model_mst_bin->check_bin();
          if(count($result) == 0){
              $error_loc_to = combine_location($to_loc[$i], $to_zone[$i], $to_area[$i], $to_rack[$i], $to_bin[$i]);
              $check_to = 0;
              break;
          }
      }

      $response["status_check_from"] = $check_from;
      $response["status_check_to"] = $check_to;
      $response["error_loc_from"] = $error_loc_from;
      $response["error_loc_to"] = $error_loc_to;

      echo json_encode($response);
  }
  //--

  // master barcode 2023-01-20
  function create_new2(){
      $this->model_zlog->insert("Warehouse - Creating Transferbin"); // insert log

      $bin_dest = $_POST['bin_dest'];
      $message = $_POST['message'];
      $doc_user = $_POST["doc_user"];
      $whs = $_POST["whs"];
      $loc = json_decode(stripslashes($_POST['loc']));
      $zone = json_decode(stripslashes($_POST['zone']));
      $area = json_decode(stripslashes($_POST['area']));
      $rack = json_decode(stripslashes($_POST['rack']));
      $bin = json_decode(stripslashes($_POST['bin']));
      $item = json_decode(stripslashes($_POST['item']));
      $desc = json_decode(stripslashes($_POST['desc']));
      $uom = json_decode(stripslashes($_POST['uom']));
      $qty_max = json_decode(stripslashes($_POST['qty_max']));
      $rack_inp = json_decode(stripslashes($_POST['rack_inp']));
      $sn = json_decode(stripslashes($_POST['sn']));
      $sn2 = json_decode(stripslashes($_POST['sn2']));

      $datetime = get_datetime_now();
      $date = get_date_now();
      $session_data = $this->session->userdata('z_tpimx_logged_in');

      // check if stock available to tranf
      $result_avail = $this->check_if_stock_available2($loc,$zone,$area,$rack,$bin,$item,$qty_max,$rack_inp,$sn,$sn2);

      if($result_avil["result"] == "0"){
          $result_end = 0;
          $message_end = "Error, Location = ".$result_avil["result_sn2_error_message"].$result_avil["result_sn2_error_message"];
      }
      else{ // if everything is ok go to transfer

          // create header document
          $new_doc_no = $this->create_header_doc($datetime, $date, $message, $session_data['z_tpimx_user_id'], $doc_user, "1", $whs);

          // insert detail
          $this->insert_detail2($loc, $zone, $area, $rack, $bin, $item ,$desc,$uom,$qty_max, $new_doc_no, $datetime, $rack_inp, $sn, $sn2);

          $result_end = 1;
          $message_end = "New Transfer Bin Document has been created with No = ".$new_doc_no;
      }


      if($result_end){
          $response['status'] = "1";
          $response['msg'] = $message_end;
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = $message_end;
        echo json_encode($response);
      }
  }
  //---

  // master barcode 2023-01-20
  function check_if_stock_available2($loc,$zone,$area,$rack,$bin,$item,$qty_max,$rack_inp,$sn,$sn2){
      $this->load->model('model_tsc_item_sn','',TRUE);

      $if_all_ok = 1;
      $if_all_sn2_ok = 0;
      $if_all_sn_ok = 0;

      $result_sn2_error_message = "";
      $result_sn_error_message = "";

      unset($sn_temp); unset($sn2_temp);
      $sn_temp_total_qty = 0;
      $sn2_temp_total_qty = 0;

      // seperate master barcode
      for($i=0; $i<count($loc); $i++){
          if($sn[$i] == 0){
            $sn2_temp[] = $sn2[$i];
            $sn2_temp_total_qty += $qty_max[$i];
          }
          else{
            $sn_temp[] = $sn[$i];
            $sn_temp_total_qty += $qty_max[$i];
          }
      }

      // compare master barcode total qty
      if($sn2_temp_total_qty > 0){
        $total_qty_sn2 = $this->model_tsc_item_sn->get_total_qty_by_sn2($sn2_temp);
        if($total_qty_sn2 == $sn2_temp_total_qty){
            $if_all_sn2_ok = 1;
        }
      }
      //--

      // compare serial number total qty
      if($sn_temp_total_qty > 0){
        $total_qty_sn = $this->model_tsc_item_sn->get_total_qty_by_serial_number($sn_temp);
        if($total_qty_sn == $sn_temp_total_qty){
            $if_all_sn_ok = 1;
        }
      }
      //---

      // if sn or sn2 not ok.. so check which sn and sn2 then return the result
      if($if_all_sn2_ok == 0){
          if($sn2_temp_total_qty > 0){
            $result_sn2_error = $this->model_tsc_item_sn->get_sn2_not_completed($sn2_temp);
            if(count($result_sn2_error) > 0){
                  $if_all_ok = 0;
                  $if_all_sn2_ok = 0;

                  foreach($result_sn2_error as $row){
                      $result_sn2_error_message.= $row.",";
                  }
            }
          }
      }

      if($if_all_sn_ok == 0){
          if($sn_temp_total_qty > 0){
            $result_sn_error = $this->model_tsc_item_sn->get_sn_not_status_available($sn);
            if(count($result_sn_error) > 0){
                  $if_all_ok = 0;
                  $if_all_sn_ok = 0;

                  foreach($result_sn_error as $row){
                      $result_sn_error_message.= $row.",";
                  }
            }
          }
      }
      //---

      if($if_all_ok == 1){
          $data["result"] = 1;
      }
      else{
          $data["result"] = 0;
          $data["result_sn_error_message"] = $result_sn_error_message;
          $data["result_sn2_error_message"] = $result_sn2_error_message;
      }

      return $data;
  }
  //----

  function insert_detail2($loc, $zone, $area, $rack, $bin, $item, $desc,$uom,$qty, $doc_no, $datetime, $rack_inp, $sn, $sn2){
      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_transferbin_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);

      unset($d_to_loc); unset($d_to_zone); unset($d_to_area); unset($d_to_rack); unset($d_to_bin);

      unset($d2_doc_no); unset($d2_src_line_no); unset($d2_item_code); unset($d2_qty); unset($d2_uom); unset($d2_sn);
      unset($d2_created_datetime); unset($d2_sn2); unset($d2_line_no);

      $j = 1;
      for($i=0;$i<count($loc);$i++){
          $line_no[$i] = $j;
          $j++;

          $temp = explode("-",$rack_inp[$i]);

          $d_to_loc[] = $temp[0];
          $d_to_zone[] = $temp[1];
          $d_to_area[] = $temp[2];
          $d_to_rack[] = $temp[3];
          $d_to_bin[] = $temp[4];
      }

      // seperate sn2 and sn
      unset($seperate_sn2); unset($seperate_sn);
      for($i=0;$i<count($loc);$i++){
        if($sn[$i] == 0){
          $seperate_sn2[] = $sn2[$i];
          $seperate_sn2_data[$sn2[$i]]["line_no"] = $line_no[$i];
          $seperate_sn2_data[$sn2[$i]]["item"]    = $item[$i];
          $seperate_sn2_data[$sn2[$i]]["qty"]     = $qty[$i];
          $seperate_sn2_data[$sn2[$i]]["uom"]     = $uom[$i];
          $seperate_sn2_data[$sn2[$i]]["sn"]      = $sn[$i];
          $seperate_sn2_data[$sn2[$i]]["sn2"]     = $sn2[$i];
          $seperate_sn2_data[$sn2[$i]]["line_no"] = $line_no[$i];
        }
      }
      //--

      $j=1;
      if(isset($seperate_sn2)){
          if(count($seperate_sn2) > 0){
                $result_sn = $this->model_tsc_item_sn->get_sn_by_sn2($seperate_sn2);
                if(count($result_sn) > 0){
                  foreach($result_sn as $row){
                      $d2_doc_no[] = $doc_no;
                      $d2_src_line_no[] = $seperate_sn2_data[$row["sn2"]]["line_no"];
                      $d2_item_code[] = $seperate_sn2_data[$row["sn2"]]["item"];
                      $d2_qty[] = $seperate_sn2_data[$row["sn2"]]["qty"];
                      $d2_uom[] = $seperate_sn2_data[$row["sn2"]]["uom"];
                      $d2_sn[] = $row["serial_number"];
                      $d2_sn2[] = $seperate_sn2_data[$row["sn2"]]["sn2"];
                      $d2_created_datetime = $datetime;
                      $d2_line_no[] = $j;
                      $j++;
                  }
                }
          }
      }

      for($i=0; $i<count($loc); $i++){
          if($sn[$i] == 0){ // if master barcode
          }
          else{ // if not master barcode
              $d2_doc_no[] = $doc_no;
              $d2_src_line_no[] = $line_no[$i];
              $d2_item_code[] = $item[$i];
              $d2_qty[] = $qty[$i];
              $d2_uom[] = $uom[$i];
              $d2_sn[] = $sn[$i];
              $d2_sn2[] = $sn2[$i];
              $d2_created_datetime = $datetime;
              $d2_line_no[] = $j;
              $j++;
          }
      }

      /*
      for($i=0; $i<count($loc); $i++){
          $j=1;
          if($sn[$i] == 0){ // if master barcode
              //get detail serial_number
              $result_sn = $this->model_tsc_item_sn->get_sn_by_sn2_v2($sn2[$i]);
              if(count($result_sn) > 0){
                foreach($result_sn as $row){
                    $d2_doc_no[] = $doc_no;
                    $d2_src_line_no[] = $line_no[$i];
                    $d2_item_code[] = $item[$i];
                    $d2_qty[] = $qty[$i];
                    $d2_uom[] = $uom[$i];
                    $d2_sn[] = $row["serial_number"];
                    $d2_sn2[] = $sn2[$i];
                    $d2_created_datetime = $datetime;
                    $d2_line_no[] = $j;
                    $j++;
                }
              }
          }
          else{ // if not master barcode
              $d2_doc_no[] = $doc_no;
              $d2_src_line_no[] = $line_no[$i];
              $d2_item_code[] = $item[$i];
              $d2_qty[] = $qty[$i];
              $d2_uom[] = $uom[$i];
              $d2_sn[] = $sn[$i];
              $d2_sn2[] = $sn2[$i];
              $d2_created_datetime = $datetime;
              $d2_line_no[] = $j;
              $j++;
          }
      }
      */

      // change status item_sn to 4
      $this->model_tsc_item_sn->update_status_v3($d2_sn, "4");

      // insert transferbin_d
      $this->model_tsc_transferbin_d->insert_v2($doc_no,$item,$qty,$uom,$loc,$zone, $area, $rack, $bin, $d_to_loc, $d_to_zone, $d_to_area, $d_to_rack, $d_to_bin, $desc , $datetime, $line_no);

      // insert transferbin_d2
      $this->model_tsc_transferbin_d2->insert_v3($d2_doc_no, $d2_src_line_no, $d2_item_code, $d2_qty, $d2_uom, $d2_sn, $d2_created_datetime, $d2_sn2, $d2_line_no);

  }
  //---

  function pick_all(){
      $doc_no = $_POST['doc_no'];
      $line = $_POST["line"];

      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_transferbin_d2','',TRUE);

      $datetime = get_datetime_now();
      $date = get_date_now();

      // update pick datetime d2
      $this->model_tsc_transferbin_d2->pick_datetime = $datetime;
      $this->model_tsc_transferbin_d2->doc_no = $doc_no;
      $result_d2 = $this->model_tsc_transferbin_d2->update_pick_datetime2();
      //---

      // update pick dateteime d
      $this->model_tsc_transferbin_d->pick_datetime = $datetime;
      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $result_d = $this->model_tsc_transferbin_d->update_pick_datetime2();
      //---

      if($result_d && $result_d2){
          $response['status'] = "1";
          $response['msg'] = "Pick All finished";
          echo json_encode($response);
      }
      else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
      }
  }
  //----

  function put_all(){
      $doc_no = $_POST['doc_no'];
      $line = $_POST["line"];

      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_transferbin_d2','',TRUE);

      $datetime = get_datetime_now();
      $date = get_date_now();

      // update pick datetime d2
      $this->model_tsc_transferbin_d2->putaway_datetime = $datetime;
      $this->model_tsc_transferbin_d2->doc_no = $doc_no;
      $result_d2 = $this->model_tsc_transferbin_d2->update_put_datetime2();
      //---

      // update pick dateteime d
      $this->model_tsc_transferbin_d->putaway_datetime = $datetime;
      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $result_d = $this->model_tsc_transferbin_d->update_put_datetime2();
      //---

      if($result_d && $result_d2){
          $response['status'] = "1";
          $response['msg'] = "Put All finished";
          echo json_encode($response);
      }
      else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
      }
  }
  //---

  // 2023-07-28
  function transfer_stocks2(){
      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE); // 2023-10-26
      $this->load->model('model_tsc_item_entry','',TRUE); // 2023-10-26

      $doc_no = $_POST['id'];
      //$line_no = $_POST['line_no'];

      $this->model_tsc_transferbin_d->doc_no = $doc_no;
      $result_doc = $this->model_tsc_transferbin_d->get_list_by_doc_no();

      // check if banda
      foreach($result_doc as $row){
          if(substr($row["item_code"], 0, 3) == "TYP") $isbanda = 1;
          else $isbanda = 0;

          break;
      }
      //---

      if($isbanda == 0){
        foreach($result_doc as $row){
            // update item sn (status and change the location by serial number)
            $result_item_sn = $this->update_item_sn_status_and_location($doc_no, $row["line_no"]);

            // insert item entry
            if($result_item_sn) $this->insert_item_entry($doc_no, $row["line_no"]);

            // update transfer_d confirmed = 1
            $result = $this->update_d_confirmed($doc_no, $row["line_no"], "1");
        }
      }
      else{
          // update location
          $result = $this->model_tsc_transferbin_d->get_doc_d_d2($doc_no);
          foreach($result as $row){
              $data[] = array(
                "sn" => $row["serial_number"],
                "location" => $row["location_code_to"],
                "zone" => $row["zone_code_to"],
                "area" => $row["area_code_to"],
                "rack" => $row["rack_code_to"],
                "bin" => $row["bin_code_to"],
                "status" => 1,
              );
          }
          $this->model_tsc_item_sn->update_location_v5($data);
          //---

          unset($data);

          // insert item entry
          foreach($result as $row){
            $from = $row['location_code_from']."-".$row['zone_code_from']."-".$row['area_code_from']."-".$row['rack_code_from']."-".$row['bin_code_from'];
            $to = $row['location_code_to']."-".$row['zone_code_to']."-".$row['area_code_to']."-".$row['rack_code_to']."-".$row['bin_code_to'];

            $data[] = array(
              "item_code" => $row["item_code"],
              "qty" => -1,
              "src_no" => $row['doc_no'],
              "type" => "3",
              "text" => $row['line_no']."|from: ".$from,
              "serial_number" => "",
              "text2" => "",
              "description" => $row['description'],
              "created_datetime" => $datetime,
              "location_code" => $row['location_code_from'], // WH3 2023-05-12
            );

            $data[] = array(
              "item_code" => $row["item_code"],
              "qty" => 1,
              "src_no" => $row['doc_no'],
              "type" => "3",
              "text" => $row['line_no']."|to: ".$to,
              "serial_number" => "",
              "text2" => "",
              "description" => $row['description'],
              "created_datetime" => $datetime,
              "location_code" => $row['location_code_to'], // WH3 2023-05-12
            );
          }
          $this->model_tsc_item_entry->insert_with_bulk($data);
          //--

          $this->model_tsc_transferbin_d->doc_no = $doc_no;
          $this->model_tsc_transferbin_d->confirmed = "1";
          $result = $this->model_tsc_transferbin_d->update_confirmed_by_docno();
      }

      if($result){
          $response['status'] = "1";
          $response['msg'] = "TransferBin has been confirmed";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //---

  // 2023-10-26
  function create_new3(){
      $this->model_zlog->insert("Warehouse - Creating Transferbin"); // insert log

      $bin_dest = $_POST['bin_dest'];
      $message = $_POST['message'];
      $doc_user = $_POST["doc_user"];
      $loc = json_decode(stripslashes($_POST['loc']));
      $zone = json_decode(stripslashes($_POST['zone']));
      $area = json_decode(stripslashes($_POST['area']));
      $rack = json_decode(stripslashes($_POST['rack']));
      $bin = json_decode(stripslashes($_POST['bin']));
      $item = json_decode(stripslashes($_POST['item']));
      $desc = json_decode(stripslashes($_POST['desc']));
      $uom = json_decode(stripslashes($_POST['uom']));
      $qty_max = json_decode(stripslashes($_POST['qty_max']));
      $qty_inp = json_decode(stripslashes($_POST['qty_max']));
      //$qty_inp = json_decode(stripslashes($_POST['qty_inp']));
      $sn = json_decode(stripslashes($_POST['sn'])); // 2023-10-26
      $sn2 = json_decode(stripslashes($_POST['sn2'])); // 2023-10-26
      $whs = $_POST["whs"]; // 2023-10-26
      $rack_inp = json_decode(stripslashes($_POST['rack_inp'])); // 2023-10-26

      $datetime = get_datetime_now();
      $date = get_date_now();
      $session_data = $this->session->userdata('z_tpimx_logged_in');

      $this->load->model('model_tsc_item_sn','',TRUE);

      // check all the SN will is available
      unset($sn_temp);
      for($i=0;$i<count($loc);$i++){
          $sn_temp[] = $sn[$i];
      }

      $result_avil["result"] = $this->model_tsc_item_sn->get_not_status_by_multiple_sn($sn_temp,"1");
      //---

      if($result_avil["result"] == 0){
          $result_end = 0;
          //$message_end = "Error, Location = ".$result_avil["loc_error"]."-".$result_avil["zone_error"]."-".$result_avil["area_error"]."-".$result_avil["rack_error"]."-".$result_avil["bin_error"].", Item = ".$result_avil["item_error"].", ".$result_avil["message"];
          $message_end = "Error";
      }
      else{ // if everything is ok go to transfer

          $this->model_tsc_item_sn->update_status_v3($sn_temp, "4");

          // create header document
          $new_doc_no = $this->create_header_doc($datetime, $date, $message, $session_data['z_tpimx_user_id'], $doc_user, "1",$whs);

          // insert detail
          $this->insert_detail3($loc, $zone, $area, $rack, $bin, $item ,$desc,$uom,$qty_inp,$rack_inp, $new_doc_no, $datetime, $sn, $sn2);

          $result_end = 1;
          $message_end = "New Transfer Bin Document has been created with No = ".$new_doc_no;
      }


      if($result_end){
          $response['status'] = "1";
          $response['msg'] = $message_end;
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = $message_end;
        echo json_encode($response);
      }

  }
  //--

  //2023-10-26
  function insert_detail3($loc, $zone, $area, $rack, $bin, $item, $desc,$uom, $qty ,$bin_dest, $doc_no, $datetime, $sn, $sn2){
      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_transferbin_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);

      unset($d_doc_no); unset($d_line_no); unset($d_item); unset($d_qty); unset($d_uom);
      unset($d_desc); unset($d_created_datetime);
      unset($d_loc_from); unset($d_zone_from); unset($d_area_from); unset($d_rack_from); unset($d_bin_from);
      unset($d_loc_to); unset($d_zone_to); unset($d_area_to); unset($d_rack_to); unset($d_bin_to);

      unset($d2_doc_no); unset($d2_src_line_no); unset($d2_item); unset($d2_qty); unset($d2_uom); unset($d2_sn);
      unset($d2_created_datetime); unset($d2_sn2); unset($d2_line_no);

      for($i=0;$i<count($loc);$i++){
          $line_no = $i+1;
          $d_doc_no[]=$doc_no; $d_line_no[]=$i+1; $d_item[]=$item[$i]; $d_qty[]=$qty[$i]; $d_uom[]=$uom[$i];
          $d_desc[]=$desc[$i]; $d_created_datetime[]=$datetime;
          $d_loc_from[]=$loc[$i];
          $d_zone_from[]=$zone[$i];
          $d_area_from[]=$area[$i];
          $d_rack_from[]=$rack[$i];
          $d_bin_from[]=$bin[$i];

          $new_bin_dest = explode("-",$bin_dest[$i]);

          $d_loc_to[]=$new_bin_dest[0];
          $d_zone_to[]=$new_bin_dest[1];
          $d_area_to[]=$new_bin_dest[2];
          $d_rack_to[]=$new_bin_dest[3];
          $d_bin_to[]=$new_bin_dest[4];

          $d2_doc_no[] = $doc_no; $d2_src_line_no[] = $line_no;
          $d2_item[] = $item[$i]; $d2_qty[] = $qty[$i];
          $d2_uom[] = $uom[$i]; $d2_sn[] = $sn[$i];
          $d2_created_datetime[] = $datetime; $d2_sn2[] = $sn2[$i]; $d2_line_no[] = $line_no;
      }

      // insert d
      $this->model_tsc_transferbin_d->insert_v2($doc_no,$d_item, $d_qty, $d_uom, $d_loc_from,$d_zone_from, $d_area_from, $d_rack_from, $d_bin_from, $d_loc_to,$d_zone_to, $d_area_to, $d_rack_to, $d_bin_to, $d_desc, $datetime, $d_line_no);

      // insert d2
      $this->model_tsc_transferbin_d2->insert_v3($d2_doc_no, $d2_src_line_no, $d2_item, $d2_qty, $d2_uom, $d2_sn, $datetime, $d2_sn2, $d2_line_no);
  }
  //---
}
