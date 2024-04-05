<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itr_material extends CI_Controller{

      function getDailyReport($sap, $pi_date_from, $pi_date_to, $pi_cus, $pi_part, $pi_trg, $pi_sls, $pi_prod, $pi_rate, $pi_se, $pi_order,$pi_div ){
		  
		$result=$sap->callFunction("ZFN_ZBIO001",
					array(	
						array("IMPORT","PI_DATE_FROM",$pi_date_from),
						array("IMPORT","PI_DATE_TO",$pi_date_to),
						array("IMPORT","PI_CUS",$pi_cus),
						array("IMPORT","PI_PART",$pi_part),
						array("IMPORT","PI_TRG",$pi_trg),
						array("IMPORT","PI_SLS",$pi_sls),
						array("IMPORT","PI_PROD",$pi_prod),
						array("IMPORT","PI_RATE",$pi_rate),
						array("IMPORT","PI_SE",$pi_se),
						array("IMPORT","PI_DIV",$pi_div),
						array("IMPORT","PI_ORDER",$pi_order),
						array("TABLE","PT_CUS",array()),
						array("TABLE","PT_PART",array()),
						array("TABLE","PT_TRG",array()),
						array("TABLE","PT_SLS",array()),
						array("TABLE","PT_PROD",array()),
						array("TABLE","PT_RATE",array()),
						array("TABLE","PT_SE",array()),
						array("TABLE","PT_ORDER",array())
				)
			);
		return $result;
	}
	//----
		
	function delete_material($active){
		$this->load->model('model_itr','',TRUE);
		$result = $this->model_itr->delete_material($active);
	}
	//---
	
	function insert_material($result){
		$this->load->model('model_itr','',TRUE);
		
		foreach ($result["PT_PROD"] as $row) {
			if($row['MTYPE'] != 'ZECO'){
				$mat_id 	= $row['PRODUCTKEY'];
				$mat_desc 	= $row['ITEM_DESCRIPTION'];
				$mat_type 	= $row['MTYPE'];
				
				if($row['UOM'] == "ST") $uom = 'PC'; 
				else if($row['UOM'] == "PAK") $uom = 'PAC'; 
				else $uom = $row['UOM'];
				
				$active 	= 'Y';
				$result1 	= $this->model_itr->insert_material_to_db($mat_id,$mat_desc,$mat_type,$uom,$active);
			}
		}
	}
	
	//---
	
	function get_product(){
		$date_from 	= date('Y-m-d');
		$date_to 	= date('Y-m-d');
		
		// delete active Yes
		$this->delete_material('Y');
		
		$sap = $this->config->item('sap300');  
		
		// insert general
		unset($result);
		$result = $this->getDailyReport($sap, $date_from, $date_to, '', '', '', '', 'X', '', '' ,'','');
		$this->insert_material($result);
		
		
		// insert nonfood
		unset($result);
		$result = $this->getDailyReport($sap, $date_from, $date_to, '', '', '', '', 'X', '', '' ,'','N');
		$this->insert_material($result);
		
		// insert food
		unset($result);
		$result = $this->getDailyReport($sap, $date_from, $date_to, '', '', '', '', 'X', '', '' ,'','F');
		$this->insert_material($result);
	}
}


?>
