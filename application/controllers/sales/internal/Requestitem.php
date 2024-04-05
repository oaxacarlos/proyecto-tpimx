<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Requestitem extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model('internal/itemrequest/model_tsc_in_out_bound_h_temp','model_tsc_in_out_bound_h_temp');
    $this->load->model('internal/itemrequest/model_tsc_in_out_bound_d_temp','model_tsc_in_out_bound_d_temp');
    $this->load->model('internal/model_mst_approval_user','model_mst_approval_user');
    $this->load->model('model_mst_location','',TRUE);
    $this->load->model('model_mst_item','',TRUE);
    $this->load->model('model_jobs','',TRUE);
    $this->load->model('model_config','',TRUE);
    $this->load->model('model_tsc_doc_history','',TRUE);
    $this->load->model('model_mst_ship_to','',TRUE);
    $this->load->model('model_tsc_in_out_bound_h','',TRUE);
    $this->load->model('model_tsc_in_out_bound_d','',TRUE);
    $this->load->model('model_tsc_so','',TRUE);
  }
  //--

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'internal/requestitem'])){
          $this->load->view('view_home');
      }
      else{
          $data["var_location"] = assign_data($this->model_mst_location->get_data()) ; // get warehouse list
          $data["var_item"] = assign_data($this->model_mst_item->get_data());           // get item
          $data["var_ship_to"] = assign_data($this->model_mst_ship_to->get_list_without_mecanica_old()); // get ship to

          $this->load->view('sales/internal/requestitem/new/v_index',$data);
      }
  }
  //---

  function create_new(){
      $item = json_decode(stripslashes($_POST['item']));
      $name = json_decode(stripslashes($_POST['name']));
      $uom = json_decode(stripslashes($_POST['uom']));
      $loc = json_decode(stripslashes($_POST['loc']));
      $src_no = json_decode(stripslashes($_POST['src_no']));
      $src_line_no = json_decode(stripslashes($_POST['src_line_no']));
      $qty = json_decode(stripslashes($_POST['qty']));
      $ext_doc = $_POST["ext_doc"];
      $h_loc = $_POST["h_loc"];
      $address = $_POST["address"];

      $doc_type = "2";

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $created_user = $session_data['z_tpimx_user_id'];
      $doc_no = $this->insert_tsc_in_out_bound_h_temp($h_loc,$created_user,$ext_doc, $doc_type,$address); // insert header

      // address
      $new_address = explode(" | ",$address);
      //--

      // insert detail
      unset($data);
      for($i=0;$i<count($item);$i++){
          if($src_no[$i] == "") $src_no_temp = $doc_no;
          else $src_no_temp = $src_no[$i];

          $data[] = array(
            "src_no" => $src_no_temp,
            "location_code" => $loc[$i],
            "item_code" => $item[$i],
            "uom" => $uom[$i],
            "desc" => $name[$i],
            "qty" => $qty[$i],
            "src_line_no" => $src_line_no[$i],
            "dest_no" => $new_address[1],
          );
      }

      $this->insert_tsc_in_out_bound_d_temp($doc_no,$data);
      $this->insert_so($doc_no,$new_address[0],$h_loc,$new_address[1], $new_address[2], $new_address[4], $new_address[5], $new_address[6],
      $new_address[7], $new_address[9], $new_address[8], $new_address[3]);
      //--

      if($doc_no){
          $response['status'] = "1";
          $response['msg'] = "New Document has been created with No = ".$doc_no;
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //--

  function insert_tsc_in_out_bound_h_temp($loc,$user,$external_doc, $doc_type,$address){


      // get config for document no
      $this->model_config->name = "doc_temp_doc_pref";
      $prefix = $this->model_config->get_value_by_setting_name();

      $this->model_config->name = "doc_temp_doc_no";
      $last_doc_no = $this->model_config->get_value_by_setting_name();

      $new_doc_no = $last_doc_no+1;

      $this->model_config->name = "doc_temp_doc_no";
      $this->model_config->valuee = $new_doc_no;
      $this->model_config->update_value();

      $this->model_config->name = "doc_temp_doc_digit";
      $digit = $this->model_config->get_value_by_setting_name();

      $doc_no = $prefix.sprintf("%0".$digit."d", $new_doc_no);
      //---

      // insert header
      $datetime = get_datetime_now();
      $date = get_date_now();

      // split address
      $new_address = explode(" | ",$address);

      $result = $this->model_tsc_in_out_bound_h_temp->insert($doc_no, $datetime, $datetime, $doc_type, $loc, $user, $external_doc, "17", $date,"0", "1",$new_address[0], $new_address[1], $new_address[2], $new_address[4], $new_address[5], $new_address[6],
      $new_address[7], $new_address[9], $new_address[8], $new_address[3]);
      //---

      // insert doc history
      $this->model_tsc_doc_history->insert($doc_no,"","","1","",$datetime, $external_doc,"");
      //--

      if($result) return $doc_no;
      else return false;
  }
  //---

  function insert_tsc_in_out_bound_d_temp($doc_no,$data){
      $datetime = get_datetime_now();
      $date = get_date_now();

      $line_no_add = 10000;
      $line_no = 10000;

      foreach($data as $row){
          if($data["src_line_no"] == "") $src_line_no_temp = $line_no;

          $result = $this->model_tsc_in_out_bound_d_temp->insert_d($doc_no, $line_no, $row["location_code"], $row["src_no"], $src_line_no_temp, $row["item_code"], $row["uom"], $row["desc"], $row["qty"], $row["dest_no"]);
          $line_no += $line_no_add;
      }
  }
  //---

  function checking(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'internal/requestitem/checking'])){
          $this->load->view('view_home');
      }
      else{
          $data["var_in_out_h"] = assign_data($this->model_tsc_in_out_bound_h_temp->get_data("17")) ; // get warehouse list

          $this->load->view('sales/internal/requestitem/checking/v_index',$data);
      }
  }
  //---

  function checking_detail(){
      $doc_no = $_POST["doc_no"];
      $data["cust_no"] = $_POST["cust_no"];
      $data["cust_name"] = $_POST["cust_name"];
      $data["address"] = $_POST["address"];
      $data["address2"] = $_POST["address2"];
      $data["city"] = $_POST["city"];
      $data["contact"] = $_POST["contact"];
      $data["country_region_code"] = $_POST["country_region_code"];
      $data["post_code"] = $_POST["post_code"];
      $data["county"] = $_POST["county"];
      $data["status"] = $_POST["status"];
      $data["idx_row"] = $_POST["idx_row"];

      $result = $this->model_tsc_in_out_bound_d_temp->get_data_by_doc_no_stock($doc_no);
      if(count($result) > 0){
          $data["var_in_out_d"] = assign_data($result);
      }
      else $data["var_in_out_d"] = 0;

      $data["doc_no"] = $doc_no;

      $this->load->view('sales/internal/requestitem/checking/v_detail',$data);
  }
  //---

  function checking_detail_process(){
      $item_code = json_decode(stripslashes($_POST['item_code']));
      $line = json_decode(stripslashes($_POST['line']));
      $qty = json_decode(stripslashes($_POST['qty']));
      $qty_edited = json_decode(stripslashes($_POST['qty_edited']));
      $doc_no = $_POST["doc_no"];
      $message = $_POST["message"];
      $status = $_POST["status"];

      $status_existing = $this->model_tsc_in_out_bound_h_temp->get_status($doc_no);

      if($status_existing != $status){
          $result = false;
          $message_error = "The Document already proceed..";
      }
      else{
          // check if any edited qty
          $check = 0;
          unset($new_line);
          for($i=0;$i<count($line);$i++){
              if($qty[$i] != $qty_edited[$i]){
                  $new_line[] = $i;
                  $check = 1;
              }
          }
          //---

          // if need edit qty
          if($check == 1){
              for($i=0;$i<count($new_line);$i++){
                  $this->model_tsc_in_out_bound_d_temp->update_qty_edited($doc_no, $line[$new_line[$i]], $item_code[$new_line[$i]], $qty_edited[$new_line[$i]]);
              }
          }
          //---

          $status_to = $this->model_mst_approval_user->get_next_status_by_code_status($status,"ITEMTEMP");
          if(is_null($status_to)){
              $result = true;
          }
          else{
              $result = $this->model_tsc_in_out_bound_h_temp->update_status($status_to, $doc_no);
          }
      }

      if($result){
          $response['status'] = "1";
          $response['msg'] = "Your Document has been proceed to next Approval";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = $message_error;
        echo json_encode($response);
      }
  }
  //---

  function approve(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'internal/requestitem/approve'])){
        $this->load->view('view_home');
    }
    else{
        $data["var_in_out_h"] = assign_data($this->model_tsc_in_out_bound_h_temp->get_data("18")) ; // get warehouse list

        $this->load->view('sales/internal/requestitem/approve/v_index',$data);
    }
  }
  //---

  function approve_detail(){
      $doc_no = $_POST["doc_no"];
      $data["cust_no"] = $_POST["cust_no"];
      $data["cust_name"] = $_POST["cust_name"];
      $data["address"] = $_POST["address"];
      $data["address2"] = $_POST["address2"];
      $data["city"] = $_POST["city"];
      $data["contact"] = $_POST["contact"];
      $data["country_region_code"] = $_POST["country_region_code"];
      $data["post_code"] = $_POST["post_code"];
      $data["county"] = $_POST["county"];
      $data["status"] = $_POST["status"];
      $data["idx_row"] = $_POST["idx_row"];

      $result = $this->model_tsc_in_out_bound_d_temp->get_data_by_doc_no($doc_no);
      if(count($result) > 0){
          $data["var_in_out_d"] = assign_data($result);
      }
      else $data["var_in_out_d"] = 0;

      $data["doc_no"] = $doc_no;

      $this->load->view('sales/internal/requestitem/approve/v_detail',$data);
  }
  //--

  function approve_detail_process(){
      $doc_no_temp = $_POST["doc_no"];
      $message = $_POST["message"];
      $status = $_POST["status"];

      $status_existing = $this->model_tsc_in_out_bound_h_temp->get_status($doc_no_temp);
      $doc_no="";
      if($status_existing != $status){
          $result = false;
          $message_error = "The Document already proceed..";
      }
      else{
          $result_h = assign_data_one($this->model_tsc_in_out_bound_h_temp->get_data_by_doc_no($doc_no_temp));
          $result_d = $this->model_tsc_in_out_bound_d_temp->get_data_by_doc_no($doc_no_temp);

          $doc_no = $this->insert_tsc_in_out_bound_h($result_h["doc_location_code"],$result_h["created_user"],$result_h["external_document"], $result_h["doc_type"]); // insert header

          // insert detail
          unset($data);
          foreach($result_d as $row){
              if($src_no[$i] == "") $src_no_temp = $doc_no;
              //else $src_no_temp = $src_no[$i];

              if($row["qty_edited"] > 0){
                $data[] = array(
                  "src_no" => $row["src_no"],
                  "location_code" => $row["src_location_code"],
                  "item_code" => $row["item_code"],
                  "uom" => $row["uom"],
                  "desc" => $row["description"],
                  "qty" => $row["qty_edited"],
                  "src_line_no" => $row["src_line_no"],
                  "dest_no" => $row["dest_no"],
                );
              }
          }

          $this->insert_tsc_in_out_bound_d($doc_no,$data);
          $this->insert_so($doc_no, $result_h["cust_no"], $result_h["doc_location_code"],$result_h["ship_to_code"], $result_h["name"], $result_h["address"], $result_h["address2"], $result_h["city"], $result_h["contact"],
          $result_h["country_region_code"], $result_h["post_code"], $result_h["county"]);
          //--

          $this->model_tsc_in_out_bound_h_temp->update_ref_doc($doc_no_temp, $doc_no); // update ref document

          $status_to = $this->model_mst_approval_user->get_next_status_by_code_status($status,"ITEMTEMP");
          if(is_null($status_to)){
              $result = true;
          }
          else{
              $result = $this->model_tsc_in_out_bound_h_temp->update_status($status_to, $doc_no_temp);
          }
      }

      if($doc_no){
          $response['status'] = "1";
          $response['msg'] = "The Document has been proceed to Warehouse";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //---

  // 2022-12-02
  function insert_tsc_in_out_bound_h($loc,$user,$external_doc, $doc_type){

      // get config for document no
      $this->model_config->name = "adjust_negv_doc_pref";
      $prefix = $this->model_config->get_value_by_setting_name();

      $this->model_config->name = "adjust_negv_doc_no";
      $last_doc_no = $this->model_config->get_value_by_setting_name();

      $new_doc_no = $last_doc_no+1;

      $this->model_config->name = "adjust_negv_doc_no";
      $this->model_config->valuee = $new_doc_no;
      $this->model_config->update_value();

      $this->model_config->name = "adjust_negv_doc_digit";
      $digit = $this->model_config->get_value_by_setting_name();

      $doc_no = $prefix.sprintf("%0".$digit."d", $new_doc_no);
      //---

      // insert header
      $datetime = get_datetime_now();
      $date = get_date_now();

      $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
      $this->model_tsc_in_out_bound_h->doc_datetime = $datetime;
      $this->model_tsc_in_out_bound_h->created_datetime = $datetime;
      $this->model_tsc_in_out_bound_h->doc_type = $doc_type;
      $this->model_tsc_in_out_bound_h->doc_location_code = $loc;
      $this->model_tsc_in_out_bound_h->month_end = 0;
      $this->model_tsc_in_out_bound_h->created_user = $user;
      $this->model_tsc_in_out_bound_h->status = "1";
      $this->model_tsc_in_out_bound_h->doc_date = $date;
      $this->model_tsc_in_out_bound_h->doc_posting_date = $datetime;
      $this->model_tsc_in_out_bound_h->external_document = $external_doc;
      $result = $this->model_tsc_in_out_bound_h->insert_h();
      //---

      // insert doc history
      $this->model_tsc_doc_history->insert($doc_no,"","","1","",$datetime, $external_doc,"");
      //--

      if($result) return $doc_no;
      else return false;
  }
  //---

  // 2022-12-02
  function insert_tsc_in_out_bound_d($doc_no,$data){
      $datetime = get_datetime_now();
      $date = get_date_now();

      $line_no_add = 10000;
      $line_no = 10000;

      foreach($data as $row){
          if($data["src_line_no"] == "") $src_line_no_temp = $line_no;

          $this->model_tsc_in_out_bound_d->doc_no = $doc_no;
          $this->model_tsc_in_out_bound_d->line_no = $line_no;
          $this->model_tsc_in_out_bound_d->src_location_code = $row["location_code"];
          $this->model_tsc_in_out_bound_d->src_no = $row["src_no"];
          $this->model_tsc_in_out_bound_d->src_line_no = $src_line_no_temp;
          $this->model_tsc_in_out_bound_d->item_code = $row["item_code"];
          $this->model_tsc_in_out_bound_d->qty = $row["qty"];
          $this->model_tsc_in_out_bound_d->uom = $row["uom"];
          $this->model_tsc_in_out_bound_d->description = $row["desc"];
          $this->model_tsc_in_out_bound_d->dest_no = $row["dest_no"];
          $this->model_tsc_in_out_bound_d->qty_to_ship = $row["qty"];
          $this->model_tsc_in_out_bound_d->qty_outstanding = $row["qty"];
          $this->model_tsc_in_out_bound_d->qty_to_picked = 0;
          $this->model_tsc_in_out_bound_d->qty_to_packed = 0;
          $this->model_tsc_in_out_bound_d->qty_packed_outstanding = $row["qty"];
          $this->model_tsc_in_out_bound_d->master_barcode = 0; // 2023-01-18 master barcode
          $this->model_tsc_in_out_bound_d->valuee = 0; // valuee 2023-01-30
          $this->model_tsc_in_out_bound_d->valuee_per_pcs = 0; // valuee 2023-01-30
          $result = $this->model_tsc_in_out_bound_d->insert_d();
          $line_no += $line_no_add;
      }
  }
  //---

  // 2023-01-13
  function insert_so($doc_no, $customer, $loc, $ship_to_code, $name, $address, $address2, $city, $contact, $ctry_region_code, $post_code, $county){

      $this->model_tsc_so->so_no = $doc_no;
      $this->model_tsc_so->location_code = $loc;
      $this->model_tsc_so->sell_cust_no = $customer;
      $this->model_tsc_so->bill_cust_no = $customer;
      $this->model_tsc_so->bill_cust_name = $name;
      $this->model_tsc_so->bill_to_addr = $address;
      $this->model_tsc_so->bill_to_addr2 = $address2;
      $this->model_tsc_so->bill_to_city = $city;
      $this->model_tsc_so->bill_to_contact = $contact ;
      $this->model_tsc_so->bill_to_post_code = $post_code;
      $this->model_tsc_so->bill_to_county = $county;
      $this->model_tsc_so->bill_to_ctry_region_code = $ctry_region_code;
      $this->model_tsc_so->ship_to_name = $name;
      $this->model_tsc_so->ship_to_addr = $address;
      $this->model_tsc_so->ship_to_addr2 = $address2;
      $this->model_tsc_so->ship_to_city = $city;
      $this->model_tsc_so->ship_to_contact = $contact;
      $this->model_tsc_so->ship_to_post_code = $post_code;
      $this->model_tsc_so->ship_to_county = $county;
      $this->model_tsc_so->ship_to_ctry_region_code = $ctry_region_code;
      $this->model_tsc_so->sell_to_cust_name = $name;
      $this->model_tsc_so->sell_to_cust_addr = $address;
      $this->model_tsc_so->sell_to_cust_addr2 = $address2;
      $this->model_tsc_so->sell_to_city = $city;
      $this->model_tsc_so->sell_to_contact = $contact;
      $this->model_tsc_so->sell_to_post_code = $post_code;
      $this->model_tsc_so->sell_to_county = $county;
      $this->model_tsc_so->sell_to_ctry_code = $ctry_region_code;
      $result = $this->model_tsc_so->insert();

      return $result;
  }
  //--

  function cancel_doc(){
      $doc_no = $_POST["doc_no"];
      $message = $_POST["message"];

      $datetime = get_datetime_now();

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];

      $result = $this->model_tsc_in_out_bound_h_temp->cancel_doc($doc_no, $datetime, $user, $message);

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
  //---

}

?>
