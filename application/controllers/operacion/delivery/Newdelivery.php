  <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newdelivery extends CI_Controller{

    function __construct(){
      parent::__construct();
         $this->load->model('operacion/delivery/mst/delv/delv_part','model_operacion_mst_delv_part');
         $this->load->model('operacion/delivery/mst/delv/delv_status','model_operacion_mst_delv_status');
         $this->load->model('operacion/delivery/mst/domicili','model_operacion_mst_domicili');
         $this->load->model('operacion/delivery/mst/vendor','model_operacion_mst_vendor');
         $this->load->model('operacion/delivery/mst/city','model_operacion_mst_city');
         $this->load->model('operacion/delivery/mst/driver','model_operacion_mst_driver');
         $this->load->model('operacion/delivery/mst/state','model_operacion_mst_state');
         $this->load->model('operacion/delivery/mst/payment/payment_terms','model_operacion_mst_payment_terms');
         $this->load->model('operacion/delivery/mst/payment/payment_status','model_operacion_mst_payment_status');
         $this->load->model('operacion/delivery/tsc/delivery/delivery_h','model_operacion_tsc_delivery_h');
         $this->load->model('operacion/delivery/tsc/delivery/delivery_d','model_operacion_tsc_delivery_d');
         $this->load->model('operacion/config','model_operacion_config');
         $this->load->model('operacion/delivery/tsc/delivery/delivery_history','model_operacion_tsc_delivery_history');
         $this->load->model('operacion/delivery/mst/delv/delv_limit_amount','model_operacion_mst_delv_limit_amount');
    }

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_folder').'delivery/newdelivery'])){
            $this->load->view('view_home');
        }
        else{
            $data["var_delv_part"] = assign_data($this->model_operacion_mst_delv_part->get_data());
            $data["var_delv_status"] = assign_data($this->model_operacion_mst_delv_status->get_data());
            $data["var_domilici"] = assign_data($this->model_operacion_mst_domicili->get_data());
            $data["var_vendor"] = assign_data($this->model_operacion_mst_vendor->get_data());
            $data["var_city"] = assign_data($this->model_operacion_mst_city->get_data());
            $data["var_driver"] = assign_data($this->model_operacion_mst_driver->get_data());
            $data["var_state"] = assign_data($this->model_operacion_mst_state->get_data());
            $data["var_payment_terms"] = assign_data($this->model_operacion_mst_payment_terms->get_data());
            //$data["var_payment_status"] = assign_data($this->model_operacion_mst_payment_status->get_data());

            $this->load->view('operacion/delivery/new/v_index',$data);
        }
    }
    //---

    function get_invoices_whship(){
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];

        $result = $this->model_operacion_tsc_delivery_h->get_invoice_wship($date_from, $date_to);

        if(count($result) == 0){
            $data["var_invoices"] = 0;
        }
        else{
            $data["var_invoices"] = assign_data($result);
        }

        $this->load->view('operacion/delivery/new/v_invoice_detail',$data);
    }
    //---

    function create_new(){
        $doc_type = json_decode(stripslashes($_POST['doc_type']));
        $doc_no = json_decode(stripslashes($_POST['doc_no']));
        $doc_date = json_decode(stripslashes($_POST['doc_date']));
        $so_ref = json_decode(stripslashes($_POST['so_ref']));
        $cust_no = json_decode(stripslashes($_POST['cust_no']));
        $subtotal = json_decode(stripslashes($_POST['subtotal']));
        $total2 = json_decode(stripslashes($_POST['total2']));
        $remarks = json_decode(stripslashes($_POST['remarks']));
        $cust_name = json_decode(stripslashes($_POST['cust_name']));

        if(!isset($cust_name)){
            $cust_name = $this->get_cust_name($doc_no);
        }
        else{
            if(count($cust_name) != count($cust_no)){
                $cust_name = $this->get_cust_name($doc_no);
            }
        }

        $address = json_decode(stripslashes($_POST['address']));
        $address2 = json_decode(stripslashes($_POST['address2']));
        $city = json_decode(stripslashes($_POST['city']));
        $state2 = json_decode(stripslashes($_POST['state2']));
        $post_code = json_decode(stripslashes($_POST['post_code']));
        $country = json_decode(stripslashes($_POST['country']));
        $qty = json_decode(stripslashes($_POST['qty']));
        $required_remarks = json_decode(stripslashes($_POST['required_remarks']));
        $sending_date = $_POST["sending_date"];
        $destination = $_POST["destination"];
        $state = $_POST["state"];
        $driver = $_POST["driver"];
        $vendor = $_POST["vendor"];
        $tracking_no = $_POST["tracking_no"];
        $domicili = $_POST["domicili"];
        $payment_terms = $_POST["payment_terms"];
        $delivery_status = $_POST["delivery_status"];
        $total = $_POST["total"];
        $box = $_POST["box"];
        $pallet = $_POST["pallet"];
        $remark_h = $_POST["remark_h"];
        $sub_total_vendor = $_POST["sub_total_vendor"];
        $folio = $_POST["folio"]; // 2023-10-13

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];

        $new_doc_no = $this->insert_header($sending_date, $destination, $state, $driver, $vendor, $tracking_no, $box, $pallet, $domicili, $payment_terms, $delivery_status, $total, $created_user, $remark_h, $sub_total_vendor, $folio);

        $this->insert_detail($new_doc_no, $doc_no, $doc_date, $so_ref, $cust_no, $subtotal, $total2, $remarks, $cust_name, $address, $address2, $city, $state2, $post_code, $country, $qty,$created_user, $doc_type, $required_remarks);

        if($new_doc_no){
            $response['status'] = "1";
            $response['msg'] = "New Delivery Document has been created with No = ".$new_doc_no;
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //---

    function insert_header($delv_date, $destination, $state, $driver, $vendor_no, $tracking_no, $box, $pallet, $domicili, $payment_term, $delv_status, $total, $created_by, $remarks,  $sub_total_vendor, $folio){

        // get config for document no
        $this->model_operacion_config->name = "delv_doc_pref";
        $prefix = $this->model_operacion_config->get_value_by_setting_name();

        $this->model_operacion_config->name = "delv_doc_no";
        $last_doc_no = $this->model_operacion_config->get_value_by_setting_name();

        $new_doc_no = $last_doc_no+1;

        $this->model_operacion_config->name = "delv_doc_no";
        $this->model_operacion_config->valuee = $new_doc_no;
        $this->model_operacion_config->update_value();

        $this->model_operacion_config->name = "delv_doc_digit";
        $digit = $this->model_operacion_config->get_value_by_setting_name();

        $new_doc_no = $prefix.sprintf("%0".$digit."d", $new_doc_no);
        //---

        $datetime = get_datetime_now();
        $date = get_date_now();

        /*$this->model_operacion_config->name = "tax";
        $tax_percentage = $this->model_operacion_config->get_value_by_setting_name();

        $subtotal = $total / (1+$tax_percentage);
        $tax = $subtotal * $tax_percentage;*/

        $tax = 0;

        if($sub_total_vendor == "" or is_null($sub_total_vendor)) $subtotal = 0;
        else $subtotal = $sub_total_vendor;

        $this->model_operacion_tsc_delivery_h->insert($new_doc_no, $datetime, $delv_date, $destination, $state, $driver, $vendor_no, $tracking_no, $box, $pallet, $domicili, $payment_term, $subtotal, $delv_status, $total, $tax, $created_by, $remarks, $date,$folio);

        $this->model_operacion_tsc_delivery_history->insert($new_doc_no, $datetime, $created_by, "2", "New Document");

        return $new_doc_no;
    }
    //---

    function insert_detail($new_doc_no, $doc_no, $doc_date, $so_ref, $cust_no, $subtotal, $total, $remarks, $cust_name, $address, $address2, $city, $state, $post_code, $country, $qty, $created_by, $doc_type, $required_remarks){

        $datetime = get_datetime_now();
        $date = get_date_now();

        $this->model_operacion_tsc_delivery_d->insert($new_doc_no, $doc_no, $doc_date, $so_ref, $cust_no, $subtotal, $total, $remarks, $cust_name, $address, $address2, $city, $state, $post_code, $country, $qty, $created_by, $datetime, $doc_type, $required_remarks);

    }
    //--

    function get_cust_name($doc_no){
        unset($result);
        foreach($doc_no as $row){
            $result[] = $this->model_operacion_tsc_delivery_h->get_cust_name($row);
        }

        return $result;
    }
    //--

    function check_invc_no_has_applied(){
        $doc_no = $_POST["doc_no"];

        $check = $this->model_operacion_tsc_delivery_d->check_invc_has_applied($doc_no);

        echo json_encode($check);
    }
    //--

    function get_limit_percentage(){
        $state = $_POST["state"];
        $result = $this->model_operacion_mst_delv_limit_amount->get_data_by_state($state);
        echo json_encode($result);
    }
    //---

    // 2023-07-12
    function get_wship_consign(){
        $date_from = $_POST["date_from"];
        $date_to = $_POST["date_to"];

        $result = $this->model_operacion_tsc_delivery_h->get_wship_consigment($date_from, $date_to);

        if(count($result) == 0){
            $data["var_consign"] = 0;
        }
        else{
            $data["var_consign"] = assign_data($result);
        }

        $this->load->view('operacion/delivery/new/v_consign_detail',$data);
    }
    //---

    function upload(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('operacion_folder').'delivery/newdelivery/upload'])){
            $this->load->view('view_home');
        }
        else{

            /*$data["var_delv_part"] = assign_data($this->model_operacion_mst_delv_part->get_data());
            $data["var_delv_status"] = assign_data($this->model_operacion_mst_delv_status->get_data());
            $data["var_domilici"] = assign_data($this->model_operacion_mst_domicili->get_data());
            $data["var_vendor"] = assign_data($this->model_operacion_mst_vendor->get_data());
            $data["var_city"] = assign_data($this->model_operacion_mst_city->get_data());
            $data["var_driver"] = assign_data($this->model_operacion_mst_driver->get_data());
            $data["var_state"] = assign_data($this->model_operacion_mst_state->get_data());
            $data["var_payment_terms"] = assign_data($this->model_operacion_mst_payment_terms->get_data());*/
            //$data["var_payment_status"] = assign_data($this->model_operacion_mst_payment_status->get_data());

            $this->load->view('operacion/delivery/new/v_upload',$data);
        }
    }
    //--

    function upload_file(){

      $src = $_FILES['file']['tmp_name'];
      //$itr_code = $_POST['itr_code'];

      $target_file = $this->config->item('operacion_newdelivery');

      $temp = explode(".", $_FILES["file"]["name"]);
      $newfilename = round(microtime(true)) . '.' . end($temp);

      $targ = $target_file.$newfilename;
      $result["status"] = move_uploaded_file($src, $targ);

      if($result["status"] == 1) $result["filename"] = $newfilename;

      echo json_encode($result);

    }
    //---------------------

    function upload_file_checking(){

        $file = $_POST["attachment"];
        $target_file = $this->config->item('operacion_newdelivery');

        $open = fopen($target_file.$file, "r");

        while (($data_temp = fgetcsv($open, 1000, ",")) !== FALSE){
            $array[] = $data_temp;
        }

        fclose($open);

        $data["tables"] = $array;

        $data["var_delv_part"] = assign_data($this->model_operacion_mst_delv_part->get_data());
        $data["var_delv_status"] = assign_data($this->model_operacion_mst_delv_status->get_data());
        $data["var_domicili"] = assign_data($this->model_operacion_mst_domicili->get_data());
        $data["var_vendor"] = assign_data($this->model_operacion_mst_vendor->get_data());
        $data["var_city"] = assign_data($this->model_operacion_mst_city->get_data());
        $data["var_driver"] = assign_data($this->model_operacion_mst_driver->get_data());
        $data["var_state"] = assign_data($this->model_operacion_mst_state->get_data());
        $data["var_payment_terms"] = assign_data($this->model_operacion_mst_payment_terms->get_data());
        $data["var_payment_status"] = assign_data($this->model_operacion_mst_payment_status->get_data());


        // 2023-08-21
        $data["var_tracking_no_check"] = $this->check_tracking_no_already_exist_on_db($array);

        $this->load->view('operacion/delivery/new/v_upload_file_checking',$data);
    }
    //---

    function upload_file_process(){
        $sending_date = json_decode(stripslashes($_POST['sending_date']));
        $destino = json_decode(stripslashes($_POST['destino']));
        $estado = json_decode(stripslashes($_POST['estado']));
        $chofer = json_decode(stripslashes($_POST['chofer']));
        $vendor = json_decode(stripslashes($_POST['vendor']));
        $tracking_no = json_decode(stripslashes($_POST['tracking_no']));
        $domicili = json_decode(stripslashes($_POST['domicili']));
        $payment_terms = json_decode(stripslashes($_POST['payment_terms']));
        $remark_header = json_decode(stripslashes($_POST['remark_header']));
        $caja = json_decode(stripslashes($_POST['caja']));
        $pallet = json_decode(stripslashes($_POST['pallet']));
        $delivery_status = json_decode(stripslashes($_POST['delivery_status']));
        $subtotal_header = json_decode(stripslashes($_POST['subtotal_header']));
        $total_header = json_decode(stripslashes($_POST['total_header']));
        $doc_no = json_decode(stripslashes($_POST['doc_no']));
        $doc_date = json_decode(stripslashes($_POST['doc_date']));
        $so_ref = json_decode(stripslashes($_POST['so_ref']));
        $cust_no = json_decode(stripslashes($_POST['cust_no']));
        $total_detail = json_decode(stripslashes($_POST['total_detail']));
        $remarks_detail = json_decode(stripslashes($_POST['remarks_detail']));

        $total_row = count($tracking_no);
        unset($data_header); unset($data_detail);

        // transform to header and detail
        $tracking_no_temp = "";
        for($i=0;$i<$total_row;$i++){
            if($tracking_no_temp == ""){
                $tracking_no_temp = $tracking_no[$i];

                $data_header[] = array(
                    "sending_date"  => $sending_date[$i],
                    "destino"       => $destino[$i],
                    "estado"        => $estado[$i],
                    "chofer"        => $chofer[$i],
                    "tracking_no"   => $tracking_no[$i],
                    "domicili"      => $domicili[$i],
                    "payment_terms" => $payment_terms[$i],
                    "remark_header" => $remark_header[$i],
                    "caja"          => $caja[$i],
                    "pallet"        => $pallet[$i],
                    "delivery_status" => $delivery_status[$i],
                    "subtotal"      => $subtotal_header[$i],
                    "total"         => $total_header[$i],
                    "vendor"        => $vendor[$i],
                );

                if($cust_no[$i] == "1190027") $cust_name = "MECANICA TEK";
                else if($cust_no[$i] == "1190033") $cust_name = "SIGMA ALIMENTOS COMERCIAL";
                else $cust_name = "";

                $this->model_operacion_config->name = "tax";
                $tax_percentage = $this->model_operacion_config->get_value_by_setting_name();

                $subtotal = $total_detail[$i];
                $total_detail[$i] = round($total_detail[$i] * (1+$tax_percentage),2);

                $data_detail[$tracking_no_temp]["doc_no"][]   = $doc_no[$i];
                $data_detail[$tracking_no_temp]["doc_date"][] = $doc_date[$i];
                $data_detail[$tracking_no_temp]["so_ref"][]   = $so_ref[$i];
                $data_detail[$tracking_no_temp]["cust_no"][]  = $cust_no[$i];
                $data_detail[$tracking_no_temp]["total"][]    = $total_detail[$i];
                $data_detail[$tracking_no_temp]["remarks"][]  = $remarks_detail[$i];
                $data_detail[$tracking_no_temp]["cust_name"][]= $cust_name;
                $data_detail[$tracking_no_temp]["subtotal"][] = $subtotal;
                $data_detail[$tracking_no_temp]["address"][]  = "";
                $data_detail[$tracking_no_temp]["address2"][]  = "";
                $data_detail[$tracking_no_temp]["city"][]     = "";
                $data_detail[$tracking_no_temp]["state"][]    = "";
                $data_detail[$tracking_no_temp]["post_code"][]= "";
                $data_detail[$tracking_no_temp]["country"][]  = "MEX";
                $data_detail[$tracking_no_temp]["qty"][]      = "";
                $data_detail[$tracking_no_temp]["doc_type"][] = "3";
                $data_detail[$tracking_no_temp]["required_remark1"][]= "0";
            }
            else{
                if($tracking_no_temp != $tracking_no[$i]){
                    $tracking_no_temp = $tracking_no[$i];

                    $data_header[] = array(
                        "sending_date"  => $sending_date[$i],
                        "destino"       => $destino[$i],
                        "estado"        => $estado[$i],
                        "chofer"        => $chofer[$i],
                        "tracking_no"   => $tracking_no[$i],
                        "domicili"      => $domicili[$i],
                        "payment_terms" => $payment_terms[$i],
                        "remark_header" => $remark_header[$i],
                        "caja"          => $caja[$i],
                        "pallet"        => $pallet[$i],
                        "delivery_status" => $delivery_status[$i],
                        "subtotal"      => $subtotal_header[$i],
                        "total"         => $total_header[$i],
                        "vendor"        => $vendor[$i],
                    );

                    if($cust_no[$i] == "1190027") $cust_name = "MECANICA TEK";
                    else if($cust_no[$i] == "1190033") $cust_name = "SIGMA ALIMENTOS COMERCIAL";
                    else $cust_name = "";

                    $this->model_operacion_config->name = "tax";
                    $tax_percentage = $this->model_operacion_config->get_value_by_setting_name();

                    $subtotal = $total_detail[$i];
                    $total_detail[$i] = round($total_detail[$i] * (1+$tax_percentage),2);

                    $data_detail[$tracking_no_temp]["doc_no"][]   = $doc_no[$i];
                    $data_detail[$tracking_no_temp]["doc_date"][] = $doc_date[$i];
                    $data_detail[$tracking_no_temp]["so_ref"][]   = $so_ref[$i];
                    $data_detail[$tracking_no_temp]["cust_no"][]  = $cust_no[$i];
                    $data_detail[$tracking_no_temp]["total"][]    = $total_detail[$i];
                    $data_detail[$tracking_no_temp]["remarks"][]  = $remarks_detail[$i];
                    $data_detail[$tracking_no_temp]["cust_name"][]= $cust_name;
                    $data_detail[$tracking_no_temp]["subtotal"][] = $subtotal;
                    $data_detail[$tracking_no_temp]["address"][]  = "";
                    $data_detail[$tracking_no_temp]["address2"][]  = "";
                    $data_detail[$tracking_no_temp]["city"][]     = "";
                    $data_detail[$tracking_no_temp]["state"][]    = "";
                    $data_detail[$tracking_no_temp]["post_code"][]= "";
                    $data_detail[$tracking_no_temp]["country"][]  = "MEX";
                    $data_detail[$tracking_no_temp]["qty"][]      = "";
                    $data_detail[$tracking_no_temp]["doc_type"][] = "3";
                    $data_detail[$tracking_no_temp]["required_remark1"][]= "0";
                }
                else{
                    if($cust_no[$i] == "1190027") $cust_name = "MECANICA TEK";
                    else if($cust_no[$i] == "1190033") $cust_name = "SIGMA ALIMENTOS COMERCIAL";
                    else $cust_name = "";

                    $this->model_operacion_config->name = "tax";
                    $tax_percentage = $this->model_operacion_config->get_value_by_setting_name();

                    $subtotal = $total_detail[$i];
                    $total_detail[$i] = round($total_detail[$i] * (1+$tax_percentage),2);

                    //$subtotal = $total_detail[$i] / (1+$tax_percentage);

                    $data_detail[$tracking_no_temp]["doc_no"][]   = $doc_no[$i];
                    $data_detail[$tracking_no_temp]["doc_date"][] = $doc_date[$i];
                    $data_detail[$tracking_no_temp]["so_ref"][]   = $so_ref[$i];
                    $data_detail[$tracking_no_temp]["cust_no"][]  = $cust_no[$i];
                    $data_detail[$tracking_no_temp]["total"][]    = $total_detail[$i];
                    $data_detail[$tracking_no_temp]["remarks"][]  = $remarks_detail[$i];
                    $data_detail[$tracking_no_temp]["cust_name"][]= $cust_name;
                    $data_detail[$tracking_no_temp]["subtotal"][] = $subtotal;
                    $data_detail[$tracking_no_temp]["address"][]  = "";
                    $data_detail[$tracking_no_temp]["address2"][]  = "";
                    $data_detail[$tracking_no_temp]["city"][]     = "";
                    $data_detail[$tracking_no_temp]["state"][]    = "";
                    $data_detail[$tracking_no_temp]["post_code"][]= "";
                    $data_detail[$tracking_no_temp]["country"][]  = "MEX";
                    $data_detail[$tracking_no_temp]["qty"][]      = "";
                    $data_detail[$tracking_no_temp]["doc_type"][] = "3";
                    $data_detail[$tracking_no_temp]["required_remark1"][]= "0";
                }

            }
        }
        //---

        // start insert
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $created_user = $session_data['z_tpimx_user_id'];

        foreach($data_header as $row){

          $new_doc_no = $this->insert_header($row["sending_date"], $row["destino"], $row["estado"], $row["chofer"], $row["vendor"], $row["tracking_no"], $row["caja"], $row["pallet"], $row["domicili"], $row["payment_terms"],
          $row["delivery_status"], $row["total"], $created_user, $row["remark_header"], $row["subtotal"],"");

          $this->insert_detail($new_doc_no, $data_detail[$row["tracking_no"]]["doc_no"], $data_detail[$row["tracking_no"]]["doc_date"], $data_detail[$row["tracking_no"]]["so_ref"], $data_detail[$row["tracking_no"]]["cust_no"], $data_detail[$row["tracking_no"]]["subtotal"],
          $data_detail[$row["tracking_no"]]["total"], $data_detail[$row["tracking_no"]]["remarks"], $data_detail[$row["tracking_no"]]["cust_name"],
          $data_detail[$row["tracking_no"]]["address"], $data_detail[$row["tracking_no"]]["address2"], $data_detail[$row["tracking_no"]]["city"], $data_detail[$row["tracking_no"]]["state"], $data_detail[$row["tracking_no"]]["post_code"],
          $data_detail[$row["tracking_no"]]["country"], $data_detail[$row["tracking_no"]]["qty"], $created_user, $data_detail[$row["tracking_no"]]["doc_type"], $data_detail[$row["tracking_no"]]["required_remark1"]);

        }
        //---

        if($new_doc_no){
            $response['status'] = "1";
            $response['msg'] = "Succes, New Delivery Document have been uploaded";
            echo json_encode($response);
        }
        else{
          $response['status'] = "0";
          $response['msg'] = "Error";
          echo json_encode($response);
        }
    }
    //--

    function check_tracking_no_already_exist_on_db($array){

        unset($data); unset($result);
        $first = 1;
        $temp = "";

        foreach($array as $row){
          if($first == 1){
            $data[] = $row[5];
            $temp = $row[5];
          }
          else{
            if($temp != $row[5]){
                $data[] = $row[5];
                $temp = $row[5];
            }
          }

          $first++;
        }
        //--

        foreach($data as $row){
            $result_check = $this->model_operacion_tsc_delivery_h->check_trackingno_not_existing($row);

            if(is_null($result_check["tracking_no"]) or $result_check["tracking_no"] == "") $no_exist = 1;
            else $no_exist = 0;

            $result[] = array(
              "tracking_no" => $row,
              "no_exist"    => $no_exist,
              "delv_no"     => $result_check["doc_no"],
            );
        }
        //--

        return $result;
    }
    //--
}

?>
