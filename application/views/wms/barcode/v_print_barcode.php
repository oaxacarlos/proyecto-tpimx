<link href="<?php echo base_url();?>assets/bootstrap4/css/bootstrap431.min.css" rel="stylesheet" type="text/css" />

<style type="text/css" media="print">
@page {
    /*size: 5.1cm 2.5cm;   /* auto is the initial value */
    /*margin: 12 2 0 2;  /* this affects the margin in the printer settings */
    /*margin: 0;*/

    /*  size: auto;   /* auto is the initial value */
    /*margin: 0mm;  /* this affects the margin in the printer settings */
}

@media print {
  @page {
    margin: 0 0 0 0;
    size: auto;
  }

  /*top right bottom left */
  body { margin: 0cm 0cm 1cm 0cm; }

}

</style>

<style>
#barcode_style{
  width:500px;
  margin-bottom:10px;
}

#row_barcode_style{
  margin-top:30px;
}

</style>

<?php

mb_internal_encoding("iso-8859-1");
mb_http_output( "UTF-8" );
ob_start("mb_output_handler");

function print_barcode($file_sn, $serial_number, $file_ext, $item_code, $file_item,$margin_left){
    $text = "<span class='col-2' style='margin-left:".$margin_left."px;'>";
      //$text.="<span class='col-4'>";
      if(strpos($item_code,"TYP")!== false){
		      if(strlen($item_code) >= 14) $text.= "<div class='col'><img src='".base_url().$file_item.$item_code.$file_ext."' width='250'></div>";
		      else $text.= "<div class='col'><img src='".base_url().$file_item.$item_code.$file_ext."' width='195'></div>";
	    }
	    else $text.= "<div class='col'><img src='".base_url().$file_item.$item_code.$file_ext."' width='160'></div>";

      $text.= "<div class='col' style='margin-top:10px;'><img src='".base_url().$file_sn.$serial_number.$file_ext."' width='160'></div>";
      //$text.="</span>";
      //$text.="<span class='col-1'>";
      //$text.="</span>";
    $text.= "</span>";

//

//$text= "<div class='col'><img src='".base_url().$file_item.$item_code.$file_ext."' width='200'></div>";
//$text.= "<div class='col' style='margin-top:10px;'><img src='".base_url().$file_sn.$serial_number.$file_ext."' width='200'></div>";

    return $text;
}
?>

<body style="width:100%;">

  <?php

    $i=0;
    echo "<div class='row' style='margin-top:10px;'>";
    foreach($barcode_data as $row){
        if($i==0){
            echo print_barcode($file_folder_sn, $row["serial_number"], $file_ext, $row["item_code"],$file_folder_items,-10);
            //echo print_barcode($file_folder_sn, $row["serial_number"], $file_ext, $row["item_code"],$file_folder_items,-10);
        }
        else{
            if($i%2 == 0){
                echo "</div>";
                echo "<div style='margin-top:320px;'></div>";
                echo "<div class='row' style='padding-top:5px;'>";
                echo print_barcode($file_folder_sn, $row["serial_number"],$file_ext, $row["item_code"],$file_folder_items,-10);
                //echo print_barcode($file_folder_sn, $row["serial_number"],$file_ext, $row["item_code"],$file_folder_items,-10);
            }
            else{
                echo print_barcode($file_folder_sn  , $row["serial_number"], $file_ext,$row["item_code"],$file_folder_items,110);
                //echo print_barcode($file_folder_sn  , $row["serial_number"], $file_ext,$row["item_code"],$file_folder_items,110);
            }
        }
        $i++;
    }
    echo "</div>";

  ?>

</div>

</body>
