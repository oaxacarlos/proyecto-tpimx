<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Picking extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();

      $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'picking'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - Picking"); // insert log

            $this->load->view('wms/outbound/picking/v_index');
        }
    }
    //---

    function new(){
        $this->model_zlog->insert("Warehouse - New Picking"); // insert log

        $id = $_GET['id'];
        $doc_date = $_GET['docdate'];
        $whs = $_GET['whs'];
        $srclink = $_GET['srclink'];

        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'picking'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->model('model_tsc_in_out_bound_h','',TRUE);

            // check if locked or not
            $this->model_tsc_in_out_bound_h->doc_no = $id;
            $status = $this->model_tsc_in_out_bound_h->get_status();

            //if($status=='99'){
            //    $data["locked"] = 1;
            //    $this->load->view('wms/outbound/whship/warehouse', $data);
            //}
            //else{
                $data["doc_no"] = $id;
                $data["doc_date"] = $doc_date;
                $data['whs'] = $whs;
                $data['srclink'] = $srclink;

                // locked doc no
                //$this->model_tsc_in_out_bound_h->doc_no = $id;
                //$this->model_tsc_in_out_bound_h->status = "99";
                //$status = $this->model_tsc_in_out_bound_h->update_status();
                //---

                $this->load->view('wms/outbound/picking/v_new', $data);
            //}
        }
    }
    //---

    function list_outbound(){
        $h_doc_no = $_POST['doc_no'];
        $whs = $_POST["whs"]; // WH3

        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_tsc_in_out_bound_d','',TRUE);

        // get outbound header
        $result_h = $this->model_tsc_in_out_bound_h->get_one_doc("2","1");
        $data["var_outbound_h"] = assign_data_one($result_h);
        //---

        // get outbound detail
        $doc_no[] = $h_doc_no;
        //$result_d = $this->model_tsc_in_out_bound_d->get_list_v2($doc_no);
        $result_d = $this->model_tsc_in_out_bound_d->get_list_v4($doc_no, $whs);
        $data["var_outbound_d"] = assign_data($result_d);
        //---

        $data["doc_no"] = $h_doc_no;

        $this->load->view('wms/outbound/picking/v_new_list', $data);
    }
    //---

    function list_pick(){
        $this->load->model('model_login','',TRUE);
        $this->load->model('model_config','',TRUE);
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        $whs = $_POST["whs"];
        $doc_no = $_POST["doc_no"]; // 2023-07-12

        // get user list
        $this->model_config->name = "pick_depart";
        $result_config = $this->model_config->get_value_by_setting_name();

        $depart = explode("|",$result_config);
        //$result = $this->model_login->get_user_list_by_department($depart);
        $result = $this->model_login->get_user_list_by_department_and_whs($depart, $whs);
        $data['user_list'] = assign_data($result);
        //--

        // check and get qc_user 2023-07-12
        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $result = assign_data_one($this->model_tsc_in_out_bound_h->get_one_doc_h());
        $data["qc_user_id"] = $result["qc_user"];
        //---

        // get user qc
        $result = $this->model_login->get_user_list_by_department_and_whs_qc($depart, $whs);
        $data['user_list_qc'] = assign_data($result);
        //---

        $this->load->view('wms/outbound/picking/v_new_pick', $data);
    }
    //---

    function get_pick_item(){
        $item_code = $_POST['id'];
        $qty_rem = $_POST['qty_rem'];
        $desc = $_POST['desc'];
        $line = $_POST['line'];
        $src_line_no = $_POST['src_line_no'];
        $uom = $_POST['uom'];
        $h_whs = $_POST["h_whs"];

        $this->load->model('model_tsc_item_sn','',TRUE);

        $this->model_tsc_item_sn->item_code = $item_code;
        $this->model_tsc_item_sn->status = "1";
        //$result = $this->model_tsc_item_sn->list_item_location_by_item_code_and_status($h_whs);
        $result = $this->model_tsc_item_sn-> list_item_location_by_item_code_and_status_ver_three($h_whs);
        $data['var_item_loc'] = assign_data($result);

        $data['item_code'] = $item_code;
        $data['qty_rem'] = $qty_rem;
        $data['desc'] = $desc;
        $data['line'] = $line;
        $data['src_line_no'] = $src_line_no;
        $data['uom'] = $uom;

        $this->load->view('wms/outbound/picking/v_new_item_loc', $data);
    }
    //---

    function create_new(){
        $this->model_zlog->insert("Warehouse - Creating Picking"); // insert log

        $h_doc_user = $_POST['h_doc_user'];
        $h_doc_no = $_POST['h_doc_no'];
        $h_whs = $_POST['h_whs'];
        $message = $_POST['message'];
        $total_row = $_POST['counter'];
        $pick_item_code = json_decode(stripslashes($_POST['pick_item_code']));
        $pick_desc = json_decode(stripslashes($_POST['pick_desc']));
        $pick_loc_code = json_decode(stripslashes($_POST['pick_loc_code']));
        $pick_zone_code = json_decode(stripslashes($_POST['pick_zone_code']));
        $pick_area_code = json_decode(stripslashes($_POST['pick_area_code']));
        $pick_rack_code = json_decode(stripslashes($_POST['pick_rack_code']));
        $pick_bin_code = json_decode(stripslashes($_POST['pick_bin_code']));
        $pick_src_line_no = json_decode(stripslashes($_POST['pick_src_line_no']));
        $pick_uom = json_decode(stripslashes($_POST['pick_uom']));
        $pick_qty = json_decode(stripslashes($_POST['pick_qty']));
        $h_doc_user_qc = $_POST['h_doc_user_qc']; // 2023-07-12

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];

        $this->load->model('model_tsc_doc_history','',TRUE);
        $this->load->model('model_tsc_notif','',TRUE); // notif 2023-05-19
        $this->load->model('model_tsc_in_out_bound_h','',TRUE); // 2023-07-12

        $datetime = get_datetime_now();
        $date = get_date_now();

        $this->db->trans_begin();
        $doc_no = $this->create_header_doc($created_user,$h_doc_user,$date,$datetime,$message,$h_whs,"1"); // create header

        $this->create_detail_v2($doc_no,$total_row,$pick_item_code, $pick_desc,$pick_loc_code, $pick_zone_code, $pick_area_code, $pick_rack_code, $pick_bin_code, $pick_qty ,$pick_src_line_no,$pick_uom,$h_whs, $h_doc_no, $datetime, $date); // created detail

        // insert doc history
        $this->model_tsc_doc_history->insert($doc_no,$h_doc_no,"","1","",$datetime,$message,"");
        //--

        // notif 2023-05-19
        $notif_msg = "Tienes un document surtir de numero = ".$doc_no;
        $link = base_url()."index.php/wms/outbound/picking/goto";
        $this->model_tsc_notif->insert($datetime, $created_user, "", $h_doc_user, "", $notif_msg, "0", $link);
        //--

        // update user QC 2023-07-12
        $this->model_tsc_in_out_bound_h->update_user_qc($h_doc_no, $h_doc_user_qc);
        //--

        if($doc_no){
            $response['status'] = "1";
            $response['msg'] = "New Picking Document has been created with No = ".$doc_no;
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }

        $this->db->trans_complete();
    }
    //---

    function create_header_doc($created_user,$assign_user,$date,$datetime,$message,$whs,$status){
        $this->load->model('model_tsc_picking_h','',TRUE);
        $this->load->model('model_config','',TRUE);

        // get prefix from config
        $this->model_config->name = "pref_picking";
        $prefix = $this->model_config->get_value_by_setting_name();
        //--

        $this->model_tsc_picking_h->prefix_code = $prefix;
        $this->model_tsc_picking_h->created_datetime = $datetime;
        $this->model_tsc_picking_h->doc_datetime = $datetime;
        $this->model_tsc_picking_h->doc_type = "1";
        $this->model_tsc_picking_h->src_location_code = $whs;
        $this->model_tsc_picking_h->created_user = $created_user;
        $this->model_tsc_picking_h->external_document =  "";
        $this->model_tsc_picking_h->statuss =  $status;
        $this->model_tsc_picking_h->doc_date =  $date;
        $this->model_tsc_picking_h->assign_user = $assign_user;
        $this->model_tsc_picking_h->text1 = $message;
        $result = $this->model_tsc_picking_h->call_store_procedure_newpicking();

        return $result;
    }
    //---

    function create_detail($doc_no,$total_row,$pick_item_code, $pick_desc,$pick_loc_code, $pick_zone_code, $pick_area_code, $pick_rack_code, $pick_bin_code, $pick_qty ,$pick_src_line_no,$pick_uom, $whs, $src_no, $datetime, $date){
        $this->load->model('model_tsc_picking_d','',TRUE);
        $this->load->model('model_tsc_item_invt','',TRUE);
        $this->load->model('model_tsc_in_out_bound_d','',TRUE);

        for($i=0;$i<$total_row;$i++){
            $line = $i+1;
            $this->model_tsc_picking_d->doc_no = $doc_no;
            $this->model_tsc_picking_d->line_no = $line;
            $this->model_tsc_picking_d->src_location_code = $whs;
            $this->model_tsc_picking_d->src_no = $src_no;
            $this->model_tsc_picking_d->src_line_no = $pick_src_line_no[$i];
            $this->model_tsc_picking_d->item_code = $pick_item_code[$i];
            $this->model_tsc_picking_d->uom = $pick_uom[$i];
            $this->model_tsc_picking_d->qty_to_picked = $pick_qty[$i];
            $this->model_tsc_picking_d->desc = $pick_desc[$i];
            $this->model_tsc_picking_d->created_datetime = $datetime;
            $this->model_tsc_picking_d->location_code = $pick_loc_code[$i];
            $this->model_tsc_picking_d->zone_code = $pick_zone_code[$i];
            $this->model_tsc_picking_d->area_code = $pick_area_code[$i];
            $this->model_tsc_picking_d->rack_code = $pick_rack_code[$i];
            $this->model_tsc_picking_d->bin_code = $pick_bin_code[$i];
            $this->model_tsc_picking_d->insert();

            // insert pick d2
            $this->insert_detail2($doc_no, $line, $pick_item_code[$i], $pick_desc[$i],$pick_uom[$i],$pick_loc_code[$i], $pick_zone_code[$i], $pick_area_code[$i], $pick_rack_code[$i], $pick_bin_code[$i], $datetime, $pick_qty[$i]);
            //---

            // update outbound_d
            $this->model_tsc_in_out_bound_d->qty_to_picked = $pick_qty[$i];
            $this->model_tsc_in_out_bound_d->doc_no = $src_no;
            $this->model_tsc_in_out_bound_d->line_no = $pick_src_line_no[$i];
            $this->model_tsc_in_out_bound_d->update_qtytopicked_and_outstanding();
            //---

            // update inventory
            $this->model_tsc_item_invt->item_code = $pick_item_code[$i];
            $this->model_tsc_item_invt->available = $pick_qty[$i] * -1;
            $this->model_tsc_item_invt->picking = $pick_qty[$i];
            $this->model_tsc_item_invt->picked = 0;
            $this->model_tsc_item_invt->packing = 0;
            $this->model_tsc_item_invt->update_invt();
            //---
        }
    }
    //---

    function insert_detail2($src_no, $src_line_no, $item_code, $desc,$uom,$location_code_pick, $zone_code_pick, $area_code_pick, $rack_code_pick, $bin_code_pick, $created_datetime, $qty){
        $this->load->model('model_tsc_picking_d2','',TRUE);
        $this->load->model('model_tsc_item_sn','',TRUE);

        $this->model_tsc_item_sn->status = 1;
        $this->model_tsc_item_sn->item_code = $item_code;
        $this->model_tsc_item_sn->location_code = $location_code_pick;
        $this->model_tsc_item_sn->zone_code = $zone_code_pick;
        $this->model_tsc_item_sn->area_code = $area_code_pick;
        $this->model_tsc_item_sn->rack_code = $rack_code_pick;
        $this->model_tsc_item_sn->bin_code = $bin_code_pick;
        $result_sn = $this->model_tsc_item_sn->get_list_sn_with_status_limit($qty);

        $this->model_tsc_item_sn->update_status_v2($result_sn, '2');

        // insert d2 version 2
        $this->model_tsc_picking_d2->insert_v2($src_no,$src_line_no, $item_code, $uom, $desc, $created_datetime, $location_code_pick, $zone_code_pick, $area_code_pick, $rack_code_pick, $bin_code_pick, $result_sn, 1);
        //--

    }
    //---

    function get_picking(){
        $this->load->model('model_tsc_picking_h','',TRUE);
        $this->load->model('model_login','',TRUE);

        $status = ["1"];
        $result = $this->model_tsc_picking_h->list_by_status($status);
        $data["var_picking_list"] = assign_data($result);

        // 2023-08-08
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $data["user"] = $session_data['z_tpimx_user_id'];
        //---

        // 2023-08-08
        $result = $this->model_login->get_user_color("pick");
        if(count($result) > 0){
            unset($user_color);
            foreach($result as $row){
                $user_color[$row["user_id"]] = $row["color"];
            }
        }
        $data["user_color"] = $user_color;
        //--

        $this->load->view('wms/outbound/picking/v_list', $data);


    }
    //---

    function get_picking_list_d(){
        $doc_no = $_POST['id'];
        $return_link = $_POST['link'];

        $this->load->model('model_tsc_picking_d','',TRUE);

        $this->model_tsc_picking_d->doc_no = $doc_no;
        $result = $this->model_tsc_picking_d->get_list_data();
        $data['var_picking_d'] = assign_data($result);

        $this->load->view($return_link,$data);
    }
    //---

    function get_picking_list_d2(){
        $doc_no = $_POST['id'];
        $line_no = $_POST['line_no'];
        $src_line_no = $_POST['src_line_no'];
        $src_no = $_POST['src_no'];
        $return_link = $_POST['link'];

        $this->load->model('model_tsc_picking_d2','',TRUE);

        $this->model_tsc_picking_d2->doc_no = $doc_no;
        $this->model_tsc_picking_d2->src_line_no = $line_no;
        $result = $this->model_tsc_picking_d2->get_list_data();
        $data['var_picking_d2'] = assign_data($result);

        $this->load->view($return_link,$data);
    }
    //---

    function goto(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'picking/goto'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - GoTo Picking"); // insert log

            $this->load->view('wms/outbound/picking/v_pick_goto');
        }
    }
    //---

    function get_pick_goto_list(){
        $this->load->model('model_tsc_picking_h','',TRUE);
        $this->load->model('model_config','',TRUE);

        $status = ["1"];
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id'];

        // get limit
        $this->model_config->name = "limit_picking";
        $limit = $this->model_config->get_value_by_setting_name();
        //---

        $result = $this->model_tsc_picking_h->list_by_status_and_user_by_limit($status,$user,$limit);

        if(count($result) == 0) $data['var_pick'];
        else $data['var_pick'] = assign_data($result);

        $this->model_config->name = "shipment_timeout_minutes";
        $data["var_timeout_wms"] = $this->model_config->get_value_by_setting_name();

        $this->model_config->name = "shipment_timeout_text";
        $data["var_timeout_text"] = $this->model_config->get_value_by_setting_name();

        $this->load->view('wms/outbound/picking/v_pick_goto_list',$data);
    }
    //----

    function goto_process(){
        $this->model_zlog->insert("Warehouse - GoTo Process Picking"); // insert log

        $doc_no = $_GET['docno'];

        $this->load->model('model_tsc_picking_d','',TRUE);
        $this->load->model('model_tsc_picking_h','',TRUE);
        $this->load->model('model_tsc_picking_d2','',TRUE);

        // check if status already done
        $this->model_tsc_picking_h->doc_no = $doc_no;
        $doc_status = $this->model_tsc_picking_h->get_doc_status();
        if($doc_status != 1){
            $this->goto();
        }
        else{
            $this->load->view('templates/navigation');
            $this->model_tsc_picking_d->doc_no = $doc_no;
            $result = $this->model_tsc_picking_d->get_list_data();
            $data['var_pick_goto_d'] = assign_data($result);

            $this->model_tsc_picking_d2->doc_no = $doc_no;
            $result = $this->model_tsc_picking_d2->get_list_data_with_location_sn2_pcs_conv();
            $data["var_pick_goto_d2"] = assign_data($result);

            $data['doc_no'] = $doc_no ;

            $this->load->view('wms/outbound/picking/v_pick_goto_process',$data);
        }

    }
    //----

    function goto_finish(){
        $d_line_no = json_decode(stripslashes($_POST['d_line_no']));
        $d_src_no = json_decode(stripslashes($_POST['d_src_no']));
        $d_item_code = json_decode(stripslashes($_POST['d_item_code']));
        $d_start_time = json_decode(stripslashes($_POST['d_start_time']));
        $d_finish_time = json_decode(stripslashes($_POST['d_finish_time']));
        $d_qty = json_decode(stripslashes($_POST['d_qty']));
        $h_doc_no = $_POST['h_doc_no'];
        $start_all_datetime = $_POST['start_all_datetime'];
        $finish_all_datetime = $_POST['finish_all_datetime'];

        $this->load->model('model_tsc_item_invt','',TRUE);
        $this->load->model('model_tsc_doc_history','',TRUE);

        for($i=0;$i<count($d_line_no);$i++){
            // update inventory
            $this->model_tsc_item_invt->item_code = $d_item_code[$i];
            $this->model_tsc_item_invt->available = 0;
            $this->model_tsc_item_invt->picking = $d_qty[$i] * -1;
            $this->model_tsc_item_invt->picked = $d_qty[$i];
            $this->model_tsc_item_invt->packing = 0;
            $this->model_tsc_item_invt->update_invt();
            //---

        }

        $result_h = $this->update_start_finish_time_h($start_all_datetime, $finish_all_datetime, $h_doc_no); // update h
        $this->update_pick_h_status('12',$h_doc_no); // update status h
        $this->update_item_sn_picked_datetime($d_line_no, $h_doc_no, $d_start_time); // update item_sc
        //$this->refresh_status_pick_out_bound(); // refresh status picking

        // insert doc history
        $this->model_tsc_doc_history->insert($h_doc_no,$h_doc_no,"","12","",$finish_all_datetime,"Finished Picking","");
        //--

        if($result_h){
            $response['status'] = "1";
            $response['msg'] = "The Picking has finished";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //--

    function update_start_finish_time_d2($start, $complete, $doc_no, $src_line_no){
        $this->load->model('model_tsc_picking_d2','',TRUE);
        $this->model_tsc_picking_d2->pick_datetime = $start;
        $this->model_tsc_picking_d2->completely_picked = $complete;
        $this->model_tsc_picking_d2->src_no = $doc_no;
        $this->model_tsc_picking_d2->src_line_no = $src_line_no;
        $result = $this->model_tsc_picking_d2->update_start_finish_time();

        return $result;
    }
    //--

    function update_start_finish_time_d($start, $complete, $doc_no, $line_no){
        $this->load->model('model_tsc_picking_d','',TRUE);
        $this->model_tsc_picking_d->picked_datetime = $start;
        $this->model_tsc_picking_d->completely_picked = $complete;
        $this->model_tsc_picking_d->doc_no = $doc_no;
        $this->model_tsc_picking_d->line_no = $line_no;
        $result = $this->model_tsc_picking_d->update_start_finish_time();

        return $result;
    }
    //---

    function update_start_finish_time_h($start_all_datetime, $finish_all_datetime, $doc_no){
        $this->load->model('model_tsc_picking_h','',TRUE);
        $this->model_tsc_picking_h->start_datetime = $start_all_datetime;
        $this->model_tsc_picking_h->all_finished_datetime = $finish_all_datetime;
        $this->model_tsc_picking_h->doc_no = $doc_no;
        $result = $this->model_tsc_picking_h->update_start_finish_time();

        return $result;
    }
    //---

    function update_pick_h_status($status, $doc_no){
        $this->load->model('model_tsc_picking_h','',TRUE);
        $this->model_tsc_picking_h->statuss = $status;
        $this->model_tsc_picking_h->doc_no = $doc_no;
        $result = $this->model_tsc_picking_h->update_status();
        return $result;
    }
    //---

    function update_item_sn_picked_datetime($d_line_no, $h_doc_no, $start_time){
        $this->load->model('model_tsc_picking_d2','',TRUE);
        $this->load->model('model_tsc_item_sn','',TRUE);

        for($i=0;$i<count($d_line_no);$i++){
            $this->model_tsc_picking_d2->doc_no = $h_doc_no;
            $this->model_tsc_picking_d2->src_line_no = $d_line_no[$i];
            $result = $this->model_tsc_picking_d2->get_list_data();
            $data_d2 = assign_data($result);

            $this->model_tsc_item_sn->update_picked_datetime_v2($start_time[$i],$data_d2);

        }
    }
    //---

    function refresh_status_pick_out_bound(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $result = $this->model_tsc_in_out_bound_h->get_list_inbound_done();
        $data_inbound_done = assign_data($result);

        if(count($result)>0){
          foreach($data_inbound_done as $row){
              $this->model_tsc_in_out_bound_h->status = '12';
              $this->model_tsc_in_out_bound_h->doc_no = $row['inout_doc_no'];
              $this->model_tsc_in_out_bound_h->pick_finished = get_datetime_now();
              $this->model_tsc_in_out_bound_h->update_status();
              $this->model_tsc_in_out_bound_h->update_pick_finished();
          }
        }
    }
    //--

    function checking_stock_item_invt(){
        $item_code = json_decode(stripslashes($_POST['item_code']));
        $qty = json_decode(stripslashes($_POST['qty']));

        $this->load->model('model_tsc_item_invt','',TRUE);

        $check = 1;
        for($i=0;$i<count($item_code);$i++){
            $this->model_tsc_item_invt->item_code = $item_code[$i];
            $qty_available = $this->model_tsc_item_invt->get_lasted_available();
            if($qty > $qty_available){
                $check = 0;
                $item_code_error = $item_code[$i];
                break;
            }
        }

        if($check == 1){
            $response['status'] = "1";
            $response['msg'] = "";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Stock not enough for item code = ".$item_code_error;
          echo json_encode($response);
        }
    }
    //---

    function print(){
        $doc_no = $_GET['id'];

        $this->load->model('model_tsc_picking_h','',TRUE);
        $this->load->model('model_tsc_picking_d','',TRUE);
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        $result = $this->model_tsc_picking_h->get_by_one_doc($doc_no);
        $data["var_picking_h"] = assign_data_one($result);

        $this->model_tsc_picking_d->doc_no = $doc_no;
        $result = $this->model_tsc_picking_d->get_list_data();
        $data['var_picking_d'] = assign_data($result);

        // get ext doc
        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $result = $this->model_tsc_in_out_bound_h->get_one_doc_h();
        $data["var_doc_h"] = assign_data_one($result);

        $this->load->view('wms/outbound/picking/v_print', $data);
    }
    //---

    function update_start_finish_time_d_v2(){
        $this->load->model('model_tsc_picking_d','',TRUE);

        $start_time = $_POST['start'];
        $finish_time = $_POST['finish'];
        $doc_no = $_POST['doc_no'];
        $line_no = $_POST['line_no'];

        $this->update_start_finish_time_d2($start_time, $finish_time, $doc_no, $line_no);
        $result = $this->update_start_finish_time_d($start_time, $finish_time, $doc_no, $line_no);

        if($result){ echo json_encode("1"); }
        else{ echo json_encode("0"); }
    }
    //---

    // 2022-11-04
    function get_change_user(){
        $doc_no = $_POST["id"];

        $this->load->model('model_login','',TRUE);

        $depart[] = "DPT006";
        $result = $this->model_login->get_user_list_by_department($depart);
        $data["var_user"] = assign_data($result);
        $data["doc_no"] = $doc_no;

        $this->load->view('wms/outbound/picking/v_change_user', $data);
    }
    //---

    // 2022-11-04
    function change_user_process(){
        $doc_no = $_POST['doc_no'];
        $userid = $_POST['userid'];

        $this->load->model('model_tsc_picking_h','',TRUE);

        $this->model_tsc_picking_h->assign_user = $userid;
        $this->model_tsc_picking_h->doc_no = $doc_no;
        $result = $this->model_tsc_picking_h->change_assign_user();

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

    function create_detail_v2($doc_no,$total_row,$pick_item_code, $pick_desc,$pick_loc_code, $pick_zone_code, $pick_area_code, $pick_rack_code, $pick_bin_code, $pick_qty , $pick_src_line_no, $pick_uom, $whs, $src_no, $datetime, $date){

        $this->load->model('model_tsc_picking_d','',TRUE);
        $this->load->model('model_tsc_item_invt','',TRUE);
        $this->load->model('model_tsc_in_out_bound_d','',TRUE);
        $this->load->model('model_mst_item_uom_conv','',TRUE);
        $this->load->model('model_tsc_item_sn','',TRUE);

        // get the uom - pcs
        $temp = $this->model_mst_item_uom_conv->get_pcs_multiple_item($pick_item_code);
        unset($item_pcs);
        foreach($temp as $row){ $item_pcs[$row["item_code"]] = $row["pcs"]; }
        //---

        // insert pick_d
        unset($d_doc_no); unset($d_line); unset($d_src_location_code); unset($d_src_no); unset($d_src_line_no);
        unset($d_item_code); unset($d_uom); unset($d_qty_to_picked); unset($d_desc); unset($d_created_time);
        unset($d_location_code); unset($d_zone_code); unset($d_area_code); unset($d_rack_code); unset($d_bin_code);
        unset($d_created_datetime);

        for($i=0;$i<$total_row;$i++){
            $line = $i+1;
            $d_doc_no[] = $doc_no;
            $d_line[] = $line;
            $d_src_location_code[] = $whs;
            $d_src_no[] = $src_no;
            $d_src_line_no[] = $pick_src_line_no[$i];
            $d_item_code[] = $pick_item_code[$i];
            $d_uom[] = $pick_uom[$i];
            $d_qty_to_picked[] = $pick_qty[$i];
            $d_desc[] = $pick_desc[$i];
            $d_created_datetime[] = $datetime;
            $d_location_code[] = $pick_loc_code[$i];
            $d_zone_code[] = $pick_zone_code[$i];
            $d_area_code[] = $pick_area_code[$i];
            $d_rack_code[] = $pick_rack_code[$i];
            $d_bin_code[] = $pick_bin_code[$i];
        }

        $this->model_tsc_picking_d->insert_v3($d_doc_no,$d_line,$d_src_location_code, $d_src_no, $d_src_line_no, $d_item_code, $d_qty_to_picked, $d_uom, $d_location_code, $d_zone_code, $d_area_code, $d_rack_code, $d_bin_code, $d_desc, $d_created_datetime);
        //---

        $this->insert_detail2_v2($d_doc_no, $d_line, $d_src_location_code, $d_src_no, $d_src_line_no,$d_item_code, $d_uom, $d_qty_to_picked, $d_desc, $d_location_code, $d_zone_code, $d_area_code, $d_rack_code, $d_bin_code, $d_created_datetime, $item_pcs);

        // update outbound_d
        $result_combine_d = $this->combine_outbound_d($d_qty_to_picked, $d_src_no, $d_src_line_no);
        $this->model_tsc_in_out_bound_d->update_v3($result_combine_d["d_qty_to_picked"], $result_combine_d["d_src_no"], $result_combine_d["d_src_line_no"]);
        //---

        // update inventory
        for($i=0;$i<$total_row;$i++){
            $this->model_tsc_item_invt->item_code = $pick_item_code[$i];
            $this->model_tsc_item_invt->available = $pick_qty[$i] * -1;
            $this->model_tsc_item_invt->picking = $pick_qty[$i];
            $this->model_tsc_item_invt->picked = 0;
            $this->model_tsc_item_invt->packing = 0;
            $this->model_tsc_item_invt->update_invt();
        }
        //---
    }
    //---

    function insert_detail2_v2($d_doc_no, $d_line, $d_src_location_code, $d_src_no, $d_src_line_no,$d_item_code, $d_uom, $d_qty_to_picked, $d_desc, $d_location_code, $d_zone_code, $d_area_code, $d_rack_code, $d_bin_code, $d_created_datetime, $item_pcs){
        $this->load->model('model_tsc_picking_d2','',TRUE);
        $this->load->model('model_tsc_item_sn','',TRUE);



        // insert_pick_d2
        for($i=0;$i<count($d_doc_no);$i++){

          unset($src_no); unset($src_line_no); unset($item_code); unset($uom); unset($desc); unset($created_datetime);
          unset($location_code_pick); unset($zone_code_pick); unset($area_code_pick); unset($rack_code_pick); unset($bin_code_pick);
          unset($sn); unset($sn2); unset($line_no); unset($master_barcode);

            $j=1;
            if($item_pcs[$d_item_code[$i]] == 1){
                $this->model_tsc_item_sn->status = 1;
                $this->model_tsc_item_sn->item_code = $d_item_code[$i];
                $this->model_tsc_item_sn->location_code = $d_location_code[$i];
                $this->model_tsc_item_sn->zone_code = $d_zone_code[$i];
                $this->model_tsc_item_sn->area_code = $d_area_code[$i];
                $this->model_tsc_item_sn->rack_code = $d_rack_code[$i];
                $this->model_tsc_item_sn->bin_code = $d_bin_code[$i];
                $result_sn = $this->model_tsc_item_sn->get_list_sn_with_status_limit($d_qty_to_picked[$i]);

                $this->model_tsc_item_sn->update_status_v3($this->convert_sn($result_sn), '2'); // 2023-07-17

                foreach($result_sn as $row){
                    $src_no[] = $d_doc_no[$i];
                    $src_line_no[] = $d_line[$i];
                    $item_code[] = $d_item_code[$i];
                    $uom[] = $d_uom[$i];
                    $desc[] = $d_desc[$i];
                    $created_datetime[] = $d_created_datetime[$i];
                    $location_code_pick[] = $d_location_code[$i];
                    $zone_code_pick[] = $d_zone_code[$i];
                    $area_code_pick[] = $d_area_code[$i];
                    $rack_code_pick[] = $d_rack_code[$i];
                    $bin_code_pick[] = $d_bin_code[$i];
                    $sn[] = $row["serial_number"];
                    $sn2[] = $row["sn2"];
                    $line_no[] = $j;
                    $master_barcode[] = "0";
                    $j++;
                }
            }
            else{
                if($d_qty_to_picked[$i] >= $item_pcs[$d_item_code[$i]]){
                    // calculate how many ctn & pcs
                    $pcs = $d_qty_to_picked[$i] % $item_pcs[$d_item_code[$i]];
                    $ctn = ($d_qty_to_picked[$i]-$pcs) / $item_pcs[$d_item_code[$i]];
                    $ctn_pcs = $ctn*$item_pcs[$d_item_code[$i]];

                    $ctn_get = 0;
                    $result_master_barcode = $this->model_tsc_item_sn->get_sn_sn2_still_have_master_code($d_item_code[$i], $ctn, $d_location_code[$i], $d_zone_code[$i], $d_area_code[$i], $d_rack_code[$i], $d_bin_code[$i]);
                    debug($d_doc_no[$i]." = ".$d_item_code[$i]." = ".count($result_master_barcode)." masuk 1");
                    if(count($result_master_barcode) > 0){
                        unset($temp_sn2);
                        foreach($result_master_barcode as $row){
                          $temp_sn2[] = $row["sn2"];
                          $ctn_get += 1;
                        }
                        $result_sn = $this->model_tsc_item_sn->get_sn_by_sn2($temp_sn2);

                        $this->model_tsc_item_sn->update_status_v3($this->convert_sn($result_sn), '2'); // 2023-07-17

                        foreach($result_sn as $row){
                            $src_no[] = $d_doc_no[$i];
                            $src_line_no[] = $d_line[$i];
                            $item_code[] = $d_item_code[$i];
                            $uom[] = $d_uom[$i];
                            $desc[] = $d_desc[$i];
                            $created_datetime[] = $d_created_datetime[$i];
                            $location_code_pick[] = $d_location_code[$i];
                            $zone_code_pick[] = $d_zone_code[$i];
                            $area_code_pick[] = $d_area_code[$i];
                            $rack_code_pick[] = $d_rack_code[$i];
                            $bin_code_pick[] = $d_bin_code[$i];
                            $sn[] = $row["serial_number"];
                            $sn2[] = $row["sn2"];
                            $line_no[] = $j;
                            $master_barcode[] = "1";
                            $j++;
                        }
                    }

                    debug($d_doc_no[$i]." = ".$d_item_code[$i]." = ".$ctn_get." = ".$ctn." masuk 2");
                    if($ctn_get == $ctn){ // get remaining pcs
                      if($pcs > 0){
                          debug($d_doc_no[$i]." = ".$d_item_code[$i]." = ".$pcs." masuk 3");
                          $result_sn = $this->model_tsc_item_sn->get_sn_sn2_already_pcs_with_limit($d_item_code[$i], $pcs, $d_location_code[$i], $d_zone_code[$i], $d_area_code[$i], $d_rack_code[$i], $d_bin_code[$i]);

                          debug($d_doc_no[$i]." = ".$d_item_code[$i]." = ".count($result_sn)." masuk 4");

                          //if(count($result_sn) == 0){ // if no pcs.. seperate the pcs
                          if(count($result_sn) != $pcs){ // if no pcs.. seperate the pcs
                                $this->model_tsc_item_sn->status        = "1";
                                $this->model_tsc_item_sn->item_code     = $d_item_code[$i];
                                $this->model_tsc_item_sn->location_code = $d_location_code[$i];
                                $this->model_tsc_item_sn->zone_code     = $d_zone_code[$i];
                                $this->model_tsc_item_sn->area_code     = $d_area_code[$i];
                                $this->model_tsc_item_sn->rack_code     = $d_rack_code[$i];
                                $this->model_tsc_item_sn->bin_code      = $d_bin_code[$i];
                                $result_sn = $this->model_tsc_item_sn->get_list_sn_with_status_limit_exclude_sn2($pcs,$sn2);
                          }

                          $this->model_tsc_item_sn->update_status_v3($this->convert_sn($result_sn), '2'); // 2023-07-17
                          foreach($result_sn as $row){
                              $src_no[] = $d_doc_no[$i];
                              $src_line_no[] = $d_line[$i];
                              $item_code[] = $d_item_code[$i];
                              $uom[] = $d_uom[$i];
                              $desc[] = $d_desc[$i];
                              $created_datetime[] = $d_created_datetime[$i];
                              $location_code_pick[] = $d_location_code[$i];
                              $zone_code_pick[] = $d_zone_code[$i];
                              $area_code_pick[] = $d_area_code[$i];
                              $rack_code_pick[] = $d_rack_code[$i];
                              $bin_code_pick[] = $d_bin_code[$i];
                              $sn[] = $row["serial_number"];
                              $sn2[] = $row["sn2"];
                              $line_no[] = $j;
                              $master_barcode[] = "0";
                              $j++;
                          }
                      }
                    }
                    else{ // if not all have in ctn.. get the rest on pcs
                        $new_pcs = (($ctn-$ctn_get) * $item_pcs[$d_item_code[$i]]) + $pcs;

                        debug($d_doc_no[$i]." = ".$d_item_code[$i]." = ".$new_pcs." masuk 5");

                        if($new_pcs > 0){
                          $result_sn = $this->model_tsc_item_sn->get_sn_sn2_already_pcs_with_limit($d_item_code[$i], $new_pcs, $d_location_code[$i], $d_zone_code[$i], $d_area_code[$i], $d_rack_code[$i], $d_bin_code[$i]);

                          debug($d_doc_no[$i]." = ".$d_item_code[$i]." = ".count($result_sn)." masuk 6");

                          if(count($result_sn) > 0){

                              $this->model_tsc_item_sn->update_status_v3($this->convert_sn($result_sn), '2'); // 2023-07-17

                              foreach($result_sn as $row){
                                  $src_no[] = $d_doc_no[$i];
                                  $src_line_no[] = $d_line[$i];
                                  $item_code[] = $d_item_code[$i];
                                  $uom[] = $d_uom[$i];
                                  $desc[] = $d_desc[$i];
                                  $created_datetime[] = $d_created_datetime[$i];
                                  $location_code_pick[] = $d_location_code[$i];
                                  $zone_code_pick[] = $d_zone_code[$i];
                                  $area_code_pick[] = $d_area_code[$i];
                                  $rack_code_pick[] = $d_rack_code[$i];
                                  $bin_code_pick[] = $d_bin_code[$i];
                                  $sn[] = $row["serial_number"];
                                  $sn2[] = $row["sn2"];
                                  $line_no[] = $j;
                                  $master_barcode[] = "0";
                                  $j++;
                              }
                          }
                          else{   // this else to prevent the ol system with have no master barcode

                            debug($d_doc_no[$i]." = ".$d_item_code[$i]." = masuk ke individual SN"." masuk 7");

                              $this->model_tsc_item_sn->status = 1;
                              $this->model_tsc_item_sn->item_code = $d_item_code[$i];
                              $this->model_tsc_item_sn->location_code = $d_location_code[$i];
                              $this->model_tsc_item_sn->zone_code = $d_zone_code[$i];
                              $this->model_tsc_item_sn->area_code = $d_area_code[$i];
                              $this->model_tsc_item_sn->rack_code = $d_rack_code[$i];
                              $this->model_tsc_item_sn->bin_code = $d_bin_code[$i];
                              $result_sn = $this->model_tsc_item_sn->get_list_sn_with_status_limit($d_qty_to_picked[$i]);

                              $this->model_tsc_item_sn->update_status_v3($this->convert_sn($result_sn), '2'); // 2023-07-17

                              foreach($result_sn as $row){
                                  $src_no[] = $d_doc_no[$i];
                                  $src_line_no[] = $d_line[$i];
                                  $item_code[] = $d_item_code[$i];
                                  $uom[] = $d_uom[$i];
                                  $desc[] = $d_desc[$i];
                                  $created_datetime[] = $d_created_datetime[$i];
                                  $location_code_pick[] = $d_location_code[$i];
                                  $zone_code_pick[] = $d_zone_code[$i];
                                  $area_code_pick[] = $d_area_code[$i];
                                  $rack_code_pick[] = $d_rack_code[$i];
                                  $bin_code_pick[] = $d_bin_code[$i];
                                  $sn[] = $row["serial_number"];
                                  $sn2[] = $row["sn2"];
                                  $line_no[] = $j;
                                  $master_barcode[] = "0";
                                  $j++;
                              }
                          }
                        }
                    }

                    //---
                }
                else{

                    debug($d_doc_no[$i]." = ".$d_item_code[$i]." = masuk ke SN already has pcs"." masuk 8");

                    // get the sn already on pcs
                    $result_sn = $this->model_tsc_item_sn->get_sn_sn2_already_pcs_with_limit($d_item_code[$i], $d_qty_to_picked[$i], $d_location_code[$i], $d_zone_code[$i], $d_area_code[$i], $d_rack_code[$i], $d_bin_code[$i]);

                    debug($d_doc_no[$i]." = ".$d_item_code[$i]." = ".count($result_sn)." = ".$d_qty_to_picked[$i]." masuk 11 ");

                    if(count($result_sn) > 0 && count($result_sn) == $d_qty_to_picked[$i]){

                      $this->model_tsc_item_sn->update_status_v3($this->convert_sn($result_sn), '2'); // 2023-07-17

                      foreach($result_sn as $row){
                          $src_no[] = $d_doc_no[$i];
                          $src_line_no[] = $d_line[$i];
                          $item_code[] = $d_item_code[$i];
                          $uom[] = $d_uom[$i];
                          $desc[] = $d_desc[$i];
                          $created_datetime[] = $d_created_datetime[$i];
                          $location_code_pick[] = $d_location_code[$i];
                          $zone_code_pick[] = $d_zone_code[$i];
                          $area_code_pick[] = $d_area_code[$i];
                          $rack_code_pick[] = $d_rack_code[$i];
                          $bin_code_pick[] = $d_bin_code[$i];
                          $sn[] = $row["serial_number"];
                          $sn2[] = $row["sn2"];
                          $line_no[] = $j;
                          $master_barcode[] = "0";
                          $j++;
                      }
                    }
                    else{ // if have no on pcs.. so need to break it down to pcs

                  //debug($d_doc_no[$i]." = ".$d_item_code[$i]." = masuk untuk breakdown ke pcs"." masuk 9");

                        $this->model_tsc_item_sn->status = 1;
                        $this->model_tsc_item_sn->item_code = $d_item_code[$i];
                        $this->model_tsc_item_sn->location_code = $d_location_code[$i];
                        $this->model_tsc_item_sn->zone_code = $d_zone_code[$i];
                        $this->model_tsc_item_sn->area_code = $d_area_code[$i];
                        $this->model_tsc_item_sn->rack_code = $d_rack_code[$i];
                        $this->model_tsc_item_sn->bin_code = $d_bin_code[$i];
                        $result_sn = $this->model_tsc_item_sn->get_list_sn_with_status_limit($d_qty_to_picked[$i]);

                        $this->model_tsc_item_sn->update_status_v3($this->convert_sn($result_sn), '2'); // 2023-07-17

                        foreach($result_sn as $row){
                            $src_no[] = $d_doc_no[$i];
                            $src_line_no[] = $d_line[$i];
                            $item_code[] = $d_item_code[$i];
                            $uom[] = $d_uom[$i];
                            $desc[] = $d_desc[$i];
                            $created_datetime[] = $d_created_datetime[$i];
                            $location_code_pick[] = $d_location_code[$i];
                            $zone_code_pick[] = $d_zone_code[$i];
                            $area_code_pick[] = $d_area_code[$i];
                            $rack_code_pick[] = $d_rack_code[$i];
                            $bin_code_pick[] = $d_bin_code[$i];
                            $sn[] = $row["serial_number"];
                            $sn2[] = $row["sn2"];
                            $line_no[] = $j;
                            $master_barcode[] = "0";
                            $j++;
                        }

                        // check if same the qty
                        /*$remain_pcs = 0;
                        if(count($result_sn) < $d_qty_to_picked[$i]){
                            $remain_pcs = $d_qty_to_picked[$i] - count($result_sn);
                        }
                        //---

                        // get remaining pcs from another location
                        if($remain_pcs > 0){
                            $result_picking = $this->model_tsc_item_sn->get_sn_sn2_already_pcs_with_limit_not_certain_rack($d_item_code[$i], $remain_pcs, $d_location_code[$i], $d_zone_code[$i], $d_area_code[$i], $d_rack_code[$i], $d_bin_code[$i]);

                            if(count($result_picking) == $remain_pcs){
                                foreach($result_picking as $row_picking){
                                    $src_no[] = $d_doc_no[$i];
                                    $src_line_no[] = $d_line[$i];
                                    $item_code[] = $d_item_code[$i];
                                    $uom[] = $d_uom[$i];
                                    $desc[] = $d_desc[$i];
                                    $created_datetime[] = $d_created_datetime[$i];
                                    $location_code_pick[] = $d_location_code[$i];
                                    $zone_code_pick[] = $d_zone_code[$i];
                                    $area_code_pick[] = $d_area_code[$i];
                                    $rack_code_pick[] = $d_rack_code[$i];
                                    $bin_code_pick[] = $d_bin_code[$i];
                                    $sn[] = $row_picking["serial_number"];
                                    $sn2[] = $row_picking["sn2"];
                                    $line_no[] = $j;
                                    $master_barcode[] = "0";
                                    $j++;
                                }
                            }
                        }
                        //----*/
                    }
                }
            }

            // insert d2 version 3
            $this->model_tsc_picking_d2->insert_v3($src_no,$src_line_no, $item_code, $uom, $desc, $created_datetime, $location_code_pick, $zone_code_pick, $area_code_pick, $rack_code_pick, $bin_code_pick, $sn, $sn2,1, $line_no, $master_barcode);
            //--

        }


        //$this->model_tsc_item_sn->update_status_v3($sn, '2'); // update sn to picking

    }
    //---

    function combine_outbound_d($d_qty_to_picked, $d_src_no, $d_src_line_no){
        // combine if get from different rack
        unset($d_qty_to_picked_temp);
        unset($d_src_no_temp);
        unset($d_src_line_no_temp);

        $d_src_line_no_temp2 = "";
        for($i=0;$i<count($d_src_line_no);$i++){
            if($d_src_line_no_temp2 == ""){
                $d_qty_to_picked_temp[] = $d_qty_to_picked[$i];
                $d_src_no_temp[] = $d_src_no[$i];
                $d_src_line_no_temp[] = $d_src_line_no[$i];
                $d_src_line_no_temp2 = $d_src_line_no[$i];
            }
            else{
                $check = 0;
                $index = 0;
                for($j=0;$j<count($d_src_line_no_temp);$j++){
                    if($d_src_line_no[$i] == $d_src_line_no_temp[$j]){
                        $check = 1;
                        $index = $j;
                        break;
                    }
                }

                if($check == 1){
                    $d_qty_to_picked_temp[$j] += $d_qty_to_picked[$i];
                    $d_src_line_no_temp2 = $d_src_line_no[$i];
                }
                else{
                    $d_qty_to_picked_temp[] = $d_qty_to_picked[$i];
                    $d_src_no_temp[] = $d_src_no[$i];
                    $d_src_line_no_temp[] = $d_src_line_no[$i];
                    $d_src_line_no_temp2 = $d_src_line_no[$i];
                }
            }
        }

        $data["d_qty_to_picked"] = $d_qty_to_picked_temp;
        $data["d_src_no"] = $d_src_no_temp;
        $data["d_src_line_no"] = $d_src_line_no_temp;

        return $data;
    }

    //2023-03-30
    function check_already_picking_by_shipment(){
        $doc_no     = $_POST["doc_no"];
        $item_code  = json_decode(stripslashes($_POST['pick_item_code']));
        $qty        = json_decode(stripslashes($_POST['pick_qty']));
        $src_line_no= json_decode(stripslashes($_POST['pick_src_line_no']));

        $this->load->model('model_tsc_in_out_bound_d','',TRUE);

        $doc_no_temp[] = $doc_no;
        $result = $this->model_tsc_in_out_bound_d->get_list($doc_no_temp);

        $error = 0;
        foreach($result as $row){
            for($i=0;$i<count($item_code);$i++){
                if($row["item_code"] == $item_code[$i]){
                  if($row["qty_to_picked"] >= $row["qty_to_ship"]){
                      $error_item = $row["item_code"];
                      $error = 1;
                      break;
                  }
                }
            }

            if($error == 1) break;
        }
        //--

        if($error == 0){
          $response['status'] = "1";
          $response['msg'] = "";
        }
        else{
          $response['status'] = "0";
          $response['msg'] = $error_item;
        }

        echo json_encode($response);
    }
    //--

    // 2023-07-17
    function convert_sn($result_sn){
      unset($temp_result_sn);
      foreach($result_sn as $row){
        $temp_result_sn[] = $row["serial_number"];
      }

      return $temp_result_sn;
    }
    //--

    // 2023-08-17
    function check_auto_pick(){
        $item_code = $_POST['item'];
        $qty = $_POST['qty'];
        $h_whs = $_POST["h_whs"];



        $this->load->model('model_tsc_item_sn','',TRUE);

        $this->model_tsc_item_sn->item_code = $item_code;
        $this->model_tsc_item_sn->status = "1";
        $result = $this->model_tsc_item_sn->list_item_location_by_item_code_and_status($h_whs);

        if(count($result) > 1){
            $response['status'] = "0";
        }
        else{
            $result_loc = assign_data_one($result);
            $response["loc"]  = $result_loc['location_code'];
            $response["zone"] = $result_loc['zone_code'];
            $response["area"] = $result_loc['area_code'];
            $response["rack"] = $result_loc['rack_code'];
            $response["bin"]  = $result_loc['bin_code'];
            $response['status'] = "1";
        }

        echo json_encode($response);
    }
    //---
}
