<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Whrcpt extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->database();

    $this->load->model('model_zlog','',TRUE);
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'whrcpt'])){
          $this->load->view('view_home');
      }
      else{
          $this->model_zlog->insert("Warehouse - InBound Navision"); // insert log

          $this->load->view('wms/inbound/v_whrcpt_list');
      }
  }

  //---

  function get_whrcpt_list_h(){
      $this->load->model('model_inbound','',TRUE);
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      // get header
      $result = $this->model_inbound->whrcpt_list_h();
      unset($data['var_whrcpt_list_h']);
      if($result) $data['var_whrcpt_list_h'] = assign_data($result);
      //---

      if($result){
          // check if already in tsc_in_out_bound_h
          unset($doc_no);
          foreach($data['var_whrcpt_list_h'] as $row){
              $doc_no[] = $row['no'];
          }
          $result_in_out_bound_h = $this->model_tsc_in_out_bound_h->check_h_by_doc_no($doc_no);
          //----

          // remove data already proceed to in_out_bound_h
          if(count($result_in_out_bound_h ) > 0){
              $data['var_whrcpt_list_h'] = $this->remove_already_process_from_whsrcpt_to_in_out_bound($data['var_whrcpt_list_h'], $result_in_out_bound_h);
          }
          //----
      }

      $this->load->view('wms/inbound/v_whrcpt_list_data',$data);
  }
  //---

  function get_whrcpt_list_d(){
      $id      = $_POST['id'];
      $return_link = $_POST['link'];

      $this->load->model('model_inbound','',TRUE);

      // get line
      unset($doc_no);
      $doc_no[] = $id;
      $result = $this->model_inbound->whrcpt_list_d($doc_no);
      $data['var_whrcpt_list_d'] = assign_data($result);
      //----

      $this->load->view($return_link,$data);
  }
  //----

  function transfer_whrcpt_to_received(){
      $this->model_zlog->insert("Warehouse - Transfer WH Receipt to Received"); // insert log

      $id = $_POST['id'];
      $message = $_POST['message'];

      $this->load->model('model_inbound','',TRUE);
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      $exist = $this->model_inbound->check_tsc_in_out_bound_h_existing($id);

      if($exist > 0){ // check if already proceed
          $response['status'] = "0";
          $response['msg'] = "The Data has been process by another user";
          echo json_encode($response);
      }
      else{
          // pull header from navision
          $result = $this->model_inbound->whrcpt_list_h_by_no($id);
          $data['var_whrcpt_list_h'] = assign_data_one($result);
          //---

          // pull detail from navision
          unset($doc_no);
          $doc_no[] = $id;
          $result = $this->model_inbound->whrcpt_list_d($doc_no);
          $data['var_whrcpt_list_d'] = assign_data($result);
          //---

          $result_h = $this->insert_tsc_in_out_bound_h($data['var_whrcpt_list_h'],$message); // insert to tsc_in_out_bound_h
          $result_d = $this->insert_tsc_in_out_bound_d($data['var_whrcpt_list_d']); // insert to tsc_in_out_bound_d

          // update stock to extraction
          $this->update_extraction($data['var_whrcpt_list_d']);
          //---

          // update message
          $this->model_tsc_in_out_bound_h->doc_no = $id;
          $this->model_tsc_in_out_bound_h->text = $message;
          $this->model_tsc_in_out_bound_h->update_message();
          //---

          if($result_h && $result_d){
              $response['status'] = "1";
              $response['msg'] = "The WHS Receipt has been transfered to Warehouse";
              echo json_encode($response);
          }
          else{
            $response['status'] = "0";
            $response['msg'] = "Error";
            echo json_encode($response);
          }
      }

  }
  //---

  function insert_tsc_in_out_bound_h($data,$message){
      // declare
      $datetime = get_datetime_now();
      $date = get_date_now();
      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);
      //---

      // check if transfer from warehouse
      $check_transfer_from_wh = $this->check_if_get_from_warehouse($data);
      //---

      $this->model_tsc_in_out_bound_h->doc_no = $data["no"];
      $this->model_tsc_in_out_bound_h->doc_datetime = $datetime;
      $this->model_tsc_in_out_bound_h->created_datetime = $datetime;
      $this->model_tsc_in_out_bound_h->doc_type = "1";
      $this->model_tsc_in_out_bound_h->doc_location_code = $data["loc_code"];
      $this->model_tsc_in_out_bound_h->month_end = 0;
      $this->model_tsc_in_out_bound_h->created_user = $session_data['z_tpimx_user_id'];
      $this->model_tsc_in_out_bound_h->external_document = "";
      $this->model_tsc_in_out_bound_h->status = "1";
      $this->model_tsc_in_out_bound_h->doc_date = $date;
      $this->model_tsc_in_out_bound_h->doc_posting_date = $data["posting_date"];
      $this->model_tsc_in_out_bound_h->external_document = $data["ext_doc_no"];
      $this->model_tsc_in_out_bound_h->transfer_from_wh = $check_transfer_from_wh["shipment_no"];
      $this->model_tsc_in_out_bound_h->from_wh = $check_transfer_from_wh["wh_no"];
      $result = $this->model_tsc_in_out_bound_h->insert_h();

      // insert doc history
      $this->model_tsc_doc_history->insert($data["no"],"","","1","",$datetime, $message,"");
      //--

      if($result) return true; else return false;
  }
  //---

  function insert_tsc_in_out_bound_d($data){
      // declare
      $datetime = get_datetime_now();
      $date = get_date_now();
      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $this->load->model('model_tsc_in_out_bound_d','',TRUE);
      //---

      /*foreach($data as $row){
          $this->model_tsc_in_out_bound_d->doc_no = $row["no"];
          $this->model_tsc_in_out_bound_d->line_no = $row["line_no"];
          $this->model_tsc_in_out_bound_d->src_location_code = $row["location_code"];
          $this->model_tsc_in_out_bound_d->src_no = $row["src_no"];
          $this->model_tsc_in_out_bound_d->src_line_no = $row["src_line_no"];
          $this->model_tsc_in_out_bound_d->item_code = $row["item_no"];
          $this->model_tsc_in_out_bound_d->qty = $row["qty_to_receive"];
          $this->model_tsc_in_out_bound_d->uom = $row["uom"];
          $this->model_tsc_in_out_bound_d->description = $row["description"];
          $this->model_tsc_in_out_bound_d->dest_no = "";
          $this->model_tsc_in_out_bound_d->master_barcode = "1"; // master barcode 2023-01-17
          $this->model_tsc_in_out_bound_d->valuee = "0"; // valuee 2023-02-13
          $this->model_tsc_in_out_bound_d->valuee_per_pcs = "0"; // valuee 2023-02-13
          $result = $this->model_tsc_in_out_bound_d->insert_d();
      }*/

      unset($data2);
      foreach($data as $row){

        $data2[] = array(
            "doc_no" => $row["no"],
            "line_no" => $row["line_no"],
            "src_location_code" => $row["location_code"],
            "src_no" => $row["src_no"],
            "src_line_no" => $row["src_line_no"],
            "item_code" => $row["item_no"],
            "uom" => $row["uom"],
            "description" => $row["description"],
            "qty" => $row["qty_to_receive"],
            "dest_no" => "",
            "master_barcode" => 1, // 2023-01-17 master barcode
            "valuee" => 0, // valuee 2023-01-30
            "valuee_per_pcs" => 0, // valuee 2023-01-30
        );
      }

      $result = $this->model_tsc_in_out_bound_d->insert_d_ver2($data2);

      if($result) return true; else false;
  }
  //---

  function remove_already_process_from_whsrcpt_to_in_out_bound($whrcpt_h, $in_out_bound_h){
    $temp = $whrcpt_h;
    unset($whrcpt_h);
    foreach($temp as $key => $row){
        $is_there = 0;
        foreach($in_out_bound_h as $row2){
            if($row['no'] == $row2['doc_no']){
                $is_there = 1; break;
            }
        }

        if($is_there == 0) $whrcpt_h[] = $temp[$key];
    }

    return $whrcpt_h;
  }
  //----

  function update_extraction($data){
      $this->load->model('model_tsc_item_invt','',TRUE);

      foreach($data as $row){
          $this->model_tsc_item_invt->extraction = $row["qty_to_receive"];
          $this->model_tsc_item_invt->available = 0;
          $this->model_tsc_item_invt->item_code = $row["item_no"];
          $this->model_tsc_item_invt->update_invt3();
      }
  }
  //---

  function check_if_get_from_warehouse($data){

      $this->load->model('model_config','',TRUE);

      // get the TO number from warehouse receipt line
      $source_no = $this->model_inbound->get_to_no_from_whse_receipt_line($data["no"]);

      // check the TO is transfer between warehouse in transfer header
      $transfer_from = $this->model_inbound->get_transfer_from($source_no);

      $this->model_config->name = "wh_transfer_no_gen_sn";
      $result_config = $this->model_config->get_value_by_setting_name();
      //$new_result_config = explode(",",$result_config);
      $new_result_config = explode("|",$result_config);

      $check = 0;
      for($i=0;$i<count($new_result_config);$i++){
          $temp = explode("-", $new_result_config[$i]);
          if($temp[0]==$transfer_from && $temp[1]==$data["loc_code"]){
              $check = 1;
          }
      }

      if($check == 0){
          $result["transfer_wh"] = 0;
      }
      else{
          $result["transfer_wh"] = 1;

          // get warehouse shipment number from posted whse shhipment line with TO number
          $shipment_no = $this->model_inbound->get_whse_shipment_no($source_no);
          $result["shipment_no"] = $shipment_no;
          $result["wh_no"] = $transfer_from;
      }

      return $result;
  }
  //--
}

?>
