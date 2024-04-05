<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
    class Model_export extends CI_Model {
 
    	
		 function exportList(){
      $db = $this->load->database('custreg', true);
	
      $query = $db->query("select customer_rc_number,business_name,substring(customer_name,1,5)AS search_1,email,telephone_1,mobile_phone,address,cg_code,contact_person_firstname,contact_person_lastname,contact_person_phone,contact_person_email,creditNFood,creditFood,channel_code,division_code,incoterm_code,payment_term_code,sales_district_code,sales_group_code,
	  sales_office_code,kw_market,tax_code,state_code,lga_id,plants_code,transport_route from crs_customers Order By customer_id  limit 5");
      return $query->result_array();
    }  
		
    }
?>