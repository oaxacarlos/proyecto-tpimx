<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      New Outbound
</div>

<div class="row" style="margin-top:20px; margin-left:10px;">
  <div class="col-1">
    Date
    <input type="text" class="form-control" id="inp_doc_date" value="<?php echo date("Y-m-d"); ?>" readonly>
  </div>

  <div class="col-2"> Location
    <?php
      /*echo "<select id='inp_location' class='form-control'>";
      foreach($var_location as $row){
        echo "<option value='".$row["code"]."'>".$row["name"]."</option>";
      }
      echo "</select>";*/

      $user_plant = get_plant_code_user();
      $new_user_plant = explode(",",$user_plant);

      echo "<select id='inp_location' class='form-control'>";
        foreach($var_location as $row){
            for($i=0; $i<count($new_user_plant); $i++){
                $temp = trim($new_user_plant[$i],"\'");
                if($row["code"] == $temp){
                    echo "<option value='".$row["code"]."'>".$row["name"]."</option>";
                }
            }
        }
      echo "</select>";
    ?>
  </div>

  <div class="col-4">
    External Document No
    <input type="text" class="form-control" id="inp_ext_doc" value="">
  </div>

  <div class="col-1">
    <span class="badge badge-success">Process OutBound</span><br>
    <button class="btn btn-primary" id="btn_process">PROCESS</button>
  </div>
</div>

<div class="row" style="margin-top:20px; margin-left:10px;">
  <div class="col-2" style="color:red;">
    Use Customer 9990001 for adjustment
  </div>
</div>

<div class="row" style="margin-top:20px; margin-left:10px;">
    <table class="table table-bordered table-striped" id="tbl_item" style="width:80%;">
      <thead>
        <tr>
          <th colspan='8'>Search Item<input type="text" id="search_item" class="form-control" onkeypress='return get_item(event)' ></th>
        </tr>
        <tr class="table-info">
          <th>Item</th>
          <th>Desc</th>
          <th>Uom</th>
          <th>Location</th>
          <th>Source No</th>
          <th>Source Line No</th>
          <th>Dest No</th>
          <th>Qty</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
</div>

<?php

unset($autocomplete);

$i=0;
foreach($var_item as $row){
    $value2 = $row['code']."|".$row['name']."|".$row['uom'];
    $autocomplete[$i] = $value2;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);

?>

<script>

var glb_index = 2;
var autocomplete = <?php echo $js_array_autocomplete; ?>;

$(document).ready(function() {
  $("#search_item").autocomplete({ source: autocomplete });

  $("#search_item").keyup(function() {
      this.value = this.value.toLocaleUpperCase();
  });
})
//---

function get_item(event){
    if(event.keyCode == 13){
        if($("#search_item").val() == "") return false;

        var item = $("#search_item").val();
        var new_item = item.split("|");
        f_add_row(new_item[0], new_item[1], new_item[2]);
        $("#search_item").val("");
        $("#search_item").focus();
    }
}
//---

function f_add_row(item_code, desc, uom){
      var text = "";
      text = text + "<tr id='tbl_item_row_"+glb_index+"'>";
        text = text + "<td id='tbl_item_code_"+glb_index+"'>"+item_code+"</td>";
        text = text + "<td id='tbl_item_name_"+glb_index+"'>"+desc+"</td>";
        text = text + "<td id='tbl_item_uom_"+glb_index+"'>"+uom+"</td>";
        text = text + "<td id='tbl_item_loc_"+glb_index+"'>"+$("#inp_location").val()+"</td>";
        text = text + "<td><input type='text' id='tbl_item_src_no_"+glb_index+"' class='form-control' placeholder='Sales Order (optional)'></td>";
        text = text + "<td><input type='text' id='tbl_item_src_line_no_"+glb_index+"' class='form-control' placeholder='Sales Order Line (optional)'></td>";
        text = text + "<td><input type='text' id='tbl_item_dest_no_"+glb_index+"' class='form-control'placeholder='ShipToCode / CustomerCode'></td>";
        text = text + "<td><input type='text' id='tbl_item_qty_"+glb_index+"' class='form-control' onkeypress='return isNumberKey(event)' ></td>";
        text = text + "<td><button class='btn btn-danger btn-sm' onclick=f_delete_item("+glb_index+")>X</button></td>";
      text = text + "</tr>";
      $("#tbl_item").append(text);
      glb_index++;
}
//---

function f_delete_item(i){  $("#tbl_item_row_"+i).remove(); }
//--

$("#btn_process").click(function(){

      // check item not blank
      if(!f_check_item_not_blank()){
        show_error("No Item");
        return false;
      }

      // check all qty has filled
      var check_qty = f_check_if_all_qty_has_filled();
      if(check_qty != true){
          show_error("No Tiene QTY en Item "+$("#tbl_item_code_"+check_qty).text());
          return false;
      }

      // process
      f_process();
})
//---

function f_check_item_not_blank(){
    if(glb_index == 0) return false;

    check = 0;
    for(i=0;i<=glb_index;i++){
      if(check_if_id_exist($("#tbl_item_code_"+i))){
          check = 1;
          break;
      }
    }

    if(check == 1) return true;
    else return false;
}
//--

function f_check_if_all_qty_has_filled(){
    check = 1;
    for(i=0;i<=glb_index;i++){
      if(check_if_id_exist($("#tbl_item_qty_"+i))){
          if($("#tbl_item_qty_"+i).val()==''){
             check = 0;
             break;
          }
      }
    }

    if(check == 1) return true;
    else return i;
}
//--

function f_process(){
  swal({
    title: "Are you sure ?",
    html: "Proceed this OutBound",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
      if(result.value){

          // get all data
          var ext_doc = $("#inp_ext_doc").val();
          var h_loc = $("#inp_location").val();
          var item=[]; var name=[]; var uom=[]; var loc=[]; var src_no=[];
          var qty=[]; var src_line_no=[]; var dest_no=[];

          var ii = 0;

          for(i=0;i<glb_index;i++){
              if(check_if_id_exist($("#tbl_item_code_"+i))){
                  item[ii] = $("#tbl_item_code_"+i).text();
                  name[ii] = $("#tbl_item_name_"+i).text();
                  uom[ii] = $("#tbl_item_uom_"+i).text();
                  loc[ii] = $("#tbl_item_loc_"+i).text();
                  src_no[ii] = $("#tbl_item_src_no_"+i).val();
                  qty[ii] = $("#tbl_item_qty_"+i).val();
                  src_line_no[ii] = $("#tbl_item_src_line_no_"+i).val();
                  dest_no[ii] = $("#tbl_item_dest_no_"+i).val();
                  ii++;
              }
          }
          //---

          $.ajax({
              url  : "<?php echo base_url();?>index.php/wms/outbound/newoutbound/create_new",
              type : "post",
              dataType  : 'html',
              data : {loc:JSON.stringify(loc), item:JSON.stringify(item) , name:JSON.stringify(name), uom:JSON.stringify(uom) , qty:JSON.stringify(qty), src_no:JSON.stringify(src_no), ext_doc:ext_doc, h_loc:h_loc, src_line_no:JSON.stringify(src_line_no), dest_no:JSON.stringify(dest_no) },
              success: function(data){
                  var responsedata = $.parseJSON(data);

                  if(responsedata.status == 1){
                        swal({
                           title: responsedata.msg,
                           type: "success", confirmButtonText: "OK",
                        }).then(function(){
                          setTimeout(function(){
                            $('#loading_body').hide();
                            window.location.href = "<?php echo base_url();?>index.php/wms/outbound/newoutbound";
                          },100)
                        });
                  }
                  else if(responsedata.status == 0){
                      Swal('Error!',responsedata.msg,'error');
                      $('#loading_body').hide();
                  }
              }
          })

      }
  })
}
//---

</script>
