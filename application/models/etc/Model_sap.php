<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_sap extends CI_Model{

   var $state_code,$salesoffice,$customer_id,$business_name,$customer_rc_number,$customer_name,$email,$telephone_1,$telephone2,$mobile_phone;
   var $address,$contact_person_firstname,$relative_name,$contact_person_title,$contact_person_lastname,$contact_person_phone,$contact_person_email,$contact_person_gender,$customer_sap_code;
   var $credit,$created_at,$created_time,$created_user,$status_code,$canceled_flag,$canceled_date_time,$contact_person_address,$canceled_by,$channel_code,$cg_code;
   var $division_code,$incoterm_code,$account_group_code,$payment_term_code,$sales_district_code,$sales_group_code,$sales_office_code,$tax_code,$country_code;
   var $statecode,$lga_id,$market_code,$transport_route,$plants_code,$crs_approval_code_user,$crs_h_code,$crs_approval_code,$approval_date,$approval_datetime;
	var $crs_approval_text1,$email_user,$user_id,$crs_code,$relative_phone,$region;
	var $kw_business_address, $kw_companyname,$kw_market,$kw_infrastructure,$kw_connections,$kw_doc,$kw_brother_occupation,$kw_father_occupation,$kw_brother_name,$kw_father_name,$kw_sales_order,$kw_remarks,$contact_person,
$kw_businessnature,$kw_products,$kw_years,$kw_sales_turn_over,$kw_area_to_cover,$kw_start_date,$kw_sales_executive_name,$kw_reason,$kw_attachment;
	     
    function list_all_crs_dist_channel(){
      $db = $this->load->database('custreg', true);
      $query = $db->query("select * from crs_dist_channel;");
      return $query->result_array();
    }
    //--------------------

   function list_all_crs_cust_group(){
      $db = $this->load->database('custreg', true);
      $query = $db->query("select * from crs_cust_group;");
      return $query->result_array();
    }
    //--------------------

  function list_all_crs_incoterms(){
      $db = $this->load->database('custreg', true);
      $query = $db->query("select * from crs_incoterms;");
      return $query->result_array();
    }
    //--------------------
 
   function list_all_crs_division(){
      $db = $this->load->database('custreg', true);
      $query = $db->query("select * from crs_division;");
      return $query->result_array();
    }
    //---------
	
	  function list_all_crs_paymentterm(){
      $db = $this->load->database('custreg', true);
      $query = $db->query("select * from crs_payment_term;");
      return $query->result_array();
    }
    //---------

  function list_all_crs_state(){
      $db = $this->load->database('custreg', true);
      $query = $db->query("select * from crs_state;");
      return $query->result_array();
    }
    //-
	
	
   function list_all_crs_lga(){
      $db = $this->load->database('custreg', true);
      $query = $db->query("select * from crs_lga;");
      return $query->result_array();
    }
	//-------------
	
	function list_crs_lga_bystate($state_code){
      $db = $this->load->database('custreg', true);
      $query = $db->query("select * from crs_lga where state_code like '".$state_code."%'");
      return $query->result_array();
    }
    //--------------------
	

      function list_all_sales_district(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("select * from crs_sales_district;");
        return $query->result_array();
    }
	
	//---------------
	
	   function list_all_sales_office(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("select * from crs_sales_office;");
        return $query->result_array();
    }
	
	   function list_all_plants(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("select * from crs_plants;");
        return $query->result_array();
    }
	
	   function list_all_sales_group(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("select * from crs_sales_group;");
        return $query->result_array();
    }
	
	   function list_all_tax(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("select * from crs_tax;");
        return $query->result_array();
    }
	
	
	 function list_sales_group_byofficedivision($salesoffice,$division_code){
      $db = $this->load->database('custreg', true);
	  
      $query = $db->query("select * from crs_sales_group where division_code ='".$division_code."' and
                    sales_office_code = '".$salesoffice."'");
      return $query->result_array();
    }
    //--------------------
		
	function customer_add($post){
    //echo "<pre>"; print_r($post);exit;
    $db1 = $this->load->database('custreg', true);
		date_default_timezone_set("Africa/Lagos");
		$now=date("h:i:sa");
		$nowd=date("Y-m-d");
		  $session_data = $this->session->userdata('z_tpimx_logged_in');
			 $user_id = $session_data['z_tpimx_user_id'];
		$id = file_get_contents('file.txt');
		$customer_id   =  "D". str_pad($id, 4, 0, STR_PAD_LEFT);
		
    $kw_companyname = implode(",", $post['kw_companyname']);
    $kw_businessnature = implode(",", $post['kw_businessnature']);
    $kw_products = implode(",", $post['kw_products']);
    $kw_years = implode(",", $post['kw_years']);
    $kw_sales_turn_over = implode(",", $post['kw_sales_turn_over']);
    $kw_attachment = implode(",", $post['files']);

		$data = array();
    $data['customer_id']= $customer_id;
	  $data['customer_rc_number']= $post['customer_rc_number'];
	   $data['business_name']= $post['business_name'];
    $data['customer_name']= $post['customer_name'];
    $data['email']= $post['email'];
    $data['telephone_1']= $post['telephone_1'];
    $data['telephone_2']= $post['telephone_2'];
    $data['mobile_phone']= $post['mobile_phone'];
    $data['address']= $post['address'];
    $data['contact_person_lastname']= $post['contact_person_lastname'];
	$data['contact_person_firstname']= $post['contact_person_firstname'];
    $data['relative_name']= $post['relative_name'];
	  $data['relative_phone']= $post['relative_phone'];
    $data['contact_person_title']= $post['contact_person_title'];
    $data['contact_person_phone']= $post['contact_person_phone'];
    $data['contact_person_email']= $post['contact_person_email'];
    $data['contact_person_gender']= $post['contact_person_gender'];
   // $data['contact_person_position']= $post['contact_person_position'];
    $data['customer_sap_code']= $post['customer_sap_code'];
    $data['created_at']= $nowd;
	  $data['created_time']= $now;
	  $data['created_user']= $user_id;
	  $data['status_code']= 'CRSST001';
    $data['canceled_flag']= $post['canceled_flag'];
	  $data['canceled_date_time']= $post['canceled_date_time'];
    $data['contact_person_address']= $post['contact_person_address'];
    $data['canceled_by']= $post['canceled_by'];
    $data['channel_code']= $post['crs_dist_channel'];
	  $data['cg_code']= $post['crs_cg'];
	  $data['division_code']= $post['crs_division']; 
    $data['incoterm_code']= $post['crs_incoterms'];
    $data['account_group_code']= $post['account_group_code'];
	  $data['sales_district_code']= $post['crs_sales_district']; 
	  $data['sales_group_code']= $post['crs_sales_group']; 
	  $data['sales_office_code']= $post['crs_sales_office'];
	  $data['tax_code']= $post['crs_tax'];
	  $data['country_code']= $post['country_code'];
	  $data['state_code']= $post['crs_state'];
	  $data['lga_id']= $post['crs_lga'];
	   $data['region']= $post['region'];
	  $data['market_code']= $post['market_code'];
	  $data['transport_route']= $post['transport_route'];
	  $data['plants_code']= $post['crs_plants'];
	  $data['kw_business_address']= $post['kw_business_address'];
    $data['kw_companyname']= $kw_companyname;
    $data['kw_businessnature']= $kw_businessnature;
    $data['kw_products']= $kw_products;
    $data['kw_years']= $kw_years;
    $data['kw_sales_turn_over']= $kw_sales_turn_over;
    $data['kw_area_to_cover']= $post['kw_area_to_cover'];
	  $data['kw_infrastructure']=$post['kw_infrastructure']; 
		$data['kw_connections']=$post['kw_connections']; 
		$data['kw_doc']=$post['kw_doc']; 
		$data['kw_father_name']=$post['kw_father_name'];
		$data['kw_father_occupation']=$post['kw_father_occupation'];
		$data['kw_brother_name']=$post['kw_brother_name'];
		$data['kw_brother_occupation']=$post['kw_brother_occupation'];
		$data['kw_remarks']= $post['kw_remarks'];
		$data['kw_sales_order']=$post['kw_sales_order'];
		$data['kw_market']= $post['kw_market'];
	  $data['kw_start_date']= $post['kw_start_date'];
	  $data['kw_attachment']= $post['kw_attachment'];
	  $data['kw_reason'] = $post['kw_reason'];
	   $data['contact_person'] = $post['contact_person'];
    $data['kw_attachment'] = $kw_attachment;

    $result = $db1->insert('crs_customers', $data);
		//echo $db1->last_query(); die;
		  // $id = $this->db->insert_id();
		if($result) {
			$id++;
			file_put_contents('file.txt', $id);
			echo $customer_id;
			return $customer_id."#".$post['business_name'];
		}else{
			return false;
		}
  }

  // function customer_add(){
    
  //   $db1 = $this->load->database('custreg', true);
  //   date_default_timezone_set("Africa/Lagos");
  //   $now=date("h:i:sa");
  //   $nowd=date("Y-m-d");
  //   $id = file_get_contents('file.txt');
  //   $customer_id   =  "D". str_pad($id, 4, 0, STR_PAD_LEFT);
      
  //   $data = array(
  //        'customer_id' => $customer_id,
  //    'customer_rc_number' => $this->customer_rc_number,
  //         'customer_name' => $this->customer_name,
  //         'email' => $this->email,
  //         'telephone_1' => $this->telephone_1,
  //         'telephone_2' => $this->telephone_2,
  //         'mobile_phone' => $this->mobile_phone,
  //         'address' => $this->address,
  //         'contact_person_firstname' => $this->contact_person_firstname,
  //         'contact_person_lastname' => $this->contact_person_lastname,
  //         'contact_person_title' => $this->contact_person_title,
  //         'contact_person_phone' => $this->contact_person_phone,
  //         'contact_person_email' => $this->contact_person_email,
  //         'contact_person_gender' => $this->contact_person_gender,
  //         'contact_person_position' => $this->contact_person_position,
  //         'customer_sap_code' => $this->customer_sap_code,
  //     //'credit' => $this->credit,
  //         'created_at' => $nowd,
  //     'created_time' => $now,
  //    // 'created_user' => $this->created_user,
  //      'status_code' => $this->status_code,
  //         'canceled_flag' => $this->canceled_flag,
  //     'canceled_date_time' => $this->canceled_date_time,
  //         'contact_person_address' => $this->contact_person_address,
  //         'canceled_by' => $this->canceled_by,
  //         'channel_code' => $this->channel_code,
  //     'cg_code' => $this->cg_code,
  //     'division_code' => $this->division_code, 
  //         'incoterm_code' => $this->incoterm_code,
  //         'account_group_code' => $this->account_group_code,
  //         //'payment_term_code' => $this->payment_term_code,
  //      'sales_district_code' => $this->sales_district_code, 
  //     'sales_group_code' => $this->sales_group_code, 
  //     'sales_office_code' => $this->sales_office_code,
  //     'tax_code' => $this->tax_code,
  //     'country_code' => $this->country_code,
  //     'state_code' => $this->statecode,
  //     'lga_id' => $this->lga_id,
  //     'market_code' => $this->market_code,
  //     'transport_route' => $this->transport_route,
  //     'plants_code' => $this->plants_code,
  //     'kw_business_address' => $this->kw_business_address,
  //         'kw_companyname' => $this->kw_companyname,
  //         'kw_businessnature' => $this->kw_businessnature,
  //         'kw_products' => $this->kw_products,
  //         'kw_years' => $this->kw_years,
  //         'kw_sales_turn_over' => $this->kw_sales_turn_over,
  //         'kw_area_to_cover' => $this->kw_area_to_cover,
  //     'kw_infrastructure' =>$this->kw_infrastructure, 
  //     'kw_connections' =>$this->kw_connections, 
  //     'kw_father_name' =>$this->kw_father_name,
  //     'kw_father_occupation' =>$this->kw_father_occupation,
  //     'kw_brother_name' =>$this->kw_brother_name,
  //     'kw_brother_occupation' =>$this->kw_brother_occupation,
  //     'kw_market' => $this->kw_market,
  //     'kw_sales_executive_name' => $this->kw_sales_executive_name,
  //    'kw_attachment' => $this->kw_attachment,
  //      'kw_reason' => $this->kw_reason
  //         );


  //         $result = $db1->insert('crs_customers', $data);
  //   //echo $db1->last_query(); die;
  //     // $id = $this->db->insert_id();
  //      if($result) {
  //     $id++;
  //       file_put_contents('file.txt', $id);
  //        echo $customer_id;

  //      }else{
  //        return false;
  //      }
  // }

function list_approval($user_id){

        $db = $this->load->database('custreg', true);
		$query = $db->query("select zone from crs_approval_email where user_id ='".$user_id."'");
		if($query->num_rows() > 0){
		$region = $query->row()->zone;
		$q2= $db->query("SELECT cus.customer_id,cus.business_name,cus.customer_name,cus.region,st.status_name,cus.created_time,cus.created_at FROM crs_customers cus
               inner join crs_mster_status st on(st.status_code=cus.status_code)
			   where st.status_code='CRSST001' and region='".$region."'
              order by created_time;");
			   return $q2->result_array();
		}
	}

	
	function list_approval2(){

        $db = $this->load->database('custreg', true);
        $query = $db->query
		("SELECT  cus.customer_id,cus.business_name,cus.customer_name,st.status_name,cus.created_time,cus.created_at 
		FROM crs_customers cus
               inner join crs_mster_status st on(st.status_code=cus.status_code)where st.status_code='CRSST005'
              order by created_time;");

        return $query->result_array();
    }
   
    function get_approval_level1(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_approval where crs_approval_code='CRSAP001';")->row();
        return $query->crs_approval_code;
    }
	
	 	   function get_approval_level2(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_approval where crs_approval_code='CRSAP002';")->row();
        return $query->crs_approval_code;
    }
    	   function get_approval_level3(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_approval where crs_approval_code='CRSAP003';")->row();
        return $query->crs_approval_code;
    }
	
		   function get_status_from_approval_code(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_mster_status where status_code='CRSST002';")->row();
        return $query->status_code;
    }
	
		   function get_status_from_approval2_code(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_mster_status where status_code='CRSST003';")->row();
        return $query->status_code;
    }
	
		   function get_status_from_salesadmin(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_mster_status where status_code='CRSST005';")->row();
        return $query->status_code;
    }
	
		   function get_reject_status(){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_mster_status where status_code='CRSST004';")->row();
        return $query->status_code;
    }

	

	 function update_status_crs_to_approval($crs_code){
        $db = $this->load->database('custreg', true);
        $query = $db->query("update crs_customers set status_code='CRSST002'
                      where customer_id='".$crs_code."';");
					  // var_dump($query);
					  // die();
        return true;
    }
	
	 function update_status_crs_to_transport($customer_id){
        $db = $this->load->database('custreg', true);
        $query = $db->query("update crs_customers set status_code='CRSST006'
                      where customer_id='".$crs_code."';");
					  // var_dump($query);
					  // die();
        return true;
    }
	
		 function update_status_crs_to_approval2($crs_code){
        $db = $this->load->database('custreg', true);
        $query = $db->query("update crs_customers set status_code='CRSST003'
                      where customer_id='".$crs_code."';");
					  // var_dump($query);
					  // die();
        return true;
    }

		 function update_status_to_superapprov($customer_id){
        $db = $this->load->database('custreg', true);
        $query = $db->query("update crs_customers set status_code='CRSST003'
                      where customer_id='".$crs_code."';");
					  // var_dump($query);
					  // die();
        return true;
    }
	
	 function update_status_crs_to_reject($crs_code){
        $db = $this->load->database('custreg', true);
        $query = $db->query("update crs_customers set status_code='CRSST004'
                      where customer_id='".$crs_code."';");
					  // var_dump($query);
					  // die();
        return true;
    }
	
		
	function list_approval_by_crs_code($customer_id){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_customers cus
		           inner join crs_division d on(cus.division_code=d.division_code)
                inner join crs_dist_channel ch on(cus.channel_code=ch.channel_code)
				 inner join crs_cust_group cg on(cus.cg_code=cg.cg_code)
                inner join crs_lga lg on(cus.lga_id =lg.lga_id)
                inner join crs_state s on(cus.state_code=s.state_code)
				inner join crs_tax tx on(cus.tax_code =tx.tax_code)
                inner join crs_mster_status st on(cus.status_code=st.status_code)
                 inner join crs_sales_district dis on(cus.sales_district_code=dis.sales_district_code) 
				 inner join crs_sales_group sg on(cus.sales_group_code = sg.sales_grp_code) 
                  inner join crs_sales_office off on(cus.sales_office_code=off.sales_office_code)
                       inner join crs_incoterms tm on(cus.incoterm_code=tm.incoterm_code)						
               where st.status_code='CRSST001' and cus.customer_id='".$customer_id."' order by created_time;");
                  //and ih.canceled!='X'
        return $query->result_array();
    }
   
   function list_approval_by_crs_code2($customer_id){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_customers cus
		           inner join crs_division d on(cus.division_code=d.division_code)
                inner join crs_dist_channel ch on(cus.channel_code=ch.channel_code)
				 inner join crs_cust_group cg on(cus.cg_code=cg.cg_code)
                inner join crs_lga lg on(cus.lga_id =lg.lga_id)
                inner join crs_state s on(cus.state_code=s.state_code)
				inner join crs_tax tx on(cus.tax_code =tx.tax_code)
                inner join crs_mster_status st on(cus.status_code=st.status_code)
                 inner join crs_sales_district dis on(cus.sales_district_code=dis.sales_district_code) 
				 inner join crs_sales_group sg on(cus.sales_group_code = sg.sales_grp_code) 
                  inner join crs_sales_office off on(cus.sales_office_code=off.sales_office_code)
                       inner join crs_incoterms tm on(cus.incoterm_code=tm.incoterm_code)						
               where st.status_code='CRSST005' and cus.customer_id='".$customer_id."' order by created_time;");
                  //and ih.canceled!='X'
        return $query->result_array();
    }
   
   
   function list_custedit($customer_id){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_customers cus
		           inner join crs_division d on(cus.division_code=d.division_code)
                inner join crs_dist_channel ch on(cus.channel_code=ch.channel_code)
				 inner join crs_cust_group cg on(cus.cg_code=cg.cg_code)
                inner join crs_lga lg on(cus.lga_id =lg.lga_id)
                inner join crs_state s on(cus.state_code=s.state_code)
				inner join crs_tax tx on(cus.tax_code =tx.tax_code)
                inner join crs_mster_status st on(cus.status_code=st.status_code)
                 inner join crs_sales_district dis on(cus.sales_district_code=dis.sales_district_code) 
				 inner join crs_sales_group sg on(cus.sales_group_code = sg.sales_grp_code) 
                  inner join crs_sales_office off on(cus.sales_office_code=off.sales_office_code)
                       inner join crs_incoterms tm on(cus.incoterm_code=tm.incoterm_code)						
               where st.status_code='CRSST004' and cus.customer_id='".$customer_id."' order by created_time;");
                  //and ih.canceled!='X'
        return $query->result_array();
    }
	

	///IT Input SAP
	function display_custrecords()
	{
    $db = $this->load->database('custreg', true);
	$query= $db->query("select cus.customer_id,cus.business_name,cus.customer_name,cus.created_at,cus.created_time from crs_customers cus where status_code='CRSST003' and customer_sap_code is null;");

	 return $query->result_array();
	 
	}
	
	function display_custrecordslist()
	{
    $db = $this->load->database('custreg', true);
	$query= $db->query("select cus.customer_id,cus.business_name,cus.customer_name,cus.created_at,cus.created_time from crs_customers cus where status_code='CRSST002' and creditNFood is null and creditFood is null and payment_term_code is null;");

	 return $query->result_array();
	 
	}
	
	function display_editrecords()
	{
    $db = $this->load->database('custreg', true);
	$query= $db->query("select cus.customer_id,cus.business_name,cus.customer_name,cus.created_at,cus.created_time from crs_customers cus where status_code='CRSST004';");

	 return $query->result_array();
	 
	}
	
	
	function display_allcustomer($customer_id)
	{
    $db = $this->load->database('custreg', true);
	$query= $db->query("SELECT * FROM crs_customers cus
		           inner join crs_division d on(cus.division_code=d.division_code)
                inner join crs_dist_channel ch on(cus.channel_code=ch.channel_code)
				 inner join crs_cust_group cg on(cus.cg_code=cg.cg_code)
                inner join crs_lga lg on(cus.lga_id =lg.lga_id)
                inner join crs_state s on(cus.state_code=s.state_code)
				inner join crs_tax tx on(cus.tax_code =tx.tax_code)
                inner join crs_mster_status st on(cus.status_code=st.status_code)
                 inner join crs_sales_district dis on(cus.sales_district_code=dis.sales_district_code) 
				 inner join crs_sales_group sg on(cus.sales_group_code = sg.sales_grp_code) 
                  inner join crs_sales_office off on(cus.sales_office_code=off.sales_office_code)
                    
                       inner join crs_incoterms tm on(cus.incoterm_code=tm.incoterm_code)						
               where st.status_code='CRSST002' and cus.customer_id='".$customer_id."'");

	 return $query->result_array();
	 
	}
	
	
 function update_sap_code($customer_id,$customer_sap_code){
        $db = $this->load->database('custreg', true);
		$q= $db->query("SELECT customer_sap_code  FROM crs_customers WHERE customer_sap_code is null and customer_id='".$customer_id."'");
		// var_dump($q->row()->transport_route);
		// die();
		if($q->row()->customer_sap_code==NULL){
			$query = $db->query("update crs_customers set customer_sap_code='".$customer_sap_code."',status_code='CRSST007' where customer_id='".$customer_id."';");
			
		   return true;			
		}else{
			return false;
		}

    }
	
	
	function insertsalesadmin($customer_id,$crs_approval_code,$crs_status,$remarks,$approval_date,$approval_by_user){
         $db = $this->load->database('custreg', true);
			$data = array(
					'customer_id' => $customer_id,
				  'crs_approval_code' => $crs_approval_code,
				  'status_code' => $crs_status,
					  'crs_approval_text1' => $remarks,
					   'approval_by_user' => $approval_by_user,
					   'approval_date' => $approval_date,
					 
					   );
					   $result = $db->insert('crs_h_approval', $data);
			 
				   if($result) {
				  return true;

				   }else{
					return false;
				   }
			}
	
	
	 function update_credit_limit($customer_id,$payment,$creditF,$creditN){
        $db = $this->load->database('custreg', true);
		$q= $db->query("SELECT payment_term_code,creditFood,creditNFood FROM crs_customers WHERE customer_id='".$customer_id."'");

 // $db->save_queries = TRUE;
// var_dump($db->last_query());
		 // var_dump($creditF);
		  // die();		 

		if($q->row()->payment_term_code==NULL){
			 $query = $db->query("UPDATE crs_customers set payment_term_code='".$payment."' where customer_id='".$customer_id."';");		
		 }

		if($q->row()->creditFood==NULL){
		    $query = $db->query("UPDATE crs_customers set creditFood='".$creditF."' where customer_id='".$customer_id."';");
		}
		
		if($q->row()->creditNFood==NULL){
		   $query = $db->query("UPDATE crs_customers set creditNFood='".$creditN."' where customer_id='".$customer_id."';");
		}
		$query = $db->query("UPDATE crs_customers set status_code='CRSST005' where customer_id='".$customer_id."';");
   return true;	
		
    }
	
	
	
	function updateform($crs_customername,$crs_businessname,$crs_customerid,$customer_rc_number,$crs_dist_channel,$crs_cg,$cdate,$ctime,$crs_incoterms,$crs_division,$crs_tax,$telephone_1,$mobile_phone,$email,$contact_person_title,$contact_person_gender,
		$contact_person_firstname,$contact_person_lastname,$relative_name,$relative_phone,$contact_person_phone,$contact_person_email,$kw_business_address,
		$address,$contact_person_address,$sales_district_code,$sales_group_code,$kw_area_to_cover,$kw_father_name,$kw_father_occupation,
		$kw_brother_name,$kw_brother_occupation,$kw_infrastructure,	$kw_connections,$kw_sales_turn_over,$kw_years,$kw_products,
		$kw_businessnature,$kw_reason,$kw_companyname,$kw_start_date,$kw_sales_order,$kw_remarks,$state_code,$lga_id,$kw_market,$plants_code, $kw_attachment){
	
		
	$db = $this->load->database('custreg', true);
	
	           $nowd=date("Y-m-d");
			$now=date("h:i:sa");
	          $value = array(
		
          'customer_id'              => $crs_customerid,
          'customer_name'              => $crs_customername,
          'customer_rc_number'           => $customer_rc_number,
          'channel_code'    => $crs_dist_channel,
		   'cg_code'              => $crs_cg,
          'incoterm_code'              => $crs_incoterms,
          'division_code'              => $crs_division,
          'tax_code'           => $crs_tax,
          'telephone_1'    => $telephone_1,
		  'mobile_phone'              => $mobile_phone,
          'email'              => $email,
          'contact_person_title'           => $contact_person_title,
          'contact_person_gender'    => $contact_person_gender,
		  'kw_business_address'              => $kw_business_address,
          'address'              => $address,
          'contact_person_address'           => $contact_person_address,
          'sales_district_code'    => $sales_district_code,
		  'sales_group_code'    => $sales_group_code,
          'kw_area_to_cover'              => $kw_area_to_cover,
          'kw_father_name'              => $kw_father_name,
          'kw_father_occupation'           => $kw_father_occupation,
          'kw_brother_name'    => $kw_brother_name,
		  'kw_brother_occupation'              => $kw_brother_occupation,
          'kw_infrastructure'              => $kw_infrastructure,
          'kw_connections'           => $kw_connections,
          'kw_sales_turn_over'    => $kw_sales_turn_over,
		  'kw_years'              => $kw_years,
          'kw_products'              => $kw_products,
          'kw_businessnature'           => $kw_businessnature,
          'kw_reason'    => $kw_reason,
		   'kw_companyname'              => $kw_companyname,
          'kw_start_date'              => $kw_start_date,
          'kw_sales_order'           => $kw_sales_order,
          'kw_remarks'    => $kw_remarks,
		  'state_code'              => $state_code,
          'lga_id'              => $lga_id,
          'kw_market'           => $kw_market,
          'plants_code'    => $plants_code,
          );
		$q= $db->query("UPDATE crs_customers set business_name='".$crs_businessname."', customer_rc_number ='".$customer_rc_number."',customer_name='".$crs_customername."',email ='".$email."',telephone_1 ='".$telephone_1."',mobile_phone ='".$mobile_phone."',
		address ='".$address."',kw_business_address ='".$kw_business_address."',cg_code ='".$crs_cg."',
	contact_person_firstname ='".$contact_person_firstname."',contact_person_lastname ='".$contact_person_lastname."',relative_name ='".$relative_name."',contact_person_title ='".$contact_person_title."',relative_phone ='".$relative_phone."',contact_person_phone ='".$contact_person_phone."',
		contact_person_email ='".$contact_person_email."',contact_person_gender ='".$contact_person_gender."',
		created_at='".$nowd."',created_time= '".$now."',contact_person_address ='".$contact_person_address."',
		channel_code ='".$crs_dist_channel."',division_code ='".$crs_division."',incoterm_code ='".$crs_incoterms."',
		sales_district_code ='".$sales_district_code."',sales_group_code ='".$sales_group_code."',
		kw_area_to_cover ='".$kw_area_to_cover."',kw_father_name ='".$kw_father_name."',kw_father_occupation ='".$kw_father_occupation."',kw_brother_name ='".$kw_brother_name."',
		kw_infrastructure ='".$kw_infrastructure."',kw_connections ='".$kw_connections."',kw_sales_turn_over ='".$kw_sales_turn_over."',kw_years ='".$kw_years."',
		kw_products ='".$kw_products."',kw_businessnature ='".$kw_businessnature."',kw_reason ='".$kw_reason."',
		kw_companyname ='".$kw_companyname."',kw_start_date ='".$kw_start_date."',kw_sales_order ='".$kw_sales_order."',kw_remarks ='".$kw_remarks."',tax_code ='".$crs_tax."',state_code ='".$state_code."',
		lga_id ='".$lga_id."',kw_market ='".$kw_market."',plants_code ='".$plants_code."',status_code='CRSST001'
		
		where customer_id='".$crs_customerid."';");
		
		return true;
	}
	

	/*
		function crs_approval($crs_code,$remarks,$crs_status,$user_id){
        $db1 = $this->load->database('custreg', true);

        $data = array(
          'customer_id'              => $crs_code,
          'status_code'              => $crs_status,
          'approval_by_user'           => $user_id,
          'crs_approval_text1'    => $remarks,
        
          );
        // var_dump($data);
		 // die;
          $result = $db1->insert('crs_transaction_status_code',$data);

          if($result) return true;
          else return false;
    }
    //----------------
*/

function crs_approval($crs_code,$remarks,$crs_status,$user_id,$crs_approval_code){
        $db1 = $this->load->database('custreg', true);
		date_default_timezone_set("Africa/Lagos");
		$now=date("h:i:sa");
		$nowda=date("Y-m-d");

        $data = array(
          'customer_id'              => $crs_code,
          'status_code'              => $crs_status,
		 'crs_approval_code'  => $crs_approval_code,
          'approval_by_user'           => $user_id,
          'crs_approval_text1'    => $remarks,
		   'approval_datetime'    => $now,
		     'approval_date'    => $nowda,
        
          );
        // var_dump($data);
		 // die;
          $result = $db1->insert('crs_h_approval',$data);

          if($result) return true;
          else return false;
    }	
/*	
		function crs_approval2($crs_code,$remarks,$crs_status,$user_id){
        $db1 = $this->load->database('custreg', true);
          date_default_timezone_set("Africa/Lagos");
		$now=date("h:i:sa");
		$nowda=date("Y-m-d");
        $data = array(
          'customer_id'              => $crs_code,
          'status_code'              => $crs_status,
          'approval_by_user'           => $user_id,
          'crs_approval_text1'    => $remarks,
         'approval_datetime'    => $now,
		     'approval_date'    => $nowda,
        
          );
        // var_dump($data);
		 // die;
          $result = $db1->insert('crs_transaction_status_code_level2',$data);

          if($result) return true;
          else return false;
    }
    //----------------
*/



function crs_approval2($crs_code,$remarks,$crs_status,$user_id,$crs_approval_code){
        $db1 = $this->load->database('custreg', true);
          date_default_timezone_set("Africa/Lagos");
		$now=date("h:i:sa");
		$nowda=date("Y-m-d");
        $data = array(
          'customer_id'              => $crs_code,
          'status_code'              => $crs_status,
		  'crs_approval_code'  => $crs_approval_code,
          'approval_by_user'           => $user_id,
          'crs_approval_text1'    => $remarks,
         'approval_datetime'    => $now,
		     'approval_date'    => $nowda,
        
          );
        // var_dump($data);
		 // die;
          $result = $db1->insert('crs_h_approval',$data);

          if($result) return true;
          else return false;
    }	
	
/*	function crs_reject2($crs_code,$remarks,$crs_status,$user_id){
        $db = $this->load->database('custreg', true);

        $data = array(
          'customer_id'              => $crs_code,
          'status_code'              => $crs_status,
          'approval_by_user'           => $user_id,
          'crs_approval_text1'    => $remarks,
        
          );
        // var_dump($data);
		 // die;
          $result1 = $db->insert('crs_transaction_status_code_level2',$data);

          if($result1) return true;
          else return false;
    }
    //----------------
	
	
			function crs_reject($crs_code,$remarks,$crs_status,$user_id){
        $db = $this->load->database('custreg', true);

        $data = array(
          'customer_id'              => $crs_code,
          'status_code'              => $crs_status,
          'approval_by_user'           => $user_id,
          'crs_approval_text1'    => $remarks,
        
          );
        // var_dump($data);
		 // die;
          $result1 = $db->insert('crs_transaction_status_code',$data);

          if($result1) return true;
          else return false;
    }
    //----------------
	
	
	*/
		function crs_saleadminreject($crs_customerid,$crs_status,$user_id){
        $db = $this->load->database('custreg', true);

        $data = array(
          'customer_id'              => $crs_customerid,
          'status_code'              => $crs_status,
          'approval_by_user'           => $user_id,
         // 'crs_approval_text1'    => $remarks,
        
          );
        // var_dump($data);
		 // die;
          $result1 = $db->insert('crs_transaction_status_code',$data);

          if($result1) return true;
          else return false;
    }
	
	
	function list_crs_report_with_approval_list($customer_id){ 
        $db = $this->load->database('custreg', true);
		$status= $db->query("SELECT * FROM crs_h_approval i
                      inner join crs_approval ap on(i.crs_approval_code=ap.crs_approval_code)
                      inner join user u on(u.user_id=i.approval)
                      where i.customer_id='".$customer_id." and crs_approval_code='CRSAP001' and crs_approval_code='CRSAP002'");
        return $query->result_array();
		
    }
	
	 function list_crs_approval_with_approval_list($customer_id){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_h_approval t
                      inner join crs_approval ip on(t.crs_approval_code= ip.crs_approval_code)
                      inner join user u on(u.user_id=t.approval_by_user)
                      where t.customer_id='".$customer_id."' order by t.approval_datetime;");

                     
        return $query->result_array();
    }
/*	
	 function list_crs_approval_with_approval_list($customer_id){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_transaction_status_code t
                      inner join crs_mster_status s on(t.status_code= s.status_code)
                      inner join user u on(u.user_id=t.approval_by_user)
                      where t.customer_id='".$customer_id."';");

                     
        return $query->result_array();
    }
	
/*	 function update_status_on_approval2($remarks,$crs_status,$user_id,$crs_code){
        $db = $this->load->database('custreg', true);
        $query = $db->query("update crs_transaction_status_code set status_code='".$crs_status."', approval_by_user ='".$user_id."',crs_approval_text1='".$remarks."',
                      where customer_id='".$crs_code."';");
        return true;
    }
*/	
	 function update_status_crs_to_canceled($user_id,$datetime,$crs_code,$remarks){
        $db = $this->load->database('custreg', true);
        $query = $db->query("update crs_customers set status_code='CRSST004',canceled_flag='X', crs_approval_text2='".$remarks."',
                      canceled_by='".$user_id."',canceled_date_time='".$datetime."'
                      where customer_id='".$crs_code."';");
        return true;
    }

	function list_email_approval($user_id ){
	$db = $this->load->database('custreg', true);
	$query = $db->query("SELECT i.email FROM crs_approval_email i inner join user on(i.user_id=user.user_id)
                            where i.user_id='".$user_id."';");
        return $query->result_array();
	}
	
	function list_email_reject($crs_code ){
	$db = $this->load->database('custreg', true);
	$query = $db->query("SELECT user.email FROM crs_customers cus inner join user on(cus.created_user=user.user_id)
                            where cus.customer_id ='".$crs_code."';");
        return $query->result_array();
	}
	
	function list_email_salesreject($crs_customerid ){
	$db = $this->load->database('custreg', true);
	$query = $db->query("SELECT user.email FROM crs_customers cus inner join user on(cus.created_user=user.user_id)
                            where cus.customer_id ='".$crs_customerid."';");
        return $query->result_array();
	}
	
	
	function list_report_by_crs_code($customer_id){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_customers cus
		           inner join crs_division d on(cus.division_code=d.division_code)
                inner join crs_dist_channel ch on(cus.channel_code=ch.channel_code)
				 inner join crs_cust_group cg on(cus.cg_code=cg.cg_code)
                inner join crs_lga lg on(cus.lga_id =lg.lga_id)
                inner join crs_state s on(cus.state_code=s.state_code)
				inner join crs_tax tx on(cus.tax_code =tx.tax_code)
				inner join crs_incoterms inc on(cus.incoterm_code =inc.incoterm_code)
                inner join crs_mster_status st on(cus.status_code=st.status_code)
                 inner join crs_sales_district dis on(cus.sales_district_code=dis.sales_district_code) 
				 inner join crs_sales_group sg on(cus.sales_group_code = sg.sales_grp_code) 
                  inner join crs_sales_office off on(cus.sales_office_code=off.sales_office_code)				  
               where cus.customer_id='".$customer_id."'
                order by created_time;");
                  //and ih.canceled!='X'
        return $query->result_array();
    }
    //-------------
	
	
	 function report_crs_h($from,$to){
        $db = $this->load->database('custreg', true);
        $query = $db->query("SELECT * FROM crs_customers cus
		           inner join crs_division d on(cus.division_code=d.division_code)
                inner join crs_dist_channel ch on(cus.channel_code=ch.channel_code)
				 inner join crs_cust_group cg on(cus.cg_code=cg.cg_code)
                inner join crs_lga lg on(cus.lga_id =lg.lga_id)
                inner join crs_state sta on(cus.state_code=sta.state_code)
                inner join crs_mster_status st on(cus.status_code=st.status_code)
				inner join crs_incoterms inc on(cus.incoterm_code =inc.incoterm_code)
				inner join crs_tax tx on(cus.tax_code =tx.tax_code)
				inner join crs_sales_group sg on(cus.sales_group_code=sg.sales_grp_code)
                 inner join crs_sales_district dis on(cus.sales_district_code=dis.sales_district_code) 
                  inner join crs_sales_office off on(cus.sales_office_code=off.sales_office_code)
                                 where created_at >='".$from."' and created_at <='".$to."'
                order by created_time;");
        return $query->result_array();
    }
  }
///////

    
    


?>