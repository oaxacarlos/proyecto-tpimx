<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Timelineso extends CI_Controller{

   function index(){
       $this->load->view('templates/navigation');

       if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'timelineso'])){
           $this->load->view('view_home');
       }
       else{
           $this->load->view('wms/report/timelineso/v_index', $data);
       }
   }
   //---

   function get_shipment_list(){
      $doc_no = $_POST['doc_no'];

      $this->load->model('model_tsc_in_out_bound_d','',TRUE);

      $this->model_tsc_in_out_bound_d->src_no = $doc_no;
      $result = $this->model_tsc_in_out_bound_d->get_docno_by_shipmentno();
      if(count($result) == 0){
          $response['status'] = "0";
      }
      else{
          $response['status'] = "1";
          foreach($result as $row){
              $response["ship_no"][] = $row["doc_no"];
          }
      }

      echo json_encode($response);
   }
   //---

}
