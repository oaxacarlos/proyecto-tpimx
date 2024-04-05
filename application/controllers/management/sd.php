<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sd extends CI_Controller{

    function __construct(){
      parent::__construct();
         $this->load->model('management/model_sd','model_management_sd');
         $this->load->model('model_sales_report','',TRUE);
         $this->load->model('model_zlog','',TRUE);
    }

    function invtmonitor(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('management_folder').'sd/invtmonitor'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Inventory Monitoring"); // insert log

            $this->load->view('management/sd/invtmonitor/v_index', $data);
        }
    }
    //---

    function invtmonitor_data(){
        $this->model_zlog->insert("Gen Inventory Monitoring Data"); // insert log

        $type = $_POST["type"];

        $this_year = date("Y");
        $last_year = $this_year - 1;

        $today = date("Y-m-d");

        $date["last_year_from"] = $last_year."-01-01";
        $date["last_year_to"] = $last_year."-12-31";

        $date["ytd_from"] = $this_year."-01-01";
        $date["ytd_to"] = date("Y-n-j", strtotime("last day of previous month"));

        $date["last_6months_to"] = $date["ytd_to"];
        $date["last_6months_from"]  =  get_last_6months_first_day($date["ytd_to"]);

        $date["last_12months_to"] = $date["ytd_to"];
        $date["last_12months_from"]  = get_last_12months_first_day($date["ytd_to"]);

        $result = $this->model_management_sd->get_inventory_monitoring($date,$type);
        $data["var_report"] = assign_data($result);

        $data["var_date"] = $date;
        $data["var_type"] = $type;

        $this->load->view('management/sd/invtmonitor/v_report', $data);

    }
    //---

    function invtmonitor_detail(){
        $this->model_zlog->insert("Gen Inventory Monitoring Detail"); // insert log

        $item_cat           = $_POST["item_cat"];
        $manf_code          = $_POST["manf_code"];
        $type               = $_POST["type"];
        $ytd_from           = $_POST["ytd_from"];
        $ytd_to             = $_POST["ytd_to"];
        $last_year_from     = $_POST["last_year_from"];
        $last_year_to       = $_POST["last_year_to"];
        $last_6months_from  = $_POST["last_6months_from"];
        $last_6months_to    = $_POST["last_6months_to"];
        $last_12months_from = $_POST["last_12months_from"];
        $last_12months_to   = $_POST["last_12months_to"];
        $brand              = $_POST["brand"];

        if($type == "1") $result = $this->model_management_sd->get_inventory_monitoring_invt($brand,$item_cat,$manf_code);
        else if($type == "2") $result = $this->model_management_sd->get_inventory_monitoring_invoice($ytd_from,$ytd_to,$brand,$item_cat,$manf_code);
        else if($type == "3") $result = $this->model_management_sd->get_inventory_monitoring_invoice($last_year_from,$last_year_to,$brand,$item_cat,$manf_code);
        else if($type == "4") $result = $this->model_management_sd->get_inventory_monitoring_item_not_moving($last_6months_from,$last_6months_to,$brand,$item_cat,$manf_code);
        else if($type == "5") $result = $this->model_management_sd->get_inventory_monitoring_item_not_moving($last_12months_from,$last_12months_to,$brand,$item_cat,$manf_code);
        else if($type == "6") $result = $this->model_management_sd->get_inventory_monitoring_item_not_moving($last_year_from,$last_year_to,$brand,$item_cat,$manf_code);

        $data["var_report"] = assign_data($result);

        $this->load->view('management/sd/invtmonitor/v_detail', $data);
    }
    //--

    function salescontrib(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('management_folder').'sd/salescontrib'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Sales Contribution"); // insert log

            $this->load->view('management/sd/salescontrib/v_index', $data);
        }
    }
    //---

    function salescontrib_data(){
        $this->model_zlog->insert("Gen Sales Contribution Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];
        $brand = $_POST["brand"];
        $cat = $_POST["cat"];

        if($cat == "ALL") $cat = "";

        $last_year = $year-1;
        $last_2year = $year-2;
        $months = array("01","02","03","04","05","06","07","08","09","10","11","12");
        if($type == "1") $result = $this->model_management_sd->get_8020_review_qty_report($year, $last_year, $last_2year, $months, $brand, $cat);
        else if($type == "2") $result = $this->model_management_sd->get_8020_review_amount_report($year, $last_year, $last_2year, $months, $brand, $cat);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;
        $data["type"] = $type;

        if($type == 1){ $data["amount_format"] = 0; $data["comma_digit"] = 0; }
        else{ $data["amount_format"] = 0; $data["comma_digit"] = 2; }

        $this->load->view('management/sd/salescontrib/v_report', $data);
    }
    //---

    function sofullfill(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('management_folder').'sd/sofullfill'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("SO Full Fill"); // insert log

            $this->load->view('management/sd/sofullfill/v_index', $data);
        }
    }
    //---

    function sofullfill_so_detail(){
        $this->model_zlog->insert("Gen SO Full Fill SO Detail"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_management_sd->get_detail_so_monthly($year, $month);
        $data["var_report"] = assign_data($result);

        $this->load->view('management/sd/sofullfill/v_so_detail', $data);
    }
    //--

    function sofullfill_so_summary(){
        $this->model_zlog->insert("Gen SO Full Fill SO Summary"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_management_sd->get_summary_so_monthly($year, $month);
        $data["var_report"] = assign_data($result);

        $this->load->view('management/sd/sofullfill/v_so_summary', $data);
    }
    //--

    function sofullfill_cust_bo(){
        $this->model_zlog->insert("Gen SO Full Fill Cust BO"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_management_sd->get_cust_bo_so_monthly($year, $month);
        $data["var_report"] = assign_data($result);

        $this->load->view('management/sd/sofullfill/v_cust_bo', $data);
    }
    //--

    function sofullfill_cust_top30(){
        $this->model_zlog->insert("Gen SO Full Fill Cust Top 30"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_management_sd->get_cust_top30_so_monthly($year, $month);
        $data["var_report"] = assign_data($result);

        $this->load->view('management/sd/sofullfill/v_cust_top30', $data);
    }
    //--

    function sofullfill_so_sku(){
        $this->model_zlog->insert("Gen SO Full Fill SO SKU"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_management_sd->get_sku_so_monthly($year, $month);
        $data["var_report"] = assign_data($result);

        $this->load->view('management/sd/sofullfill/v_so_sku', $data);
    }
    //--

    // 2023-05-16
    function invoicesdetail(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('management_folder').'sd/invoicesdetail'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Management Invoices Detail"); // insert log

            $session_data = $this->session->userdata('z_tpimx_logged_in');
            $user = $session_data['z_tpimx_user_id'];
            $userid_1 = $session_data['z_tpimx_userid_1'];

            $data["var_user"] = $userid_1;
            $data["var_customer"] = $this->model_sales_report->get_customer_by_cs_code_nav_local($userid_1);

            $this->load->view('management/sd/invoicesdetail/v_index', $data);
        }
    }

    // 2023-0-16
    function invoicesdetail_data(){
        $this->model_zlog->insert("Gen Management Invoices Detail Data"); // insert log

        $cust_no = $_POST["cust_no"];
        $from = $_POST["from"];
        $to = $_POST["to"];
        $doc_type = $_POST["doc_type"];

        if($cust_no == "ALL") $cust_no = "";

        $result = $this->model_management_sd->get_invoices_by_period_customer($from, $to, $cust_no,$doc_type);
        $data["var_report"] = assign_data($result);

        $this->load->view('management/sd/invoicesdetail/v_report',$data);
    }
    //----

    // 2023-05-25
    function custreview(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('management_folder').'sd/custreview'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Management Customer Review"); // insert log
            //$result = $this->model_sales_report->get_customer_nav_local();
            //$data["var_customer_data"] = assign_data($result);

            $this->load->view('management/sd/custreview/v_index', $data);
        }
    }
    //--

    // 2023-05-25
    function custreview_data(){
        $this->model_zlog->insert("Gen Management Customer Review Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year-1;
        $last_2year = $year-2;
        $months = array("01","02","03","04","05","06","07","08","09","10","11","12");
        $firstdate_this_year = date("Y-01-01");
        $lastdate_this_year = date("Y-12-31");

        // get last 6 months
        $today = get_date_now();
        $last_6months = get_last_6months_first_day($today);

        if($type == "1") $result = $this->model_management_sd->get_cust_review2_qty_report($year, $last_year, $last_2year, $months, $today, $last_6months,$firstdate_this_year,$lastdate_this_year);
        else if($type == "2") $result = $this->model_management_sd->get_cust_review2_amount_report($year, $last_year, $last_2year, $months, $today, $last_6months,$firstdate_this_year,$lastdate_this_year);
        $data["var_report"] = assign_data($result);

        $result = $this->model_management_sd->get_cust_review_detail($year, $last_year, $last_2year, $months);
        $data["var_detail"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;


        if($type == 1){
          $data["amount_format"] = 0; $data["comma_digit"] = 0;
          $data["typee"] = "qty";
        }
        else{
          $data["amount_format"] = 0; $data["comma_digit"] = 2;
          $data["typee"] = "amount";
        }

        $this->load->view('management/sd/custreview/v_report', $data);
    }
    //--

    // 2023-09-18
    function custdetailreview(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('management_folder').'sd/custdetailreview'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Management Customer Detail Review"); // insert log
            //$result = $this->model_sales_report->get_customer_nav_local();
            //$data["var_customer_data"] = assign_data($result);

            $this->load->view('management/sd/custdetailreview/v_index', $data);
        }
    }
    //--

    // 2023-09-18
    function custdetailreview_filter_data(){
        $this->model_zlog->insert("Gen Management Custome Detail Filter Review Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year-1;
        $last_2year = $year-2;
        $months = array("01","02","03","04","05","06","07","08","09","10","11","12");
        $firstdate_this_year = date("Y-01-01");
        $lastdate_this_year = date("Y-12-31");

        // get last 6 months
        $today = get_date_now();
        $last_6months = get_last_6months_first_day($today);

        if($type == "1") $result = $this->model_management_sd->get_cust_review_detail_filter_qty_report($year, $last_year, $last_2year, $months, $today, $last_6months,$firstdate_this_year,$lastdate_this_year);
        else if($type == "2") $result = $this->model_management_sd->get_cust_review_detail_filter_amount_report($year, $last_year, $last_2year, $months, $today, $last_6months,$firstdate_this_year,$lastdate_this_year);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;


        if($type == 1){
          $data["amount_format"] = 0; $data["comma_digit"] = 0;
          $data["typee"] = "qty";
        }
        else{
          $data["amount_format"] = 0; $data["comma_digit"] = 2;
          $data["typee"] = "amount";
        }

        $this->load->view('management/sd/custdetailreview/v_filter_report', $data);
    }
    //--

    // 2023-09-18
    function custdetailreview_banda_data(){
        $this->model_zlog->insert("Gen Management Custome Detail Banda Review Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year-1;
        $last_2year = $year-2;
        $months = array("01","02","03","04","05","06","07","08","09","10","11","12");
        $firstdate_this_year = date("Y-01-01");
        $lastdate_this_year = date("Y-12-31");

        // get last 6 months
        $today = get_date_now();
        $last_6months = get_last_6months_first_day($today);

        if($type == "1") $result = $this->model_management_sd->get_cust_review_detail_banda_qty_report($year, $last_year, $last_2year, $months, $today, $last_6months,$firstdate_this_year,$lastdate_this_year);
        else if($type == "2") $result = $this->model_management_sd->get_cust_review_detail_banda_amount_report($year, $last_year, $last_2year, $months, $today, $last_6months,$firstdate_this_year,$lastdate_this_year);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;

        if($type == 1){
          $data["amount_format"] = 0; $data["comma_digit"] = 0;
          $data["typee"] = "qty";
        }
        else{
          $data["amount_format"] = 0; $data["comma_digit"] = 2;
          $data["typee"] = "amount";
        }

        $this->load->view('management/sd/custdetailreview/v_banda_report', $data);
    }
    //--

    // 2023-09-19
    function custdetailreview_filter2_data(){
        $this->model_zlog->insert("Gen Management Customer Detail Filter 2 Review Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year-1;
        $last_2year = $year-2;
        $months = array("01","02","03","04","05","06","07","08","09","10","11","12");
        $firstdate_this_year = date("Y-01-01");
        $lastdate_this_year = date("Y-12-31");

        // get last 6 months
        $today = get_date_now();
        $last_6months = get_last_6months_first_day($today);

        if($type == "1") $result = $this->model_management_sd->get_cust_review_detail_filter_qty_report2($year, $last_year, $last_2year, $months, $today, $last_6months,$firstdate_this_year,$lastdate_this_year);
        else if($type == "2") $result = $this->model_management_sd->get_cust_review_detail_filter_amount_report2($year, $last_year, $last_2year, $months, $today, $last_6months,$firstdate_this_year,$lastdate_this_year);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;


        if($type == 1){
          $data["amount_format"] = 0; $data["comma_digit"] = 0;
          $data["typee"] = "qty";
        }
        else{
          $data["amount_format"] = 0; $data["comma_digit"] = 2;
          $data["typee"] = "amount";
        }

        $this->load->view('management/sd/custdetailreview/v_filter_report2', $data);
    }
    //--

    // 2023-09-19
    function custdetailreview_banda2_data(){
        $this->model_zlog->insert("Gen Management Custome Detail Banda 2 Review Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year-1;
        $last_2year = $year-2;
        $months = array("01","02","03","04","05","06","07","08","09","10","11","12");
        $firstdate_this_year = date("Y-01-01");
        $lastdate_this_year = date("Y-12-31");

        // get last 6 months
        $today = get_date_now();
        $last_6months = get_last_6months_first_day($today);

        if($type == "1") $result = $this->model_management_sd->get_cust_review_detail_banda_qty_report2($year, $last_year, $last_2year, $months, $today, $last_6months,$firstdate_this_year,$lastdate_this_year);
        else if($type == "2") $result = $this->model_management_sd->get_cust_review_detail_banda_amount_report2($year, $last_year, $last_2year, $months, $today, $last_6months,$firstdate_this_year,$lastdate_this_year);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;

        if($type == 1){
          $data["amount_format"] = 0; $data["comma_digit"] = 0;
          $data["typee"] = "qty";
        }
        else{
          $data["amount_format"] = 0; $data["comma_digit"] = 2;
          $data["typee"] = "amount";
        }

        $this->load->view('management/sd/custdetailreview/v_banda_report2', $data);
    }
    //--

    function po_created_received(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('management_folder').'sd/po_created_received'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Management PO Created - Received"); // insert log
            $this->load->view('management/sd/po_created_received/v_index', $data);
        }
    }
    //---

    function po_created_received_data(){
        $year = $_POST["year"];

        $result = $this->model_management_sd->get_po_created_received($year);
        $data["var_report"] = assign_data($result);
        $this->load->view('management/sd/po_created_received/v_report', $data);
    }
    //---
}
