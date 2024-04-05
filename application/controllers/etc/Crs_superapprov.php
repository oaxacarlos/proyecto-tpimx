<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crs_superapprov extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

 
  function index(){
  }
  
      function superapprv(){
      $this->load->view('templates/navigation');
	 $this->load->model('model_sap','',TRUE);	
	  $result = $this->model_sap->list_approval2();
	// if($result){
            foreach($result as $row){
               $data['v_list_crs_apprv'][] = array(
			                              "status_name" => $row['status_name'],
                                         "customer_id" => $row['customer_id'],
                                         "created_time" => $row['created_time'],
										 "business_name" => $row['business_name'],
                                         "customer_name" => $row['customer_name'],
                                         "created_at" => $row['created_at'],
                                       
                                         );
            }
          
        // else $data['v_list_crs_apprv'] = 0;
      // } 
	   $this->load->view('sap/v_crs_superapproval',$data);	
       }  
	
 function show_crs_detail(){
     $customer_id = $_POST['customer_id'];

     $this->load->model('model_sap','',TRUE);

      $result = $this->model_sap->list_approval_by_crs_code2($customer_id);
      if($result){
        foreach($result as $row){
           $data['v_list_crs_apprv_detail_h'][] = array(
                                     "customer_id" => $row['customer_id'],
                                     "customer_rc_number" => $row['customer_rc_number'],
                                     "customer_name" => $row['customer_name'],
									 "business_name" => $row['business_name'],
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
	  else $data['v_list_crs_apprv_detail_h'] = 0;
	 
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
                                         "status_code" => $row['status_code'],
										  "approval_date" => $row['approval_date'],
                                         "approval_datetime" => $row['approval_datetime'],
                                         "crs_approval_text1" => $row['crs_approval_text1'],
                                         "email" => $row['email'],
                                         "user_id" => $row['user_id'],
                                         "name" => $row['name'],
                                         "status_name" => $row['status_name'],
                                         );
            }
          }
     }
      else{
         $data['v_list_crs_apprv_detail_approval'] = 0;
      }

      $this->load->view('sap/v_crs_superapprovaldetails',$data);
	  
 }
 
 function file_download()
    {
        $file_name= $this->input->get('f');

        $this->load->helper('download');
        $data = file_get_contents($file_name);

        force_download($file_name, $data);
} 
	   

	    // Approved by Sales Head
    function approval2(){
      $crs_code       = $_POST['crs_code'];
      $remarks        = str_replace("'","",$_POST['remarks']);
	  
	   $this->load->model('model_sap','',TRUE);
	   
	    $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user_id = $session_data['z_tpimx_user_id'];
	  // var_dump($user_id);
			  // die;
              $crs_status = $this->model_sap->get_status_from_approval2_code();
			  $crs_approval_code = $this->model_sap->get_approval_level3();
			  // var_dump($crs_status);
			  // die;
			        // $this->model_sap->customer_id = $crs_code;
			// $this->model_sap->crs_approval_text1 = $remarks;
			// $this->model_sap->status_code = $crs_status;
			$data=$this->model_sap->crs_approval2($crs_code,$remarks,$crs_status,$user_id,$crs_approval_code);
			 
			
        $this->model_sap->update_status_crs_to_approval2($crs_code);
		
		 $this->load->library('MY_phpmailer');
		  $to = "isaac.olawoye@euro-mega.com";
		  	$cc= "medinat.raji@euro-mega.com";
		 $subject = "New Customer Registration-".$crs_code;
		 $mailContent.= "<table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, 	Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, We would like to inform you that New Customer with Registration Number ".$crs_code." is awaiting processing for transport route. Kindly <a href='http://172.23.8.10/z_tpimx/index.php/crs_transport/transport'>Click this link</a> to update.</p>
                                  </td>
                                </tr>
                   </tbody>
             </table>";
		  $this->send($to,$subject,$mailContent,$altbody,$cc,$from_info);
	
	}
    
	function crs_rejected2(){
      $crs_code = $_POST['crs_code'];
      $remarks = str_replace("'","",$_POST['remarks']);
      $this->load->model('model_sap','',TRUE);

      $session_data = $this->session->userdata('z_tpimx_logged_in');
      $user_id = $session_data['z_tpimx_user_id'];
       $date = date('Y-m-d');
      $datetime = date('Y-m-d H:i:s');
		 //var_dump($crs_status);
      // die;
		  
        $result = $this->model_sap->update_status_crs_to_canceled($user_id,$datetime,$crs_code,$remarks);
	  //  echo $result;
	  // $this->model_sap->update_status_crs_to_reject($crs_code);
	   $result3 = $this->model_sap->list_email_reject($crs_code );
	    	    $this->load->library('MY_phpmailer');
					foreach($result3 as $row){
				$to = $row['email'];
				$cc= "medinat.raji@euro-mega.com";
		 $subject = "Rejected Customer Registration-".$crs_code;
		  $mailContent.= "<table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, 	Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                   <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform you that  Customer ".$crs_code." Registration has been rejected. Kindly <a href='http://172.23.8.10/z_tpimx/index.php/Crs_edit_reg/editinput'>click this link</a> to update.</p>
								</tr>
									</tbody>
									</table>
								   <table>
									<tbody>
									</tr>
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
      //$mail->addAddress("isaac.olawoye@euro-mega.com", "Transport Manager");     // Add a recipient
	  //$mail->addCC("medinat.raji@euro-mega.com", "Sales Admin");// Add a recipient
	    $mail->addAddress($to, $to); 
		 $mail->addCC($cc, $cc);
      $mail->isHTML(true);

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