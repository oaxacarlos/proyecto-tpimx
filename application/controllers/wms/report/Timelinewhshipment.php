<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Timelinewhshipment extends CI_Controller{

   function index(){
       $this->load->view('templates/navigation');

       if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'timelinewhshipment'])){
           $this->load->view('view_home');
       }
       else{
           $data["get_doc_no"]= $_GET["doc_no"];
           $this->load->view('wms/report/timelinewhshipment/v_index', $data);
       }
   }
   //---

   function get_data(){

      $doc_no = $_POST['doc_no'];

      $this->load->model('model_tsc_in_out_bound_h','',TRUE);
      $this->load->model('model_tsc_doc_history','',TRUE);
      $this->load->model('model_outbound','',TRUE);

      unset($data["is_exist"]);

      // get outbound created
      $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
      $this->model_tsc_in_out_bound_h->doc_type = "2";
      if(!$this->model_tsc_in_out_bound_h->is_exist_inout_bound()){
          $data["is_exist"]=0;
      }
      else{
            $data["is_exist"]=1;
            $data["start_datetime"] = 0;
            $data["start_text"] = 0;
            $data["picking"] = 0;
            $data["picking_finished"] = 0;
            $data["qc"] = 0;
            $data["packing"] = 0;
            $data["submitnav"] = 0;
            $data["invoice"] = 0;
            $data["create_picking"] = 0;

            // start
            $this->model_tsc_doc_history->doc1 = $doc_no;
            $result = $this->model_tsc_doc_history->get_whsreceipt_whshipment();
            if(count($result) > 0){
              $result = assign_data_one($this->model_tsc_doc_history->get_whsreceipt_whshipment());
              $data["start_datetime"] = $result["created_datetime"];
              $data["start_text"] = $result["text1"];
              $data["start_user"] = $result["name"];
            }
            //---

      }

      // creating the picking 2023-06-08
      if($data["start_datetime"]!=0){
          $this->model_tsc_doc_history->doc2 = $doc_no;
          $result = $this->model_tsc_doc_history->get_created_picking();
          if(count($result) > 0){ $data["create_picking"] = assign_data_one($result); }
      }

      // picking
      if($data["start_datetime"]!=0){
          $this->model_tsc_doc_history->doc2 = $doc_no;
          $result = $this->model_tsc_doc_history->get_picking();
          if(count($result) > 0){ $data["picking"] = assign_data($result); }
      }
      //---

      // Picking finished
      if($data["picking"] != 0){
          unset($doc);
          foreach($data["picking"] as $row){
              $doc[] = $row["doc1"];
          }
          $result = $this->model_tsc_doc_history->get_picking_finished($doc);
          if(count($result) > 0){ $data["picking_finished"] = assign_data($result); }
      }
      //----

      // QC
      if($data["picking_finished"] != 0){
          $result = $this->model_tsc_doc_history->get_qc($doc_no);
          if(count($result) > 0){ $data["qc"] = assign_data_one($result); }
      }
      //----

      // submit navision
      if($data["qc"] != 0 or $data["qc"] == 0){
          $result = $this->model_tsc_doc_history->get_submit_to_nav();
          if(count($result) > 0){ $data["submitnav"] = assign_data_one($result);
          }
      }
      //----

      // packing
      if($data["submitnav"] != 0){
          $this->model_tsc_doc_history->doc1 = $doc_no;
          $result = $this->model_tsc_doc_history->get_packing($doc_no);
          //if(count($result) > 0){ $data["submitnav"] = assign_data_one($result);
          if(count($result) > 0){ $data["packing"] = assign_data($result); }
      }

      // invoice
      if($data["invoice"] != 0 or $data["invoice"] == 0){
          $result = $this->model_outbound->get_shipment_invoice_nav($doc_no);
          if(count($result) > 0) $data["invoice"] = assign_data($result);
      }
      //--

      $data["doc_no"] = $doc_no;
      $this->load->view('wms/report/timelinewhshipment/v_report', $data);
    }
   //---
}
