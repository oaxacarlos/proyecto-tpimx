<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gt extends CI_Controller{
	
  public $food_si;
  public $nonfood_si;
  public $inv_food;
  public $inv_nonfood;
  public $total_si;
		
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

  function index(){
      $this->load->view('templates/navigation');
  }
  //----------------

  function sir(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['gt/sir'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('gt/v_sir',$data);
    }
  }
  //------------------------
  
  function convert_date($date){
		$temp = explode("-",$date);
		$new_date = $temp[0].$temp[1].$temp[2];
		return $new_date;
 }
 //----
  
  function sir_generate(){
	
	$region 		= $_POST['region'];
	$depot 			= $_POST['depot'];
	$slsno 			= $_POST['slsno'];
	$region_text 	= $_POST['region_text'];
	$depot_text 	= $_POST['depot_text'];
	$slsno_text 	= $_POST['slsno_text'];
	$date 			= $_POST['date'];
	
	$temp_slsno = explode("-",$slsno);
	$slsno1 = $temp_slsno[0];
	$slsno2 = $temp_slsno[1];
	
	$output_string = "";

	$output_string.= $this->print_header($region_text,$depot_text,$slsno_text,$date);
	
	// detail
	$output_string.="<table class='table table-bordered table-sm'>";
	
		// header column
		$output_string.= $this->print_header_column();
		
		// result		
		$sap = $this->config->item('sap300');
        $result = $sap->callFunction("ZFN_SD_WEB_GT_RETAIL_INV",
        array(
            array("IMPORT","I_BEGIN_DATE",$this->convert_date($date)),
            array("IMPORT","I_END_DATE",$this->convert_date($date)),
            array("IMPORT","I_VKGRP1",$slsno1),
            array("IMPORT","I_VKGRP2",$slsno2),
            array("TABLE","PT_RESULT",array()),
        ));
		
		if ($sap->getStatus() == SAPRFC_OK) {
			$kunnr = "";
			$kunnr_name = "";
			$this->intial();
			
			$no = 1;			
			foreach ($result["PT_RESULT"] as $row) {				
				if($kunnr == ""){ 
					$kunnr = $row['KUNRG'];
					$kunnr_name = $row['NAME1'];
				}
				else{
					if($inv_food!='' && $inv_nonfood!=''){
						$output_string.= $this->print_result($no,$kunnr,$kunnr_name,$this->inv_food,$this->inv_nonfood,$this->food_si,$this->nonfood_si);
						$this->intial();
						$no++;
					}
					else if($kunnr != $row['KUNRG']){
						$output_string.= $this->print_result($no,$kunnr,$kunnr_name,$this->inv_food,$this->inv_nonfood,$this->food_si,$this->nonfood_si);
						
						$kunnr = $row['KUNRG'];
						$kunnr_name = $row['NAME1'];
						$this->intial();
						$no++;
					}
				}
				
				if($row['SPART'] == "10"){
					$this->food_si += $row['AMOUNT'];
					$this->inv_food = $row['BILLING_DOC'];
				}
				else if($row['SPART'] == "20"){
					$this->nonfood_si += $row['AMOUNT'];
					$this->inv_nonfood = $row['BILLING_DOC'];
				}
				
				$this->total_si += $row['AMOUNT'];
			}
			$output_string.= $this->print_result($no,$kunnr,$kunnr_name,$this->inv_food,$this->inv_nonfood,$this->food_si,$this->nonfood_si);
			
			$output_string.= $this->print_total($this->total_si);
		}
		
	$output_string.="</table>";
	
	$output_string.= $this->print_footer();
		
	echo $output_string;
  }
  //------------------------
  
  function intial(){
		$this->food_si = 0;
		$this->nonfood_si = 0;
		$this->inv_food = "";
		$this->inv_nonfood = "";
  }
  
  function print_header($region_text,$depot_text,$slsno_text,$date){
	  $output_string="<table class='table table-bordered table-sm'>";
	
		$output_string.="<tr>";
			$output_string.="<td colspan=5><b>DELIVERY & PAYMENT COLLECTION TALLY SHEET</b></td>";
		$output_string.="</tr>";
		
		$output_string.="<tr>";
			$output_string.="<td><b>REGION</td><td><b>".$region_text ."</b></td>";
			$output_string.="<td></td>";
			$output_string.="<td><b>Date of Delivery</b></td>";
			$output_string.="<td><b>".$date."</b></td>";
		$output_string.="</tr>";
		
		$output_string.="<tr>";	
			$output_string.="<td><b>DEPOT</td><td><b>".$depot_text."</b></td>";
			$output_string.="<td></td>";
			$output_string.="<td><b>Delivery By</b></td>";
			$output_string.="<td></td>";
		$output_string.="</tr>";
		
		$output_string.="<tr>";	
			$output_string.="<td><b>Preseller Name</td><td><b>".$slsno_text."</b></td>";
			$output_string.="<td></td>";
			$output_string.="<td><b>Assisted By</b></td>";
			$output_string.="<td></td>";
		$output_string.="</tr>";
	
		$output_string.="<tr><td colspan='5'></td></tr>";	
	$output_string.="</table>";
	
	return $output_string;
  }
  
  //---
  
  function print_footer(){
	  // footer 1
	$output_string="<table class='table table-bordered table-sm'>";
		$output_string.="<tr>";
			$output_string.="<td colspan='6'></td><td colspan='3' class='table-info'><b> Actual Cash Count vs. Payment Received</b></td>";
		$output_string.="</tr>";
	
		$output_string.="<tr>";
			$output_string.="<td colspan='6'>Created By (SAG Name):</td>";
			$output_string.="<td class='table-info'><b>DENOM</b></td>
							<td class='table-info'><b>PIECES</b></td>
							<td class='table-info'><b>TOTAL</b></td>";
		$output_string.="</tr>";
		
		$output_string.="<tr><td colspan='6'></td><td>1000</td><td></td><td></td></tr>";
		
		$output_string.="<tr>";
			$output_string.="<td colspan='6'>Returned Validated by (Depot Staff)</td>";
			$output_string.="<td>500</td><td></td><td></td>";
		$output_string.="</tr>";
		
		$output_string.="<tr><td colspan='6'></td><td>200</td><td></td><td></td></tr>";
		
		$output_string.="<tr>";
			$output_string.="<td colspan='6'>Cash Received by (Cashier Name):</td>";
			$output_string.="<td>100</td><td></td><td></td>";
		$output_string.="</tr>";
		
		$output_string.="<tr><td colspan='6'></td><td>50</td><td></td><td></td></tr>";
		$output_string.="<tr><td colspan='6'></td><td>20</td><td></td><td></td></tr>";
		$output_string.="<tr><td colspan='6'></td><td>10</td><td></td><td></td></tr>";
		$output_string.="<tr><td colspan='6'></td><td>5</td><td></td><td></td></tr>";
		
		$output_string.="<tr><td colspan='6'></td><td><b>TOTAL</b></td><td></td><td></td></tr>";
		
		$output_string.="<tr><td colspan='6'></td><td> Short / Over Remittance<br>(Payment Received less<br>Actual Remittance)</td><td></td><td></td></tr>";
		
	$output_string.="</table>";
	
	 return $output_string;
  }

  //---
  
  function print_header_column(){
	$output_string="<tr class='table-info'>";
		$output_string.="<td><b>S/N</b></td>";
		$output_string.="<td><b>Customer Name</b></td>";
		$output_string.="<td><b>Customer Code</b></td>";
		$output_string.="<td><b>Sales Invoice No. - NON FOOD</b></td>";
		$output_string.="<td><b>Sales Invoice No. - FOOD</b></td>";
		$output_string.="<td><b>Sales Invoice Amt.</b></td>";
		$output_string.="<td><b>Payment Received (To be filled out by the Delivery Person)</b></td>";
		$output_string.="<td><b>Amount of Stocks Returned (To be filled out by the Delivery Person)</b></td>";
		$output_string.="<td><b>Amount of Stocks Returned (To be filled out by the Depot)</b></td>";
	$output_string.="</tr>";
	
	return $output_string;
  }
  
  //---
  
  function print_result($no,$kunnr,$kunnr_name,$inv_no_food,$inv_no_nonfood,$si_food,$si_nonfood){
	  $output_string="";
	  
	  $output_string.="<tr>";
		$output_string.="<td>".$no."</td>";
		$output_string.="<td>".$kunnr_name."</td>";
		$output_string.="<td>".ltrim($kunnr,'0')."</td>";
		$output_string.="<td>".$inv_no_nonfood."</td>";
		$output_string.="<td>".$inv_no_food."</td>";
		
		$total = $si_nonfood + $si_food;
		$output_string.="<td>".number_format($total)."</td>";
		
		$output_string.="<td></td>";
		$output_string.="<td></td>";
		$output_string.="<td></td>";
	  $output_string.="</tr>";
	  
	  return $output_string;
  }
  
  //---
  
  function print_total($total){
	  $output_string="";
	  
	  $output_string.="<tr class='table-success'>";
		$output_string.="<td></td>";
		$output_string.="<td></td>";
		$output_string.="<td></td>";
		$output_string.="<td></td>";
		$output_string.="<td><b>TOTAL SALES</b></td>";

		$output_string.="<td><b>".number_format($total)."</b></td>";
		
		$output_string.="<td></td>";
		$output_string.="<td></td>";
		$output_string.="<td></td>";
	  $output_string.="</tr>";
	  
	  return $output_string;
  }
}

?>
