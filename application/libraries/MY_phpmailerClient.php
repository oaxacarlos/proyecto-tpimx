<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_PHPMailerClient {
    public function MY_PHPMailer() {
        //require_once('PHPMailer/class.phpmailer.php');
        //require 'PHPMailer/PHPMailerAutoload.php';

        //use PHPMailer\PHPMailer\PHPMailer;
    }

    //--------------------

    public function send($to,$subject,$body,$altbody,$cc,$from_info, $from, $password, $host){

      require 'PHPMailer662/src/PHPMailer.php';
      require 'PHPMailer662/src/SMTP.php';
      require 'PHPMailer662/src/Exception.php';

      $mail = new PHPMailer\PHPMailer\PHPMailer();

      //$from = "notification@toyopower.com";
      //$password = "Sing1234";

      $mail->isSMTP();                                      // Set mailer to use SMTP
      //$mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup SMTP servers
      $mail->Host = $host;
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = $from;                 // SMTP username
      $mail->Password = $password;                          // SMTP password
      //$mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;
      $mail->setFrom($from, $from_info);
      $mail->addAddress($to, $to);     // Add a recipient
      $mail->isHTML(true);

      $mail->Subject = $subject;
      $mail->Body    = $body;
      //$mail->AltBody = $altbody;
      //$mail->attach  = $attachment;
      //$mail->addAttachment($attachment);

      // cc
      if(isset($cc)){
        if($cc!=""){
          if(count($cc) > 0){
              foreach($cc as $row){
                  $mail->AddCC($row);
              }
          }
        }

      }

      //---

      if(!$mail->send()) {
          //echo 'Message could not be sent.';
          echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
          //echo 'Message has been sent';
          return true;
      }

    }
    //----------------------------

    function contact(){
        $htmlContent = '
        <!-- contact section -->
        <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">

            <tr class="hide">
                <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
            </tr>
            <tr>
                <td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
            </tr>

            <tr>
                <td height="60" style="border-top: 1px solid #e0e0e0;font-size: 60px; line-height: 60px;">&nbsp;</td>
            </tr>

            <tr>
                <td align="center">
                    <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590 bg_color">

                        <tr>
                            <td>
                                <table border="0" width="300" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

                                    <tr>
                                        <!-- logo -->
                                        <td align="left">
                                            <a href="" style="display: block; border-style: none !important; border: 0 !important;"><img width="80" border="0" style="display: block; width: 80px;" src="'.base_url().'/assets/pic/logo2.png" alt="" /></a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td align="left" style="color: #888888; font-size: 14px; font-family: "Work Sans", Calibri, sans-serif; line-height: 23px;" class="text_color">
                                            <div style="color: #333333; font-size: 14px; font-family: "Work Sans", Calibri, sans-serif; font-weight: 600; mso-line-height-rule: exactly; line-height: 23px;">

                                                Email us: <br/> <a href="mailto:" style="color: #888888; font-size: 14px; font-family: "Hind Siliguri", Calibri, Sans-serif; font-weight: 400;">marketing@tpi-mexico.com</a>

                                            </div>
                                        </td>
                                    </tr>

                                </table>

                                <table border="0" width="2" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                    <tr>
                                        <td width="2" height="10" style="font-size: 10px; line-height: 10px;"></td>
                                    </tr>
                                </table>

                                <table border="0" width="200" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">

                                    <tr>
                                        <td class="hide" height="45" style="font-size: 45px; line-height: 45px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" align="right" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <a href="https://www.facebook.com/sakurafiltermexico" style="display: block; border-style: none !important; border: 0 !important;"><img width="24" border="0" style="display: block;" src="http://i.imgur.com/RBRORq1.png" alt=""></a>
                                                    </td>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td height="60" style="font-size: 60px; line-height: 60px;">&nbsp;</td>
            </tr>

        </table>
        <!-- end section -->
        ';

        return $htmlContent;
    }
    //--

    function footer2(){
        $htmlContent = '
        <!-- footer ====== -->
        <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="f4f4f4">

            <tr>
                <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
            </tr>

            <tr>
                <td align="center">

                    <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

                        <tr>
                            <td>
                                <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                    <tr>
                                        <td align="left" style="color: #aaaaaa; font-size: 14px; font-family: "Work Sans", Calibri, sans-serif; line-height: 24px;">
                                            <div style="line-height: 24px;">

                                                <span style="color: #333333;">TPI-MX</span>

                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <table border="0" align="left" width="5" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                    <tr>
                                        <td height="20" width="5" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                                    </tr>
                                </table>

                                <table border="0" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                </table>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>

            <tr>
                <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
            </tr>

        </table>
        <!-- end footer ====== -->
        ';

        return $htmlContent;
    }
    //--

    function header2(){
      $htmlContent = '
      <html xmlns:v="urn:schemas-microsoft-com:vml">
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
        <!--[if !mso]--><!-- -->
        <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,700" rel="stylesheet">
        <!-- <![endif]-->

        <title>TPI-MX</title>
        <style type="text/css">
          body {
              width: 100%;
              background-color: #ffffff;
              margin: 0;
              padding: 0;
              -webkit-font-smoothing: antialiased;
              mso-margin-top-alt: 0px;
              mso-margin-bottom-alt: 0px;
              mso-padding-alt: 0px 0px 0px 0px;
          }

          p,
          h1,
          h2,
          h3,
          h4 {
              margin-top: 0;
              margin-bottom: 0;
              padding-top: 0;
              padding-bottom: 0;
          }

          span.preheader {
              display: none;
              font-size: 1px;
          }

          html {
              width: 100%;
          }

          table {
              font-size: 14px;
              border: 0;
          }
          /* ----------- responsivity ----------- */

          @media only screen and (max-width: 640px) {
              /*------ top header ------ */
              .main-header {
                  font-size: 20px !important;
              }
              .main-section-header {
                  font-size: 28px !important;
              }
              .show {
                  display: block !important;
              }
              .hide {
                  display: none !important;
              }
              .align-center {
                  text-align: center !important;
              }
              .no-bg {
                  background: none !important;
              }
              /*----- main image -------*/
              .main-image img {
                  width: 440px !important;
                  height: auto !important;
              }
              /* ====== divider ====== */
              .divider img {
                  width: 440px !important;
              }
              /*-------- container --------*/
              .container590 {
                  width: 440px !important;
              }
              .container580 {
                  width: 400px !important;
              }
              .main-button {
                  width: 220px !important;
              }
              /*-------- secions ----------*/
              .section-img img {
                  width: 320px !important;
                  height: auto !important;
              }
              .team-img img {
                  width: 100% !important;
                  height: auto !important;
              }
          }

          @media only screen and (max-width: 479px) {
              /*------ top header ------ */
              .main-header {
                  font-size: 18px !important;
              }
              .main-section-header {
                  font-size: 26px !important;
              }
              /* ====== divider ====== */
              .divider img {
                  width: 280px !important;
              }
              /*-------- container --------*/
              .container590 {
                  width: 280px !important;
              }
              .container590 {
                  width: 280px !important;
              }
              .container580 {
                  width: 260px !important;
              }
              /*-------- secions ----------*/
              .section-img img {
                  width: 280px !important;
                  height: auto !important;
              }
          }
        </style>
        <!-- [if gte mso 9]><style type=”text/css”>
            body {
            font-family: arial, sans-serif!important;
            }
            </style>
        <![endif]-->
      </head>
      <body class="respond" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
          <!-- pre-header -->
          <table style="display:none!important;">
              <tr>
                  <td>
                      <div style="overflow:hidden;display:none;font-size:1px;color:#ffffff;line-height:1px;font-family:Arial;maxheight:0px;max-width:0px;opacity:0;">
                          TPI-MX
                      </div>
                  </td>
              </tr>
          </table>
          <!-- pre-header end -->
          <!-- header -->
          <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff">
              <tr>
                  <td align="center">
                      <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

                          <tr>
                              <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                          </tr>

                          <tr>
                              <td align="center">

                                  <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

                                      <tr>
                                          <td align="center" height="100" style="height:100px;">
                                              <a href="" style="display: block; border-style: none !important; border: 0 !important;"><img width="200" border="0" style="display: block; width: 200px;" src="'.base_url().'/assets/pic/logo2.png" alt="" /></a>
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>

                          <tr>
                              <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                          </tr>

                      </table>
                  </td>
              </tr>
          </table>
          <!-- end header -->
      ';

      return $htmlContent;
    }
    //---

    function email_registered($link, $txt_hi, $txt_name, $txt_remarks, $txt_verify){
        $htmlContent = $this->header2();

        $htmlContent.= '
              <!-- big image section -->
              <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">

                  <tr>
                      <td align="center">
                          <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                              <tr>

                                  <td align="center" class="section-img">
                                      <a href="" style=" border-style: none !important; display: block; border: 0 !important;"><img src="'.base_url().'/assets/pic/banner1.png" style="display: block; width: 590px;" width="590" border="0" alt="" /></a>
                                  </td>
                              </tr>
                              <tr>
                                  <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                              </tr>
                              <tr>
                                  <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="main-header">
                                      <div style="line-height: 35px">
                                          '.$txt_hi.' <span style="color: #5caad2;">'.$txt_name.'</span>
                                      </div>
                                  </td>
                              </tr>

                              <tr>
                                  <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                              </tr>

                              <tr>
                                  <td align="center">
                                      <table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                                          <tr>
                                              <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>

                              <tr>
                                  <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                              </tr>

                              <tr>
                                  <td align="center">
                                      <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container590">
                                          <tr>
                                              <td align="center" style="color: #888888; font-size: 16px; font-family: "Work Sans", Calibri, sans-serif; line-height: 24px;">
                                                  <div style="line-height: 24px">
                                                      '.$txt_remarks.'
                                                  </div>
                                              </td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>

                              <tr>
                                  <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                              </tr>
                              <tr>
                                  <td align="center">
                                      <table border="0" align="center" width="160" cellpadding="0" cellspacing="0" bgcolor="5caad2" style="">
                                          <tr>
                                              <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                          </tr>
                                          <tr>
                                              <td align="center" style="color: #ffffff; font-size: 14px; font-family: "Work Sans", Calibri, sans-serif; line-height: 26px;">
                                                  <div style="line-height: 26px;">
                                                      <a href="'.$link.'" style="color: #ffffff; text-decoration: none;">'.$txt_verify.'</a>
                                                  </div>
                                              </td>
                                          </tr>
                                          <tr>
                                              <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>
                          </table>

                      </td>
                  </tr>

              </table>
              <!-- end section -->';

              $htmlContent.= $this->contact();
              $htmlContent.= $this->footer2();

      $htmlContent.= '</body></html>';

      return $htmlContent;
    }
    //---

    function email_welcome($txt_hi, $txt_name, $txt_remarks, $link, $txt_login_now){
        $htmlContent = $this->header2();

        // content
        $htmlContent.= '
              <!-- big image section -->
              <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">

                  <tr>
                      <td align="center">
                          <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                              <tr>

                                  <td align="center" class="section-img">
                                      <a href="" style=" border-style: none !important; display: block; border: 0 !important;"><img src="'.base_url().'/assets/pic/welcome.jpg" style="display: block; width: 590px;" width="590" border="0" alt="" /></a>
                                  </td>
                              </tr>
                              <tr>
                                  <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                              </tr>
                              <tr>
                                  <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="main-header">
                                      <div style="line-height: 35px">
                                          '.$txt_hi.' <span style="color: #5caad2;">'.$txt_name.'</span>
                                      </div>
                                  </td>
                              </tr>

                              <tr>
                                  <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                              </tr>

                              <tr>
                                  <td align="center">
                                      <table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                                          <tr>
                                              <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>

                              <tr>
                                  <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                              </tr>

                              <tr>
                                  <td align="center">
                                      <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container590">
                                          <tr>
                                              <td align="center" style="color: #888888; font-size: 16px; font-family: "Work Sans", Calibri, sans-serif; line-height: 24px;">
                                                  <div style="line-height: 24px">
                                                      '.$txt_remarks.'
                                                  </div>
                                              </td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>

                              <tr>
                                  <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                              </tr>
                              <tr>
                                  <td align="center">
                                      <table border="0" align="center" width="160" cellpadding="0" cellspacing="0" bgcolor="5caad2" style="">
                                          <tr>
                                              <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                          </tr>
                                          <tr>
                                              <td align="center" style="color: #ffffff; font-size: 14px; font-family: "Work Sans", Calibri, sans-serif; line-height: 26px;">
                                                  <div style="line-height: 26px;">
                                                      <a href="'.$link.'" style="color: #ffffff; text-decoration: none;">'.$txt_login_now.'</a>
                                                  </div>
                                              </td>
                                          </tr>
                                          <tr>
                                              <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>
                          </table>

                      </td>
                  </tr>

              </table>
              <!-- end section -->';
        //--

        $htmlContent.= $this->contact();
        $htmlContent.= $this->footer2();
        $htmlContent.= '</body></html>';

        return $htmlContent;
    }
    //--

    function email_forgot_password($txt_hi, $txt_name, $txt_remarks, $link, $txt_button){
        $htmlContent = $this->header2();

        // content
        $htmlContent.= '
              <!-- big image section -->
              <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">

                  <tr>
                      <td align="center">
                          <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                              <tr>

                                  <td align="center" class="section-img">
                                      <a href="" style=" border-style: none !important; display: block; border: 0 !important;"><img src="'.base_url().'/assets/pic/forgotpass.jpg" style="display: block; width: 590px;" width="590" border="0" alt="" /></a>
                                  </td>
                              </tr>
                              <tr>
                                  <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                              </tr>
                              <tr>
                                  <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="main-header">
                                      <div style="line-height: 35px">
                                          '.$txt_hi.' <span style="color: #5caad2;">'.$txt_name.'</span>
                                      </div>
                                  </td>
                              </tr>

                              <tr>
                                  <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                              </tr>

                              <tr>
                                  <td align="center">
                                      <table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                                          <tr>
                                              <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>

                              <tr>
                                  <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                              </tr>

                              <tr>
                                  <td align="center">
                                      <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container590">
                                          <tr>
                                              <td align="center" style="color: #888888; font-size: 16px; font-family: "Work Sans", Calibri, sans-serif; line-height: 24px;">
                                                  <div style="line-height: 24px">
                                                      '.$txt_remarks.'
                                                  </div>
                                              </td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>

                              <tr>
                                  <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                              </tr>
                              <tr>
                                  <td align="center">
                                      <table border="0" align="center" width="160" cellpadding="0" cellspacing="0" bgcolor="5caad2" style="">
                                          <tr>
                                              <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                          </tr>
                                          <tr>
                                              <td align="center" style="color: #ffffff; font-size: 14px; font-family: "Work Sans", Calibri, sans-serif; line-height: 26px;">
                                                  <div style="line-height: 26px;">
                                                      <a href="'.$link.'" style="color: #ffffff; text-decoration: none;">'.$txt_button.'</a>
                                                  </div>
                                              </td>
                                          </tr>
                                          <tr>
                                              <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                          </tr>
                                      </table>
                                  </td>
                              </tr>
                          </table>

                      </td>
                  </tr>

              </table>
              <!-- end section -->';
        //--

        $htmlContent.= $this->contact();
        $htmlContent.= $this->footer2();
        $htmlContent.= '</body></html>';

        return $htmlContent;
    }
    //--

    function send_email($data){
  			$email_config = $this->_CI->model_config->get_email_detail(); // get email config
  			$result = $this->send($data["to"],$data["subject"], $data["body"], $data["altbody"], $data["cc"], $email_config["from_info"], $email_config["email_user"], $email_config["email_pass"], $email_config["email_host"]);
  			return $result;
  	}
  	//---

    function new_footer(){
        $htmlContent= '
													<table class="image_block block-8" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;padding-bottom:15px;">
																<div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/920893_905254/Buscanos%20en%20nuestras%20redes.svg" style="display: block; height: auto; border: 0; width: 360px; max-width: 100%;" width="360"></div>
															</td>
														</tr>
													</table>
            <table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
              <tbody>
                <tr>
                  <td>
                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
                      <tbody>
                        <tr>
                          <td class="column column-1" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                            <table class="heading_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                              <tr>
                                <td class="pad" style="text-align:center;width:100%;padding-top:5px;padding-bottom:5px;">
                                  <h1 style="margin: 0; color: #000000; direction: ltr; font-family: "Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif; font-size: 19px; font-weight: 700; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;"><span class="tinyMce-placeholder">SAKURA FILTERS</span></h1>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td class="column column-2" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                            <table class="heading_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                              <tr>
                                <td class="pad" style="text-align:center;width:100%;padding-top:5px;padding-bottom:5px;">
                                  <h1 style="margin: 0; color: #000000; direction: ltr; font-family: "Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif; font-size: 19px; font-weight: 700; letter-spacing: normal; line-height: 120%; text-align: center; margin-top: 0; margin-bottom: 0;"><span class="tinyMce-placeholder">TOYOPOWER</span></h1>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
              <tbody>
                <tr>
                  <td>
                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
                      <tbody>
                        <tr>
                          <td class="column column-1" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                            <table class="social_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                              <tr>
                                <td class="pad" style="text-align:center;padding-right:0px;padding-left:0px;padding-top:5px;padding-bottom:5px;">
                                  <div class="alignment" align="center">
                                    <table class="social-table" width="108px" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;">
                                      <tr>
                                        <td style="padding:0 2px 0 2px;"><a href="https://www.facebook.com/sakurafiltermexico" target="_blank"><img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/colored/facebook@2x.png" width="32" height="32" alt="Facebook" title="Sakura Filter Mexico Facebook" style="display: block; height: auto; border: 0;"></a></td>
                                        <td style="padding:0 2px 0 2px;"><a href="https://www.instagram.com/sakura_filter_mexico/" target="_blank"><img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/colored/instagram@2x.png" width="32" height="32" alt="Instagram" title="Sakura Filter Mexico Instagram" style="display: block; height: auto; border: 0;"></a></td>
                                        <td style="padding:0 2px 0 2px;"><a href="https://www.youtube.com/@sakurafiltermexico5218" target="_blank"><img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/colored/youtube@2x.png" width="32" height="32" alt="YouTube" title="Sakura Filter Mexico Youtube" style="display: block; height: auto; border: 0;"></a></td>
                                      </tr>
                                    </table>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td class="column column-2" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                            <table class="social_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                              <tr>
                                <td class="pad" style="text-align:center;padding-right:0px;padding-left:0px;padding-top:5px;padding-bottom:5px;">
                                  <div class="alignment" align="center">
                                    <table class="social-table" width="36px" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;">
                                      <tr>
                                        <td style="padding:0 2px 0 2px;"><a href="https://www.facebook.com/toyopowermexico" target="_blank"><img src="https://app-rsrc.getbee.io/public/resources/social-networks-icon-sets/colored/facebook@2x.png" width="32" height="32" alt="Facebook" title="Toyopower Facebook" style="display: block; height: auto; border: 0;"></a></td>
                                      </tr>
                                    </table>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="row row-12" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:"Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;font-size:10px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:12px;">
																	<p style="margin: 0; margin-bottom: 16px; font-size:10px;"><strong>Nota: </strong>El uso de la cuenta de usuario es personal e intransferible, por lo que el usuario no se encuentra facultado para ceder los datos de validación para acceder a la página.</p>
																	<p style="margin: 0; margin-bottom: 16px; font-size:10px;">El programa “Acumula puntos y canjea premios con Sakura Filters y Toyopower” es válido en los siguientes estados: Aguascalientes, Baja California, Baja California Sur, Campeche, Ciudad de México, Chiapas, Chihuahua, Coahuila de Zaragoza, Colima, Durango, Estado de México, Guanajuato, Guerrero, Hidalgo, Jalisco, Michoacán de Ocampo, Morelos, Nayarit, Nuevo León, Oaxaca, Puebla, Querétaro, Quintana Roo, San Luis Potosí, Sinaloa, Sonora, Tabasco, Tamaulipas, Tlaxcala, Veracruz, Yucatán y Zacatecas.</p>
																	<p style="margin: 0; margin-bottom: 16px; font-size:10px;"><strong>El usuario podrá acumular puntos de la siguiente manera:</strong></p>
																	<p style="margin: 0; margin-bottom: 16px; font-size:10px;">1. Mecánica base: Cuando el usuario compre un filtro de aire, aceite, separador, combustible, hidráulico, refrigerante, de transmisión y de cabina, en cualquiera de sus presentaciones, de la marca Sakura Filters, y/o bandas de transmisión de la marca Toyopower, se le acumularán puntos que podrán ser canjeados por premios.</p>
																	<p style="margin: 0; margin-bottom: 16px; font-size:10px;">2. Los puntos se otorgarán por cada filtro y banda comprada, dependiendo del valor que se le da a cada una, como se muestra en la tabla de nuestra página.</p>
																	<p style="margin: 0; margin-bottom: 16px; font-size:10px;">3. No hay límite de puntos acumulables por ticket, ni por día ni por mes. Se pueden acumular tantos puntos como se tengan.</p>
																	<p style="margin: 0; margin-bottom: 16px; font-size:10px;">4. El usuario podrá registrar cuantos tickets tenga y pueda a lo largo del día, siempre y cuando no sean mayores a los 30 días de su compra hasta su registro.</p>
																	<p style="margin: 0; margin-bottom: 16px; font-size:10px;">5. Los tickets que serán registrados en nuestra página serán sometidos a examinación por lo que podrán ser aprobados o rechazados según el caso (consultar en Validación y Rechazo en Tickets).</p>
																	<p style="margin: 0; margin-bottom: 16px; font-size:10px;">6. En caso de ser aprobados, se podrá visualizar la actualización de los puntos inmediatamente y, de tener suficientes puntos, se podrá hacer el canje de los premios.</p>
																	<p style="margin: 0; font-size:10px;">TPI IMPORTACIONES S.A. DE C.V. se reserva el derecho de, a discreción y sin notificación previa, excluir a usuarios de participar por violar los presentes términos y condiciones, violentar derechos de propiedad intelectual, derechos de autor o el intento de comprometer/violentar este programa de cualquier forma.</p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
          </td>
        </tr>
      </tbody>
    </table><!-- End -->
    </body>

    </html>';

    return $htmlContent;
    }

    function new_invoice_approved($link){
          $htmlContent='
          <!DOCTYPE html>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
	<!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
	<!--<![endif]-->
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		.desktop_hide,
		.desktop_hide table {
			mso-hide: all;
			display: none;
			max-height: 0px;
			overflow: hidden;
		}

		@media (max-width:620px) {

			.desktop_hide table.icons-inner,
			.social_block.desktop_hide .social-table {
				display: inline-block !important;
			}

			.icons-inner {
				text-align: center;
			}

			.icons-inner td {
				margin: 0 auto;
			}

			.image_block img.big,
			.row-content {
				width: 100% !important;
			}

			.mobile_hide {
				display: none;
			}

			.stack .column {
				width: 100%;
				display: block;
			}

			.mobile_hide {
				min-height: 0;
				max-height: 0;
				max-width: 0;
				overflow: hidden;
				font-size: 0px;
			}

			.desktop_hide,
			.desktop_hide table {
				display: table !important;
				max-height: none !important;
			}
		}
	</style>
</head>

<body style="background-color: transparent; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
	<table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: transparent;">
		<tbody>
			<tr>
				<td>
					<table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																<div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/920893_905254/banner%20top.png" style="display: block; height: auto; border: 0; width: 600px; max-width: 100%;" width="600"></div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:5px;">
																<div style="color:#101112;direction:ltr;font-family:"Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:16.8px;">
																	<p style="margin: 0;">¡Cada vez más cerca de canjear tus premios!</p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="icons_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="vertical-align: middle; color: #000000; font-family: inherit; font-size: 14px; text-align: center;">
																<table class="alignment" cellpadding="0" cellspacing="0" role="presentation" align="center" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
																	<tr>
																		<td style="vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px;"><img class="icon" src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/920893_905254/Approved.svg" alt height="128" width="186" align="center" style="display: block; height: auto; margin: 0 auto; border: 0;"></td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block" style="height:5px;line-height:5px;font-size:1px;">&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:"Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:16.8px;">
																	<p style="margin: 0;">Revisamos la información de tus productos y ¡nos complace informarte que fueron aprobados!</p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-5" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:"Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:16.8px;">
																	<p style="margin: 0; margin-bottom: 16px;">¡Gracias por registrar tus compras!</p>
																	<p style="margin: 0;">Puedes comprobar tus puntos en nuestra página.</p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block" style="height:1px;line-height:1px;font-size:1px;">&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-7" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																<div class="alignment" align="center" style="line-height:10px"><a href="'.$link.'" target="_blank" style="outline:none" tabindex="-1"><img src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/920893_905254/Balance%20de%20Puntos.svg" style="display: block; height: auto; border: 0; width: 210px; max-width: 100%;" width="210"></a></div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
          <table class="row row-8" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="divider_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad">
																<div class="alignment" align="center">
																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
																		<tr>
																			<td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 2px solid #DB0A0A;"><span>&#8202;</span></td>
																		</tr>
																	</table>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
          ';

          $htmlContent.= $this->new_footer();
          return $htmlContent;
    }
    //---

    function new_invoice_reject($link, $remark2){
        $htmlContent='
        <!DOCTYPE html>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
	<!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
	<!--<![endif]-->
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		.desktop_hide,
		.desktop_hide table {
			mso-hide: all;
			display: none;
			max-height: 0px;
			overflow: hidden;
		}

		@media (max-width:620px) {

			.desktop_hide table.icons-inner,
			.social_block.desktop_hide .social-table {
				display: inline-block !important;
			}

			.icons-inner {
				text-align: center;
			}

			.icons-inner td {
				margin: 0 auto;
			}

			.image_block img.big,
			.row-content {
				width: 100% !important;
			}

			.mobile_hide {
				display: none;
			}

			.stack .column {
				width: 100%;
				display: block;
			}

			.mobile_hide {
				min-height: 0;
				max-height: 0;
				max-width: 0;
				overflow: hidden;
				font-size: 0px;
			}

			.desktop_hide,
			.desktop_hide table {
				display: table !important;
				max-height: none !important;
			}
		}
	</style>
</head>

<body style="background-color: transparent; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
	<table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: transparent;">
		<tbody>
			<tr>
				<td>
					<table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																<div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/920893_905254/banner%20top.png" style="display: block; height: auto; border: 0; width: 600px; max-width: 100%;" width="600"></div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:5px;">
																<div style="color:#101112;direction:ltr;font-family:"Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:16.8px;">
																	<p style="margin: 0;">Necesitamos más información para procesar tu solicitud.</p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="icons_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="vertical-align: middle; color: #000000; font-family: inherit; font-size: 14px; text-align: center;">
																<table class="alignment" cellpadding="0" cellspacing="0" role="presentation" align="center" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
																	<tr>
																		<td style="vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px;"><img class="icon" src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/920893_905254/Rejected.svg" alt height="128" width="146" align="center" style="display: block; height: auto; margin: 0 auto; border: 0;"></td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block" style="height:5px;line-height:5px;font-size:1px;">&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:"Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:16.8px;">
                                  <p style="margin: 0; margin-bottom: 16px;">Razón : '.$remark2.'</p>
																	<p style="margin: 0; margin-bottom: 16px;">Revisamos la información de tus productos, sin embargo, hubo un error en la comprobación, por lo que hemos rechazado tu solicitud.</p>
																	<p style="margin: 0; margin-bottom: 16px;">Puedes volver a procesarla o, bien, ponerte en contacto con nosotros para solucionar tu solicitud.</p>
																	<p style="margin: 0; margin-bottom: 16px;">¡Gracias por registrar tus compras!</p>
																	<p style="margin: 0;">Puedes comprobar tus puntos en nuestra página.</p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-5" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block" style="height:1px;line-height:1px;font-size:1px;">&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																<div class="alignment" align="center" style="line-height:10px"><a href="'.$link.'" target="_blank" style="outline:none" tabindex="-1"><img src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/920893_905254/Balance%20de%20Puntos.svg" style="display: block; height: auto; border: 0; width: 210px; max-width: 100%;" width="210"></a></div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-7" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="divider_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad">
																<div class="alignment" align="center">
																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
																		<tr>
																			<td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 2px solid #DB0A0A;"><span>&#8202;</span></td>
																		</tr>
																	</table>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
        ';

        $htmlContent.= $this->new_footer();
        return $htmlContent;
    }
    //--

    function new_send_item($link, $pic, $desc){
        $htmlContent = '
        <!DOCTYPE html>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
	<!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
	<!--<![endif]-->
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		.desktop_hide,
		.desktop_hide table {
			mso-hide: all;
			display: none;
			max-height: 0px;
			overflow: hidden;
		}

		@media (max-width:620px) {

			.desktop_hide table.icons-inner,
			.social_block.desktop_hide .social-table {
				display: inline-block !important;
			}

			.icons-inner {
				text-align: center;
			}

			.icons-inner td {
				margin: 0 auto;
			}

			.image_block img.big,
			.row-content {
				width: 100% !important;
			}

			.mobile_hide {
				display: none;
			}

			.stack .column {
				width: 100%;
				display: block;
			}

			.mobile_hide {
				min-height: 0;
				max-height: 0;
				max-width: 0;
				overflow: hidden;
				font-size: 0px;
			}

			.desktop_hide,
			.desktop_hide table {
				display: table !important;
				max-height: none !important;
			}
		}
	</style>
</head>

<body style="background-color: transparent; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
	<table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: transparent;">
		<tbody>
			<tr>
				<td>
					<table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																<div class="alignment" align="center" style="line-height:10px"><img class="big" src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/920893_905254/banner%20top.png" style="display: block; height: auto; border: 0; width: 600px; max-width: 100%;" width="600"></div>
															</td>
														</tr>
													</table>
													<table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-bottom:5px;">
																<div style="color:#101112;direction:ltr;font-family:"Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;font-size:21px;font-weight:700;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:25.2px;">
																	<p style="margin: 0;">¡Ya falta muy poco!</p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:"Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:19.2px;">
																	<p style="margin: 0;">Tu producto ha sido enviado y pronto llegará a la dirección que nos proporcionaste.</p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;padding-top:5px;padding-bottom:5px;">
																<div class="alignment" align="center" style="line-height:10px"><img src="'.$pic.'" style="display: block; height: auto; border: 0; width: 157px; max-width: 100%;" width="157"></div>
															</td>
														</tr>
													</table>
												</td>
												<td class="column column-2" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad" style="padding-top:5px;padding-bottom:5px;">
																<div style="color:#101112;direction:ltr;font-family:"Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:19.2px;">
																	<p style="margin: 0;">'.$desc.'</p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block" style="height:5px;line-height:5px;font-size:1px;">&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-5" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="paragraph_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tr>
															<td class="pad">
																<div style="color:#101112;direction:ltr;font-family:"Montserrat", "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:16.8px;">
																	<p style="margin: 0; margin-bottom: 16px;">Si aún tienes más puntos, puedes seguir canjeando, ¡no hay límites!</p>
																	<p style="margin: 0;">Puedes comprobar tus puntos en nuestra página.</p>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<div class="spacer_block" style="height:1px;line-height:1px;font-size:1px;">&#8202;</div>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-7" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																<div class="alignment" align="center" style="line-height:10px"><a href="'.$link.'" target="_blank" style="outline:none" tabindex="-1"><img src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/920893_905254/Balance%20de%20Puntos.svg" style="display: block; height: auto; border: 0; width: 210px; max-width: 100%;" width="210"></a></div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="row row-8" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px;" width="600">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="divider_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tr>
															<td class="pad">
																<div class="alignment" align="center">
																	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
																		<tr>
																			<td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 2px solid #DB0A0A;"><span>&#8202;</span></td>
																		</tr>
																	</table>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
        ';

        $htmlContent.= $this->new_footer();
        return $htmlContent;
    }
    //--
}

?>
