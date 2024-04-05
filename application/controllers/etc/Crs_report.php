<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crs_report extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['crs_report'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sap/v_crs_report');
      }
  }
  
  
  function get_crs_report_detail(){
      $this->load->model('model_sap','',TRUE);

      $date_from  = $_POST['date_from'];
      $date_to    = $_POST['date_to'];
	  
	  $result =$this->model_sap->report_crs_h($date_from,$date_to);
	foreach($result as $row){
           $data['v_crs_report_generate'][] = array(
                                    "customer_id" => $row['customer_id'],
									 "business_name" => $row['business_name'],
                                     "customer_rc_number" => $row['customer_rc_number'],
                                     "customer_name" => $row['customer_name'],
									 "created_time" => $row['created_time'],
									   "created_at" => $row['created_at'],
									    "status_code" => $row['status_code'],
										"status_name" => $row['status_name'],
                                     "email" => $row['email'],
                                     "telephone_1" => $row['telephone_1'],
                                     "mobile_phone" => $row['mobile_phone'],
									 "address" => $row['address'],
                                     "cg_code" => $row['cg_code'],
									 "customer_sap_code" => $row['customer_sap_code'],
									 "customer_group" => $row['customer_group'],
                                     "contact_person_firstname" => $row['contact_person_firstname'],
                                     "contact_person_lastname" => $row['contact_person_lastname'],
                                     "contact_person_phone" => $row['contact_person_phone'],
                                     "contact_person_email" => $row['contact_person_email'],
									    "relative_name" => $row['relative_name'],
										 "relative_phone" => $row['relative_phone'],
										 "contact_person" => $row['contact_person'],
                                     "credit" => $row['credit'],
									 "incoterms" => $row['incoterms'],
									 "incoterm_code" => $row['incoterm_code'],
                                     "channel_code" => $row['channel_code'],
									 "description" => $row['description'],
                                     "division_code" => $row['division_code'],
									 "division" => $row['division'],
                                     "sales_district_code" => $row['sales_district_code'],
									 "sales_district" => $row['sales_district'],
                                     "sales_group_code" => $row['sales_group_code'],
                                     "sales_office_code" => $row['sales_office_code'],
									  "sales_office" => $row['sales_office'],
                                     "tax_code" => $row['tax_code'],
									 "status" => $row['status'],
									  "kw_market" => $row['kw_market'],
									  "kw_business_address" => $row['kw_business_address'],
									  "contact_person_address" => $row['contact_person_address'],
									  "kw_father_name" => $row['kw_father_name'],
									  "kw_father_occupation" => $row['kw_father_occupation'],
									  "kw_brother_name" => $row['kw_brother_name'],
									  "kw_brother_occupation" => $row['kw_brother_occupation'],
									  "kw_infrastructure" => $row['kw_infrastructure'],
									  "kw_reason" => $row['kw_reason'],
									  "kw_connections" => $row['kw_connections'],
									  "kw_area_to_cover" => $row['kw_area_to_cover'],
									  "kw_sales_order" => $row['kw_sales_order'],
									  "kw_start_date" => $row['kw_start_date'],
									  "kw_remarks" => $row['kw_remarks'],
									  "state_code" => $row['state_code'],
									 "state" => $row['state'],
                                     "lga_id" => $row['lga_id'],
									 "lga" => $row['lga'],
									 "supervisor_name" => $row['supervisor_name'],
                                     "transport_route" => $row['transport_route'],
                                      "transport_zone" => $row['transport_zone'],
									  "kw_attachment" => $row['kw_attachment'],
                                    
                                     );		
} 
$this->load->view('sap/v_crs_report_generate',$data); 
	  
  }
  
  function show_crs_detail(){
     $customer_id = $_POST['customer_id'];

     $this->load->model('model_sap','',TRUE);

      $result = $this->model_sap->list_report_by_crs_code($customer_id);
      if($result){
        foreach($result as $row){
           $data['v_list_crs_report_detail'][] = array(
                                     "customer_id" => $row['customer_id'],
									 "business_name" => $row['business_name'],
                                     "customer_rc_number" => $row['customer_rc_number'],
                                     "customer_name" => $row['customer_name'],
                                     "email" => $row['email'],
                                     "telephone_1" => $row['telephone_1'],
                                     "mobile_phone" => $row['mobile_phone'],
									 "address" => $row['address'],
                                     "cg_code" => $row['cg_code'],
									 "customer_sap_code" => $row['customer_sap_code'],
									 "customer_group" => $row['customer_group'],
                                     "contact_person_firstname" => $row['contact_person_firstname'],
                                     "contact_person_lastname" => $row['contact_person_lastname'],
                                     "contact_person_phone" => $row['contact_person_phone'],
                                     "contact_person_email" => $row['contact_person_email'],
									    "relative_name" => $row['relative_name'],
										 "relative_phone" => $row['relative_phone'],
										 "contact_person" => $row['contact_person'],
                                     "credit" => $row['credit'],
									 "incoterms" => $row['incoterms'],
									 "incoterm_code" => $row['incoterm_code'],
                                     "channel_code" => $row['channel_code'],
									 "description" => $row['description'],
                                     "division_code" => $row['division_code'],
									 "division" => $row['division'],
                                     "sales_district_code" => $row['sales_district_code'],
									 "sales_district" => $row['sales_district'],
                                     "sales_group_code" => $row['sales_group_code'],
                                     "sales_office_code" => $row['sales_office_code'],
									  "sales_office" => $row['sales_office'],
                                     "tax_code" => $row['tax_code'],
									 "status" => $row['status'],
									  "kw_market" => $row['kw_market'],
									  "kw_business_address" => $row['kw_business_address'],
									  "contact_person_address" => $row['contact_person_address'],
									  "kw_father_name" => $row['kw_father_name'],
									  "kw_father_occupation" => $row['kw_father_occupation'],
									  "kw_brother_name" => $row['kw_brother_name'],
									  "kw_brother_occupation" => $row['kw_brother_occupation'],
									  "kw_infrastructure" => $row['kw_infrastructure'],
									  "kw_reason" => $row['kw_reason'],
									  "kw_connections" => $row['kw_connections'],
									  "kw_area_to_cover" => $row['kw_area_to_cover'],
									  "kw_sales_order" => $row['kw_sales_order'],
									  "kw_start_date" => $row['kw_start_date'],
									  "kw_remarks" => $row['kw_remarks'],
									  "state_code" => $row['state_code'],
									 "state" => $row['state'],
                                     "lga_id" => $row['lga_id'],
									 "lga" => $row['lga'],
									 "supervisor_name" => $row['supervisor_name'],
                                     "transport_route" => $row['transport_route'],
                                      "transport_zone" => $row['transport_zone'],
									  "kw_attachment" => $row['kw_attachment'],
                                     
                                     );
        }
      }
	    else $data['v_list_crs_report_detail'] = 0;
	 
	 // get approval
      if($result){
          
          $result1 = $this->model_sap->list_crs_approval_with_approval_list($customer_id);
          if(!$result1){
              $data['v_list_crs_apprv_detail_approval'] = 0;
          }
          else{
            foreach($result1 as $row){
               $data['v_list_crs_apprv_detail_approval'][] = array(
                                        "customer_id" => $row['customer_id'],
                                        "crs_approval_name" => $row['crs_approval_name'],
										  "approval_date" => $row['approval_date'],
                                         "approval_datetime" => $row['approval_datetime'],
                                         "crs_approval_text1" => $row['crs_approval_text1'],
                                         "email" => $row['email'],
                                         "user_id" => $row['user_id'],
                                         "name" => $row['name'],
                                         );
            }
          }
		
     }
      else{
         $data['v_list_crs_apprv_detail_approval'] = 0;
      }

      $this->load->view('sap/v_crs_report_detail',$data);
	  
 }

  }
  
  ?>