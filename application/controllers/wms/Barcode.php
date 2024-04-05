<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Barcode extends CI_Controller{

    function __construct(){
      parent::__construct();
      $this->load->database();
    }
    //---

    function print_barcode_by_doc(){

        $doc_type = $_GET['doctype'];
        $doc_no = $_GET['docno'];

        $file_folder_sn = $this->config->item('wms_barcode_file_sn');
        $file_folder_items = $this->config->item('wms_barcode_file_items');
        $file_ext = ".png";

        $result_data = $this->get_data_by_doc_type($doc_type, $doc_no);

        foreach($result_data as $row){
            // generate serial number
            $filename_sn = $file_folder_sn.$row["serial_number"].$file_ext;
            //if (!file_exists($filename_sn)){
                $this->set_barcode($row["serial_number"],$file_folder_sn,$file_ext,20,1,1,10,0);
            //}

            // generate item code
            $filename_items = $file_folder_items.$row["item_code"].$file_ext;
            //if (!file_exists($filename_items)){
                $this->set_barcode($row["item_code"],$file_folder_items,$file_ext,30,1,1,15,0);
            //}
        }

        $data["barcode_data"] = $result_data;
        $data["file_folder_sn"] = $file_folder_sn;
        $data["file_folder_items"] = $file_folder_items;
        $data["file_ext"] = $file_ext;

        $this->load->view('wms/barcode/v_print_barcode',$data);
    }
    //----

    private function set_barcode($code,$file_folder,$file_ext, $height, $factor, $barthinwidth, $fontSize, $master)
  	{
    		//load library
    		$this->load->library('ci_zend');

    		//load in folder Zend
    		$this->ci_zend->load('Zend/Barcode');

        if($master == "" or is_null($master) or !isset($master) or $master==0){
          Zend_Barcode::setBarcodeFont(APPPATH . '\libraries\Zend\Fonts\arial.ttf');
        }

        //generate barcode
        $barcodeOptions = array(
            'text' => $code,
            'barHeight'=> $height,
            'factor'=>$factor,
            'withQuietZones' => true,
            'fontSize' => $fontSize
        );

        $file = Zend_Barcode::factory('code128', 'image',$barcodeOptions,array())->draw();
        imagepng($file,$file_folder.$code.$file_ext);
  	}
    //--

    function get_data_by_doc_type($doc_type, $doc_no){
        $this->load->model('model_tsc_received_d2','',TRUE);
        $this->load->model('model_tsc_picking_d','',TRUE);
        $this->load->model('model_tsc_transferbin_d2','',TRUE);
        $this->load->model('model_tsc_print','',TRUE);
        $this->load->model('model_tsc_received_h','',TRUE);
        $this->load->model('model_tsc_transferbin_h','',TRUE);

        if($doc_type == "received"){
            $this->model_tsc_received_d2->doc_no = $doc_no;
            $result = $this->model_tsc_received_d2->get_data();
            $data = assign_data($result);

            // add counting print 2023-02-07
            $datetime = get_datetime_now();
            $this->model_tsc_print->insert($doc_no, $datetime,"barcode child");

            $this->model_tsc_received_h->update_print_barcode("1", $doc_no);
            //---
        }
        else if($doc_type == "whshipment"){
            $this->model_tsc_picking_d->src_no = $doc_no;
            $result = $this->model_tsc_picking_d->get_pick_serial_number_scan_by_whship();
            $data = assign_data($result);
        }
        else if($doc_type == "qctemp"){
            $this->model_tsc_picking_d->src_no = $doc_no;
            $result = $this->model_tsc_picking_d->get_pick_serial_number_scan_by_whship_temp();
            $data = assign_data($result);
        }
        else if($doc_type == "transferbin"){
            $this->model_tsc_transferbin_d2->doc_no = $doc_no;
            $result = $this->model_tsc_transferbin_d2->get_list_by_doc_no_print_barcode();
            $data = assign_data($result);

            // add counting print 2023-02-07
            $datetime = get_datetime_now();
            $this->model_tsc_print->insert($doc_no, $datetime,"transfer bin barcode");

            $this->model_tsc_transferbin_h->update_print_barcode("1", $doc_no);
            //---
        }
        else if($doc_type == "received2"){
            $this->model_tsc_received_d2->doc_no = $doc_no;
            $result = $this->model_tsc_received_d2->get_data_by_master_code();
            $data = assign_data($result);

            // add counting print 2023-02-07
            $datetime = get_datetime_now();
            $this->model_tsc_print->insert($doc_no, $datetime,"master barcode");

            $this->model_tsc_received_h->update_print_master_barcode("1", $doc_no);
            //---
        }
        else if($doc_type == "transferbin2"){
            $this->model_tsc_transferbin_d2->doc_no = $doc_no;
            $result = $this->model_tsc_transferbin_d2->get_list_by_doc_no_print_master_barcode();
            $data = assign_data($result);

            // add counting print 2023-02-07
            $datetime = get_datetime_now();
            $this->model_tsc_print->insert($doc_no, $datetime,"transfer bin master barcode");

            $this->model_tsc_transferbin_h->update_print_master_barcode("1", $doc_no);
            //---
        }
        else if($doc_type == "qctemp2"){ // WH3
            $this->model_tsc_picking_d->src_no = $doc_no;
            $result = $this->model_tsc_picking_d->get_pick_sn2_pick_by_whship_temp();
            $data = assign_data($result);
        }

        return $data;
    }
    //---

    function print_barcode_by_doc_temp(){

        $doc_type = $_GET['doctype'];
        $doc_no = $_GET['docno'];

        $file_folder_sn = $this->config->item('wms_barcode_file_sn');
        $file_folder_items = $this->config->item('wms_barcode_file_items');
        $file_ext = ".png";

        $result_data = $this->get_data_by_doc_type($doc_type, $doc_no);

        foreach($result_data as $row){
            // generate serial number
            $filename_sn = $file_folder_sn.$row["serial_number"].$file_ext;
            //if (!file_exists($filename_sn)){
                $this->set_barcode($row["serial_number"],$file_folder_sn,$file_ext,20,1,1,10,0);
            //}

            // generate item code
            $filename_items = $file_folder_items.$row["item_code"].$file_ext;
            //if (!file_exists($filename_items)){
                $this->set_barcode($row["item_code"],$file_folder_items,$file_ext,20,1,1,15,0);
            //}
        }

        $data["barcode_data"] = $result_data;
        $data["file_folder_sn"] = $file_folder_sn;
        $data["file_folder_items"] = $file_folder_items;
        $data["file_ext"] = $file_ext;

        $this->load->view('wms/barcode/v_print_barcode',$data);
    }
    //----

    // 2022-11-05
    function print_barcode_by_item_code_status(){
        $item_code = $_GET['id'];
        $status = $_GET['status'];
        $loc = $_GET['loc'];
        $zone = $_GET['zone'];
        $area = $_GET['area'];
        $rack= $_GET['rack'];
        $bin = $_GET['bin'];

        $this->load->model('model_tsc_item_sn','',TRUE);

        $file_folder_sn = $this->config->item('wms_barcode_file_sn');
        $file_folder_items = $this->config->item('wms_barcode_file_items');
        $file_ext = ".png";

        $this->model_tsc_item_sn->item_code = $item_code;
        $this->model_tsc_item_sn->status = $status;
        $this->model_tsc_item_sn->location_code = $loc;
        $this->model_tsc_item_sn->zone_code = $zone;
        $this->model_tsc_item_sn->area_code = $area;
        $this->model_tsc_item_sn->rack_code = $rack;
        $this->model_tsc_item_sn->bin_code = $bin;
        $result = $this->model_tsc_item_sn->get_data_by_item_code_and_status_and_loc();

        foreach($result as $row){
            // generate serial number
            $filename_sn = $file_folder_sn.$row["serial_number"].$file_ext;
            //if (!file_exists($filename_sn)){
                $this->set_barcode($row["serial_number"],$file_folder_sn,$file_ext,20,1,1,10,0);
            //}

            // generate item code
            $filename_items = $file_folder_items.$row["item_code"].$file_ext;
            //if (!file_exists($filename_items)){
                $this->set_barcode($row["item_code"],$file_folder_items,$file_ext,30,1,1,15,0);
            //}
        }

        $data["barcode_data"] = $result;
        $data["file_folder_sn"] = $file_folder_sn;
        $data["file_folder_items"] = $file_folder_items;
        $data["file_ext"] = $file_ext;

        $this->load->view('wms/barcode/v_print_barcode',$data);
    }
    //---

    //2022-11-16
    function print_barcode_partially(){
        $total = $_GET["total"];

        $file_folder_sn = $this->config->item('wms_barcode_file_sn');
        $file_folder_items = $this->config->item('wms_barcode_file_items');
        $file_ext = ".png";

        unset($data);

        for($i=0;$i<$total;$i++){
            $data[] = array(
                "serial_number" => $_GET["sn".$i],
                "item_code" => $_GET["item".$i]
            );

            // generate serial number
            $filename_sn = $file_folder_sn.$_GET["sn".$i].$file_ext;
            //if (!file_exists($filename_sn)){
                $this->set_barcode($_GET["sn".$i],$file_folder_sn,$file_ext,20,1,1,10,0);
            //}

            // generate item code
            $filename_items = $file_folder_items.$_GET["item".$i].$file_ext;
            //if (!file_exists($filename_items)){
                $this->set_barcode($_GET["item".$i],$file_folder_items,$file_ext,20,1,1,15,0);
            //}
        }

        $data["barcode_data"] = $data;
        $data["file_folder_sn"] = $file_folder_sn;
        $data["file_folder_items"] = $file_folder_items;
        $data["file_ext"] = $file_ext;

        $this->load->view('wms/barcode/v_print_barcode',$data);
    }
    //--


    // 2022-11-15 master barcode
    function print_master_barcode_by_doc(){

        $doc_type = $_GET['doctype'];
        $doc_no = $_GET['docno'];

        $file_folder_sn = $this->config->item('wms_barcode_file_sn');
        $file_folder_items = $this->config->item('wms_barcode_file_items');
        $file_ext = ".png";

        $result_data = $this->get_data_by_doc_type($doc_type, $doc_no);

        unset($qty_sn2);
        foreach($result_data as $row){
            // generate serial number
            $filename_sn = $file_folder_sn.$row["sn2"].$file_ext;
            //if (!file_exists($filename_sn)){
                $this->set_barcode($row["sn2"],$file_folder_sn,$file_ext,40,1,1,10,1);
            //}

            // generate item code
            $filename_items = $file_folder_items.$row["item_code"].$file_ext;
            //if (!file_exists($filename_items)){
                $this->set_barcode($row["item_code"],$file_folder_items,$file_ext,30,1,1,15,1);
            //}

            $qty_sn2[$row["sn2"]] = $row["qty_sn2"];
        }

        $data["barcode_data"] = $result_data;
        $data["file_folder_sn"] = $file_folder_sn;
        $data["file_folder_items"] = $file_folder_items;
        $data["file_ext"] = $file_ext;
        $data["qty_sn2"] = $qty_sn2;

        $this->load->view('wms/barcode/v_print_master_barcode',$data);
    }
    //----

    // 2023-03-09
    function print_master_barcode_by_sn2(){
        $sn2 = $_GET["sn2"];

        $this->load->model('model_tsc_item_sn','',TRUE);

        $file_folder_sn = $this->config->item('wms_barcode_file_sn');
        $file_folder_items = $this->config->item('wms_barcode_file_items');
        $file_ext = ".png";

        $result_data = $this->model_tsc_item_sn->get_sn2_item_by_sn2($sn2);

        foreach($result_data as $row){
            // generate serial number
            $filename_sn = $file_folder_sn.$row["sn2"].$file_ext;
            //if (!file_exists($filename_sn)){
                $this->set_barcode($row["sn2"],$file_folder_sn,$file_ext,40,1,1,10,1);
            //}

            // generate item code
            $filename_items = $file_folder_items.$row["item_code"].$file_ext;
            //if (!file_exists($filename_items)){
                $this->set_barcode($row["item_code"],$file_folder_items,$file_ext,30,1,1,15,1);
            //}
        }

        $data["barcode_data"] = $result_data;
        $data["file_folder_sn"] = $file_folder_sn;
        $data["file_folder_items"] = $file_folder_items;
        $data["file_ext"] = $file_ext;

        $this->load->view('wms/barcode/v_print_master_barcode',$data);
    }
    //---

    function print_barcode_by_com(){
        $com_name = $_GET["comname"];

        $file_folder_sn = $this->config->item('wms_barcode_file_sn');
        $file_folder_items = $this->config->item('wms_barcode_file_items');
        $file_ext = ".png";

        $filename_items = $file_folder_items.$com_name.$file_ext;
        $this->set_barcode($com_name,$file_folder_items,$file_ext,30,1,1,15,0);

        $data[] = $com_name;
        $data["barcode_data"] = $data;
        $data["file_folder_items"] = $file_folder_items;
        $data["file_ext"] = $file_ext;

        $this->load->view('wms/barcode/v_print_barcode_com',$data);
    }
    //---

    // 2023-10-20
    function print_masterbarcode_by_item_code_status(){
        $item_code = $_GET['id'];
        $status = $_GET['status'];
        $loc = $_GET['loc'];
        $zone = $_GET['zone'];
        $area = $_GET['area'];
        $rack= $_GET['rack'];
        $bin = $_GET['bin'];

        $this->load->model('model_tsc_item_sn','',TRUE);

        $file_folder_sn = $this->config->item('wms_barcode_file_sn');
        $file_folder_items = $this->config->item('wms_barcode_file_items');
        $file_ext = ".png";

        $this->model_tsc_item_sn->item_code = $item_code;
        $this->model_tsc_item_sn->status = $status;
        $this->model_tsc_item_sn->location_code = $loc;
        $this->model_tsc_item_sn->zone_code = $zone;
        $this->model_tsc_item_sn->area_code = $area;
        $this->model_tsc_item_sn->rack_code = $rack;
        $this->model_tsc_item_sn->bin_code = $bin;
        $result = $this->model_tsc_item_sn->get_data_by_item_code_and_status_and_loc_group_by_sn2();

        foreach($result as $row){
            // generate serial number
            $filename_sn = $file_folder_sn.$row["sn2"].$file_ext;
            //if (!file_exists($filename_sn)){
                $this->set_barcode($row["sn2"],$file_folder_sn,$file_ext,40,1,1,10,1);
            //}

            // generate item code
            $filename_items = $file_folder_items.$row["item_code"].$file_ext;
            //if (!file_exists($filename_items)){
                $this->set_barcode($row["item_code"],$file_folder_items,$file_ext,30,1,1,15,1);
            //}
        }

        $data["barcode_data"] = $result;
        $data["file_folder_sn"] = $file_folder_sn;
        $data["file_folder_items"] = $file_folder_items;
        $data["file_ext"] = $file_ext;

        $this->load->view('wms/barcode/v_print_master_barcode',$data);
    }
    //---
}

?>
