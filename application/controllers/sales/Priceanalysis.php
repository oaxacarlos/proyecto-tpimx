<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Priceanalysis extends CI_Controller{
    function __construct(){
      parent::__construct();
         $this->load->model('model_price_analysis','',TRUE);
         $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'priceanalysis'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Price Analysis"); // insert log

            $result = $this->model_price_analysis->get_mst_item_nav();
            $data["var_item"] = assign_data($result);
            $this->load->view('sales/priceanalysis/v_index', $data);
        }
    }
    //---

    function detail(){
        $this->model_zlog->insert("Gen Price Analysis Data"); // insert log

        $item_code = $_POST['item_code'];
        $year = date("Y");

        $last_4years[] = $year - 3;
        $last_4years[] = $year - 2;
        $last_4years[] = $year - 1;
        $last_4years[] = $year;

        $result = $this->model_price_analysis->get_detail_price_by_item_no( $last_4years, $item_code);
        $data["var_detail"] = assign_data($result);
        $data["var_year"] = $last_4years;

        $this->load->view('sales/priceanalysis/v_table_detail', $data);
    }
    //--

    function item_year(){
        $this->model_zlog->insert("Gen Price Analysis Item Year Data"); // insert log

        $item_code = $_POST['item_code'];
        $year = date("Y");

        $last_4years[] = $year - 3;
        $last_4years[] = $year - 2;
        $last_4years[] = $year - 1;
        $last_4years[] = $year;

        $result = $this->model_price_analysis->get_detail_price_by_item_no( $last_4years, $item_code);
        $result = assign_data($result);

        foreach($result as $row){
            $response["categories"][] = $row["unit_price"];
            foreach($last_4years as $row2){
              $data[$row2][] = (int)$row["qty_".$row2];
            }
        }

        foreach($last_4years as $row2){
            $response["data"][] = array(
                "name" => $row2,
                "data" => $data[$row2]
            );
        }

        echo json_encode($response);
    }
    //---

    function chart_year(){
        $this->model_zlog->insert("Gen Price Analysis Chart Year Data"); // insert log

        $item_code = $_POST['item_code'];
        $year = date("Y");

        $last_4years[] = $year - 3;
        $last_4years[] = $year - 2;
        $last_4years[] = $year - 1;
        $last_4years[] = $year;

        // parent
        $result = $this->model_price_analysis->get_total_qty_by_year( $last_4years, $item_code);
        $result = assign_data($result);

        foreach($result as $row){
            foreach($last_4years as $row2){
              $response["series"][] = array(
                  "name" => strval($row2),
                  "y" => (int)$row["qty_".$row2],
                  "drilldown" => strval($row2)
              );
            }
        }
        //---

        // drilldown
        $result = $this->model_price_analysis->get_detail_price_by_item_no( $last_4years, $item_code);
        $result = assign_data($result);
        foreach($result as $row){
            foreach($last_4years as $row2){
              $data[$row2][] = array($row["unit_price"],(int)$row["qty_".$row2]);
            }
        }

        foreach($last_4years as $row2){
            $response["drilldown"][] = array(
                "name" => strval($row2),
                "id" => strval($row2),
                "data" => $data[$row2]
            );
        }
        //---

        echo json_encode($response);
    }
    //---

    function cross_reference(){
        $this->model_zlog->insert("Gen Cross Reference Data"); // insert log

        $item_code = $_POST['item_code'];
        $result = $this->model_price_analysis->get_cross_reference($item_code);
        $data["var_detail"] = assign_data($result);
        $this->load->view('sales/priceanalysis/v_cross_reference', $data);
    }
    //--
}
