<script>
$(document).ready(function() {
    $('#DataTable2').DataTable({
      "order": [[ 1, 'asc']],
      "paging": false,
    });
});
</script>

<div class="container-fluid">
  <button class="btn btn-success" id='btn_print_barcode'>PRINT</button>
</div>

<table class="table table-bordered table-striped" id="DataTable2">
  <thead>
    <tr>
      <th><input type='checkbox' id='check_all'></th>
      <th>Item</th>
      <th>SN</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $i=0;
      foreach($var_barcode as $row){
          echo "<tr>";
            echo "<td><input type='checkbox' id='check_sn_".$i."'></td>";
            echo "<td id='value_item_".$i."'>".$row["item_code"]."</td>";
            echo "<td id='value_sn_".$i."'>".$row["serial_number"]."</td>";
          echo "</tr>";
          $i++;
      }

      $total_row = $i;

      echo "<input type='hidden' id='total_row_barcode' value='".$i."'>";
    ?>
  </tbody>
</table>


<script>

$("#check_all").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
//---

function check_if_checkbox_not_blank(){
    var table = $('#DataTable2').DataTable();
    table.search("").draw();

    var total_row = '<?php echo $total_row; ?>';

    var no_blank = 0;
    for(i=0;i<total_row;i++){
      if($('#check_sn_'+i).is(':checked') == true){
        no_blank = 1;
        break;
      }
    }
    return no_blank;
}
//---

$("#btn_print_barcode").click(function(){
    // check if not all checked
    if(!check_if_checkbox_not_blank()){
        show_error("You have not checked any data");
        return false;
    }

    print_barcode(); // add data
})
//---

function print_barcode(){
    // get the data
    var total_row = '<?php echo $total_row; ?>';
    var code = [];
    var item = [];
    var counter = 0;
    for(i=0;i<total_row;i++){
      if($('#check_sn_'+i).is(':checked') == true){
          code[counter] = $("#value_sn_"+i).text();
          item[counter] = $("#value_item_"+i).text();
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
