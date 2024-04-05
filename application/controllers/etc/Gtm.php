<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gtm extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

  function index(){
      $this->load->view('templates/navigation');
  }
  //----------------

  function mps(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['gtm/mps'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->model('model_gtm','',TRUE);

        // get region
        $result = $this->model_gtm->list_region();
        if($result){
          foreach($result as $row){
              $data['v_list_region'][] = array(
                  "kodescabang" => $row['kodescabang'],
                  "ket"         => $row['ket'],
              );
          }
        }

        $this->load->view('gtm/v_mps',$data);
    }
  }
  //------------------------

  function mps_load_supervisor(){
      $region = $_POST['region'];

      $this->load->model('model_gtm','',TRUE);
      $result = $this->model_gtm->list_sales_supervisor_by_region($region);
      if($result){
        foreach($result as $row){
            $data[] = array(
                "slsno"       => $row['slsno'],
                "slsname"     => $row['slsname'],
                "slstp"       => $row['slstp'],
                "divisi"      => $row['divisi'],
                "kodecabang"  => $row['kodecabang'],
            );
        }
      }

      echo json_encode($data);
      die();
  }
  //----------------

  function mps_load_keywhosaler(){
      $supervisor = $_POST['supervisor'];

      $this->load->model('model_gtm','',TRUE);
      $result = $this->model_gtm->list_keywhosaler_by_supervisor($supervisor);
      if($result){
        foreach($result as $row){
            $data[] = array(
                "slsno"       => $row['slsno'],
                "custno"      => $row['custno'],
                "custname"    => $row['custname'],
            );
        }
      }

      echo json_encode($data);
      die();
  }
  //------------------

  function mps_generate(){
      $region       = $_POST['region'];
      $supervisor   = $_POST['supervisor'];
      $keywhosaler  = $_POST['keywhosaler'];
      $date         = $_POST['date'];

      $temp = explode("-",$date);

      $month = $temp[1]; $year = $temp[0];

      $this->load->model('model_gtm','',TRUE);
      $output_string = "";

      // get ss name
      $supervisor_name = $this->model_gtm->get_supervisor_name($supervisor);

      // get keywhosaler name
      $keywhosaler_name = $this->model_gtm->get_keywhosaler_name($keywhosaler);

      // get frequency
      $frequency = $this->model_gtm->get_frequency_frute_order($supervisor,$keywhosaler);

      // get top and credit limit
      $result = $this->model_gtm->get_top_credit_limit($keywhosaler);
      if($result){
        foreach($result as $row){
            $top = $row['top']." - ".$row['top_desc'];
            $credit_limit = $row['climit'];
        }
      }

      $output_string.=  "<table class='table table-bordered' style='font-family:Verdana; font-size:14px;'>";
        $output_string.="<tr><td style='font-size:20px;' colspan='3'><b>Monthly Planning Sheet</td>";
        $output_string.="<td></td>";
        $output_string.="<td colspan='3'></td>";
        $output_string.="<td></td>";
        $output_string.="<td colspan='3'></td>";
        $output_string.="<td></td>";
        $output_string.="<td colspan='3'><b>Month : <u>".date("F",strtotime($date))."</u></td>";
        $output_string.="</tr>";

        $output_string.="<tr>";
          $output_string.="<td colspan='3'><b>SS Name : <u>".$supervisor_name."</u></td>";
          $output_string.="<td></td>";
          $output_string.="<td colspan='3'><b>Route :</td>";
          $output_string.="<td></td>";
          $output_string.="<td colspan='3'><b>TOP : <u>".$top."</u></td>";
          $output_string.="<td></td>";
          $output_string.="<td colspan='3'><b>Year : <u>".$year."</u></td>";
        $output_string.="</tr>";

        $output_string.="<tr>";
          $output_string.="<td colspan='3'><b>KW Name : <u>".$keywhosaler_name."</u></td>";
          $output_string.="<td></td>";
          $output_string.="<td colspan='3'><b>Freq : <u>F".$frequency."</u></td>";
          $output_string.="<td></td>";
          $output_string.="<td colspan='3'><b>Credit Limit : <u>".$credit_limit."</u></td>";
          $output_string.="<td></td>";
          $output_string.="<td colspan='3'><b>Planning Date : <u>".$date."</u></td>";
        $output_string.="</tr>";

      $output_string.=  "</table>";

      // detail
      $this->model_gtm->custno = $keywhosaler;

      // last month
      $this->model_gtm->last_inventory_month = date('m', strtotime($date." -1 month"));
      $this->model_gtm->last_inventory_year  = date('Y', strtotime($date." -1 month"));

      // last 3 month
      $this->model_gtm->last_3month_from = date('Y-m-d', strtotime($date." first day of -3 month"));
      $this->model_gtm->last_3month_to   = date('Y-m-d', strtotime($date." last day of previous month"));

      // for target
      $this->model_gtm->month = $month;
      $this->model_gtm->year = $year;

      $result = $this->model_gtm->mps_report();

      $output.="<table>
                  <tr><td></td></tr>
                  <tr><td></td></tr>
                </table>";

      $output_string.="<table class='table table-bordered table-striped' style='font-family:Verdana; font-size:14px;' >";

      $output_string.="<tr>";
        $output_string.="<td colspan='3'></td>";
        $output_string.="<td colspan='2'><b>P3M Sales</td>";
        $output_string.="<td></td>";
        $output_string.="<td colspan='5'><b>Planning</td>";
        $output_string.="<td colspan='2'></td>";
        $output_string.="<td colspan='5'><b>Actual</td>";
      $output_string.="</tr>";

      $output_string.="<tr>";
        $output_string.="<td><b>No</td>";
        $output_string.="<td><b>SKU</td>";
        $output_string.="<td><b>INV</td>";
        $output_string.="<td><b>per Month</td>";
        $output_string.="<td><b>per Day</td>";
        $output_string.="<td><b>DIL</td>";
        $output_string.="<td><b>Week 1 (1-7)</td>";
        $output_string.="<td><b>Week 2 (8-14)</td>";
        $output_string.="<td><b>Week 3 (15-21)</td>";
        $output_string.="<td><b>Week 4 (21-29)</td>";
        $output_string.="<td><b>Total</td>";
        $output_string.="<td><b>Target</td>";
        $output_string.="<td><b>Value</td>";
        $output_string.="<td><b>Week 1 (1-7)</td>";
        $output_string.="<td><b>Week 2 (8-14)</td>";
        $output_string.="<td><b>Week 3 (15-21)</td>";
        $output_string.="<td><b>Week 4 (21-29)</td>";
        $output_string.="<td><b>Total</td>";
      $output_string.="</tr>";

      if($result){
          $index=1;
          foreach($result as $row){
              $output_string.="<tr style='font-size:14px;'>";
                $output_string.="<td>".$index."</td>";
                $output_string.="<td style='font-size:18px;'>".$row['brandname']."</td>";
                $output_string.="<td>".number_format($row['last_inv'])."</td>";
                $output_string.="<td>".number_format(round($row['last_3month_invoice']))."</td>";
                $output_string.="<td>".number_format(round($row['last_3month_invoice_per_day']))."</td>";
                $output_string.="<td>".number_format(round($row['dil']))."</td>";
                $output_string.="<td></td>";
                $output_string.="<td></td>";
                $output_string.="<td></td>";
                $output_string.="<td></td>";
                $output_string.="<td></td>";
                $output_string.="<td>".number_format($row['target'])."</td>";
                $output_string.="<td></td>";
                $output_string.="<td></td>";
                $output_string.="<td></td>";
                $output_string.="<td></td>";
                $output_string.="<td></td>";
                $output_string.="<td></td>";
              $output_string.="</tr>";

              $index++;
          }
      }

      $output_string.="</table>";

      echo json_encode($output_string);

  }
  //------------------

  function sales_invoice(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['gtm/sales_invoice'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('gtm/v_sales_invoice',$data);
    }
  }
  //------------------------

  function sales_invoice_generate(){
      $this->load->model('model_gtm','',TRUE);

      $date_from  = $_POST['date_from'];
      $date_to    = $_POST['date_to'];

      $result = $this->model_gtm->get_sales_invoice($date_from,$date_to);

      if($result){
          foreach($result as $row){
            $data['v_list_sales_invoice'][] = array(
                "docnumber"   => $row['docnumber'],
                "docdate"     => $row['docdate'],
                "custcode"    => $row['custcode'],
                "custname"    => $row['custname'],
                "divch"       => $row['divch'],
                "custgroup"   => $row['custgroup'],
                "region"      => $row['region'],
                "qty"         => $row['qty'],
                "netweight"   => $row['netweight'],
                "price"       => $row['price'],
                "priceaftervat" => $row['priceaftervat'],
                "discpercent" => $row['discpercent'],
                "grosssales"  => $row['grosssales'],
                "netsales"    => $row['netsales'],
                "curr"        => $row['curr'],
                "product_id"  => $row['product_id'],
                "item_description" => $row['item_description'],
                "division"    => $row['division'],
                "category"    => $row['category'],
                "brand"       => $row['brand'],
                "sku"         => $row['sku'],
            );
          }
      }

      $this->load->view('gtm/v_sales_invoice_generate',$data);
  }
  //----------------
  
  function npd_tracking(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['gtm/npd_tracking'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('gtm/v_npd_tracking');
    }
  }
  //-----------------------

  function npd_tracking_generate(){
      $date_from  = $_POST['date_from'];
      $date_to    = $_POST['date_to'];

      $temp_from  = explode("-",$date_from);
      $temp_to    = explode("-",$date_to);

      $date_from_van  = $temp_from[2]."/".$temp_from[1]."/".$temp_from[0];
      $date_to_van    = $temp_to[2]."/".$temp_to[1]."/".$temp_to[0];

      $data['date_from_kwsmt']  = $date_from ;
      $data['date_to_kwsmt']    = $date_to ;
      $data['date_from_van']    = $date_from_van;
      $data['date_to_van']      = $date_to_van ;

      $this->load->view('gtm/v_npd_tracking_generate',$data);
  }
  //---
  
  function sales_order(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['gtm/sales_order'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('gtm/v_sales_order',$data);
    }
  }
  //------------------------

  function sales_order_generate(){
      $this->load->model('model_gtm','',TRUE);

      $date_from  = $_POST['date_from'];
      $date_to    = $_POST['date_to'];

      $result = $this->model_gtm->get_sales_order($date_from,$date_to);

      if($result){
          foreach($result as $row){
            $data['v_list_sales_order'][] = array(
                "docnumber"   => $row['docnumber'],
                "docdate"     => $row['docdate'],
                "custcode"    => $row['custcode'],
                "custname"    => $row['custname'],
                "divch"       => $row['divch'],
                "custgroup"   => $row['custgroup'],
                "region"      => $row['region'],
                "qty"         => $row['qty'],
                "netweight"   => $row['netweight'],
                "price"       => $row['price'],
                "priceaftervat" => $row['priceaftervat'],
                "discpercent" => $row['discpercent'],
                "grosssales"  => $row['grosssales'],
                "netsales"    => $row['netsales'],
                "curr"        => $row['curr'],
                "product_id"  => $row['product_id'],
                "item_description" => $row['item_description'],
                "division"    => $row['division'],
                "category"    => $row['category'],
                "brand"       => $row['brand'],
                "sku"         => $row['sku'],
            );
          }
      }

      $this->load->view('gtm/v_sales_order_generate',$data);
  }
  //----------------

}

?>
