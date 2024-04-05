<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Putaway extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();

    $this->load->model('model_zlog','',TRUE);
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'putaway'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - PutAway"); // insert log

          $this->load->view('wms/inbound/v_putaway');
      }
  }
  //---

  function get_putaway_list(){
      $this->load->model('model_tsc_putaway_h','',TRUE);

      $status = ["6","7"];
      $result = $this->model_tsc_putaway_h->list_by_status($status);
      $data['var_putaway'] = assign_data($result);

      $this->load->view('wms/inbound/v_putaway_list',$data);
  }
  //----

  function new(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'putaway'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->model('model_login','',TRUE);
          $this->load->model('model_config','',TRUE);

          $this->model_config->name = "putaway_depart";
          $result_config = $this->model_config->get_value_by_setting_name();

          $depart = explode("|",$result_config);
          $result = $this->model_login->get_user_list_by_department($depart);
          $data['user_list'] = assign_data($result);

          $this->load->view('wms/inbound/v_putaway_new', $data);
      }
  }
  //--

  function get_source_list(){
      $return_link = $_POST['link'];
      $h_whs = $_POST["h_whs"];

      $this->load->model('model_tsc_received_d','',TRUE);

      $status = ["3"];
      $result = $this->model_tsc_received_d->get_list_outstanding_and_has_gen_sn($h_whs);
      $data['var_source_list'] = assign_data($result);

      $this->load->view($return_link,$data);
  }
  //---

  function get_bin(){
      $return_link = $_POST['link'];
      $total_qty_outstanding = $_POST['total_qty_outstanding'];
      $total_qty_put = $_POST['total_qty_put'];
      $doc_no = $_POST['doc_no'];
      $line_no = $_POST['line_no'];
      $item_code = $_POST['item_code'];
      $desc = $_POST['desc'];
      $uom = $_POST['uom'];
      $row_doc = $_POST['row_doc'];

      $this->load->model('Model_mst_bin','',TRUE);

      $result = $this->Model_mst_bin->get_data();
      $data['var_bin'] = assign_data($result);
      $data['total_qty_outstanding'] = $total_qty_outstanding;
      $data['total_qty_put'] = $total_qty_put;
      $data['doc_no'] = $doc_no;
      $data['line_no'] = $line_no;
      $data['item_code'] = $item_code;
      $data['desc'] = $desc;
      $data['uom'] = $uom;
      $data['row_doc'] = $row_doc;

      $this->load->view('wms/inbound/putaway/v_putaway_bin_list',$data);
  }
  //---

  function create_new(){
      $h_doc_received = json_decode(stripslashes($_POST['h_doc_received']));
      $h_line = json_decode(stripslashes($_POST['h_line']));
      $h_loc = json_decode(stripslashes($_POST['h_loc']));
      $h_item_code = json_decode(stripslashes($_POST['h_item_code']));
      $h_desc = json_decode(stripslashes($_POST['h_desc']));
      $h_qty_total = json_decode(stripslashes($_POST['h_qty_total']));
      $h_qty_put = json_decode(stripslashes($_POST['h_qty_put']));
      $h_qty_rem = json_decode(stripslashes($_POST['h_qty_rem']));
      $h_uom = json_decode(stripslashes($_POST['h_uom']));
      $d_doc_no = json_decode(stripslashes($_POST['d_doc_no']));
      $d_line_no = json_decode(stripslashes($_POST['d_line_no']));
      $d_item_code = json_decode(stripslashes($_POST['d_item_code']));
      $d_desc = json_decode(stripslashes($_POST['d_desc']));
      $d_qty = json_decode(stripslashes($_POST['d_qty']));
      $d_uom = json_decode(stripslashes($_POST['d_uom']));
      $d_loc = json_decode(stripslashes($_POST['d_loc']));
      $d_zone = json_decode(stripslashes($_POST['d_zone']));
      $d_area = json_decode(stripslashes($_POST['d_area']));
      $d_rack = json_decode(stripslashes($_POST['d_rack']));
      $d_bin = json_decode(stripslashes($_POST['d_bin']));
      $counter_h = $_POST['counter_h'];
      $counter_d = $_POST['counter_d'];
      $h_doc_date = $_POST['h_doc_date'];
      $h_doc_user = $_POST['h_doc_user'];
      $message = $_POST['message'];

      $this->load->model('model_tsc_doc_history','',TRUE);

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $created_user = $session_data['z_tpimx_user_id'];

      $datetime = get_datetime_now();
      $date = get_date_now();

      // create new document
      $no_header_doc = $this->create_header_doc($h_doc_date, $h_doc_user,$h_loc[0], "1", $created_user,"6",$datetime,$datetime,$h_doc_user);

      // insert detail
      $this->create_detail($no_header_doc, $h_doc_received, $h_line, $h_loc, $h_item_code, $h_desc, $h_qty_total, $h_qty_put, $h_qty_rem, $h_uom, $datetime, $d_doc_no, $d_line_no, $d_item_code, $d_qty, $d_uom, $d_loc, $d_zone, $d_area, $d_rack, $d_bin, $d_desc);

      // update complete put away at received
      $this->update_received_qty_outstanding($h_doc_received,$h_line,$h_qty_rem,$h_qty_put, $h_qty_total);

      //update text
      $this->update_text($no_header_doc,$message);

      // insert doc history
      foreach($h_doc_received as $row){
          $this->model_tsc_doc_history->insert($no_header_doc,$row,"","6","",$datetime, $message,"");
      }

      //--

      if($no_header_doc){
          $response['status'] = "1";
          $response['msg'] = "New Put Away Document has been created with No = ".$no_header_doc;
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }

  }
  //--

  function create_header_doc($h_doc_date, $h_doc_user,$h_loc, $doc_type, $created_user, $status,$created_datetime,$doc_datetime, $assign_user){
      $this->load->model('model_tsc_putaway_h','',TRUE);
      $this->load->model('model_config','',TRUE);

      // get prefix from config
      $this->model_config->name = "pref_putaway";
      $prefix = $this->model_config->get_value_by_setting_name();
      //--

      $this->model_tsc_putaway_h->prefix_code = $prefix;
      $this->model_tsc_putaway_h->created_datetime = $created_datetime;
      $this->model_tsc_putaway_h->doc_datetime = $doc_datetime;
      $this->model_tsc_putaway_h->doc_type = $doc_type;
      $this->model_tsc_putaway_h->src_location_code = $h_loc;
      $this->model_tsc_putaway_h->created_user = $created_user;
      $this->model_tsc_putaway_h->external_document =  "";
      $this->model_tsc_putaway_h->statuss =  $status;
      $this->model_tsc_putaway_h->doc_date =  $h_doc_date;
      $this->model_tsc_putaway_h->assign_user = $assign_user;
      $result = $this->model_tsc_putaway_h->call_store_procedure_newputaway();

      return $result;
  }
  //---

  function create_detail($no_header_doc, $h_doc_received, $h_line, $h_loc, $h_item_code, $h_desc, $h_qty_total, $h_qty_put, $h_qty_rem, $h_uom, $created_datetime, $d_doc_no, $d_line_no, $d_item_code, $d_qty, $d_uom, $d_loc, $d_zone, $d_area, $d_rack, $d_bin, $d_desc){
      $this->load->model('model_tsc_putaway_d','',TRUE);

      $k = 1;
      $x = 1;
      for($i=0;$i<count($h_doc_received);$i++){
          $this->model_tsc_putaway_d->doc_no = $no_header_doc;
          $this->model_tsc_putaway_d->line_no = ($i+1);
          $this->model_tsc_putaway_d->src_location_code = $h_loc[$i];
          $this->model_tsc_putaway_d->src_no = $h_doc_received[$i];
          $this->model_tsc_putaway_d->src_line_no = $h_line[$i];
          $this->model_tsc_putaway_d->item_code = $h_item_code[$i];
          $this->model_tsc_putaway_d->uom = $h_uom[$i];
          $this->model_tsc_putaway_d->qty_to_put = $h_qty_put[$i];
          $this->model_tsc_putaway_d->desc = $h_desc[$i];
          $this->model_tsc_putaway_d->created_datetime = $created_datetime;
          $this->model_tsc_putaway_d->insert();

          // insert detail2
          $temp = $this->create_detail2($no_header_doc, $d_doc_no, $d_line_no, $d_item_code, $d_qty, $d_uom, $d_loc, $d_zone, $d_area, $d_rack, $d_bin, $created_datetime, $h_doc_received[$i], ($i+1),$h_line[$i], $d_desc, $k, $x);
          $k = $temp['k'];
          $x = $temp['x'];
      }
      return true;
  }
  //---

  function create_detail2($no_header_doc, $d_doc_no, $d_line_no, $d_item_code, $d_qty, $d_uom, $d_loc, $d_zone, $d_area, $d_rack, $d_bin, $created_datetime, $h_doc_received,$src_line_no, $h_line, $d_desc,$k, $x){

      $this->load->model('model_tsc_putaway_d3','',TRUE);
      $this->load->model('model_tsc_putaway_d2','',TRUE);
      $this->load->model('model_tsc_received_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);

      $datetime = get_datetime_now();

      //$j=1;
      for($i=0;$i<count($d_doc_no);$i++){
          if(($d_doc_no[$i] == $h_doc_received) && ($d_line_no[$i]==$h_line)){
              $this->model_tsc_putaway_d2->doc_no = $no_header_doc;
              $this->model_tsc_putaway_d2->src_no = $d_doc_no[$i];
              $this->model_tsc_putaway_d2->line_no = $x;
              $this->model_tsc_putaway_d2->src_line_no = $src_line_no;
              $this->model_tsc_putaway_d2->item_code = $d_item_code[$i];
              $this->model_tsc_putaway_d2->qty = $d_qty[$i];
              $this->model_tsc_putaway_d2->uom = $d_uom[$i];
              $this->model_tsc_putaway_d2->location_code = $d_loc[$i];
              $this->model_tsc_putaway_d2->zone_code = $d_zone[$i];
              $this->model_tsc_putaway_d2->area_code = $d_area[$i];
              $this->model_tsc_putaway_d2->rack_code = $d_rack[$i];
              $this->model_tsc_putaway_d2->bin_code = $d_bin[$i];
              $this->model_tsc_putaway_d2->created_datetime = $created_datetime;
              $this->model_tsc_putaway_d2->description = $d_desc[$i];
              $this->model_tsc_putaway_d2->insert();


              // insert detail3
              $this->model_tsc_received_d2->doc_no = $d_doc_no[$i];
              $this->model_tsc_received_d2->statuss = "0";
              $this->model_tsc_received_d2->line_no = $h_line;
              $result_d3 = $this->model_tsc_received_d2->get_data_by_doc_no_line_no_statuss_order_by_datetime_limit($d_qty[$i]);
              $data_d3 = assign_data($result_d3);

              //$k=1;

              /*foreach($data_d3 as $row){
                $this->model_tsc_putaway_d3->doc_no = $no_header_doc;
                $this->model_tsc_putaway_d3->src_no = $d_doc_no[$i];
                $this->model_tsc_putaway_d3->line_no= $k;
                $this->model_tsc_putaway_d3->src_line_no= $src_line_no;
                $this->model_tsc_putaway_d3->item_code= $d_item_code[$i];
                $this->model_tsc_putaway_d3->qty= 1;
                $this->model_tsc_putaway_d3->uom= $d_uom[$i];
                $this->model_tsc_putaway_d3->location_code_put= $d_loc[$i];
                $this->model_tsc_putaway_d3->zone_code_put= $d_zone[$i];
                $this->model_tsc_putaway_d3->area_code_put= $d_area[$i];
                $this->model_tsc_putaway_d3->rack_code_put= $d_rack[$i];
                $this->model_tsc_putaway_d3->bin_code_put= $d_bin[$i];
                $this->model_tsc_putaway_d3->serial_number_put= $row['serial_number'];
                $this->model_tsc_putaway_d3->created_datetime= $datetime;
                $this->model_tsc_putaway_d3->description= $d_desc[$i];
                $this->model_tsc_putaway_d3->src_line_no_d2= $x;
                $this->model_tsc_putaway_d3->insert();
                $k++;
              }
              //---*/

              // insert version 2
              unset($d_doc_no_temp); unset($k_temp); unset($d_item_code_temp);
              unset($d_uom_temp); unset($d_loc_temp); unset($d_zone_temp);
              unset($d_area_temp); unset($d_rack_temp); unset($d_bin_temp);
              unset($d_desc_temp); unset($serial_number_temp);

              $insert_by_number_row = 500;
              $row_insert = 1;

              foreach($data_d3 as $row){
                if($row_insert > $insert_by_number_row){ // insert to database
                    $this->model_tsc_putaway_d3->insert_v2($no_header_doc, $d_doc_no_temp, $k_temp, $src_line_no, $d_item_code_temp, 1, $d_uom_temp, $d_loc_temp, $d_zone_temp, $d_area_temp, $d_rack_temp, $d_bin_temp, $serial_number_temp, $datetime, $d_desc_temp, $x);  // insert
                    $row_insert = 1;
                    unset($d_doc_no_temp);
                    unset($k_temp);
                    unset($d_item_code_temp);
                    unset($d_uom_temp);
                    unset($d_loc_temp);
                    unset($d_zone_temp);
                    unset($d_area_temp);
                    unset($d_rack_temp);
                    unset($d_bin_temp);
                    unset($d_desc_temp);
                    unset($serial_number_temp);

                    $d_doc_no_temp[] = $d_doc_no[$i];
                    $k_temp[] = $k;
                    $d_item_code_temp[] = $d_item_code[$i];
                    $d_uom_temp[] = $d_uom[$i];
                    $d_loc_temp[] = $d_loc[$i];
                    $d_zone_temp[] = $d_zone[$i];
                    $d_area_temp[] = $d_area[$i];
                    $d_rack_temp[] = $d_rack[$i];
                    $d_bin_temp[] = $d_bin[$i];
                    $d_desc_temp[] = $d_desc[$i];
                    $serial_number_temp[] = $row['serial_number'];
                }
                else{
                    $d_doc_no_temp[] = $d_doc_no[$i];
                    $k_temp[] = $k;
                    $d_item_code_temp[] = $d_item_code[$i];
                    $d_uom_temp[] = $d_uom[$i];
                    $d_loc_temp[] = $d_loc[$i];
                    $d_zone_temp[] = $d_zone[$i];
                    $d_area_temp[] = $d_area[$i];
                    $d_rack_temp[] = $d_rack[$i];
                    $d_bin_temp[] = $d_bin[$i];
                    $d_desc_temp[] = $d_desc[$i];
                    $serial_number_temp[] = $row['serial_number'];
                }

                $row_insert++;
                $k++;
              }

              $this->model_tsc_putaway_d3->insert_v2($no_header_doc, $d_doc_no_temp, $k_temp, $src_line_no, $d_item_code_temp, 1, $d_uom_temp, $d_loc_temp, $d_zone_temp, $d_area_temp, $d_rack_temp, $d_bin_temp, $serial_number_temp, $datetime, $d_desc_temp, $x);  // insert last rows
              //---

              // update status in received2
              /*foreach($data_d3 as $row){
                  $this->model_tsc_received_d2->status = 1;
                  $this->model_tsc_received_d2->serial_number = $row['serial_number'];
                  $this->model_tsc_received_d2->update_status();
              }*/

              // update status v2
              $row_insert = 1;
              unset($serial_number_temp);
              foreach($data_d3 as $row){
                  if($row_insert > $insert_by_number_row){ // insert to database
                      $this->model_tsc_received_d2->update_status_v2($serial_number_temp,1);
                      $row_insert = 1;
                      unset($serial_number_temp);
                  }

                  $serial_number_temp[] = $row['serial_number'];
                  $row_insert++;
              }
              $this->model_tsc_received_d2->update_status_v2($serial_number_temp,1);
              //--

              //$j++;
              $x++;
          }
      }
      $temp['x'] = $x; $temp['k']=$k;
      return $temp;
  }
  //---

  function update_received_qty_outstanding($h_doc_received,$h_line,$h_qty_rem,$h_qty_put, $h_qty_total){
    $this->load->model('model_tsc_received_d','',TRUE);

    debug("21 = ".date("Y-m-d h:i:s"));
    for($i=0;$i<count($h_doc_received);$i++){
          $qty_update = $h_qty_total[$i] - $h_qty_put[$i];
          $this->model_tsc_received_d->doc_no = $h_doc_received[$i];
          $this->model_tsc_received_d->line_no = $h_line[$i];
          $this->model_tsc_received_d->qty_outstanding = $qty_update;
          $this->model_tsc_received_d->update_qty_outstanding();
    }
    debug("22 = ".date("Y-m-d h:i:s"));
    return true;
  }
  //---

  function get_putaway_list_d(){
      $doc_no = $_POST['id'];
      $return_link = $_POST['link'];

      $this->load->model('model_tsc_putaway_d','',TRUE);

      $this->model_tsc_putaway_d->doc_no = $doc_no;
      $result = $this->model_tsc_putaway_d->get_list_data();
      $data['var_putaway_d'] = assign_data($result);

      $this->load->view($return_link,$data);
  }

  //--

  function get_putaway_list_d2(){
      $doc_no = $_POST['id'];
      $line_no = $_POST['line_no'];
      $src_line_no = $_POST['src_line_no'];
      $src_no = $_POST['src_no'];
      $return_link = $_POST['link'];

      $this->load->model('model_tsc_putaway_d2','',TRUE);

      $this->model_tsc_putaway_d2->doc_no = $doc_no;
      $this->model_tsc_putaway_d2->src_line_no = $src_line_no;
      $result = $this->model_tsc_putaway_d2->get_list_data();
      $data['var_putaway_d2'] = assign_data($result);

      $this->load->view($return_link,$data);
  }

  //--

  function get_putaway_list_d3(){
      $doc_no = $_POST['id'];
      $line_no = $_POST['line_no'];
      $src_line_no = $_POST['src_line_no'];
      $src_no = $_POST['src_no'];
      $return_link = $_POST['link'];

      $this->load->model('model_tsc_putaway_d3','',TRUE);

      $this->model_tsc_putaway_d3->doc_no = $doc_no;
      $this->model_tsc_putaway_d3->src_line_no_d2 = $line_no;
      $result = $this->model_tsc_putaway_d3->get_list_data();
      $data['var_putaway_d3'] = assign_data($result);

      $this->load->view($return_link,$data);
  }

  //--

  function goto(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'putaway/goto'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - GoTo PutAway"); // insert log

          $this->load->view('wms/inbound/putawaygoto/v_putaway_goto');
      }
  }
  //---

  function get_putaway_goto_list(){
      $this->load->model('model_tsc_putaway_h','',TRUE);

      $status = ["6"];
      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];
      $result = $this->model_tsc_putaway_h->list_by_status_and_user($status,$user);

      if(count($result) == 0) $data['var_putaway'];
      else $data['var_putaway'] = assign_data($result);

      $this->load->view('wms/inbound/v_putaway_goto_list',$data);
  }
  //----

  function goto_process(){
      $doc_no = $_GET['docno'];

      $this->load->model('model_tsc_putaway_d2','',TRUE);
      $this->load->model('model_tsc_putaway_h','',TRUE);

      $this->model_zlog->insert("Warehouse - GoTo Process PutAway"); // insert log

      // check if status already done
      $this->model_tsc_putaway_h->doc_no = $doc_no;
      $doc_status = $this->model_tsc_putaway_h->get_doc_status();
      if($doc_status != 6){
          $this->goto();
      }
      else{
          $this->load->view('templates/navigation');
          $this->model_tsc_putaway_d2->doc_no = $doc_no;
          $result = $this->model_tsc_putaway_d2->get_list_data_by_doc_no();
          $data['var_putaway_goto_d2'] = assign_data($result);
          $data['doc_no'] = $doc_no ;

          $this->load->view('wms/inbound/v_putaway_goto_process',$data);
      }

  }
  //----

  function goto_finish(){
      $d2_line_no = json_decode(stripslashes($_POST['d2_line_no']));
      $d2_src_line_no = json_decode(stripslashes($_POST['d2_src_line_no']));
      $d2_src_no = json_decode(stripslashes($_POST['d2_src_no']));
      $d2_item_code = json_decode(stripslashes($_POST['d2_item_code']));
      $d2_start_time = json_decode(stripslashes($_POST['d2_start_time']));
      $d2_finish_time = json_decode(stripslashes($_POST['d2_finish_time']));
      $h_doc_no = $_POST['h_doc_no'];
      $start_all_datetime = $_POST['start_all_datetime'];
      $finish_all_datetime = $_POST['finish_all_datetime'];

      for($i=0;$i<count($d2_line_no);$i++){
          // update d3
          $this->update_start_finish_time_d3($d2_start_time[$i], $d2_finish_time[$i], $h_doc_no, $d2_src_no[$i], $d2_line_no[$i], $d2_src_line_no[$i]);

          // update d2
          $this->update_start_finish_time_d2($d2_start_time[$i], $d2_finish_time[$i], $h_doc_no, $d2_line_no[$i], $d2_src_line_no[$i]);
      }

      $this->update_start_finish_time_d($d2_src_line_no, $h_doc_no);   // update d
      $result_h = $this->update_start_finish_time_h($start_all_datetime, $finish_all_datetime, $h_doc_no); // update h
      $this->update_put_away_h_status('7',$h_doc_no); // update status h
      $this->update_item_sc_location($d2_line_no, $h_doc_no, $d2_src_no); // update item_sc
      $this->refresh_status_putaway_in_bound(); // refresh status putaway

      if($result_h){
          $response['status'] = "1";
          $response['msg'] = "The Put Away has finished";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //--

  function update_start_finish_time_d3($put, $complete, $doc_no, $src_no, $src_line_no_d2, $src_line_no){
      $this->load->model('model_tsc_putaway_d3','',TRUE);
      $this->model_tsc_putaway_d3->put_datetime = $put;
      $this->model_tsc_putaway_d3->completely_put = $complete;
      $this->model_tsc_putaway_d3->doc_no = $doc_no;
      $this->model_tsc_putaway_d3->src_no = $src_no;
      $this->model_tsc_putaway_d3->src_line_no_d2 = $src_line_no_d2;
      $this->model_tsc_putaway_d3->src_line_no = $src_line_no;
      $result = $this->model_tsc_putaway_d3->update_start_finish_time();

      return $result;
  }
  //--

  function update_start_finish_time_d2($put, $complete, $doc_no, $line_no, $src_line_no){
      $this->load->model('model_tsc_putaway_d2','',TRUE);
      $this->model_tsc_putaway_d2->startput_datetime = $put;
      $this->model_tsc_putaway_d2->completely_put = $complete;
      $this->model_tsc_putaway_d2->doc_no = $doc_no;
      $this->model_tsc_putaway_d2->line_no = $line_no;
      $this->model_tsc_putaway_d2->src_line_no = $src_line_no;
      $result = $this->model_tsc_putaway_d2->update_start_finish_time();

      return $result;
  }
  //---

  function update_start_finish_time_h($start_all_datetime, $finish_all_datetime, $doc_no){
      $this->load->model('model_tsc_putaway_h','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);

      $this->model_tsc_putaway_h->start_datetime = $start_all_datetime;
      $this->model_tsc_putaway_h->all_finished_datetime = $finish_all_datetime;
      $this->model_tsc_putaway_h->doc_no = $doc_no;
      $result = $this->model_tsc_putaway_h->update_start_finish_time();

      // insert doc history
      $this->model_tsc_doc_history->insert($doc_no,$doc_no,"","7","",$finish_all_datetime,"Finished Put Away","");
      //--

      return $result;
  }
  //---

  function update_start_finish_time_d($d2_src_line_no, $doc_no){
      $this->load->model('model_tsc_putaway_d2','',TRUE);
      $this->load->model('model_tsc_putaway_d','',TRUE);

      for($i=0;$i<count($d2_src_line_no);$i++){
          $this->model_tsc_putaway_d2->doc_no = $doc_no;
          $this->model_tsc_putaway_d2->src_line_no = $d2_src_line_no[$i];
          $result = $this->model_tsc_putaway_d2->get_start_finish_by_docno_srcline();

          // update to d
          $this->model_tsc_putaway_d->doc_no = $doc_no;
          $this->model_tsc_putaway_d->line_no = $d2_src_line_no[$i];
          $this->model_tsc_putaway_d->put_datetime= $result['starttime'];
          $this->model_tsc_putaway_d->completely_put = $result['finishtime'];
          $this->model_tsc_putaway_d->update_start_finish_time();
      }
  }
  //---

  function update_put_away_h_status($status, $doc_no){
      $this->load->model('model_tsc_putaway_h','',TRUE);
      $this->model_tsc_putaway_h->statuss = $status;
      $this->model_tsc_putaway_h->doc_no = $doc_no;
      $result = $this->model_tsc_putaway_h->update_status();
      return $result;
  }
  //---

  function update_item_sc_location($d2_line_no, $h_doc_no){
      $this->load->model('model_tsc_putaway_d3','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);

      for($i=0;$i<count($d2_line_no);$i++){
          $this->model_tsc_putaway_d3->doc_no = $h_doc_no;
          $this->model_tsc_putaway_d3->src_line_no_d2 = $d2_line_no[$i];
          $result = $this->model_tsc_putaway_d3->get_list_data();
          $data_d3 = assign_data($result);

          $this->model_tsc_item_sn->update_location_v3($data_d3);
      }
  }
  //---

  function refresh_status_putaway_in_bound(){
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);

      $result = $this->model_tsc_in_out_bound_h->get_list_inbound_done();
      $data_inbound_done = assign_data($result);

      if(count($result)>0){
        foreach($data_inbound_done as $row){
            $this->model_tsc_in_out_bound_h->status = '8';
            $this->model_tsc_in_out_bound_h->doc_no = $row['inout_doc_no'];
            $datetime = get_datetime_now();
            $this->model_tsc_in_out_bound_h->putaway_finished = $datetime;
            $this->model_tsc_in_out_bound_h->update_status();
            $this->model_tsc_in_out_bound_h->update_put_away_finished();

            // insert doc history
            $this->model_tsc_doc_history->insert($row['inout_doc_no'],$row['inout_doc_no'],"","8","",$datetime,"Finished Put Away","");
            //--
        }
      }
  }
  //--

  function update_text($doc_no, $text){
      $this->load->model('model_tsc_putaway_h','',TRUE);

      $this->model_tsc_putaway_h->doc_no = $doc_no;
      $this->model_tsc_putaway_h->text = $text;
      $result = $this->model_tsc_putaway_h->update_text();
      return $result;
  }
  //---

  // 2022-11-09
  function change_user_process(){
      $doc_no = $_POST['doc_no'];
      $userid = $_POST['userid'];

      $this->load->model('model_tsc_putaway_h','',TRUE);

      $this->model_tsc_putaway_h->assign_user = $userid;
      $this->model_tsc_putaway_h->doc_no = $doc_no;
      $result = $this->model_tsc_putaway_h->change_assign_user();

      if($result){
          $response["status"] = 1;
          $response["msg"] = "The data has been proceed";
      }
      else{
          $response["status"] = 0;
          $response["msg"] = "Error";
      }

        echo json_encode($response);
  }
  //--

  // 2022-11-09
  function get_change_user(){
      $doc_no = $_POST["id"];

      $this->load->model('model_login','',TRUE);

      $depart[] = "DPT006";
      $result = $this->model_login->get_user_list_by_department($depart);
      $data["var_user"] = assign_data($result);
      $data["doc_no"] = $doc_no;

      $this->load->view('wms/inbound/v_putaway_change_user', $data);
  }
  //---

  // 2022-11-15 master barcode
  function new2(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'putaway'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - New PutAway"); // insert log

          $this->load->model('model_login','',TRUE);
          $this->load->model('model_config','',TRUE);
          $this->load->model('model_mst_location','',TRUE);

          $this->model_config->name = "putaway_depart";
          $result_config = $this->model_config->get_value_by_setting_name();

          $depart = explode("|",$result_config);
          $result = $this->model_login->get_user_list_by_department($depart);
          $data['user_list'] = assign_data($result);

          // get location
          $result_location = $this->model_mst_location->get_data();
          if(count($result_location) > 0){
              $data["var_location"] = assign_data($result_location);
          }
          else{
              $data["var_location"] = 0;
          }
          //---

          $this->load->view('wms/inbound/putaway/v_putaway_new', $data);
      }
  }
  //--

  // 2022-11-15 master barcode
  function get_bin_master_barcode(){
      $return_link = $_POST['link'];
      $total_qty_outstanding = $_POST['total_qty_outstanding'];
      $total_qty_put = $_POST['total_qty_put'];
      $doc_no = $_POST['doc_no'];
      $line_no = $_POST['line_no'];
      $item_code = $_POST['item_code'];
      $desc = $_POST['desc'];
      $uom = $_POST['uom'];
      $row_doc = $_POST['row_doc'];
      $counter_master_barcode = $_POST['counter_master_barcode'];
      $master_barcode = json_decode(stripslashes($_POST['master_barcode']));
      $h_whs = $_POST["h_whs"];

      $this->load->model('model_mst_bin','',TRUE);
      $this->load->model('model_tsc_received_d2','',TRUE);

      //$result = $this->model_mst_bin->get_data();
      $result = $this->model_mst_bin->get_data_by_location($h_whs);

      $data['var_bin'] = assign_data($result);
      $data['total_qty_outstanding'] = $total_qty_outstanding;
      $data['total_qty_put'] = $total_qty_put;
      $data['doc_no'] = $doc_no;
      $data['line_no'] = $line_no;
      $data['item_code'] = $item_code;
      $data['desc'] = $desc;
      $data['uom'] = $uom;
      $data['row_doc'] = $row_doc;

      // get and calculated qty per master barcode
      $this->model_tsc_received_d2->doc_no = $doc_no;
      $this->model_tsc_received_d2->line_no = $line_no;
      $result = $this->model_tsc_received_d2->get_data_per_master_barcode_exlcude_existing($master_barcode);

      if(count($result) > 0) $data["var_item_per_master_code"] = assign_data($result);
      else $data["var_item_per_master_code"] = 0;

      $this->load->view('wms/inbound/putaway/v_putaway_bin_list',$data);
  }
  //---

  // 2022-11-16 master barcode
  function create_new2(){
      $this->model_zlog->insert("Warehouse - Creating New PutAway..."); // insert log

      $h_doc_received = json_decode(stripslashes($_POST['h_doc_received']));
      $h_line = json_decode(stripslashes($_POST['h_line']));
      $h_loc = json_decode(stripslashes($_POST['h_loc']));
      $h_item_code = json_decode(stripslashes($_POST['h_item_code']));
      $h_desc = json_decode(stripslashes($_POST['h_desc']));
      $h_qty_total = json_decode(stripslashes($_POST['h_qty_total']));
      $h_qty_put = json_decode(stripslashes($_POST['h_qty_put']));
      $h_qty_rem = json_decode(stripslashes($_POST['h_qty_rem']));
      $h_uom = json_decode(stripslashes($_POST['h_uom']));
      $d_doc_no = json_decode(stripslashes($_POST['d_doc_no']));
      $d_line_no = json_decode(stripslashes($_POST['d_line_no']));
      $d_item_code = json_decode(stripslashes($_POST['d_item_code']));
      $d_desc = json_decode(stripslashes($_POST['d_desc']));
      $d_qty = json_decode(stripslashes($_POST['d_qty']));
      $d_uom = json_decode(stripslashes($_POST['d_uom']));
      $d_loc = json_decode(stripslashes($_POST['d_loc']));
      $d_zone = json_decode(stripslashes($_POST['d_zone']));
      $d_area = json_decode(stripslashes($_POST['d_area']));
      $d_rack = json_decode(stripslashes($_POST['d_rack']));
      $d_bin = json_decode(stripslashes($_POST['d_bin']));
      $d_sn2 = json_decode(stripslashes($_POST['d_sn2']));
      $counter_h = $_POST['counter_h'];
      $counter_d = $_POST['counter_d'];
      $h_doc_date = $_POST['h_doc_date'];
      $h_doc_user = $_POST['h_doc_user'];
      $message = $_POST['message'];

      $this->load->model('model_tsc_doc_history','',TRUE);

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $created_user = $session_data['z_tpimx_user_id'];

      $datetime = get_datetime_now();
      $date = get_date_now();

      // create new document
      $no_header_doc = $this->create_header_doc($h_doc_date, $h_doc_user,$h_loc[0], "1", $created_user,"6",$datetime,$datetime,$h_doc_user);

      // insert detail
      $this->create_detail_master_barcode($no_header_doc, $h_doc_received, $h_line, $h_loc, $h_item_code, $h_desc, $h_qty_total, $h_qty_put, $h_qty_rem, $h_uom, $datetime, $d_doc_no, $d_line_no, $d_item_code, $d_qty, $d_uom, $d_loc, $d_zone, $d_area, $d_rack, $d_bin, $d_desc, $d_sn2);

      // update complete put away at received
      $this->update_received_qty_outstanding($h_doc_received,$h_line,$h_qty_rem,$h_qty_put, $h_qty_total);

      //update text
      $this->update_text($no_header_doc,$message);

      // insert doc history
      foreach($h_doc_received as $row){
          $this->model_tsc_doc_history->insert($no_header_doc,$row,"","6","",$datetime, $message,"");
      }

      //--

      if($no_header_doc){
          $response['status'] = "1";
          $response['msg'] = "New Put Away Document has been created with No = ".$no_header_doc;
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }

  }
  //--

  // 2022-11-16 master barcode
  function create_detail_master_barcode($no_header_doc, $h_doc_received, $h_line, $h_loc, $h_item_code, $h_desc, $h_qty_total, $h_qty_put, $h_qty_rem, $h_uom, $created_datetime, $d_doc_no, $d_line_no, $d_item_code, $d_qty, $d_uom, $d_loc, $d_zone, $d_area, $d_rack, $d_bin, $d_desc, $d_sn2){

      $this->load->model('model_tsc_putaway_d3','',TRUE);
      $this->load->model('model_tsc_putaway_d2','',TRUE);
      $this->load->model('model_tsc_received_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);
      $this->load->model('model_tsc_putaway_d','',TRUE);

      $datetime = get_datetime_now();

      unset($dd_doc_no); unset($dd_line_no); unset($dd_src_location_code); unset($dd_src_no); unset($dd_src_line_no);
      unset($dd_item_code); unset($dd_uom); unset($dd_qty_put); unset($dd_desc); unset($dd_created_datetime);

      unset($put_away_d2_doc_no); unset($put_away_d2_src_no); unset($put_away_d2_line_no);
      unset($put_away_d2_src_line_no); unset($put_away_d2_item_code); unset($put_away_d2_qty); unset($put_away_d2_uom);
      unset($put_away_d2_location_code); unset($put_away_d2_zone_code); unset($put_away_d2_area_code);
      unset($put_away_d2_rack_code); unset($put_away_d2_bin_code); unset($put_away_d2_created_datetime);
      unset($put_away_d2_description); unset($put_away_d2_sn2);

      unset($d3_doc_no_temp); unset($k_temp); unset($d3_item_code_temp);
      unset($d3_uom_temp); unset($d3_loc_temp); unset($d3_zone_temp);
      unset($d3_area_temp); unset($d3_rack_temp); unset($d3_bin_temp); unset($d3_sn2_temp);
      unset($d3_desc_temp); unset($d3_serial_number_temp); unset($d3_src_line_d2); unset($d3_src_line_no);

      unset($data_d33);

      debug("1 = ".date("Y-m-d h:i:s"));

      $k = 1;
      $x = 1;
      for($i=0;$i<count($h_doc_received);$i++){
          $dd_doc_no[]             = $no_header_doc;
          $dd_line_no[]            = $i+1;
          $dd_src_location_code[]  = $h_loc[$i];
          $dd_src_no[]             = $h_doc_received[$i];
          $dd_src_line_no[]        = $h_line[$i];
          $dd_item_code[]          = $h_item_code[$i];
          $dd_uom[]                = $h_uom[$i];
          $dd_qty_to_put[]         = $h_qty_put[$i];
          $dd_desc[]               = $h_desc[$i];
          $dd_created_datetime[]   = $created_datetime;

          for($ii=0;$ii<count($d_doc_no);$ii++){
              if(($d_doc_no[$ii] == $h_doc_received[$i]) && ($d_line_no[$ii] == $h_line[$i])){
                  $put_away_d2_doc_no[] = $no_header_doc;
                  $put_away_d2_src_no[] = $d_doc_no[$ii];
                  $put_away_d2_line_no[] = $x;
                  $put_away_d2_src_line_no[] = $i+1;
                  $put_away_d2_item_code[] = $d_item_code[$ii];
                  $put_away_d2_qty[] = $d_qty[$ii];
                  $put_away_d2_uom[] = $d_uom[$ii];
                  $put_away_d2_location_code[] = $d_loc[$ii];
                  $put_away_d2_zone_code[] = $d_zone[$ii];
                  $put_away_d2_area_code[] = $d_area[$ii];
                  $put_away_d2_rack_code[] = $d_rack[$ii];
                  $put_away_d2_bin_code[] = $d_bin[$ii];
                  $put_away_d2_created_datetime[] = $created_datetime;
                  $put_away_d2_description[] = $d_desc[$ii];
                  $put_away_d2_sn2[] = $d_sn2[$ii];

                  // insert detail3
                  $this->model_tsc_received_d2->doc_no = $d_doc_no[$ii];
                  $this->model_tsc_received_d2->statuss = "0";
                  $this->model_tsc_received_d2->line_no = $h_line[$i];
                  //$result_d3 = $this->model_tsc_received_d2->get_data_by_doc_no_line_no_statuss_order_by_datetime_limit($d_qty[$i]);
                  $result_d3 = $this->model_tsc_received_d2->get_data_sn_by_sn2($d_doc_no[$ii], $h_line[$i], $d_sn2[$ii]);
                  $data_d3 = assign_data($result_d3);
                  foreach($data_d3 as $row){
                      $d3_doc_no_temp[]     = $d_doc_no[$ii];
                      $k_temp[]             = $k;
                      $d3_item_code_temp[]  = $d_item_code[$ii];
                      $d3_uom_temp[]        = $d_uom[$ii];
                      $d3_loc_temp[]        = $d_loc[$ii];
                      $d3_zone_temp[]       = $d_zone[$ii];
                      $d3_area_temp[]       = $d_area[$ii];
                      $d3_rack_temp[]       = $d_rack[$ii];
                      $d3_bin_temp[]        = $d_bin[$ii];
                      //$d3_sn2_temp[]        = $d_sn2[$ii];
                      $d3_desc_temp[]       = $d_desc[$ii];
                      $d3_serial_number_temp[] = $row['serial_number'];
                      $d3_sn2_temp[]        = $row["sn2"];
                      $d3_src_line_d2[]     = $x;
                      $d3_src_line_no[]     = $i+1;

                      $k++;

                      $data_d33[] = $row;
                  }
                  //----
                  $x++;
              }
          }
      }
      //---

      debug("2 = ".date("Y-m-d h:i:s"));

      // insert d
      $this->model_tsc_putaway_d->insert_v3($dd_doc_no, $dd_line_no, $dd_src_location_code, $dd_src_no, $dd_src_line_no, $dd_item_code, $dd_uom, $dd_qty_to_put, $dd_desc, $dd_created_datetime, count($dd_doc_no));

      debug("3 = ".date("Y-m-d h:i:s"));

      // insert d2
      $this->model_tsc_putaway_d2->insert_v2($put_away_d2_doc_no, $put_away_d2_src_no, $put_away_d2_line_no, $put_away_d2_src_line_no, $put_away_d2_item_code, $put_away_d2_qty, $put_away_d2_uom, $put_away_d2_location_code, $put_away_d2_zone_code, $put_away_d2_area_code, $put_away_d2_rack_code, $put_away_d2_bin_code, $put_away_d2_created_datetime, $put_away_d2_description, $put_away_d2_sn2);

      debug("4 = ".date("Y-m-d h:i:s"));

      // insert d3
      $this->model_tsc_putaway_d3->insert_v3($no_header_doc, $d3_doc_no_temp, $k_temp, $d3_src_line_no, $d3_item_code_temp, 1, $d3_uom_temp, $d3_loc_temp, $d3_zone_temp, $d3_area_temp, $d3_rack_temp, $d3_bin_temp, $d3_serial_number_temp, $datetime, $d3_desc_temp, $d3_src_line_d2, $d3_sn2_temp);

      debug("5 = ".date("Y-m-d h:i:s"));

      // update status in received2
      // update status v2
      $row_insert = 1;
      $insert_by_number_row = 1000;
      unset($serial_number_temp);
      debug("6 = ".date("Y-m-d h:i:s"));
      foreach($data_d33 as $row){
          if($row_insert > $insert_by_number_row){ // insert to database
              $this->model_tsc_received_d2->update_status_v2($serial_number_temp,1);
              $row_insert = 1;
              unset($serial_number_temp);
          }

          $serial_number_temp[] = $row['serial_number'];
          $row_insert++;
      }
      debug("7 = ".date("Y-m-d h:i:s"));
      $this->model_tsc_received_d2->update_status_v2($serial_number_temp,1);
      debug("8 = ".date("Y-m-d h:i:s"));
      //--

      // 2023-09-22
      //unset($serial_number_temp);
      //foreach($data_d33 as $row){ $serial_number_temp[] = $row['serial_number'];}
      //debug("6 = ".date("Y-m-d h:i:s"));
      //$this->model_tsc_received_d2->update_status_v3($serial_number_temp,"1");
      //--
      debug("10 = ".date("Y-m-d h:i:s"));
  }
  //---

  /*function create_detail2_master_barcode($no_header_doc, $d_doc_no, $d_line_no, $d_item_code, $d_qty, $d_uom, $d_loc, $d_zone, $d_area, $d_rack, $d_bin, $created_datetime, $h_doc_received,$src_line_no, $h_line, $d_desc,$k, $x, $d_sn2){

      $this->load->model('model_tsc_putaway_d3','',TRUE);
      $this->load->model('model_tsc_putaway_d2','',TRUE);
      $this->load->model('model_tsc_received_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);

      $datetime = get_datetime_now();

      unset($put_away_d2_doc_no); unset($put_away_d2_src_no); unset($put_away_d2_line_no);
      unset($put_away_d2_src_line_no); unset($put_away_d2_item_code); unset($put_away_d2_qty); unset($put_away_d2_uom);
      unset($put_away_d2_location_code); unset($put_away_d2_zone_code); unset($put_away_d2_area_code);
      unset($put_away_d2_rack_code); unset($put_away_d2_bin_code); unset($put_away_d2_created_datetime);
      unset($put_away_d2_description); unset($put_away_d2_sn2);

      //$j=1;
      for($i=0;$i<count($d_doc_no);$i++){
          if(($d_doc_no[$i] == $h_doc_received) && ($d_line_no[$i]==$h_line)){
              $put_away_d2_doc_no[] = $no_header_doc;
              $put_away_d2_src_no[] = $d_doc_no[$i];
              $put_away_d2_line_no[] = $x;
              $put_away_d2_src_line_no[] = $src_line_no;
              $put_away_d2_item_code[] = $d_item_code[$i];
              $put_away_d2_qty[] = $d_qty[$i];
              $put_away_d2_uom[] = $d_uom[$i];
              $put_away_d2_location_code[] = $d_loc[$i];
              $put_away_d2_zone_code[] = $d_zone[$i];
              $put_away_d2_area_code[] = $d_area[$i];
              $put_away_d2_rack_code[] = $d_rack[$i];
              $put_away_d2_bin_code[] = $d_bin[$i];
              $put_away_d2_created_datetime[] = $created_datetime;
              $put_away_d2_description[] = $d_desc[$i];
              $put_away_d2_sn2[] = $d_sn2[$i];

              // insert detail3
              $this->model_tsc_received_d2->doc_no = $d_doc_no[$i];
              $this->model_tsc_received_d2->statuss = "0";
              $this->model_tsc_received_d2->line_no = $h_line;
              //$result_d3 = $this->model_tsc_received_d2->get_data_by_doc_no_line_no_statuss_order_by_datetime_limit($d_qty[$i]);
              $result_d3 = $this->model_tsc_received_d2->get_data_sn_by_sn2($d_doc_no[$i], $h_line, $d_sn2[$i]);
              $data_d3 = assign_data($result_d3);

              // insert version 2
              unset($d_doc_no_temp); unset($k_temp); unset($d_item_code_temp);
              unset($d_uom_temp); unset($d_loc_temp); unset($d_zone_temp);
              unset($d_area_temp); unset($d_rack_temp); unset($d_bin_temp); unset($d_sn2_temp);
              unset($d_desc_temp); unset($serial_number_temp);

              $insert_by_number_row = 500;
              $row_insert = 1;

              foreach($data_d3 as $row){
                $d_doc_no_temp[] = $d_doc_no[$i];
                $k_temp[] = $k;
                $d_item_code_temp[] = $d_item_code[$i];
                $d_uom_temp[] = $d_uom[$i];
                $d_loc_temp[] = $d_loc[$i];
                $d_zone_temp[] = $d_zone[$i];
                $d_area_temp[] = $d_area[$i];
                $d_rack_temp[] = $d_rack[$i];
                $d_bin_temp[] = $d_bin[$i];
                $d_sn2_temp[] = $d_sn2[$i];
                $d_desc_temp[] = $d_desc[$i];
                $serial_number_temp[] = $row['serial_number'];
                $d_sn2_temp[] = $row["sn2"];

                $k++;
              }

              //$this->model_tsc_putaway_d3->insert_v2($no_header_doc, $d_doc_no_temp, $k_temp, $src_line_no, $d_item_code_temp, 1, $d_uom_temp, $d_loc_temp, $d_zone_temp, $d_area_temp, $d_rack_temp, $d_bin_temp, $serial_number_temp, $datetime, $d_desc_temp, $x, $d_sn2_temp);  // insert last rows
              $this->model_tsc_putaway_d3->insert_v3($no_header_doc, $d_doc_no_temp, $k_temp, $src_line_no, $d_item_code_temp, 1, $d_uom_temp, $d_loc_temp, $d_zone_temp, $d_area_temp, $d_rack_temp, $d_bin_temp, $serial_number_temp, $datetime, $d_desc_temp, $x, $d_sn2_temp);
              //---

              // update status in received2
              // update status v2
              $row_insert = 1;
              unset($serial_number_temp);
              foreach($data_d3 as $row){
                  if($row_insert > $insert_by_number_row){ // insert to database
                      $this->model_tsc_received_d2->update_status_v2($serial_number_temp,1);
                      $row_insert = 1;
                      unset($serial_number_temp);
                  }

                  $serial_number_temp[] = $row['serial_number'];
                  $row_insert++;
              }
              $this->model_tsc_received_d2->update_status_v2($serial_number_temp,1);
              //--

              //$j++;
              $x++;
          }
      }

      // insert put_away_d2
      $this->model_tsc_putaway_d2->insert_v2($put_away_d2_doc_no, $put_away_d2_src_no, $put_away_d2_line_no, $put_away_d2_src_line_no, $put_away_d2_item_code, $put_away_d2_qty, $put_away_d2_uom, $put_away_d2_location_code, $put_away_d2_zone_code, $put_away_d2_area_code, $put_away_d2_rack_code, $put_away_d2_bin_code, $put_away_d2_created_datetime, $put_away_d2_description, $put_away_d2_sn2);

      $temp['x'] = $x; $temp['k']=$k;
      return $temp;
  }
  //---
  */

  // 2022-11-16 master barcode
  function goto_process2(){
      $this->model_zlog->insert("Warehouse - GoTo Process PutAway"); // insert log

      $doc_no = $_GET['docno'];

      $this->load->model('model_tsc_putaway_d2','',TRUE);
      $this->load->model('model_tsc_putaway_h','',TRUE);

      // check if status already done
      $this->model_tsc_putaway_h->doc_no = $doc_no;
      $doc_status = $this->model_tsc_putaway_h->get_doc_status();
      if($doc_status != 6){
          $this->goto();
      }
      else{
          $this->load->view('templates/navigation');
          $this->model_tsc_putaway_d2->doc_no = $doc_no;
          //$result = $this->model_tsc_putaway_d2->get_list_data_by_doc_no();
          $result = $this->model_tsc_putaway_d2->get_list_data_by_location_and_item();
          $data['var_putaway_goto_d2'] = assign_data($result);
          $data['doc_no'] = $doc_no ;

          $this->load->view('wms/inbound/putawaygoto/v_putaway_goto_process',$data);
      }
  }
  //----

  // 2022-11-16 master barcode
  function get_putaway_goto_list2(){
      $this->load->model('model_tsc_putaway_h','',TRUE);

      $status = ["6"];
      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];
      $result = $this->model_tsc_putaway_h->list_by_status_and_user($status,$user);

      if(count($result) == 0) $data['var_putaway'];
      else $data['var_putaway'] = assign_data($result);

      $this->load->view('wms/inbound/putawaygoto/v_putaway_goto_list',$data);
  }
  //----

  // 2022-11-17 master barcode
  function get_putaway_goto_item_detail(){
      $item_code  = $_POST["item"];
      $doc_no     = $_POST["doc_no"];
      $src_no     = $_POST["src_no"];
      $src_line_no= $_POST["src_line_no"];
      $line_no    = $_POST["line_no"];
      $loc    = $_POST["loc"];
      $zone    = $_POST["zone"];
      $area    = $_POST["area"];
      $rack    = $_POST["rack"];
      $bin    = $_POST["bin"];
      $x = $_POST["x"];
      $y = $_POST["y"];

      $this->load->model('model_tsc_putaway_d3','',TRUE);
      $this->load->model('model_tsc_putaway_d2','',TRUE);
      $this->load->model('model_tsc_putaway_d','',TRUE);
      $this->load->model('model_tsc_putaway_h','',TRUE);

      $datetime = get_datetime_now();

      $result = $this->model_tsc_putaway_d3->get_data_by_sn2_sn_group_by($doc_no, $src_no, $src_line_no, $item_code, $loc, $zone, $area, $rack, $bin);
      $data["var_putaway_d3"] = assign_data($result);

      $data["item_code"]  = $item_code;
      $data["doc_no"]     = $doc_no;
      $data["src_no"]     = $src_no;
      $data["src_line_no"]= $src_line_no;
      $data["src_line_no_d2"]= $line_no;
      $data["loc"]= $loc;
      $data["zone"]= $zone;
      $data["area"]= $area;
      $data["rack"]= $rack;
      $data["bin"]= $bin;
      $data["x"] = $x;
      $data["y"] = $y;

      if($this->model_tsc_putaway_d->check_start_time_is_null($doc_no, $src_line_no)){
          $this->model_tsc_putaway_d3->update_put_datetime($doc_no, $src_no, $src_line_no, $item_code, $loc, $zone, $area, $rack, $bin, $datetime); // update start datetime put_away_d3
          $this->model_tsc_putaway_d2->update_startput_datetime($doc_no, $src_line_no, $src_no, $item_code, $loc, $zone, $area, $rack, $bin ,$datetime); // update start datetime put_away_d2
          $this->model_tsc_putaway_d->update_start_time($doc_no, $src_line_no, $datetime); // update start datetime put_away_d
          $this->model_tsc_putaway_h->update_start_time($doc_no,$datetime);
      }

      $this->load->view('wms/inbound/putawaygoto/v_putaway_goto_item_detail',$data);

  }
  //---

  // 2022-11-17 master barcode
  function update_put_item(){
      $item_code  = $_POST["item"];
      $doc_no     = $_POST["doc_no"];
      $src_no     = $_POST["src_no"];
      $src_line_no= $_POST["src_line_no"];
      $loc    = $_POST["loc"];
      $zone    = $_POST["zone"];
      $area    = $_POST["area"];
      $rack    = $_POST["rack"];
      $bin    = $_POST["bin"];

      $this->load->model('model_tsc_putaway_d','',TRUE);
      $this->load->model('model_tsc_putaway_d2','',TRUE);
      $this->load->model('model_tsc_putaway_d3','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);

      $datetime = get_datetime_now();

      // update putaway_d3 complete datetime
      $result = $this->model_tsc_putaway_d3->update_completely_datetime($doc_no, $src_no, $src_line_no, $item_code, $loc, $zone, $area, $rack, $bin, $datetime);

      // update item_sn_location status = 0
      $result_data = $this->model_tsc_putaway_d3->get_sn_by_location_doc($doc_no, $src_no, $src_line_no, $item_code, $loc, $zone, $area, $rack, $bin);
      $this->model_tsc_item_sn->update_location_v3($result_data);

      // update putaway_d2 complete datetime
      $this->model_tsc_putaway_d2->update_completely_datetime($doc_no, $src_line_no, $src_no, $item_code, $loc, $zone, $area, $rack, $bin ,$datetime);

      if($this->model_tsc_putaway_d3->check_if_complete_put_all_not_null($doc_no, $src_line_no, $src_no, $item_code)){
          $this->model_tsc_putaway_d->update_completely_time($doc_no, $src_line_no, $datetime); // update putaway_d complete datetime
      }

      if($result){
          $response['status'] = "1";
          $response['msg'] = "Put Away finished for ".$item_code." = ".combine_location($loc,$zone,$area,$rack,$bin);
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //--

  function goto_finish2(){
      $h_doc_no = $_POST['h_doc_no'];

      $datetime = get_datetime_now();

      $result_h = $this->update_put_away_h_status('7',$h_doc_no); // update status h
      $this->refresh_status_putaway_in_bound(); // refresh status putaway

      if($result_h){
          $response['status'] = "1";
          $response['msg'] = "The Put Away has finished";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //--

  // 2023-06-01
  function check_bin(){
      $location = json_decode(stripslashes($_POST["location"]));
      $zone = json_decode(stripslashes($_POST["zone"]));
      $area = json_decode(stripslashes($_POST["area"]));
      $rack = json_decode(stripslashes($_POST["rack"]));
      $bin = json_decode(stripslashes($_POST["bin"]));

      $this->load->model('model_mst_bin','',TRUE);

      $error = 0;
      $error_line = "";
      for($i=0;$i<count($location);$i++){
          $this->model_mst_bin->location_code = $location[$i];
          $this->model_mst_bin->zone_code     = $zone[$i];
          $this->model_mst_bin->area_code     = $area[$i];
          $this->model_mst_bin->rack_code     = $rack[$i];
          $this->model_mst_bin->code      = $bin[$i];
          $this->model_mst_bin->active        = "1";
          $exist = $this->model_mst_bin->check_bin2();
          if($exist == 0){
              $error = 1;
              $error_line = $i;
              break;
          }
      }

      $response["error"] = $error;

      if($error == 1){
          $response["location"] = $location[$i];
          $response["zone"]     = $zone[$i];
          $response["area"]     = $area[$i];
          $response["rack"]     = $rack[$i];
          $response["bin"]      = $bin[$i];
      }

      echo json_encode($response);
  }
  //--

  // 2023-10-23 master barcode
  function get_bin_master_barcode2(){
      $return_link = $_POST['link'];
      $total_qty_outstanding = $_POST['total_qty_outstanding'];
      $total_qty_put = $_POST['total_qty_put'];
      $doc_no = $_POST['doc_no'];
      $line_no = $_POST['line_no'];
      $item_code = $_POST['item_code'];
      $desc = $_POST['desc'];
      $uom = $_POST['uom'];
      $row_doc = $_POST['row_doc'];
      $counter_master_barcode = $_POST['counter_master_barcode'];
      $master_barcode = json_decode(stripslashes($_POST['master_barcode']));
      $h_whs = $_POST["h_whs"];

      $this->load->model('model_mst_bin','',TRUE);
      $this->load->model('model_tsc_received_d2','',TRUE);

      //$result = $this->model_mst_bin->get_data();
      $result = $this->model_mst_bin->get_data_by_location($h_whs);

      $data['var_bin'] = assign_data($result);
      $data['total_qty_outstanding'] = $total_qty_outstanding;
      $data['total_qty_put'] = $total_qty_put;
      $data['doc_no'] = $doc_no;
      $data['line_no'] = $line_no;
      $data['item_code'] = $item_code;
      $data['desc'] = $desc;
      $data['uom'] = $uom;
      $data['row_doc'] = $row_doc;

      // get and calculated qty per master barcode
      $this->model_tsc_received_d2->doc_no = $doc_no;
      $this->model_tsc_received_d2->line_no = $line_no;
      $result = $this->model_tsc_received_d2->get_data_per_master_barcode_exlcude_existing($master_barcode);

      if(count($result) == 0){
          $response["status"] = 0;
      }
      else{
          $response["status"] = 1;
          foreach($result as $row){
              $response["data"][] = array(
                "sn2" => $row["sn2"],
                "qty" => $row["qty"]
              );
          }
      }

      echo json_encode($response);
  }
  //---

}

?>
