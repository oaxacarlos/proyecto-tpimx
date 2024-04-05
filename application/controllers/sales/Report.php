<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Report extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('model_sales_report', '', TRUE);
        $this->load->model('model_zlog', '', TRUE);
    }

    function custsalessum()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'custsalessum'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Customer Sales Summary"); // insert log
            $result = $this->model_sales_report->get_customer_nav_local();
            $data["var_customer_data"] = assign_data($result);

            $this->load->view('sales/report/custsalessum/v_index', $data);
        }
    }
    //---

    function custsalessum_data()
    {
        $this->model_zlog->insert("Gen Customer Sales Summary Data"); // insert log

        $cust_code = $_POST["cust_code"];
        $cust_name = $_POST["cust_name"];
        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");

        if ($type == "1") $result = $this->model_sales_report->get_customer_sales_summary_report($cust_code, $year, $last_year, $last_2year, $months);
        else if ($type == "2") $result = $this->model_sales_report->get_customer_sales_summary_amount_report($cust_code, $year, $last_year, $last_2year, $months);

        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;
        $data["cust_code"] = $cust_code;
        $data["cust_name"] = $cust_name;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/report/custsalessum/v_report', $data);
    }
    //---

    function custsalesreview_data()
    {
        $this->model_zlog->insert("Gen Customer Sales Review Data"); // insert log

        $cust_code = $_POST["cust_code"];
        $cust_name = $_POST["cust_name"];
        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        if ($type == "1") $result = $this->model_sales_report->get_customer_sales_review_qty_report($cust_code, $year, $last_year, $last_2year, $months);
        else if ($type == "2") $result = $this->model_sales_report->get_customer_sales_review_amount_report($cust_code, $year, $last_year, $last_2year, $months);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;
        $data["cust_code"] = $cust_code;
        $data["cust_name"] = $cust_name;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/report/custsalessum/v_slsreview_report', $data);
    }
    //---

    function productreview_data()
    {
        $this->model_zlog->insert("Gen Product Review Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        if ($type == "1") {
            $result = $this->model_sales_report->get_product_review_qty_report($year, $last_year, $last_2year, $months);
            $data["var_report"] = assign_data($result);

            $result = $this->model_sales_report->get_sales_report_by_item_cat_all_year("1", $year, $last_year, $last_2year, $months, $today_lastyear, $jan_date);
            $data["var_report_filter"] = assign_data($result);

            $result = $this->model_sales_report->get_sales_report_by_item_cat_all_year("2", $year, $last_year, $last_2year, $months, $today_lastyear, $jan_date);
            $data["var_report_belt"] = assign_data($result);
        } else if ($type == "2") {
            $result = $this->model_sales_report->get_product_review_amount_report($year, $last_year, $last_2year, $months);
            $data["var_report"] = assign_data($result);

            $result = $this->model_sales_report->get_sales_report_by_item_cat_all_year_amount("1", $year, $last_year, $last_2year, $months, $today_lastyear, $jan_date);
            $data["var_report_filter"] = assign_data($result);

            $result = $this->model_sales_report->get_sales_report_by_item_cat_all_year_amount("2", $year, $last_year, $last_2year, $months, $today_lastyear, $jan_date);
            $data["var_report_belt"] = assign_data($result);
        }


        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/report/custsalessum/v_productreview_report', $data);
    }
    //---

    function productreviewcust_data()
    {
        $this->model_zlog->insert("Gen Product Review Cust Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];
        $cust_code = $_POST["cust_code"];
        $cust_name = $_POST["cust_name"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        if ($type == "1") $result = $this->model_sales_report->get_product_review_cust_qty_report($year, $last_year, $last_2year, $months, $cust_code);
        else if ($type == "2") $result = $this->model_sales_report->get_product_review_cust_amount_report($year, $last_year, $last_2year, $months, $cust_code);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;
        $data["cust_code"] = $cust_code;
        $data["cust_name"] = $cust_name;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/report/custsalessum/v_productreviewcust_report', $data);
    }
    //---

    function custreview_data()
    {
        $this->model_zlog->insert("Gen Cust Review Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        $firstdate_this_year = date("Y-01-01");
        $lastdate_this_year = date("Y-12-31");

        // get last 6 months
        $today = get_date_now();
        $last_6months = get_last_6months_first_day($today);

        if ($type == "1") $result = $this->model_sales_report->get_cust_review_qty_report($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year);
        else if ($type == "2") $result = $this->model_sales_report->get_cust_review_amount_report($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/report/custsalessum/v_custreview_report', $data);
    }
    //---

    function salesmansalesdetail()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'salesmansalesdetail'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Salesman Sales Detail"); // insert log
            $session_data = $this->session->userdata('z_tpimx_logged_in');
            $user = $session_data['z_tpimx_user_id'];
            $sls_code = $this->model_sales_report->get_salesman_user($user);   // get user-salesman
            $result = $this->model_sales_report->get_customer_by_sls_person_code_nav_local($sls_code);
            $data["var_customer_data"] = assign_data($result);
            $data["var_sls_code"] = $sls_code;
            $this->load->view('sales/report/salesmansalesdetail/v_index', $data);
        }
    }
    //---

    function salesman_salesreport_data()
    {
        $this->model_zlog->insert("Gen Salesman Sales Report Data"); // insert log

        $cust_code = $_POST["cust_code"];
        $cust_name = $_POST["cust_name"];
        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");

        $today_lastyear = $last_year . date("-m-t");
        $month_name_last_year = date("F");
        $jan_date = $last_year . "-01-01";

        if ($type == "1") $result = $this->model_sales_report->get_salesman_sales_report($cust_code, $year, $last_year, $last_2year, $months, $today_lastyear, $jan_date);
        else if ($type == "2") $result = $this->model_sales_report->get_salesman_sales_amount_report($cust_code, $year, $last_year, $last_2year, $months, $today_lastyear, $jan_date);

        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;
        $data["cust_code"] = $cust_code;
        $data["cust_name"] = $cust_name;
        $data["month_name_last_year"] = $month_name_last_year;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/report/salesmansalesdetail/v_salesreport', $data);
    }
    //---

    function producttype_data()
    {
        $this->model_zlog->insert("Gen Product Type Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        $firstdate_this_year = date("Y-01-01");
        $lastdate_this_year = date("Y-12-31");

        // get last 6 months
        $today = get_date_now();
        $last_6months = get_last_6months_first_day($today);

        if ($type == "1") {
            $result = $this->model_sales_report->get_product_review_qty_report($year, $last_year, $last_2year, $months);
            $data["var_report_item"] = assign_data($result);

            $result = $this->model_sales_report->product_customer_sales_qty_report($year, $last_year, $last_2year, $months);
            foreach ($result as $row) {
                $var_report_cust[$row["item_no"]][] = array(
                    "customer" => $row['customer'],
                    "si_qty_last_2year" => $row["si_qty_last_2year"],
                    "si_qty_last_year" => $row["si_qty_last_year"],
                    "now_" . $year . "_01" => $row["now_" . $year . "_01"],
                    "now_" . $year . "_02" => $row["now_" . $year . "_02"],
                    "now_" . $year . "_03" => $row["now_" . $year . "_03"],
                    "now_" . $year . "_04" => $row["now_" . $year . "_04"],
                    "now_" . $year . "_05" => $row["now_" . $year . "_05"],
                    "now_" . $year . "_06" => $row["now_" . $year . "_06"],
                    "now_" . $year . "_07" => $row["now_" . $year . "_07"],
                    "now_" . $year . "_08" => $row["now_" . $year . "_08"],
                    "now_" . $year . "_09" => $row["now_" . $year . "_09"],
                    "now_" . $year . "_10" => $row["now_" . $year . "_10"],
                    "now_" . $year . "_11" => $row["now_" . $year . "_11"],
                    "now_" . $year . "_12" => $row["now_" . $year . "_12"],
                    "line_amount_this_year" => $row['line_amount_this_year'],
                    "line_cost_this_year" => $row['line_cost_this_year'],
                    "gp_percent" => $row["gp_percent"],
                );
            }
            $data["var_report_cust"] = $var_report_cust;
        } else if ($type == "2") {
            $result = $this->model_sales_report->get_product_review_amount_report($year, $last_year, $last_2year, $months);
            $data["var_report_item"] = assign_data($result);

            $result = $this->model_sales_report->get_cust_review_amount_report($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year);
            $data["var_report_cust"] = assign_data($result);
        }


        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/report/custsalessum/v_producttype_report', $data);
    }
    //---

    function salesweekly()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'salesweekly'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Sales Weekly"); // insert log

            $session_data = $this->session->userdata('z_tpimx_logged_in');
            $user = $session_data['z_tpimx_user_id'];
            $userid_1 = $session_data['z_tpimx_userid_1'];
            $slscode = $this->model_sales_report->get_salesman_user($user);   // get user-salesman

            $result = $this->model_sales_report->get_salesman_active($slscode);
            $data["var_salesman_data"] = assign_data($result);

            $result = $this->model_sales_report->cs_data_from_user($userid_1);
            $data["var_cs_data"] = assign_data($result);

            $this->load->view('sales/report/salesweekly/v_index', $data);
        }
    }
    //---

    function salesnational_view_salesvsbudget_data()
    {
        $this->model_zlog->insert("Gen Sales National Sales vs Budget Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_salesvsbudget_salesperson($year, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            /*foreach($result as $row){
              $sls_code = "'".$row["sales_person_code"]."'";
              $response["data"][] = array(
                "sales_person_code" => $sls_code,
                "sales_value" => $row["amount"],
                "target_value" => $row["tgt_value"]
              );
          }*/

            unset($sales_value);
            unset($target_value);

            foreach ($result as $row) {
                $sls_code = "'" . $row["sales_person_code"] . "'";

                $response["categories"][] = $sls_code;

                $sales_value[]  = (int)$row["amount"];
                $target_value[] = (int)$row["tgt_value"];

                /*$response["data"][] = array(
                "sales_value" => $row["amount"],
                "target_value" => $row["tgt_value"]
              );*/
            }


            $response["detail"][] = array(
                "name" => "Sales Value",
                "data" =>  $sales_value
            );

            $response["detail"][] = array(
                "name" => "Target Value",
                "data" =>   $target_value
            );
        }

        echo json_encode($response);
    }
    //---

    function salesnational_view_salesbycategory_data()
    {
        $this->model_zlog->insert("Gen Sales National Sales by Category Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_salesbycategory($year, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $response[] = array($row["item_category_code"], (int)$row["amount"]);
            }
        }

        echo json_encode($response);
    }
    //---

    function salesnational_view_actual_netsales_mtd_data()
    {
        $this->model_zlog->insert("Gen Sales National Net Sales MTD Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_actual_netsales_mtd($year, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales"] = $percentage_sales;
                $response["target"] = 100 - $percentage_sales;
                $response["salesvalue"] = round($row["amount"] / 1000);
                $response["targetvalue"] = round($row["tgt_value"] / 1000);
            }
        }

        echo json_encode($response);
    }
    //----

    function salesnational_view_actual_netsales_ytd_data()
    {
        $this->model_zlog->insert("Gen Sales National Net Sales YTD Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_actual_netsales_ytd($year, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales"] = $percentage_sales;
                $response["target"] = 100 - $percentage_sales;
                $response["salesvalue"] = round($row["amount"] / 1000);
                $response["targetvalue"] = round($row["tgt_value"] / 1000);
            }
        }

        echo json_encode($response);
    }
    //----

    function salesnational_view_actual_netsales_sakura_data()
    {
        $this->model_zlog->insert("Gen Sales National Net Sales MTD Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_actual_netsales_sakura($year, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            // calculate total
            $total = 0;
            foreach ($result as $row) {
                $total += $row["amount"];
            }

            //
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $total);
                $response[] = array(
                    "name" => $row["description"],
                    "y" => $percentage_sales,
                    "x" => $row["amount"]
                );
            }
        }

        echo json_encode($response);
    }
    //----

    function salesnational_view_actual_netsales_typ_data()
    {
        $this->model_zlog->insert("Gen Sales National Net Sales YTD Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_actual_netsales_typ($year, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            // calculate total
            $total = 0;
            foreach ($result as $row) {
                $total += $row["amount"];
            }

            //
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $total);
                $response[] = array(
                    "name" => $row["description"],
                    "y" => $percentage_sales,
                    "x" => $row["amount"]
                );
            }
        }

        echo json_encode($response);
    }
    //----

    function salesnational_view_salestrendvsbudget_data()
    {
        $this->model_zlog->insert("Gen Sales National Sales Trend vs Budget Data"); // insert log

        $year = $_POST["year"];

        $result = $this->model_sales_report->salesnational_salestrendvsbudget($year);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $response["target"][] = (int)$row["tgt_value"];
                $response["sales"][] = (int)$row["sales_amount"];
            }
        }

        echo json_encode($response);
    }
    //---

    function salesnational_view_dailysalestrend_data()
    {
        $this->model_zlog->insert("Gen Sales National Daily Sales Trend Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $last_2year = $year - 2;
        $last_year = $year - 1;
        $total_day_last_2year = cal_days_in_month(CAL_GREGORIAN, $month, $last_2year);
        $total_day_last_year = cal_days_in_month(CAL_GREGORIAN, $month, $last_year);
        $total_day_last_this_year = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $result_this_year = $this->model_sales_report->salesnational_sales_by_day_month($year, $month);
        $result_this_year = assign_data($result_this_year);

        $result_last_year = $this->model_sales_report->salesnational_sales_by_day_month($last_year, $month);
        $result_last_year = assign_data($result_last_year);

        $result_last_2year = $this->model_sales_report->salesnational_sales_by_day_month($last_2year, $month);
        $result_last_2year = assign_data($result_last_2year);

        // get total day with 3 years
        $total_day = "";
        if ($total_day_last_this_year >= $total_day_last_year) $total_day = $total_day_last_this_year;
        else $total_day = $total_day_last_year;

        if ($total_day < $total_day_last_2year) $total_day = $total_day_last_2year;
        //--

        unset($data);

        // this year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_this_year as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_this_year[] = (int)$total;
        }
        //--

        // last year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_last_year as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_last_year[] = (int)$total;
        }
        //--

        // last 2 year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_last_2year as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_last_2year[] = (int)$total;
        }
        //--

        $response["this_year"] = $data_this_year;
        $response["last_year"] = $data_last_year;
        $response["last_2year"] = $data_last_2year;
        $response["this_year_text"] = strval($year);
        $response["last_year_text"] = strval($last_year);
        $response["last_2year_text"] = strval($last_2year);
        echo json_encode($response);
    }
    //----

    function salesnational_view_actual_netsales_salesbygeographic()
    {
        $this->model_zlog->insert("Gen Sales National Net Sales by Geographic Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_sales_geographic_mtd($year, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $response[] = array($row['id'], (int)$row['amount']);
            }
        }

        echo json_encode($response);
    }
    //---

    function report_salesman_by_category_data()
    {
        $this->model_zlog->insert("Gen Salesman by Category Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->salesman_salesbycategory($year, $month, $slscode);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $response[] = array($row["item_category_code"], (int)$row["amount"]);
            }
        }

        echo json_encode($response);
    }
    //---

    function report_salesman_daily_trend_3months_data()
    {
        $this->model_zlog->insert("Gen Salesman Daily Trend 3 Months Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $last_2year = $year - 2;
        $last_year = $year - 1;
        $total_day_last_2year = cal_days_in_month(CAL_GREGORIAN, $month, $last_2year);
        $total_day_last_year = cal_days_in_month(CAL_GREGORIAN, $month, $last_year);
        $total_day_last_this_year = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $result_this_year = $this->model_sales_report->salesman_sales_by_day_month($year, $month, $slscode);
        $result_this_year = assign_data($result_this_year);

        $result_last_year = $this->model_sales_report->salesman_sales_by_day_month($last_year, $month, $slscode);
        $result_last_year = assign_data($result_last_year);

        $result_last_2year = $this->model_sales_report->salesman_sales_by_day_month($last_2year, $month, $slscode);
        $result_last_2year = assign_data($result_last_2year);

        // get total day with 3 years
        $total_day = "";
        if ($total_day_last_this_year >= $total_day_last_year) $total_day = $total_day_last_this_year;
        else $total_day = $total_day_last_year;

        if ($total_day < $total_day_last_2year) $total_day = $total_day_last_2year;
        //--

        unset($data);

        // this year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_this_year as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_this_year[] = (int)$total;
        }
        //--

        // last year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_last_year as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_last_year[] = (int)$total;
        }
        //--

        // last 2 year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_last_2year as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_last_2year[] = (int)$total;
        }
        //--

        $response["this_year"] = $data_this_year;
        $response["last_year"] = $data_last_year;
        $response["last_2year"] = $data_last_2year;
        $response["this_year_text"] = strval($year);
        $response["last_year_text"] = strval($last_year);
        $response["last_2year_text"] = strval($last_2year);
        echo json_encode($response);
    }
    //---

    function report_salesman_customer_active_data()
    {
        $this->model_zlog->insert("Gen Salesman Customer Active Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->salesman_customer_buy_and_nobuy($year, $month, $slscode);
        $response["total"] = (int)$result["total"];
        $response["buy"] = (int)$result["buy"];
        $response["notbuy"] = (int)$result["notbuy"];

        echo json_encode($response);
    }
    //---

    function report_salesman_top_20customers_data()
    {
        $this->model_zlog->insert("Gen Salesman Top 20 Customers Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $date = $year . "-" . $month . "-01";
        $last_month_from = get_last_month_first_day($date);
        $last_month_to = get_last_month_last_day($date);
        $last_2months_from = get_last_2months_first_day($date);
        $last_2months_to = get_last_2months_last_day($date);

        $result = $this->model_sales_report->salesman_customer_top20($year, $month, $last_month_from, $last_month_to, $last_2months_from, $last_2months_to, $slscode);
        $result = assign_data($result);

        foreach ($result as $row) {
            $response["categories"][] = $row["name"];
            $data_this_month[] = (int)$row["amount"];
            $data_last_month[] = (int)$row["amount_last_month"];
            $data_last_2months[] = (int)$row["amount_last_2month"];
        }

        $response["this_month"] = $data_this_month;
        $response["last_month"] = $data_last_month;
        $response["last_2months"] = $data_last_2months;
        $response["this_month_name"] = date("F", strtotime($date));
        $response["last_month_name"] = date("F", strtotime($last_month_from));
        $response["last_2months_name"] = date("F", strtotime($last_2months_from));

        echo json_encode($response);
    }
    //----

    function report_salesman_top_40items_data()
    {
        $this->model_zlog->insert("Gen Salesman Top 40 Items Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $date = $year . "-" . $month . "-01";
        $last_month_from = get_last_month_first_day($date);
        $last_month_to = get_last_month_last_day($date);
        $last_2months_from = get_last_2months_first_day($date);
        $last_2months_to = get_last_2months_last_day($date);

        $result = $this->model_sales_report->salesman_item_top40($year, $month, $last_month_from, $last_month_to, $last_2months_from, $last_2months_to, $slscode);
        $result = assign_data($result);

        foreach ($result as $row) {
            $response["categories"][] = $row["item_no"];
            $data_this_month[] = (int)$row["amount"];
            $data_last_month[] = (int)$row["amount_last_month"];
            $data_last_2months[] = (int)$row["amount_last_2month"];
        }

        $response["this_month"] = $data_this_month;
        $response["last_month"] = $data_last_month;
        $response["last_2months"] = $data_last_2months;
        $response["this_month_name"] = date("F", strtotime($date));
        $response["last_month_name"] = date("F", strtotime($last_month_from));
        $response["last_2months_name"] = date("F", strtotime($last_2months_from));

        echo json_encode($response);
    }
    //----

    function report_salesman_rating_data()
    {
        $this->model_zlog->insert("Gen Salesman Rating Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $month_before = get_counting_months_before($month);

        $result = $this->model_sales_report->salesman_target_mtd($year, $month, $slscode);
        $response["tgt_value"] = number_format($result);
        $tgt_mtd = $result;

        $result = $this->model_sales_report->salesman_sales_mtd($year, $month, $slscode);
        $response["sales_mtd"] = number_format($result);
        $sales_mtd = $result;

        $response["percentage_mtd"] = percentage($sales_mtd, $tgt_mtd) . "%";

        //$result = $this->model_sales_report->salesman_target_ytd($year, $slscode);
        $result = $this->model_sales_report->salesman_target_ytd_ver2($year, $slscode, $month_before);
        $response["tgt_value_ytd"] = number_format($result);
        $tgt_ytd = $result;

        $result = $this->model_sales_report->salesman_sales_ytd($year, $slscode);
        $response["sales_ytd"] = number_format($result);
        $sales_ytd = $result;

        $response["percentage_ytd"] = percentage($sales_ytd, $tgt_ytd) . "%";

        echo json_encode($response);
    }
    //---

    function report_salesman_weekly_performance_data()
    {
        $this->model_zlog->insert("Gen Salesman Weekly Performance Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        // calculate weeks and days
        $period = get_week_in_month($year, $month);
        $total_working_days = get_total_working_days_a_month($year, $month);

        for ($i = 0; $i < count($period); $i++) {
            $day = get_working_days_between_date($period[$i]["from"], $period[$i]["to"], FALSE, NULL);
            $period[$i]["day"] = $day;
        }
        //---

        // calculate target
        $target_value = $this->model_sales_report->salesman_target_mtd($year, $month, $slscode);
        $target_value_per_day = $target_value / $total_working_days;
        $total_target = 0;
        for ($i = 0; $i < count($period); $i++) {
            $total_target += $period[$i]["day"] * $target_value_per_day;
            $response["target"][] = array((int)$total_target);
            $response["week"][] = $i + 1;
        }
        //---

        // calculate sales
        $sales_value = 0;
        for ($i = 0; $i < count($period); $i++) {
            $sales_value += $this->model_sales_report->salesman_sales_mtd_by_period($period[$i]["from"], $period[$i]["to"], $slscode);
            $response["sales"][] = array((int)$sales_value);
        }
        //-----

        echo json_encode($response);
    }
    //---

    function report_salesman_daily_trend_last_3months_data()
    {
        $this->model_zlog->insert("Gen Salesman Daily Trend Last 3Months Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $date = $year . "-" . $month . "-01";
        $last_month_from = get_last_month_first_day($date);
        $last_month_to = get_last_month_last_day($date);
        $last_2months_from = get_last_2months_first_day($date);
        $last_2months_to = get_last_2months_last_day($date);

        $total_day_last_2month = cal_days_in_month(CAL_GREGORIAN, substr($last_2months_from, 5, 2), substr($last_2months_from, 0, 4));
        $total_day_last_month = cal_days_in_month(CAL_GREGORIAN, substr($last_month_from, 5, 2), substr($last_month_from, 0, 4));
        $total_day_last_this_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $result_this_month = $this->model_sales_report->salesman_sales_by_day_month($year, $month, $slscode);
        $result_this_month = assign_data($result_this_month);

        $result_last_month = $this->model_sales_report->salesman_sales_by_period($last_month_from, $last_month_to, $slscode);
        $result_last_month = assign_data($result_last_month);

        $result_last_2month = $this->model_sales_report->salesman_sales_by_period($last_2months_from, $last_2months_to, $slscode);
        $result_last_2month = assign_data($result_last_2month);

        // get total day with 3 month
        $total_day = "";
        if ($total_day_last_this_month >= $total_day_last_month) $total_day = $total_day_last_this_month;
        else $total_day = $total_day_last_month;

        if ($total_day < $total_day_last_2month) $total_day = $total_day_last_2month;
        //--

        unset($data);

        // this year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_this_month as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_this_month[] = (int)$total;
        }
        //--

        // last year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_last_month as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_last_month[] = (int)$total;
        }
        //--

        // last 2 year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_last_2month as $row) {
                if ($row["dayy"] == $i) {
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

    function salesman_customerreport_data()
    {
        $this->model_zlog->insert("Gen Salesman Customer Report Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];
        $sls_code = $_POST['sls_code'];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        $firstdate_this_year = date("Y-01-01");
        $lastdate_this_year = date("Y-12-31");

        // get last 6 months
        $today = get_date_now();
        $last_6months = get_last_6months_first_day($today);

        if ($type == "1") $result = $this->model_sales_report->get_cust_review_qty_report_by_salesman($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year, $sls_code);
        else if ($type == "2") $result = $this->model_sales_report->get_cust_review_amount_report_by_salesman($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year, $sls_code);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/report/salesmansalesdetail/v_custreview_report', $data);
    }
    //---

    function salesnational_view_dailysalesorder_data()
    {
        $this->model_zlog->insert("Gen Sales National Daily Sales Order Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->get_sales_order_daily_from_navision($year, $month);
        $data["var_detail"] = assign_data($result);
        $this->load->view('sales/report/salesweekly/v_salesorder_daily_detail', $data);
    }
    //---

    function report_salesman_salesorder_daily_data()
    {
        $this->model_zlog->insert("Gen Salesman Sales Order Daily Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST['slscode'];

        $result = $this->model_sales_report->get_sales_order_daily_by_salesman_from_navision($year, $month, $slscode);
        $data["var_detail"] = assign_data($result);
        $this->load->view('sales/report/salesweekly/v_salesman_salesorder_daily_detail', $data);
    }
    //---

    function salesnational_view_total_invoice_cn_nett()
    {
        $this->model_zlog->insert("Gen Sales National Total Invoice CN Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $month_now = date("m");
        $year_now = date("Y");

        $data["total_wms_value"] = 0;
        $data["total_sls_shipment"] = 0;

        $result = $this->model_sales_report->get_invoice_cn_nett_from_navision($year, $month);
        if (count($result) > 0) {
            $data["var_detail"] = assign_data_one($result);
        } else {
            $data["var_detail"] = array(
                "yearr" => $year,
                "monthh" => $month,
                "total_invoice" => 0,
                "total_cm" => 0,
                "total_nett" => 0
            );
        }

        if ($year == $year_now && $month == $month_now) {
            $data["total_wms_value"] = $this->model_sales_report->get_value_wms_from_navision();
            $data["total_sls_shipment"] = $this->model_sales_report->get_value_sls_shipment_not_invoice_from_navision();
        }

        $data["year"] = $year;
        $data["month"] = $month;

        // 2023-06-02 get total BO
        $data["total_bo_value"] = $this->model_sales_report->get_total_value_backorder_without_stock();
        //--

        $this->load->view('sales/report/salesweekly/v_invoice_cn_nett', $data);
    }
    //---

    function salesnational_view_total_value_wms()
    {
        $this->model_zlog->insert("Gen Sales National Total Value WMS Data"); // insert log

        $result = $this->model_sales_report->get_value_wms_from_navision();
        $data["var_detail"] = assign_data_one($result);
        $this->load->view('sales/report/salesweekly/v_value_wms', $data);
    }
    //---

    // CS
    function report_cs_rating_data()
    {
        $this->model_zlog->insert("Gen CS Rating Report Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $month_before = get_counting_months_before($month);

        $result = $this->model_sales_report->cs_target_mtd($year, $month, $slscode);
        $response["tgt_value"] = number_format($result);
        $tgt_mtd = $result;

        $result = $this->model_sales_report->cs_sales_mtd($year, $month, $slscode);
        $response["sales_mtd"] = number_format($result);
        $sales_mtd = $result;

        $response["percentage_mtd"] = percentage($sales_mtd, $tgt_mtd) . "%";

        //$result = $this->model_sales_report->salesman_target_ytd($year, $slscode);
        $result = $this->model_sales_report->cs_target_ytd_ver2($year, $slscode, $month_before);
        $response["tgt_value_ytd"] = number_format($result);
        $tgt_ytd = $result;

        $result = $this->model_sales_report->cs_sales_ytd($year, $slscode);
        $response["sales_ytd"] = number_format($result);
        $sales_ytd = $result;

        $response["percentage_ytd"] = percentage($sales_ytd, $tgt_ytd) . "%";

        echo json_encode($response);
    }
    //---

    function report_cs_by_category_data()
    {
        $this->model_zlog->insert("Gen CS by Category Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->cs_salesbycategory($year, $month, $slscode);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $response[] = array($row["item_category_code"], (int)$row["amount"]);
            }
        }

        echo json_encode($response);
    }
    //---

    function report_cs_weekly_performance_data()
    {
        $this->model_zlog->insert("Gen CS Weekly Performance Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        // calculate weeks and days
        $period = get_week_in_month($year, $month);
        $total_working_days = get_total_working_days_a_month($year, $month);

        for ($i = 0; $i < count($period); $i++) {
            $day = get_working_days_between_date($period[$i]["from"], $period[$i]["to"], FALSE, NULL);
            $period[$i]["day"] = $day;
        }
        //---

        // calculate target
        $target_value = $this->model_sales_report->cs_target_mtd($year, $month, $slscode);
        $target_value_per_day = $target_value / $total_working_days;
        $total_target = 0;
        for ($i = 0; $i < count($period); $i++) {
            $total_target += $period[$i]["day"] * $target_value_per_day;
            $response["target"][] = array((int)$total_target);
            $response["week"][] = $i + 1;
        }
        //---

        // calculate sales
        $sales_value = 0;
        for ($i = 0; $i < count($period); $i++) {
            $sales_value += $this->model_sales_report->cs_sales_mtd_by_period($period[$i]["from"], $period[$i]["to"], $slscode);
            $response["sales"][] = array((int)$sales_value);
        }
        //-----

        echo json_encode($response);
    }
    //---

    function report_cs_daily_trend_3months_data()
    {
        $this->model_zlog->insert("Gen CS Daily Trend 3 Months Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $last_2year = $year - 2;
        $last_year = $year - 1;
        $total_day_last_2year = cal_days_in_month(CAL_GREGORIAN, $month, $last_2year);
        $total_day_last_year = cal_days_in_month(CAL_GREGORIAN, $month, $last_year);
        $total_day_last_this_year = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $result_this_year = $this->model_sales_report->cs_sales_by_day_month($year, $month, $slscode);
        $result_this_year = assign_data($result_this_year);

        $result_last_year = $this->model_sales_report->cs_sales_by_day_month($last_year, $month, $slscode);
        $result_last_year = assign_data($result_last_year);

        $result_last_2year = $this->model_sales_report->cs_sales_by_day_month($last_2year, $month, $slscode);
        $result_last_2year = assign_data($result_last_2year);

        // get total day with 3 years
        $total_day = "";
        if ($total_day_last_this_year >= $total_day_last_year) $total_day = $total_day_last_this_year;
        else $total_day = $total_day_last_year;

        if ($total_day < $total_day_last_2year) $total_day = $total_day_last_2year;
        //--

        unset($data);

        // this year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_this_year as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_this_year[] = (int)$total;
        }
        //--

        // last year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_last_year as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_last_year[] = (int)$total;
        }
        //--

        // last 2 year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_last_2year as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_last_2year[] = (int)$total;
        }
        //--

        $response["this_year"] = $data_this_year;
        $response["last_year"] = $data_last_year;
        $response["last_2year"] = $data_last_2year;
        $response["this_year_text"] = strval($year);
        $response["last_year_text"] = strval($last_year);
        $response["last_2year_text"] = strval($last_2year);
        echo json_encode($response);
    }
    //---

    function report_cs_customer_active_data()
    {
        $this->model_zlog->insert("Gen CS Customer Active Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->cs_customer_buy_and_nobuy($year, $month, $slscode);
        $response["total"] = (int)$result["total"];
        $response["buy"] = (int)$result["buy"];
        $response["notbuy"] = (int)$result["notbuy"];

        echo json_encode($response);
    }
    //---

    function report_cs_top_20customers_data()
    {
        $this->model_zlog->insert("Gen CS Top 20 Customers Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $date = $year . "-" . $month . "-01";
        $last_month_from = get_last_month_first_day($date);
        $last_month_to = get_last_month_last_day($date);
        $last_2months_from = get_last_2months_first_day($date);
        $last_2months_to = get_last_2months_last_day($date);

        $result = $this->model_sales_report->cs_customer_top20($year, $month, $last_month_from, $last_month_to, $last_2months_from, $last_2months_to, $slscode);
        $result = assign_data($result);

        foreach ($result as $row) {
            $response["categories"][] = $row["name"];
            $data_this_month[] = (int)$row["amount"];
            $data_last_month[] = (int)$row["amount_last_month"];
            $data_last_2months[] = (int)$row["amount_last_2month"];
        }

        $response["this_month"] = $data_this_month;
        $response["last_month"] = $data_last_month;
        $response["last_2months"] = $data_last_2months;
        $response["this_month_name"] = date("F", strtotime($date));
        $response["last_month_name"] = date("F", strtotime($last_month_from));
        $response["last_2months_name"] = date("F", strtotime($last_2months_from));

        echo json_encode($response);
    }
    //----

    function report_cs_top_40items_data()
    {
        $this->model_zlog->insert("Gen CS Top 40 Items Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $date = $year . "-" . $month . "-01";
        $last_month_from = get_last_month_first_day($date);
        $last_month_to = get_last_month_last_day($date);
        $last_2months_from = get_last_2months_first_day($date);
        $last_2months_to = get_last_2months_last_day($date);

        $result = $this->model_sales_report->cs_item_top40($year, $month, $last_month_from, $last_month_to, $last_2months_from, $last_2months_to, $slscode);
        $result = assign_data($result);

        foreach ($result as $row) {
            $response["categories"][] = $row["item_no"];
            $data_this_month[] = (int)$row["amount"];
            $data_last_month[] = (int)$row["amount_last_month"];
            $data_last_2months[] = (int)$row["amount_last_2month"];
        }

        $response["this_month"] = $data_this_month;
        $response["last_month"] = $data_last_month;
        $response["last_2months"] = $data_last_2months;
        $response["this_month_name"] = date("F", strtotime($date));
        $response["last_month_name"] = date("F", strtotime($last_month_from));
        $response["last_2months_name"] = date("F", strtotime($last_2months_from));

        echo json_encode($response);
    }
    //----

    function report_cs_salesorder_daily_data()
    {
        $this->model_zlog->insert("Gen CS Sales Order Daily Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST['slscode'];

        $result = $this->model_sales_report->get_sales_order_daily_by_cs_from_navision($year, $month, $slscode);
        $data["var_detail"] = assign_data($result);
        $this->load->view('sales/report/salesweekly/v_cs_salesorder_daily_detail', $data);
    }
    //---

    function report_cs_daily_trend_last_3months_data()
    {
        $this->model_zlog->insert("Gen CS Daily Trend Last 3 Months Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $date = $year . "-" . $month . "-01";
        $last_month_from = get_last_month_first_day($date);
        $last_month_to = get_last_month_last_day($date);
        $last_2months_from = get_last_2months_first_day($date);
        $last_2months_to = get_last_2months_last_day($date);

        $total_day_last_2month = cal_days_in_month(CAL_GREGORIAN, substr($last_2months_from, 5, 2), substr($last_2months_from, 0, 4));
        $total_day_last_month = cal_days_in_month(CAL_GREGORIAN, substr($last_month_from, 5, 2), substr($last_month_from, 0, 4));
        $total_day_last_this_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $result_this_month = $this->model_sales_report->cs_sales_by_day_month($year, $month, $slscode);
        $result_this_month = assign_data($result_this_month);

        $result_last_month = $this->model_sales_report->cs_sales_by_period($last_month_from, $last_month_to, $slscode);
        $result_last_month = assign_data($result_last_month);

        $result_last_2month = $this->model_sales_report->cs_sales_by_period($last_2months_from, $last_2months_to, $slscode);
        $result_last_2month = assign_data($result_last_2month);

        // get total day with 3 month
        $total_day = "";
        if ($total_day_last_this_month >= $total_day_last_month) $total_day = $total_day_last_this_month;
        else $total_day = $total_day_last_month;

        if ($total_day < $total_day_last_2month) $total_day = $total_day_last_2month;
        //--

        unset($data);

        // this year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_this_month as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_this_month[] = (int)$total;
        }
        //--

        // last year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_last_month as $row) {
                if ($row["dayy"] == $i) {
                    $total += $row["amount"];
                    break;
                }
            }

            $data_last_month[] = (int)$total;
        }
        //--

        // last 2 year
        $total = 0;
        for ($i = 1; $i <= $total_day; $i++) {
            foreach ($result_last_2month as $row) {
                if ($row["dayy"] == $i) {
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

    // 2023-03-10
    function get_custproductcat_filter_data()
    {
        $this->model_zlog->insert("Gen Cust Product Cat Filter Data"); // insert log

        $year = $_POST["year"];
        $cust_code = $_POST["cust_code"];
        $name = "FILTRO";

        $cat = $this->model_sales_report->get_category_by_name($name);

        $data["var_cat_all"] = $this->model_sales_report->get_custproductcat_all($year, $cat, $name, $cust_code);
        $data["var_cat_cust"] = $this->model_sales_report->get_custproductcat_customer($year, $cat, $name, $cust_code);
        $data["var_cat"] = $cat;

        $this->load->view('sales/report/custsalessum/v_custproductcat_filter', $data);
    }
    //--

    function get_custproductcat_banda_data()
    {
        $this->model_zlog->insert("Gen Cust Product Cat Banda Data"); // insert log

        $year = $_POST["year"];
        $cust_code = $_POST["cust_code"];
        $name = "BANDA";

        $cat = $this->model_sales_report->get_category_by_name($name);

        $data["var_cat_all"] = $this->model_sales_report->get_custproductcat_all($year, $cat, $name, $cust_code);
        $data["var_cat_cust"] = $this->model_sales_report->get_custproductcat_customer($year, $cat, $name, $cust_code);
        $data["var_cat"] = $cat;

        $this->load->view('sales/report/custsalessum/v_custproductcat_banda', $data);
    }
    //---

    function get_custproductcat_prontopago_data()
    {
        $this->model_zlog->insert("Gen Cust Product Cat ProntoPago Data"); // insert log

        $year = $_POST["year"];
        $cust_code = $_POST["cust_code"];

        $data["pronto_pago"]  = $this->model_sales_report->get_pronto_pago($year, $cust_code);
        $data["volumen"]      = $this->model_sales_report->get_volumen($year, $cust_code);
        $data["promo_data"] = assign_data_one($this->model_sales_report->get_item_promo_amount_qty($year, $cust_code));

        $this->load->view('sales/report/custsalessum/v_custproductcat_prontopago', $data);
    }
    //---

    function itemreview()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'itemreview'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Item Review"); // insert log
            $this->load->view('sales/report/itemreview/v_index', $data);
        }
    }
    //---

    function itemreview_data()
    {
        $this->model_zlog->insert("Gen Item Review Data"); // insert log

        $year = $_POST["year"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $last_3year = $year - 3;
        $last_4year = $year - 4;

        $result = $this->model_sales_report->get_item_review($year, $last_year, $last_2year, $last_3year, $last_4year);
        $data["var_data"] = assign_data($result);

        $data["this_year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;
        $data["last_3year"] = $last_3year;
        $data["last_4year"] = $last_4year;

        $this->load->view('sales/report/itemreview/v_report', $data);
    }
    //---

    function sellingprice()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'sellingprice'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Selling Price"); // insert log

            $result = $this->model_sales_report->get_customer_nav_local();
            $data["var_customer_data"] = assign_data($result);

            $result = $this->model_sales_report->get_item_nav_local();
            $data["var_item_data"] = assign_data($result);

            $this->load->view('sales/report/sellingprice/v_index', $data);
        }
    }
    //----

    function sellingprice_data()
    {
        $this->model_zlog->insert("Gen Selling Price Data"); // insert log

        $customer = $_POST["cust_no"];
        $item = $_POST["item_code"];

        $result = $this->model_sales_report->get_selling_price_cust_item($customer, $item);
        $data["var_data"] = assign_data($result);

        $this->load->view('sales/report/sellingprice/v_report', $data);
    }
    //---

    function crossreference()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'crossreference'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Cross Reference"); // insert log

            $this->load->view('sales/report/crossreference/v_index');
        }
    }
    //---

    function crossreference_data()
    {
        $this->model_zlog->insert("Gen Cross Reference Data"); // insert log

        $search = $_POST["search"];

        $company = $this->model_sales_report->get_crosref_company();
        $data["var_company"] = assign_data($company);

        $result = $this->model_sales_report->get_cross_reference_with_search($company, $search);
        $data["var_report"] = assign_data($result);

        $this->load->view('sales/report/crossreference/v_report', $data);
    }
    //---

    function application()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'application'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Application"); // insert log

            $result = $this->model_sales_report->get_application_distinct();
            $data["var_application"] = assign_data($result);
            $this->load->view('sales/report/application/v_index', $data);
        }
    }
    //---

    function application_data()
    {
        $this->model_zlog->insert("Gen Application Data"); // insert log

        $search = $_POST["search"];

        $result = $this->model_sales_report->get_application_by_search($search);
        $data["var_report"] = assign_data($result);

        $this->load->view('sales/report/application/v_report', $data);
    }
    //---

    function invoice()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'invoice'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Invoice"); // insert log

            $session_data = $this->session->userdata('z_tpimx_logged_in');
            $user = $session_data['z_tpimx_user_id'];
            $userid_1 = $session_data['z_tpimx_userid_1'];

            $data["var_user"] = $userid_1;
            $data["var_customer"] = $this->model_sales_report->get_customer_by_cs_code_nav_local($userid_1);

            $this->load->view('sales/report/invoice/v_index', $data);
        }
    }
    //---

    function invoice_data()
    {
        $this->model_zlog->insert("Gen Invoice Data"); // insert log

        $cust_no = $_POST["cust_no"];
        $from = $_POST["from"];
        $to = $_POST["to"];
        $doc_type = $_POST["doc_type"];
        $brand = $_POST["brand"];

        if ($cust_no == "ALL") $cust_no = "";

        if ($brand == "all") {
            $brand = "";
            $brand_not = "";
        } else if ($brand == "B") {
            $brand = "TYP";
            $brand_not = "";
        } else if ($brand == "F") {
            $brand = "TYP";
            $brand_not = "not";
        }


        $result = $this->model_sales_report->get_invoices_by_period_customer($from, $to, $cust_no, $doc_type, $brand, $brand_not);
        $data["var_report"] = assign_data($result);

        $this->load->view('sales/report/invoice/v_report', $data);
    }
    //--

    function contribution()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'contribution'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Contribution"); // insert log

            $this->load->view('sales/report/contribution/v_index', $data);
        }
    }
    //---

    function contribution_data()
    {
        $this->model_zlog->insert("Contribution Data"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];
        $brand = $_POST["brand"];
        $cat = $_POST["cat"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        if ($type == "1") $result = $this->model_sales_report->get_8020_review_qty_report($year, $last_year, $last_2year, $months, $brand, $cat);
        else if ($type == "2") $result = $this->model_sales_report->get_8020_review_amount_report($year, $last_year, $last_2year, $months, $brand, $cat);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;
        $data["type"] = $type;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 1;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/report/contribution/v_report', $data);
    }
    //---

    function itemmoving()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'itemmoving'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Item Moving"); // insert log

            $today = date("Y-m-d");
            $last_120days = date("Y-m-d", strtotime($today . " -120 days"));
            $last_180days = date("Y-m-d", strtotime($today . " -180 days"));
            $last_240days = date("Y-m-d", strtotime($today . " -240 days"));

            $result = $this->model_sales_report->get_item_moving($today, $last_120days, $last_180days, $last_240days);
            $data["var_report"] = assign_data($result);

            $this->load->view('sales/report/itemmoving/v_index', $data);
        }
    }
    //---

    // 2023-05-10
    function custreview_data2()
    {
        $this->model_zlog->insert("Gen Cust Review Data 2"); // insert log

        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        $firstdate_this_year = date("Y-01-01");
        $lastdate_this_year = date("Y-12-31");

        // get last 6 months
        $today = get_date_now();
        $last_6months = get_last_6months_first_day($today);

        if ($type == "1") $result = $this->model_sales_report->get_cust_review2_qty_report($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year);
        else if ($type == "2") $result = $this->model_sales_report->get_cust_review2_amount_report($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year);
        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 1;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/report/custsalessum/v_custreview2_report', $data);
    }
    //---

    // 2023-05-25
    function salesnational_view_salesvsbudget_filter_data()
    {
        $this->model_zlog->insert("Gen Sales National Sales vs Budget Filter Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_salesvsbudget_salesperson_type($year, $month, "F", "1");
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            unset($sales_value);
            unset($target_value);

            foreach ($result as $row) {
                $sls_code = "'" . $row["sales_person_code"] . "'";

                $response["categories"][] = $sls_code;

                $sales_value[]  = (int)$row["amount"];
                $target_value[] = (int)$row["tgt_value"];
            }


            $response["detail"][] = array(
                "name" => "Sales Value",
                "data" =>  $sales_value
            );

            $response["detail"][] = array(
                "name" => "Target Value",
                "data" =>   $target_value
            );
        }

        echo json_encode($response);
    }
    //---

    // 2023-05-25
    function salesnational_view_salesvsbudget_belt_data()
    {
        $this->model_zlog->insert("Gen Sales National Sales vs Budget Belt Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_salesvsbudget_salesperson_type($year, $month, "B", "2");
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            unset($sales_value);
            unset($target_value);

            foreach ($result as $row) {
                $sls_code = "'" . $row["sales_person_code"] . "'";

                $response["categories"][] = $sls_code;

                $sales_value[]  = (int)$row["amount"];
                $target_value[] = (int)$row["tgt_value"];
            }


            $response["detail"][] = array(
                "name" => "Sales Value",
                "data" =>  $sales_value
            );

            $response["detail"][] = array(
                "name" => "Target Value",
                "data" =>   $target_value
            );
        }

        echo json_encode($response);
    }
    //---

    // 2023-05-25
    function salesnational_view_salestrendvsbudget_filter_data()
    {
        $this->model_zlog->insert("Gen Sales National Sales Trend vs Budget Filter Data"); // insert log

        $year = $_POST["year"];

        $result = $this->model_sales_report->salesnational_salestrendvsbudget_type($year, "F", "1");
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $response["target"][] = (int)$row["tgt_value"];
                $response["sales"][] = (int)$row["sales_amount"];
            }
        }

        echo json_encode($response);
    }
    //---

    // 2023-05-25
    function salesnational_view_salestrendvsbudget_belt_data()
    {
        $this->model_zlog->insert("Gen Sales National Sales Trend vs Budget Belt Data"); // insert log

        $year = $_POST["year"];

        $result = $this->model_sales_report->salesnational_salestrendvsbudget_type($year, "B", "2");
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $response["target"][] = (int)$row["tgt_value"];
                $response["sales"][] = (int)$row["sales_amount"];
            }
        }

        echo json_encode($response);
    }
    //---

    // 2023-05-25
    function salesnational_view_actual_netsales_mtd_filter_data()
    {
        $this->model_zlog->insert("Gen Sales National Actual Net Sales MTD Filter Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_actual_netsales_mtd_type($year, $month, "F", "1");
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales"] = $percentage_sales;
                $response["target"] = 100 - $percentage_sales;
                $response["salesvalue"] = round($row["amount"] / 1000);
                $response["targetvalue"] = round($row["tgt_value"] / 1000);
            }
        }

        echo json_encode($response);
    }
    //----

    // 2023-05-25
    function salesnational_view_actual_netsales_ytd_filter_data()
    {
        $this->model_zlog->insert("Gen Sales National Actual Net Sales YTD Filter Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_actual_netsales_ytd_type($year, $month, "F", "1");
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales"] = $percentage_sales;
                $response["target"] = 100 - $percentage_sales;
                $response["salesvalue"] = round($row["amount"] / 1000);
                $response["targetvalue"] = round($row["tgt_value"] / 1000);
            }
        }

        echo json_encode($response);
    }
    //----

    // 2023-05-25
    function salesnational_view_actual_netsales_mtd_belt_data()
    {
        $this->model_zlog->insert("Gen Sales National Actual Net Sales MTD Belt Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_actual_netsales_mtd_type($year, $month, "B", "2");
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales"] = $percentage_sales;
                $response["target"] = 100 - $percentage_sales;
                $response["salesvalue"] = round($row["amount"] / 1000);
                $response["targetvalue"] = round($row["tgt_value"] / 1000);
            }
        }

        echo json_encode($response);
    }
    //----

    // 2023-05-25
    function salesnational_view_actual_netsales_ytd_belt_data()
    {
        $this->model_zlog->insert("Gen Sales National Actual Net Sales YTD Belt Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $result = $this->model_sales_report->salesnational_actual_netsales_ytd_type($year, $month, "B", "2");
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales"] = $percentage_sales;
                $response["target"] = 100 - $percentage_sales;
                $response["salesvalue"] = round($row["amount"] / 1000);
                $response["targetvalue"] = round($row["tgt_value"] / 1000);
            }
        }

        echo json_encode($response);
    }
    //----

    // 2023-05-30
    function salesnational_view_achievement_data()
    {
        $this->model_zlog->insert("Gen Sales National Achievement Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];

        $lastyear = $year - 1;

        // this year
        $result = $this->model_sales_report->salesnational_actual_netsales_ytd($year, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales_thisyear"] = $percentage_sales;
                $response["target_thisyear"] = 100 - $percentage_sales;
                $response["salesvalue_thisyear"] = round($row["amount"] / 1000);
                $response["targetvalue_thisyear"] = round($row["tgt_value"] / 1000);
            }
        }
        //--

        // last year
        $result = $this->model_sales_report->salesnational_actual_netsales_ytd($lastyear, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales_lastyear"] = $percentage_sales;
                $response["target_lastyear"] = 100 - $percentage_sales;
                $response["salesvalue_lastyear"] = round($row["amount"] / 1000);
                $response["targetvalue_lastyear"] = round($row["tgt_value"] / 1000);
            }
        }
        //---

        // this month
        $result = $this->model_sales_report->salesnational_actual_netsales_mtd($year, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales_thismonth"] = $percentage_sales;
                $response["target_thismonth"] = 100 - $percentage_sales;
                $response["salesvalue_thismonth"] = round($row["amount"] / 1000);
                $response["targetvalue_thismonth"] = round($row["tgt_value"] / 1000);
            }
        }
        //--

        // last year month
        $result = $this->model_sales_report->salesnational_actual_netsales_mtd($lastyear, $month);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales_lastyearmonth"] = $percentage_sales;
                $response["target_lastyearmonth"] = 100 - $percentage_sales;
                $response["salesvalue_lastyearmonth"] = round($row["amount"] / 1000);
                $response["targetvalue_lastyearmonth"] = round($row["tgt_value"] / 1000);
            }
        }
        //--

        // growth ytd
        $response["ytd_growth"] = round(($response["salesvalue_thisyear"] - $response["salesvalue_lastyear"]) / $response["salesvalue_lastyear"] * 100, 2);

        $response["mtd_growth"] = round(($response["salesvalue_thismonth"] - $response["salesvalue_lastyearmonth"]) / $response["salesvalue_lastyearmonth"] * 100, 2);
        //--

        echo json_encode($response);
    }
    //--

    // 2023-05-30
    function salesman_view_achievement_data()
    {
        $this->model_zlog->insert("Gen Salesman Achievement Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $month_before = get_counting_months_before($month);

        $lastyear = $year - 1;

        // from to this year
        $from_this_year = $year . "-01-01";
        $to_this_year_temp = $year . "-" . $month . "-01";
        $to_this_year = date("Y-m-t", strtotime($to_this_year_temp));
        //--

        // from to last year
        $from_last_year = $lastyear . "-01-01";
        $to_last_year_temp = $lastyear . "-" . $month . "-01";
        $to_last_year = date("Y-m-t", strtotime($to_last_year_temp));
        //---

        // this year
        //$result = $this->model_sales_report->salesman_sales_ytd($year, $slscode);
        $result = $this->model_sales_report->salesman_sales_mtd_by_period($from_this_year, $to_this_year, $slscode);
        $temp1 = $result;
        $salesvalue_thisyear = $result;
        $response["salesvalue_thisyear"] = number_format($result);

        $result = $this->model_sales_report->salesman_target_ytd_ver2($year, $slscode, $month_before);
        $temp2 = $result;
        $response["targetvalue_thisyear"] = number_format($result);

        $percentage_sales = percentage($temp1, $temp2);

        $response["sales_thisyear"] = $percentage_sales;
        $response["target_thisyear"] = 100 - $percentage_sales;
        //---

        // last year
        //$result = $this->model_sales_report->salesman_sales_ytd($lastyear, $slscode);
        $result = $this->model_sales_report->salesman_sales_mtd_by_period($from_last_year, $to_last_year, $slscode);
        $temp1 = $result;
        $salesvalue_lastyear = $result;
        $response["salesvalue_lastyear"] = number_format($result);

        $result = $this->model_sales_report->salesman_target_ytd_ver2($lastyear, $slscode, $month_before);
        $temp2 = $result;
        $response["targetvalue_lastyear"] = number_format($result);

        $percentage_sales = percentage($temp1, $temp2);

        $response["sales_lastyear"] = $percentage_sales;
        $response["target_lastyear"] = 100 - $percentage_sales;
        //---

        // this month
        $result = $this->model_sales_report->salesman_sales_mtd($year, $month, $slscode);
        $temp1 = $result;
        $salesvalue_thismonth = $result;
        $response["salesvalue_thismonth"] = number_format($result);

        $result = $this->model_sales_report->salesman_target_mtd($year, $month, $slscode);
        $temp2 = $result;
        $response["targetvalue_thismonth"] = number_format($result);

        $percentage_sales = percentage($temp1, $temp2);

        $response["sales_thismonth"] = $percentage_sales;
        $response["target_thismonth"] = 100 - $percentage_sales;
        //---

        // last year month
        $result = $this->model_sales_report->salesman_sales_mtd($lastyear, $month, $slscode);
        $temp1 = $result;
        $salesvalue_lastyearmonth = $result;
        $response["salesvalue_lastyearmonth"] = number_format($result);

        $result = $this->model_sales_report->salesman_target_mtd($lastyear, $month, $slscode);
        $temp2 = $result;
        $response["targetvalue_lastyearmonth"] = number_format($result);

        $percentage_sales = percentage($temp1, $temp2);

        $response["sales_lastyearmonth"] = $percentage_sales;
        $response["target_lastyearmonth"] = 100 - $percentage_sales;
        //---


        // growth ytd
        $response["ytd_growth"] = round(($salesvalue_thisyear - $salesvalue_lastyear) / $salesvalue_lastyear * 100, 2);
        $response["mtd_growth"] = round(($salesvalue_thismonth - $salesvalue_lastyearmonth) / $salesvalue_lastyearmonth * 100, 2);
        //--

        echo json_encode($response);
    }
    //--

    // 2023-05-30
    function cs_view_achievement_data()
    {
        $this->model_zlog->insert("Gen CS Achievement Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $month_before = get_counting_months_before($month);

        $lastyear = $year - 1;

        // from to this year
        $from_this_year = $year . "-01-01";
        $to_this_year_temp = $year . "-" . $month . "-01";
        $to_this_year = date("Y-m-t", strtotime($to_this_year_temp));
        //--

        // from to last year
        $from_last_year = $lastyear . "-01-01";
        $to_last_year_temp = $lastyear . "-" . $month . "-01";
        $to_last_year = date("Y-m-t", strtotime($to_last_year_temp));
        //---

        // this year
        //$result = $this->model_sales_report->cs_sales_ytd($year, $slscode);
        $result = $this->model_sales_report->cs_sales_mtd_by_period($from_this_year, $to_this_year, $slscode);
        $temp1 = $result;
        $salesvalue_thisyear = $result;
        $response["salesvalue_thisyear"] = number_format($result);

        $result = $this->model_sales_report->cs_target_ytd_ver2($year, $slscode, $month_before);
        $temp2 = $result;
        $response["targetvalue_thisyear"] = number_format($result);

        $percentage_sales = percentage($temp1, $temp2);

        $response["sales_thisyear"] = $percentage_sales;
        $response["target_thisyear"] = 100 - $percentage_sales;
        //---

        // last year
        //$result = $this->model_sales_report->cs_sales_ytd($lastyear, $slscode);
        $result = $this->model_sales_report->cs_sales_mtd_by_period($from_last_year, $to_last_year, $slscode);
        $temp1 = $result;
        $salesvalue_lastyear = $result;
        $response["salesvalue_lastyear"] = number_format($result);

        $result = $this->model_sales_report->cs_target_ytd_ver2($lastyear, $slscode, $month_before);
        $temp2 = $result;
        $response["targetvalue_lastyear"] = number_format($result);

        $percentage_sales = percentage($temp1, $temp2);

        $response["sales_lastyear"] = $percentage_sales;
        $response["target_lastyear"] = 100 - $percentage_sales;
        //---

        // this month
        $result = $this->model_sales_report->cs_sales_mtd($year, $month, $slscode);
        $temp1 = $result;
        $salesvalue_thismonth = $result;
        $response["salesvalue_thismonth"] = number_format($result);

        $result = $this->model_sales_report->cs_target_mtd($year, $month, $slscode);
        $temp2 = $result;
        $response["targetvalue_thismonth"] = number_format($result);

        $percentage_sales = percentage($temp1, $temp2);

        $response["sales_thismonth"] = $percentage_sales;
        $response["target_thismonth"] = 100 - $percentage_sales;
        //---

        // last year month
        $result = $this->model_sales_report->cs_sales_mtd($lastyear, $month, $slscode);
        $temp1 = $result;
        $salesvalue_lastyearmonth = $result;
        $response["salesvalue_lastyearmonth"] = number_format($result);

        $result = $this->model_sales_report->cs_target_mtd($lastyear, $month, $slscode);
        $temp2 = $result;
        $response["targetvalue_lastyearmonth"] = number_format($result);

        $percentage_sales = percentage($temp1, $temp2);

        $response["sales_lastyearmonth"] = $percentage_sales;
        $response["target_lastyearmonth"] = 100 - $percentage_sales;
        //---


        // growth ytd
        $response["ytd_growth"] = round(($salesvalue_thisyear - $salesvalue_lastyear) / $salesvalue_lastyear * 100, 2);
        $response["mtd_growth"] = round(($salesvalue_thismonth - $salesvalue_lastyearmonth) / $salesvalue_lastyearmonth * 100, 2);
        //--

        echo json_encode($response);
    }
    //--

    // 2023-06-09
    function backorderdetail()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'backorderdetail'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Backorder Detail"); // insert log

            $data["var_report"] = $this->model_sales_report->get_backorder_with_item_sn();
            $this->load->view('sales/report/backorderdetail/v_index', $data);
        }
    }
    //----

    // 2023-06-29
    function salesnational_view_top_by_state()
    {
        $this->model_zlog->insert("Gen Sales National Map by Name"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $name = $_POST["name"];

        $top = 10;

        $result = $this->model_sales_report->get_sales_top_by_name($name, $year, $month, $top);
        $data["var_report"] = assign_data($result);
        $data["top"] = $top;
        $this->load->view('sales/report/salesweekly/v_salesinvoice_map_state', $data);
    }
    //--

    // 2023-07-04
    function customers()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'customers'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Gen Sales Report Customers"); // insert log

            $result = $this->model_sales_report->get_list_cust_from_nav_db();
            $data["var_report"] = assign_data($result);

            $this->load->view('sales/report/customers/v_index', $data);
        }
    }
    //---

    // 2023-07-21
    function report_salesman_last_6months()
    {
        $this->model_zlog->insert("Gen Salesman Sales Last 6 Months"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $year_month = get_counting_months_before2($year, $month, 6);

        unset($data_sales);
        foreach ($year_month as $row) {

            $result = $this->model_sales_report->salesman_sales_mtd($row["year"], $row["month"], $slscode);

            if ($row["month"] <= 9) $temp_month = "0" . $row["month"];
            else $temp_month = $row["month"];
            $result_tgt = $this->model_sales_report->salesman_target_mtd($row["year"], $temp_month, $slscode);

            $monthNum  = $row["month"];
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // March

            //$temp = array($row["year"]."-".$monthName ,(int)$result);
            $month_text[] = $row["year"] . "-" . $monthName;
            $data_sales[] = (int)($result / 1000);
            $data_tgt[] = (int)($result_tgt / 1000);
        }

        $response["x_axis_name"] = $month_text;
        $response["last_6months_line"] = $data_sales;
        $response["sales"] = $data_sales;
        $response["target"] = $data_tgt;
        $response["last_6months_text"] = "Sales";
        echo json_encode($response);
    }
    //---

    // 2023-07-27
    function sales_item_cat()
    {
        $this->model_zlog->insert("Gen Customer Sales Item Cat"); // insert log

        $cust_code = $_POST["cust_code"];
        $cust_name = $_POST["cust_name"];
        $year = $_POST["year"];
        $type = $_POST["type"];

        $last_year = $year - 1;
        $last_2year = $year - 2;
        $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");

        $today_lastyear = $last_year . date("-m-t");
        $month_name_last_year = date("F");
        $jan_date = $last_year . "-01-01";

        if ($type == "1") $result = $this->model_sales_report->get_sales_report_by_item_cat_year($cust_code, $year, $last_year, $last_2year, $months, $today_lastyear, $jan_date);
        else if ($type == "2") $result = $this->model_sales_report->get_sales_report_by_item_cat_year_amount($cust_code, $year, $last_year, $last_2year, $months, $today_lastyear, $jan_date);

        $data["var_report"] = assign_data($result);

        $data["months"] = $months;
        $data["year"] = $year;
        $data["last_year"] = $last_year;
        $data["last_2year"] = $last_2year;
        $data["cust_code"] = $cust_code;
        $data["cust_name"] = $cust_name;
        $data["month_name_last_year"] = $month_name_last_year;

        if ($type == 1) {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 0;
        } else {
            $data["amount_format"] = 0;
            $data["comma_digit"] = 2;
        }

        $this->load->view('sales/customer/dashboard/v_salesitemcatreport', $data);
    }
    //--

    function fill_rate_data()
    {
        $this->model_zlog->insert("Gen Customer Fill Rate"); // insert log
        $cust_code = $_POST["cust_code"];
        $year = $_POST["year"];
        $type = $_POST["type"];

        $result = $this->model_sales_report->customer_fill_rate($cust_code, $year, $type);
        $data["var_report"] = assign_data($result);

        $today_year = date("Y");
        $today_month = date("m");
        $result = $this->model_sales_report->get_so_bo_nav($today_year, $today_month, $cust_code);
        $data["var_report2"] = assign_data_one($result);

        $result = $this->model_sales_report->get_bo_nav_by_customer($cust_code);
        $data["var_bo"] = $result;

        $data["today_year"] = $today_year;
        $data["today_month"] = $today_month;

        $this->load->view('sales/customer/dashboard/v_fillrate', $data);
    }
    //--

    // 2023-07-28
    function salesman_actual_netsales_mtd_filter_data()
    {
        $this->model_zlog->insert("Gen Salesman Actual Net Sales MTD Filter Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->salesman_actual_netsales_mtd_type($year, $month, "F", "1", $slscode);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales"] = $percentage_sales;
                $response["target"] = 100 - $percentage_sales;
                $response["salesvalue"] = round($row["amount"] / 1000);
                $response["targetvalue"] = round($row["tgt_value"] / 1000);
            }
        }

        echo json_encode($response);
    }
    //---

    // 2023-07-28
    function salesman_actual_netsales_ytd_filter_data()
    {
        $this->model_zlog->insert("Gen Salesman Actual Net Sales YTD Filter Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->salesman_actual_netsales_ytd_type($year, $month, "F", "1", $slscode);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales"] = $percentage_sales;
                $response["target"] = 100 - $percentage_sales;
                $response["salesvalue"] = round($row["amount"] / 1000);
                $response["targetvalue"] = round($row["tgt_value"] / 1000);
            }
        }

        echo json_encode($response);
    }
    //---

    // 2023-07-28
    function salesman_actual_netsales_mtd_belt_data()
    {
        $this->model_zlog->insert("Gen Salesman Actual Net Sales MTD Belt Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->salesman_actual_netsales_mtd_type($year, $month, "B", "2", $slscode);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales"] = $percentage_sales;
                $response["target"] = 100 - $percentage_sales;
                $response["salesvalue"] = round($row["amount"] / 1000);
                $response["targetvalue"] = round($row["tgt_value"] / 1000);
            }
        }

        echo json_encode($response);
    }
    //---

    // 2023-07-28
    function salesman_actual_netsales_ytd_belt_data()
    {
        $this->model_zlog->insert("Gen Salesman Actual Net Sales MTD Belt Data"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->salesman_actual_netsales_ytd_type($year, $month, "B", "2", $slscode);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $response["sales"] = $percentage_sales;
                $response["target"] = 100 - $percentage_sales;
                $response["salesvalue"] = round($row["amount"] / 1000);
                $response["targetvalue"] = round($row["tgt_value"] / 1000);
            }
        }

        echo json_encode($response);
    }
    //---

    // 2023-07-31
    function salesman_sales_item_cat_filter_mtd()
    {
        $this->model_zlog->insert("Gen Salesman Sales Item Category Filter MTD"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->get_sales_report_by_item_cat_year_amount_mtd($year, $month, $slscode, "1");
        $data["var_report"] = assign_data($result);

        $result = $this->model_sales_report->salesman_actual_netsales_mtd_type($year, $month, "F", "1", $slscode);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $data["sales"] = $percentage_sales;
                $data["target"] = 100 - $percentage_sales;
                $data["salesvalue"] = round($row["amount"] / 1000);
                $data["targetvalue"] = round($row["tgt_value"]);
            }
        }

        $this->load->view('sales/report/salesweekly/v_salesman_cat_filter_mtd', $data);
    }
    //--

    // 2023-07-31
    function salesman_sales_item_cat_filter_ytd()
    {
        $this->model_zlog->insert("Gen Salesman Sales Item Category Filter YTD"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->get_sales_report_by_item_cat_year_amount_ytd($year, $month, $slscode, "1");
        $data["var_report"] = assign_data($result);

        $result = $this->model_sales_report->salesman_actual_netsales_ytd_type($year, $month, "F", "1", $slscode);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $data["sales"] = $percentage_sales;
                $data["target"] = 100 - $percentage_sales;
                $data["salesvalue"] = round($row["amount"] / 1000);
                $data["targetvalue"] = round($row["tgt_value"]);
            }
        }

        $this->load->view('sales/report/salesweekly/v_salesman_cat_filter_ytd', $data);
    }
    //--

    // 2023-07-31
    function salesman_sales_item_cat_belt_mtd()
    {
        $this->model_zlog->insert("Gen Salesman Sales Item Category Belt MTD"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->get_sales_report_by_item_cat_year_amount_mtd($year, $month, $slscode, "2");
        $data["var_report"] = assign_data($result);

        $result = $this->model_sales_report->salesman_actual_netsales_mtd_type($year, $month, "B", "2", $slscode);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $data["sales"] = $percentage_sales;
                $data["target"] = 100 - $percentage_sales;
                $data["salesvalue"] = round($row["amount"] / 1000);
                $data["targetvalue"] = round($row["tgt_value"]);
            }
        }

        $this->load->view('sales/report/salesweekly/v_salesman_cat_belt_mtd', $data);
    }
    //--

    // 2023-07-31
    function salesman_sales_item_cat_belt_ytd()
    {
        $this->model_zlog->insert("Gen Salesman Sales Item Category Belt YTD"); // insert log

        $year = $_POST["year"];
        $month = $_POST["month"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->get_sales_report_by_item_cat_year_amount_ytd($year, $month, $slscode, "2");
        $data["var_report"] = assign_data($result);

        $result = $this->model_sales_report->salesman_actual_netsales_ytd_type($year, $month, "B", "2", $slscode);
        $result = assign_data($result);

        if (count($result) < 0) {
            $response = 0;
        } else {
            foreach ($result as $row) {
                $percentage_sales = percentage($row["amount"], $row["tgt_value"]);
                $data["sales"] = $percentage_sales;
                $data["target"] = 100 - $percentage_sales;
                $data["salesvalue"] = round($row["amount"] / 1000);
                $data["targetvalue"] = round($row["tgt_value"]);
            }
        }

        $this->load->view('sales/report/salesweekly/v_salesman_cat_belt_ytd', $data);
    }
    //--

    function salesman_backorder_data()
    {
        $this->model_zlog->insert("Gen Salesman Back Order"); // insert log

        $year = $_POST["year"];
        $slscode = $_POST["slscode"];

        $result = $this->model_sales_report->salesman_fill_rate($slscode, $year);
        $data["var_report"] = assign_data($result);

        $today_year = date("Y");
        $today_month = date("m");
        $result = $this->model_sales_report->salesman_so_bo_nav_by_month($today_year, $today_month, $slscode);
        $data["var_report2"] = assign_data_one($result);

        $result = assign_data_one($this->model_sales_report->salesman_so_bo_nav($slscode));
        $data["var_bo_qty"] = $result["outstanding"];
        $data["var_bo_amount"] = $result["amount_outstanding"];

        $data["today_year"] = $today_year;
        $data["today_month"] = $today_month;

        $this->load->view('sales/report/salesweekly/v_salesman_backorder', $data);
    }
    //---

    //2023-08-03
    function get_custproductcat_filter2_data()
    {
        $this->model_zlog->insert("Gen Cust Product Cat Filter Data"); // insert log

        $year = $_POST["year"];
        $cust_code = $_POST["cust_code"];
        $name = "FILTRO";

        $cat = $this->model_sales_report->get_category_by_name($name);

        $data["var_cat_all"] = $this->model_sales_report->get_custproductcat_all($year, $cat, $name, $cust_code);
        $data["var_cat_cust"] = $this->model_sales_report->get_custproductcat_customer($year, $cat, $name, $cust_code);
        $data["var_cat"] = $cat;

        $this->load->view('sales/report/custsalessum/v_custproductcat_filter2', $data);
    }
    //--

    function get_custproductcat_banda2_data()
    {
        $this->model_zlog->insert("Gen Cust Product Cat Banda Data"); // insert log

        $year = $_POST["year"];
        $cust_code = $_POST["cust_code"];
        $name = "BANDA";

        $cat = $this->model_sales_report->get_category_by_name($name);

        $data["var_cat_all"] = $this->model_sales_report->get_custproductcat_all($year, $cat, $name, $cust_code);
        $data["var_cat_cust"] = $this->model_sales_report->get_custproductcat_customer($year, $cat, $name, $cust_code);
        $data["var_cat"] = $cat;

        $this->load->view('sales/report/custsalessum/v_custproductcat_banda2', $data);
    }
    //---

    //2023-10-02
    function salesnational_sales_this_year_vs_last_year_per_month()
    {
        $this->model_zlog->insert("Gen Sales National This Year VS Last Year per month"); // insert log

        $year = $_POST["year"];

        $last_year  = $year - 1;

        $response["months"] = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec");
        $months = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");

        $result_this_year = $this->model_sales_report->get_salesnational_sales_by_month_year($year);
        $result_last_year = $this->model_sales_report->get_salesnational_sales_by_month_year($last_year);

        // data this year
        unset($data_this_year);
        foreach ($months as $row_month) {
            $total = 0;
            foreach ($result_this_year as $row) {
                if ($row["monthh"] == $row_month) {
                    $total = $row["amount"];
                    break;
                }
            }
            $data_this_year[] = (int)$total;
        }
        //---

        // data last year
        unset($data_last_year);
        foreach ($months as $row_month) {
            $total = 0;
            foreach ($result_last_year as $row) {
                if ($row["monthh"] == $row_month) {
                    $total = $row["amount"];
                    break;
                }
            }
            $data_last_year[] = (int)$total;
        }
        //---

        $response["this_year_text"]  = strval($year);
        $response["last_year_text"]  = strval($last_year);
        $response["this_year"] = $data_this_year;
        $response["last_year"] = $data_last_year;

        echo json_encode($response);
    }
    //---
    function salesyear()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'salesyear'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Sales per year"); // insert log
            $session_data = $this->session->userdata('z_tpimx_logged_in');
            $user = $session_data['z_tpimx_user_id'];
            $sls_code = $this->model_sales_report->get_salesman_user($user);   // get user-salesman
            $result = $this->model_sales_report->get_customer_by_sls_person_code_nav_local($sls_code);
            $data["var_customer_data"] = assign_data($result);
            $data["var_sls_code"] = $sls_code;
            $this->load->view('sales/report/salesyear/v_index', $data);
        }
    }
    //---
    function sales_yeardata()
    {
        $cust_code = $_POST['cust_code'];
        // $cust_name = $_POST['cust_name'];
        $year = $_POST['year'];
        $last_year = $year - 1;
        $months = array("01", "02", "03");
        // $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        $last_2year = $year - 2;
        $result = $this->model_sales_report->get_sales_year($cust_code, $last_year, $last_2year); //primera tabla de comparacion por año
        // $result = $this->model_sales_report->get_sales_month_comp($cust_code, $year,$last_year); //tabla de 42 columnas
        // $result = $this->model_sales_report->get_sales_month_comp_now($cust_code, $year,$last_year, $months);
        $data["var_report"] = assign_data($result);
        $data['var_year'] = $year;
        $data['var_lastyear'] = $last_year;
        $this->load->view('sales/report/salesyear/v_report', $data);
    }
    //---2024-02-27
    function salespermonth()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'salespermonth'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Sales per month"); // insert log
            $session_data = $this->session->userdata('z_tpimx_logged_in');
            $user = $session_data['z_tpimx_user_id'];
            $sls_code = $this->model_sales_report->get_salesman_user($user);   // get user-salesman
            $result = $this->model_sales_report->get_customer_by_sls_person_code_nav_local($sls_code);
            $data["var_customer_data"] = assign_data($result);
            $data["var_sls_code"] = $sls_code;
            $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
            $data["months"] = $months;
            $this->load->view('sales/report/salespermonth/v_index', $data);
        }
    }
    //---
    function get_salespermonth()
    {
        $cust_code = $_POST['cust_code'];
        $year = $_POST['year'];
        $last_year = $year - 1;
        $month = $_POST['month'];
        $result = $this->model_sales_report->get_sales_month($cust_code, $year, $last_year, $month);
        $data["var_report"] = assign_data($result);
        $data["var_year"] = $year ;
        $data["var_month"] = $month;
        $data["var_lastyear"] = $last_year;
        $this->load->view('sales/report/salespermonth/v_report', $data);
    }

    function salesperbranch(){
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('sales_report_folder') . 'salesperbranch'])) {
            $this->load->view('view_home');
        } else {
            $this->model_zlog->insert("Sales per branch"); // insert log
            $session_data = $this->session->userdata('z_tpimx_logged_in');
            $user = $session_data['z_tpimx_user_id'];
            $sls_code = $this->model_sales_report->get_salesman_user($user);   // get user-salesman
            $result = $this->model_sales_report->get_customer_by_sls_person_code_nav_local($sls_code);
            $data["var_customer_data"] = assign_data($result);
            $data["var_sls_code"] = $sls_code;
            $months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
            $data["months"] = $months;
            $this->load->view('sales/report/salesperbranch/v_index', $data);
        }
    }
    function sales_branchdata(){
        $cust_code = $_POST['cust_code'];
        $year = $_POST['year'];
        $last_year = $year - 1;
        $last_2year = $year - 2;
        $month = $_POST['month'];
        $branchs = $this->model_sales_report->get_number_branch($cust_code); //obtener sucursales ( ship_to_city)
        $branchs_ = assign_data($branchs); // array de sucursales
        $result = $this->model_sales_report->get_data_branch($cust_code,$branchs_, $month, $year, $last_year, $last_2year); //obtiene los datos
        $data["var_branch"] = assign_data($branchs);
        $data["var_data"] = assign_data($result);
        $data['last_2year'] = $last_2year;
        $data['last_year'] = $last_year;
        $data['year'] = $year;
        $this->load->view('sales/report/salesperbranch/v_report', $data);
     }
}
