<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Packing extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();

      $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'packing'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - Packing"); // insert log

            $this->load->view('wms/outbound/packing/v_index');
        }
    }
    //---

    function get_list(){
        $status_pack = $_POST["status_pack"];

        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $status = array("13","14","16");
        $this->model_tsc_in_out_bound_h->doc_type = "2";
        //$result = $this->model_tsc_in_out_bound_h->list_with_doc_type_one_and_qty($status);
        $result =  $this->model_tsc_in_out_bound_h->list_with_doc_type_one_and_qty_finished_pack($status,$status_pack);
        $data["var_packing"] = assign_data($result);
        $this->load->view('wms/outbound/packing/v_list',$data);
    }
    //---

    function gotopack(){
        $this->model_zlog->insert("Warehouse - GoTo Packing"); // insert log

        $id =  $_GET['id'];
        $whs = $_GET['whs'];

        $data["doc_no"] = $id;
        $data['whs'] = $whs;

        $this->load->view('templates/navigation');
        $this->load->view('wms/outbound/packing/v_gotopack',$data);
    }
    //---

    function gotopack_list(){
        $id =  $_GET['id'];
        $whs = $_GET['whs'];

        $data["doc_no"] = $id;
        $data['whs'] = $whs;

        $this->load->view('templates/navigation');
        $this->load->view('wms/outbound/packing/v_gotopack',$data);
    }
    //---

    function list_outbound(){
        $h_doc_no = $_POST['doc_no'];

        $this->load->model('model_tsc_picking_d','',TRUE);

        $this->model_tsc_picking_d->src_no = $h_doc_no;
        $result = $this->model_tsc_picking_d->get_list_pick_for_pack_by_src_no();
        $data["var_pick_list"] = assign_data($result);

        $data["doc_no"] = $h_doc_no;

        $this->load->view('wms/outbound/packing/v_gotopack_list', $data);
    }
    //---

    function list_pack(){
        $this->load->model('model_login','',TRUE);

        // get user list
        $result = $this->model_login->get_user_list();
        $data['user_list'] = assign_data($result);
        //--

        $this->load->view('wms/outbound/packing/v_gotopack_pack', $data);
    }
    //---

    //-- goto pack manual
    function gotopack_man(){
        $id =  $_GET['id'];
        $whs = $_GET['whs'];

        $data["doc_no"] = $id;
        $data['whs'] = $whs;

        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        $this->model_tsc_in_out_bound_h->doc_no = $id;
        $result_doc_h = $this->model_tsc_in_out_bound_h->get_one_doc_h();
        $data["result_doc_h"] = assign_data_one($result_doc_h);

        $this->load->view('templates/navigation');
        $this->load->view('wms/outbound/packing/v_gotopack_man',$data);
    }
    //---

    function get_gotopack_man_list(){
      $h_doc_no = $_POST['doc_no'];

      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $this->load->model('model_tsc_in_out_bound_d','',TRUE);

      // get outbound detail
      $doc_no[] = $h_doc_no;
      $result_d = $this->model_tsc_in_out_bound_d->get_list_with_so($doc_no);
      $data["var_outbound_d"] = assign_data($result_d);
      //---

      // get dest_no
      $this->model_tsc_in_out_bound_d->doc_no = $h_doc_no;
      $result_dest = $this->model_tsc_in_out_bound_d->get_dest_no_by_doc_no();
      $data["var_dest"] = assign_data($result_dest);
      //---

      $data["doc_no"] = $h_doc_no;

      //---

      $this->load->view('wms/outbound/packing/v_gotopack_man_list', $data);
    }
    //---

    function get_gotopack_man_pack(){
        $this->load->model('model_login','',TRUE);

        // get user list
        $result = $this->model_login->get_user_list();
        $data['user_list'] = assign_data($result);
        //--

        // item pack 2023-02-01
        $this->load->model('model_mst_item_pack','',TRUE);

        $result = $this->model_mst_item_pack->get_data();
        if(count($result) > 0) $data["var_data_item_pack"] = assign_data($result);
        else  $data["var_data_item_pack"] = 0;
        //---

        $this->load->view('wms/outbound/packing/v_gotopack_man_pack', $data);
    }
    //---

    function create_new(){
        $this->model_zlog->insert("Warehouse - Creating New Packing"); // insert log

        $h_doc_no = $_POST['h_doc_no'];
        $h_whs = $_POST['h_whs'];
        $message = $_POST['message'];
        $total_row = $_POST['counter'];
        $dest_no = $_POST['dest_no'];
        $ship_to_name = $_POST['ship_to_name'];
        $ship_to_contact = $_POST['ship_to_contact'];
        $ship_to_addr = $_POST['ship_to_addr'];
        $ship_to_addr2 = $_POST['ship_to_addr2'];
        $ship_to_city = $_POST['ship_to_city'];
        $ship_to_post_code = $_POST['ship_to_post_code'];
        $ship_to_county = $_POST['ship_to_county'];
        $ship_to_ctry_region_code = $_POST['ship_to_ctry_region_code'];
        $pack_item_code = json_decode(stripslashes($_POST['pack_item_code']));
        $pack_desc = json_decode(stripslashes($_POST['pack_desc']));
        $pack_src_line_no = json_decode(stripslashes($_POST['pack_src_line_no']));
        $pack_uom = json_decode(stripslashes($_POST['pack_uom']));
        $pack_qty = json_decode(stripslashes($_POST['pack_qty']));
        $item_pack_code = json_decode(stripslashes($_POST['item_pack_code']));
        $item_pack_qty = json_decode(stripslashes($_POST['item_pack_qty']));

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];

        $this->load->model('model_tsc_doc_history','',TRUE);

        $datetime = get_datetime_now();
        $date = get_date_now();

        $this->db->trans_begin();
        $doc_no = $this->create_header_doc($created_user,$date,$datetime,$message,$h_whs,"1", $h_doc_no); // create header
        $this->create_detail($doc_no,$total_row,$pack_item_code, $pack_desc, $pack_qty ,$pack_src_line_no,$pack_uom,$h_whs, $h_doc_no, $datetime, $date); // created detail

        // update ship to
        $this->update_dest($dest_no, $ship_to_name, $ship_to_contact, $ship_to_addr, $ship_to_addr2, $ship_to_city, $ship_to_post_code, $ship_to_county, $ship_to_ctry_region_code, $doc_no);

        $this->db->trans_complete();

        // insert doc history
        $this->model_tsc_doc_history->insert($doc_no,$h_doc_no,"","13","",$datetime,$message,"");
        //--

        // check if finished packing & month end submitted.. update to submit to navision
        $this->check_if_finished_packing_and_month_end_and_submitted($h_doc_no);
        //--

        // INSERT ITEM PACK
        if($item_pack_code[0] != ""){
          $this->insert_item_packing($item_pack_code,$item_pack_qty,$doc_no);
        }
        //--

        if($doc_no){
            $response['status'] = "1";
            $response['msg'] = "New Packing Document has been created with No = ".$doc_no;
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //---

    function create_header_doc($created_user,$date,$datetime,$message,$whs,$status, $h_doc_no){
        $this->load->model('model_tsc_packing_h','',TRUE);
        $this->load->model('model_config','',TRUE);

        // get prefix from config
        $this->model_config->name = "pref_packing";
        $prefix = $this->model_config->get_value_by_setting_name();
        //--

        $this->model_tsc_packing_h->prefix_code = $prefix;
        $this->model_tsc_packing_h->created_datetime = $datetime;
        $this->model_tsc_packing_h->doc_datetime = $datetime;
        $this->model_tsc_packing_h->doc_type = "1";
        $this->model_tsc_packing_h->src_location_code = $whs;
        $this->model_tsc_packing_h->created_user = $created_user;
        $this->model_tsc_packing_h->external_document =  "";
        $this->model_tsc_packing_h->statuss =  $status;
        $this->model_tsc_packing_h->doc_date =  $date;
        $this->model_tsc_packing_h->text1 = $message;
        $this->model_tsc_packing_h->src_no = $h_doc_no;
        $result = $this->model_tsc_packing_h->call_store_procedure_newpacking();

        return $result;
    }
    //---

    function create_detail($doc_no,$total_row,$pack_item_code, $pack_desc, $pack_qty ,$pack_src_line_no,$pack_uom, $whs, $src_no, $datetime, $date){
        $this->load->model('model_tsc_packing_d','',TRUE);
        $this->load->model('model_tsc_in_out_bound_d','',TRUE);
        $this->load->model('model_tsc_item_invt','',TRUE);

        for($i=0;$i<$total_row;$i++){
            $line = $i+1;
            $this->model_tsc_packing_d->doc_no = $doc_no;
            $this->model_tsc_packing_d->line_no = $line;
            $this->model_tsc_packing_d->src_location_code = $whs;
            $this->model_tsc_packing_d->src_no = $src_no;
            $this->model_tsc_packing_d->src_line_no = $pack_src_line_no[$i];
            $this->model_tsc_packing_d->item_code = $pack_item_code[$i];
            $this->model_tsc_packing_d->uom = $pack_uom[$i];
            $this->model_tsc_packing_d->qty_to_packed = $pack_qty[$i];
            $this->model_tsc_packing_d->desc = $pack_desc[$i];
            $this->model_tsc_packing_d->created_datetime = $datetime;
            $this->model_tsc_packing_d->insert();

            // insert pick d2
            $this->insert_detail2($doc_no, $line, $pack_item_code[$i], $pack_desc[$i],$pack_uom[$i], $datetime, $pack_qty[$i], $src_no, $pack_src_line_no[$i]);
            //---

            // update outbound_d
            $this->model_tsc_in_out_bound_d->qty_to_packed = $pack_qty[$i];
            $this->model_tsc_in_out_bound_d->doc_no = $src_no;
            $this->model_tsc_in_out_bound_d->line_no = $pack_src_line_no[$i];
            $this->model_tsc_in_out_bound_d->update_qtytopacked_and_outstanding_packed();
            //---

            // update inventory
            $this->model_tsc_item_invt->item_code = $pack_item_code[$i];
            $this->model_tsc_item_invt->available = 0;
            $this->model_tsc_item_invt->picking = 0;
            $this->model_tsc_item_invt->picked = 0;
            $this->model_tsc_item_invt->packing = $pack_qty[$i]*-1;
            $this->model_tsc_item_invt->update_invt();
            //---
        }
    }
    //---

    function insert_detail2($src_no, $src_line_no, $item_code, $desc,$uom,$created_datetime, $qty, $whship, $wship_line_no){
        $this->load->model('model_tsc_picking_d2','',TRUE);
        $this->load->model('model_tsc_packing_d2','',TRUE);
        $this->load->model('model_tsc_item_sn','',TRUE);

        // get serial_number from picking_list
        $result_sn = $this->model_tsc_picking_d2->get_pickig_with_packed_null($whship,$wship_line_no,$qty);
        //---

        // insert d2
        if(count($result_sn) > 0){
            $this->model_tsc_packing_d2->insert_v2($src_no, $src_line_no, $item_code,1,$uom, $desc, $created_datetime, $result_sn);
            $this->model_tsc_picking_d2->update_packed_by_serial_number_v2($result_sn,1,$created_datetime);
        }

        //--
    }
    //---

    function update_dest($dest_no, $ship_to_name, $ship_to_contact, $ship_to_addr, $ship_to_addr2, $ship_to_city, $ship_to_post_code, $ship_to_county, $ship_to_ctry_region_code, $doc_no){
        $this->load->model('model_tsc_packing_h','',TRUE);

        $this->model_tsc_packing_h->dest_no = $dest_no;
        $this->model_tsc_packing_h->dest_addr = mysql_escape_mimic($ship_to_addr);
        $this->model_tsc_packing_h->dest_addr2 = mysql_escape_mimic($ship_to_addr2);
        $this->model_tsc_packing_h->dest_name = mysql_escape_mimic($ship_to_name);
        $this->model_tsc_packing_h->dest_contact = mysql_escape_mimic($ship_to_contact);
        $this->model_tsc_packing_h->dest_county = $ship_to_county;
        $this->model_tsc_packing_h->dest_post_code = $ship_to_post_code;
        $this->model_tsc_packing_h->dest_country = $ship_to_ctry_region_code;
        $this->model_tsc_packing_h->dest_city = $ship_to_city;
        $this->model_tsc_packing_h->doc_no = $doc_no;
        $this->model_tsc_packing_h->update_dest();
    }
    //---

    function get_gotopack_man_list_packed(){
        $h_doc_no = $_POST['doc_no'];

        $this->load->model('model_tsc_packing_d','',TRUE);

        // get data
        $this->model_tsc_packing_d->src_no = $h_doc_no;
        $result = $this->model_tsc_packing_d->get_list_pack();
        $data['var_list_packed'] = assign_data($result);
        //--

        $data["doc_no_print_all"] = $h_doc_no;

        $this->load->view('wms/outbound/packing/v_gotopack_man_list_packed', $data);
    }
    //---

    function print(){
        $doc_no = $_GET['id'];
        $so_no = $_GET['so'];

        $this->load->model('model_tsc_packing_h','',TRUE);
        $this->load->model('model_tsc_packing_d','',TRUE);
        $this->load->model('model_config','',TRUE);
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_tsc_so','',TRUE);

        // get info header
        $this->model_tsc_packing_h->doc_no = $doc_no;
        $result = $this->model_tsc_packing_h->get_info_by_doc_no();
        $data["var_packing_h"] = assign_data_one($result);
        //---

        // get info detail
        //$this->model_tsc_packing_d->doc_no = $doc_no;
        //$result = $this->model_tsc_packing_d->get_info_by_doc_no();
        //$data["var_packing_d"] = assign_data($result);
        //---

        // get address tpimx
        $this->model_config->name='tpimx_addr';
        $data["tpimx_addr"] = $this->model_config->get_value_by_setting_name();
        //--

        // get whsdhip header information
        $this->model_tsc_in_out_bound_h->doc_no = $data["var_packing_h"]["src_no"];
        $result_doc_h = $this->model_tsc_in_out_bound_h->get_one_doc_h();
        $data["result_doc_h"] = assign_data_one($result_doc_h);
        //---

        // get so information
        $this->model_tsc_so->so_no = $so_no;
        $result_so = $this->model_tsc_so->get_data_by_doc_no();
        $data["result_so"] = assign_data_one($result_so);
        //--

        $data["so_no"] = $so_no;

        $this->load->view('wms/outbound/packing/v_print', $data);
    }
    //---

    function printlist(){
        $doc_no = $_GET['id'];
        $box = $_GET['box'];
        $row = $_GET['row'];
        $so_no = $_GET['so'];

        $this->load->model('model_tsc_packing_h','',TRUE);
        $this->load->model('model_tsc_packing_d','',TRUE);
        $this->load->model('model_config','',TRUE);

        // get info header
        $this->model_tsc_packing_h->doc_no = $doc_no;
        $result = $this->model_tsc_packing_h->get_info_by_doc_no();
        $data["var_packing_h"] = assign_data_one($result);
        //---

        // get info detail
        $this->model_tsc_packing_d->doc_no = $doc_no;
        $result = $this->model_tsc_packing_d->get_info_by_doc_no();
        $data["var_packing_d"] = assign_data($result);
        //---

        // get address tpimx
        //$this->model_config->name='tpimx_addr';
        //$data["tpimx_addr"] = $this->model_config->get_value_by_setting_name();
        //--

        $data["box"] = $box;
        $data["total_row"] = $row;
        $data["so_no"] = $so_no;

        $this->load->view('wms/outbound/packing/v_print_list', $data);
    }
    //---

    function check_if_finished_packing_and_month_end_and_submitted($h_doc_no){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_tsc_in_out_bound_d','',TRUE);

        // get outbound detail
        $doc_no[] = $h_doc_no;
        $result_d = $this->model_tsc_in_out_bound_d->get_list_with_so($doc_no);
        //---

        // check if finished packing
        $check = 1;
        foreach($result_d as $row){
          if($row['qty_to_ship']-$row['qty_to_packed'] > 0){
              $check = 0;
              break;
          }
        }
        //---

        // check if finished packing & month end submitted.. then update the status to submit to navision
        if($check == 1){
            $this->model_tsc_in_out_bound_h->doc_no = $h_doc_no;
            $result = $this->model_tsc_in_out_bound_h->check_month_end_and_submitted();

            if($result){
                $datetime = get_datetime_now();

                // update status in out bound h
                $this->model_tsc_in_out_bound_h->status = '14';
                $this->model_tsc_in_out_bound_h->doc_no = $h_doc_no;
                $this->model_tsc_in_out_bound_h->update_status();
                //---

                $this->model_tsc_in_out_bound_h->update_pack_finished($datetime,$h_doc_no);

                // insert doc history
                $message = "Finished Packing";
                $this->model_tsc_doc_history->insert($h_doc_no,$h_doc_no,"","14","",$datetime,$message,"");
                //--
            }
        }
        //---
    }
    //---

    function printlistall(){
        $doc_no = $_GET['id'];

        $this->load->model('model_tsc_packing_d','',TRUE);

        // get data
        $this->model_tsc_packing_d->src_no = $doc_no;
        $result = $this->model_tsc_packing_d->get_list_pack();
        $data['var_packing_h'] = assign_data($result);
        //--

        // get info detail
        unset($doc_no_bulk);

        foreach($data['var_packing_h'] as $row){
            $doc_no_bulk[] = $row["doc_no"];
        }
        $this->model_tsc_packing_d->doc_no = $doc_no;
        $result = $this->model_tsc_packing_d->get_info_by_multiple_doc_no($doc_no_bulk);
        $data["var_packing_d"] = assign_data($result);
        //---

        $data["doc_no"] = $doc_no;

        $this->load->view('wms/outbound/packing/v_print_list_all', $data);
    }
    //---

    function check_submit_navision(){
        $doc_no = $_POST['docno'];

        $this->load->model('model_tsc_doc_history','',TRUE);

        $this->model_tsc_doc_history->doc1 = $doc_no;
        $result =  $this->model_tsc_doc_history->get_submit_to_nav();

        if(count($result) > 0) echo json_encode("1");
        else echo json_encode("0");
    }
    //---

    function get_item_pack(){
        $this->load->model('model_mst_item_pack','',TRUE);

        $result = $this->model_mst_item_pack->get_data();
        if(count($result) > 0) $data["var_data"] = assign_data($result);
        else  $data["var_data"] = 0;

        $this->load->view('wms/outbound/packing/v_item_pack', $data);
    }
    //---

    function check_item_pack_stock(){
        $item_pack_code = json_decode(stripslashes($_POST['item_pack_code']));
        $item_pack_qty = json_decode(stripslashes($_POST['item_pack_qty']));

        $this->load->model('model_tsc_item_sn','',TRUE);

        unset($response["nostock"]);
        $count_no_stock = 0;
        for($i=0;$i<count($item_pack_code);$i++){
            $result_sn = $this->model_tsc_item_sn->get_sn_with_limit_without_location($item_pack_code[$i], "1", $item_pack_qty[$i]);
            if(count($result_sn) == 0){
                $response["nostock"][] = $item_pack_code[$i];
                $count_no_stock++;
            }
        }

        if($count_no_stock == 0 ) $response["nostock"] = 0;

        echo json_encode($response);
    }
    //---

    function insert_item_packing($item_pack_code,$item_pack_qty,$doc_no){
          $this->load->model('model_tsc_item_sn','',TRUE);
          $this->load->model('model_tsc_packing_item','',TRUE);

          for($i=0; $i<count($item_pack_code); $i++){
              $result_sn = $this->model_tsc_item_sn->get_sn_with_limit_without_location($item_pack_code[$i], "1", $item_pack_qty[$i]);
              if(count($result_sn) > 0){
                unset($data); unset($sn);

                $line_no = 1;
                $valuee_per_pcs = 0; $count_valuee_per_pcs = 0;
                foreach($result_sn as $row){
                    $valuee_per_pcs += $row["valuee"];
                    $count_valuee_per_pcs++;
                    $sn[] = $row["serial_number"];
                }

                $valuee_per_pcs = round($valuee_per_pcs / $count_valuee_per_pcs,2);

                $data[] = array(
                    "pack_doc_no" => $doc_no,
                    "item_code"   => $item_pack_code[$i],
                    "line_no"     => $line_no,
                    "qty"         => $item_pack_qty[$i],
                    "valuee_per_pcs" => $valuee_per_pcs,
                    "valuee"      => round($valuee_per_pcs * $item_pack_qty[$i],2),
                );
                $line_no++;

                $this->model_tsc_packing_item->insert($data);
                $this->model_tsc_item_sn->update_status_v3($sn, "3");
              }
          }
    }
    //--

    function print2(){
        $doc_no = $_GET['id'];
        $so_no = $_GET['so'];

        $this->load->model('model_tsc_packing_h','',TRUE);
        $this->load->model('model_tsc_packing_d','',TRUE);
        $this->load->model('model_config','',TRUE);
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_tsc_so','',TRUE);

        // get info header
        $this->model_tsc_packing_h->doc_no = $doc_no;
        $result = $this->model_tsc_packing_h->get_info_by_doc_no();
        $data["var_packing_h"] = assign_data_one($result);
        //---

        // get info detail
        //$this->model_tsc_packing_d->doc_no = $doc_no;
        //$result = $this->model_tsc_packing_d->get_info_by_doc_no();
        //$data["var_packing_d"] = assign_data($result);
        //---

        // get address tpimx
        $this->model_config->name='tpimx_addr';
        $data["tpimx_addr"] = $this->model_config->get_value_by_setting_name();
        //--

        // get whsdhip header information
        $this->model_tsc_in_out_bound_h->doc_no = $data["var_packing_h"]["src_no"];
        $result_doc_h = $this->model_tsc_in_out_bound_h->get_one_doc_h();
        $data["result_doc_h"] = assign_data_one($result_doc_h);
        //---

        // get so information
        $this->model_tsc_so->so_no = $so_no;
        $result_so = $this->model_tsc_so->get_data_by_doc_no();
        $data["result_so"] = assign_data_one($result_so);
        //--

        $data["so_no"] = $so_no;

        $this->load->view('wms/outbound/packing/v_print2', $data);
    }
    //---

	function printlist2(){
        $doc_no = $_GET['id'];
        $box = $_GET['box'];
        $row = $_GET['row'];
        $so_no = $_GET['so'];

        $this->load->model('model_tsc_packing_h','',TRUE);
        $this->load->model('model_tsc_packing_d','',TRUE);
        $this->load->model('model_config','',TRUE);

        // get info header
        $this->model_tsc_packing_h->doc_no = $doc_no;
        $result = $this->model_tsc_packing_h->get_info_by_doc_no();
        $data["var_packing_h"] = assign_data_one($result);
        //---

        // get info detail
        $this->model_tsc_packing_d->doc_no = $doc_no;
        $result = $this->model_tsc_packing_d->get_info_by_doc_no();
        $data["var_packing_d"] = assign_data($result);
        //---

        // get address tpimx
        //$this->model_config->name='tpimx_addr';
        //$data["tpimx_addr"] = $this->model_config->get_value_by_setting_name();
        //--

        $data["box"] = $box;
        $data["total_row"] = $row;
        $data["so_no"] = $so_no;

        $this->load->view('wms/outbound/packing/v_print_list2', $data);
    }
    //---
}
?>
