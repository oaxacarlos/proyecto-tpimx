<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Timelinewhrcpt extends CI_Controller{

   function index(){
       $this->load->view('templates/navigation');

       if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'timelinewhrcpt'])){
           $this->load->view('view_home');
       }
       else{
           $data["get_doc_no"]= $_GET["doc_no"];
           $this->load->view('wms/report/timelinewhrcpt/v_index', $data);
       }
   }
   //---

   function get_data(){

      $doc_no = $_POST['doc_no'];

      $this->load->model('model_tsc_doc_history','',TRUE);
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      unset($data["is_exist"]);

      // get inbound created
      $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
      $this->model_tsc_in_out_bound_h->doc_type = "1";
      if(!$this->model_tsc_in_out_bound_h->is_exist_inout_bound()){
          $data["is_exist"]=0;
      }
      else{
            $data["is_exist"]=1;
            $data["start_datetime"] = 0;
            $data["start_text"] = 0;
            $data["received"] = 0;
            $data["received_verified"] = 0;
            $data["gen_sn"] = 0;
            $data["put_away"] = 0;
            $data["put_away_finished"] = 0;
            $data["release"] = 0;

            // start
            $this->model_tsc_doc_history->doc1 = $doc_no;
            $result = $this->model_tsc_doc_history->get_whsreceipt_whshipment();
            if(count($result) > 0){
              $result = assign_data_one($this->model_tsc_doc_history->get_whsreceipt_whshipment());
              $data["start_datetime"] = $result["created_datetime"];
              $data["start_text"] = $result["text1"];
            }
            //---

            // received
            if($data["start_datetime"]!=0){
              $this->model_tsc_doc_history->doc2 = $doc_no;
              $result = $this->model_tsc_doc_history->get_received();
              if(count($result) > 0){ $data["received"] = assign_data($result); }
            }
            //---

            // Received Verified
            if($data["received"] != 0){
                unset($doc);
                foreach($data["received"] as $row){
                    $doc[] = $row["doc1"];
                }
                $result = $this->model_tsc_doc_history->get_received_verified($doc);
                $data["received_verified"] = assign_data($result);
            }
            //----

            // Gen SN
            if($data["received_verified"] != 0){
                unset($doc);
                foreach($data["received_verified"] as $row){
                    $doc[] = $row["doc1"];
                }
                $result = $this->model_tsc_doc_history->get_gen_sn($doc);
                $data["gen_sn"] = assign_data($result);
            }
            //---

            // Put Away
            if($data["gen_sn"] != 0){
                unset($doc);
                foreach($data["gen_sn"] as $row){
                    $doc[] = $row["doc1"];
                }
                $result = $this->model_tsc_doc_history->get_put_away($doc);
                $data["put_away"] = assign_data($result);
            }
            //--

            // Put Away Finished
            if($data["put_away"] != 0){
                unset($doc);
                foreach($data["put_away"] as $row){
                    $doc[] = $row["doc1"];
                }
                $result = $this->model_tsc_doc_history->get_put_away_finished($doc);
                $data["put_away_finished"] = assign_data($result);
            }
            //--

            // Release
            if($data["put_away_finished"] != 0){
                $this->model_tsc_doc_history->doc1 = $doc_no;
                $result = $this->model_tsc_doc_history->get_whsreceipt_release();
                if(count($result) > 0){ $data["release"] = assign_data_one($result); }
            }
            //---

            // Submit to Nav
            if($data["release"] != 0){
                $this->model_tsc_doc_history->doc1 = $doc_no;
                $result = $this->model_tsc_doc_history->get_submit_to_nav();
                if(count($result) > 0){ $data["submitnav"] = assign_data_one($result); }
            }

            //---

      }

      $data["doc_no"] = $doc_no;
      $this->load->view('wms/report/timelinewhrcpt/v_report', $data);
   }
   //---
}
