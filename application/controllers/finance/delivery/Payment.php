<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Payment extends CI_Controller{

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

      if(!isset($_SESSION['menus_list_user'][$this->config->item('finance_folder').'delivery/payment'])){
          $this->load->view('view_home');
      }
      else{
          $status = "'5'";
          $result = $this->model_operacion_tsc_delivery_h->get_data_by_payment_status_null($status);
          if(count($result) > 0){
              $data["var_data"] = assign_data($result);
          }

          $this->load->view('finance/delivery/payment/v_index',$data);
      }
  }
  //---

  function edit(){
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

      $this->load->view('finance/delivery/payment/v_edit',$data);
  }
  //---

  function update(){
      $doc_no = $_POST["doc_no"];
      $invc_vendor_no = $_POST["invc_vendor_no"];
      $invc_vendor_date = $_POST["invc_vendor_date"];
      $invc_vendor_subtotal = $_POST["invc_vendor_subtotal"];
      $invc_vendor_total = $_POST["invc_vendor_total"];
      $invc_vendor_remarks = $_POST["invc_vendor_remarks"];
      $invc_payment_date = $_POST["invc_payment_date"];
      $invc_vendor_uuid = $_POST["invc_vendor_uuid"];

      $datetime = get_datetime_now();
      $date = get_date_now();

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];
      $name = $session_data['z_tpimx_name'];

      $result = $this->model_operacion_tsc_delivery_h->payment_update($doc_no, "", $invc_payment_date, $invc_vendor_no, $invc_vendor_date, $invc_vendor_subtotal, $invc_vendor_total,$invc_vendor_remarks, $datetime, $user, $invc_vendor_uuid); // 2023-07-07

      $result = $this->model_operacion_tsc_delivery_h->update_status($doc_no,"8");

      //$this->model_operacion_tsc_delivery_history->insert($doc_no, $datetime, $user, "7", "Payment updated and Finished by ".$name);

      $this->model_operacion_tsc_delivery_history->insert($doc_no, $datetime, $user, "8", "Payment updated by ".$name); // 2023-07-07


      if($result){
          $response['status'] = "1";
          $response['msg'] = "The Document has been Updated";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //---

  function approve(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('finance_folder').'delivery/payment/approve'])){
          $this->load->view('view_home');
      }
      else{
          $status = "'8'";
          $result = $this->model_operacion_tsc_delivery_h->get_data_by_payment_status_null_with_percentage($status);
          if(count($result) > 0){
              $data["var_data"] = assign_data($result);
          }

          $this->load->view('finance/delivery/payment/v_approve',$data);
      }
  }
  //---

  function approve_updated(){
      $doc_no = json_decode(stripslashes($_POST['id']));
      $payment_approve_date = $_POST["payment_approve_date"];

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];
      $name = $session_data['z_tpimx_name'];

      $datetime = get_datetime_now();

      for($i=0;$i<count($doc_no);$i++){
          $result = $this->model_operacion_tsc_delivery_h->payment_update2($doc_no[$i], "PAID", $payment_approve_date, $user);
          $result = $this->model_operacion_tsc_delivery_h->update_status($doc_no[$i],"7");
          $this->model_operacion_tsc_delivery_history->insert($doc_no[$i], $datetime, $user, "7", "Payment Approve and Finished by ".$name); // 2023-07-07
      }

      if($result){
          $response['status'] = "1";
          $response['msg'] = "The Document has been Updated";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //---

  // 2023-07-13
  function detail(){
      $doc_no = $_POST["id"];

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

      $this->load->view('finance/delivery/payment/v_detail',$data);
  }
  //---

  // 2023-07-21
  function update2(){
      $doc_no = $_POST["doc_no"];
      $invc_vendor_no = $_POST["invc_vendor_no"];
      $invc_vendor_date = $_POST["invc_vendor_date"];
      $invc_vendor_subtotal = $_POST["invc_vendor_subtotal"];
      $invc_vendor_total = $_POST["invc_vendor_total"];
      $invc_vendor_remarks = $_POST["invc_vendor_remarks"];
      $invc_payment_date = $_POST["invc_payment_date"];
      $invc_vendor_uuid = $_POST["invc_vendor_uuid"];

      $datetime = get_datetime_now();
      $date = get_date_now();

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];
      $name = $session_data['z_tpimx_name'];

      $result = $this->model_operacion_tsc_delivery_h->payment_update($doc_no, "", $invc_payment_date, $invc_vendor_no, $invc_vendor_date, $invc_vendor_subtotal, $invc_vendor_total,$invc_vendor_remarks, $datetime, $user, $invc_vendor_uuid);

      $result = $this->model_operacion_tsc_delivery_h->update_status($doc_no,"8");

      //$this->model_operacion_tsc_delivery_history->insert($doc_no, $datetime, $user, "7", "Payment updated and Finished by ".$name);

      $this->model_operacion_tsc_delivery_history->insert($doc_no, $datetime, $user, "", "Payment updated by ".$name); // 2023-07-07


      if($result){
          $response['status'] = "1";
          $response['msg'] = "The Document has been Updated";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //---

  //2023-11-06
  function upload(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('finance_folder').'delivery/payment/upload'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('finance/delivery/paymentupload/v_index',$data);
      }
  }
  //---

  //2023-11-06
  function upload_file(){

      $src = $_FILES['file']['tmp_name'];
      //$itr_code = $_POST['itr_code'];

      $target_file = $this->config->item('finance_payment');

      $temp = explode(".", $_FILES["file"]["name"]);
      $newfilename = round(microtime(true)) . '.' . end($temp);

      $targ = $target_file.$newfilename;
      $result["status"] = move_uploaded_file($src, $targ);

      if($result["status"] == 1) $result["filename"] = $newfilename;

      echo json_encode($result);

  }
  //---------------------

  //2023-11-06
  function upload_file_checking(){

      $file = $_POST["attachment"];
      $target_file = $this->config->item('finance_payment');

      $open = fopen($target_file.$file, "r");

      while (($data_temp = fgetcsv($open, 1000, ",")) !== FALSE){
          $array[] = $data_temp;
      }

      fclose($open);

      $data["tables"] = $array;


      $this->load->view('finance/delivery/paymentupload/v_upload_file_checking',$data);
  }
  //---

  //2023-11-06
  function upload_file_process(){
      $doc_no = json_decode(stripslashes($_POST['doc_no']));
      $subtotal = json_decode(stripslashes($_POST['subtotal']));
      $total = json_decode(stripslashes($_POST['total']));
      $invoice_vendor_no = json_decode(stripslashes($_POST['invoice_vendor_no']));
      $invoice_vendor_date = json_decode(stripslashes($_POST['invoice_vendor_date']));
      $payment_date = json_decode(stripslashes($_POST['payment_date']));
      $uuid = json_decode(stripslashes($_POST['uuid']));
      $remarks = json_decode(stripslashes($_POST['remarks']));

      $datetime = get_datetime_now();
      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user = $session_data['z_tpimx_user_id'];
      $name = $session_data['z_tpimx_name'];

      $result = $this->model_operacion_tsc_delivery_h->update_payment_upload($doc_no, $subtotal, $total, $invoice_vendor_no, $invoice_vendor_date, $payment_date, $uuid, $remarks, "8");

      for($i=0;$i<count($doc_no);$i++){
          $this->model_operacion_tsc_delivery_history->insert($doc_no[$i], $datetime, $user, "8", "Payment updated by ".$name);
      }

      if($result){
          $response['status'] = "1";
          $response['msg'] = "Succeed, Payment has been updated";
          echo json_encode($response);
      }
      else{
        $response['status'] = "0";
        $response['msg'] = "Error";
        echo json_encode($response);
      }
  }
  //----
}

?>
