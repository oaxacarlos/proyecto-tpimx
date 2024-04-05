<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	<title>TPIMX-PORTAL</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/login1/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login1/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login1/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login1/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login1/vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login1/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login1/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login1/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login1/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login1/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login1/css/main.css">
<!--===============================================================================================-->
</head>
<body>

<div class="limiter">
<div class="container-login100">
<div class="wrap-login100">
  <div class="login100-form-title" style="background-image: url(<?php echo base_url(); ?>assets/login1/images/bg-01.jpg);">
    <span class="login100-form-title-1">
      TPI-MX<br>PORTAL
    </span>
  </div>
  <?php echo form_open('verify_login'); ?>
  <!--<form class="login100-form validate-form">-->
    <div class="wrap-input100 validate-input m-b-26" data-validate="Username is required" style="margin-top:20px;">
      <span class="label-input100">Username</span>
      <input class="input100" type="text" name="username" placeholder="Enter username">
      <span class="focus-input100"></span>
    </div>

    <div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
      <span class="label-input100">Password</span>
      <input class="input100" type="password" name="password" placeholder="Enter password">
      <span class="focus-input100"></span>
    </div>

    <?php echo validation_errors(); ?>
    <div class="container-login100-form-btn" style="margin-bottom:10px;">
      <button class="login100-form-btn" type="submit">
        Login
      </button>
    </div>
  </form>
</div>
</div>
</div>


            <!--===============================================================================================-->
  	<script src="<?php echo base_url(); ?>assets/login1/vendor/jquery/jquery-3.2.1.min.js"></script>
  <!--===============================================================================================-->
  	<script src="<?php echo base_url(); ?>assets/login1/vendor/animsition/js/animsition.min.js"></script>
  <!--===============================================================================================-->
  	<script src="<?php echo base_url(); ?>assets/login1/vendor/bootstrap/js/popper.js"></script>
  	<script src="<?php echo base_url(); ?>assets/login1/vendor/bootstrap/js/bootstrap.min.js"></script>
  <!--===============================================================================================-->
  	<script src="<?php echo base_url(); ?>assets/login1/vendor/select2/select2.min.js"></script>
  <!--===============================================================================================-->
  	<script src="<?php echo base_url(); ?>assets/login1/vendor/daterangepicker/moment.min.js"></script>
  	<script src="<?php echo base_url(); ?>assets/login1/vendor/daterangepicker/daterangepicker.js"></script>
  <!--===============================================================================================-->
  	<script src="<?php echo base_url(); ?>assets/login1/vendor/countdowntime/countdowntime.js"></script>
  <!--===============================================================================================-->
  	<script src="<?php echo base_url(); ?>assets/login1/js/main.js"></script>

        </body>

    </html>
