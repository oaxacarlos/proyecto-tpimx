<style>
tr{
  font-size: 12px;
}
</style>

<div class="modal" id="myModalPack" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Packing : <span id="modal_text_item"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_pack">
        <div class="container-fluid">
          Qty <input type="text" class="form-control" id="modal_text_qty" onkeypress='return isNumberKey_local(event)' style="font-size:40px;">
          <input type="hidden" class="form-control" id="modal_text_qty_max">
          <input type="hidden" class="form-control" id="modal_text_index">
          <input type="hidden" class="form-control" id="modal_text_src_line">
        </div>
        <div class="container-fluid" style="margin-top:20px;"> <!-- number key -->
            <div class="row">
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key(1)>1</button></div>
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key(2)>2</button></div>
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key(3)>3</button></div>
            </div>
            <div class="row" style="margin-top:20px;">
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key(4)>4</button></div>
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key(5)>5</button></div>
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key(6)>6</button></div>
            </div>
            <div class="row" style="margin-top:20px;">
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key(7)>7</button></div>
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key(8)>8</button></div>
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key(9)>9</button></div>
            </div>
            <div class="row" style="margin-top:20px;">
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control"></button></div>
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key(0)>0</button></div>
                <div class="col-4"><button class="btn btn-outline-primary btn-lg form-control" onclick=f_number_key('backspace')><i class="bi bi-arrow-left"></i></button></div>
            </div>
        </div>
        <div class="container-fluid" style="margin-top:40px;">
          <button class="btn btn-primary form-control" id="btn_add_pack">Add</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="container" style="margin-top:10px;">
    <table class="table table-bordered table-sm table-striped">
      <thead>
        <tr>
          <th>Sent To</th>
          <th>Name</th>
          <th>Contact</th>
          <th>Addr</th>
          <th>Addr2</th>
          <th>City</th>
          <th>PostCode</th>
          <th>County</th>
          <th>Country</th>
          <th>SO</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach($var_dest as $row){
            echo "<tr>";
              echo "<td id='tbl_dest_no_".$row["dest_no"]."'>".$row["dest_no"]."</td>";
              echo "<td id='tbl_ship_to_name_".$row["dest_no"]."'>".$row["ship_to_name"]."</td>";
              echo "<td id='tbl_ship_to_contact_".$row["dest_no"]."'>".$row["ship_to_contact"]."</td>";
              echo "<td id='tbl_ship_to_addr_".$row["dest_no"]."'>".$row["ship_to_addr"]."</td>";
              echo "<td id='tbl_ship_to_addr2_".$row["dest_no"]."'>".$row["ship_to_addr2"]."</td>";
              echo "<td id='tbl_ship_to_city_".$row["dest_no"]."'>".$row["ship_to_city"]."</td>";
              echo "<td id='tbl_ship_to_post_code_".$row["dest_no"]."'>".$row["ship_to_post_code"]."</td>";
              echo "<td id='tbl_ship_to_county_".$row["dest_no"]."'>".$row["ship_to_county"]."</td>";
              echo "<td id='tbl_ship_to_ctry_region_code_".$row["dest_no"]."'>".$row["ship_to_ctry_region_code"]."</td>";
              echo "<td id='tbl_ship_to_so_".$row["so_no"]."'>".$row["so_no"]."</td>";
            echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

  <div class="row">
    <div class="container" style="margin-top:10px;">
        <table class="table table-bordered table-sm table-striped">
          <thead>
            <tr>
              <th>Item Code</th>
              <th>Desc</th>
              <th>Qty</th>
              <th>Qty Packed</th>
              <th>Qty Remain</th>
              <th>Uom</th>
              <th>Sent To</th>
              <th>SO</th>
            </tr>
          </thead>
          <?php
          $i=0;
          foreach($var_outbound_d as $row){
            $qty_rem = $row['qty_to_ship']-$row['qty_to_packed'];

            if($qty_rem == 0) $btn_disabled = "disabled";
            else $btn_disabled = "";

            echo "<tr>";
              echo "<td id='tbl_list_item_".$i."'>".$row['item_code']."</td>";
              echo "<td id='tbl_list_desc_".$i."'>".$row['description']."</td>";
              echo "<td id='tbl_list_qty_".$i."'>".$row['qty_to_ship']."</td>";
              echo "<td id='tbl_list_qtypack_".$i."'>".convert_number2($row['qty_to_packed'])."</td>";
              echo "<td id='tbl_list_qtyrem_".$i."'>".convert_number2($qty_rem)."</td>";
              echo "<td id='tbl_list_uom_".$i."'>".$row['uom']."</td>";
              echo "<td id='tbl_list_dest_".$i."'>".$row['dest_no']."</td>";
              echo "<td id='tbl_list_so_".$i."'>".$row['so_no']."</td>";
              echo "<td id='tbl_list_line_no_".$i."'>".$i."</td>";
              echo "<td id='tbl_list_src_line_no_".$i."'>".$row['line_no']."</td>";
            echo "</tr>";
            $i++;
          }
          ?>
        </table>
    </div>
    </div>

    <input type="hidden" value="<?php echo $i; ?>" id="inp_total_row">

<script>

var glb_row_pack=0;

function disabled_enabled_btn_pack(line){
    if(convert_number($("#tbl_list_qtyrem_"+line).text()) <= 0) $("#btn_tbl_list_pack_"+line).attr("disabled", "disabled");
    else $("#btn_tbl_list_pack_"+line).removeAttr("disabled");
}
//---

$('#btn_add_pack').click(function(){

    if($("#modal_text_qty").val()!=""){
        if(parseFloat($("#modal_text_qty").val()) > parseFloat($("#modal_text_qty_max").val())){
            show_error("Qty you inputted greater than Qty Remaining ");
        }
        else{
            f_add_qty_to_packed($("#modal_text_index").val(), $("#modal_text_qty").val());
            $("#scan_item").focus();
        }
    }
})
//---

function f_add_qty_to_packed(index, qty){

    // check dest no.. if blank direct insert... if not blank should be same with all the dest
    if(!check_dest_no(index)){
        show_error("El número SO debe ser el mismo");
        $("#myModalPack").modal("hide");
    }
    else{
        text = add_row(index, qty);
        $("#table_pack_list").append(text);
        calculate_qty_list(index,qty,"plus");
        check_if_packlist_not_empty();
        disabled_enabled_btn_pack(index);
        $("#myModalPack").modal("toggle");
    }
}
//----

function calculate_qty_list(i,qty,condition){
    qty = parseFloat(qty);
    qty_rem = parseFloat($("#tbl_list_qtyrem_"+i).text());
    qty_pack = parseFloat($("#tbl_list_qtypack_"+i).text());

    if(condition == "plus"){
      qty_rem = qty_rem - qty;
      qty_pack = qty_pack + qty;
    }
    else if(condition == "minus"){
      qty_rem = qty_rem + qty;
      qty_pack = qty_pack - qty;
    }

    $("#tbl_list_qtyrem_"+i).text(qty_rem);
    $("#tbl_list_qtypack_"+i).text(qty_pack);
}
//---

function add_row(index, qty){
    var text = "";
    text = text + "<tr id='tbl_pack_list_row_"+glb_row_pack+"'>";
    text = text + "<td id='tbl_pack_list_item_code_"+glb_row_pack+"'>"+$('#tbl_list_item_'+index).text()+"</td>";
    text = text + "<td id='tbl_pack_list_desc_"+glb_row_pack+"'>"+$('#tbl_list_desc_'+index).text()+"</td>";
    text = text + "<td id='tbl_pack_list_qty_"+glb_row_pack+"'>"+qty+"</td>";
    text = text + "<td id='tbl_pack_list_uom_"+glb_row_pack+"'>"+$('#tbl_list_uom_'+index).text()+"</td>";
    text = text + "<td id='tbl_pack_list_dest_"+glb_row_pack+"'>"+$('#tbl_list_dest_'+index).text()+"</td>";
    text = text + "<td id='tbl_pack_list_so_"+glb_row_pack+"'>"+$('#tbl_list_so_'+index).text()+"</td>";
    text = text + "<td><button class='btn btn-danger btn-sm' id='btn_tbl_pack_delete' onclick=f_tbl_pack_delete("+glb_row_pack+","+qty+")>X</td>";
    text = text + "<td>"+index+"</td>";
    text = text + "<td>"+glb_row_pack+"</td>";
    text = text + "<td id='tbl_pack_list_src_line_no_"+glb_row_pack+"'>"+$('#tbl_list_src_line_no_'+index).text()+"</td>";
    text = text + "</tr>";

    glb_row_pack++;

    return text;
}
//--


function f_tbl_pack_delete(line, qty){
    calculate_qty_list(index,qty,"minus");
    $("#tbl_pack_list_row_"+line).remove();
    check_if_packlist_not_empty();
    disabled_enabled_btn_pack(line);
}
//----

function f_key_add(event){
    if(event.keyCode == 13){
        $('#btn_add_pack').click();
    }
}
//---

function isNumberKey_local(evt){
    if(evt.keyCode == 13){
        $('#btn_add_pack').click();
    }

    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))
        return false;

    return true;
}
//---

function check_dest_no(index){
    if(glb_row_pack == 0){
        return true;
    }
    else{
      var check = 1;
      var dest = $("#tbl_list_so_"+index).text();
      for(i=0;i<=glb_row_pack;i++){
          if(check_if_id_exist("#tbl_pack_list_row_"+i)){
              if(dest == "") dest = $("#tbl_pack_list_so_"+i).text();
              else{
                 if(dest != $("#tbl_pack_list_so_"+i).text()){
                   check = 0;
                 }
              }
          }
      }
    }

    if(check == 0) return false;
    else return true;
}
//---

function f_number_key(number){
    if(parseInt(number) >= 0 && parseInt(number)<=9){
       var temp = $("#modal_text_qty").val();
       if(temp == "" && parseInt(number)==0){
          $("#modal_text_qty").focus();
       }
       else{
         temp = temp + number;
         $("#modal_text_qty").val(temp);
         $("#modal_text_qty").focus();
       }
    }
    else if(number == "backspace"){
        var temp = $("#modal_text_qty").val();
        temp = temp.slice(0, -1);
        $("#modal_text_qty").val(temp);
        $("#modal_text_qty").focus();
    }
}
//---

</script>
