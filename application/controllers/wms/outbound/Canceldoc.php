<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Canceldoc extends CI_Controller{

    function __construct(){
      parent::__construct();
      $this->load->database();

      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $this->load->model('model_tsc_in_out_bound_d','',TRUE);
      $this->load->model('model_tsc_adjust_doc_h','',TRUE);
      $this->load->model('model_tsc_adjust_doc_d','',TRUE);
      $this->load->model('model_tsc_picking_h','',TRUE);
      $this->load->model('model_tsc_picking_d','',TRUE);
      $this->load->model('model_tsc_picking_d2','',TRUE);
      $this->load->model('model_tsc_item_sn','',TRUE);
      $this->load->model('model_tsc_item_invt','',TRUE);
      $this->load->model('model_tsc_packing_d2','',TRUE);
      $this->load->model('model_tsc_packing_d','',TRUE);
      $this->load->model('model_tsc_packing_h','',TRUE);
      $this->load->model('model_config','',TRUE);
      $this->load->model('model_tsc_item_invt','',TRUE);
      $this->load->model('model_tsc_item_entry','',TRUE);
      $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'canceldoc'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - Cancel Document"); // insert log

            $this->load->view('wms/outbound/canceldoc/v_index');
        }
    }
    //----

    function get_whship(){
        $doc_no = $_POST["inp_whship"];

        $picking = 0;
        $packing = 0;

        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $result_h = assign_data_one($this->model_tsc_in_out_bound_h->get_one_doc_h());

        if($result_h["canceled"]=="1"){
            $data["message"] = "The Shipment has beed canceled.";
            $data["status"] = 0;
        }
        else{
            // check picking
            $this->model_tsc_picking_d->src_no = $doc_no;
            $result = $this->model_tsc_picking_d->get_pick_doc_no_by_src_no();
            if(count($result) > 0){
                $data["var_picking_d"] = assign_data($result);

                $this->model_tsc_picking_d->src_no = $doc_no;
                $data["var2_picking_d"] = assign_data($this->model_tsc_picking_d->get_list_pick_for_pack_by_src_no());

                $picking = 1;
            }
            else{ $data["var_picking_d"] = 0; }
            //---

            // check packing
            $this->model_tsc_packing_d->src_no = $doc_no;
            $result = $this->model_tsc_packing_d->get_pack_doc_no_by_src_no();
            if(count($result) > 0){
                $data["var_packing_d"] = assign_data($result);

                $this->model_tsc_packing_d->src_no = $doc_no;
                $data["var2_packing_d"] = assign_data($this->model_tsc_packing_d->get_list());

                $packing = 1;
            }
            else{ $data["var_packing_d"] = 0; }
            //--

            $data["doc_no"] = $doc_no;
            $data["status"] = 1;
        }


        $this->load->view('wms/outbound/canceldoc/v_list',$data);
    }
    //---

    function process(){
        $doc_no = $_POST["doc_no"];
        $message = $_POST["message"];

        $datetime = get_datetime_now();

        $this->db->trans_begin();

        // check packing
        $this->model_tsc_packing_d->src_no = $doc_no;
        $result = $this->model_tsc_packing_d->get_list_pack();
        if(count($result) > 0 ){ // if has packing.. cancel it
            $this->cancel_packing($doc_no);
        }
        //---

        // check picking
        $doc_no_temp[] = $doc_no;
        $result = $this->model_tsc_in_out_bound_d->get_list_with_picking($doc_no_temp);
        if(count($result) > 0){ // if has picking.. cancel it
            $this->cancel_picking($doc_no);
        }
        //--

        // cancel document wship
        $result = $this->model_tsc_in_out_bound_h->canceled_doc($datetime, $doc_no, $message);

        if($result){
            $response["status"] = 1;
            $response["msg"] = "The data has been proceed";

            // send email
            $this->send_email($doc_no, $message ,$datetime);
        }
        else{
            $response["status"] = 0;
            $response["msg"] = "Error";
        }

        echo json_encode($response);
        $this->db->trans_complete();
    }
    //--

    function cancel_packing($doc_no){

        $this->model_tsc_packing_d->src_no = $doc_no;
        $result = $this->model_tsc_packing_d->get_list_pack_group_by_src_no();

        foreach($result as $row){
            // delete pick_d2
            $this->model_tsc_packing_d2->doc_no = $row['doc_no'];
            $this->model_tsc_packing_d2->delete_packing_d2_by_doc_no();
            //--

            // delete pick_d
            $this->model_tsc_packing_d->doc_no = $row['doc_no'];
            $this->model_tsc_packing_d->delete_packing_d_by_doc_no();
            //--

            // update status
            $this->model_tsc_packing_h->doc_no = $row["doc_no"];
            $this->model_tsc_packing_h->statuss = "0";
            $this->model_tsc_packing_h->update_status();
            //--
        }
    }
    //---

    function cancel_picking($doc_no){

        $datetime = get_datetime_now();

        // get picking document no
        $this->model_tsc_picking_d->src_no = $doc_no;
        $result_pick_doc_no = $this->model_tsc_picking_d->get_pick_doc_no_by_src_no();
        //---

        foreach($result_pick_doc_no as $row){
            // get serial number from pick_d2
            $this->model_tsc_picking_d2->doc_no = $row["doc_no"];
            $result_pick_d2 = $this->model_tsc_picking_d2->get_list_data_by_doc_no();
            //--

            // change status on item_sn
            unset($sn);
            foreach($result_pick_d2 as $row2){
                if(is_null($row2["serial_number_scan"]) or $row2["serial_number_scan"] == "") $sn[] = $row2["serial_number_pick"];
                else $sn[] = $row2["serial_number_scan"];
            }
            if(!is_null($sn)) $this->model_tsc_item_sn->update_status_v3($sn, "1");
            //---

            // delete pick d2, update invt, insert item entry
            foreach($result_pick_d2 as $row2){
                // delete pick d2
                $this->model_tsc_picking_d2->src_no = $row2["src_no"];
                $this->model_tsc_picking_d2->line_no = $row2["line_no"];
                $this->model_tsc_picking_d2->src_line_no = $row2["src_line_no"];
                $this->model_tsc_picking_d2->item_code = $row2["item_code"];
                $this->model_tsc_picking_d2->serial_number_pick = $row2["serial_number_pick"];
                $this->model_tsc_picking_d2->delete_pick_d2_by_srcno_lineno_srclineno_itemcode_serialnumberpick();
                //---

                // update invt
                $this->model_tsc_item_invt->available = $row2["qty"];
                $this->model_tsc_item_invt->picking = 0;
                $this->model_tsc_item_invt->picked = 0;
                $this->model_tsc_item_invt->packing = 0;
                $this->model_tsc_item_invt->packing = 0;
                $this->model_tsc_item_invt->item_code = $row2["item_code"];
                $this->model_tsc_item_invt->update_invt();
                //---

                // item entry
                $data_entry[] = array(
                  "item_code" => $row2["item_code"],
                  "qty" => $row2['qty'],
                  "src_no" => $row2['src_no'],
                  "type" => "1",
                  "text" => $doc_no,
                  "serial_number" => "",
                  "text2" => "",
                  "description" => "Canceled Document",
                  "created_datetime" => $datetime,
                  "location_code" => $row2["location_code_pick"]
                );
                //----
            }

            $this->model_tsc_item_entry->insert_with_bulk($data_entry); // insert minus item entry
            //---

            // cancel picking document
            $this->model_tsc_picking_h->doc_no = $row["doc_no"];
            $this->model_tsc_picking_h->statuss= "0";
            $this->model_tsc_picking_h->update_status();
            //--
        }

    }
    //---

    function send_email($doc_no, $message ,$datetime){
        // get send to
        $this->model_config->name = "email_whship_canceled_doc_to";
        $send_to = $this->model_config->get_value_by_setting_name();
        //---

        // get send cc
        unset($cc);
        $this->model_config->name = "email_whship_canceled_doc_cc";
        $cc_temp = $this->model_config->get_value_by_setting_name();
        $cc = explode("|",$cc_temp);
        //--

        $this->load->library('MY_phpmailer');

        // get the detail
        $doc_no_temp[] = $doc_no;
        $result_data = assign_data($this->model_tsc_in_out_bound_d->get_list_with_so($doc_no_temp));
        unset($data);
        foreach($result_data as $row){
            $data[] = array(
                "doc_no" => $row["doc_no"],
                "line_no" => $row["line_no"],
                "item_code" => $row["item_code"],
                "desc" => $row["description"],
                "qty_to_ship" => $row["qty_to_ship"],
                "so_no" => $row["so_no"],
                "cust_code" => $row["dest_no"],
                "cust_name" => $row["ship_to_name"],
            );
        }
        //---

        $body = $this->my_phpmailer->email_body_whship_canceled($doc_no,$datetime,$message,$data);
        $to = $send_to;
        $subject = "WH Shipment Canceled (".$doc_no.")";
        $from_info = "WMS TPI-MX";
        $altbody = "";
        $result = $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
    }
    //---
}

?>
