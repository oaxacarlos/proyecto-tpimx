<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newinbound extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->database();

        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_tsc_in_out_bound_d','',TRUE);
        $this->load->model('model_mst_location','',TRUE);
        $this->load->model('model_mst_item','',TRUE);
        $this->load->model('model_jobs','',TRUE);
        $this->load->model('model_config','',TRUE);
        $this->load->model('model_tsc_doc_history','',TRUE);
        $this->load->model('model_zlog','',TRUE);
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_inbound_folder').'newinbound'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - NewInBound"); // insert log

            $data["var_location"] = assign_data($this->model_mst_location->get_data2()) ; // get warehouse list
            $data["var_item"] = assign_data($this->model_mst_item->get_data());           // get item

            $this->load->view('wms/inbound/new/v_index',$data);
        }
    }
    //--

    // 2022-12-02
    function create_new(){
        $this->model_zlog->insert("Create New InBound"); // insert log

        $item = json_decode(stripslashes($_POST['item']));
        $name = json_decode(stripslashes($_POST['name']));
        $uom = json_decode(stripslashes($_POST['uom']));
        $loc = json_decode(stripslashes($_POST['loc']));
        $src_no = json_decode(stripslashes($_POST['src_no']));
        $qty = json_decode(stripslashes($_POST['qty']));
        $valuee = json_decode(stripslashes($_POST['valuee'])); // valuee 2023-01-30
        $master_barcode = json_decode(stripslashes($_POST['master_barcode']));  // 2023-01-17 master barcode
        $ext_doc = $_POST["ext_doc"];
        $h_loc = $_POST["h_loc"];

        $doc_type = "1";

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];
        $doc_no = $this->insert_tsc_in_out_bound_h($h_loc,$created_user,$ext_doc, $doc_type); // insert header

        // insert detail
        unset($data);
        for($i=0;$i<count($item);$i++){
            if($src_no[$i] == "") $src_no_temp = $doc_no;

            if($valuee[$i] == "" || $valuee[$i] == "."){
              $valuee_temp = 0;
              $valuee_per_pcs_temp = 0;
            }
            else{
              $valuee_temp = $valuee[$i];
              $valuee_per_pcs_temp = round($valuee[$i] / $qty[$i],2);
            }

            $data[] = array(
              "src_no" => $src_no_temp,
              "location_code" => $loc[$i],
              "item_code" => $item[$i],
              "uom" => $uom[$i],
              "desc" => $name[$i],
              "qty" => $qty[$i],
              "valuee" => $valuee_temp, // valuee 2023-01-30
              "valuee_per_pcs" => $valuee_per_pcs_temp, // valuee 2023-01-30
              "master_barcode" => $master_barcode[$i]
            );
        }

        $this->insert_tsc_in_out_bound_d($doc_no,$data);
        //--

        if($doc_no){
            $response['status'] = "1";
            $response['msg'] = "New InBound Document has been created with No = ".$doc_no;
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //--

    // 2022-12-02
    function insert_tsc_in_out_bound_h($loc,$user,$external_doc, $doc_type){

        // get config for document no
        $this->model_config->name = "adjust_posv_doc_pref";
        $prefix = $this->model_config->get_value_by_setting_name();

        $this->model_config->name = "adjust_posv_doc_no";
        $last_doc_no = $this->model_config->get_value_by_setting_name();

        $new_doc_no = $last_doc_no+1;

        $this->model_config->name = "adjust_posv_doc_no";
        $this->model_config->valuee = $new_doc_no;
        $this->model_config->update_value();

        $this->model_config->name = "adjust_posv_doc_digit";
        $digit = $this->model_config->get_value_by_setting_name();

        $doc_no = $prefix.sprintf("%0".$digit."d", $new_doc_no);
        //---

        // insert header
        $datetime = get_datetime_now();
        $date = get_date_now();


        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $this->model_tsc_in_out_bound_h->doc_datetime = $datetime;
        $this->model_tsc_in_out_bound_h->created_datetime = $datetime;
        $this->model_tsc_in_out_bound_h->doc_type = $doc_type;
        $this->model_tsc_in_out_bound_h->doc_location_code = $loc;
        $this->model_tsc_in_out_bound_h->month_end = 0;
        $this->model_tsc_in_out_bound_h->created_user = $user;
        $this->model_tsc_in_out_bound_h->status = "1";
        $this->model_tsc_in_out_bound_h->doc_date = $date;
        $this->model_tsc_in_out_bound_h->doc_posting_date = $datetime;
        $this->model_tsc_in_out_bound_h->external_document = $external_doc;
        $result = $this->model_tsc_in_out_bound_h->insert_h();
        //---

        // insert doc history
        $this->model_tsc_doc_history->insert($doc_no,"","","1","",$datetime, $external_doc,"");
        //--

        if($result) return $doc_no;
        else return false;
    }
    //---

    // 2022-12-02
    function insert_tsc_in_out_bound_d($doc_no,$data){
        $datetime = get_datetime_now();
        $date = get_date_now();

        $line_no_add = 10000;
        $line_no = 10000;

        foreach($data as $row){
            $this->model_tsc_in_out_bound_d->doc_no = $doc_no;
            $this->model_tsc_in_out_bound_d->line_no = $line_no;
            $this->model_tsc_in_out_bound_d->src_location_code = $row["location_code"];
            $this->model_tsc_in_out_bound_d->src_no = $row["src_no"];
            $this->model_tsc_in_out_bound_d->src_line_no = $line_no;
            $this->model_tsc_in_out_bound_d->item_code = $row["item_code"];
            $this->model_tsc_in_out_bound_d->qty = $row["qty"];
            $this->model_tsc_in_out_bound_d->uom = $row["uom"];
            $this->model_tsc_in_out_bound_d->description = $row["desc"];
            $this->model_tsc_in_out_bound_d->dest_no = "";
            $this->model_tsc_in_out_bound_d->master_barcode = $row["master_barcode"]; // master barcode 2023-01-17
            $this->model_tsc_in_out_bound_d->valuee = $row["valuee"]; // valuee 2023-01-30
            $this->model_tsc_in_out_bound_d->valuee_per_pcs = $row["valuee_per_pcs"]; // valuee 2023-01-30
            $result = $this->model_tsc_in_out_bound_d->insert_d();
            $line_no += $line_no_add;
        }
    }
    //---
}

?>
