<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();

      $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'checking'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('wms/outbound/submitnav/v_index');
        }
    }
    //---

    function monthend(){
        $this->load->model('model_mst_location','',TRUE);

        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'setup/monthend'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - Setup Month End"); // insert log

            // get location
            $result_location = $this->model_mst_location->get_data();
            if(count($result_location) > 0){
                $data["var_location"] = assign_data($result_location);
            }
            else{
                $data["var_location"] = 0;
            }
            //---

            $this->load->view('wms/outbound/setup/monthend/v_index',$data);
        }
    }
    //---

    function monthend_get_list(){
        $this->load->model('model_tsc_month_end','',TRUE);
        $result = $this->model_tsc_month_end->get_list();
        $data["var_monthend"] = assign_data($result);
        $this->load->view('wms/outbound/setup/monthend/v_list',$data);
    }
    //---

    function monthend_check(){
        $from = $_POST['from'];
        $to = $_POST['to'];
        $whs = $_POST["whs"];

        $this->load->model('model_tsc_month_end','',TRUE);

        $this->model_tsc_month_end->fromm = $from;
        $this->model_tsc_month_end->too = $to;
        $this->model_tsc_month_end->location_code = $whs;
        $is_from = $this->model_tsc_month_end->is_fromm();

        $this->model_tsc_month_end->fromm = $from;
        $this->model_tsc_month_end->too = $to;
        $this->model_tsc_month_end->location_code = $whs;
        $is_too = $this->model_tsc_month_end->is_too();

        if($is_fromm==0 && $is_too==0){ echo json_encode("1"); }
        else echo json_encode("0");

    }
    //---

    function monthend_add(){
        $from = $_POST['from'];
        $to = $_POST['to'];
        $name = $_POST['name'];
        $whs = $_POST["whs"];

        $this->load->model('model_tsc_month_end','',TRUE);

        $this->model_tsc_month_end->fromm = $from;
        $this->model_tsc_month_end->too = $to;
        $this->model_tsc_month_end->name = $name;
        $this->model_tsc_month_end->location_code = $whs;
        $result = $this->model_tsc_month_end->insert();

        if($result){
            $response['status'] = "1";
            $response['msg'] = "New Month End has beed added";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //---

    function monthend_delete(){
        $id = $_POST['id'];

        $this->load->model('model_tsc_month_end','',TRUE);

        $this->model_tsc_month_end->id = $id;
        $result = $this->model_tsc_month_end->delete_by_id();

        if($result){
            $response['status'] = "1";
            $response['msg'] = "Month End has been deleted";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //--

    function locked(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'setup/locked'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('wms/outbound/setup/locked/v_index');
        }
    }
    //---

    function locked_doc(){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id'];

        if($user=="8" or $user=="111") $user = "";

        $result = $this->model_tsc_in_out_bound_h->get_locked_document($user);
        $data["var_locked_doc"] = assign_data($result);
        $this->load->view('wms/outbound/setup/locked/v_list',$data);

    }
    //---


}

?>
