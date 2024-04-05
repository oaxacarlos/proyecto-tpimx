<style>
.ui-autocomplete {
    position: absolute;
    z-index: 1000;
    top: 0;
    left: 0;
    cursor: default;
    background-color: #fff;
    padding:3px;
    border: 1px solid #ccc
}

.ui-autocomplete > li.ui-state-focus {
  background-color: #FF6C00;
}
</style>

<div class="row">
  <div class="col-md-3">
    Doc No
    <input type='text'  id='add_qty_bin_doc_no' value='<?php echo $doc_no ?>' class="form-control" disabled>
  </div>
  <div class="col-md-2">
    Line No
    <input type='text'  id='add_qty_bin_line_no' value='<?php echo $line_no ?>' class="form-control" disabled>
  </div>
  <div class="col-md-3">
    Item code
    <input type='text'  id='add_qty_bin_item_code' value='<?php echo $item_code ?>' class="form-control" disabled>
  </div>

  <input type='hidden'  id='add_qty_bin_desc' value='<?php echo $desc ?>' class="form-control" disabled>
  <input type='hidden'  id='add_qty_bin_uom' value='<?php echo $uom ?>' class="form-control" disabled>

</div>

<div class="row" style="margin-top:10px;">
  <div class="col-md-3">
    Total Qty
    <input type='text' id='add_qty_bin_total_qty' value='<?php echo $total_qty_outstanding ?>' class="form-control" disabled>
  </div>
  <div class="col-md-2">
    Putted
    <input type='text' id='add_qty_bin_put_qty' value='<?php echo $total_qty_put ?>' class="form-control" disabled>
  </div>
  <div class="col-md-3">
    Remaining
    <input type='text'  id='add_qty_bin_remain_qty' value='<?php echo ($total_qty_outstanding-$total_qty_put) ?>' class="form-control" disabled>
  </div>
</div>

<!--
<div class="text-right">
  <button class="btn btn-primary" onclick="f_add_qty_bin()">+</button>
</div>
-->

<!--
<table id="tbl_add_qty_bin" class="table table-sm" style="margin-top:20px;">
  <thead>
    <th style="width:100px;">Qty Put</th>
    <th>Loc-Zone-Area-Rack-Bin</th>
    <th style="width:50px;">Action</th>
  </thead>
  <tbody>
  </tbody>
</table>
-->

<table id="tbl_add_qty_bin" class="table table-sm" style="margin-top:20px;">
  <thead>
    <tr>
      <th>Master Barcode</th>
      <th>Qty</th>
      <th>Location</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
      $i=0;
      if($var_item_per_master_code == 0){
          echo "<tr>";
            echo "<td colspan='3'>No Data</td>";
          echo "</tr>";
      }
      else{
        foreach($var_item_per_master_code as $row){
          echo "<tr>";
            echo "<td id='tbl_add_qty_bin_sn2_".$i."'>".$row["sn2"]."</td>";
            echo "<td><input type='text' id='tbl_add_qty_bin_qty_".$i."' value='".$row["qty"]."' disabled size='5'></td>";
            echo "<td><input type='text' id='tbl_add_qty_bin_bin_".$i."' class='form-control' value=''></td>";

            if($i==0){
                echo "<td><button class='btn btn-info' id='copy_to_all_item_per_master_barcode'>Copy to All</button></td>";
            }

          echo "</tr>";
          $i++;
        }
      }

      $total_row_item_per_master_barcode = $i;

      echo "<input type='hidden' name='total_row_item_per_master_barcode' id='total_row_item_per_master_barcode' value='".$i."'>";
    ?>
  </tbody>
</table>

<div class="text-right" style='margin-top:20px;'>
  <button class="btn btn-primary" id='btn_process_add_qty_bin'>Process</button>
</div>

<input type='hidden'  id='add_qty_bin_row_doc' value='<?php echo $row_doc ?>' class="form-control" disabled>

<?php

$option="";
unset($autocomplete);
$i=0;
foreach($var_bin as $row){
    $value = $row['location_code']." | ".$row['zone_code']." | ".$row['area_code']." | ".$row['rack_code']." | ".$row['code'];
    $value2 = $row['location_code']."-".$row['zone_code']."-".$row['area_code']."-".$row['rack_code']."-".$row['code'];
    $option.= "<option value='".$value."'>".$value."</option>";
    $autocomplete[$i] = $value2;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);

?>

<script>

var option = "<?php echo $option; ?>";
var counter= "<?php echo $total_row_item_per_master_barcode; ?>";
var autocomplete = <?php echo $js_array_autocomplete; ?>;

$( function() {
  $( "#testing").autocomplete({
    source: autocomplete
  });

  var total_row_item_per_master_barcode = $("#total_row_item_per_master_barcode").val();
  for(i=0;i<total_row_item_per_master_barcode;i++){
    $( "#tbl_add_qty_bin_bin_"+i).autocomplete({
      source: autocomplete
    });
  }

})

function f_add_qty_bin(){
    text = f_add_to_table_qty_bin(counter);
    $("#tbl_add_qty_bin").append(text);
    $( "#tbl_add_qty_bin_bin_"+counter).autocomplete({
      source: autocomplete
    });
    counter++;
}
//---
/*
function f_add_to_table_qty_bin(i){
    var text = "";
    text = text + "<tr id='row_add_qty_bin_qty_"+i+"'>";
      text = text + "<td><input type='text' id='tbl_add_qty_bin_qty_"+i+"' class='form-control' onkeypress='return isNumberKey(event)'></td>";

      text = text + "<td>";
        text = text + "<select id='tbl_add_qty_bin_bin_"+i+"' class='form-control'>";
          text = text + "<option value='-'>-</option>";
          text = text + option;
        text = text + "</select>"
      text = text + "</td>";

      text = text + "<td><button class='btn btn-danger btn-sm' onclick='f_remove_row_add_qty_bin("+i+")'>X</button></td>";
    text = text + "</tr>";

    return text;
}*/

function f_add_to_table_qty_bin(i){
    var text = "";
    text = text + "<tr id='row_add_qty_bin_qty_"+i+"'>";
      text = text + "<td><input type='text' id='tbl_add_qty_bin_qty_"+i+"' class='form-control' onkeypress='return isNumberKey(event)'></td>";

      text = text + "<td>";
        text = text + "<input type='text' id='tbl_add_qty_bin_bin_"+i+"' class='form-control'>";
      text = text + "</td>";

      text = text + "<td><button class='btn btn-danger btn-sm' onclick='f_remove_row_add_qty_bin("+i+")'>X</button></td>";
    text = text + "</tr>";

    return text;
}


//---

function f_remove_row_add_qty_bin(i){
    $("#row_add_qty_bin_qty_"+i).remove();
}
//---

$("#btn_process_add_qty_bin").click(function(){

      // check if bin has blank
      if(!f_check_bin_blank()){
        show_error("There is Bin has BLANK");
        return false;
      }

      // check total qty put must less than remaining...
      if(!f_check_qty_put_less_than_remaining()){
        show_error("QTY putted not allow greater then QTY total");
        return false;
      }
      //--

      // check if not all bin selected
      if(!f_check_bin_selected_all()){
        show_error("There is Bin has not been selected");
        return false;
      }

      //** if everything is ok
      f_add_to_table_loc_zone_area_rack_bin(); // add to table loc-zone-area-rack-Bin
      $('#tbl_detail2').append(text);

      f_update_qty_put_document(); // update document qty put
      var total_qty_temp = f_calculate_total_put();  // calculate and update total put
      $('#tbl_detail_qty_put').text(total_qty_temp);

      var total_qty_temp = f_calculate_total_rem(); // calculate and update total rem
      $('#tbl_detail_qty_rem').text(total_qty_temp); // update qty rem

      var qty_temp = f_calculate_total_bin(); // calculate qty loc-zone-area-rack-bin qty
      $('#tbl_detail2_qty_total').text(qty_temp); // update qty loc-zone-area-rack-bin qty

      $("#myModalBin").modal('toggle');

})
//---

function f_check_qty_put_less_than_remaining(){
    //var qty_total = parseInt($("#add_qty_bin_total_qty").val());
    var qty_total = parseInt($("#add_qty_bin_remain_qty").val());
    var qty = 0;

    for(i=0;i<counter;i++){
        if(check_if_id_exist("#tbl_add_qty_bin_qty_"+i)){
            qty = qty + convert_number($("#tbl_add_qty_bin_qty_"+i).val());
        }
    }

    if(qty <= qty_total) return true; else return false;
}
//---

function f_check_bin_blank(){

    var error = 0;
    for(i=0;i<counter;i++){
        if(check_if_id_exist("#tbl_add_qty_bin_bin_"+i)){
            if($("#tbl_add_qty_bin_bin_"+i).val() == ''){
                error = 1; break;
            }
        }
    }
    if(error == 1) return false; else return true;
}
//--

function f_check_bin_selected_all(){
    var all_selected=1;

    for(i=0;i<counter;i++){
        if(check_if_id_exist("#tbl_add_qty_bin_bin_"+i)){
          if($("#tbl_add_qty_bin_qty_"+i).val()==""){
            if($("#tbl_add_qty_bin_bin_"+i).val()=="-"){
                all_selected = 0;
                break;
            }
          }
        }
    }

    if(all_selected == 1) return true; else return false;
}

function f_add_to_table_loc_zone_area_rack_bin(){
  var id_row_doc = $('#add_qty_bin_row_doc').val();

  text = "";
  for(i=0;i<counter;i++){
      text = text + "<tr id='tbl_detail2_row_"+glb_loc_zone_area_rack_bin+"'>";
        text = text + "<td id='tbl_detail2_doc_no_"+glb_loc_zone_area_rack_bin+"'>"+$('#add_qty_bin_doc_no').val()+"</td>";
        text = text + "<td id='tbl_detail2_line_no_"+glb_loc_zone_area_rack_bin+"'>"+$('#add_qty_bin_line_no').val()+"</td>";
        text = text + "<td id='tbl_detail2_item_code_"+glb_loc_zone_area_rack_bin+"'>"+$('#add_qty_bin_item_code').val()+"</td>";
        text = text + "<td id='tbl_detail2_desc_"+glb_loc_zone_area_rack_bin+"'>"+$('#add_qty_bin_desc').val()+"</td>";
        text = text + "<td id='tbl_detail2_qty_"+glb_loc_zone_area_rack_bin+"'>"+$("#tbl_add_qty_bin_qty_"+i).val()+"</td>";
        text = text + "<td id='tbl_detail2_uom_"+glb_loc_zone_area_rack_bin+"'>"+$('#add_qty_bin_uom').val()+"</td>";
        text = text + "<td id='tbl_detail2_sn2_"+glb_loc_zone_area_rack_bin+"'>"+$('#tbl_add_qty_bin_sn2_'+i).text()+"</td>";

        //var new_bin = $('#tbl_add_qty_bin_bin_'+i).val().split(" | ");
        var new_bin = $('#tbl_add_qty_bin_bin_'+i).val().split("-");

        text = text + "<td id='tbl_detail2_loc_"+glb_loc_zone_area_rack_bin+"'>"+new_bin[0]+"</td>";
        text = text + "<td id='tbl_detail2_zone_"+glb_loc_zone_area_rack_bin+"'>"+new_bin[1]+"</td>";
        text = text + "<td id='tbl_detail2_area_"+glb_loc_zone_area_rack_bin+"'>"+new_bin[2]+"</td>";
        text = text + "<td id='tbl_detail2_rack_"+glb_loc_zone_area_rack_bin+"'>"+new_bin[3]+"</td>";
        text = text + "<td id='tbl_detail2_bin_"+glb_loc_zone_area_rack_bin+"'>"+new_bin[4]+"</td>";

        text = text + "<td id='tbl_detail2_row_doc_"+glb_loc_zone_area_rack_bin+"' style='display:none;'>"+id_row_doc+"</td>";

        text = text + "<td><button class='btn btn-danger btn-sm' onclick=f_delete_bin("+glb_loc_zone_area_rack_bin+")>X</button></td>";

      text = text + "</tr>";
      glb_loc_zone_area_rack_bin++;
  }

  return text;
}
//---

function f_update_qty_put_document(){
    var id_row_doc = $('#add_qty_bin_row_doc').val();
    var qty_put = parseInt($('#tbl_qty_put_'+id_row_doc).text());

    qty = 0;
    for(i=0;i<counter;i++){
      if(check_if_id_exist("#tbl_add_qty_bin_qty_"+i)){
          qty = qty + convert_number($("#tbl_add_qty_bin_qty_"+i).val());
      }
    }

    qty_put_updated = qty_put + qty;
    $('#tbl_qty_put_'+id_row_doc).text(qty_put_updated);

    var qty_total = parseInt($('#tbl_qty_outstanding_'+id_row_doc).text());
    var qty_rem = qty_total - qty_put_updated;
    $('#tbl_qty_rem_'+id_row_doc).text(qty_rem);
}
//---

function f_delete_bin(i){

    var id_doc_h = $("#tbl_detail2_row_doc_"+i).text();
    var qty_put_bin = parseInt($('#tbl_detail2_qty_'+i).text());

    f_update_qty_put_document2(id_doc_h,qty_put_bin);

    var total_qty_temp = f_calculate_total_put();  // calculate and update total put
    $('#tbl_detail_qty_put').text(total_qty_temp);

    var total_qty_temp = f_calculate_total_rem(); // calculate and update total rem
    $('#tbl_detail_qty_rem').text(total_qty_temp); // update qty rem

    $('#tbl_detail2_row_'+i).remove();

    var qty_temp = f_calculate_total_bin(); // calculate qty loc-zone-area-rack-bin qty
    $('#tbl_detail2_qty_total').text(qty_temp); // update qty loc-zone-area-rack-bin qty
}
//---

function f_update_qty_put_document2(id_doc_h,qty_put_bin){

    var qty_put = parseInt($('#tbl_qty_put_'+id_doc_h).text());

    qty_put_updated = qty_put - qty_put_bin;
    $('#tbl_qty_put_'+id_doc_h).text(qty_put_updated);

    var qty_total = parseInt($('#tbl_qty_outstanding_'+id_doc_h).text());
    var qty_rem = qty_total - qty_put_updated;
    $('#tbl_qty_rem_'+id_doc_h).text(qty_rem);
}
//---

$("#copy_to_all_item_per_master_barcode").click(function(){
    var total_row_item_per_master_barcode = $("#total_row_item_per_master_barcode").val();
    for(i=1;i<total_row_item_per_master_barcode;i++){
        $("#tbl_add_qty_bin_bin_"+i).val($("#tbl_add_qty_bin_bin_0").val());
    }
});

</script>
