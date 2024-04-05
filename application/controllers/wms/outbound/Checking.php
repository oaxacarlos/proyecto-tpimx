<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checking extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'checking'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - Checking"); // insert log

            $this->load->view('wms/outbound/checking/v_index');
        }
    }
    //---

    function get_list(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_config','',TRUE);
        $this->load->model('model_login','',TRUE);

        $result = $this->model_tsc_in_out_bound_h->get_data_whship_with_qty_has_picked();
        $data["var_whship_has_picked"] = assign_data($result);

        $this->model_config->name = "shipment_timeout_minutes";
        $data["var_timeout_wms"] = $this->model_config->get_value_by_setting_name();

        $this->model_config->name = "shipment_timeout_text";
        $data["var_timeout_text"] = $this->model_config->get_value_by_setting_name();

        // 2023-07-12
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $data["user"] = $session_data['z_tpimx_user_id'];
        //---

        // 2023-07-19
        $result = $this->model_login->get_user_color("qc");
        if(count($result) > 0){
            unset($user_color);
            foreach($result as $row){
                $user_color[$row["user_id"]] = $row["color"];
            }
        }
        $data["user_color"] = $user_color;
        //--

        $this->load->view('wms/outbound/checking/v_list',$data);
    }
    //---

    function qc(){
        $this->model_zlog->insert("Warehouse - QC"); // insert log

        $this->load->view('templates/navigation');

        $src_no = $_GET['id'];

        $this->load->model('model_tsc_picking_d','',TRUE);
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        $this->model_tsc_in_out_bound_h->doc_no = $src_no;
        $result = $this->model_tsc_in_out_bound_h->get_one_doc_h();
        $data["data_outbound_h"] = assign_data_one($result);

        $this->model_tsc_picking_d->src_no = $src_no;
        $result = $this->model_tsc_picking_d->get_list_serial_number_by_src_no();
        $data["var_serial_number"] = assign_data($result);
        $data["outbound_h"] = $src_no;

        // locked doc
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $this->model_tsc_in_out_bound_h->doc_no = $src_no;
        $result = $this->model_tsc_in_out_bound_h->update_doc_to_locked($session_data['z_tpimx_user_id']);
        //--

        $this->load->view('wms/outbound/checking/v_qc',$data);
    }
    //---

    function check_data_invt_with_sn(){
        $serial_number = $_POST["sn"];
        $item_code = $_POST["item"];

        $this->load->model('model_tsc_item_sn','',TRUE);
        $this->load->model('model_config','',TRUE);


        //$result = $this->model_tsc_item_sn->get_data_by_serial_number();
        //$result = $this->model_tsc_item_sn->get_data_by_serial_number_and_item_code();

        // get config
        $this->model_config->name = "scan_status_qc";
        $in_status = $this->model_config->get_value_by_setting_name();

        $this->model_tsc_item_sn->serial_number = $serial_number;
        //$this->model_tsc_item_sn->item_code = $item_code;
        $result = $this->model_tsc_item_sn->get_data_by_serial_number_and_status($in_status);

        if(!$result){
            $response['item_code'] = "";
            $response['serial_number'] = "";
            $response['statuss'] = "";
            $response['location_code'] = "";
            $response['zone_code'] = "";
            $response['area_code'] = "";
            $response['rack_code'] = "";
            $response['bin_code'] = "";
        }
        else{
            $result2 = assign_data_one($result);
            $response['item_code'] = $result2["item_code"];
            $response['serial_number'] = $result2["serial_number"];
            $response['statuss'] = $result2["statuss"];
            $response['location_code'] = $result2["location_code"];
            $response['zone_code'] = $result2["zone_code"];
            $response['area_code'] = $result2["area_code"];
            $response['rack_code'] = $result2["rack_code"];
            $response['bin_code'] = $result2["bin_code"];
        }

        echo json_encode($response);
    }
    //---

    function proceedpack(){
        $this->model_zlog->insert("Warehouse - OutBound Proceed Pack"); // insert log

        $total_row = $_POST['counter'];
        $doc_no = $_POST['doc_no'];
        $item_code = json_decode(stripslashes($_POST['item_code']));
        $loc_pick = json_decode(stripslashes($_POST['loc_pick']));
        $zone_pick = json_decode(stripslashes($_POST['zone_pick']));
        $area_pick = json_decode(stripslashes($_POST['area_pick']));
        $rack_pick = json_decode(stripslashes($_POST['rack_pick']));
        $bin_pick = json_decode(stripslashes($_POST['bin_pick']));
        $sn_pick = json_decode(stripslashes($_POST['sn_pick']));
        $loc_scan = json_decode(stripslashes($_POST['loc_scan']));
        $zone_scan = json_decode(stripslashes($_POST['zone_scan']));
        $area_scan = json_decode(stripslashes($_POST['area_scan']));
        $rack_scan = json_decode(stripslashes($_POST['rack_scan']));
        $bin_scan = json_decode(stripslashes($_POST['bin_scan']));
        $sn_scan = json_decode(stripslashes($_POST['sn_scan']));
        $line_no = json_decode(stripslashes($_POST['line_no']));  // 2022-11-11
        $src_line_no = json_decode(stripslashes($_POST['src_line_no'])); // 2022-11-11
        $pick_doc = json_decode(stripslashes($_POST['pick_doc'])); // 2022-11-11

        $this->load->model('model_tsc_item_sn','',TRUE);
        $this->load->model('model_tsc_picking_d2','',TRUE);
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_tsc_in_out_bound_d','',TRUE);
        $this->load->model('model_tsc_item_entry','',TRUE);
        $this->load->model('model_tsc_item_invt','',TRUE);
        $this->load->model('model_tsc_doc_history','',TRUE);

        $datetime = get_datetime_now();

        //$this->model_tsc_picking_d2->update_scan_by_serial_number_v2($loc_pick,$zone_pick, $area_pick,$rack_pick,$bin_pick,$sn_scan,$datetime,$sn_pick, $total_row);

        // 2022-11-11
        $this->model_tsc_picking_d2->update_scan_by_serial_number_v3($loc_pick,$zone_pick, $area_pick,$rack_pick,$bin_pick,$sn_scan,$datetime,$sn_pick, $total_row, $line_no, $src_line_no, $pick_doc);
        //---

        // if pick and scan are diffrent SN
        unset($sn_temp); $total_row_temp=0;
        for($i=0;$i<$total_row;$i++){
            if($sn_pick[$i] != $sn_scan[$i]){
                $sn_temp[] = $sn_pick[$i];
                $total_row_temp++;
            }
        }
        if($total_row_temp > 0) $this->model_tsc_item_sn->update_status_picked_datetime_v2("1","NULL",$sn_temp,$total_row_temp);
        //---

        $this->model_tsc_item_sn->update_status_picked_datetime_v2("3",$datetime,$sn_scan,$total_row);

        // update inventory and item entry
        $doc_no2[] = $doc_no;
        $result = $this->model_tsc_in_out_bound_d->get_list($doc_no2);

        //$datetime = get_datetime_now();

        foreach($result as $row){
            // update inventory
            $this->model_tsc_item_invt->item_code = $row["item_code"];
            $this->model_tsc_item_invt->available = 0;
            $this->model_tsc_item_invt->picking = 0;
            $this->model_tsc_item_invt->picked = $row["qty_to_picked"]*-1;
            $this->model_tsc_item_invt->packing = $row["qty_to_picked"];
            $this->model_tsc_item_invt->update_invt();
            //---

            // item entry
            $data_entry[] = array(
              "item_code" => $row["item_code"],
              "qty" => $row['qty_to_picked']*-1,
              "src_no" => $row['doc_no'],
              "type" => "2",
              "text" => $row['line_no']."|".$row['src_no']."|".$row['src_line_no'],
              "serial_number" => "",
              "text2" => "",
              "description" => $row['description'],
              "created_datetime" => $datetime,
              "location_code" => $row['src_location_code'], // WH3 2023-05-12
            );
            //----
        }
        $this->model_tsc_item_entry->insert_with_bulk($data_entry);

        //---


        // update status outbound h to packing
        $this->model_tsc_in_out_bound_h->status = "13";
        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $result = $this->model_tsc_in_out_bound_h->update_status();
        //---

        // insert doc history
        $this->model_tsc_doc_history->insert($doc_no,$doc_no,"","13","",$datetime,"Finished QC","");
        //--

        if($result){
            $response['status'] = "1";
            $response['msg'] = "The QC has finished";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }

    //---

    function print(){
        $doc_no = $_GET["id"];

        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_tsc_in_out_bound_d','',TRUE);
        $this->load->model('model_tsc_so','',TRUE);

        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $result = $this->model_tsc_in_out_bound_h->get_one_doc_h();
        $data["var_doc_h"] = assign_data_one($result);

        $doc_no_array[] = $doc_no;
        $result = $this->model_tsc_in_out_bound_d->get_list($doc_no_array);
        $data["var_doc_d"] = assign_data($result);

        // get so
        unset($temp); $first=1;
        foreach($data["var_doc_d"] as $row){
            if($first == 1){
               $temp[] = $row["src_no"];
               $first = 0;
             }
            else{
                if (!in_array($row["src_no"], $temp)) $temp[] = $row["src_no"];
            }
        }

        if(count($temp) > 0){
            foreach($temp as $row){
                $docno[] = $row;
            }
            $result = $this->model_tsc_so->get_data_by_multiple_doc_no($docno);
            $data["var_so"] = assign_data($result);
        }

        //-----

        $this->load->view('wms/outbound/checking/v_print',$data);
    }
    //--

    // 2022-11-04
    function skipscan(){
        $doc_no = $_POST["doc_no"];
        $status = $_POST["status"];

        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        if($status == "1"){
            $status = "0";
            $status_message = "The Document has returned back to scan";
        }
        else if($status == "0"){
          $status = "1";
          $status_message = "The Document has been skipped";
        }

        $result = $this->model_tsc_in_out_bound_h->skip_scan_doc($doc_no, $status);

        if($result){
            $response["status"] = 1;
            $response["msg"] = $status_message;
        }
        else{
            $response["status"] = 0;
            $response["msg"] = "Error";
        }

        echo json_encode($response);
    }
    //--

    // 2022-11-16
    function get_detail_barcode(){
        $doc_no = $_POST['id'];
        $doc_type = $_POST['doc_type'];
        $link = $_POST["link"];

        $this->load->model('model_tsc_picking_d','',TRUE);

        if($doc_type == "qctemp"){
            $this->model_tsc_picking_d->src_no = $doc_no;
            $result = $this->model_tsc_picking_d->get_pick_serial_number_scan_by_whship_temp();
            $data["var_barcode"] = assign_data($result);
        }

        $this->load->view($link,$data);
    }
    //--

    function check_data_invt_with_sn2(){
        $serial_number = $_POST["sn"];
        $item_code = $_POST["item"];

        $this->load->model('model_tsc_item_sn','',TRUE);
        $this->load->model('model_config','',TRUE);

        // get config
        $this->model_config->name = "scan_status_qc";
        $in_status = $this->model_config->get_value_by_setting_name();

        // check master barcode & serial number
        $this->model_tsc_item_sn->sn2 = $serial_number;
        $this->model_tsc_item_sn->serial_number = $serial_number;
        $result = $this->model_tsc_item_sn->get_data_by_sn_sn2_and_status($in_status);

        if(!$result){
            $response['item_code'] = "";
            $response['serial_number'] = "";
            $response['statuss'] = "";
            $response['location_code'] = "";
            $response['zone_code'] = "";
            $response['area_code'] = "";
            $response['rack_code'] = "";
            $response['bin_code'] = "";
            $response['sn2'] = "";
        }
        else{
            $response = assign_data($result);
            /*$response['item_code'] = $result2["item_code"];
            $response['serial_number'] = $result2["serial_number"];
            $response['statuss'] = $result2["statuss"];
            $response['location_code'] = $result2["location_code"];
            $response['zone_code'] = $result2["zone_code"];
            $response['area_code'] = $result2["area_code"];
            $response['rack_code'] = $result2["rack_code"];
            $response['bin_code'] = $result2["bin_code"];*/

        }

        echo json_encode($response);
    }
    //---

    // 2022-12-16
    function proceedpack2(){
        $this->model_zlog->insert("Warehouse - OutBound Proceed Pack"); // insert log

        $total_row = $_POST['counter'];
        $doc_no = $_POST['doc_no'];
        $item_code = json_decode(stripslashes($_POST['item_code']));
        $loc_pick = json_decode(stripslashes($_POST['loc_pick']));
        $zone_pick = json_decode(stripslashes($_POST['zone_pick']));
        $area_pick = json_decode(stripslashes($_POST['area_pick']));
        $rack_pick = json_decode(stripslashes($_POST['rack_pick']));
        $bin_pick = json_decode(stripslashes($_POST['bin_pick']));
        $sn_pick = json_decode(stripslashes($_POST['sn_pick']));
        $loc_scan = json_decode(stripslashes($_POST['loc_scan']));
        $zone_scan = json_decode(stripslashes($_POST['zone_scan']));
        $area_scan = json_decode(stripslashes($_POST['area_scan']));
        $rack_scan = json_decode(stripslashes($_POST['rack_scan']));
        $bin_scan = json_decode(stripslashes($_POST['bin_scan']));
        $sn_scan = json_decode(stripslashes($_POST['sn_scan']));
        $line_no = json_decode(stripslashes($_POST['line_no']));  // 2022-11-11
        $src_line_no = json_decode(stripslashes($_POST['src_line_no'])); // 2022-11-11
        $pick_doc = json_decode(stripslashes($_POST['pick_doc'])); // 2022-11-11
        $sn2_pick = json_decode(stripslashes($_POST['sn2_pick']));
        $sn2_scan = json_decode(stripslashes($_POST['sn2_scan']));

        $this->load->model('model_tsc_item_sn','',TRUE);
        $this->load->model('model_tsc_picking_d2','',TRUE);
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_tsc_in_out_bound_d','',TRUE);
        $this->load->model('model_tsc_item_entry','',TRUE);
        $this->load->model('model_tsc_item_invt','',TRUE);
        $this->load->model('model_tsc_doc_history','',TRUE);
        $this->load->model('model_tsc_item_sn2','',TRUE);

        $datetime = get_datetime_now();

        // 2022-11-11
        $this->model_tsc_picking_d2->update_scan_by_serial_number_v3($loc_pick,$zone_pick, $area_pick,$rack_pick,$bin_pick,$sn_scan,$datetime,$sn_pick, $total_row, $line_no, $src_line_no, $pick_doc, $sn2_scan);
        //---

        // if pick and scan are diffrent SN.. and return back picking to status available = 1
        unset($sn_temp);
        $total_row_temp=0;
        for($i=0;$i<$total_row;$i++){
            if($sn_pick[$i] != $sn_scan[$i]){
                $sn_temp[] = $sn_pick[$i];
                $total_row_temp++;
            }
        }
        if($total_row_temp > 0){ $this->model_tsc_item_sn->update_status_picked_datetime_v2("1","NULL",$sn_temp,$total_row_temp); }
        //---

        // update sn2 status = 0.. void
        $sn2_temp = array_unique($sn2_scan);
        $this->model_tsc_item_sn2->update_status_scan_datetime_v2("0",$datetime,$sn2_temp,count($sn2_temp));
        //---

        $this->model_tsc_item_sn->update_status_picked_datetime_v2("3",$datetime,$sn_scan,$total_row);

        // update inventory and item entry
        $doc_no2[] = $doc_no;
        $result = $this->model_tsc_in_out_bound_d->get_list($doc_no2);
        $this->update_invt_and_item_entry($result,$datetime);
        //---

        // update status outbound h to packing
        $this->model_tsc_in_out_bound_h->status = "13";
        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $result = $this->model_tsc_in_out_bound_h->update_status();
        //---

        // insert doc history
        $this->model_tsc_doc_history->insert($doc_no,$doc_no,"","13","",$datetime,"Finished QC","");
        //--

        if($result){
            $response['status'] = "1";
            $response['msg'] = "The QC has finished";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }

    //---

    function update_invt_and_item_entry($data,$datetime){
        $this->load->model('model_tsc_item_entry','',TRUE);
        $this->load->model('model_tsc_item_invt','',TRUE);

        foreach($data as $row){
            // update inventory
            $this->model_tsc_item_invt->item_code = $row["item_code"];
            $this->model_tsc_item_invt->available = 0;
            $this->model_tsc_item_invt->picking = 0;
            $this->model_tsc_item_invt->picked = $row["qty_to_picked"]*-1;
            $this->model_tsc_item_invt->packing = $row["qty_to_picked"];
            $this->model_tsc_item_invt->update_invt();
            //---

            // item entry
            $data_entry[] = array(
              "item_code" => $row["item_code"],
              "qty" => $row['qty_to_picked']*-1,
              "src_no" => $row['doc_no'],
              "type" => "2",
              "text" => $row['line_no']."|".$row['src_no']."|".$row['src_line_no'],
              "serial_number" => "",
              "text2" => "",
              "description" => $row['description'],
              "created_datetime" => $datetime,
              "location_code" => $row['src_location_code'], // WH3 2023-05-12
            );
            //----
        }
        $this->model_tsc_item_entry->insert_with_bulk($data_entry);
    }
    //--

    // 2023-03-13
    function urgent(){
        $doc_no = $_POST["doc_no"];
        $urgent = $_POST["urgent"];

        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        if($urgent == "0") $result = $this->model_tsc_in_out_bound_h->urgent($doc_no, "1");
        else $result = $this->model_tsc_in_out_bound_h->urgent($doc_no, "0");

        if($urgent == "1"){
            $status = "0";
            $status_message = "The Document has returned back to NO URGENT";
        }
        else if($urgent == "0"){
          $status = "1";
          $status_message = "The Document has been changed to URGENT";
        }

        if($result){
            $response["status"] = 1;
            $response["msg"] = $status_message;
        }
        else{
            $response["status"] = 0;
            $response["msg"] = "Error";
        }

        echo json_encode($response);
    }
    //--

    // 2023-07-12
    function get_change_user(){
        $doc_no = $_POST["id"];

        $this->load->model('model_login','',TRUE);

        $depart[] = "DPT006";
        $depart[] = "DPT005";
        $result = $this->model_login->get_user_list_by_department($depart);
        $data["var_user"] = assign_data($result);
        $data["doc_no"] = $doc_no;

        $this->load->view('wms/outbound/checking/v_change_user', $data);
    }
    //---

    // 2023-07-12
    function change_user_process(){
        $doc_no = $_POST['doc_no'];
        $userid = $_POST['userid'];

        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        $result = $this->model_tsc_in_out_bound_h->update_user_qc($doc_no, $userid);

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
    //---
}

?>
