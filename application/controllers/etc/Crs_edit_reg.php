<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


      class Crs_edit_reg extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
	$this->load->model('model_sap');
	        $this->load->helper('url', 'form');

  }
  
  
  function index(){
  
	     
      }

function editinput(){
	$this->load->view('templates/navigation');


	              
 
	$this->load->model('model_sap','',TRUE);	
	

 	
	
	$result =$this->model_sap->display_editrecords();
	foreach($result as $row){
           $data['v_edit_customers'][] = array(
                                     "customer_id" => $row['customer_id'],
									 "business_name" => $row['business_name'],
                                     "customer_name" => $row['customer_name'],
                                     "created_at" => $row['created_at'],
                                     "created_time" => $row['created_time'],
                                   //  "market_code" => $row['market_code'],
                                     );		
} 
$this->load->view('sap/v_crs_edit_reg',$data); 
}
	

	
function show_cust_detail(){
     $customer_id = $_POST['customer_id'];

     $this->load->model('model_sap','',TRUE);

      $result = $this->model_sap->list_custedit($customer_id);
      if($result){
        foreach($result as $row){
           $data['v_list_crs_custedit_detail'][] = array(
                                    "customer_id" => $row['customer_id'],
                                     "customer_rc_number" => $row['customer_rc_number'],
									 "business_name" => $row['business_name'],
                                     "customer_name" => $row['customer_name'],
                                     "email" => $row['email'],
                                     "telephone_1" => $row['telephone_1'],
                                     "mobile_phone" => $row['mobile_phone'],
									 "address" => $row['address'],
                                     "cg_code" => $row['cg_code'],
									 "customer_group" => $row['customer_group'],
									 "contact_person_title" => $row['contact_person_title'],
                                     "contact_person_gender" => $row['contact_person_gender'],
                                     "contact_person_firstname" => $row['contact_person_firstname'],
                                     "contact_person_lastname" => $row['contact_person_lastname'],
                                     "contact_person_phone" => $row['contact_person_phone'],
                                     "contact_person_email" => $row['contact_person_email'],
                                     "creditFood" => $row['creditFood'],
									  "creditNFood" => $row['creditNFood'],
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
									    "plants_code" => $row['plants_code'],
									 "state" => $row['state'],
                                     "lga_id" => $row['lga_id'],
									 "lga" => $row['lga'],
									 "kw_sales_turn_over" => $row['kw_sales_turn_over'],
									    "kw_years" => $row['kw_years'],
									 "kw_products" => $row['kw_products'],
                                     "kw_businessnature" => $row['kw_businessnature'],
									 "kw_reason" => $row['kw_reason'],
									 "kw_companyname" => $row['kw_companyname'],
									 "supervisor_name" => $row['supervisor_name'],
                                     "transport_route" => $row['transport_route'],
                                      "transport_zone" => $row['transport_zone'],
									  "kw_attachment" => $row['kw_attachment'],
									   "created_at" => $row['created_at'],
                                     "created_time" => $row['created_time'],
									  
                                     );
        }
      }

      $this->load->view('sap/v_crs_edit_details',$data);
	  
 }
     
function updateform(){
	 
		$crs_customername=$this->input->post('crs_customername'); 
		$crs_businessname=$this->input->post('crs_businessname'); 
		$crs_customerid=$this->input->post('crs_customerid');
		$customer_rc_number=$this->input->post('customer_rc_number'); 
		$crs_dist_channel=$this->input->post('crs_dist_channel');
		$crs_cg=$this->input->post('crs_cg'); 
		$cdate=$this->input->post('cdate'); 
		$ctime=$this->input->post('ctime'); 
		$crs_incoterms=$this->input->post('crs_incoterms');
		$crs_division=$this->input->post('crs_division'); 
		$crs_tax=$this->input->post('crs_tax');
		$telephone_1=$this->input->post('telephone_1'); 
		$mobile_phone=$this->input->post('mobile_phone');
		$email=$this->input->post('email'); 
		$contact_person_title=$this->input->post('contact_person_title');
		$contact_person_gender=$this->input->post('contact_person_gender'); 
		$contact_person_firstname=$this->input->post('contact_person_firstname');
		$contact_person_lastname=$this->input->post('contact_person_lastname'); 
		$contact_person_phone=$this->input->post('contact_person_phone');
		$contact_person_email=$this->input->post('contact_person_email'); 
		$kw_business_address=$this->input->post('kw_business_address');
		$address=$this->input->post('address'); 
		$contact_person_address=$this->input->post('contact_person_address');
		$sales_district_code=$this->input->post('sales_district_code'); 
		$sales_group_code=$this->input->post('sales_group_code');
		$sales_office_code=$this->input->post('sales_office_code'); 
		$kw_area_to_cover=$this->input->post('kw_area_to_cover');
		$kw_father_name=$this->input->post('kw_father_name'); 
		$kw_father_occupation=$this->input->post('kw_father_occupation');
		$kw_brother_name=$this->input->post('kw_brother_name'); 
		$kw_brother_occupation=$this->input->post('kw_brother_occupation');
		$kw_infrastructure=$this->input->post('kw_infrastructure'); 
		$kw_connections=$this->input->post('kw_connections');
		$kw_sales_turn_over=$this->input->post('kw_sales_turn_over'); 
		$relative_name=$this->input->post('relative_name');
		$relative_phone=$this->input->post('relative_phone'); 
		$kw_years=$this->input->post('kw_years');
		$kw_products=$this->input->post('kw_products'); 
		$kw_businessnature=$this->input->post('kw_businessnature');
		$kw_reason=$this->input->post('kw_reason'); 
		$kw_companyname=$this->input->post('kw_companyname');
		$kw_start_date=$this->input->post('kw_start_date'); 
		$kw_sales_order=$this->input->post('kw_sales_order');
		$kw_remarks=$this->input->post('kw_remarks');
		$state_code=$this->input->post('state_code'); 
		$lga_id=$this->input->post('lga_id');
		$kw_market=$this->input->post('kw_market'); 
		$plants_code=$this->input->post('plants_code');
		
	/*	 $post = $this->input->post();
		 if (!empty($_FILES['files']['name'])) {
        $data = array();
        $countfiles = count($_FILES['files']['name']);
        if ($countfiles > 9) {
          echo "you can upload 9 files maxium";
        }else{
          for($i=0;$i<$countfiles;$i++){
   
            if(!empty($_FILES['files']['name'][$i])){
     
              // Define new $_FILES array - $_FILES['file']
              $_FILES['file']['name'] = $_FILES['files']['name'][$i];
              $_FILES['file']['type'] = $_FILES['files']['type'][$i];
              $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
              $_FILES['file']['error'] = $_FILES['files']['error'][$i];
              $_FILES['file']['size'] = $_FILES['files']['size'][$i];
             
              $config['upload_path'] = './uploads/'; 

              $config['allowed_types'] = 'pdf|png|jpeg|jpg|gif|tiff';
              $config['max_size'] = '5000'; // max_size in kb
              $config['file_name'] = $_FILES['files']['name'][$i];
     
              $test = $this->load->library('upload',$config); 
              if($this->upload->do_upload('file')){
                // Get data about the file
                $uploadData = $this->upload->data();
                $filename = $uploadData['file_name'];
                // Initialize array
                $data['filenames'][] = $filename;
                print_r($filename);
				}else{
                //echo $this->upload->display_errors(); 
              }
            }
          }
        }
      }
		$post['files'] = $data['filenames'];
		
		 $kw_attachment = implode(",", $post['files']);
	// var_dump($payment);
		// die(); //model  */
		$this->load->model('model_sap','',TRUE);
		
        $data=$this->model_sap->updateform($crs_customername,$crs_businessname,$crs_customerid,$customer_rc_number,$crs_dist_channel,$crs_cg,$cdate,$ctime,$crs_incoterms,$crs_division,$crs_tax,$telephone_1,$mobile_phone,$email,$contact_person_title,$contact_person_gender,
		$contact_person_firstname,$contact_person_lastname,$relative_name,$relative_phone,$contact_person_phone,$contact_person_email,$kw_business_address,
		$address,$contact_person_address,$sales_district_code,$sales_group_code,$kw_area_to_cover,$kw_father_name,$kw_father_occupation,
		$kw_brother_name,$kw_brother_occupation,$kw_infrastructure,	$kw_connections,$kw_sales_turn_over,$kw_years,$kw_products,
		$kw_businessnature,$kw_reason,$kw_companyname,$kw_start_date,$kw_sales_order,$kw_remarks,$state_code,$lga_id,$kw_market,$plants_code);
		
		header('Content-Type: application/json');
        echo json_encode($data);
		
    }
	
 
	  }  
?>
	  