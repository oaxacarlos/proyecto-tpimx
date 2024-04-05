<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller{

  function __construct(){
    parent::__construct();
       $this->load->model('model_sales_report','',TRUE);
       $this->load->model('model_zlog','',TRUE);
  }

  function dashboard(){
      $this->load->view('templates/navigation');

      $this->model_zlog->insert("Customer Dashboard"); // insert log

      /*if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder').'customer/dashboard'])){
          $this->load->view('view_home');
      }
      else{*/
          $session_data = $this->session->userdata('z_tpimx_logged_in');
          $user = $session_data['z_tpimx_user_id'];
          $sls_code = $this->model_sales_report->get_salesman_user($user);   // get user-salesman
          $result = $this->model_sales_report->get_customer_by_sls_person_code_nav_local($sls_code);
          $data["var_customer_data"] = assign_data($result);
          $data["var_sls_code"] = $sls_code;

          $this->load->view('sales/customer/dashboard/v_index', $data);
      //}
  }
  //---

  function report_3months_sales(){
      $this->model_zlog->insert("Gen Customer Dashboard 3 Months Sales"); // insert log

      $year = $_POST["year"];
      $month = $_POST["month"];
      $custno = $_POST["customer"];

      $date = $year."-".$month."-01";
      $last_month_from = get_last_month_first_day($date);
      $last_month_to = get_last_month_last_day($date);
      $last_2months_from = get_last_2months_first_day($date);
      $last_2months_to = get_last_2months_last_day($date);

      $total_day_last_2month = cal_days_in_month(CAL_GREGORIAN, substr($last_2months_from,5,2), substr($last_2months_from,0,4));
      $total_day_last_month = cal_days_in_month(CAL_GREGORIAN, substr($last_month_from,5,2), substr($last_month_from,0,4));
      $total_day_last_this_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

      $result_this_month = $this->model_sales_report->get_customer_sales_by_day_month($year, $month, $custno);
      $result_this_month = assign_data($result_this_month);

      $result_last_month = $this->model_sales_report->get_customer_sales_by_day_period($last_month_from, $last_month_to, $custno);
      $result_last_month = assign_data($result_last_month);

      $result_last_2month = $this->model_sales_report->get_customer_sales_by_day_period($last_2months_from, $last_2months_to, $custno);
      $result_last_2month = assign_data($result_last_2month);

      // get total day with 3 month
      $total_day = "";
      if($total_day_last_this_month >= $total_day_last_month) $total_day = $total_day_last_this_month;
      else $total_day = $total_day_last_month;

      if($total_day < $total_day_last_2month) $total_day = $total_day_last_2month;
      //--

      unset($data);

      // this month
      $total = 0;
      for($i=1;$i<=$total_day;$i++){
          foreach($result_this_month as $row){
            if($row["dayy"] == $i){
                $total += $row["amount"];
                break;
            }
          }

          $data_this_month[] = (int)$total;
      }
      //--

      // last month
      $total = 0;
      for($i=1;$i<=$total_day;$i++){
          foreach($result_last_month as $row){
            if($row["dayy"] == $i){
                $total += $row["amount"];
                break;
            }
          }

          $data_last_month[] = (int)$total;
      }
      //--

      // last 2 months
      $total = 0;
      for($i=1;$i<=$total_day;$i++){
          foreach($result_last_2month as $row){
            if($row["dayy"] == $i){
                $total += $row["amount"];
                break;
            }
          }

          $data_last_2month[] = (int)$total;
      }
      //--

      $response["this_month"] = $data_this_month;
      $response["last_month"] = $data_last_month;
      $response["last_2month"] = $data_last_2month;
      $response["this_month_text"] = date("F", strtotime($date));
      $response["last_month_text"] = date("F", strtotime($last_month_from));
      $response["last_2month_text"] = date("F", strtotime($last_2months_from));
      echo json_encode($response);
  }
  //---

  function report_3years_sales(){
      $this->model_zlog->insert("Gen Customer Dashboard 3 Years Sales"); // insert log

      $year = $_POST["year"];
      $custno = $_POST["customer"];

      $last_2year = $year-2;
      $last_year  = $year-1;

      $response["months"] = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec");
      $months = array("1","2","3","4","5","6","7","8","9","10","11","12");

      $result_this_year = $this->model_sales_report->get_customer_sales_by_month_year($year, $custno);
      $result_last_year = $this->model_sales_report->get_customer_sales_by_month_year($last_year, $custno);
      $result_last_2year = $this->model_sales_report->get_customer_sales_by_month_year($last_2year, $custno);

      // data this year
      unset($data_this_year);
      foreach($months as $row_month){
        $total = 0;
        foreach($result_this_year as $row){
            if($row["monthh"] == $row_month){
              $total = $row["amount"];
              break;
            }
        }
        $data_this_year[] = (int)$total;
      }
      //---

      // data last year
      unset($data_last_year);
      foreach($months as $row_month){
        $total = 0;
        foreach($result_last_year as $row){
            if($row["monthh"] == $row_month){
              $total = $row["amount"];
              break;
            }
        }
        $data_last_year[] = (int)$total;
      }
      //---

      // data last 2years
      unset($data_last_2year);
      foreach($months as $row_month){
        $total = 0;
        foreach($result_last_2year as $row){
            if($row["monthh"] == $row_month){
              $total = $row["amount"];
              break;
            }
        }
        $data_last_2year[] = (int)$total;
      }
      //---

      $response["this_year_text"]  = strval($year);
      $response["last_year_text"]  = strval($last_year);
      $response["last_2year_text"] = strval($last_2year);
      $response["this_year"] = $data_this_year;
      $response["last_year"] = $data_last_year;
      $response["last_2year"] = $data_last_2year;

      $response["btn_excel"] = "0"; $response["btn_copy"] = "0";
      if(isset($_SESSION['user_permis']["31"])) $response["btn_excel"] = "1";
      if(isset($_SESSION['user_permis']["32"])) $response["btn_copy"] = "1";

      echo json_encode($response);
  }
  //---

  function salesman_salesreport_data(){
      $this->model_zlog->insert("Gen Salesman Sales Report Data"); // insert log

      $cust_code = $_POST["cust_code"];
      $cust_name = $_POST["cust_name"];
      $year = $_POST["year"];
      $type = $_POST["type"];

      $last_year = $year-1;
      $last_2year = $year-2;
      $months = array("01","02","03","04","05","06","07","08","09","10","11","12");

      $today_lastyear = $last_year.date("-m-t");
      $month_name_last_year = date("F");
      $jan_date = $last_year."-01-01";

      if($type == "1") $result = $this->model_sales_report->get_salesman_sales_report($cust_code, $year, $last_year, $last_2year, $months,$today_lastyear,$jan_date);
      else if($type == "2") $result = $this->model_sales_report->get_salesman_sales_amount_report($cust_code, $year, $last_year, $last_2year, $months,$today_lastyear,$jan_date);

      $data["var_report"] = assign_data($result);

      $data["months"] = $months;
      $data["year"] = $year;
      $data["last_year"] = $last_year;
      $data["last_2year"] = $last_2year;
      $data["cust_code"] = $cust_code;
      $data["cust_name"] = $cust_name;
      $data["month_name_last_year"] = $month_name_last_year;

      if($type == 1){ $data["amount_format"] = 0; $data["comma_digit"] = 0; }
      else{ $data["amount_format"] = 0; $data["comma_digit"] = 2; }

      $this->load->view('sales/customer/dashboard/v_salesreport', $data);
  }
  //---

  function sales_item_cat(){
      $this->model_zlog->insert("Gen Customer Sales Item Cat"); // insert log

      $cust_code = $_POST["cust_code"];
      $cust_name = $_POST["cust_name"];
      $year = $_POST["year"];
      $type = $_POST["type"];

      $last_year = $year-1;
      $last_2year = $year-2;
      $months = array("01","02","03","04","05","06","07","08","09","10","11","12");

      $today_lastyear = $last_year.date("-m-t");
      $month_name_last_year = date("F");
      $jan_date = $last_year."-01-01";

      if($type == "1") $result = $this->model_sales_report->get_sales_report_by_item_cat_year($cust_code, $year, $last_year, $last_2year, $months,$today_lastyear,$jan_date);
      else if($type == "2") $result = $this->model_sales_report->get_sales_report_by_item_cat_year_amount($cust_code, $year, $last_year, $last_2year, $months,$today_lastyear,$jan_date);

      $data["var_report"] = assign_data($result);

      $data["months"] = $months;
      $data["year"] = $year;
      $data["last_year"] = $last_year;
      $data["last_2year"] = $last_2year;
      $data["cust_code"] = $cust_code;
      $data["cust_name"] = $cust_name;
      $data["month_name_last_year"] = $month_name_last_year;

      $first_character = substr($cust_code, 0, 1);
      $data["first_character"] = $first_character;

      if($type == 1){ $data["amount_format"] = 0; $data["comma_digit"] = 0; }
      else{ $data["amount_format"] = 0; $data["comma_digit"] = 2; }

      $this->load->view('sales/customer/dashboard/v_salesitemcatreport', $data);
  }
  //--

  function fill_rate_data(){
      $this->model_zlog->insert("Gen Customer Fill Rate"); // insert log
      $cust_code = $_POST["cust_code"];
      $year = $_POST["year"];
      $type = $_POST["type"];

      $result = $this->model_sales_report->customer_fill_rate($cust_code, $year, $type);
      $data["var_report"] = assign_data($result);

      $today_year = date("Y");
      $today_month = date("m");
      $result = $this->model_sales_report->get_so_bo_nav($today_year, $today_month, $cust_code, $type);
      if(count($result) == 0){
        $data["var_report2"] = array(
            "yearr" => $today_year,
            "month" => $today_month,
            "orderr" => 0,
            "proceed" => 0,
            "outstanding" => 0,
            "percent_fill_rate" => 0,
            "percent_outstanding" => 0,
        );
      }
      else $data["var_report2"] = assign_data_one($result);

      $result = $this->model_sales_report->get_bo_nav_by_customer($cust_code, $type);
      $data["var_bo"] = $result;

      $data["today_year"] = $today_year;
      $data["today_month"] = $today_month;

      $this->load->view('sales/customer/dashboard/v_fillrate', $data);
  }
  //--
}
