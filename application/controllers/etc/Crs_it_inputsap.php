<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


      class Crs_it_inputsap extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
	$this->load->model('model_sap');
	        $this->load->helper('url', 'form');

  }
  
  
  function index(){
  
	     
      }

function sapinput(){
	$this->load->view('templates/navigation');
	//$this->load->view('sap/v_transport',$data);	

	               // load crs paymentterm	
 
	$this->load->model('model_sap','',TRUE);	
	

 	
	
	$result =$this->model_sap->display_custrecords();
	foreach($result as $row){
           $data['v_list_customers'][] = array(
                                     "customer_id" => $row['customer_id'],
									 "business_name" => $row['business_name'],
                                     "customer_name" => $row['customer_name'],
                                     "created_at" => $row['created_at'],
                                     "created_time" => $row['created_time'],
                                   //  "market_code" => $row['market_code'],
                                     );		
} 
$this->load->view('sap/v_crs_it_inputsap',$data); 
}
	
	
	function salesinput(){
	$this->load->view('templates/navigation');
	//$this->load->view('sap/v_transport',$data);	

 
	$this->load->model('model_sap','',TRUE);
	
	$result =$this->model_sap->display_custrecordslist();
	foreach($result as $row){
           $data['v_list_customerstosales'][] = array(
                                     "customer_id" => $row['customer_id'],
									 "business_name" => $row['business_name'],
                                     "customer_name" => $row['customer_name'],
                                     "created_at" => $row['created_at'],
                                     "created_time" => $row['created_time'],
                                   //  "market_code" => $row['market_code'],
                                     );		
} 
$this->load->view('sap/v_crs_salesadmininput',$data); 
}


function show_customer_detail(){

     $customer_id = $_POST['customer_id'];

     $this->load->model('model_sap','',TRUE);
	 
	              // load crs paymentterm
	$result = $this->model_sap->list_all_crs_paymentterm();
        foreach($result as $row){
          $data['v_list_crs_paymentterm'][] = array(
                                    "payment_term_code" => $row['payment_term_code'],
                                     "payment_term" => $row['payment_term'],
                                    
                                    );
	 
}

      $result = $this->model_sap->display_allcustomer($customer_id);
      if($result){
        foreach($result as $row){
           $data['v_cust_list_detail'][] = array(
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

      $this->load->view('sap/v_crs_salesadmin_details',$data);
	  
 }

	
 function updatesap(){
	 

		$customer_sap_code=$this->input->post('cust_sapcode');
		$customer_id=$this->input->post('crs_customerid');
	// var_dump($transport_route);
	// die(); model
		$this->load->model('model_sap','',TRUE);
        $data=$this->model_sap->update_sap_code($customer_id,$customer_sap_code);
		header('Content-Type: application/json');
        echo json_encode($data);
		
		//$this->model_sap->update_status_to_waitsap($customer_id);
		  $this->load->library('MY_phpmailer');
		  $to ="adeyokunnu.folasade@euro-mega.com";
		  $cc ="medinat.raji@euro-mega.com";
		    $subject = "New Customer ".$customer_id." Registration";
		$mailContent= "<h1>New Customer Registration-".$customer_id."</h1>";	
       $mailContent.= "<table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, 	Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, We would like to inform you that New Customer ".$customer_id." has been registered successfully.</p>
                                  </td>
                                </tr>
                   </tbody>
             </table>";
		  $this->send($to,$subject,$mailContent,$altbody,$cc,$from_info);
    }
	
	function file_download()
    {
        $file_name= $this->input->get('f');

        $this->load->helper('download');
        $data = file_get_contents($file_name);

        force_download($file_name, $data);
}
	
	function updatecredit(){
	 
		$payment=$this->input->post('crs_payment_term');
        $creditF=$this->input->post('cust_creditF');
		$creditN=$this->input->post('cust_creditN');
		$customer_id=$this->input->post('crs_customerid');
		$remarks=$this->input->post('remarks');
		$approval_date=$this->input->post('date');
		$approval_by_user=$this->input->post('user');
		
		//$n = $this->input->post(');
	// var_dump($payment);
		// die(); //model
		$this->load->model('model_sap','',TRUE);
        $data=$this->model_sap->update_credit_limit($customer_id,$payment,$creditF,$creditN);
		$crs_approval_code=$this->model_sap->get_approval_level2();
		$crs_status=$this->model_sap->get_status_from_salesadmin();
		//fix in insert function from model
		$this->model_sap->insertsalesadmin($customer_id,$crs_approval_code,$crs_status,$remarks,$approval_date,$approval_by_user);
		header('Content-Type: application/json');
        echo json_encode($data);
		
		 		
	
		 $this->load->library('MY_phpmailer');
	            $to = "carrie.obasuyi@euro-mega.com";
                $subject = "New Customer ".$customer_id." Registration";
				$cc= "medinat.raji@euro-mega.com";
       $mailContent.= "<table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, 	Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, We would like to inform you that New Customer Registration ".$customer_id." is awaiting your approval/rejection. Kindly <a href='http://172.23.8.10/z_tpimx/index.php/Crs_superapprov/superapprv'>Click this link</a> to view.</p>
                                  </td>
						  							  
								  
                                </tr>
                   </tbody>
             </table>";
		  $this->send($to,$subject,$mailContent,$altbody,$cc,$from_info);
		  
    }
	  
	function crs_reject(){
      $crs_customerid = $_POST['crs_customerid'];
      $remarks = str_replace("'","",$_POST['remarks']);
      $this->load->model('model_sap','',TRUE);

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user_id = $session_data['z_tpimx_user_id'];
	   $email = $session_data['z_tpimx_email'];
	  $date = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
      // $crs_status = $this->model_sap->get_reject_status();
		 //var_dump($crs_status);
      // die;
		  
      $result = $this->model_sap->update_status_crs_to_canceled($user_id,$datetime,$crs_code,$remarks);
	  //  echo $result;
	  // $this->model_sap->update_status_crs_to_reject($crs_customerid);
	    $result3 = $this->model_sap->list_email_salesreject($crs_customerid );
	   
	    $this->load->library('MY_phpmailer');
		foreach($result3 as $row){
	$to = $row['email'];
	$cc= $email;	
	    $this->load->library('MY_phpmailer');
	//	$to= "";
		 $subject = "Rejected Customer Registration-".$crs_customerid;
		  $mailContent.= "<table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, 	Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                   <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, We would like to inform you that Customer ".$crs_customerid." Registration has been Rejected. Kindly <a href='http://172.23.8.10/z_tpimx/index.php/Crs_edit_reg/editinput'>Click this link</a> to update.</p>
                                  </td>
								  </tr>
								  </tbody>
								  </table>
								  
								  <table>
								  <tbody>
								  <tr>
								  <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>Remarks</div>
                                  </td>
								  
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>".$remarks."</div>
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
     // $mail->addAddress("carrie.obasuyi@euro-mega.com", "Sales Head");     // Add a recipient
	  $mail->addCC($cc, $cc);
      $mail->isHTML(true);

      $mail->Subject =  $subject;
	// $mailContent= "<h1>New customer registration</h1>";
	        
	   
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
	  