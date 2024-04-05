<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Item Request
</div>

<div class="row" style="margin-top:20px; margin-left:10px;">
  <div class="col-1">
    Date
    <input type="text" class="form-control" id="inp_doc_date" value="<?php echo date("Y-m-d"); ?>" readonly>
  </div>

  <div class="col-2"> Location
    <?php
      echo "<select id='inp_location' class='form-control'>";
      foreach($var_location as $row){
        echo "<option value='".$row["code"]."'>".$row["name"]."</option>";
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
  <div class="col-9" style="color:red;">
    Search Address (Use Customer 9990001 if you don't want refer to any customer)
    <input type="text" id="search_address" class="form-control" onkeypress='return get_item(event)' >
  </div>
</div>

<div class="row" style="margin-top:20px; margin-left:10px;">
  <div class="col-1">
    Same Address
    <input type="checkbox" id="inp_same_address" onclick=f_same_address()>
  </div>
</div>

<div class="row border" style="margin-top:20px; margin-left:10px; padding-bottom:20px;">
  <div class="col-2">
    Address
    <input type="text" class="form-control" id="inp_address" value="">
  </div>
  <div class="col-2">
    Address 2
    <input type="text" class="form-control" id="inp_address2" value="">
  </div>
  <div class="col-2">
    City
    <input type="text" class="form-control" id="inp_city" value="">
  </div>
  <div class="col-2">
    State
    <input type="text" class="form-control" id="inp_state" value="">
  </div>
  <div class="col-2">
    Contact
    <input type="text" class="form-control" id="inp_contact" value="">
  </div>
  <div class="col-2">
    Post Code
    <input type="text" class="form-control" id="inp_post_code" value="">
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

//--

unset($autocomplete);

$i=0;
foreach($var_ship_to as $row){
    $value2 = $row['cust_no']." | ".$row['ship_to_code']." | ".$row['name']." | ".$row['county']." | ".$row['address']." | ".
    $row['address2']." | ".$row['city']." | ".$row['contact']." | ".$row['post_code']." | ".$row['country_region_code'];
    $autocomplete[$i] = $value2;
    $i++;
}

$js_array_autocomplete_ship_to = json_encode($autocomplete);

?>

<script>

var glb_index = 2;
var autocomplete = <?php echo $js_array_autocomplete; ?>;
var autocomplete_ship_to = <?php echo $js_array_autocomplete_ship_to; ?>;

$(document).ready(function() {
  $("#search_item").autocomplete({ source: autocomplete });

  $("#search_item").keyup(function() {
      this.value = this.value.toLocaleUpperCase();
  });

  $("#search_address").autocomplete({ source: autocomplete_ship_to });

  $("#search_address").keyup(function() {
      this.value = this.value.toLocaleUpperCase();
  });
})
//---

function get_item(event){
    if(event.keyCode == 13){
        if($("#search_item").val() == "") return false;

        var item = $("#search_item").val();
        var new_item = item.split("|");

        if(typeof new_item[0] === 'undefined' || typeof new_item[1] === 'undefined' ||  typeof new_item[2] === 'undefined'){
          show_error("el elemento que ingresa no es información completa");
        }
        else{
            f_add_row(new_item[0], new_item[1], new_item[2]);
            $("#search_item").val("");
            $("#search_item").focus();
        }

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

      // check address if ok
      if(!f_check_address()){
        show_error("Direccion no completo");
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

  var checked_same_addres = $('#inp_same_address').is(":checked");
  if(checked_same_addres == false){
      if($("#inp_address").val() == ""){
          show_error("Necesitas ingresar ADDRESS");
          return false;
      }

      if($("#inp_address2").val() == ""){
          show_error("Necesitas ingresar ADDRESS 2");
          return false;
      }

      if($("#inp_city").val() == ""){
          show_error("Necesitas ingresar CITY");
          return false;
      }

      if($("#inp_state").val() == ""){
          show_error("Necesitas ingresar STATE");
          return false;
      }

      if($("#inp_contact").val() == ""){
          show_error("Necesitas ingresar CONTACT");
          return false;
      }

      if($("#inp_post_code").val() == ""){
          show_error("Necesitas ingresar POST CODE");
          return false;
      }
  }

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
          var address = $("#search_address").val();
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
                  ii++;
              }
          }
          //---

          // check same address
          if(checked_same_addres == false){
              var address = $("#search_address").val();
              var new_address = address.split(" | ");
              var address2 = "";

              address2 = new_address[0]+" | "+new_address[1]+" | "+new_address[2]+" | "+$("#inp_contact").val()+" | "+$("#inp_address").val()+" | "+$("#inp_address2").val()+" | "+$("#inp_city").val()+" | "+$("#inp_contact").val()+" | "+$("#inp_post_code").val()+" | "+new_address[9];

              address = address2;
          }
          //--

          $.ajax({
              url  : "<?php echo base_url();?>index.php/sales/internal/requestitem/create_new",
              type : "post",
              dataType  : 'html',
              data : {loc:JSON.stringify(loc), item:JSON.stringify(item) , name:JSON.stringify(name), uom:JSON.stringify(uom) , qty:JSON.stringify(qty), src_no:JSON.stringify(src_no), ext_doc:ext_doc, h_loc:h_loc, src_line_no:JSON.stringify(src_line_no), address:address },
              success: function(data){
                  var responsedata = $.parseJSON(data);

                  if(responsedata.status == 1){
                        swal({
                           title: responsedata.msg,
                           type: "success", confirmButtonText: "OK",
                        }).then(function(){
                          setTimeout(function(){
                            $('#loading_body').hide();
                            window.location.href = "<?php echo base_url();?>index.php/sales/internal/requestitem";
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

function f_check_address(){
    var address = $("#search_address").val();
    var new_address = address.split(" | ");

    if(new_address[0]!="" && new_address[1]!="" && new_address[2]!="" && new_address[3]!="" && new_address[4]!=""
      && new_address[5]!="" && new_address[6]!="" && new_address[8]!="" && new_address[9]!=""
    )
      return true;
    else return false;
}
//---

function f_same_address(){

    var checked = $('#inp_same_address').is(":checked");

    if(checked == true){
      var address = $("#search_address").val();
      if(address == "") show_error("No Tienes el dirrecion ingresar");
      else{
          var new_address = address.split(" | ");
          $("#inp_address").val(new_address[4]); $("#inp_address").attr("disabled", "disabled");
          $("#inp_address2").val(new_address[5]); $("#inp_address2").attr("disabled", "disabled");
          $("#inp_city").val(new_address[6]); $("#inp_city").attr("disabled", "disabled");
          $("#inp_contact").val(new_address[7]); $("#inp_contact").attr("disabled", "disabled");
          $("#inp_state").val(new_address[3]); $("#inp_state").attr("disabled", "disabled");
          $("#inp_post_code").val(new_address[8]); $("#inp_post_code").attr("disabled", "disabled");
      }
    }
    else{
        $("#inp_address").val(""); $("#inp_address").prop('disabled', false);
        $("#inp_address2").val(""); $("#inp_address2").prop('disabled', false);
        $("#inp_city").val(""); $("#inp_city").prop('disabled', false);
        $("#inp_contact").val(""); $("#inp_contact").prop('disabled', false);
        $("#inp_state").val(""); $("#inp_state").prop('disabled', false);
        $("#inp_post_code").val(""); $("#inp_post_code").prop('disabled', false);
    }
}
//---

</script>
