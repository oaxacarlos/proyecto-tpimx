<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'dashboard'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('wms/report/dashboard/v_index', $data);
        }
    }
    //---

    function whsreceipt_not_proceed(){
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

        $total_data = 0;
        if(!is_null($data['var_whrcpt_list_h'])){
            if(count($data['var_whrcpt_list_h']) > 0){
                $total_data = count($data['var_whrcpt_list_h']);
            }
        }
        //---

        echo json_encode($total_data);
    }
    //--

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

    function whship_not_proceed(){
        $this->load->model('model_outbound','',TRUE);
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        // get header
        $result = $this->model_outbound->whship_list_h();
        unset($data['var_whship_list_h']);
        if($result) $data['var_whship_list_h'] = assign_data($result);

        //---

        if($result){
            // check if already in tsc_in_out_bound_h
            unset($doc_no);
            foreach($data['var_whship_list_h'] as $row){
                $doc_no[] = $row['no'];
            }
            $result_in_out_bound_h = $this->model_tsc_in_out_bound_h->check_h_by_doc_no($doc_no);
            //----

            // remove data already proceed to in_out_bound_h
            if(count($result_in_out_bound_h ) > 0){
                $data['var_whship_list_h'] = $this->remove_already_process_from_whship_to_in_out_bound($data['var_whship_list_h'], $result_in_out_bound_h);
            }
            //----
        }

        $total_data = 0;
        if(!is_null($data['var_whship_list_h'])){
            if(count($data['var_whship_list_h']) > 0){
                $total_data = count($data['var_whship_list_h']);
            }
        }
        //---

        echo json_encode($total_data);
    }
    //----

    function remove_already_process_from_whship_to_in_out_bound($whship_h, $in_out_bound_h){
      $temp = $whship_h;
      unset($whship_h);
      foreach($temp as $key => $row){
          $is_there = 0;
          foreach($in_out_bound_h as $row2){
              if($row['no'] == $row2['doc_no']){
                  $is_there = 1; break;
              }
          }

          if($is_there == 0) $whship_h[] = $temp[$key];
      }

      return $whship_h;
    }
    //----

    function outstand_received(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $total_data = $this->model_tsc_in_out_bound_h->dsh_received_outstanding();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function received(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $total_data = $this->model_tsc_in_out_bound_h->dsh_received();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function outstand_putaway(){
        $this->load->model('Model_tsc_putaway_h','',TRUE);
        $total_data = $this->Model_tsc_putaway_h->dsh_outstanding_putaway();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function outstand_whship(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $total_data = $this->model_tsc_in_out_bound_h->dsh_outstanding_whship();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function outstand_picked(){
        $this->load->model('model_tsc_picking_h','',TRUE);
        $total_data = $this->model_tsc_picking_h->dsh_outstanding_picked();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function outstand_qc(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $total_data = $this->model_tsc_in_out_bound_h->dsh_outstanding_qc();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function outstand_packed(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $total_data = $this->model_tsc_in_out_bound_h->dsh_outstanding_packed_doc();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function outstand_whship_doc(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $total_data = $this->model_tsc_in_out_bound_h->dsh_outstanding_whship_doc();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function outstand_picked_doc(){
        $this->load->model('model_tsc_picking_h','',TRUE);
        $total_data = $this->model_tsc_picking_h->dsh_outstanding_picked_doc();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function outstand_qc_doc(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $total_data = $this->model_tsc_in_out_bound_h->dsh_outstanding_qc_doc();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function outstand_packed_doc(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $total_data = $this->model_tsc_in_out_bound_h->dsh_outstanding_packed_doc();

        if(!is_null($total_data)) echo json_encode($total_data);
        else echo json_encode("0");
    }
    //---

    function get_top10_inbound(){
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];
        $top = $_POST["top"];

        $this->load->model('model_tsc_item_entry','',TRUE);

        $result = $this->model_tsc_item_entry->get_inbound_by_periode_limit($date_from, $date_to, $top);

        if(count($result) == 0){
            $response['status'] = "0";
        }
        else{
            $response['status'] = "1";
            foreach($result as $row){
                $response["data"][] = array($row["item_code"],(int)$row["qty"]);
            }
        }

        echo json_encode($response);
    }
    //--

    function get_top10_outbound(){
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];
        $top = $_POST["top"];

        $this->load->model('model_tsc_item_entry','',TRUE);

        $result = $this->model_tsc_item_entry->get_outbound_by_periode_limit($date_from, $date_to, $top);

        if(count($result) == 0){
            $response['status'] = "0";
        }
        else{
            $response['status'] = "1";
            foreach($result as $row){
                $response["data"][] = array($row["item_code"],(int)$row["qty"]);
            }
        }

        echo json_encode($response);
    }
    //--

    function outstand_amount(){
        $this->load->model('model_report','',TRUE);
        $result = $this->model_report->report_outstanding_amount_from_nav();
        $data["var_report"] = assign_data($result);
        $this->load->view('wms/report/dashboard/v_outstanding_amount', $data);
    }
    //---

    function shipment_today(){
        $this->load->model('model_report','',TRUE);

        $date = get_date_now();
        $result = $this->model_report->total_in_out_from_to($date, $date);
        $data["var_total"] = $result;
        $this->load->view('wms/report/dashboard/v_dashboard_today', $data);
    }
    //---

    // 2023-06-07
    function in_finished_detail(){
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->report_in_finished_detail($date_from,$date_to);
        unset($total_in);
        unset($total_out);

        if(count($result) < 0){ $response = 0; }
        else{
            foreach($result as $row){
                $doc_date = "'".$row["doc_date"]."'";

                $response["categories"][] = $doc_date;

                $total_in[]  = (int)$row["total_in"];
                $total_out[] = (int)$row["total_out"];
            }

            $response["detail"][] = array(
                "name" => "In",
                "data" =>  $total_in
            );

            $response["detail"][] = array(
                "name" => "Finished",
                "data" => $total_out
            );
        }

        echo json_encode($response);
    }
    //---
}
