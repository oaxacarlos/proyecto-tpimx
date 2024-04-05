<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edit extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();

      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $this->load->model('model_tsc_in_out_bound_d','',TRUE);
      $this->load->model('model_tsc_adjust_doc_h','',TRUE);
      $this->load->model('model_tsc_adjust_doc_d','',TRUE);
      $this->load->model('model_tsc_picking_d','',TRUE);
      $this->load->model('model_tsc_picking_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);
      $this->load->model('model_tsc_item_invt','',TRUE);
      $this->load->model('model_config','',TRUE);
      $this->load->model('model_tsc_so','',TRUE);
      $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'edit'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - OutBound Edit"); // insert log

            $this->load->view('wms/outbound/edit/v_index');
        }
    }
    //----

    function get_whship(){
        $doc_no = $_POST["inp_whship"];

        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $result_h = assign_data_one($this->model_tsc_in_out_bound_h->get_one_doc_h());

        if(($result_h["status1"]!="1" and $result_h["status1"]!="11") or $result_h["canceled"]=="1"){
            $data["message"] = "The Shipment status not Open and Picking or has been canceled. Not allow to Edit";
            $data["status"] = 0;
        }
        else{
            $doc_no_temp[] = $doc_no;
            $result_d = assign_data($this->model_tsc_in_out_bound_d->get_list_with_picking($doc_no_temp));

            $data["var_doc_h"] = $result_h;
            $data["var_doc_d"] = $result_d;
            $data["status"] = 1;
        }

        $this->load->view('wms/outbound/edit/v_list',$data);
    }
    //---

    function create_new(){
        $this->model_zlog->insert("Warehouse - Creating OutBound Edit"); // insert log

        $doc_no = json_decode(stripslashes($_POST['doc_no']));
        $line_no = json_decode(stripslashes($_POST['line_no']));
        $item_code = json_decode(stripslashes($_POST['item_code']));
        $desc = json_decode(stripslashes($_POST['desc']));
        $qty_to_ship = json_decode(stripslashes($_POST['qty_to_ship']));
        $qty_minus = json_decode(stripslashes($_POST['qty_minus']));
        $qty_result = json_decode(stripslashes($_POST['qty_result']));
        $pick_no = json_decode(stripslashes($_POST['pick_no']));
        $pick_line_no = json_decode(stripslashes($_POST['pick_line_no']));
        $so_no = json_decode(stripslashes($_POST['so_no']));
        $cust_code = json_decode(stripslashes($_POST['cust_code']));
        $cust_name = json_decode(stripslashes($_POST['cust_name']));
        $message = $_POST["message"];

        $this->db->trans_begin();

        // get config
        $new_code = "";
        $this->model_config->name = "doc_number_adjust";
        $last_doc_number = $this->model_config->get_value_by_setting_name();
        $last_doc_number = $last_doc_number + 1;

        $this->model_config->name = "doc_number_adjust";
        $this->model_config->valuee = $last_doc_number;
        $this->model_config->update_value();

        $this->model_config->name = "pref_adjust_doc";
        $pref_doc = $this->model_config->get_value_by_setting_name();

        $this->model_config->name = "digit_adjust_doc";
        $digit_doc = $this->model_config->get_value_by_setting_name();

        $new_code = $pref_doc.sprintf("%0".$digit_doc."d", $last_doc_number);
        //----

        // initial
        $datetime = get_datetime_now();
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];
        //---

        // insert new header
        $this->model_tsc_adjust_doc_h->doc_no = $new_code;
        $this->model_tsc_adjust_doc_h->created_datetime = $datetime;
        $this->model_tsc_adjust_doc_h->created_user = $created_user;
        $this->model_tsc_adjust_doc_h->text1 = $message;
        $this->model_tsc_adjust_doc_h->confirm = 0;
        $this->model_tsc_adjust_doc_h->insert();

        // insert detail
        if($new_code!="") $this->model_tsc_adjust_doc_d->insert_batch($new_code, $doc_no, $line_no, $item_code, $desc, $qty_to_ship, $qty_minus, $qty_result,$pick_no, $pick_line_no, $so_no, $cust_code, $cust_name);

        //$this->send_email_created_new($new_code, $doc_no, $line_no, $item_code, $desc, $qty_to_ship, $qty_minus, $qty_result,$pick_no, $pick_line_no, $message, $so_no, $cust_code, $cust_name);

        $this->insert_into_email3($new_code, $message, $datetime); // 2023-08-31

        $this->db->trans_complete();

        if($new_code!=""){
            $response['status'] = "1";
            $response['msg'] = "New Edit Document has been created with No = ".$new_code;
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
        }

        echo json_encode($response);
    }
    //---

    function confirm(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'edit/confirm'])){
            $this->load->view('view_home');
        }
        else{
          $result = $this->model_tsc_adjust_doc_h->get_list();
          $data["var_doc_h"] = assign_data($result);
          $this->load->view('wms/outbound/edit/confirm/v_index',$data);
        }
    }
    //---

    function confirm_detail(){
        $doc_no = $_POST["id"];
        $link = $_POST["link"];
        $status = $_POST["status"];

        $data["var_doc_d"] = assign_data($this->model_tsc_adjust_doc_d->get_list($doc_no));
        $data["doc_no"] = $doc_no;
        $data["status"] = $status;

        $this->load->view($link,$data);
    }
    //--

    function confirm_process(){
        $doc_no = $_POST["doc_no"];

        $this->db->trans_begin();
        $adjust_doc_h = $this->model_tsc_adjust_doc_h->get_list_one_doc($doc_no);
        $adjust_doc_d = $this->model_tsc_adjust_doc_d->get_list($doc_no);

        foreach($adjust_doc_d as $row){
            if(is_null($row["picking_no"]) or $row["picking_no"]==""){ // only edit in "in out bound document"
                $this->edit_inout_bound_d($row["doc_no_edited"], $row["line_no_edited"], $row["item_code"], $row["qty_minus"]);
            }
            else{
                // if have Picking
                // edit 1. item invt, 2. item sn, 3. pick d2, 4. pick d, 5. in out bound

                // get the serial_number from pick d2
                $limit = $row["qty_minus"]*-1;
                $this->model_tsc_picking_d2->src_no = $row["picking_no"];
                $this->model_tsc_picking_d2->src_line_no = $row["picking_line_no"];
                $result_pick_d2 = $this->model_tsc_picking_d2->get_list_data_by_docno_srclineno($limit);
                //---



                // change status on item_sn
                unset($sn);
                foreach($result_pick_d2 as $row2){ $sn[] = $row2["serial_number_pick"]; }
                $this->model_tsc_item_sn->update_status_v3($sn, "1");
                //---

                // delete pick d2
                foreach($result_pick_d2 as $row2){
                    $this->model_tsc_picking_d2->src_no = $row2["src_no"];
                    $this->model_tsc_picking_d2->line_no = $row2["line_no"];
                    $this->model_tsc_picking_d2->src_line_no = $row2["src_line_no"];
                    $this->model_tsc_picking_d2->item_code = $row2["item_code"];
                    $this->model_tsc_picking_d2->serial_number_pick = $row2["serial_number_pick"];
                    $this->model_tsc_picking_d2->delete_pick_d2_by_srcno_lineno_srclineno_itemcode_serialnumberpick();
                }

                //update pick d
                $result_pick_d = assign_data_one($this->model_tsc_picking_d->get_picking_no_grup_by_doc_no($row["picking_no"], $row["picking_line_no"], $row["item_code"]));

                $qty_to_picked = $result_pick_d["qty_to_picked"] + $row["qty_minus"];

                // update invt
                $this->model_tsc_item_invt->available = $row["qty_minus"]*-1;
                $this->model_tsc_item_invt->picking = $row["qty_minus"];
                $this->model_tsc_item_invt->picked = 0;
                $this->model_tsc_item_invt->packing = 0;
                $this->model_tsc_item_invt->packing = 0;
                $this->model_tsc_item_invt->item_code = $row["item_code"];
                $this->model_tsc_item_invt->update_invt();

                if($qty_to_picked > 0){
                    $this->model_tsc_picking_d->doc_no = $row["picking_no"];
                    $this->model_tsc_picking_d->line_no = $row["picking_line_no"];
                    $this->model_tsc_picking_d->item_code = $row["item_code"];
                    $this->model_tsc_picking_d->qty_to_picked = $qty_to_picked;
                    $this->model_tsc_picking_d->update_qty_to_picked();
                }
                else{ // delete line
                  $this->model_tsc_picking_d->doc_no = $row["picking_no"];
                  $this->model_tsc_picking_d->line_no = $row["picking_line_no"];
                  $this->model_tsc_picking_d->item_code = $row["item_code"];
                  $this->model_tsc_picking_d->delete_line();
                }

                // update in out bound d
                $this->edit_inout_bound_d($row["doc_no_edited"], $row["line_no_edited"], $row["item_code"], $row["qty_minus"]);
            }
        }

        // update status confirm
        $this->model_tsc_adjust_doc_h->doc_no = $doc_no;
        $this->model_tsc_adjust_doc_h->confirm_datetime = get_datetime_now();
        $result = $this->model_tsc_adjust_doc_h->update_confirmed();

        if($result){
            $response["status"] = 1;
            $response["msg"] = "The data has been proceed";
        }
        else{
            $response["status"] = 0;
            $response["msg"] = "Error";
        }

        echo json_encode($response);

        $this->db->trans_complete();
    }
    //--

    function calculate_qty($value,$minus){
        if($value > 0) $value = $value + $minus;
        return $value;
    }
    //---

    function edit_inout_bound_d($doc_no_edit, $line_no_edited, $item_code, $qty_minus){
        $result_doc_d = $this->model_tsc_in_out_bound_d->get_list_with_docno_lineno_itemcode($doc_no_edit, $line_no_edited, $item_code);
        foreach($result_doc_d as $row2){
            $temp = $this->calculate_qty($row2["qty_to_ship"] ,$qty_minus);
            if($temp > 0){
                // update qty_to_ship, qty_to_picked, qty_outstanding, qty, qty_packed, qty_packed_outstanding
                $this->model_tsc_in_out_bound_d->qty_to_ship = $this->calculate_qty($row2["qty_to_ship"] ,$qty_minus);
                $this->model_tsc_in_out_bound_d->qty_to_picked = $this->calculate_qty($row2["qty_to_picked"] ,$qty_minus);
                $this->model_tsc_in_out_bound_d->qty_outstanding = $this->calculate_qty($row2["qty_outstanding"] ,$qty_minus);;
                $this->model_tsc_in_out_bound_d->qty = $this->calculate_qty($row2["qty"] ,$qty_minus);
                //$this->model_tsc_in_out_bound_d->qty_to_packed = $this->calculate_qty($row2["qty_to_packed"] ,$qty_minus);
                $this->model_tsc_in_out_bound_d->qty_packed_outstanding = $this->calculate_qty($row2["qty_packed_outstanding"] ,$qty_minus);
                $this->model_tsc_in_out_bound_d->doc_no = $row2["doc_no"];
                $this->model_tsc_in_out_bound_d->line_no = $row2["line_no"];
                $result_query = $this->model_tsc_in_out_bound_d->update_qty_ship_picked_outstanding_packed_packedoutstanding();
            }
            else{
                $this->model_tsc_in_out_bound_d->doc_no = $doc_no_edit;
                $this->model_tsc_in_out_bound_d->line_no = $line_no_edited;
                $this->model_tsc_in_out_bound_d->item_code = $item_code;
                $this->model_tsc_in_out_bound_d->delete_line();
            }
        }
    }
    //---

    function confirm_cancel(){
        $doc_no = $_POST["doc_no"];

        $this->model_tsc_adjust_doc_h->doc_no = $doc_no;
        $this->model_tsc_adjust_doc_h->canceled_datetime = get_datetime_now();
        $result = $this->model_tsc_adjust_doc_h->update_cancel();

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
    //----

    function send_email_created_new($new_code, $doc_no, $line_no, $item_code, $desc, $qty_to_ship, $qty_minus, $qty_result,$pick_no, $pick_line_no, $message, $so_no, $cust_code, $cust_name){
        $this->load->model('model_config','',TRUE);

        // get send to
        $this->model_config->name = "email_adjust_doc_to";
        $send_to = $this->model_config->get_value_by_setting_name();
        //---

        // get send cc
        unset($cc);
        $this->model_config->name = "email_adjust_doc_cc";
        $cc_temp = $this->model_config->get_value_by_setting_name();
        $cc = explode("|",$cc_temp);
        //--

        $this->load->library('MY_phpmailer');

        unset($detail);
        for($i=0;$i<count($doc_no);$i++){
            $detail[] = array(
                "doc_no" => $doc_no[$i],
                "line_no" => $line_no[$i],
                "item_code" => $item_code[$i],
                "desc" => $desc[$i],
                "qty_to_ship" => $qty_to_ship[$i],
                "qty_minus" => $qty_minus[$i],
                "qty_result" => $qty_result[$i],
                "so_no" => $so_no[$i],
                "cust_code" => $cust_code[$i],
                "cust_name" => $cust_name[$i],
            );
        }

        // 2023-08-31
        //$datetime = get_datetime_now();
        //$this->insert_into_email($new_code, $message, $datetime, "3");

        $body = $this->my_phpmailer->email_body_whship_edit($new_code,get_datetime_now(),$message, $detail);
        $to = $send_to;
        $subject = "WH Shipment Edit (".$new_code.")";
        $from_info = "WMS TPI-MX";
        $altbody = "";
        $result = $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
    }
    //---

    function check_wh_user(){
        $doc_no = $_POST["inp_whship"];

        $user_plant = get_plant_user_by_array();

        // get warehouse from the doc
        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $result = $this->model_tsc_in_out_bound_h->get_one_doc_h();
        if(count($result) == 0){
            $status = 0;
            $error_msg = "We couldn't found the Shipment";
        }
        else{
            $result = assign_data_one($result);
            $check = 0;
            for($i=0;$i<count($user_plant);$i++){
                if($result["doc_location_code"] == $user_plant[$i]){
                    $check = 1;
                }
            }

            if($check == 0){
                $status = 0;
                $error_msg = "Your Warehouse setup not assign to this Warehouse";
            }
            else{
                $status = 1;
                $error_msg = "";
            }
        }

        $response["status"] = $status;
        $response["msg"] = $error_msg;

        echo json_encode($response);
    }
    //--


    // 2023-06-21
    function create_new2(){
        $this->model_zlog->insert("Warehouse - Creating OutBound Edit"); // insert log

        $doc_no[] = $_POST["wship_no"];
        $line_no[] = $_POST["src_line_no"];
        $item_code[] = $_POST["item_code"];
        $desc[] = $_POST["desc"];
        $qty_to_ship[] = $_POST["qty_before_edit"];
        $qty_minus[] = $_POST["qty_minus"];
        $qty_result[] = $_POST["qty_result"];
        $pick_no[] = $_POST["pick_doc_no"];
        $pick_line_no[] = $_POST["id"];
        //$so_no = json_decode(stripslashes($_POST['so_no']));
        //$cust_code = json_decode(stripslashes($_POST['cust_code']));
        //$cust_name = json_decode(stripslashes($_POST['cust_name']));
        $message = $_POST["message"];
        $type = $_POST["type"];

        // get SO no, cust_no, cust_name
        $result = $this->model_tsc_so->so_information_from_whsip_and_line($doc_no[0], $line_no[0]);
        $result = assign_data_one($result);
        $so_no[]  = $result["so_no"];
        $cust_code[] = $result["sell_cust_no"];
        $cust_name[] = $result["sell_to_cust_name"];
        //---

        $this->db->trans_begin();

        // get config
        $new_code = "";
        $this->model_config->name = "doc_number_adjust";
        $last_doc_number = $this->model_config->get_value_by_setting_name();
        $last_doc_number = $last_doc_number + 1;

        $this->model_config->name = "doc_number_adjust";
        $this->model_config->valuee = $last_doc_number;
        $this->model_config->update_value();

        $this->model_config->name = "pref_adjust_doc";
        $pref_doc = $this->model_config->get_value_by_setting_name();

        $this->model_config->name = "digit_adjust_doc";
        $digit_doc = $this->model_config->get_value_by_setting_name();

        $new_code = $pref_doc.sprintf("%0".$digit_doc."d", $last_doc_number);
        //----

        // initial
        $datetime = get_datetime_now();
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];
        //---

        // insert new header
        $this->model_tsc_adjust_doc_h->doc_no = $new_code;
        $this->model_tsc_adjust_doc_h->created_datetime = $datetime;
        $this->model_tsc_adjust_doc_h->created_user = $created_user;
        $this->model_tsc_adjust_doc_h->text1 = $message;
        $this->model_tsc_adjust_doc_h->confirm = 2;
        $this->model_tsc_adjust_doc_h->insert();

        // insert detail
        if($new_code!="") $this->model_tsc_adjust_doc_d->insert_batch($new_code, $doc_no, $line_no, $item_code, $desc, $qty_to_ship, $qty_minus, $qty_result,$pick_no, $pick_line_no, $so_no, $cust_code, $cust_name);

        //$this->send_email_created_new2($new_code, $doc_no, $line_no, $item_code, $desc, $qty_to_ship, $qty_minus, $qty_result,$pick_no, $pick_line_no, $message, $so_no, $cust_code, $cust_name, $type);

        $this->insert_into_email($new_code, $message, $datetime, $type); // 2023-08-24

        $this->db->trans_complete();

        if($new_code!=""){
            $response['status'] = "1";
            $response['msg'] = "New Edit Document has been created with No = ".$new_code;
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
        }

        echo json_encode($response);

    }
    //---

    // 2023-06-22
    function send_process(){
        $doc_no = $_POST["doc_no"];

        $result_h = assign_data_one($this->model_tsc_adjust_doc_h->get_list_one_doc($doc_no));
        $result_d = assign_data($this->model_tsc_adjust_doc_d->get_list($doc_no));

        foreach($result_d as $row){
            $wship_no[] = $row["doc_no_edited"];
            $line_no[] = $row["line_no_edited"];
            $item_code[] = $row["item_code"];
            $desc[] = $row["description"];
            $qty_to_ship[] = $row["qty_to_ship"];
            $qty_minus[] = $row["qty_minus"];
            $qty_result[] = $row["qty_result"];
            $pick_no[] = $row["picking_no"];
            $pick_line_no[] = $row["picking_line_no"];
            $so_no[] = $row["so_no"];
            $cust_code[] = $row["cust_code"];
            $cust_name[] = $row["cust_name"];
        }

        //$this->send_email_created_new($result_h["doc_no"], $wship_no, $line_no, $item_code, $desc, $qty_to_ship, $qty_minus, $qty_result,$pick_no, $pick_line_no, $result_h["text1"], $so_no, $cust_code, $cust_name);

        $this->insert_into_email2($result_h["doc_no"], $result_h["text1"], $datetime); // 2023-08-24

        // update
        $this->model_tsc_adjust_doc_h->confirm = 0;
        $this->model_tsc_adjust_doc_h->doc_no = $doc_no;
        $this->model_tsc_adjust_doc_h->canceled_datetime = get_datetime_now();
        $status = $this->model_tsc_adjust_doc_h->update_confirmed2();

        if($status == 1){
            $response['status'] = "1";
            $response['msg'] = "Email has been Sent";
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
        }

        echo json_encode($response);

    }
    //---

    // 2023-06-22
    function send_email_created_new2($new_code, $doc_no, $line_no, $item_code, $desc, $qty_to_ship, $qty_minus, $qty_result,$pick_no, $pick_line_no, $message, $so_no, $cust_code, $cust_name, $type){
        $this->load->model('model_config','',TRUE);

        // get send to
        $this->model_config->name = "email_adjust_to_from_picker";
        $send_to = $this->model_config->get_value_by_setting_name();
        //---

        // get send cc
        unset($cc);
        $this->model_config->name = "email_adjust_cc_from_picker";
        $cc_temp = $this->model_config->get_value_by_setting_name();
        $cc = explode("|",$cc_temp);
        //--

        $this->load->library('MY_phpmailer');

        unset($detail);
        for($i=0;$i<count($doc_no);$i++){
            $detail[] = array(
                "doc_no" => $doc_no[$i],
                "line_no" => $line_no[$i],
                "item_code" => $item_code[$i],
                "desc" => $desc[$i],
                "qty_to_ship" => $qty_to_ship[$i],
                "qty_minus" => $qty_minus[$i],
                "qty_result" => $qty_result[$i],
                "so_no" => $so_no[$i],
                "cust_code" => $cust_code[$i],
                "cust_name" => $cust_name[$i],
            );
        }

        $body = $this->my_phpmailer->email_body_whship_edit_from_picker($new_code,get_datetime_now(),$message, $detail);
        $to = $send_to;

        if($type == 1) $subject = "Picker Request - WH Shipment Edit (".$new_code.")";
        else if($type == 2) $subject = "Admin Picking - WH Shipment Edit (".$new_code.")";

        $from_info = "WMS TPI-MX";
        $altbody = "";
        $result = $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
    }
    //---

    function insert_into_email($doc_no, $message, $datetime, $type){
        $this->load->model('model_config','',TRUE);
        $this->load->model('model_tsc_email','',TRUE);

        // get send to
        $this->model_config->name = "email_adjust_to_from_picker";
        $send_to = $this->model_config->get_value_by_setting_name();
        //---

        // get send cc
        unset($cc);
        $this->model_config->name = "email_adjust_cc_from_picker";
        $cc = $this->model_config->get_value_by_setting_name();
        //$cc = explode("|",$cc_temp);
        //--

        $to = $send_to;

        if($type == 1) $subject = "Picker Request - WH Shipment Edit (".$doc_no.")";
        else if($type == 2) $subject = "Admin Picking - WH Shipment Edit (".$doc_no.")";

        $from_info = "WMS TPI-MX";
        $altbody = "";

        $datetime = get_datetime_now();

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];

        $this->model_tsc_email->insert("3", $doc_no, $to, $cc, $subject,"notification@toyopower.com", $datetime, $from_info, $message, $created_user);
    }
    //--

    function insert_into_email2($doc_no, $message, $datetime){
        $this->load->model('model_config','',TRUE);
        $this->load->model('model_tsc_email','',TRUE);

        // get send to
        $this->model_config->name = "email_adjust_doc_to";
        $send_to = $this->model_config->get_value_by_setting_name();
        //---

        // get send cc
        unset($cc);
        $this->model_config->name = "email_adjust_doc_cc";
        $cc = $this->model_config->get_value_by_setting_name();
        //$cc = explode("|",$cc_temp);
        //--

        $to = $send_to;
        $subject = "WH Shipment Edit (".$doc_no.")";
        $from_info = "WMS TPI-MX";
        $altbody = "";

        $datetime = get_datetime_now();

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];

        $this->model_tsc_email->insert("4", $doc_no, $to, $cc, $subject,"notification@toyopower.com", $datetime, $from_info, $message, $created_user);
    }
    //--

    function insert_into_email3($doc_no, $message, $datetime){
        $this->load->model('model_config','',TRUE);
        $this->load->model('model_tsc_email','',TRUE);

        // get send to
        $this->model_config->name = "email_adjust_doc_to";
        $send_to = $this->model_config->get_value_by_setting_name();
        //---

        // get send cc
        unset($cc);
        $this->model_config->name = "email_adjust_doc_cc";
        $cc = $this->model_config->get_value_by_setting_name();
        //$cc = explode("|",$cc_temp);
        //--

        $to = $send_to;
        $subject = "WH Shipment Edit (".$doc_no.")";
        $from_info = "WMS TPI-MX";
        $altbody = "";

        $datetime = get_datetime_now();

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];

        $this->model_tsc_email->insert("5", $doc_no, $to, $cc, $subject,"notification@toyopower.com", $datetime, $from_info, $message, $created_user);
    }
    //--
}
