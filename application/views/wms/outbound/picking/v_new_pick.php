
<div class="row">
  <div class="col-3">
      Doc Date
      <span><input type='text' class="form-control" value="<?php echo get_date_now(); ?>" disabled></span>
  </div>
  <div class="col-3">
    User Picking
    <select name='user_list' id='h_doc_user' class="form-control">
      <option value='-'>-</option>
        <?php
          foreach($user_list as $row){
            echo "<option value='".$row['user_id']."'>".$row['name']."</option>";
          }
        ?>
    </select>
  </div>
  <div class="col-3">
    User QC
    <?php
      if($qc_user_id != "" or !is_null($qc_user_id)) $disabled = "disabled";
      else $disabled = "";
    ?>
    <select name='user_list_qc' id='h_doc_user_qc' class="form-control" <?php echo $disabled; ?>>
      <option value='-'>-</option>
        <?php
          foreach($user_list_qc as $row){
              $selected = "";
              if($row["user_id"] == $qc_user_id){
                $selected = "selected";
              }
              echo "<option value='".$row['user_id']."' ".$selected.">".$row['name']."</option>";
          }
        ?>
    </select>
  </div>
  <div class="col-3">
      Process
      <button class="btn btn-primary" id="btn_create_picklist" disabled>Create PickList</button>
  </div>
</div>

<div class="container-fluid" style="margin-top:10px;">
  <button class="btn btn-warning" onclick=f_auto_pick()>AUTO</button>
</div>

<div class="container-fluid" style="margin-top:10px;">
  <table class="table table-bordered table-sm table-striped" id="table_pick_list">
    <thead>
      <tr>
        <th>Item Code</th>
        <th>Desc</th>
        <th>Loc</th>
        <th>Zone</th>
        <th>Area</th>
        <th>Rack</th>
        <th>Bin</th>
        <th>Pick</th>
        <th>Uom</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<script>

var glb_row_pick = 0;

function check_if_picklist_not_empty(){
    var row = document.getElementById("table_pick_list").rows.length;

    if(row <= 1) $("#btn_create_picklist").attr("disabled", "disabled");
    else $("#btn_create_picklist").removeAttr("disabled");
}
//---

$("#btn_create_picklist").click(function(){

  var h_doc_no = $("#h_doc_no").val();

  if($("#h_doc_user").val() == '-'){
      show_error("You haven't choosen the User Picking");
  }
  else if($("#h_doc_user_qc").val() == '-'){
      show_error("You haven't choosen the User QC");
  }
  else{

    //var check_picking = check_if_the_shipment_already_picked(h_doc_no);
    if(check_if_the_shipment_already_picked(h_doc_no) == 0){
        //show_error("No puede seleccionar este envío, es posible que ya se haya seleccionado");
    }
    else{
      swal({
        input: 'textarea',
        inputPlaceholder: 'Type your message here',
        showCancelButton: true,
        confirmButtonText: 'OK'
      }).then(function (result) {
            if(result.dismiss == "cancel"){}
            else{
              if(result.value == ""){ show_error("You have to type message");}
              else{
                  var message = result.value;

                  swal({
                    title: "Are you sure ?",
                    html: "Proceed this Picking List",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                  }).then(function (result) {
                      if(result.value){

                          $("#loading_text").text("Getting data, Please wait...");

                          // get variable
                          var h_doc_user = $("#h_doc_user").val();
                          var h_doc_no = $("#h_doc_no").val();
                          var h_whs = $("#h_whs").val();
                          var h_doc_user_qc = $("#h_doc_user_qc").val();

                          // get picking data
                          var pick_item_code = [];
                          var pick_desc = [];
                          var pick_loc_code = [];
                          var pick_zone_code = [];
                          var pick_area_code = [];
                          var pick_rack_code = [];
                          var pick_bin_code = [];
                          var pick_qty = [];
                          var pick_src_line_no = [];
                          var pick_uom = [];

                          var counter = 0;

                          for(i=0;i<glb_row_pick;i++){
                              if(check_if_id_exist("#tbl_pick_list_row_"+i)){
                                  pick_item_code[counter] = $("#tbl_pick_list_item_code_"+i).text();
                                  pick_desc[counter] = $("#tbl_pick_list_desc_"+i).text();
                                  pick_loc_code[counter] = $("#tbl_pick_list_loc_code_"+i).text();
                                  pick_zone_code[counter] = $("#tbl_pick_list_zone_code_"+i).text();
                                  pick_area_code[counter] = $("#tbl_pick_list_area_code_"+i).text();
                                  pick_rack_code[counter] = $("#tbl_pick_list_rack_code_"+i).text();
                                  pick_bin_code[counter] = $("#tbl_pick_list_bin_code_"+i).text();
                                  pick_qty[counter] = $("#tbl_pick_list_qty_"+i).text();
                                  pick_src_line_no[counter] = $("#tbl_pick_list_src_line_no_"+i).text();
                                  pick_uom[counter] = $("#tbl_pick_list_uom_"+i).text();
                                  counter++;
                              }
                          }
                          //---

                          // Checking stock..
                          $("#loading_text").text("Checking Stocks, Please wait...");
                          $.ajax({
                              url  : "<?php echo base_url();?>index.php/wms/outbound/picking/checking_stock_item_invt",
                              type : "post",
                              dataType  : 'html',
                              data : {item_code:JSON.stringify(pick_item_code), qty:JSON.stringify(pick_qty) },
                              success: function(data){
                                  var responsedata = $.parseJSON(data);
                                  if(responsedata.status == 1){
                                      //show_error(responsedata.msg);
                                      $('#loading_body').hide();
                                      return false;
                                  }
                              }
                          })
                          //---

                          // if stock available.. create picking
                          $("#loading_text").text("Creating New Picking Document, Please wait...");
                          $('#loading_body').show();
                          //---


                          $.ajax({
                              url  : "<?php echo base_url();?>index.php/wms/outbound/picking/create_new",
                              type : "post",
                              dataType  : 'html',
                              data : {pick_item_code:JSON.stringify(pick_item_code),pick_desc:JSON.stringify(pick_desc), pick_loc_code:JSON.stringify(pick_loc_code), pick_zone_code:JSON.stringify(pick_zone_code), pick_area_code:JSON.stringify(pick_area_code), pick_rack_code:JSON.stringify(pick_rack_code), pick_bin_code:JSON.stringify(pick_bin_code), pick_qty:JSON.stringify(pick_qty), pick_src_line_no:JSON.stringify(pick_src_line_no), pick_uom:JSON.stringify(pick_uom),
                              h_doc_user:h_doc_user,h_doc_no:h_doc_no,
                              message:message, counter:counter, h_whs:h_whs, h_doc_user_qc:h_doc_user_qc},
                              success: function(data){
                                  var responsedata = $.parseJSON(data);

                                  if(responsedata.status == 1){
                                        swal({
                                           title: responsedata.msg,
                                           type: "success", confirmButtonText: "OK",
                                        }).then(function(){
                                          setTimeout(function(){
                                            f_refresh_list_outbound();
                                            f_load_list_pick();
                                            $('#loading_body').hide();
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
            }
      })
    }
  }

})
//---

function check_if_the_shipment_already_picked(doc_no){

    var pick_item_code = [];
    var pick_qty = [];
    var pick_src_line_no = [];
    var counter=0;

    for(i=0;i<glb_row_pick;i++){
        if(check_if_id_exist("#tbl_pick_list_row_"+i)){
            pick_item_code[counter] = $("#tbl_pick_list_item_code_"+i).text();
            pick_qty[counter] = $("#tbl_pick_list_qty_"+i).text();
            pick_src_line_no[counter] = $("#tbl_pick_list_src_line_no_"+i).text();
            counter++;
        }
    }

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/outbound/picking/check_already_picking_by_shipment",
        type : "post",
        dataType  : 'html',
        async: false,
        data : {doc_no:doc_no,pick_item_code:JSON.stringify(pick_item_code),pick_qty:JSON.stringify(pick_qty), pick_src_line_no:JSON.stringify(pick_src_line_no) },
        success: function(data){
            responsedata = $.parseJSON(data);
        }
    })

    if(responsedata.status == 1){ return 1; }
    else if(responsedata.status == 0){
        show_error("No puede seleccionar este envío, es posible que ya se haya seleccionado = "+ responsedata.msg);
        return 0;
    }
}
//--

// 2023-08-17
function f_auto_pick(){
  swal({
    title: "Are you sure ?",
    html: "Auto Pick",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
      if(result.value){

          $("#loading_text").text("Processing AUTO, Please wait...");
          $("#loading_body").show();


          setTimeout(() => {
            var total_row = parseInt($("#total_outbound_d").val());
            var label_no = "<i class='bi bi-x-lg' style='font-size:20px; color:red;'></i>";
            var label_yes = "<i class='bi bi-check-lg' style='font-size:20px; color:green;'></i>";
            var label_finish = "<i class='bi bi-flag-fill' style='font-size:20px; color:blue;'></i>";
            var auto = "";
            var first = 1;

            for(i=0;i<total_row;i++){
                if(first == 1){
                    f_pick($("#tbl_list_item_"+i).text(),i);
                    $('#myModalPick').modal('toggle');
                }

                status = 1;

                // check if qty remaining < 0
                if(parseInt($("#tbl_list_qtyrem_"+i).text()) <= 0){
                  status = 0;
                  auto = label_finish ;
                }
                //--

                // check if stock available
                if(status == 1){
                  if($("#tbl_list_available_"+i).text() == "NO"){
                      status = 0;
                      auto = label_no ;
                  }
                }
                //--

                // check if conversion is ok
                if(status == 1){
                  var str = $("#tbl_list_conversion_"+i).text();
                  var substr = "NO";

                  if(str.includes(substr)){
                      status = 0;
                      auto = label_no ;
                  }
                }
                //---

                // check if rack only one if yes and pick
                if(status == 1){
                    var item = $("#tbl_list_item_"+i).text();
                    var qty = $("#tbl_list_qtyrem_"+i).text();
                    var h_whs = $("#h_whs").val();
                    result_check = check_auto_pick_with_one_rack(item, qty,h_whs,i,label_yes,label_no);
                    if(result_check.status == 0){
                        status = 0;
                        auto = label_no ;
                    }
                    else{
                        line = $("#tbl_list_line_no_"+i).text();
                        auto_add_row(i,line,result_check,qty,h_whs);
                        auto = label_yes;
                    }
                }

                $("#tbl_list_auto_"+i).html(auto);

                first++;
            }

            $("#loading_body").hide();
          }, "1000");
      }
  })
}
//---

function check_auto_pick_with_one_rack(item, qty,h_whs, i,label_yes,label_no){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/outbound/picking/check_auto_pick",
        type : "post",
        dataType  : 'html',
        async: false,
        data : {item:item, h_whs:h_whs, qty:qty},
        success: function(data){
            responsedata = $.parseJSON(data);

        }
    })

    return responsedata;
}
//---

function auto_add_row(i,line,loc,qty,whs){
    var text = "";
    text = text + "<tr id='tbl_pick_list_row_"+glb_row_pick+"'>";
    text = text + "<td id='tbl_pick_list_item_code_"+glb_row_pick+"'>"+$('#tbl_list_item_'+i).text()+"</td>";
    text = text + "<td id='tbl_pick_list_desc_"+glb_row_pick+"'>"+$('#tbl_list_desc_'+i).text()+"</td>";
    text = text + "<td id='tbl_pick_list_loc_code_"+glb_row_pick+"'>"+loc.loc+"</td>";
    text = text + "<td id='tbl_pick_list_zone_code_"+glb_row_pick+"'>"+loc.zone+"</td>";
    text = text + "<td id='tbl_pick_list_area_code_"+glb_row_pick+"'>"+loc.area+"</td>";
    text = text + "<td id='tbl_pick_list_rack_code_"+glb_row_pick+"'>"+loc.rack+"</td>";
    text = text + "<td id='tbl_pick_list_bin_code_"+glb_row_pick+"'>"+loc.bin+"</td>";
    text = text + "<td id='tbl_pick_list_qty_"+glb_row_pick+"'>"+qty+"</td>";
    text = text + "<td id='tbl_pick_list_uom_"+glb_row_pick+"'>"+$('#tbl_list_uom_'+i).text()+"</td>";
    text = text + "<td><button class='btn btn-danger btn-sm' id='btn_tbl_pick_delete' onclick=f_tbl_pick_delete("+line+","+$('#inp_pick_qty_pick_'+i).val()+","+glb_row_pick+")>X</td>";
    text = text + "<td style='display:none;'>"+line+"</td>";
    text = text + "<td style='display:none;'>"+glb_row_pick+"</td>";
    text = text + "<td id='tbl_pick_list_src_line_no_"+glb_row_pick+"' style='display:none;'>"+$('#tbl_list_src_line_no_'+i).text()+"</td>";
    text = text + "</tr>";

    glb_row_pick++;

    $("#table_pick_list").append(text);

    auto_calculate_qty_pick(line,qty);
    auto_calculate_qty_rem(line,qty);
    check_if_picklist_not_empty();
    disabled_enabled_btn_pick(line);
}
//--

function auto_calculate_qty_pick(line,total_qty){
    // qty pick
    var qty_pick = parseFloat($('#tbl_list_qtypick_'+line).text());
    var qty_pick = qty_pick + parseFloat(total_qty);
    $("#tbl_list_qtypick_"+line).text(qty_pick);
}
//---

function auto_calculate_qty_rem(line,total_qty){
    // get qty_remain
    var qty_remain = parseFloat($('#tbl_list_qtyrem_'+line).text());
    var qty_remain = qty_remain - total_qty;
    $("#tbl_list_qtyrem_"+line).text(qty_remain);
}
//----



</script>
