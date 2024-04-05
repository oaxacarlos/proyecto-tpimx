<style>

</style>

  <div class="container">
    Qty : <?php echo $qty_rem ?>
  </div>

  <div class="row">
    <div class="container" style="margin-top:10px;">
        <table class="table table-bordered table-sm table-striped">

          <thead>
            <tr>
              <th>Status</th>
              <th>Loc</th>
              <th>Zone</th>
              <th>Area</th>
              <th>Rack</th>
              <th>Bin</th>
              <th>Qty</th>
              <th>Uom</th>
              <th>Pick</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan='8'></td>
              <td id='total_qty_pick'>0</td>
            </tr>
          <?php
          $i=0;

          if(count($var_item_loc) == 1) $auto_fill_pick = 1;
          else $auto_fill_pick = 0;

          foreach($var_item_loc as $row){

            $sn = 0;
            $sn2 = 0;

            if (strpos($row['masterr'], "0") !== false) $sn = 1;
            if (strpos($row['masterr'], "1") !== false) $sn2 = 1;

            $status = ""; $status1 = ""; $status2 = "";
            if($sn == 1) $status1 = "Individual";
            if($sn2 == 1) $status2 = "Master";

            if($status1 != "" && $status2!="") $status = $status1." & ".$status2;
            else if($status1 != "" && $status2 == "") $status = $status1;
            else if($status1 == "" && $status2 != "") $status = $status2;

            echo "<tr>";
              echo "<td id='tbl_pick_status_".$i."'>".$status."</td>";
              echo "<td id='tbl_pick_loc_code_".$i."'>".$row['location_code']."</td>";
              echo "<td id='tbl_pick_zone_code_".$i."'>".$row['zone_code']."</td>";
              echo "<td id='tbl_pick_area_code_".$i."'>".$row['area_code']."</td>";
              echo "<td id='tbl_pick_rack_code_".$i."'>".$row['rack_code']."</td>";
              echo "<td id='tbl_pick_bin_code_".$i."'>".$row['bin_code']."</td>";
              echo "<td id='tbl_pick_qty_code_".$i."'>".$row['total']."</td>";
              echo "<td id='tbl_pick_uom_".$i."'>".$uom."</td>";

              /*if($row['zone_code']=="X" && $row['area_code']=="X" && $row['rack_code']=="X" && $row['bin_code']=="X"){
                  $disabled = "disabled";
              }
              else{
                  $disabled = "";
              }*/

              if($auto_fill_pick == 1){
                  if($qty_rem > $row['total']) $auto_fill_qty =  $row['total'];
                  else $auto_fill_qty =  $qty_rem;

                  $auto_fill_text = "<span style='color:red;'>AUTO</span>";
              }
              else{
                $auto_fill_qty = "";
                $auto_fill_text = "";
              }

              echo "<td style='width:100px;'><input class='form-control' size='1' id='inp_pick_qty_pick_".$i."' onkeypress='return isNumberKey(event)' onkeyup=f_calculate_total_pick(".$i.") ".$disabled." value='".$auto_fill_qty."'>".$auto_fill_text."</td>";
            echo "</tr>";
            $i++;
          }
          ?>
          </tbody>
        </table>

        <div class="text-right" style='margin-top:20px;'>
          <button id='btn_add_pick' class="btn btn-primary text-right">Add</button>
        </div>

    </div>
    </div>

    <input type='hidden' value='<?php echo $item_code ?>' id='tbl_pick_item_code'>
    <input type='hidden' value='<?php echo $desc ?>' id='tbl_pick_desc'>
    <input type='hidden' value='<?php echo $qty_rem ?>' id='tbl_pick_qty_rem' >
    <input type='hidden' value='0' id='inp_pick_qty_pick_total'>
    <input type='hidden' value='<?php echo $i ?>' id='inp_total_row'>
    <input type='hidden' value='<?php echo $line ?>' id='inp_line'>
    <input type='hidden' value='<?php echo $src_line_no ?>' id='inp_src_line_no'>

<script>

$('#btn_add_pick').click(function(){
    var total_row = $('#inp_total_row').val();
    var total_qty = f_get_total_pick(total_row);

    var tbl_pick_qty_rem = parseFloat($('#tbl_pick_qty_rem').val());

    if(total_qty > tbl_pick_qty_rem){
       show_error("Qty you inputted greater than Qty Remaining ");
    }
    else{
        f_add_qty_to_picked(total_row);
        $('#myModalPick').modal('toggle');
    }
})
//---

function f_get_total_pick(total_row){
    var total = 0;
    for(i=0;i<total_row;i++){
        total = total + convert_number($("#inp_pick_qty_pick_"+i).val());
    }

    return total;
}
//---

function f_add_qty_to_picked(total_row){
    var total_qty = 0;
    var line = $('#inp_line').val();

    for(i=0;i<total_row;i++){
        qty = convert_number($("#inp_pick_qty_pick_"+i).val());
        if(qty > 0){
            text = add_row(i,line);
            $("#table_pick_list").append(text);
            total_qty = total_qty + qty;
        }
    }

    calculate_qty_pick(line,total_qty);
    calculate_qty_rem(line,total_qty);
    check_if_picklist_not_empty();
    disabled_enabled_btn_pick(line);

}
//----

function add_row(i,line){
    var text = "";
    text = text + "<tr id='tbl_pick_list_row_"+glb_row_pick+"'>";
    text = text + "<td id='tbl_pick_list_item_code_"+glb_row_pick+"'>"+$('#tbl_pick_item_code').val()+"</td>";
    text = text + "<td id='tbl_pick_list_desc_"+glb_row_pick+"'>"+$('#tbl_pick_desc').val()+"</td>";
    text = text + "<td id='tbl_pick_list_loc_code_"+glb_row_pick+"'>"+$('#tbl_pick_loc_code_'+i).text()+"</td>";
    text = text + "<td id='tbl_pick_list_zone_code_"+glb_row_pick+"'>"+$('#tbl_pick_zone_code_'+i).text()+"</td>";
    text = text + "<td id='tbl_pick_list_area_code_"+glb_row_pick+"'>"+$('#tbl_pick_area_code_'+i).text()+"</td>";
    text = text + "<td id='tbl_pick_list_rack_code_"+glb_row_pick+"'>"+$('#tbl_pick_rack_code_'+i).text()+"</td>";
    text = text + "<td id='tbl_pick_list_bin_code_"+glb_row_pick+"'>"+$('#tbl_pick_bin_code_'+i).text()+"</td>";
    text = text + "<td id='tbl_pick_list_qty_"+glb_row_pick+"'>"+$('#inp_pick_qty_pick_'+i).val()+"</td>";
    text = text + "<td id='tbl_pick_list_uom_"+glb_row_pick+"'>"+$('#tbl_pick_uom_'+i).text()+"</td>";
    text = text + "<td><button class='btn btn-danger btn-sm' id='btn_tbl_pick_delete' onclick=f_tbl_pick_delete("+line+","+$('#inp_pick_qty_pick_'+i).val()+","+glb_row_pick+")>X</td>";
    text = text + "<td style='display:none;'>"+line+"</td>";
    text = text + "<td style='display:none;'>"+glb_row_pick+"</td>";
    text = text + "<td id='tbl_pick_list_src_line_no_"+glb_row_pick+"' style='display:none;'>"+$('#inp_src_line_no').val()+"</td>";
    text = text + "</tr>";

    glb_row_pick++;

    return text;
}
//--

function f_calculate_total_pick(i){

    // check qty in location with input
    var qty = convert_number($('#tbl_pick_qty_code_'+i).text());
    var qty_pick = convert_number($('#inp_pick_qty_pick_'+i).val());
    if(qty_pick > qty){
      show_error("QTY your input not allow greater than QTY Available");
      $('#inp_pick_qty_pick_'+i).val('')
      return false;
    }

    var total_row = $('#inp_total_row').val();
    total_qty = f_get_total_pick(total_row);
    $('#total_qty_pick').text(total_qty);
}
//---

function calculate_qty_pick(line,total_qty){
    // qty pick
    var qty_pick = parseFloat($('#tbl_list_qtypick_'+line).text());
    var qty_pick = qty_pick + total_qty;
    $("#tbl_list_qtypick_"+line).text(qty_pick);
}
//---

function calculate_qty_rem(line,total_qty){
    // get qty_remain
    var qty_remain = parseFloat($('#tbl_list_qtyrem_'+line).text());
    var qty_remain = qty_remain - total_qty;
    $("#tbl_list_qtyrem_"+line).text(qty_remain);
}
//----

function f_tbl_pick_delete(line, qty,row){
    qty = qty * -1;
    calculate_qty_pick(line,qty);
    calculate_qty_rem(line,qty);
    $("#tbl_pick_list_row_"+row).remove();
    check_if_picklist_not_empty();
    disabled_enabled_btn_pick(line);
}
//----

</script>
