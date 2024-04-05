<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


      class Crs_transport extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
	$this->load->model('model_transport');
	        $this->load->helper('url', 'form');

  }
  
  
  function index(){
  
	     
      }

function transport(){
	$this->load->view('templates/navigation');
	//$this->load->view('sap/v_transport',$data);	

 
	$this->load->model('model_transport','',TRUE);	
	$result =$this->model_transport->display_records();
	foreach($result as $row){
           $data['v_list_customers'][] = array(
                                     "customer_id" => $row['customer_id'],
									  "business_name" => $row['business_name'],
                                     "customer_name" => $row['customer_name'],
                                     "state" => $row['state'],
                                     "lga" => $row['lga'],
                                   //  "market_code" => $row['market_code'],
                                     );		
} 
$this->load->view('sap/v_transport',$data); 
}
	
	 function show_transport(){
      $this->load->model('model_transport','',TRUE);
     
      $result = $this->model_transport->list_all_transport_zone();
      foreach($result as $row){
         $data['v_list_transport_zone'][] = array(
                                   "transport_route" => $row['transport_route'],
                                   "transport_zone" => $row['transport_zone'],
                                   
                                   );
      }
      $this->load->view('sap/v_crs_request_transport',$data);
  }
	
	
/*	  function lga($deta=""){
	$this->load->model('model_transport','',TRUE);	
	$result =$this->model_transport->list_transport_bylga($deta);
		header('Content-Type: application/json');  // <-- header declaration
		echo json_encode($result, true);    // <--- encode
		exit();
  }
*/
	
 function update(){
	 

		$transport_route=$this->input->post('crs_transport');
		$customer_id=$this->input->post('crs_customerid');
	// var_dump($transport_route);
	// die(); model
		$this->load->model('model_transport','',TRUE);
        $data=$this->model_transport->update_transport_route($customer_id,$transport_route);
		header('Content-Type: application/json');
        echo json_encode($data);
		//$this->model_sap->update_status_crs_to_transport($customer_id);
	  $this->load->library('MY_phpmailer');
	  $subject = "New Customer Registration".$customer_id;
	  $mailContent= "<h1>New Customer Registration- ".$customer_id."</h1>";
	                      $mailContent.= "<table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, 	Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform you that New Customer with Registration number ".$customer_id." is awaiting processing.Kindly <a href='http://172.23.8.10/z_tpimx/index.php/jobs/Custexcel/'>click this link</a> to export.</p>
									   
                                  </td>
                                </tr>
                   </tbody>
             </table>";
		  $this->send($to,$subject,$mailContent,$altbody,$cc,$from_info);
    	
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
      $mail->addAddress("maximilliant.christo@euro-mega.com", "SAP SUPPORT");     // Add a recipient
	   $mail->addCC("raimun.suandi@euro-mega.com", "Sales Admin");// Add a recipient
      $mail->isHTML(true);

      $mail->Subject = $subject;
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
	  