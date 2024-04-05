<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kpi extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_report_folder').'kpi'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('wms/report/kpi/v_index', $data);
        }
    }
    //---

    function gen_report_doc_released(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->report_gen_report_doc_released($date_from, $date_to);
        $data["var_report"] = assign_data($result);
        $this->load->view('wms/report/kpi/v_report_doc_released', $data);
    }
    //---

    function gen_report_user_picked_qty(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->report_user_picked_qty($date_from, $date_to);
        $result_d = $this->model_report->report_gen_report_doc_released2($date_from, $date_to);

        if(count($result) == 0){
            $response['status'] = "0";
        }
        else{
            $response['status'] = "1";
            foreach($result as $row){
                $response["data"][] = array(
                    "name" => $row["username"],
                    "y" => (int)$row["qty_picked"],
                    "drilldown" => $row["username"],
                );
            }
        }

        // drilldown
        if(count($result_d) == 0){
            $response['status_d'] = "0";
        }
        else{
            $response['status_d'] = "1";

            $username = "";
            unset($temp);

            foreach($result_d as $row){
                if($username == ""){
                    $username = $row["username"];
                    $temp[] = array($row["doc_date"],(int)$row["qty_picked"]);
                }
                else{
                  if($username!=$row["username"]){
                      $response["data_d"][] = array(
                          "name" => $username,
                          "id" => $username,
                          "data" => $temp
                      );
                      $username = $row["username"];
                      unset($temp);
                  }
                  $temp[] = array($row["doc_date"],(int)$row["qty_picked"]);
                }
            }
            $response["data_d"][] = array(
                "name" => $username,
                "id" => $username,
                "data" => $temp
            );
        }

        echo json_encode($response);
    }
    //---

    function gen_report_user_picked_line(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->report_user_picked_line($date_from, $date_to);
        $result_d = $this->model_report->report_gen_report_doc_released2($date_from, $date_to);

        if(count($result) == 0){
            $response['status'] = "0";
        }
        else{
            $response['status'] = "1";
            foreach($result as $row){
                $response["data"][] = array(
                    "name" => $row["username"],
                    "y" => (int)$row["line"],
                    "drilldown" => $row["username"],
                );
            }
        }

        // drilldown
        if(count($result_d) == 0){
            $response['status_d'] = "0";
        }
        else{
            $response['status_d'] = "1";

            $username = "";
            unset($temp);

            foreach($result_d as $row){
                if($username == ""){
                    $username = $row["username"];
                    $temp[] = array($row["doc_date"],(int)$row["line"]);
                }
                else{
                  if($username!=$row["username"]){
                      $response["data_d"][] = array(
                          "name" => $username,
                          "id" => $username,
                          "data" => $temp
                      );
                      $username = $row["username"];
                      unset($temp);
                  }
                  $temp[] = array($row["doc_date"],(int)$row["line"]);
                }
            }
            $response["data_d"][] = array(
                "name" => $username,
                "id" => $username,
                "data" => $temp
            );
        }

        echo json_encode($response);
    }
    //---

    function gen_report_doc_released_put(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->report_gen_report_doc_released_put($date_from, $date_to);
        $data["var_report"] = assign_data($result);
        $this->load->view('wms/report/kpi/v_report_doc_released_put', $data);
    }
    //---

    function gen_report_user_put_qty(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->report_user_put_qty($date_from, $date_to);
        $result_d = $this->model_report->report_gen_report_doc_released2_put($date_from, $date_to);

        if(count($result) == 0){
            $response['status'] = "0";
        }
        else{
            $response['status'] = "1";
            foreach($result as $row){
                $response["data"][] = array(
                    "name" => $row["username"],
                    "y" => (int)$row["qty_put"],
                    "drilldown" => $row["username"],
                );
            }
        }

        // drilldown
        if(count($result_d) == 0){
            $response['status_d'] = "0";
        }
        else{
            $response['status_d'] = "1";

            $username = "";
            unset($temp);

            foreach($result_d as $row){
                if($username == ""){
                    $username = $row["username"];
                    $temp[] = array($row["doc_date"],(int)$row["qty_put"]);
                }
                else{
                  if($username!=$row["username"]){
                      $response["data_d"][] = array(
                          "name" => $username,
                          "id" => $username,
                          "data" => $temp
                      );
                      $username = $row["username"];
                      unset($temp);
                  }
                  $temp[] = array($row["doc_date"],(int)$row["qty_put"]);
                }
            }
            $response["data_d"][] = array(
                "name" => $username,
                "id" => $username,
                "data" => $temp
            );
        }

        echo json_encode($response);
    }
    //---

    function gen_report_user_put_line(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->report_user_put_line($date_from, $date_to);
        $result_d = $this->model_report->report_gen_report_doc_released2_put($date_from, $date_to);

        if(count($result) == 0){
            $response['status'] = "0";
        }
        else{
            $response['status'] = "1";
            foreach($result as $row){
                $response["data"][] = array(
                    "name" => $row["username"],
                    "y" => (int)$row["line"],
                    "drilldown" => $row["username"],
                );
            }
        }

        // drilldown
        if(count($result_d) == 0){
            $response['status_d'] = "0";
        }
        else{
            $response['status_d'] = "1";

            $username = "";
            unset($temp);

            foreach($result_d as $row){
                if($username == ""){
                    $username = $row["username"];
                    $temp[] = array($row["doc_date"],(int)$row["line"]);
                }
                else{
                  if($username!=$row["username"]){
                      $response["data_d"][] = array(
                          "name" => $username,
                          "id" => $username,
                          "data" => $temp
                      );
                      $username = $row["username"];
                      unset($temp);
                  }
                  $temp[] = array($row["doc_date"],(int)$row["line"]);
                }
            }
            $response["data_d"][] = array(
                "name" => $username,
                "id" => $username,
                "data" => $temp
            );
        }

        echo json_encode($response);
    }
    //---

    function gen_report_putaway_time(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->report_putaway_time_by_user($date_from, $date_to);
        $data["var_report"] = assign_data($result);
        $this->load->view('wms/report/kpi/v_report_putaway_time', $data);
    }
    //---

    function gen_report_pick_time(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->report_pick_time_by_user($date_from, $date_to);
        $data["var_report"] = assign_data($result);
        $this->load->view('wms/report/kpi/v_report_pick_time', $data);
    }
    //---

    // 2023-04-11
    function gen_report_user_picked_doc_no(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->get_doc_no_picking_by_user($date_from, $date_to);
        $result_d = $this->model_report->report_gen_report_doc_picking_by_day($date_from, $date_to);

        if(count($result) == 0){
            $response['status'] = "0";
        }
        else{
            $response['status'] = "1";
            foreach($result as $row){
                $response["data"][] = array(
                    "name" => $row["username"],
                    "y" => (int)$row["doc_no"],
                    "drilldown" => $row["username"],
                );
            }
        }

        // drilldown
        if(count($result_d) == 0){
            $response['status_d'] = "0";
        }
        else{
            $response['status_d'] = "1";

            $username = "";
            unset($temp);

            foreach($result_d as $row){
                if($username == ""){
                    $username = $row["username"];
                    $temp[] = array($row["doc_date"],(int)$row["doc_no"]);
                }
                else{
                  if($username!=$row["username"]){
                      $response["data_d"][] = array(
                          "name" => $username,
                          "id" => $username,
                          "data" => $temp
                      );
                      $username = $row["username"];
                      unset($temp);
                  }
                  $temp[] = array($row["doc_date"],(int)$row["doc_no"]);
                }
            }
            $response["data_d"][] = array(
                "name" => $username,
                "id" => $username,
                "data" => $temp
            );
        }

        echo json_encode($response);
    }
    //---

    // 2023-05-22
    function gen_report_user_picked_consume_time(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->report_gen_report_pick_consume_time_by_user($date_from, $date_to);
        $result_d = $this->model_report->report_gen_report_pick_consume_time_by_user_day($date_from, $date_to);

        if(count($result) == 0){
            $response['status'] = "0";
        }
        else{
            $response['status'] = "1";
            foreach($result as $row){
                $response["data"][] = array(
                    "name" => $row["username"],
                    "y" => (int)$row["consume_time"],
                    "drilldown" => $row["username"],
                );
            }
        }

        // drilldown
        if(count($result_d) == 0){
            $response['status_d'] = "0";
        }
        else{
            $response['status_d'] = "1";

            $username = "";
            unset($temp);

            foreach($result_d as $row){
                if($username == ""){
                    $username = $row["username"];
                    $temp[] = array($row["doc_date"],(int)$row["consume_time"]);
                }
                else{
                  if($username!=$row["username"]){
                      $response["data_d"][] = array(
                          "name" => $username,
                          "id" => $username,
                          "data" => $temp
                      );
                      $username = $row["username"];
                      unset($temp);
                  }
                  $temp[] = array($row["doc_date"],(int)$row["consume_time"]);
                }
            }
            $response["data_d"][] = array(
                "name" => $username,
                "id" => $username,
                "data" => $temp
            );
        }

        echo json_encode($response);
    }
    //---

    // 2023-05-22
    function gen_report_user_qc_doc_no(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->get_doc_no_qc_by_user($date_from, $date_to);
        $result_d = $this->model_report->get_doc_no_qc_by_user_day($date_from, $date_to);

        if(count($result) == 0){
            $response['status'] = "0";
        }
        else{
            $response['status'] = "1";
            foreach($result as $row){
                $response["data"][] = array(
                    "name" => $row["username"],
                    "y" => (int)$row["doc_no"],
                    "drilldown" => $row["username"],
                );
            }
        }

        // drilldown
        if(count($result_d) == 0){
            $response['status_d'] = "0";
        }
        else{
            $response['status_d'] = "1";

            $username = "";
            unset($temp);

            foreach($result_d as $row){
                if($username == ""){
                    $username = $row["username"];
                    $temp[] = array($row["doc_date"],(int)$row["doc_no"]);
                }
                else{
                  if($username!=$row["username"]){
                      $response["data_d"][] = array(
                          "name" => $username,
                          "id" => $username,
                          "data" => $temp
                      );
                      $username = $row["username"];
                      unset($temp);
                  }
                  $temp[] = array($row["doc_date"],(int)$row["doc_no"]);
                }
            }
            $response["data_d"][] = array(
                "name" => $username,
                "id" => $username,
                "data" => $temp
            );
        }

        echo json_encode($response);
    }
    //---

    // 2023-05-22
    function gen_report_user_qc_qty(){
        $date_from  = $_POST["date_from"];
        $date_to    = $_POST["date_to"];

        $this->load->model('model_report','',TRUE);

        $result = $this->model_report->get_qty_qc_by_user($date_from, $date_to);
        $result_d = $this->model_report->get_qty_qc_by_user_day($date_from, $date_to);

        if(count($result) == 0){
            $response['status'] = "0";
        }
        else{
            $response['status'] = "1";
            foreach($result as $row){
                $response["data"][] = array(
                    "name" => $row["username"],
                    "y" => (int)$row["qty"],
                    "drilldown" => $row["username"],
                );
            }
        }

        // drilldown
        if(count($result_d) == 0){
            $response['status_d'] = "0";
        }
        else{
            $response['status_d'] = "1";

            $username = "";
            unset($temp);

            foreach($result_d as $row){
                if($username == ""){
                    $username = $row["username"];
                    $temp[] = array($row["doc_date"],(int)$row["qty"]);
                }
                else{
                  if($username!=$row["username"]){
                      $response["data_d"][] = array(
                          "name" => $username,
                          "id" => $username,
                          "data" => $temp
                      );
                      $username = $row["username"];
                      unset($temp);
                  }
                  $temp[] = array($row["doc_date"],(int)$row["qty"]);
                }
            }
            $response["data_d"][] = array(
                "name" => $username,
                "id" => $username,
                "data" => $temp
            );
        }

        echo json_encode($response);
    }
    //---
}
