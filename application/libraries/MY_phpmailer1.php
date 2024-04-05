<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_PHPMailer {
    public function MY_PHPMailer() {
        //require_once('PHPMailer/class.phpmailer.php');
        require 'PHPMailer/PHPMailerAutoload.php';
    }

    //--------------------

    public function send($to,$subject,$body,$altbody,$cc,$from_info){
      $mail = new PHPMailer;

      $from = "noreply.info@euro-mega.com";
      $password = "Eurobi16";
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'mail.euro-mega.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = $from;                 // SMTP username
      $mail->Password = $password;                          // SMTP password
      //$mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
      $mail->SMTPSecure = 'ssl';                          // Enable TLS encryption, `ssl` also accepted	
      //$mail->SMTPSecure = '465';                          //Terakhir jalan pk cara pa, luk, port ttp d comment
      //$mail->Port = 587;     
      $mail->Port = 465;
      $mail->setFrom($from, $from_info);
      $mail->addAddress("maximilliant.christo@euro-mega.com", "lukieto@euro-mega.com");     // Add a recipient
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body    = $body;
      //$mail->AltBody = $altbody;
      //$mail->attach  = $attachment;
      //$mail->addAttachment($attachment);

      if(!$mail->send()) {
          //echo 'Message could not be sent.';
          echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
          //echo 'Message has been sent';
      }
    }
    //----------------------------

    function email_body_new_itr($itr_number,$remarks,$table_itr_h,$table_material)
  	{
  			$htmlContent = '<html><body>';

  			/*$htmlContent.= "<table width='600' border='0' align='center' cellpadding='0' cellspacing='0'>
  							  <tr>
  							    <td align='center' valign='top' bgcolor='#f1f69d' style='background-color:#f1f69d; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000000; padding:10px;'><table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-top:10px;'>
  							        <tr>
  							          <td align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#525252;'>
  							          <div style='font-size:28px;'><br>
  							            New ITR Request = ".$itr_number."
                            Remarks = ".$remarks."
                          </div>
  							            <div> <br>
  							              Dear Sir/Madam<br>
                              You have NEW ITR Request from your staff to review and approval<br>
                              Click the link below<br>
                              ".base_url()."
  							            </div></td>
  							        </tr>
  							      </table></td>
  							  <tr>
  							    <td align='right' valign='top' bgcolor='#478730' style='background-color:#478730;'><table width='100%' border='0' cellspacing='0' cellpadding='15'>
  							      <tr>
  							        <td align='right' valign='top' style='color:#ffffff; font-family:Arial, Helvetica, sans-serif; font-size:13px;'>
  										EUROMEGA<br>
  							       </tr>
  							    </table></td>
  							  </tr>
  							</table>";*/

$htmlContent.="<body style='-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; Margin: 0; border: 0; box-sizing: border-box; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; height: 100%; line-height: 24px; margin: 0; min-width: 100%; outline: 0; padding: 0; width: 100%;'>
            <table class='container' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
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

                            <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>New ITR Request</h1>
                            <h4 class='text-center text-muted' style='color: #636c72; font-size: 24px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>".$itr_number."</h4>

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
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform you have New ITR Request from your staff</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the ITR below</p>
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
                                    <div>".$table_itr_h["created_datetime"]."</div>
                                  </td>
                                </tr>
                    					  <tr>
          						            <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>Requestor</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>".$table_itr_h["name"]."</div>
                                  </td>
                               </tr>
                  					   <tr>
                    					 	 <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>Depart</div>
                                 </td>
                                 <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>".$table_itr_h["depart_name"]."</div>
                                 </td>
                               </tr>
                               <tr>
            						          <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>Depot</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>".$table_itr_h["plant_code"]." - ".$table_itr_h["plant_name"]."</div>
                                  </td>
                               </tr>
                               <tr>
          						            <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>Status</div>
                                  </td>
                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                    <div>".$table_itr_h["itr_status_name"]."</div>
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
            </table>


            <table class='container bg-light p-3' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#f8f9fa' width='100%'>
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
                                      cellpadding='0' cellspacing='0' bgcolor='#ffffff' width='100%'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;' width='100%'>
                                            <div>
                                              <table class='card-body' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; font-size:12px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                <tbody>
                                                  <tr style='margin-top:10px;'>
                                                    <td style='padding:10px; text-align:left;'>MATID</td>
                            										    <td style='padding: 10px; text-align:left;'>MATDESC</td>
                            										    <td style='padding: 10px; text-align:right;'>QTY</td>
                            										    <td style='padding: 10px; text-align:right;'>UOM</td>
                                                  </tr>";

                    foreach($table_material as $row){
                          $htmlContent.= "<tr style='margin-top:10px;'>";
                            $htmlContent.= "<td style='padding:10px;'>".$row['MATNR']."</td>";
                            $htmlContent.= "<td style='padding:10px;'>".$row['MATDESC']."</td>";
                            $htmlContent.= "<td style='padding:10px; text-align:right;'>".$row['ERFMG']."</td>";
                            $htmlContent.= "<td style='padding:10px; text-align:right;'>".$row['ERFME']."</td>";
                          $htmlContent.= "</tr>";
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
            </table>

            <h4 class='text-center text-muted' style='color: #636c72; font-size: 15px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Click link below to access ITR PORTAL</h4>

            <table class='container' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
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

                            <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                              <tbody>
                                <tr>
                                  <td align='center' style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>
                                    <div class='m-3' style='margin: 16px;'>
                                      <div class='align-center m-3' style='margin: 16px;'>
                                        <table class='btn btn-secondary btn-lg p-3 ml-2' align='left' style='border-collapse: separate !important; border-radius: 4px; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin: 0px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                          border='0' cellpadding='0' cellspacing='0'>
                                          <tbody>
                                            <tr>
                                              <td style='border-collapse: collapse; border-radius: 4px; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 10px;' bgcolor='#868e96'>
                                                <a href='".base_url()."' style='background-color: #868e96; border: 1px solid #e9703e; border-color: #868e96; border-radius: 4.8px; color: #ffffff; display: inline-block; font-family: Helvetica, Arial, sans-serif; font-size: 20px; font-weight: normal; line-height: 30px; padding: 0px 0px; text-align: center; text-decoration: none; white-space: nowrap;'>LINK</a>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>

                                      </div>
                                    </div>

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
            </table>


            <table style='margin-top:10px;' class='container bg-dark' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#343a40' width='100%'>
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
                                &#xA9;".date("Y")." EUROMEGA
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

  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //----------------

    function email_body_approval_itr($itr_number,$remarks,$table_itr_h,$table_material)
  	{
  			$htmlContent = '<html><body>';

  		/*	$htmlContent.= "<table width='600' border='0' align='center' cellpadding='0' cellspacing='0'>
  							  <tr>
  							    <td align='center' valign='top' bgcolor='#f1f69d' style='background-color:#f1f69d; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000000; padding:10px;'><table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-top:10px;'>
  							        <tr>
  							          <td align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#525252;'>
  							          <div style='font-size:28px;'><br>
  							            ITR Request = ".$itr_number."<br>
                            Remarks = ".$remarks."
                            </div>
  							            <div> <br>
  							              Dear Sir/Madam<br>
                              The ITR Request need your approval<br>
                              Click the link below<br>
                              ".base_url()."
  							            </div></td>
  							        </tr>
  							      </table></td>
  							  <tr>
  							    <td align='right' valign='top' bgcolor='#478730' style='background-color:#478730;'><table width='100%' border='0' cellspacing='0' cellpadding='15'>
  							      <tr>
  							        <td align='right' valign='top' style='color:#ffffff; font-family:Arial, Helvetica, sans-serif; font-size:13px;'>
  										EUROMEGA<br>
  							       </tr>
  							    </table></td>
  							  </tr>
  							</table>";
        */

        $htmlContent.="<body style='-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; Margin: 0; border: 0; box-sizing: border-box; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; height: 100%; line-height: 24px; margin: 0; min-width: 100%; outline: 0; padding: 0; width: 100%;'>
                    <table class='container' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
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

                                    <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>ITR Approval</h1>
                                    <h4 class='text-center text-muted' style='color: #636c72; font-size: 24px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>".$itr_number."</h4>

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
                                            <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform you have ITR Approval</p>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>

                                    <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                                      <tbody>
                                        <tr>
                                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                            <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the ITR below</p>
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
                                            <div>".$table_itr_h["created_datetime"]."</div>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                            <div>Requestor</div>
                                          </td>
                                          <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                            <div>".$table_itr_h["name"]."</div>
                                          </td>
                                       </tr>
                                       <tr>
                                         <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                            <div>Depart</div>
                                         </td>
                                         <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                            <div>".$table_itr_h["depart_name"]."</div>
                                         </td>
                                       </tr>
                                       <tr>
                                          <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                            <div>Depot</div>
                                          </td>
                                          <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                            <div>".$table_itr_h["plant_code"]." - ".$table_itr_h["plant_name"]."</div>
                                          </td>
                                       </tr>
                                       <tr>
                  						            <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                            <div>Status</div>
                                          </td>
                                          <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                            <div>".$table_itr_h["itr_status_name"]."</div>
                                          </td>
                                        </tr>
                                       <tr>
                                          <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                            <div>Approval DateTime</div>
                                          </td>
                                          <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                            <div>".$table_itr_h["approval_datetime"]."</div>
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
                    </table>


                    <table class='container bg-light p-3' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#f8f9fa' width='100%'>
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
                                              cellpadding='0' cellspacing='0' bgcolor='#ffffff' width='100%'>
                                              <tbody>
                                                <tr>
                                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;' width='100%'>
                                                    <div>
                                                      <table class='card-body' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; font-size:12px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                        <tbody>
                                                          <tr style='margin-top:10px;'>
                                                            <td style='padding:10px; text-align:left;'>MATID</td>
                                    										    <td style='padding: 10px; text-align:left;'>MATDESC</td>
                                    										    <td style='padding: 10px; text-align:right;'>QTY</td>
                                    										    <td style='padding: 10px; text-align:right;'>UOM</td>
                                                          </tr>";

                            foreach($table_material as $row){
                                  $htmlContent.= "<tr style='margin-top:10px;'>";
                                    $htmlContent.= "<td style='padding:10px;'>".$row['MATNR']."</td>";
                                    $htmlContent.= "<td style='padding:10px;'>".$row['MATDESC']."</td>";
                                    $htmlContent.= "<td style='padding:10px; text-align:right;'>".$row['ERFMG']."</td>";
                                    $htmlContent.= "<td style='padding:10px; text-align:right;'>".$row['ERFME']."</td>";
                                  $htmlContent.= "</tr>";
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
                    </table>

                    <h4 class='text-center text-muted' style='color: #636c72; font-size: 15px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Click link below to access ITR PORTAL</h4>

                    <table class='container' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
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

                                    <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                                      <tbody>
                                        <tr>
                                          <td align='center' style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>
                                            <div class='m-3' style='margin: 16px;'>
                                              <div class='align-center m-3' style='margin: 16px;'>
                                                <table class='btn btn-secondary btn-lg p-3 ml-2' align='left' style='border-collapse: separate !important; border-radius: 4px; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin: 0px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                  border='0' cellpadding='0' cellspacing='0'>
                                                  <tbody>
                                                    <tr>
                                                      <td style='border-collapse: collapse; border-radius: 4px; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 10px;' bgcolor='#868e96'>
                                                        <a href='".base_url()."' style='background-color: #868e96; border: 1px solid #e9703e; border-color: #868e96; border-radius: 4.8px; color: #ffffff; display: inline-block; font-family: Helvetica, Arial, sans-serif; font-size: 20px; font-weight: normal; line-height: 30px; padding: 0px 0px; text-align: center; text-decoration: none; white-space: nowrap;'>LINK</a>
                                                      </td>
                                                    </tr>
                                                  </tbody>
                                                </table>

                                              </div>
                                            </div>

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
                    </table>


                    <table style='margin-top:10px;' class='container bg-dark' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#343a40' width='100%'>
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
                                        &#xA9;".date("Y")." EUROMEGA
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

  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //-----------------------

    function email_body_approval_sap_itr($itr_number,$reservation_number,$remarks,$table_itr_h,$table_material)
  	{
  			$htmlContent = '<html><body>';

  			/*$htmlContent.= "<table width='600' border='0' align='center' cellpadding='0' cellspacing='0'>
  							  <tr>
  							    <td align='center' valign='top' bgcolor='#f1f69d' style='background-color:#f1f69d; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000000; padding:10px;'><table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-top:10px;'>
  							        <tr>
  							          <td align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#525252;'>
  							          <div style='font-size:28px;'><br>
  							            ITR Request = ".$itr_number."<br>
                            Reservation Number = ".$reservation_number."
                            </div>
  							            <div> <br>
  							              Dear Sir/Madam<br>
                              We would like to inform you, the ITR Number already approved by Management and then sent to SAP for Reservation<br>
                              <br>
  							            </div></td>
  							        </tr>
  							      </table></td>
  							  <tr>
  							    <td align='right' valign='top' bgcolor='#478730' style='background-color:#478730;'><table width='100%' border='0' cellspacing='0' cellpadding='15'>
  							      <tr>
  							        <td align='right' valign='top' style='color:#ffffff; font-family:Arial, Helvetica, sans-serif; font-size:13px;'>
  										EUROMEGA<br>
  							       </tr>
  							    </table></td>
  							  </tr>
  							</table>";
                */

                $htmlContent.="<body style='-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; Margin: 0; border: 0; box-sizing: border-box; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; height: 100%; line-height: 24px; margin: 0; min-width: 100%; outline: 0; padding: 0; width: 100%;'>
                            <table class='container' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
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

                                            <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>ITR Approval</h1>
                                            <h4 class='text-center text-muted' style='color: #636c72; font-size: 24px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>".$itr_number."</h4>

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
                                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, We would like to inform you, the ITR Number already approved by Management and then sent to SAP for Reservation</p>
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>

                                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                                              <tbody>
                                                <tr>
                                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the ITR below</p>
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
                                                    <div>".$table_itr_h["created_datetime"]."</div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>Requestor</div>
                                                  </td>
                                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>".$table_itr_h["name"]."</div>
                                                  </td>
                                               </tr>
                                               <tr>
                                                 <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>Depart</div>
                                                 </td>
                                                 <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>".$table_itr_h["depart_name"]."</div>
                                                 </td>
                                               </tr>
                                               <tr>
                                                  <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>Depot</div>
                                                  </td>
                                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>".$table_itr_h["plant_code"]." - ".$table_itr_h["plant_name"]."</div>
                                                  </td>
                                               </tr>
                                               <tr>
                          						            <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>Status</div>
                                                  </td>
                                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>".$table_itr_h["itr_status_name"]."</div>
                                                  </td>
                                                </tr>
                                               <tr>
                                                  <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>Approval DateTime</div>
                                                  </td>
                                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>".$table_itr_h["approval_datetime"]."</div>
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
                                                <tr>
                                                   <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                     <div>Reserv No</div>
                                                   </td>
                                                   <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                     <div>".$reservation_number."</div>
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
                            </table>


                            <table class='container bg-light p-3' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#f8f9fa' width='100%'>
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
                                                      cellpadding='0' cellspacing='0' bgcolor='#ffffff' width='100%'>
                                                      <tbody>
                                                        <tr>
                                                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;' width='100%'>
                                                            <div>
                                                              <table class='card-body' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; font-size:12px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                                <tbody>
                                                                  <tr style='margin-top:10px;'>
                                                                    <td style='padding:10px; text-align:left;'>MATID</td>
                                                                    <td style='padding: 10px; text-align:left;'>MATDESC</td>
                                                                    <td style='padding: 10px; text-align:right;'>QTY</td>
                                                                    <td style='padding: 10px; text-align:right;'>UOM</td>
                                                                  </tr>";

                                    foreach($table_material as $row){
                                          $htmlContent.= "<tr style='margin-top:10px;'>";
                                            $htmlContent.= "<td style='padding:10px;'>".$row['MATNR']."</td>";
                                            $htmlContent.= "<td style='padding:10px;'>".$row['MATDESC']."</td>";
                                            $htmlContent.= "<td style='padding:10px; text-align:right;'>".$row['ERFMG']."</td>";
                                            $htmlContent.= "<td style='padding:10px; text-align:right;'>".$row['ERFME']."</td>";
                                          $htmlContent.= "</tr>";
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
                            </table>

                            <h4 class='text-center text-muted' style='color: #636c72; font-size: 15px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Click link below to access ITR PORTAL</h4>

                            <table class='container' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
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

                                            <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                                              <tbody>
                                                <tr>
                                                  <td align='center' style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>
                                                    <div class='m-3' style='margin: 16px;'>
                                                      <div class='align-center m-3' style='margin: 16px;'>
                                                        <table class='btn btn-secondary btn-lg p-3 ml-2' align='left' style='border-collapse: separate !important; border-radius: 4px; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin: 0px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                          border='0' cellpadding='0' cellspacing='0'>
                                                          <tbody>
                                                            <tr>
                                                              <td style='border-collapse: collapse; border-radius: 4px; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 10px;' bgcolor='#868e96'>
                                                                <a href='".base_url()."' style='background-color: #868e96; border: 1px solid #e9703e; border-color: #868e96; border-radius: 4.8px; color: #ffffff; display: inline-block; font-family: Helvetica, Arial, sans-serif; font-size: 20px; font-weight: normal; line-height: 30px; padding: 0px 0px; text-align: center; text-decoration: none; white-space: nowrap;'>LINK</a>
                                                              </td>
                                                            </tr>
                                                          </tbody>
                                                        </table>

                                                      </div>
                                                    </div>

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
                            </table>


                            <table style='margin-top:10px;' class='container bg-dark' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#343a40' width='100%'>
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
                                                &#xA9;".date("Y")." EUROMEGA
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

  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //----------------------

    function email_body_reject_itr($itr_number,$remarks,$table_itr_h,$table_material)
  	{
  			$htmlContent = '<html><body>';

  		/*	$htmlContent.= "<table width='600' border='0' align='center' cellpadding='0' cellspacing='0'>
  							  <tr>
  							    <td align='center' valign='top' bgcolor='#f1f69d' style='background-color:#f1f69d; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000000; padding:10px;'><table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-top:10px;'>
  							        <tr>
  							          <td align='left' valign='top' style='font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#525252;'>
  							          <div style='font-size:28px;'><br>
  							            ITR Request = ".$itr_number."</div>
  							            <div> <br>
  							              Dear Sir/Madam<br>
                              Sorry to inform your ITR has been Rejected, You can see the Reason in below<br>
                              Reason = ".$remarks."<br>
  							            </div></td>
  							        </tr>
  							      </table></td>
  							  <tr>
  							    <td align='right' valign='top' bgcolor='#478730' style='background-color:#478730;'><table width='100%' border='0' cellspacing='0' cellpadding='15'>
  							      <tr>
  							        <td align='right' valign='top' style='color:#ffffff; font-family:Arial, Helvetica, sans-serif; font-size:13px;'>
  										EUROMEGA<br>
  							       </tr>
  							    </table></td>
  							  </tr>
  							</table>";
                */

                $htmlContent.="<body style='-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; Margin: 0; border: 0; box-sizing: border-box; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; height: 100%; line-height: 24px; margin: 0; min-width: 100%; outline: 0; padding: 0; width: 100%;'>
                            <table class='container' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
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

                                            <h1 class='text-center' style='color: inherit; font-size: 36px; font-weight: 500; line-height: 39.6px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>ITR Rejected</h1>
                                            <h4 class='text-center text-muted' style='color: #636c72; font-size: 24px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>".$itr_number."</h4>

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
                                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>Dear Sir/Madam, we would like to inform your ITR Request has been rejected</p>
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>

                                            <table class='p' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                                              <tbody>
                                                <tr>
                                                  <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 0 0 20px 0;'>
                                                    <p style='font-size: 16px; line-height: 24px; margin: 0; padding: 0;'>You can see the information of the ITR below</p>
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
                                                    <div>".$table_itr_h["created_datetime"]."</div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>Requestor</div>
                                                  </td>
                                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>".$table_itr_h["name"]."</div>
                                                  </td>
                                               </tr>
                                               <tr>
                                                 <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>Depart</div>
                                                 </td>
                                                 <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>".$table_itr_h["depart_name"]."</div>
                                                 </td>
                                               </tr>
                                               <tr>
                                                  <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>Depot</div>
                                                  </td>
                                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>".$table_itr_h["plant_code"]." - ".$table_itr_h["plant_name"]."</div>
                                                  </td>
                                               </tr>
                                               <tr>
                          						            <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>Status</div>
                                                  </td>
                                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>".$table_itr_h["itr_status_name"]."</div>
                                                  </td>
                                                </tr>
                                               <tr>
                                                  <td style='width:150px; border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>Rejected DateTime</div>
                                                  </td>
                                                  <td style='border: 1px solid transparent; border-collapse: collapse; border-color: #9be7ac; border-radius: 4px; border-spacing: 0px; color: #0a2c12; font-size: 16px; line-height: 24px; margin: 0; padding: 12px 20px;' bgcolor='#afecbd'>
                                                    <div>".$table_itr_h["rejected_datetime"]."</div>
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
                            </table>


                            <table class='container bg-light p-3' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#f8f9fa' width='100%'>
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
                                                      cellpadding='0' cellspacing='0' bgcolor='#ffffff' width='100%'>
                                                      <tbody>
                                                        <tr>
                                                          <td style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;' width='100%'>
                                                            <div>
                                                              <table class='card-body' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; font-size:12px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                                <tbody>
                                                                  <tr style='margin-top:10px;'>
                                                                    <td style='padding:10px; text-align:left;'>MATID</td>
                                            										    <td style='padding: 10px; text-align:left;'>MATDESC</td>
                                            										    <td style='padding: 10px; text-align:right;'>QTY</td>
                                            										    <td style='padding: 10px; text-align:right;'>UOM</td>
                                                                  </tr>";

                                    foreach($table_material as $row){
                                          $htmlContent.= "<tr style='margin-top:10px;'>";
                                            $htmlContent.= "<td style='padding:10px;'>".$row['MATNR']."</td>";
                                            $htmlContent.= "<td style='padding:10px;'>".$row['MATDESC']."</td>";
                                            $htmlContent.= "<td style='padding:10px; text-align:right;'>".$row['ERFMG']."</td>";
                                            $htmlContent.= "<td style='padding:10px; text-align:right;'>".$row['ERFME']."</td>";
                                          $htmlContent.= "</tr>";
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
                            </table>

                            <h4 class='text-center text-muted' style='color: #636c72; font-size: 15px; font-weight: 500; line-height: 26.4px; margin-bottom: 8px; margin-top: 0; text-align: center; vertical-align: baseline;'>Click link below to access ITR PORTAL</h4>

                            <table class='container' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' width='100%'>
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

                                            <table align='center' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0'>
                                              <tbody>
                                                <tr>
                                                  <td align='center' style='border-collapse: collapse; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0;'>
                                                    <div class='m-3' style='margin: 16px;'>
                                                      <div class='align-center m-3' style='margin: 16px;'>
                                                        <table class='btn btn-secondary btn-lg p-3 ml-2' align='left' style='border-collapse: separate !important; border-radius: 4px; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; margin: 0px; mso-table-lspace: 0pt; mso-table-rspace: 0pt;'
                                                          border='0' cellpadding='0' cellspacing='0'>
                                                          <tbody>
                                                            <tr>
                                                              <td style='border-collapse: collapse; border-radius: 4px; border-spacing: 0px; font-size: 16px; line-height: 24px; margin: 0; padding: 10px;' bgcolor='#868e96'>
                                                                <a href='".base_url()."' style='background-color: #868e96; border: 1px solid #e9703e; border-color: #868e96; border-radius: 4.8px; color: #ffffff; display: inline-block; font-family: Helvetica, Arial, sans-serif; font-size: 20px; font-weight: normal; line-height: 30px; padding: 0px 0px; text-align: center; text-decoration: none; white-space: nowrap;'>LINK</a>
                                                              </td>
                                                            </tr>
                                                          </tbody>
                                                        </table>

                                                      </div>
                                                    </div>

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
                            </table>


                            <table style='margin-top:10px;' class='container bg-dark' style='border-collapse: collapse; border-spacing: 0px; font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt;' border='0' cellpadding='0' cellspacing='0' bgcolor='#343a40' width='100%'>
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
                                                &#xA9;".date("Y")." EUROMEGA
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


  			$htmlContent.= '</body></html>';
  			return $htmlContent;
  	}
    //-----------------------
}


?>
