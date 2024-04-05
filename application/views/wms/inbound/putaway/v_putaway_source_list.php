
<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th><input type='checkbox' id='check_all'></th>
      <th>Doc No</th>
      <th>Line No</th>
      <th>Loc</th>
      <th>Item Code</th>
      <th>Description</th>
      <th>Qty</th>
      <th>Uom</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $i=0;
      foreach($var_source_list as $row){
          echo "<tr id='src_row_".$i."'>";
            echo "<td><input type='checkbox' id='check_src_".$i."'></td>";
            echo "<td id='src_doc_no_".$i."'>".$row['doc_no']."</td>";
            echo "<td id='src_line_no_".$i."'>".$row['line_no']."</td>";
            echo "<td id='src_location_code_".$i."'>".$row['src_location_code']."</td>";
            echo "<td id='src_item_code_".$i."'>".$row['item_code']."</td>";
            echo "<td id='src_desc_".$i."'>".$row['description']."</td>";
            echo "<td id='src_qty_outstanding_".$i."'>".$row['qty_outstanding']."</td>";
            echo "<td id='src_uom_".$i."'>".$row['uom']."</td>";
          echo "</tr>";

          $i++;
      }
    ?>
  </tbody>
</table>

<input type="hidden" id="total_check" name="total_check" value="<?php echo $i; ?>">

<div class='text-right'>
  <button class="btn btn-primary text-right" id="btn_process_add">Add</button>
</div>

<script>
$("#check_all").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
//---

$("#btn_process_add").click(function(){
    var total_check = $('#total_check').val();

    // check if checked not blank
    var is_check_no_blank = f_check_no_blank(total_check,'check_src_');
    if(!is_check_no_blank){
      show_error("You must select at least one data");
      return false;
    }
    //----

    // add to table
    var text = f_add_to_table(total_check);
    $("#tbl_detail").append(text);
    //---

    // calculate total
    //qty_total = f_calculate_total(total_check);
    var qty_total = f_calculate_total_outstanding();
    var qty_rem = f_calculate_total_rem(); // calculate and update total outstanding
    var qty_put =  f_calculate_total_put();
    $('#tbl_detail_qty_total').text(qty_total);
    $('#tbl_detail_qty_put').text(qty_put);
    $('#tbl_detail_qty_rem').text(qty_rem);

    // remove from modal
    f_remove_row_modal(total_check);
    //---

    $('#myModalSourceDoc').modal('toggle');

})
//---

function f_check_no_blank(total_check, id){
    var no_blank = 0;
    for(i=0;i<total_check;i++){
        if($('#check_src_'+i).is(':checked') == true){
            no_blank = 1;
            break;
        }
    }

    return no_blank;
}
//----

function f_add_to_table(total_check){
    var text = "";
    for(i=0;i<total_check;i++){
        if($('#check_src_'+i).is(':checked') == true){
            text = text + "<tr>";
              text = text + "<td id='tbl_doc_no_"+glb_counter+"'>"+$('#src_doc_no_'+i).text()+"</td>";
              text = text + "<td id='tbl_line_no_"+glb_counter+"'>"+$('#src_line_no_'+i).text()+"</td>";
              text = text + "<td id='tbl_location_code_"+glb_counter+"'>"+$('#src_location_code_'+i).text()+"</td>";
              text = text + "<td id='tbl_item_code_"+glb_counter+"'>"+$('#src_item_code_'+i).text()+"</td>";
              text = text + "<td id='tbl_desc_"+glb_counter+"'>"+$('#src_desc_'+i).text()+"</td>";
              text = text + "<td id='tbl_qty_outstanding_"+glb_counter+"'>"+$('#src_qty_outstanding_'+i).text()+"</td>";
              text = text + "<td id='tbl_qty_put_"+glb_counter+"'>0</td>";
              text = text + "<td id='tbl_qty_rem_"+glb_counter+"'>"+$('#src_qty_outstanding_'+i).text()+"</td>";
              text = text + "<td id='tbl_uom_"+glb_counter+"'>"+$('#src_uom_'+i).text()+"</td>";
              text = text + "<td><button class='btn btn-warning btn-sm' id='btn_put_"+glb_counter+"' onclick=f_get_loc_zone_area_rack_bin("+glb_counter+")>Put</button></td>";
            text = text + "</tr>";
            glb_counter++;
        }
    }

    return text;
}
//---

function f_remove_row_modal(total_check){
  for(i=0;i<total_check;i++){
      if($('#check_src_'+i).is(':checked') == true){
          $('#src_row_'+i).remove();
      }
  }
}
//----

function f_remove_row_modal2(){

    var total_check = $('#total_check').val();
    for(i=0;i<total_check;i++){
      for(j=0;j<glb_counter;j++){
        if($('#src_doc_no_'+i).text() == $('#tbl_doc_no_'+j).text() && $('#src_line_no_'+i).text() == $('#tbl_line_no_'+j).text()){
            $('#src_row_'+i).remove();
        }
      }
    }
}
f_remove_row_modal2();
//---

function f_calculate_total(total_check){
    var qty = 0;
    for(i=0;i<total_check;i++){
        if($('#check_src_'+i).is(':checked') == true){
            qty = qty + parseInt($('#src_qty_outstanding_'+i).text());
        }
    }

    return qty;
}
//---

</script>
