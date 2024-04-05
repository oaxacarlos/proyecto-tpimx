<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** Error reporting */
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

      class Custexcel extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
	//$this->load->helper('file');

	$this->load->model('model_export');
  }  


    function index(){
  
	    $this->load->view('templates/navigation');
		$this->load->view('sap/export'); 
      }
	  
	public function send($to,$subject,$body,$altbody,$cc,$from_info,$attachment){
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
      $mail->addAddress("maximilliant.christo@euro-mega.com", "SAP SUPPORT"); 
		$mail->addCC("adeyokunnu.folasade@euro-mega.com", "Admin");// Add a recipient
      $mail->isHTML(true);

      $mail->Subject = $subject;
	  
	                      $mail->Body = $body;
      //$mail->AltBody = $altbody;
      //$mail->attach  = $attachment;
      $mail->addAttachment($attachment);

      if(!$mail->send()) {
          echo 'Message could not be sent.';
          echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
          echo 'Message has been sent';
      }
    }  
	  

  function action()
 {
  $this->load->model("model_export");
  $this->load->library("PHPExcel");


//$fileloc= base_url("/controllers/jobs/custexportfile/");

  $datetimeexcel = date('YmdHis');
// Create new PHPExcel object
  $objPHPExcel = new PHPExcel();

  // Set document properties
$objPHPExcel->getProperties()->setCreator("Euromega")
							 ->setLastModifiedBy("Euromega")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLS Test Document")
							 ->setDescription("Customer Registration Report")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Customer Registration Report");



//define border		
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
//----------

// start the excelll
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'D.CHANNEL')
            ->setCellValue('B1', 'DIVISION')
            ->setCellValue('C1', 'BUSINESS NAME')
			->setCellValue('D1', 'SEARCH NAME1')
			->setCellValue('E1', 'STREET')
			->setCellValue('F1', 'PUBLIC MARKET')
            ->setCellValue('G1', 'STATE ID')
			->setCellValue('H1', 'TEL')
			->setCellValue('I1', 'PHONE')
			->setCellValue('J1', 'EMAIL')
			->setCellValue('k1', 'REC NO')
            ->setCellValue('l1', 'LGA ID')
			->setCellValue('M1', 'TRANS ZONE')
			->setCellValue('N1', 'CP SURNAME')
			->setCellValue('O1', 'CP FIRSTNAME')
			->setCellValue('P1', 'PAYMENT TERM')
			->setCellValue('Q1', 'DISTRICT')
			->setCellValue('R1', 'SALES OFFICE')
			->setCellValue('S1', 'SALES GROUP')
			->setCellValue('T1', 'CUSTOMER GROUP')
			->setCellValue('U1', 'SUPPLY PLANT')
			->setCellValue('V1', 'INCOTERM')
		//	->setCellValue('W1', 'PERSONAL CODE')
			->setCellValue('W1', 'CREDIT LIMIT NONFOOD')
			->setCellValue('X1', 'CREDIT LIMIT FOOD');
			
			$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("F1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("G1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("H1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("I1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("J1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("k1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("L1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("M1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("N1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("O1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("P1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("Q1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("R1")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("S1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("T1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("U1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("V1")->getFont()->setBold(true);
		//	$objPHPExcel->getActiveSheet()->getStyle("W1")->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle("W1")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle("X1")->getFont()->setBold(true);
		    
// create border
$objPHPExcel->getActiveSheet()->getStyle('A1:X1')->applyFromArray($styleArray);

      
 $export_row = $this->model_export->exportList();
  $row = "A";

  $index_column = 2;

 foreach($export_row as $data){
  
  	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['channel_code']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['division_code']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['business_name']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['search_1']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['address']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['kw_market']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['state_code']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['telephone_1']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['mobile_phone']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['email']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['customer_rc_number']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['lga_id']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['transport_route']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['contact_person_lastname']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['contact_person_firstname']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['payment_term_code']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
		
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['sales_district_code']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['sales_office_code']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;

	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['sales_group_code']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['cg_code']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['plants_code']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['incoterm_code']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
			
 // $objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['personel_code']); 
//	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
//	$row++;
	
	 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['creditNFood']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
	
	 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($row.$index_column,$data['creditFood']); 
	$objPHPExcel->getActiveSheet()->getStyle($row.$index_column)->applyFromArray($styleArray); 
	$row++;
		
	$row = "A";
	$index_column++;
}
$datetimeexcel = date('YmdHis');

	// set width column			
$row_first = "A";
$row_last = "Z";
while($row_first!=$row_last){
	$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($row_first)->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension($row_first)->setWidth(20);
	$row_first++;
}
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Customer Registration');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 // ob_end_clean();
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  //header('Content-Type: application/vnd.ms-excel'); 
   // header('Content-Disposition: attachment;filename="Customer-Registration Data.xls"'); 
  //  header('Cache-Control: max-age=0'); 
    //$objWriter->save('php://output'); 
	 $objWriter->save(str_replace(__file__,'custexportfile/Customer-Registration-Data-'.$datetimeexcel.".xls",__file__));
	//exit;
$filename='custexportfile/Customer-Registration-Data-'.$datetimeexcel.".xls";
	 $this->load->library('MY_phpmailer');
	 $subject = "New Customer Registration-".$data['business_name'];
	 $mailContent= "<h1>New Customer Registration-".$data['business_name']."</h1>";
	                      $mailContent.= "<table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, 	Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>This email is to inform you regarding NEW CUSTOMER REGISTRATION ".$data['business_name']."</p>
                                  </td>
                                </tr>
                   </tbody>
             </table>";
		  $this->send($to,$subject,$mailContent,$altbody,$cc,$from_info,$filename);
}

 
}
?>