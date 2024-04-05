<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Transferbinsn extends CI_Controller{

    function __construct(){
      parent::__construct();
      $this->load->database();

      $this->load->model('model_zlog','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);
      $this->load->model('model_tsc_transferbin_h','',TRUE);
      $this->load->model('model_config','',TRUE);
      $this->load->model('model_mst_bin','',TRUE);
      $this->load->model('model_tsc_transferbin_d','',TRUE);
      $this->load->model('model_tsc_transferbin_d2','',TRUE);
    }
    //--

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'transferbinsn'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - TransferbinSN"); // insert log

            $this->load->view('wms/inbound/transferbinsn/v_index');

        }
    }
    //---

    function get_sn_information(){
        $sn = $_POST["id"];

        // check if SN2 with prefix "M"
        if($sn[0] == "M") $sn2 = 1;
        else $sn2 = 0;
        //--

        $status = "'1'";

        if($sn2 == 1){ // check SN2
            $result = $this->model_tsc_item_sn->get_sn2_by_status($sn, $status);
        }
        else{ // check serial_number
            $result = $this->model_tsc_item_sn->get_sn_by_status($sn, $status);
        }

        if(count($result) == 0){
            $response["status"] = 0;
        }
        else{
            $response["status"] = 1;
            unset($response["data"]);
            foreach($result as $row){
                $response["data"][] = array(
                  "sn" => $row["serial_number"],
                  "sn2" => $row["sn2"],
                  "item_code" => $row["item_code"],
                  "item_name" => $row["item_name"],
                  "status" => $row["statuss"],
                  "status_name" => $row["sts_name"],
                  "location" => $row["location_code"],
                  "zone" => $row["zone_code"],
                  "area" => $row["area_code"],
                  "rack" => $row["rack_code"],
                  "bin" => $row["bin_code"],
                );
            }
        }

        echo json_encode($response);
    }
    //---

    function process_transfer(){
        $sn = json_decode(stripslashes($_POST['sn']));
        $sn2 = json_decode(stripslashes($_POST['sn2']));
        $loc = json_decode(stripslashes($_POST['loc']));
        $zone = json_decode(stripslashes($_POST['zone']));
        $area = json_decode(stripslashes($_POST['area']));
        $rack = json_decode(stripslashes($_POST['rack']));
        $bin = json_decode(stripslashes($_POST['bin']));
        $item = json_decode(stripslashes($_POST['item']));
        $desc = json_decode(stripslashes($_POST['item_name']));
        $status = json_decode(stripslashes($_POST['status']));
        $status_name = json_decode(stripslashes($_POST['status_name']));
        $uom = json_decode(stripslashes($_POST['uom']));
        $location = $loc[0];
        $new_location = $_POST["new_location"];

        $datetime = get_datetime_now();
        $date = get_date_now();
        $session_data = $this->session->userdata('z_tpimx_logged_in');

        // create transfer bin header
        $new_doc_no = "";
        $new_doc_no = $this->create_header_doc($datetime, $date, "Transfer Bin SN", $session_data['z_tpimx_user_id'], $session_data['z_tpimx_user_id'], "3", $location);

        // insert detail transfer detail
        for($i=0;$i<count($sn);$i++){
            $qty[$i] = 1;
            $new_rack[$i] = $new_location;
        }
        $this->insert_detail2($loc, $zone, $area, $rack, $bin, $item ,$desc,$uom,$qty, $new_doc_no, $datetime, $new_rack, $sn, $sn2);
        //--

        // change location
        $new_location2 = split_location($new_location,"-");
        $this->model_tsc_item_sn->update_location_v4($sn, $new_location2[0], $new_location2[1], $new_location2[2], $new_location2[3], $new_location2[4]);

        $message_end = "Transfer Bin by SNs Successfull";

        if($new_doc_no!=""){
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

    function create_header_doc($datetime, $date, $message, $created_user, $assign_user,$status, $whs){
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

    function get_location(){
        $location = $_POST["location_code"];

        $result = $this->model_mst_bin->get_data_by_location($location);
        $data["var_bin"] = assign_data($result);

        $this->load->view('wms/inbound/transferbinsn/v_location',$data);
    }
    //--

    function check_bin(){
        $location = $_POST["bin"];
        $location_new = split_location($location,"-");

        $this->model_mst_bin->location_code = $location_new[0];
        $this->model_mst_bin->zone_code = $location_new[1];
        $this->model_mst_bin->area_code = $location_new[2];
        $this->model_mst_bin->rack_code = $location_new[3];
        $this->model_mst_bin->code = $location_new[4];
        $this->model_mst_bin->active = 1;
        $result = $this->model_mst_bin->check_bin2();

        if($result) echo "1";
        else echo "0";
    }
    //--

    function insert_detail2($loc, $zone, $area, $rack, $bin, $item, $desc,$uom,$qty, $doc_no, $datetime, $rack_inp, $sn, $sn2){


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

        // change status item_sn to 4
        //$this->model_tsc_item_sn->update_status_v3($d2_sn, "4");

        // insert transferbin_d
        $this->model_tsc_transferbin_d->insert_v3($doc_no,$item,$qty,$uom,$loc,$zone, $area, $rack, $bin, $d_to_loc, $d_to_zone, $d_to_area, $d_to_rack, $d_to_bin, $desc , $datetime, $line_no,"1");

        // insert transferbin_d2
        $this->model_tsc_transferbin_d2->insert_v3($d2_doc_no, $d2_src_line_no, $d2_item_code, $d2_qty, $d2_uom, $d2_sn, $d2_created_datetime, $d2_sn2, $d2_line_no);

    }
    //---
}
