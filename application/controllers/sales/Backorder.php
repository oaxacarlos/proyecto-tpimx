<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Backorder extends CI_Controller{

    function __construct(){
      parent::__construct();
         $this->load->model('model_backorder','',TRUE);
         $this->load->model('model_sales_report','',TRUE);
         $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'backorder'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("BackOrder"); // insert log

            $session_data = $this->session->userdata('z_tpimx_logged_in');
            $user = $session_data['z_tpimx_user_id'];
            $slscode = $this->model_sales_report->get_salesman_user($user);   // get user-salesman

            $result = $this->model_sales_report->get_salesman_active($slscode);
            $data["var_salesman_data"] = assign_data($result);
            $this->load->view('sales/backorder/v_index', $data);
        }
    }
    //---

    function get_list(){
        $this->model_zlog->insert("Gen BackOrder Data"); // insert log

        $slscode = $_POST['slscode'];

        $result_year_month = $this->model_backorder->get_distinct_year_month($slscode);
        if(count($result_year_month) > 0){
            $data["var_year_month"] = assign_data($result_year_month);

            $result_customer_backorder = $this->model_backorder->get_backorder_by_customer_period($data["var_year_month"],$slscode);
            $data["var_customer_backorder"] = assign_data($result_customer_backorder);

            $result_customer_item_backorder = $this->model_backorder->get_backorder_by_customer_item_period($data["var_year_month"],$slscode);
            $data["var_customer_item_backorder"] = assign_data($result_customer_item_backorder);
        }

        $this->load->view('sales/backorder/v_list', $data);
    }
    //---

    function get_list_by_items(){
        $this->model_zlog->insert("Gen BackOrder Data by Items"); // insert log

        $slscode = $_POST['slscode'];

        $result_year_month = $this->model_backorder->get_distinct_year_month($slscode);
        if(count($result_year_month) > 0){
            $data["var_year_month"] = assign_data($result_year_month);

            $result_item_backorder = $this->model_backorder->get_backorder_by_item_period($data["var_year_month"],$slscode);
            $data["var_item_backorder"] = assign_data($result_item_backorder);

            $result_item_customer_backorder = $this->model_backorder->get_backorder_by_item_customer_period($data["var_year_month"],$slscode);
            $data["var_item_customer_backorder"] = assign_data($result_item_customer_backorder);
        }

        $this->load->view('sales/backorder/v_list_item', $data);
    }
    //---
}
