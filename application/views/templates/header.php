<?php

include("php_header.php");
include("initial.php");

?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title><?php echo $website_title; ?></title>

    <link type="text/css" href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="stylesheet" />
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>assets/bootstrap4/css/bootstrap431.min.css" rel="stylesheet" type="text/css" />
    <link type="text/css" href="<?php echo base_url();?>assets/css/jquery.datetimepicker.min.css" rel="stylesheet" />
    <link href="<?php echo base_url();?>assets/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <link type="text/css" href="<?php echo base_url();?>assets/datatables/media/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo base_url();?>assets/datatables/media/css/buttons.bootstrap4.min.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo base_url();?>assets/css/loader.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo base_url();?>assets/noty/animate.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo base_url();?>assets/noty/button.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo base_url();?>assets/noty/font-awesome/css/font-awesome.min.css" rel="stylesheet" />

    <link type="text/css" href="<?php echo base_url();?>assets/datatables/media/css/select.dataTables.min.css" rel="stylesheet" />

    <script src="<?php echo base_url();?>assets/bootstrap4/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/bootstrap4/js/popper.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/sweetalert2/sweetalert2.all.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/js/jquery-1.12.4.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/js/jquery-ui.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/datatables/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/datatables/media/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/bootstrap4/js/bootstrap431.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/js/jquery.datetimepicker.full.min.js" type="text/javascript"></script>

    <script src="<?php echo base_url();?>assets/datatables/media/js/buttons.flash.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatables/media/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatables/media/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatables/media/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatables/media/js/jszip.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatables/media/js/pdfmake.min.js"></script>
    <script src="<?php echo base_url();?>assets/datatables/media/js/vfs_fonts.js"></script>
    <script src="<?php echo base_url();?>assets/datatables/media/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url();?>assets/popover/src/jquery.webui-popover.js"></script>
    <script src="<?php echo base_url();?>assets/datatables/media/js/dataTables.select.min.js"></script>

    <script src="<?php echo base_url();?>assets/js/pace.min.js" type="text/javascript"></script>
    <!--<script src="<?php //echo base_url();?>assets/table2excel/jquery.table2excel.min.js"></script>-->
    <script src="<?php echo base_url();?>assets/js/table2excel.js"></script>

	  <script src="<?php echo base_url();?>assets/jsPDF/jspdf.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/jsPDF/plugins/from_html.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/jsPDF/plugins/split_text_to_size.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/jsPDF/plugins/standard_fonts_metrics.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/jsPDF/dist/jspdf.debug.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/js/html2canvas.js" type="text/javascript" type="text/javascript"></script>

    <script type="text/javascript" src="<?php echo base_url();?>assets/noty/packaged/jquery.noty.packaged.js"></script>
    <!--<script type="text/javascript" src="<?php echo base_url();?>assets/noty/notification_html.js"></script>-->

  	<script type="text/javascript" src="<?php echo base_url();?>assets/js/printThis.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/clipboard.min.js"></script>

    <script src="<?php echo base_url();?>assets/datatables/media/js/sum.js" type="text/javascript"></script>

  <!--<script src="<?php echo base_url();?>assets/js/highcharts.js" type="text/javascript"></script>-->
  <!--<script src="<?php echo base_url();?>assets/js/drilldown.js" type="text/javascript"></script>-->
  <!--<script src="<?php echo base_url();?>assets/js/worldcloud.js" type="text/javascript"></script>-->

  <!--<script src="https://code.highcharts.com/highcharts.js"></script>-->

  <!--<script src="https://code.highcharts.com/highcharts.js"></script>-->
  <script src="https://code.highcharts.com/maps/modules/map.js"></script>
  <script src="https://code.highcharts.com/maps/highmaps.js"></script>
  <script src="https://code.highcharts.com/modules/series-label.js"></script>
  <script src="https://code.highcharts.com/modules/data.js"></script>
  <script src="https://code.highcharts.com/highcharts-more.js"></script>
  <script src="https://code.highcharts.com/modules/drilldown.js"></script>

  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>

  <?php
    if(isset($_SESSION['user_permis']["9"])){ ?>
    <!--  <script src="https://code.highcharts.com/modules/exporting.js"></script>--> <?php }
  ?>

  <!--<script src="https://code.highcharts.com/modules/wordcloud.js"></script>-->


    <script src="<?php echo base_url();?>assets/js/global.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">


  </head>
  <body>
