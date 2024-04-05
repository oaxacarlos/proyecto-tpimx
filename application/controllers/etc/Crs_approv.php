<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crs_approv extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

 
  function index(){
  }
  
      function apprv(){
      $this->load->view('templates/navigation');
	  
	  $this->load->model('model_sap','',TRUE);	
	 $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user_id = $session_data['z_tpimx_user_id'];
	  $result = $this->model_sap->list_approval($user_id);
	// if($result){
             foreach($result as $row){
               $data['v_list_crs_apprv'][] = array(
			                              "status_name" => $row['status_name'],
                                         "customer_id" => $row['customer_id'],
                                         "created_time" => $row['created_time'],
                                         "customer_name" => $row['customer_name'],
										 "business_name" => $row['business_name'],
										 "region" => $row['region'],
                                         "created_at" => $row['created_at'],
                                       
                                         );
            }
        // else $data['v_list_crs_apprv'] = 0;
      // } 
	   $this->load->view('sap/v_crs_approval',$data);	
       }
	  
	   function show_crs_detail(){
     $customer_id = $_POST['customer_id'];

     $this->load->model('model_sap','',TRUE);

      $result = $this->model_sap->list_approval_by_crs_code($customer_id);
      if($result){
        foreach($result as $row){
           $data['v_list_crs_apprv_detail_h'][] = array(
                                    "customer_id" => $row['customer_id'],
									 "business_name" => $row['business_name'],
                                     "customer_rc_number" => $row['customer_rc_number'],
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

      $this->load->view('sap/v_crs_approv_details',$data);
	  
 }
	 function file_download()
    {
        $file_name= $this->input->get('f');

        $this->load->helper('download');
        $data = file_get_contents($file_name);

        force_download($file_name, $data);
} 

   function crs_approval(){
      $crs_code       = $_POST['crs_code'];
      $remarks        = str_replace("'","",$_POST['remarks']);
	  
	   $this->load->model('model_sap','',TRUE);
	   
	    $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user_id = $session_data['z_tpimx_user_id'];
	   $email = $session_data['z_tpimx_email'];
	  // var_dump($user_id);
			  // die;
              $crs_status = $this->model_sap->get_status_from_approval_code();
			   $crs_approval_code = $this->model_sap->get_approval_level1();
			  // var_dump($crs_status);
			  // die;
			        // $this->model_sap->customer_id = $crs_code;
			// $this->model_sap->crs_approval_text1 = $remarks;
			// $this->model_sap->status_code = $crs_status;
			$data=$this->model_sap->crs_approval($crs_code,$remarks,$crs_status,$user_id,$crs_approval_code);
			 
			
        $this->model_sap->update_status_crs_to_approval($crs_code);
		
		 $this->load->library('MY_phpmailer');
		 	 $to= 'medinat.raji@euro-mega.com';
			 $cc = 'theresa.okon@euro-mega.com';
		 $subject = "New Customer Registration-".$crs_code;
		 
		  $mailContent= "<h1>New customer registration-".$crs_code."</h1>";
		  $mailContent.= "<table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, 	Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                   <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, We would like to inform you that New Customer with Registration ".$crs_code." is awaiting processing. Kindly <a href='http://172.23.8.10/z_tpimx/index.php/Crs_it_inputsap/salesinput'>Click this link</a> to view.</p>
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
	
	function crs_reject(){
      $crs_code = $_POST['crs_code'];
      $remarks = str_replace("'","",$_POST['remarks']);
      $this->load->model('model_sap','',TRUE);

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user_id = $session_data['z_tpimx_user_id'];
	     $email = $session_data['z_tpimx_email'];
       $date = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
		 //var_dump($crs_status);
      // die;
		  
      $result = $this->model_sap->update_status_crs_to_canceled($user_id,$datetime,$crs_code,$remarks);
	  //  echo $result;
	 //  $this->model_sap->update_status_crs_to_reject($crs_code);
	       $result3 = $this->model_sap->list_email_reject($crs_code );
	   
	    $this->load->library('MY_phpmailer');
		foreach($result3 as $row){
	$to = $row['email'];
	$cc= $email;

		 $subject = "Rejected Customer Registration-".$crs_code;
		 $mailContent= "<h1>Rejected Customer Registration-".$crs_code."</h1>";
		  $mailContent.= "<table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, 	Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                   <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, We would like to inform you that Customer ".$crs_code." Registration has been Rejected. Kindly <a href='http://172.23.8.10/z_tpimx/index.php/Crs_edit_reg/editinput'>Click this link</a> to update.</p>
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
     // $mail->addAddress("medinat.raji@euro-mega.com", "Sales Admin");     // Add a recipient
	 // $mail->addCC("theresa.okon@euro-mega.com", "Sales Admin");// Add a recipient
	 $mail->addAddress($to, $to); 
	  $mail->addCC($cc, $cc);
      $mail->isHTML(true);

      $mail->Subject = $subject;
	 //$mailContent= "<h1>New customer registration</h1>";
	        
	  
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