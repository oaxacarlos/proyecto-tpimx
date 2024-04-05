<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Whship extends CI_Controller{
    function __construct(){
      parent::__construct();
      $this->load->database();

      $this->load->model('model_zlog','',TRUE);
    }

    function nav(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'whship/nav'])){
            $this->load->view('view_home');
        }
        else{
            $this->model_zlog->insert("Warehouse - OutBound WH Shipment"); // insert log

            $this->load->view('wms/outbound/nav/v_nav_whship');
        }
    }
    //---

    function get_nav_whship_list_h(){
        $this->load->model('model_outbound','',TRUE);
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_config','',TRUE);

        // get header
        $result = $this->model_outbound->whship_list_h();
        unset($data['var_whship_list_h']);
        if($result) $data['var_whship_list_h'] = assign_data($result);

        //---

        if($result){
            // check if already in tsc_in_out_bound_h
            unset($doc_no);
            foreach($data['var_whship_list_h'] as $row){
                $doc_no[] = $row['no'];
            }
            $result_in_out_bound_h = $this->model_tsc_in_out_bound_h->check_h_by_doc_no($doc_no);
            //----

            // remove data already proceed to in_out_bound_h
            if(count($result_in_out_bound_h ) > 0){
                $data['var_whship_list_h'] = $this->remove_already_process_from_whship_to_in_out_bound($data['var_whship_list_h'], $result_in_out_bound_h);
            }
            //----

            // get config locked outbound 2023-06-16
            $this->model_config->name = "locked_no_pull_nav_wship";
            $result_config = $this->model_config->get_value_by_setting_name();
            $temp = explode("-",$result_config);
            $data["locked_doc_from"]  = $temp[0];
            $data["locked_doc_to"]    = $temp[1];
            //--
        }

        $this->load->view('wms/outbound/nav/v_nav_whship_list',$data);
    }
    //----

    function remove_already_process_from_whship_to_in_out_bound($whship_h, $in_out_bound_h){
      $temp = $whship_h;
      unset($whship_h);
      foreach($temp as $key => $row){
          $is_there = 0;
          foreach($in_out_bound_h as $row2){
              if($row['no'] == $row2['doc_no']){
                  $is_there = 1; break;
              }
          }

          if($is_there == 0) $whship_h[] = $temp[$key];
      }

      return $whship_h;
    }
    //----

    function get_whship_list_d(){
        $id     = $_POST['id'];
        $return_link = $_POST['link'];

        $this->load->model('model_outbound','',TRUE);

        // get line
        unset($doc_no);
        $doc_no[] = $id;
        $result = $this->model_outbound->whship_list_d($doc_no);
        $data['var_whship_list_d'] = assign_data($result);
        $data['doc_no'] = $id;
        //----

        $this->load->view($return_link,$data);
    }
    //----

   function transfer_whship_to_warehouse(){
      $this->model_zlog->insert("Warehouse - Outbound Transfer to WH Shipment"); // insert log

      $id = $_POST['id'];
      $month_end = $_POST['month_end'];

      $this->load->model('model_inbound','',TRUE);
      $this->load->model('model_outbound','',TRUE);

      $exist = $this->model_inbound->check_tsc_in_out_bound_h_existing($id);

      if($exist > 0){ // check if already proceed
          $response['status'] = "0";
          $response['msg'] = "The Data has been process by another user";
          echo json_encode($response);
      }
      else{
          // pull header from navision
          $result = $this->model_outbound->whship_list_h_by_no($id);
          $data['var_whship_list_h'] = assign_data_one($result);
          //---

          // pull detail from navision
          unset($doc_no);
          $doc_no[] = $id;
          $result = $this->model_outbound->whship_list_d($doc_no);
          $data['var_whship_list_d'] = assign_data($result);
          //---

          $result_h = $this->insert_tsc_in_out_bound_h($data['var_whship_list_h'], $month_end); // insert to tsc_in_out_bound_h
          $result_d = $this->insert_tsc_in_out_bound_d($data['var_whship_list_d']); // insert to tsc_in_out_bound_d

          $result_so = $this->insert_so_to_information($data['var_whship_list_d']);  // pull sales order information

          if($month_end == 1) $this->update_month_end($id,"1");  // update month end

          if($result_h && $result_d){
              $response['status'] = "1";
              $response['msg'] = "The WH Shipment has been transfered to Warehouse";
              echo json_encode($response);
          }
          else{
            $response['status'] = "0";
            $response['msg'] = "Error ".$result_so;
            echo json_encode($response);
          }
      }
    }
    //-----

    function insert_tsc_in_out_bound_h($data, $month_end){
        // declare
        $datetime = get_datetime_now();
        $date = get_date_now();
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->load->model('model_tsc_doc_history','',TRUE);
        //---

        $this->model_tsc_in_out_bound_h->doc_no = $data["no"];
        $this->model_tsc_in_out_bound_h->doc_datetime = $datetime;
        $this->model_tsc_in_out_bound_h->created_datetime = $datetime;
        $this->model_tsc_in_out_bound_h->doc_type = "2";
        $this->model_tsc_in_out_bound_h->doc_location_code = $data["loc_code"];
        $this->model_tsc_in_out_bound_h->month_end = 0;
        $this->model_tsc_in_out_bound_h->created_user = $session_data['z_tpimx_user_id'];

        if($data["ext_doc_no"]) $this->model_tsc_in_out_bound_h->external_document = $data["ext_doc_no"];
        else $this->model_tsc_in_out_bound_h->external_document = "";

        $this->model_tsc_in_out_bound_h->status = "1";
        $this->model_tsc_in_out_bound_h->doc_date = $date;
        $this->model_tsc_in_out_bound_h->doc_posting_date = $data["posting_date"];
        $result = $this->model_tsc_in_out_bound_h->insert_h();

        // insert doc history
        $this->model_tsc_doc_history->insert($data["no"],"","","1","",$datetime,"Start Whship",$month_end);
        //--

        if($result) return true; else return false;
    }
    //---

    function insert_tsc_in_out_bound_d($data){
        // declare
        $datetime = get_datetime_now();
        $date = get_date_now();
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $this->load->model('model_tsc_in_out_bound_d','',TRUE);
        //---

        /*foreach($data as $row){

            // if consigment update the dest_no
            if(strpos($row["src_no"], "TO") !== false){
              $dest_no = $this->get_new_dest_no($row["src_no"]);
            }
            else{ $dest_no = $row["destination_no"]; }
            //---

            $this->model_tsc_in_out_bound_d->doc_no = $row["no"];
            $this->model_tsc_in_out_bound_d->line_no = $row["line_no"];
            $this->model_tsc_in_out_bound_d->src_location_code = $row["location_code"];
            $this->model_tsc_in_out_bound_d->src_no = $row["src_no"];
            $this->model_tsc_in_out_bound_d->src_line_no = $row["src_line_no"];
            $this->model_tsc_in_out_bound_d->item_code = $row["item_no"];
            $this->model_tsc_in_out_bound_d->qty_to_ship = $row["qty_to_ship"];
            $this->model_tsc_in_out_bound_d->qty_outstanding = $row["qty_to_ship"];
            $this->model_tsc_in_out_bound_d->qty_to_packed = 0;
            $this->model_tsc_in_out_bound_d->qty_packed_outstanding = $row["qty_to_ship"];
            $this->model_tsc_in_out_bound_d->qty = $row["qty"];
            $this->model_tsc_in_out_bound_d->uom = $row["uom"];
            $this->model_tsc_in_out_bound_d->description = $row["description"];
            $this->model_tsc_in_out_bound_d->dest_no = $dest_no;
            $this->model_tsc_in_out_bound_d->qty_to_picked = 0;
            $this->model_tsc_in_out_bound_d->qty_outstanding = $row["qty_to_ship"];
            $this->model_tsc_in_out_bound_d->master_barcode = 0; // 2023-01-18 master barcode
            $this->model_tsc_in_out_bound_d->valuee = 0; // 2023-02-13 valuee
            $this->model_tsc_in_out_bound_d->valuee_per_pcs = 0; // 2023-02-13 valuee
            $result = $this->model_tsc_in_out_bound_d->insert_d();
        }*/

        unset($data2);
        foreach($data as $row){

          // if consigment update the dest_no
          if($row["destination_no"]!="WH2" || $row["destination_no"]!="WH3"){ // 2023-03-01 WH3
             $dest_no = $row["destination_no"];
          }
          else if(strpos($row["src_no"], "TO") !== false){
             $dest_no = $this->get_new_dest_no($row["src_no"]);
          }
          else{ $dest_no = $row["destination_no"]; }
          //---

          $data2[] = array(
              "doc_no" => $row["no"],
              "line_no" => $row["line_no"],
              "src_location_code" => $row["location_code"],
              "src_no" => $row["src_no"],
              "src_line_no" => $row["src_line_no"],
              "item_code" => $row["item_no"],
              "uom" => $row["uom"],
              "description" => $row["description"],
              "qty" => $row["qty"],
              "qty_received" => '0',
              "qty_to_ship" => $row["qty_to_ship"],
              "qty_to_packed" => 0,
              "qty_packed_outstanding" => $row["qty_to_ship"],
              "dest_no" => $dest_no,
              "qty_to_picked" => 0,
              "qty_outstanding" => $row["qty_to_ship"],
              "master_barcode" => 0, // 2023-01-17 master barcode
              "valuee" => 0, // valuee 2023-01-30
              "valuee_per_pcs" => 0, // valuee 2023-01-30
          );
        }

        $result = $this->model_tsc_in_out_bound_d->insert_d_ver2($data2);

        if($result) return true; else false;
    }
    //---

    function warehouse(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('wms_outbound_folder').'whship/warehouse'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('wms/outbound/warehouse/v_index');
        }
    }

    //---
    function get_warehouse(){
      $this->load->model('model_tsc_in_out_bound_h','',TRUE);

      $this->model_tsc_in_out_bound_h->doc_type = '2';
      $status = array("1","99");
      $result = $this->model_tsc_in_out_bound_h->list_with_doc_type_one_and_qty($status);
      $data['var_ship'] = assign_data($result);

      $this->load->view('wms/outbound/warehouse/v_list',$data);
    }
    //--

    function get_warehouse_detail(){
        $id      = $_POST['id'];
        $return_link = $_POST['link'];
        $loc_code = $_POST['loc_code'];

        $this->load->model('model_tsc_in_out_bound_d','',TRUE);

        unset($doc_no);
        $doc_no[] = $id;
        $result = $this->model_tsc_in_out_bound_d->get_list($doc_no);
        $data['var_warehouse_detail'] = assign_data($result);
        $data['doc_no_h'] = $id;
        $data['loc_code_h'] = $loc_code;

        $this->load->view($return_link,$data);
    }
    //----

    function insert_so_to_information($whship_d){
        $this->load->model('model_tsc_so','',TRUE);
        $this->load->model('model_outbound','',TRUE);
        $this->load->model('model_mst_cust','',TRUE);
        $this->load->model('model_mst_ship_to','',TRUE);

        unset($temp_dest);
        foreach($whship_d as $row){

            // check if need insert
            $insert = 0;
            if(is_null($temp_dest)){
                $insert = 1;
                $temp_dest[] = $row['src_no'];
            }
            else{
                if(in_array($row['src_no'], $temp_dest)) $insert = 0;
                else $insert = 1;
            }
            //---

            // if need insert
            if($insert == 1){
              if(!$this->model_tsc_so->is_exist($row['src_no'])){
                if(strpos($row["src_no"], "SO") !== false){
                      $this->model_outbound->so_no = $row['src_no'];
                      $result = $this->model_outbound->get_so_header_shipto();

                      foreach($result as $row2){
                            $this->insert_so($row2['so_no'],$row2['loc_code'],$row2['sell_cust_no'], $row2['bill_cust_no'], $row2['bill_to_name'], $row2['bill_to_addr'], $row2['bill_to_addr2'], $row2['bill_to_city'], $row2['bill_to_contact'], $row2['bill_to_post_code'], $row2['bill_to_county'],
                            $row2['bill_to_ctry_region_code'], $row2['ship_to_name'], $row2['ship_to_addr'], $row2['ship_to_addr2'], $row2['ship_to_city'], $row2['ship_to_contact'], $row2['ship_to_post_code'], $row2['ship_to_county'], $row2['ship_to_ctry_region_code'],
                            $row2['sell_to_customer_name'], $row2['sell_to_addr'], $row2['sell_to_addr2'], $row2['sell_to_city'], $row2['sell_to_contact'], $row2['sell_to_post_code'], $row2['sell_to_county'], $row2['sell_to_ctry_code']);

                      }

                }
                else if(strpos($row["src_no"], "TO") !== false){

                    if($row["destination_no"]=="WH2" || $row["destination_no"]=="WH3"){ // 2023-03-01 WH3
                        $this->model_mst_cust->cust_no = $row["destination_no"];
                        $result_cust = $this->model_mst_cust->get_list_by_cust_no();
                        $result_cust = assign_data_one($result_cust);

                        // get ship to information
                        $this->model_mst_ship_to->code = $row["destination_no"];
                        $result_shipto = $this->model_mst_ship_to->get_list_by_ship_to_code();
                        $result_shipto = assign_data_one($result_shipto);

                        $this->insert_so($row["src_no"],$result_shipto['location_code'],$result_cust['cust_no'], $result_cust['cust_no'], $result_cust['name'], $result_cust['address'], $result_cust['address2'], $result_cust['city'], $result_cust['contact'], $result_cust['post_code'], $result_cust['county'],
                        $result_cust['country_region_code'], $result_shipto['name'], $result_shipto['address'], $result_shipto['address2'], $result_shipto['city'], $result_shipto['contact'], $result_shipto['post_code'], $result_shipto['county'], $result_shipto['country_region_code'],
                        $result_cust['name'], $result_cust['address'], $result_cust['address2'], $result_cust['city'], $result_cust['contact'], $result_cust['post_code'], $result_cust['county'], $result_cust['country_region_code']);
                    }
                    else{
                        $this->model_outbound->to_no = $row["src_no"];
                        $result = $this->model_outbound->get_transfer_order_line();
                        foreach($result as $row2){

                            if($row2["transfer_to_bin_code"]!=''){
                                // get bin data
                                $this->model_outbound->code = $row2["transfer_to_bin_code"];
                                $this->model_outbound->location_code = $row2["transfer_to_code"];
                                $result2 = $this->model_outbound->get_bin_info();

                                foreach($result2 as $row3){
                                    // get customer info
                                    $this->model_mst_cust->cust_no = $row3['cust_no'];
                                    $result_cust = $this->model_mst_cust->get_list_by_cust_no();
                                    $result_cust = assign_data_one($result_cust);

                                    //print_r($result_cust);

                                    // get ship to information
                                    $this->model_mst_ship_to->code = $row3['ship_to'];
                                    $result_shipto = $this->model_mst_ship_to->get_list_by_ship_to_code();
                                    $result_shipto = assign_data_one($result_shipto);

                                    //print_r($result_shipto);

                                    $this->insert_so($row["src_no"],$row3['location_code'],$result_cust['cust_no'], $result_cust['cust_no'], $result_cust['name'], $result_cust['address'], $result_cust['address2'], $result_cust['city'], $result_cust['contact'], $result_cust['post_code'], $result_cust['county'],
                                    $result_cust['country_region_code'], $result_shipto['name'], $result_shipto['address'], $result_shipto['address2'], $result_shipto['city'], $result_shipto['contact'], $result_shipto['post_code'], $result_shipto['county'], $result_shipto['country_region_code'],
                                    $result_cust['name'], $result_cust['address'], $result_cust['address2'], $result_cust['city'], $result_cust['contact'], $result_cust['post_code'], $result_cust['county'], $result_cust['country_region_code']);
                                }
                            }
                            else{
                                $this->insert_so($row["src_no"],$row3['location_code'],"9990001", "9990001", "PUBLICO EN GENERAL", "AVENIDA CEYLAN 959", "BODEGA 23", "AZCAPOTZALCO", "PUBLICO EN GENERAL", "02300", "CIUDAD DE MEXICO",
                                "MEX", "PUBLICO EN GENERAL", "AVENIDA CEYLAN 959", "BODEGA 23", "AZCAPOTZALCO", "PUBLICO EN GENERAL", "02300", "CIUDAD DE MEXICO", "MEX",
                                "PUBLICO EN GENERAL", "AVENIDA CEYLAN 959", "BODEGA 23", "AZCAPOTZALCO", "PUBLICO EN GENERAL", "02300", "CIUDAD DE MEXICO", "MEX");
                            }
                        }
                    }

                }
              }
            }
        }
    }
    //---
    function insert_so($so_no,$location_code,$sell_cust_no,$bill_cust_no,$bill_cust_name,$bill_to_addr,$bill_to_addr2,$bill_to_city,$bill_to_contact, $bill_to_post_code, $bill_to_county, $bill_to_ctry_region_code, $ship_to_name, $ship_to_addr, $ship_to_addr2,$ship_to_city, $ship_to_contact, $ship_to_post_code, $ship_to_county, $ship_to_ctry_region_code,
    $sell_to_cust_name, $sell_to_cust_addr, $sell_to_cust_addr2, $sell_to_city, $sell_to_contact, $sell_to_post_code, $sell_to_county, $sell_to_ctry_code){

        $this->load->model('model_tsc_so','',TRUE);
        $this->model_tsc_so->so_no = $so_no;
        $this->model_tsc_so->location_code = $location_code;
        $this->model_tsc_so->sell_cust_no = $sell_cust_no;
        $this->model_tsc_so->bill_cust_no = $bill_cust_no;
        $this->model_tsc_so->bill_cust_name = $bill_cust_name;
        $this->model_tsc_so->bill_to_addr = $bill_to_addr;
        $this->model_tsc_so->bill_to_addr2 = $bill_to_addr2;
        $this->model_tsc_so->bill_to_city = $bill_to_city;
        $this->model_tsc_so->bill_to_contact = $bill_to_contact ;
        $this->model_tsc_so->bill_to_post_code = $bill_to_post_code;
        $this->model_tsc_so->bill_to_county = $bill_to_county;
        $this->model_tsc_so->bill_to_ctry_region_code = $bill_to_ctry_region_code;
        $this->model_tsc_so->ship_to_name = $ship_to_name;
        $this->model_tsc_so->ship_to_addr = $ship_to_addr;
        $this->model_tsc_so->ship_to_addr2 = $ship_to_addr2;
        $this->model_tsc_so->ship_to_city = $ship_to_city;
        $this->model_tsc_so->ship_to_contact = $ship_to_contact;
        $this->model_tsc_so->ship_to_post_code = $ship_to_post_code;
        $this->model_tsc_so->ship_to_county = $ship_to_county;
        $this->model_tsc_so->ship_to_ctry_region_code = $ship_to_ctry_region_code;
        $this->model_tsc_so->sell_to_cust_name = $sell_to_cust_name;
        $this->model_tsc_so->sell_to_cust_addr = $sell_to_cust_addr;
        $this->model_tsc_so->sell_to_cust_addr2 = $sell_to_cust_addr2;
        $this->model_tsc_so->sell_to_city = $sell_to_city;
        $this->model_tsc_so->sell_to_contact = $sell_to_contact;
        $this->model_tsc_so->sell_to_post_code = $sell_to_post_code;
        $this->model_tsc_so->sell_to_county = $sell_to_county;
        $this->model_tsc_so->sell_to_ctry_code = $sell_to_ctry_code;
        $result = $this->model_tsc_so->insert();

        return $result;
    }

    //---

    function check_month_end(){
        $whs = $_POST["whs"];

        $datetime = get_datetime_now();

        $this->load->model('model_tsc_month_end','',TRUE);

        $this->model_tsc_month_end->fromm = $datetime;
        $this->model_tsc_month_end->too = $datetime;
        //$result = $this->model_tsc_month_end->get_month_end();
        $result = $this->model_tsc_month_end->get_month_end_by_whs($whs);

        echo json_encode($result);
    }
    //---

    function update_month_end($id,$update){
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        $this->model_tsc_in_out_bound_h->month_end = $update;
        $this->model_tsc_in_out_bound_h->doc_no = $id;
        $this->model_tsc_in_out_bound_h->update_month_end();
    }
    //---

    function check_doc_locked(){
        $id = $_POST['id'];
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->model_tsc_in_out_bound_h->doc_no = $id;
        $result = $this->model_tsc_in_out_bound_h->check_doc_locked();

        if($result["locked"]=="1") echo "1"; else echo "0";

        /*if($result["locked"]=="0") echo "0";
        else if($result["locked"]=="1" || $result["user_locked"] == $session_data['z_tpimx_user_id']) echo "0";
        else if($result["locked"]=="1" || $result["user_locked"] != $session_data['z_tpimx_user_id']) echo "1";*/
    }
    //---

    function doc_locked(){
        $id = $_POST['id'];
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        // update locked
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $this->model_tsc_in_out_bound_h->doc_no = $id;
        $result = $this->model_tsc_in_out_bound_h->update_doc_to_locked($session_data['z_tpimx_user_id']);
        if($result) echo "1"; else echo "0";
    }
    //---

    function doc_unlocked(){
        $id = $_POST['id'];
        $this->load->model('model_tsc_in_out_bound_h','',TRUE);

        // update locked
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $this->model_tsc_in_out_bound_h->doc_no = $id;
        $result = $this->model_tsc_in_out_bound_h->update_doc_to_unlocked();
        if($result) echo "1"; else echo "0";
    }
    //---

    function get_new_dest_no($src_no){
        $this->load->model('model_outbound','',TRUE);
        $this->model_outbound->to_no = $src_no;
        $result = $this->model_outbound->get_transfer_order_line();

          $result = assign_data_one($result);

          // get bin data
          $this->model_outbound->code = $result["transfer_to_bin_code"];
          $this->model_outbound->location_code = $result["transfer_to_code"];
          $result2 = $this->model_outbound->get_bin_info();
          if(count($result2) > 0){
            $result2 = assign_data_one($result2);
          }
          else{
            $result2["ship_to"] = "9990001";
          }

        return $result2["ship_to"];
    }
    //--

    function check_status(){
        $status = $_POST["status"];
        $doc_no = $_POST["id"];

        $this->load->model('model_tsc_in_out_bound_h','',TRUE);
        $this->model_tsc_in_out_bound_h->doc_no = $doc_no;
        $result = assign_data_one($this->model_tsc_in_out_bound_h->get_one_doc_h());

        if($status == $result["status1"]) echo "1";
        else echo "0";
    }
    //--
}


?>
