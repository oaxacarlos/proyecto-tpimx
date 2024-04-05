<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edit extends CI_Controller{

    function __construct(){
      parent::__construct();
         $this->load->model('operacion/delivery/mst/delv/delv_part','model_operacion_mst_delv_part');
         $this->load->model('operacion/delivery/mst/delv/delv_status','model_operacion_mst_delv_status');
         $this->load->model('operacion/delivery/mst/domicili','model_operacion_mst_domicili');
         $this->load->model('operacion/delivery/mst/vendor','model_operacion_mst_vendor');
         $this->load->model('operacion/delivery/mst/city','model_operacion_mst_city');
         $this->load->model('operacion/delivery/mst/driver','model_operacion_mst_driver');
         $this->load->model('operacion/delivery/mst/state','model_operacion_mst_state');
         $this->load->model('operacion/delivery/mst/payment/payment_terms','model_operacion_mst_payment_terms');
         $this->load->model('operacion/delivery/mst/payment/payment_status','model_operacion_mst_payment_status');
         $this->load->model('operacion/delivery/tsc/delivery/delivery_h','model_operacion_tsc_delivery_h');
         $this->load->model('operacion/delivery/tsc/delivery/delivery_d','model_operacion_tsc_delivery_d');
         $this->load->model('operacion/config','model_operacion_config');
         $this->load->model('operacion/delivery/tsc/doc/edited','model_operacion_doc_edited');
         $this->load->model('operacion/delivery/tsc/delivery/delivery_history','model_operacion_tsc_delivery_history');
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_folder').'delivery/edit'])){
            $this->load->view('view_home');
        }
        else{
            $status = "'1'";
            $result = $this->model_operacion_tsc_delivery_h->get_data_by_payment_status_null($status);
            if(count($result) > 0){
                $data["var_data"] = assign_data($result);
            }

            $this->load->view('operacion/delivery/edit/v_index',$data);
        }
    }
    //---

    function editdoc(){
        $this->load->view('templates/navigation');

        $doc_no = $_GET["docno"];

        $data["var_data_h"] = assign_data_one($this->model_operacion_tsc_delivery_h->get_data_by_docno($doc_no));
        $data["var_data_d"] = assign_data($this->model_operacion_tsc_delivery_d->get_data_by_docno($doc_no));
        $data["var_delv_part"] = assign_data($this->model_operacion_mst_delv_part->get_data());
        $data["var_delv_status"] = assign_data($this->model_operacion_mst_delv_status->get_data());
        $data["var_domilici"] = assign_data($this->model_operacion_mst_domicili->get_data());
        $data["var_vendor"] = assign_data($this->model_operacion_mst_vendor->get_data());
        $data["var_city"] = assign_data($this->model_operacion_mst_city->get_data());
        $data["var_driver"] = assign_data($this->model_operacion_mst_driver->get_data());
        $data["var_state"] = assign_data($this->model_operacion_mst_state->get_data());
        $data["var_payment_terms"] = assign_data($this->model_operacion_mst_payment_terms->get_data());

        $data["doc_no"] = $doc_no;

        $this->load->view('operacion/delivery/edit/v_edit',$data);
    }
    //--

    function get_invoices_whship(){
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];

        $result = $this->model_operacion_tsc_delivery_h->get_invoice_wship($date_from, $date_to);

        if(count($result) == 0){
            $data["var_invoices"] = 0;
        }
        else{
            $data["var_invoices"] = assign_data($result);
        }

        $this->load->view('operacion/delivery/edit/v_invoice_detail',$data);
    }
    //---

    function approve(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_folder').'delivery/edit/approve'])){
            $this->load->view('view_home');
        }
        else{
            $status = "'2'";
            $result = $this->model_operacion_tsc_delivery_h->get_data_by_payment_status_null($status);
            if(count($result) > 0){
                $data["var_data"] = assign_data($result);
            }

            $this->load->view('operacion/delivery/edit/v_approve',$data);
        }
    }
    //---

    function approvedoc(){
        $this->load->view('templates/navigation');

        $doc_no = $_GET["docno"];

        $data["var_data_h"] = assign_data_one($this->model_operacion_tsc_delivery_h->get_data_by_docno($doc_no));
        $data["var_data_d"] = assign_data($this->model_operacion_tsc_delivery_d->get_data_by_docno($doc_no));
        $data["var_delv_part"] = assign_data($this->model_operacion_mst_delv_part->get_data());
        $data["var_delv_status"] = assign_data($this->model_operacion_mst_delv_status->get_data());
        $data["var_domilici"] = assign_data($this->model_operacion_mst_domicili->get_data());
        $data["var_vendor"] = assign_data($this->model_operacion_mst_vendor->get_data());
        $data["var_city"] = assign_data($this->model_operacion_mst_city->get_data());
        $data["var_driver"] = assign_data($this->model_operacion_mst_driver->get_data());
        $data["var_state"] = assign_data($this->model_operacion_mst_state->get_data());
        $data["var_payment_terms"] = assign_data($this->model_operacion_mst_payment_terms->get_data());

        $data["doc_no"] = $doc_no;

        $this->load->view('operacion/delivery/edit/v_approve_doc',$data);
    }
    //---

    function reopen(){
        $doc_no = $_POST["doc_no"];

        $datetime = get_datetime_now();
        $date = get_date_now();

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id'];
        $name = $session_data['z_tpimx_name'];

        $result = $this->model_operacion_tsc_delivery_h->update_status($doc_no,"1");
        $this->model_operacion_tsc_delivery_history->insert($doc_no, $datetime, $user, "1", "ReOpen by ".$name);

        if($result){
            $response['status'] = "1";
            $response['msg'] = "The Document has been ReOpened";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //---

    function approve_process(){
        $doc_no = $_POST["doc_no"];

        $datetime = get_datetime_now();
        $date = get_date_now();

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id'];
        $name = $session_data['z_tpimx_name'];

        $result = $this->model_operacion_tsc_delivery_h->update_approved($doc_no, $datetime, $user);
        $result = $this->model_operacion_tsc_delivery_h->update_status($doc_no,"3");
        $this->model_operacion_tsc_delivery_history->insert($doc_no, $datetime, $user, "3", "Approved by ".$name);

        if($result){
            $response['status'] = "1";
            $response['msg'] = "The Document has been Approved";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //---

    function edit_process(){
        $doc_type = json_decode(stripslashes($_POST['doc_type']));
        $doc_no = json_decode(stripslashes($_POST['doc_no']));
        $doc_date = json_decode(stripslashes($_POST['doc_date']));
        $so_ref = json_decode(stripslashes($_POST['so_ref']));
        $cust_no = json_decode(stripslashes($_POST['cust_no']));
        $subtotal = json_decode(stripslashes($_POST['subtotal']));
        $total2 = json_decode(stripslashes($_POST['total2']));
        $remarks = json_decode(stripslashes($_POST['remarks']));
        $cust_name = json_decode(stripslashes($_POST['cust_name']));

        if(!isset($cust_name)){
            $cust_name = $this->get_cust_name($doc_no);
        }
        else{
            if(count($cust_name) != count($cust_no)){
                $cust_name = $this->get_cust_name($doc_no);
            }
        }

        $address = json_decode(stripslashes($_POST['address']));
        $address2 = json_decode(stripslashes($_POST['address2']));
        $city = json_decode(stripslashes($_POST['city']));
        $state2 = json_decode(stripslashes($_POST['state2']));
        $post_code = json_decode(stripslashes($_POST['post_code']));
        $country = json_decode(stripslashes($_POST['country']));
        $qty = json_decode(stripslashes($_POST['qty']));
        $required_remarks = json_decode(stripslashes($_POST['required_remarks']));
        $line_no = json_decode(stripslashes($_POST['line_no']));
        $sending_date = $_POST["sending_date"];
        $destination = $_POST["destination"];
        $state = $_POST["state"];
        $driver = $_POST["driver"];
        $vendor = $_POST["vendor"];
        $tracking_no = $_POST["tracking_no"];
        $domicili = $_POST["domicili"];
        $payment_terms = $_POST["payment_terms"];
        $delivery_status = $_POST["delivery_status"];
        $total = $_POST["total"];
        $box = $_POST["box"];
        $pallet = $_POST["pallet"];
        $remark_h = $_POST["remark_h"];
        $doc_no_h = $_POST["doc_no_h"];
        $folio = $_POST["folio"];
        $subtotal_h = $_POST['subtotal_h'];

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];
        $name = $session_data['z_tpimx_name'];

        $datetime = get_datetime_now();
        $date     = get_date_now();

        $result_h = assign_data_one($this->model_operacion_tsc_delivery_h->get_data_by_docno($doc_no_h));  // get header data
        $result_d = assign_data($this->model_operacion_tsc_delivery_d->get_data_by_docno($doc_no_h));  // get detail data

        $result = $this->edit_delivery_header($result_h,$doc_no_h,$sending_date, $destination, $state, $driver, $vendor, $tracking_no, $box, $pallet, $domicili, $payment_terms, $delivery_status, $total, $created_user, $remark_h, $datetime, $folio, $subtotal_h);

        $this->model_operacion_tsc_delivery_h->update_status($doc_no_h,"2");

        $this->edit_delivery_detail($result_d,$doc_no_h, $doc_no, $doc_date, $so_ref, $cust_no, $subtotal, $total2, $remarks, $cust_name, $address, $address2, $city, $state2, $post_code, $country, $qty,$created_user, $doc_type, $required_remarks, $datetime, $line_no);

        if($result){
            $this->model_operacion_tsc_delivery_history->insert($doc_no_h, $datetime, $created_user, "1", "Edited by ".$name);
            $response['status'] = "1";
            $response['msg'] = "The Delivery Document No = ".$doc_no_h." has been Edited";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //--

    function edit_delivery_header($result_h,$doc_no_h,$delv_date, $destination, $state, $driver, $vendor_no, $tracking_no, $box, $pallet, $domicili, $payment_term, $delv_status, $total, $created_by, $remarks, $datetime, $folio, $subtotal){

        // checking the different
        unset($data_diff);
        $check_if_as_diff = 0;

        if($result_h["delv_date"] != $delv_date){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "sending date", $result_h["delv_date"], $delv_date, "",$data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["destination"] != $destination){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "destination",$result_h["destination"], $destination, "", $data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["state"] != $state){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "state", $result_h["state"], $state, "", $data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["driver"] != $driver){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "driver", $result_h["driver"],$driver, "", $data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["vendor_no"] != $vendor_no){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "vendor no", $result_h["vendor_no"] ,$vendor_no, "", $data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["tracking_no"] != $tracking_no){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "tracking no", $result_h["tracking_no"] ,$tracking_no, "",$data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["box"] != $box){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "box", $result_h["box"] ,$box,"",$data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["pallet"] != $pallet){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "pallet", $result_h["pallet"],$pallet, "", $data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["domicili"] != $domicili){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "domicili", $result_h["domicili"],$domicili, "", $data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["payment_term"] != $payment_term){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "payment_term", $result_h["payment_term"] ,$payment_term, "",$data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["delv_status"] != $delv_status){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "delivery status", $delv_status ,$result_h["delv_status"], "",$data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["folio"] != $folio){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "folio", $result_h["folio"] ,$folio, "",$data_diff);
            $check_if_as_diff = 1;
        }

        if($result_h["total"] != $total){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "total", $result_h["total"] ,$total, "",$data_diff);
            $check_if_as_diff = 1;

            /*$this->model_operacion_config->name = "tax";
            $tax_percentage = $this->model_operacion_config->get_value_by_setting_name();

            $subtotal = $total / (1+$tax_percentage);
            $tax = $subtotal * $tax_percentage;*/

            $tax = 0;

            if($result_h["subtotal"] != $subtotal){
                $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "subtotal", $result_h["subtotal"], $subtotal, "",$data_diff);
            }

            if($result_h["tax"] != $tax){
                $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "tax", $result_h["tax"], $tax, "",$data_diff);
            }
        }
        else{
            /*$this->model_operacion_config->name = "tax";
            $tax_percentage = $this->model_operacion_config->get_value_by_setting_name();

            $subtotal = $total / (1+$tax_percentage);
            $tax = $subtotal * $tax_percentage;
            */

            $tax = 0;
        }

        if($result_h["remark1"] != $remarks){
            $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "remarks", $result_h["remark1"], $remarks, "",$data_diff);
            $check_if_as_diff = 1;
        }
        //---

        $result = $this->model_operacion_tsc_delivery_h->update($doc_no_h, $delv_date, $destination, $state, $driver, $vendor_no, $tracking_no, $box, $pallet, $domicili, $payment_term, $subtotal, $delv_status, $total, $tax, $created_by, $remarks, $datetime, $folio);

        if($check_if_as_diff == 1) $this->model_operacion_doc_edited->insert_v2($data_diff);

        return $result;
    }
    //--

    function edit_delivery_detail($result_d,$doc_no_h, $doc_no, $doc_date, $so_ref, $cust_no, $subtotal, $total, $remarks, $cust_name, $address, $address2, $city, $state, $post_code, $country, $qty, $created_by, $doc_type, $required_remarks, $datetime, $line_no){

        //unset($data_add);
        //unset($data_edit);
        //unset($data_diff);
        //unset($data_deleted);
        $check_if_as_diff = 0;

        // check edit and add
        for($i=0; $i<count($doc_no); $i++){

            // check if data exist
            $check_if_exist = 0;
            $k=0;
            foreach($result_d as $row){
                if($doc_no[$i] == $row["invc_doc_no"] && $line_no[$i]==$row["line_no"]){
                    $check_if_exist = 1;
                    $edit_row = $k;
                }
                $k++;
            }
            //---

            if($check_if_exist == 0){
                $data_add[] = $i;
                $check_if_as_diff = 1;
            }
            else{
              debug($result_d["remark1"]." = ".$remarks[$edit_row]);
              if($result_d["remark1"] != $remarks[$edit_row]){
                  $data_edit[] = $i;
                  $data_edit2[] = $remarks[$edit_row];
                  $check_if_as_diff = 1;
              }
              //---
            }
        }
        //---

        // check deleted
        $k=0;
        foreach($result_d as $row){

            $check = 0;
            for($i=0; $i<count($doc_no); $i++){

                if($row["invc_doc_no"]==$doc_no[$i] && $row["line_no"]==$line_no[$i]){
                    $check = 1;
                }
            }

            if($check == 0){
                $data_deleted[] = $k;
                $check_if_as_diff = 1;
            }

            $k++;
        }
        //---

        // update doc edited
        if(isset($data_edit)){
            if(count($data_edit) > 0){
              for($i=0;$i<count($data_edit);$i++){
                  $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "doc_no", $data_edit2[$i], $remarks[$data_edit[$i]], "doc_no=".$doc_no[$data_edit[$i]]." | line_no=".$line_no[$data_edit[$i]].", edit REMARKS",$data_diff);
              }
            }

        }
        //---

        // delete doc edited
        if(isset($data_deleted)){
            if(count($data_deleted) > 0){
              foreach($data_deleted as $row){
                  $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h,"doc_no" , "", "", "doc_no=".$result_d[$row]["doc_no"]." | line_no=".$result_d[$row]["line_no"].", deleted",$data_diff);
              }
            }

        }
        //---

        // add doc edited
        if(isset($data_add)){
            if(count($data_add) > 0){
              foreach($data_add as $row){
                  $data_diff = $this->add_edit_doc($datetime, $created_by, $doc_no_h, "doc_no" , "", "", "doc_no=".$doc_no[$row].", add new",$data_diff);
              }
            }

        }
        //---

        $this->model_operacion_tsc_delivery_d->delete_doc_no($doc_no_h);
        $this->model_operacion_tsc_delivery_d->insert($doc_no_h, $doc_no, $doc_date, $so_ref, $cust_no, $subtotal, $total, $remarks, $cust_name, $address, $address2, $city, $state, $post_code, $country, $qty, $created_by, $datetime, $doc_type, $required_remarks);

        if($check_if_as_diff == 1) $this->model_operacion_doc_edited->insert_v2($data_diff);
    }
    //---

    function add_edit_doc($datetime, $user, $doc_no, $field, $from, $to, $remark,$data){

        $data[] = array(
            "created_at"  => $datetime,
            "created_by"  => $user,
            "doc_no"      => $doc_no,
            "field"       => $field,
            "from"        => $from,
            "to"          => $to,
            "remark"      => $remark,
        );

        return $data;
    }
    //---

    function arrived(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_folder').'delivery/edit/arrived'])){
            $this->load->view('view_home');
        }
        else{
            $status = "'3'";
            $result = $this->model_operacion_tsc_delivery_h->get_data_by_payment_status_null($status);
            if(count($result) > 0){
                $data["var_data"] = assign_data($result);
            }

            $this->load->view('operacion/delivery/edit/v_arrived',$data);
        }
    }
    //---

    function arriveddoc(){
        $this->load->view('templates/navigation');

        $doc_no = $_GET["docno"];

        $data["var_data_h"] = assign_data_one($this->model_operacion_tsc_delivery_h->get_data_by_docno($doc_no));
        $data["var_data_d"] = assign_data($this->model_operacion_tsc_delivery_d->get_data_by_docno($doc_no));
        $data["var_delv_part"] = assign_data($this->model_operacion_mst_delv_part->get_data());
        $data["var_delv_status"] = assign_data($this->model_operacion_mst_delv_status->get_data());
        $data["var_domilici"] = assign_data($this->model_operacion_mst_domicili->get_data());
        $data["var_vendor"] = assign_data($this->model_operacion_mst_vendor->get_data());
        $data["var_city"] = assign_data($this->model_operacion_mst_city->get_data());
        $data["var_driver"] = assign_data($this->model_operacion_mst_driver->get_data());
        $data["var_state"] = assign_data($this->model_operacion_mst_state->get_data());
        $data["var_payment_terms"] = assign_data($this->model_operacion_mst_payment_terms->get_data());

        $data["doc_no"] = $doc_no;

        $this->load->view('operacion/delivery/edit/v_arrived_doc',$data);
    }
    //---

    function arrived_process(){
        $doc_no = $_POST["doc_no"];
        $delivery_status = $_POST["delv_status"];
        $received_date = $_POST["received_date"];
        $received_person = $_POST["received_person"];
        $vendor_no = $_POST["vendor_no"];

        $datetime = get_datetime_now();
        $date = get_date_now();

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id'];
        $name = $session_data['z_tpimx_name'];

        if($received_date =='') $this->model_operacion_tsc_delivery_h->update_arrived($doc_no, $delivery_status, $received_date, $received_person);
        else $this->model_operacion_tsc_delivery_h->update_arrived($doc_no, "ENTREGADO", $received_date, $received_person);

        $result = $this->model_operacion_tsc_delivery_h->update_status($doc_no,"5");
        $this->model_operacion_tsc_delivery_history->insert($doc_no, $datetime, $user, "6", "Updated Arriving by ".$name);

        // 2023-07-13
        if($vendor_no == "CLIENTE"){
            $result = $this->model_operacion_tsc_delivery_h->update_status($doc_no,"7");
            $this->model_operacion_tsc_delivery_history->insert($doc_no[$i], $datetime, $user, "7", "Finished by ".$name); // 2023-07-07
        }
        //--

        if($result){
            $response['status'] = "1";
            $response['msg'] = "Received Data on this Document has been updated";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //--

    function get_cust_name($doc_no){
        unset($result);
        foreach($doc_no as $row){
            $result[] = $this->model_operacion_tsc_delivery_h->get_cust_name($row);
        }

        return $result;
    }
    //--

    function cancel_doc(){
        $doc_no = $_POST["doc_no"];
        $message = $_POST["message"];

        $datetime = get_datetime_now();
        $date = get_date_now();

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id'];
        $name = $session_data['z_tpimx_name'];

        $result = $this->model_operacion_tsc_delivery_h->cancel_doc($doc_no, $datetime, $user, $message);
        $result = $this->model_operacion_tsc_delivery_h->update_status($doc_no,"4");
        $this->model_operacion_tsc_delivery_history->insert($doc_no, $datetime, $user, "4", "Canceled by ".$name);

        if($result){
            $response['status'] = "1";
            $response['msg'] = "The Document has been Canceled";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //--

    // 2023-07-10
    function receivedate(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_folder').'delivery/edit/receivedate'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('operacion/delivery/edit/v_receivedate',$data);
        }
    }
    //---

    // 2023-07-10
    function receivedate_data(){
        $doc_no = $_POST["docno"];

        $data["var_data_h"] = assign_data_one($this->model_operacion_tsc_delivery_h->get_data_by_docno($doc_no));
        $data["var_data_d"] = assign_data($this->model_operacion_tsc_delivery_d->get_data_by_docno($doc_no));
        $data["var_delv_part"] = assign_data($this->model_operacion_mst_delv_part->get_data());
        $data["var_delv_status"] = assign_data($this->model_operacion_mst_delv_status->get_data());
        $data["var_domilici"] = assign_data($this->model_operacion_mst_domicili->get_data());
        $data["var_vendor"] = assign_data($this->model_operacion_mst_vendor->get_data());
        $data["var_city"] = assign_data($this->model_operacion_mst_city->get_data());
        $data["var_driver"] = assign_data($this->model_operacion_mst_driver->get_data());
        $data["var_state"] = assign_data($this->model_operacion_mst_state->get_data());
        $data["var_payment_terms"] = assign_data($this->model_operacion_mst_payment_terms->get_data());

        $data["doc_no"] = $doc_no;

        $this->load->view('operacion/delivery/edit/v_receivedate_data',$data);
    }
    //---

    // 2023-07-10
    function receivedate_process(){
        $doc_no = $_POST["doc_no"];
        $received_date = $_POST["received_date"];

        $datetime = get_datetime_now();
        $date = get_date_now();

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id'];
        $name = $session_data['z_tpimx_name'];

        $result = $this->model_operacion_tsc_delivery_h->update_received_date($doc_no, $received_date);

        $this->model_operacion_tsc_delivery_history->insert($doc_no, $datetime, $user, "", "Updated Received Date Only by ".$name);

        if($result){
            $response['status'] = "1";
            $response['msg'] = "Received Data on this Document has been updated";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //---
}
