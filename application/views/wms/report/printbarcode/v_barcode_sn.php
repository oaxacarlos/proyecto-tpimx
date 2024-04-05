<div class="container-fluid">
  <div class="row">
    <?php
      if($sn == 0){
        $disabled = "disabled";
      }
      else{
        $disabled = "";
      }
    ?>

    <a href="<?php echo base_url()."index.php/wms/barcode/print_master_barcode_by_sn2?sn2=".$sn["sn2"] ?>" class="btn btn-success" id="btn_print_barcode_master" <?php echo $disabled ?> target="_blank">PRINT <?php echo $sn2; ?></a>
    <button class="btn btn-info" id="btn_print_barcode_master_sn" <?php echo $disabled ?> style="margin-left:20px;">PRINT SN</button>
  </div>
</div>

<table class="table table-bordered table-striped table-sm" style="margin-top:20px;" id="tbl_barcode_master">
  <thead>
    <tr>
      <th><input type="checkbox" id="check_all_barcode_master_sn"></th>
      <th>No</th>
      <th>Item</th>
      <th>SN</th>
      <th>Master Barcode</th>
      <th>Status</th>
      <th>Loc</th>
      <th>Zone</th>
      <th>Area</th>
      <th>Rack</th>
      <th>Bin</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $i=0;
          echo "<tr>";
            echo "<td><input type='checkbox' id='check_barcode_master_sn_".$i."'></td>";
            echo "<td>".($i+1)."</td>";
            echo "<td id='tbl_barcode_master_item_".$i."'>".$sn["item_code"]."</td>";
            echo "<td id='tbl_barcode_master_sn_".$i."'>".$sn["serial_number"]."</td>";
            echo "<td id='tbl_barcode_master_sn_".$i."'>".$sn["sn2"]."</td>";
            echo "<td>".$sn["sts_name"]."</td>";
            echo "<td>".$sn["location_code"]."</td>";
            echo "<td>".$sn["zone_code"]."</td>";
            echo "<td>".$sn["area_code"]."</td>";
            echo "<td>".$sn["rack_code"]."</td>";
            echo "<td>".$sn["bin_code"]."</td>";
          echo "</tr>";
          $i++;


      $total_row_master_barcode = $i;

      echo "<input type='hidden' id='total_row_barcode_master' value='".$i."'>";
    ?>
  </tbody>
</table>

<script>

$("#check_all_barcode_master_sn").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
//---

function check_if_checkbox_not_blank(){
    //var table = $('#tbl_barcode_master').DataTable();
    //table.search("").draw();

    var total_row = '<?php echo $total_row_master_barcode; ?>';

    var no_blank = 0;
    for(i=0;i<total_row;i++){
      if($('#check_barcode_master_sn_'+i).is(':checked') == true){
        no_blank = 1;
        break;
      }
    }
    return no_blank;
}
//---


$("#btn_print_barcode_master_sn").click(function(){
  // check if not all checked
  if(!check_if_checkbox_not_blank()){
      show_error("You have not checked any data");
      return false;
  }

  print_barcode(); // add data
})
//--

function print_barcode(){
    // get the data
    var total_row = '<?php echo $total_row_master_barcode; ?>';
    var code = [];
    var item = [];
    var counter = 0;
    for(i=0;i<total_row;i++){
      if($('#check_barcode_master_sn_'+i).is(':checked') == true){
          code[counter] = $("#tbl_barcode_master_sn_"+i).text();
          item[counter] = $("#tbl_barcode_master_item_"+i).text();
          counter++;
        }
    }
    //--

    link = "total="+counter;
    for(i=0;i<counter;i++){
        link = link + "&sn"+i+"="+code[i]+"&item"+i+"="+item[i];
    }

    window.open("<?php echo base_url(); ?>index.php/wms/barcode/print_barcode_partially?"+link, '_blank');
}

</script>
