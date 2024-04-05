<table class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th colspan='4' style="text-align:center;" id='tbl_rack'><?php echo $location ?></th>
    </tr>
    <tr class="table-info">
      <th>Item Code</th>
      <th>Status</th>
      <th>Qty</th>
      <th style="width:300px;">INVENTARIO (CANTIDAD)</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $i=0;
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td id='tbl_item_".$i."'>".$row["item_code"]."</td>";
            echo "<td>".$row["name"]."</td>";
            echo "<td>".$row["qty"]."</td>";
            echo "<td>
                    <span style='width:50px;'><input type='text' id='inp_qty_".$i."' size='10' onkeypress='return isNumberKey2(event)'></span>
                    <span><button class='btn btn-success btn-sm' onclick=f_assign_qty(".$i.",'".$row["qty"]."')>MISMO</button></span>
                    <span><button class='btn btn-danger btn-sm' onclick=f_reset(".$i.")>CLEAR</button></span>
                  </td>";
          echo "</tr>";

          $i++;
      }
    ?>
  </tbody>
</table>

<button class="btn btn-primary btn-lg" onclick=f_add()>+</button>

<table class="table table-bordered" id="tbl_input" style="margin-top:10px;">
  <thead>
    <tr class="table-warning">
      <th>Item Code</th>
      <th style="width:300px;">INVENTARIO (CANTIDAD)</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<!-- // 2023-09-12 -->
<div class="row">
  <div class="col-2">
    <button class="btn btn-info btn-lg text-right" id="btn_save">SAVE</button>
  </div>
  <div class="col-2">
    <input type="checkbox" id='inp_type' class='form-control'>DISCREPANCY
  </div>
</div>
<!--  -->

<input type="hidden" id="inp_total_row" value="<?php echo $i; ?>">

<?php

unset($autocomplete_item);

$j=0;
foreach($var_item as $row){
    $value2 = $row['code']."|".$row["name"];
    $autocomplete_item[$j] = $value2;
    $j++;
}

$js_array_autocomplete_item = json_encode($autocomplete_item);

?>

<script>

var glb_idx = 0;

var autocomplete_item = <?php echo $js_array_autocomplete_item; ?>;

function f_assign_qty(i,qty){
    $("#inp_qty_"+i).val(qty);
}
//---

function f_reset(i){
  $("#inp_qty_"+i).val("");
}
//--

function f_add(){
    var text = "";

    text = text + "<tr id='tbl_input_row_"+glb_idx+"'>";
      text = text + "<td><input type='text' class='form-control' id='tbl_input_item_"+glb_idx+"'></td>";
      text = text + "<td><input type='text' class='form-control' id='tbl_input_qty_"+glb_idx+"' onkeypress='return isNumberKey2(event)'></td>";
      text = text + "<td><button class='btn btn-danger' onclick=f_delete("+glb_idx+")>X</button></td>";
    text = text + "</tr>";

    $("#tbl_input").append(text);

    var new_autocomplete = [];
    var counter = 0;

    for(i=0;i<autocomplete_item.length;i++){
      new_autocomplete[counter] = autocomplete_item[i];
      counter++;
    }

    $("#tbl_input_item_"+glb_idx).autocomplete({ source: new_autocomplete });

    glb_idx++;
}
//--

function f_delete(i){
  $("#tbl_input_row_"+i).remove();
}
//---

$("#btn_save").click(function(){

  // initial
  var counter = 0;
  var data_item = [];
  var data_qty  = [];

  // check existing
  var check_existing = 0;
  var total_row = $("#inp_total_row").val();
  for(i=0;i<total_row;i++){
      if($("#inp_qty_"+i).val() != ""){
          qty = parseInt($("#inp_qty_"+i).val());
          data_item[counter]  = $("#tbl_item_"+i).text();
          data_qty[counter]   = qty;
          check_existing = 1;
          counter++;
      }
  }
  //---

  // check new
  var check_new = 0;
  for(i=0;i<glb_idx;i++){
      if(check_if_id_exist("#tbl_input_item_"+i)){
          if($("#tbl_input_qty_"+i).val()!=''){
              qty = parseInt($("#tbl_input_qty_"+i).val());
              data_item_temp = $("#tbl_input_item_"+i).val();
              data_item_temp = data_item_temp.split("|");
              data_item[counter]  = data_item_temp[0];
              //data_item[counter]  = $("#tbl_input_item_"+i).val();
              data_qty[counter]   = qty;
              check_new = 1;
              counter++;
          }
      }
  }
  //---

  if(check_existing == 0 && check_new == 0){
      show_error("No Has ingresado nada");
      return false;
  }

  // if everything is ok
  var rack = $("#tbl_rack").text();

  // 2023-09-12
  if($("#inp_type").is(":checked")==true) type = '2';
  else type = '1';
  //--


  swal({
    title: "Estas Seguro ?",
    html: "Los Datos esta bien",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {

      if(result.value){
        $("#loading_text").text("Insertando, Espera Por Favor...");
        $('#loading_body').show();

        $.ajax({
            url : "<?php echo base_url();?>index.php/wms/report/stockcount/save",
            type : 'post',
            dataType : 'html',
            data : {data_item:JSON.stringify(data_item), data_qty:JSON.stringify(data_qty), rack:rack, type:type },
            success:function(data){
                responsedata = $.parseJSON(data);

                if(responsedata.status == 1){
                    swal({
                       title: responsedata.msg,
                       type: "success", confirmButtonText: "OK",
                    }).then(function(){
                      setTimeout(function(){
                        glb_idx=0;
                        $("#report_view").empty();
                        $('#loading_body').hide();

                      },100)
                    });
                }
                else{
                    show_error("Insert Error");
                }
            }
        })
      }
  })
  //--

})
//--

function isNumberKey2(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;

    if(charCode == 45) return true;
    else if(charCode == 46) return false;
    else if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))
        return false;
    return true;
}

</script>
