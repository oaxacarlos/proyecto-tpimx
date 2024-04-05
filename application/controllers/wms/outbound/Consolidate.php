<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consolidate extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'consolidate'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('wms/outbound/consolidate/v_index');
        }
    }
    //---

    function console(){
        $this->load->model('model_tsc_packing_h','',TRUE);

        $result = $this->model_tsc_packing_h->get_list_group_by_dest_with_no_console();
        $data["var_packing_no_console"] = assign_data($result);

        $this->load->view('wms/outbound/consolidate/v_packed_no_console', $data);
    }
    //---

    function create_new(){
        $doc_no = json_decode(stripslashes($_POST['doc_no']));
        $dest_no = $_POST['dest_no'];
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $addr = $_POST['addr'];
        $addr2 = $_POST['addr2'];
        $city = $_POST['city'];
        $post_code = $_POST['post_code'];
        $county = $_POST['county'];
        $country = $_POST['country'];
        $message = $_POST['message'];

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];

        $datetime = get_datetime_now();
        $date = get_date_now();

        $this->db->trans_begin();

        $h_doc_no = $this-> create_header_doc($dest_no, $name, $contact, $addr, $addr2, $city, $post_code, $county, $country, $datetime, $date, $created_user, $message); // create header

        // create detail
        $this->insert_detail($h_doc_no, $doc_no, $datetime);

        $this->db->trans_complete();

        if($h_doc_no){
            $response['status'] = "1";
            $response['msg'] = "New Console Document has been created with No = ".$h_doc_no;
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //---

    function create_header_doc($dest_no, $name, $contact, $addr, $addr2, $city, $post_code, $county, $country, $datetime, $date, $created_user, $message){
        $this->load->model('model_tsc_console_h','',TRUE);
        $this->load->model('model_config','',TRUE);

        // get prefix from config
        $this->model_config->name = "pref_console";
        $prefix = $this->model_config->get_value_by_setting_name();
        //--

        $this->model_tsc_console_h->prefix_code = $prefix;
        $this->model_tsc_console_h->created_datetime = $datetime;
        $this->model_tsc_console_h->doc_datetime = $datetime;
        $this->model_tsc_console_h->created_user = $created_user;
        $this->model_tsc_console_h->doc_date =  $date;
        $this->model_tsc_console_h->text1 = $message;
        $this->model_tsc_console_h->dest_no = $dest_no;
        $this->model_tsc_console_h->dest_name = $name;
        $this->model_tsc_console_h->dest_addr = $addr;
        $this->model_tsc_console_h->dest_addr2 = $addr2;
        $this->model_tsc_console_h->dest_contact = $contact;
        $this->model_tsc_console_h->dest_county = $county;
        $this->model_tsc_console_h->dest_country = $country;
        $this->model_tsc_console_h->dest_post_code = $post_code;
        $this->model_tsc_console_h->dest_city = $city;
        $result = $this->model_tsc_console_h->call_store_procedure_newconsole();

        return $result;
    }
    //---

    function insert_detail($h_doc_no, $doc_no, $datetime){
        $this->load->model('model_tsc_console_d','',TRUE);

        for($i=0;$i<count($doc_no);$i++){
            $this->model_tsc_console_d->doc_no = $h_doc_no;
            $this->model_tsc_console_d->line_no = $i+1;
            $this->model_tsc_console_d->src_no = $doc_no[$i];
            $this->model_tsc_console_d->created_datetime = $datetime;
            $this->model_tsc_console_d->insert();
        }

    }
    //---

    function get_list(){
        $this->load->model('model_tsc_console_h','',TRUE);
        $result = $this->model_tsc_console_h->get_list_by_desc();
        $data["var_console"] = assign_data($result);
        $this->load->view('wms/outbound/consolidate/v_list',$data);
    }
    //---

    function detail(){
        $doc_no = $_POST['doc_no'];
        $this->load->model('model_tsc_console_d','',TRUE);

        $result = $this->model_tsc_console_d->doc_no = $doc_no;
        $result = $this->model_tsc_console_d->get_list();
        $data["var_console_detail"] = assign_data($result);
        $this->load->view('wms/outbound/consolidate/v_detail',$data);
    }
    //---
}
