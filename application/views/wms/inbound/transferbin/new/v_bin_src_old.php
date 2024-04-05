<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
        "paging": false,

    });
});
</script>

<div class="container">
  <button class="btn btn-primary" id="btn_add2">Add</button>
</div>

<table class="table table-bordered table-sm table-striped" id="DataTable" style="margin-top:20px;">
  <thead>
  <tr>
    <th></th>
    <th>Item Code</th>
    <th>Desc</th>
    <th>Qty</th>
    <th>Uom</th>
  </tr>
  </thead>
  <tbody>
  <?php
    $i=0;
    foreach($var_bin_src as $row){
        echo "<tr>";
          echo "<td style='width:20px;'><input type='checkbox' id='src_check_".$i."'></td>";
          echo "<td id='src_item_".$i."'>".$row["item_code"]."</td>";
          echo "<td id='src_desc_".$i."'>".$row["description"]."</td>";
          echo "<td id='src_qty_".$i."'>".$row["total"]."</td>";
          echo "<td id='src_uom_".$i."'>".$row["uom"]."</td>";
        echo "</tr>";
        $i++;
    }
  ?>

</tbody>

</table>

<input type='hidden' id="total_row_src_bin" value="<?php echo $i; ?>">

<div class="container text-right">
  <button class="btn btn-primary text-right" id="btn_add">Add</button>
</div>

<script>

$("#btn_add").click(function(){
    // check if not all checked
    if(!check_if_checkbox_not_blank()){
        show_error("You have not checked any data");
        return false;
    }

    add_data(); // add data
})
//---

$("#btn_add2").click(function(){
    $("#btn_add").click();
})
//---

//---
function check_if_checkbox_not_blank(){
    var table = $('#DataTable').DataTable();
    table.search("").draw();

    var total_row = $("#total_row_src_bin").val();

    var no_blank = 0;
    for(i=0;i<total_row;i++){
          if($('#src_check_'+i).is(':checked') == true){
            no_blank = 1;
            break;
          }
    }
    return no_blank;
}
//---

function add_data(){
    var total_row = $("#total_row_src_bin").val();

    for(i=0;i<total_row;i++){
        if($('#src_check_'+i).is(':checked') == true){
            if(check_is_data_already_exist($("#inp_bin_src").val(),$('#src_item_'+i).text()) == 0){
                text = add_data_to_table($("#inp_bin_src").val(),$('#src_item_'+i).text(),$('#src_qty_'+i).text(),$('#src_desc_'+i).text(), $('#src_uom_'+i).text());
                $("#tbl_result tbody").append(text);
            }
            else{
              show_error("Item & Bin already added");
            }
        }
    }

    $("#myModalBinSrc").modal("toggle");
    $("#inp_bin_src").focus();
    $("#inp_bin_src").val("");
}
//--

function check_is_data_already_exist(bin, item_no){
    var new_bin = bin.split("-");
    var tb = $('#tbl_result tbody');
    var row = tb.find("tr").length;
    if(row == 0) return 0;
    else{
      var found = 0;
      tb.find("tr").each(function(index, element) {
          index = 0;
          $(element).find('td').each(function(index, element) {
              var colVal = $(element).text();
              if(index == 0) loc = colVal;
              else if(index == 1) zone =  colVal;
              else if(index == 2) area = colVal;
              else if(index == 3) rack = colVal;
              else if(index == 4) bin = colVal;
              else if(index == 5) item = colVal;
              index++;
          })

          if(loc==new_bin[0] && zone==new_bin[1] && area==new_bin[2] && rack==new_bin[3] && bin==new_bin[4] && item==item_no) found = 1;
      })
    }

    if(found == 1) return 1; else return 0;
}
//---

function add_data_to_table(bin,item,qty, desc, uom){

    var new_bin = bin.split("-");
    text = "";
    text = text + "<tr id='tbl_result_id_"+glb_idx+"'>";
      text = text + "<td id='tbl_result_loc_"+glb_idx+"'>"+new_bin[0]+"</td>";
      text = text + "<td id='tbl_result_zone_"+glb_idx+"'>"+new_bin[1]+"</td>";
      text = text + "<td id='tbl_result_area_"+glb_idx+"'>"+new_bin[2]+"</td>";
      text = text + "<td id='tbl_result_rack_"+glb_idx+"'>"+new_bin[3]+"</td>";
      text = text + "<td id='tbl_result_bin_"+glb_idx+"'>"+new_bin[4]+"</td>";
      text = text + "<td id='tbl_result_item_"+glb_idx+"'>"+item+"</td>";
      text = text + "<td id='tbl_result_desc_"+glb_idx+"'>"+desc+"</td>";
      text = text + "<td id='tbl_result_uom_"+glb_idx+"'>"+uom+"</td>";
      text = text + "<td id='tbl_result_qty_"+glb_idx+"'>"+qty+"</td>";
      text = text + "<td><input type='text' id='tbl_result_inp_qty_"+glb_idx+"' class='form-control' onkeypress='return isNumberKey(event)'></td>";
      text = text + "<td><button class='btn btn-danger btn-sm' onclick=f_delete_tbl_result("+glb_idx+")>X</button></td>";
    text = text + "</tr>";
    glb_idx++;

    return text;
}
//---



</script>
