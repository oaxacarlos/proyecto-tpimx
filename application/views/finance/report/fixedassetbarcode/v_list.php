<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
        "paging": false,
    });
});
</script>

<div class="container-fluid">
  <button class="btn btn-success" onclick=f_print_barcode()>PRINT BARCODE</button>
</div>

<table class="table table-bordered table-striped" id="DataTable">
  <thead>
    <th><input type='checkbox' id='check_all'></th>
    <th>Code</th>
    <th>Description</th>
  </thead>
  <tbody>
    <?php
      $i=0;
      foreach($var_fixedasset as $row){
          echo "<tr>";
            echo "<td><input type='checkbox' id='check_".$i."'></td>";
            echo "<td id='code_".$i."'>".$row["code"]."</td>";
            echo "<td>".$row["description"]."</td>";
          echo "</tr>";
          $i++;
      }
    ?>
  </tbody>
</table>

<?php echo "<input type='hidden' value='".$i."' name='total_check' id='total_check'>"; ?>

<script>
$("#check_all").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
//---

function f_check_no_blank(total_check){
    var no_blank = 0;
    for(i=0;i<total_check;i++){
        if($('#check_'+i).is(':checked') == true){
            no_blank = 1;
            break;
        }
    }

    return no_blank;
}
//----

function f_print_barcode(){
    var table = $('#DataTable').DataTable();
    table.search("").draw();

    var total_check = $("#total_check").val();

    if(!f_check_no_blank(total_check)){
        show_error("You haven't checked the data");
        return false;
    }
    else{
        // get the data
        var code = [];
        var counter = 0;
        for(i=0;i<total_check;i++){
          if($('#check_'+i).is(':checked') == true){
              code[counter] = $("#code_"+i).text();
              counter++;
            }
        }
        //--

        link = "total="+counter;
        for(i=0;i<counter;i++){
            link = link + "&data"+i+"="+code[i];
        }

        window.open("<?php echo base_url(); ?>index.php/finance/report/fixedassetbarcode_print?"+link, '_blank');
    }
}

</script>
