<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_PHPMailer {
    public function MY_PHPMailer() {
        //require_once('PHPMailer/class.phpmailer.php');
        //require 'PHPMailer/PHPMailerAutoload.php';

        //use PHPMailer\PHPMailer\PHPMailer;
    }

    //--------------------

    public function send($to,$subject,$body,$altbody,$cc,$from_info){

      require_once 'PHPMailer662/src/PHPMailer.php';
      require_once 'PHPMailer662/src/SMTP.php';
      require_once 'PHPMailer662/src/Exception.php';

      $mail = new PHPMailer\PHPMailer\PHPMailer();

      //$from = "info.noreply88@gmail.com";
      //$password = "uyetwemudjdmkwus";

      $from = "notification@toyopower.com";
      //$password = "Sing2023*";
      $password = "Sing@1234";

      //$from = "no-reply@tpi-mexico.com";
      //$password = "h#843pp^Z3bnx?a*";

      $mail->isSMTP();
      //$mail->Mailer = "smtp";                              // Set mailer to use SMTP
      //$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = $from;                 // SMTP username
      $mail->Password = $password;                          // SMTP password
      //$mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;
      $mail->setFrom($from, $from_info);
      $mail->addAddress($to, $to);     // Add a recipient
      //foreach($to as $row){
      //    $mail->addAddress($row, $row);     // Add a recipient
      //}
      $mail->isHTML(true);

      $mail->Subject = $subject;
      $mail->Body    = $body;
      //$mail->AltBody = $altbody;
      //$mail->attach  = $attachment;
      //$mail->addAttachment($attachment);

      // cc
      if(count($cc) > 0){
          foreach($cc as $row){
              $mail->AddCC($row);
          }
      }
      //---

      if(!$mail->send()) {
          //echo 'Message could not be sent.';
          //debug( 'Mailer Error: ' . $mail->ErrorInfo);
          $mail->ClearAllRecipients();
          //$mail->ClearAttachments();
          $mail->clearAddresses();
      } else {
          $mail->ClearAllRecipients();
          //$mail->ClearAttachments();
          $mail->clearAddresses();
      }
    }
    //----------------------------

    public function send2($to,$subject,$body,$altbody,$cc,$from_info, $attachment){

      require_once 'PHPMailer662/src/PHPMailer.php';
      require_once 'PHPMailer662/src/SMTP.php';
      require_once 'PHPMailer662/src/Exception.php';

      $mail = new PHPMailer\PHPMailer\PHPMailer();

      $from = "no-reply@tpi-mexico.com";
      $password = "h#843pp^Z3bnx?a*";

      //$from = "notification@toyopower.com";
      //$password = "Sing2023*";

      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = $from;                 // SMTP username
      $mail->Password = $password;                          // SMTP password
      //$mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;
      $mail->setFrom($from, $from_info);

      foreach($to as $row){
          $mail->addAddress($row, $row);     // Add a recipient
      }

      //$mail->addAddress($to, $to);     // Add a recipient
      $mail->isHTML(true);

      $mail->Subject = $subject;
      $mail->Body    = $body;
      //$mail->AltBody = $altbody;
      $mail->attach  = $attachment;
      $mail->addAttachment($attachment);

      // cc
      if(count($cc) > 0){
          foreach($cc as $row){
              $mail->AddCC($row);
          }
      }
      //---

      if(!$mail->send()) {
          //echo 'Message could not be sent.';
          //debug($mail->ErrorInfo);
          //echo 'Mailer Error: ' . $mail->ErrorInfo;
          $mail->ClearAllRecipients();
          //$mail->ClearAttachments();
          $mail->clearAddresses();
      } else {
          //echo 'Message has been sent';
          //debug("sent");
          $mail->ClearAllRecipients();
          //$mail->ClearAttachments();
          $mail->clearAddresses();
      }
    }
    //----------------------------

    function email_body_whship_submitnav($doc_no,$datetime,$remarks,$detail)
  	{
        $htmlContent = $this->header();
  			$htmlContent.= "
                    <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; max-width: 1000px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                      <tbody>
                        <tr>
                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>

                            <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Warehouse Shipment</h1>
                            <h4 class='text-center text-muted' style='color: #636c72; font-size: 24px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>".$doc_no."</h4>

                            <table class='hr' style='border: 0; border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 16px 0px;' width='100%'>
                                    <table style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; border-top: 1px solid #dddddd; font-size: 16px; line-height: 24px; margin: 0;' width='100%' height='1px'></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                              </tbody>
                            </table>


                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform the Warehouse Shipment <b>Ready to Post</b></p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the Warehouse Shipment below</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='alert alert-success' style='border: 0; border-collapse: separate !important; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin-bottom: 16px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'
                              width='100%'>
                              <tbody>
                                <tr>
                      						<td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>DateTime</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>".$datetime."</div>
                                  </td>
                                </tr>
          					           <tr>
          						            <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>Remarks</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>".$remarks."</div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  <![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>";

        $htmlContent.=$this->print_detail($detail,"qty_to_ship");
        $htmlContent.=$this->footer();
  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //----------------

    function email_body_whsreceipt_submitnav($doc_no,$datetime,$remarks, $detail)
  	{
  			$htmlContent = $this->header();
        $htmlContent.="
                    <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; max-width: 1000px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                      <tbody>
                        <tr>
                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>

                            <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Warehouse Receipt</h1>
                            <h4 class='text-center text-muted' style='color: #636c72; font-size: 24px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>".$doc_no."</h4>

                            <table class='hr' style='border: 0; border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 16px 0px;' width='100%'>
                                    <table style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; border-top: 1px solid #dddddd; font-size: 16px; line-height: 24px; margin: 0;' width='100%' height='1px'></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                              </tbody>
                            </table>


                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform the Warehouse Receipt <b>Ready to Post</b></p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the Warehouse Receipt below</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='alert alert-warning' style='border: 0; border-collapse: separate !important; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin-bottom: 16px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'
                              width='100%'>
                              <tbody>
                                <tr>
                      						<td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#FFA500'>
                                    <div>DateTime</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#FFA500'>
                                    <div>".$datetime."</div>
                                  </td>
                                </tr>
          					           <tr>
          						            <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#FFA500'>
                                    <div>Remarks</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#FFA500'>
                                    <div>".$remarks."</div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  <![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>";

        $htmlContent.=$this->print_detail($detail,"qty");
        $htmlContent.=$this->footer();
  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //----------------

    function email_body_whreceipt_not_process($data)
  	{
  			$htmlContent = $this->header();
        $htmlContent.="
                    <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; max-width: 600px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                      <tbody>
                        <tr>
                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>

                            <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Warehouse Receipt</h1>

                            <table class='hr' style='border: 0; border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 16px 0px;' width='100%'>
                                    <table style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; border-top: 1px solid #dddddd; font-size: 16px; line-height: 24px; margin: 0;' width='100%' height='1px'></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                              </tbody>
                            </table>


              <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                  <tbody>
                      <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform there are Warehouse Receipt haven't been proceed</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the Warehouse Receipt below</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='container bg-light p-3' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#FFA500' width='100%'>
                              <tbody>
                                <tr>
                                  <td align='center' style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 16px;'>
                                    <!--[if (gte mso 9)|(IE)]>
                                    <table align='center'>
                                      <tbody>
                                        <tr>
                                          <td width='600'>
                                  <![endif]-->
                                    <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; max-width: 600px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>

                                            <table class='row' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin-left: -15px; margin-right: -15px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; table-layout: fixed;' border='0' cellpadding='0'
                                              cellspacing='0' width='100%'>
                                              <thead>
                                                <tr>

                                                  <th class='col-lg-6' align='left' valign='top' style='font-size: 16px; font-weight: normal; line-height: 24px; margin: 0; min-height: 1px; padding-left: 15px; padding-right: 15px;' width='50%'>

                                                    <table class='card' style='border: 1px solid #dee2e6; border-collapse: separate !important; border-radius: 4px; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; overflow: hidden;' border='0'
                                                      cellpadding='0' cellspacing='0' bgcolor='#FFA500' width='100%'>
                                                      <tbody>
                                                        <tr>
                                                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;' width='100%'>
                                                            <div>
                                                              <table class='card-body' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; font-size:12px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='1' cellpadding='0' cellspacing='0' width='100%'>
                                                                <tbody>
                                                                  <tr style='margin-top:10px;'>
                                                                    <td style='padding:10px; text-align:center;'><b>No</b></td>
                                                                    <td style='padding:10px; text-align:center;'><b>DATE</b></td>
                                            										    <td style='padding: 10px; text-align:center;'><b>WHS RECEIPT NO</b></td>
                                            										    <td style='padding: 10px; text-align:center;'><b>WHS</b></td>
                                                                  </tr>";
                                    $i=1;
                                    foreach($data as $row){
                                          $htmlContent.= "<tr style='margin-top:10px;'>";
                                            $htmlContent.= "<td style='padding:10px;'>".$i."</td>";
                                            $htmlContent.= "<td style='padding:10px;'>".$row['posting_date']."</td>";
                                            $htmlContent.= "<td style='padding:10px;'>".$row['no']."</td>";
                                            $htmlContent.= "<td style='padding:10px;'>".$row['loc_code']."</td>";
                                          $htmlContent.= "</tr>";
                                          $i++;
                                    }

                        $htmlContent.="                        </tbody>
                                                            </table>

                                                            </div>
                                                          </td>
                                                        </tr>
                                                      </tbody>
                                                    </table>
                                                  </th>

                                                </tr>
                                              </thead>
                                            </table>


                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <!--[if (gte mso 9)|(IE)]>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  <![endif]-->
                                  </td>
                                </tr>
                              </tbody>
                            </table>";

        $htmlContent.=$this->footer();
  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //----------------


    function email_body_whshipment_not_process($data)
  	{
  			$htmlContent = $this->header();
        $htmlContent.="
                    <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; max-width: 600px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                      <tbody>
                        <tr>
                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>

                            <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Warehouse Shipment</h1>

                            <table class='hr' style='border: 0; border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 16px 0px;' width='100%'>
                                    <table style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; border-top: 1px solid #dddddd; font-size: 16px; line-height: 24px; margin: 0;' width='100%' height='1px'></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                              </tbody>
                            </table>


              <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                  <tbody>
                      <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform there are Warehouse Shipment haven't been proceed</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the Warehouse Shipment below</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='container bg-light p-3' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#afecbd' width='100%'>
                              <tbody>
                                <tr>
                                  <td align='center' style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 16px;'>
                                    <!--[if (gte mso 9)|(IE)]>
                                    <table align='center'>
                                      <tbody>
                                        <tr>
                                          <td width='600'>
                                  <![endif]-->
                                    <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; max-width: 600px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>

                                            <table class='row' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin-left: -15px; margin-right: -15px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; table-layout: fixed;' border='0' cellpadding='0'
                                              cellspacing='0' width='100%'>
                                              <thead>
                                                <tr>

                                                  <th class='col-lg-6' align='left' valign='top' style='font-size: 16px; font-weight: normal; line-height: 24px; margin: 0; min-height: 1px; padding-left: 15px; padding-right: 15px;' width='50%'>

                                                    <table class='card' style='border: 1px solid #dee2e6; border-collapse: separate !important; border-radius: 4px; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; overflow: hidden;' border='0'
                                                      cellpadding='0' cellspacing='0' bgcolor='#afecbd' width='100%'>
                                                      <tbody>
                                                        <tr>
                                                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;' width='100%'>
                                                            <div>
                                                              <table class='card-body' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; font-size:12px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='1' cellpadding='0' cellspacing='0' width='100%'>
                                                                <tbody>
                                                                  <tr style='margin-top:10px;'>
                                                                    <td style='padding:10px; text-align:center;'><b>No</b></td>
                                                                    <td style='padding:10px; text-align:center;'><b>DATE</b></td>
                                            										    <td style='padding: 10px; text-align:center;'><b>WHS SHIPMENT NO</b></td>
                                            										    <td style='padding: 10px; text-align:center;'><b>WHS</b></td>
                                                                  </tr>";
                                    $i=1;
                                    foreach($data as $row){
                                          $htmlContent.= "<tr style='margin-top:10px;'>";
                                            $htmlContent.= "<td style='padding:10px;'>".$i."</td>";
                                            $htmlContent.= "<td style='padding:10px;'>".$row['posting_date']."</td>";
                                            $htmlContent.= "<td style='padding:10px;'>".$row['no']."</td>";
                                            $htmlContent.= "<td style='padding:10px;'>".$row['loc_code']."</td>";
                                          $htmlContent.= "</tr>";
                                          $i++;
                                    }

                        $htmlContent.="                        </tbody>
                                                            </table>

                                                            </div>
                                                          </td>
                                                        </tr>
                                                      </tbody>
                                                    </table>
                                                  </th>

                                                </tr>
                                              </thead>
                                            </table>


                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <!--[if (gte mso 9)|(IE)]>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  <![endif]-->
                                  </td>
                                </tr>
                              </tbody>
                            </table>";

        $htmlContent.=$this->footer();
  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //----------------

    function footer(){
        $html = "<table style='margin-top:10px;' class='container bg-dark' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#343a40' width='100%'>
          <tbody>
            <tr>
              <td align='center' style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0px 16px 0 16px;'>
                <!--[if (gte mso 9)|(IE)]>
                <table align='center'>
                  <tbody>
                    <tr>
                      <td width='600'>
              <![endif]-->
                <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; max-width: 600px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                  <tbody>
                    <tr>
                      <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>

                        <div class='m-4' style='margin: 24px;'>
                          <div class='text-light text-center m-4 w-100' style='color: #f8f9fa; margin: 24px; text-align: center; width: 100%;'>
                            &#xA9;".date("Y")." TPI-MX
                          </div>
                        </div>


                      </td>
                    </tr>
                  </tbody>
                </table>
                <!--[if (gte mso 9)|(IE)]>
                      </td>
                    </tr>
                  </tbody>
                </table>
              <![endif]-->
              </td>
            </tr>
          </tbody>
        </table>";

        return $html;
    }
    //---

    function header(){
        $htmlContent= "<html><body>
        <body style='-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; Margin: 0; border: 0; box-sizing: border-box; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; height: 100%; line-height: 24px; margin: 0; min-width: 100%; outline: 0; padding: 0; width: 100%;'>
            <table class='container' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
              <tbody>
                <tr>
                  <td align='center' style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0px 16px 0 16px;'>
                    <!--[if (gte mso 9)|(IE)]>
                    <table align='center'>
                      <tbody>
                        <tr>
                          <td width='1000'>
                  <![endif]-->";

        return $htmlContent;
    }
    //---

    function email_body_whship_edit($doc_no,$datetime,$remarks, $detail)
  	{
        $htmlContent = $this->header();
  			$htmlContent.= "
                    <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; max-width: 800px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                      <tbody>
                        <tr>
                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>

                            <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Warehouse Shipment EDIT</h1>
                            <h4 class='text-center text-muted' style='color: #636c72; font-size: 24px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>".$doc_no."</h4>

                            <table class='hr' style='border: 0; border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 16px 0px;' width='100%'>
                                    <table style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; border-top: 1px solid #dddddd; font-size: 16px; line-height: 24px; margin: 0;' width='100%' height='1px'></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                              </tbody>
                            </table>


                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform the Warehouse Shipment <b>Edit</b></p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the Warehouse Shipment below</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='alert alert-info' style='border: 0; border-collapse: separate !important; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin-bottom: 16px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'
                              width='100%'>
                              <tbody>
                                <tr>
                      						<td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#5bc0eb'>
                                    <div>DateTime</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#5bc0eb'>
                                    <div>".$datetime."</div>
                                  </td>
                                </tr>
                                <tr>
          						            <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#5bc0eb'>
                                    <div>Remarks</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#5bc0eb'>
                                    <div>".$remarks."</div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>";

                  $htmlContent.="<table table class='alert alert-info' style='border: 0; border-collapse: separate !important; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin-bottom: 16px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='1' cellpadding='0' cellspacing='0'
                    width='100%'>";

                    $style_temp = "style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 12px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#5bc0eb'";

                    $htmlContent.="<tr>";
                      $htmlContent.="<td ".$style_temp."><b>Doc No Edited</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Line No Edited</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Item Code</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Desc</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Qty to Ship</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Qty Minus</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Qty Result</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>SO No</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Cust Code</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Cust Name</b></td>";
                    $htmlContent.="</tr>";
                    foreach($detail as $row){
                        $htmlContent.="<tr>";
                          $htmlContent.="<td ".$style_temp.">".$row["doc_no_edited"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["line_no_edited"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["item_code"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["description"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["qty_to_ship"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["qty_minus"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["qty_result"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["so_no"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["cust_code"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["cust_name"]."</td>";
                        $htmlContent.="</tr>";
                    }
                  $htmlContent.="</table>";

                  $htmlContent.="</td>
                        </tr>
                      </tbody>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  <![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>";

        $htmlContent.=$this->footer();
  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //----------------

    function email_body_whship_canceled($doc_no,$datetime,$remarks, $data)
  	{
        $htmlContent = $this->header();
  			$htmlContent.= "
                    <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; max-width: 600px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                      <tbody>
                        <tr>
                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>

                            <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Warehouse Shipment</h1>
                            <h4 class='text-center text-muted' style='color: #636c72; font-size: 24px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>".$doc_no."</h4>

                            <table class='hr' style='border: 0; border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 16px 0px;' width='100%'>
                                    <table style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; border-top: 1px solid #dddddd; font-size: 16px; line-height: 24px; margin: 0;' width='100%' height='1px'></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                              </tbody>
                            </table>


                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform the Warehouse Shipment <b>has been CANCELED</b></p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the Warehouse Shipment below</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='alert alert-success' style='border: 0; border-collapse: separate !important; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin-bottom: 16px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'
                              width='100%'>
                              <tbody>
                                <tr>
                      						<td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#eb9494'>
                                    <div>DateTime</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#eb9494'>
                                    <div>".$datetime."</div>
                                  </td>
                                </tr>
          					           <tr>
          						            <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#eb9494'>
                                    <div>Remarks</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#eb9494'>
                                    <div>".$remarks."</div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>";

                            $htmlContent.="<table table class='alert alert-info' style='border: 0; border-collapse: separate !important; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin-bottom: 16px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='1' cellpadding='0' cellspacing='0'
                              width='100%'>";

                              $style_temp = "style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 12px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#eb9494'";

                              $htmlContent.="<tr>";
                                $htmlContent.="<td ".$style_temp."><b>Doc No Canceled</b></td>";
                                $htmlContent.="<td ".$style_temp."><b>Line No</b></td>";
                                $htmlContent.="<td ".$style_temp."><b>Item Code</b></td>";
                                $htmlContent.="<td ".$style_temp."><b>Desc</b></td>";
                                $htmlContent.="<td ".$style_temp."><b>Qty to Ship</b></td>";
                                $htmlContent.="<td ".$style_temp."><b>SO No</b></td>";
                                $htmlContent.="<td ".$style_temp."><b>Cust Code</b></td>";
                                $htmlContent.="<td ".$style_temp."><b>Cust Name</b></td>";
                              $htmlContent.="</tr>";
                              foreach($data as $row){
                                  $htmlContent.="<tr>";
                                    $htmlContent.="<td ".$style_temp.">".$row["doc_no"]."</td>";
                                    $htmlContent.="<td ".$style_temp.">".$row["line_no"]."</td>";
                                    $htmlContent.="<td ".$style_temp.">".$row["item_code"]."</td>";
                                    $htmlContent.="<td ".$style_temp.">".$row["desc"]."</td>";
                                    $htmlContent.="<td ".$style_temp.">".$row["qty_to_ship"]."</td>";
                                    $htmlContent.="<td ".$style_temp.">".$row["so_no"]."</td>";
                                    $htmlContent.="<td ".$style_temp.">".$row["cust_code"]."</td>";
                                    $htmlContent.="<td ".$style_temp.">".$row["cust_name"]."</td>";
                                  $htmlContent.="</tr>";
                              }
                            $htmlContent.="</table>";

                    $htmlContent.="</td>
                        </tr>
                      </tbody>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  <![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>";

        $htmlContent.=$this->footer();
  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //----------------

    function print_detail($detail,$typee){

        $htmlContent = "";

        $htmlContent.="<table border='1' align='center' style='border-collapse: collapse; border-spacing: 2px; font-family: Helvetica, Arial, sans-serif; max-width: 1000px; mso-table-lspace: 1pt; mso-table-rspace: 1pt; margin-top:20px; padding-bottom:5px; font-size:12px;' border='1' cellpadding='1' cellspacing='1' width='100%'>";
        $htmlContent.="<tr>";
          $htmlContent.="<th>No</th>";
          $htmlContent.="<th>LineNo</th>";
          $htmlContent.="<th>Item</th>";
          $htmlContent.="<th>Desc</th>";
          $htmlContent.="<th>Src Doc</th>";
          $htmlContent.="<th>SrcLineNo</th>";
          $htmlContent.="<th>Qty</th>";
          $htmlContent.="<th>Customer</th>";
          $htmlContent.="<th>CS</th>";
        $htmlContent.="</tr>";

        $no = 1;
        $total = 0;
        foreach($detail as $row){
            $htmlContent.="<tr>";
              $htmlContent.="<td style='text-align:center;'>".$no."</td>";
              $htmlContent.="<td style='text-align:center;'>".$row["line_no"]."</td>";
              $htmlContent.="<td style='text-align:center;'>".$row["item_code"]."</td>";
              $htmlContent.="<td style='text-align:center;'>".$row["description"]."</td>";
              $htmlContent.="<td style='text-align:center;'>".$row["src_no"]."</td>";
              $htmlContent.="<td style='text-align:center;'>".$row["src_line_no"]."</td>";
              $htmlContent.="<td style='text-align:center;'>".$row[$typee]."</td>";
              $htmlContent.="<td style='text-align:center;'>".$row["bill_cust_name"]."</td>";
              $htmlContent.="<td style='text-align:center;'>".$row["cs_name"]."</td>";
            $htmlContent.="</tr>";
            $total += $row[$typee];
            $no++;
        }

        $htmlContent.="<tr><td colspan='6' style='text-align:right;'>TOTAL</td><td style='text-align:center;'>".$total."</td></tr>";

        $htmlContent.="</table>";

        return $htmlContent;
    }
    //--

    function email_body_whship_edit_from_picker($doc_no,$datetime,$remarks, $detail)
  	{
        $htmlContent = $this->header();
  			$htmlContent.= "
                    <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; max-width: 800px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                      <tbody>
                        <tr>
                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>

                            <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Warehouse Shipment EDIT</h1>
                            <h4 class='text-center text-muted' style='color: #636c72; font-size: 24px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>".$doc_no."</h4>

                            <table class='hr' style='border: 0; border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 16px 0px;' width='100%'>
                                    <table style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; border-top: 1px solid #dddddd; font-size: 16px; line-height: 24px; margin: 0;' width='100%' height='1px'></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </td>
                                </tr>
                              </tbody>
                            </table>


                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform the Warehouse Shipment <b>Edit</b></p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the Warehouse Shipment below</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='alert alert-info' style='border: 0; border-collapse: separate !important; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin-bottom: 16px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'
                              width='100%'>
                              <tbody>
                                <tr>
                      						<td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#ebbb5b'>
                                    <div>DateTime</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#ebbb5b'>
                                    <div>".$datetime."</div>
                                  </td>
                                </tr>
                                <tr>
          						            <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#ebbb5b'>
                                    <div>Remarks</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#ebbb5b'>
                                    <div>".$remarks."</div>
                                  </td>
                                </tr>
                              </tbody>
                            </table>";

                  $htmlContent.="<table table class='alert alert-info' style='border: 0; border-collapse: separate !important; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin-bottom: 16px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='1' cellpadding='0' cellspacing='0'
                    width='100%'>";

                    $style_temp = "style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 12px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#ebbb5b'";

                    $htmlContent.="<tr>";
                      $htmlContent.="<td ".$style_temp."><b>Doc No Edited</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Line No Edited</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Item Code</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Desc</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Qty to Ship</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Qty Minus</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Qty Result</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>SO No</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Cust Code</b></td>";
                      $htmlContent.="<td ".$style_temp."><b>Cust Name</b></td>";
                    $htmlContent.="</tr>";
                    foreach($detail as $row){
                        $htmlContent.="<tr>";
                          $htmlContent.="<td ".$style_temp.">".$row["doc_no_edited"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["line_no_edited"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["item_code"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["description"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["qty_to_ship"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["qty_minus"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["qty_result"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["so_no"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["cust_code"]."</td>";
                          $htmlContent.="<td ".$style_temp.">".$row["cust_name"]."</td>";
                        $htmlContent.="</tr>";
                    }
                  $htmlContent.="</table>";

                  $htmlContent.="</td>
                        </tr>
                      </tbody>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  <![endif]-->
                  </td>
                </tr>
              </tbody>
            </table>";

        $htmlContent.=$this->footer();
  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //----------------
}

?>
