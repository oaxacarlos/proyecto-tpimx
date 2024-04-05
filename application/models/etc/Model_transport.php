
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_transport extends CI_Model{

   var $state_code,$transport_route,$customer_id;

 function list_all_crs_state(){
      $db = $this->load->database('custreg', true);
      $query = $db->query("select * from crs_state;");
      return $query->result_array();
    }

	function list_transport_bylga($lgaid){
      $db = $this->load->database('custreg', true);
		$lga = $db->select("lga")->from("crs_lga")->where("lga_id",$lgaid)->get()->row()->lga;
		// var_dump($lga);
		// die();
      $query = $db->query("SELECT transport_zone FROM `crs_transport` WHERE `transport_zone` like '%".$lga."%'");
      return $query->result_array();
    }

  function list_all_transport_zone(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("select * from crs_transport;");
        return $query->result_array();
    }
	
function display_records()
	{
    $db = $this->load->database('custreg', true);
	$query= $db->query("select cus.customer_id,cus.business_name,cus.customer_name,st.state,lg.lga from crs_customers cus INNER join crs_state st on(cus.state_code=st.state_code)
	inner join crs_lga lg on(cus.lga_id=lg.lga_id) where transport_route is null and status_code='CRSST003';");

	 return $query->result_array();
	 
	}
	
	
 function update_transport_route($customer_id,$transport_route){
        $db = $this->load->database('custreg', true);
		$q= $db->query("SELECT transport_route  FROM crs_customers WHERE customer_id='".$customer_id."'");
		// var_dump($q->row()->transport_route);
		// die();
		if($q->row()->transport_route==NULL){
			$query = $db->query("update crs_customers set transport_route='".$transport_route."',status_code='CRSST006' where customer_id='".$customer_id."';");
		   return true;			
		}else{
			return false;
		}

    }
	
}	
	
	
	
	
	?>