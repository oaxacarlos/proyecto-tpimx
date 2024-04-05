<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


      class Sap extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
	$this->load->helper('file');

	$this->load->model('model_sap');
  }



  function lga($deta=""){
      $this->load->model('model_sap','',TRUE);
      $result = $this->model_sap->list_crs_lga_bystate($deta);
		header('Content-Type: application/json');  // <-- header declaration
		echo json_encode($result, true);    // <--- encode
		exit();
  }


  
  
  function index(){

      }
  function cust_reg(){
	$this->load->view('templates/navigation');
	//$this->load->view('sap/v_customer_reg',$data);
	
	// load crs distchannel
	$result = $this->model_sap->list_all_crs_dist_channel();
        foreach($result as $row){
          $data['v_list_crs_dist_channel'][] = array(
                  "channel_code" => $row['channel_code'],
                   "description" => $row['description'],
                  
                  );
		}
		
		 // load crs customer group
	$result = $this->model_sap->list_all_crs_cust_group();
        foreach($result as $row){
          $data['v_list_crs_cust_group'][] = array(
                                    "cg_code" => $row['cg_code'],
                                     "customer_group" => $row['customer_group'],
                                    
                                    );
	 
}


        // load crs incoterm
	$result = $this->model_sap->list_all_crs_incoterms();
        foreach($result as $row){
          $data['v_list_crs_incoterms'][] = array(
                                    "incoterm_code" => $row['incoterm_code'],
                                     "incoterms" => $row['incoterms'],
                                    
                                    );
	 
}

               // load crs division
	$result = $this->model_sap->list_all_crs_division();
        foreach($result as $row){
          $data['v_list_crs_division'][] = array(
                                    "division_code" => $row['division_code'],
                                     "division" => $row['division'],
                                    
                                    );
	 
}

            // load crs tax
	$result = $this->model_sap->list_all_tax();
        foreach($result as $row){
          $data['v_list_crs_tax'][] = array(
                                    "tax_code" => $row['tax_code'],
                                     "status" => $row['status'],
                                    
                                    );
	 
}



               // load crs paymentterm
	$result = $this->model_sap->list_all_crs_paymentterm();
        foreach($result as $row){
          $data['v_list_crs_paymentterm'][] = array(
                                    "payment_term_code" => $row['payment_term_code'],
                                     "payment_term" => $row['payment_term'],
                                    
                                    );
	 
}


                  // load crs state
	$result = $this->model_sap->list_all_crs_state();
        foreach($result as $row){
          $data['v_list_crs_state'][] = array(
                                    "state_code" => $row['state_code'],
                                     "state" => $row['state'],
                                    
                                    );
	 
}

                    // load crs lga
	$result = $this->model_sap->list_all_crs_lga();
        foreach($result as $row){
          $data['v_list_crs_lga'][] = array(
                                    "lga_id" => $row['lga_id'],
                                     "lga" => $row['lga'],
                                    
                                  );
	 
}



  function show_lga(){
      $this->load->model('model_sap','',TRUE);

      $state_code = $_POST['crs_state'];

      // load lga
      $result = $this->model_sap->list_crs_lga_bystate($state_code);
      foreach($result as $row){
         $data['v_list_crs_lgabystate'][] = array(
                                   "lga_id" => $row['lga_id'],
                                   "lga" => $row['lga'],
                                   
                                   );
      }
  }

		$this->load->view('sap/v_customer_reg',$data);
 
	}
	  //show sales district
	   function show_sales_district(){
     $this->load->model('model_sap','',TRUE);
      
      $result = $this->model_sap->list_all_sales_district();
      foreach($result as $row){
         $data['v_list_sales_district'][] = array(
                       "sales_district_code" => $row['sales_district_code'],
                       "sales_district" => $row['sales_district'],
                                 
                                  );
      }
      $this->load->view('sap/v_crs_request_sales_district',$data);
	  
	  }
	  
	  //show sales office
	   function show_sales_office(){
     $this->load->model('model_sap','',TRUE);
      
      $result = $this->model_sap->list_all_sales_office();
      foreach($result as $row){
         $data['v_list_sales_office'][] = array(
                       "sales_office_code" => $row['sales_office_code'],
                       "sales_office" => $row['sales_office'],
                                 
                                  );
      }
      $this->load->view('sap/v_crs_request_sales_office',$data);
	  
	  }
	  
	    function show_plant(){
      $this->load->model('model_sap','',TRUE);
     
      $result = $this->model_sap->list_all_plants();
      foreach($result as $row){
         $data['v_list_plants'][] = array(
                                   "plant_code" => $row['plant_code'],
                                   "plants" => $row['plants'],
                                   
                                   );
      }
      $this->load->view('sap/v_crs_request_plants',$data);
  }
  //-----------------
	  
	  
	    function show_sales_group(){
      $this->load->model('model_sap','',TRUE);
	  
	  $salesoffice = $_POST['salesoffice'];
	   $division_code = $_POST['division_code'];
       
	   
	 
      // load sales group
      $result = $this->model_sap->list_sales_group_byofficedivision($salesoffice,$division_code);
      foreach($result as $row){
         $data['v_list_sales_group'][] = array(
                                   "sales_grp_code" => $row['sales_grp_code'],
                                   "sales_groupNF" => $row['sales_groupNF'],
								   "sales_groupFD" => $row['sales_groupFD'],
                                   "supervisor_name" => $row['supervisor_name'],
                                   "region" => $row['region'],
								   "division_code" => $row['division_code'],
                                   "region_id" => $row['region_id'],
								   "sales_office_code" => $row['sales_office_code'],
                                   );
      }
      $this->load->view('sap/v_crs_request_sales_group',$data);
  }
  //-----------------
	  
	  
    function customer_add_original(){
	
      $customer_name   = $_POST['customer_name'];
	   $business_name   = $_POST['business_name'];
      $customer_rc_number  = $_POST['customer_rc_number'];
      $channel_code = $_POST['channel_code'];
      $cg_code = $_POST['cg_code'];
      $incoterm_code = $_POST['incoterm_code'];
      $division_code = $_POST['division_code'];
	    $tax = $_POST['tax'];
      $telephone_1   = $_POST['telephone_1'];
      $mobile_phone  = $_POST['mobile_phone'];
      $email = $_POST['email'];
      $contact_person_title = $_POST['contact_person_title'];
      $contact_person_gender = $_POST['contact_person_gender'];
      $contact_person_firstname = $_POST['contact_person_firstname'];
	    $contact_person_lastname = $_POST['contact_person_lastname'];
      $contact_person_phone = $_POST['contact_person_phone'];
	   $relative_name = $_POST['relative_name'];
	    $relative_phone = $_POST['relative_phone'];
      $contact_person_email   = $_POST['contact_person_email'];
      $contact_person_address  = $_POST['contact_person_address'];
      $state_code = $_POST['statecode'];
      $lga_id = $_POST['lga_id'];
	  $region = $_POST['region'];
	   $address = $_POST['address'];
      $sales_district_code = $_POST['sales_district_code'];
      $plants_code = $_POST['plants_code'];
	   $salesoffice = $_POST['salesoffice'];
      $sales_group_code = $_POST['sales_group_code'];
      $payment_term_code = $_POST['payment_term_code'];
	  $credit = $_POST['credit'];
	   $kw_business_address = $_POST['kw_business_address'];
      $kw_companyname   = $_POST['kw_companyname'];
      $kw_businessnature  = $_POST['kw_businessnature'];
      $kw_products = $_POST['kw_products'];
      $kw_years = $_POST['kw_years'];
      $kw_sales_turn_over = $_POST['kw_sales_turn_over'];
      $kw_area_to_cover = $_POST['kw_area_to_cover'];
	  $kw_infrastructure = $_POST['kw_infrastructure'];
	   $kw_connections = $_POST['kw_connections'];
	    $kw_doc = $_POST['kw_doc'];
	   $kw_father_name = $_POST['kw_father_name'];
	   $kw_father_occupation = $_POST['kw_father_occupation'];
	   $kw_brother_occupation = $_POST['kw_brother_occupation'];
	   $kw_brother_name = $_POST['kw_brother_name'];
	  $kw_start_date = $_POST['kw_start_date'];
	   $kw_remarks = $_POST['kw_remarks'];
	    $kw_sales_order = $_POST['kw_sales_order'];
      $kw_market = $_POST['kw_market'];
      $kw_sales_executive_name = $_POST['kw_sales_executive_name'];
      $kw_reason  = $_POST['kw_reason'];
	   $contact_person  = $_POST['contact_person'];
	//	$kw_attachment = $_POST['kw_attachment'];
      
      $this->load->model('model_sap','',TRUE);
    
	    $this->model_sap->customer_name = $customer_name;
			$this->model_sap->business_name = $business_name;
		 $this->model_sap->customer_rc_number = $customer_rc_number;
		 $this->model_sap->channel_code = $channel_code;
		 $this->model_sap->cg_code = $cg_code;
		 $this->model_sap->incoterm_code = $incoterm_code;
		 $this->model_sap->division_code = $division_code;
		  $this->model_sap->tax_code = $tax;
		 $this->model_sap->telephone_1 = $telephone_1;
		 $this->model_sap->mobile_phone = $mobile_phone;
		 $this->model_sap->email = $email;
		 $this->model_sap->contact_person_title = $contact_person_title;
		$this->model_sap->contact_person_gender = $contact_person_gender;
		 $this->model_sap->contact_person_firstname = $contact_person_firstname;
		 $this->model_sap->contact_person_lastname = $contact_person_lastname;
		 $this->model_sap->contact_person_phone = $contact_person_phone;
		  $this->model_sap->contact_person_email = $contact_person_email;
		   $this->model_sap->relative_name = $relative_name;
		 $this->model_sap->contact_person_address = $contact_person_address;
		  $this->model_sap->created_user = $session_data['z_tpimx_user_id'];
		  $this->model_sap->status_code ='CRSST001';
		  $this->model_sap->statecode = $state_code;
		   $this->model_sap->lga_id = $lga_id;
		    $this->model_sap->region = $region;
		   $this->model_sap->address = $address;
		    $this->model_sap->sales_district_code = $sales_district_code;
			 $this->model_sap->plants_code = $plants_code;
			  $this->model_sap->sales_office_code = $salesoffice;
			   $this->model_sap->sales_group_code= $sales_group_code;
			    $this->model_sap->payment_term_code = $payment_term_code;
				$this->model_sap->credit = $credit;
				$this->model_sap->kw_business_address = $kw_business_address;
		  $this->model_sap->kw_home_address = $kw_home_address;
		 $this->model_sap->kw_companyname = $kw_companyname;
		 $this->model_sap->kw_businessnature = $kw_businessnature;
		 $this->model_sap->kw_products = $kw_products;
		 $this->model_sap->kw_years = $kw_years;
		$this->model_sap->kw_sales_turn_over = $kw_sales_turn_over;
		 $this->model_sap->kw_area_to_cover = $kw_area_to_cover;
		 $this->model_sap->kw_infrastructure = $kw_infrastructure;
		$this->model_sap->kw_connections = $kw_connections;
		$this->model_sap->kw_doc = $kw_doc;
		$this->model_sap->kw_father_name = $kw_father_name;
		$this->model_sap->kw_father_occupation = $kw_father_occupation;
		$this->model_sap->kw_brother_occupation = $kw_brother_occupation;
		$this->model_sap->kw_brother_name = $kw_brother_name;
		 $this->model_sap->kw_start_date = $kw_start_date;
		 $this->model_sap->kw_sales_order = $kw_sales_order;
		   $this->model_sap->kw_remarks = $kw_remarks;
		 $this->model_sap->kw_market = $kw_market;
		  //$this->model_sap->kw_sales_executive_name = $kw_sales_executive_name;
		 $this->model_sap->kw_reason = $kw_reason;
		 $this->model_sap->contact_person = $contact_person;
		 // $this->model_sap->kw_attachment = $kw_attachment;
          $result = $this->model_sap->customer_add();
	     
		 // $this->load->library('MY_phpmailer');
		//  $this->send($to,$subject,$body,$altbody,$cc,$from_info);
      
	
     }

    function customer_add(){

      $post = $this->input->post();

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
              $config['max_size'] = '20000'; // max_size in kb
              $config['file_name'] = $_FILES['files']['name'][$i];
     
              $test = $this->load->library('upload',$config); 
              if($this->upload->do_upload('file')){
                // Get data about the file
                $uploadData = $this->upload->data();
                $filename = $uploadData['file_name'];
                // Initialize array
                $data['filenames'][] = $filename;
                //print_r($filename);
              }else{
                //echo $this->upload->display_errors(); 
              }
            }
          }
        }
      }
      $post['files'] = $data['filenames'];
      $this->load->model('model_sap','',TRUE);
    
      $idnfo=$this->model_sap->customer_add($post);
	 
	  $ifo=explode("#",$idnfo);
	
	   
	   $session_data = $this->session->userdata('z_tpimx_logged_in');
	   $user_id = $session_data['z_tpimx_user_id'];
	  // echo $user_id;
	   $result3 = $this->model_sap->list_email_approval($user_id );
	   
	   
      $this->load->library('MY_phpmailer');
	foreach($result3 as $row){
	$to = $row['email'];
	//echo $to;
      $subject = "New Customer Registration-".$ifo[1];
			
	 $mailContent= "<h1>New Customer Registration-".$ifo[1]."</h1>";
	                      $mailContent.= "<table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, 	Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, We would like to inform you that New Customer ".$ifo[1]." with Registration Number ".$ifo[0]." is awaiting your approval. Kindly <a href='http://172.23.8.10/z_tpimx/index.php/crs_approv/apprv'>click this link</a> to view.</p>
									   
                                  </td>
                                </tr>
                   </tbody>
             </table>";


	 $this->send($to,$subject,$mailContent,$altbody,$cc,$from_info);
      
  
    }
}
	 
	 
	  public function send($to,$subject,$body,$altbody,$cc,$from_info){
	  $this->load->library('MY_phpmailer');
      $mail = new PHPMailer;

      $from = "noreply.info@euro-mega.com";
      $password = "Eurobi16";

      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'mail.euro-mega.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = $from;                 // SMTP username
      $mail->Password = $password;                           // SMTP password
      //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;
      $mail->setFrom($from, $from_info);
	  $mail->addAddress($to, $to);   
      //$mail->addAddress("rawlings.ikayea@euro-mega.com", "KAPM Manager"); 
		$mail->addCC("adeyokunnu.folasade@euro-mega.com", "KAPM Manager");// Add a recipient
      $mail->isHTML(true);

      //$mail->Subject = ' New Customer Registration';
	   $mail->Subject = $subject;
	                      $mail->Body = $body;
      //$mail->AltBody = $altbody;
      //$mail->attach  = $attachment;
      //$mail->addAttachment($attachment);

      if(!$mail->send()) {
         // echo 'Message could not be sent.';
          echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
         // echo 'Message has been sent';
      }
    }
	

	

	  
 }
	  
?>