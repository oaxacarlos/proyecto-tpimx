
<div class="row">
  <div class="col-3">
      Doc Date
      <span><input type='text' class="form-control" value="<?php echo get_date_now(); ?>" disabled></span>
  </div>
  <div class="col-3">
      Process
      <button class="btn btn-primary" id="btn_create_packlist" disabled>Create PackList</button>
  </div>
</div>

<div class="row fontsize" style="margin-top:10px;">
  <div class="col-4">
    Item Code
    <input type="text" class="form-control fontsize" id="scan_item" onkeypress="f_check_scan_item(event)">
  </div>
  <div>

  </div>
</div>

<div class="container-fluid" style="margin-top:10px;">
  <table class="table table-bordered table-sm table-striped" id="table_pack_list">
    <thead>
      <tr>
        <th>Item Code</th>
        <th>Desc</th>
        <th>Qty</th>
        <th>Uom</th>
        <th>Sent To</th>
        <th>SO</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<div class="modal" id="myModalItemPack">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Item Packing</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_item_pack">
        <table class="table" style='font-size:14px;'>
          <thead>
            <tr>
              <th style='font-size:14px; width:200px;'>Packing Material</th>
              <th style='font-size:14px;'>Qty</th>
            </tr>
          <thead>
          <tbody>
            <?php
              $i=0;
              foreach($var_data_item_pack as $row){
                  echo "<tr>";
                    echo "<td style='font-size:14px;' id='pack_item_code_".$i."'>".$row["code"]."</td>";
                    echo "<td><input type='text' id='inp_pack_item_".$i."' class='form-control' onkeypress='return isNumberKey(event)'></td>";
                  echo "</tr>";
                  $i++;
              }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <td></td><td><button class="btn btn-primary btn-block" id="btn_process_packing">PROCESS</button></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<input type="hidden" value="<?php echo count($var_data_item_pack); ?>" id="inp_total_item_pack">

<script>

var glb_row_pack = 0;

function intial_pack(){
   $("#scan_item").focus();
}
intial_pack();

function check_if_packlist_not_empty(){
    var row = document.getElementById("table_pack_list").rows.length;

    if(row <= 1) $("#btn_create_packlist").attr("disabled", "disabled");
    else $("#btn_create_packlist").removeAttr("disabled");
}
//---

function f_check_scan_item(event){
    if(event.keyCode == 13){
        var item = $("#scan_item").val();
        f_check_in_list(item);

    }
}
//---

function f_check_in_list(item){
    var total_row = $("#inp_total_row").val();

    found = 0;
    for(i=0;i<total_row;i++){
        if($("#tbl_list_item_"+i).text() == item && (parseFloat($("#tbl_list_qtyrem_"+i).text()) > 0)){
          found = 1;
          index = i;
          break;
        }
    }

    if(found == 0){
        show_error("No Item on the list");
    }
    else{
        $("#modal_text_item").text(item);
        $("#modal_text_qty_max").val($("#tbl_list_qtyrem_"+index).text());
        $("#modal_text_index").val(index);
        $("#modal_text_src_line").val($("#tbl_list_src_line_no_"+index).text());
        $("#modal_text_qty").val("");
        $("#myModalPack").modal();
        $("#modal_text_qty").focus();
        $("#scan_item").val("");
    }
}
//---

//---

$("#btn_create_packlist").click(function(){

    f_item_pack_zero();
    $("#myModalItemPack").modal();

})
//---

function f_create_packing(){
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
                html: "Proceed this Packing List",
                type: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
              }).then(function (result) {
                  if(result.value){
                      $("#loading_text").text("Creating New Packing Document, Please wait...");
                      $('#loading_body').show();

                      // get variable
                      var h_doc_user = $("#h_doc_user").val();
                      var h_doc_no = $("#h_doc_no").val();
                      var h_whs = $("#h_whs").val();

                      // get packing data
                      var pack_item_code = [];
                      var pack_desc = [];
                      var pack_qty = [];
                      var pack_src_line_no = [];
                      var pack_uom = [];

                      var counter = 0;

                      for(i=0;i<glb_row_pack;i++){
                          if(check_if_id_exist("#tbl_pack_list_row_"+i)){
                              pack_item_code[counter] = $("#tbl_pack_list_item_code_"+i).text();
                              pack_desc[counter] = $("#tbl_pack_list_desc_"+i).text();
                              pack_qty[counter] = $("#tbl_pack_list_qty_"+i).text();
                              pack_src_line_no[counter] = $("#tbl_pack_list_src_line_no_"+i).text();
                              pack_uom[counter] = $("#tbl_pack_list_uom_"+i).text();

                              dest_no = $("#tbl_pack_list_dest_"+i).text();
                              ship_to_name = $("#tbl_ship_to_name_"+dest_no).text();
                              ship_to_contact = $("#tbl_ship_to_contact_"+dest_no).text();
                              ship_to_addr = $("#tbl_ship_to_addr_"+dest_no).text();
                              ship_to_addr2 = $("#tbl_ship_to_addr2_"+dest_no).text();
                              ship_to_city = $("#tbl_ship_to_city_"+dest_no).text();
                              ship_to_post_code = $("#tbl_ship_to_post_code_"+dest_no).text();
                              ship_to_county = $("#tbl_ship_to_county_"+dest_no).text();
                              ship_to_ctry_region_code = $("#tbl_ship_to_ctry_region_code_"+dest_no).text();

                              counter++;
                          }
                      }
                      //---

                      // get packing item
                      var item_pack_code = [];
                      var item_pack_qty = [];
                      var counter_pack_item = 0;

                      var total_item_pack = $("#inp_total_item_pack").val();
                      for(i=0;i<total_item_pack;i++){
                          if(check_if_id_exist("#inp_pack_item_"+i)){
                              if($("#inp_pack_item_"+i).val() != ""){
                                  item_pack_code[counter_pack_item] = $("#pack_item_code_"+i).text();
                                  item_pack_qty[counter_pack_item] = $("#inp_pack_item_"+i).val();
                                  counter_pack_item++;
                              }
                          }
                      }

                      if(counter_pack_item == 0){
                          item_pack_code[0] = "";
                          item_pack_qty[0] = "";
                      }

                      //---

                      $.ajax({
                          url  : "<?php echo base_url();?>index.php/wms/outbound/packing/create_new",
                          type : "post",
                          dataType  : 'html',
                          data : {pack_item_code:JSON.stringify(pack_item_code),pack_desc:JSON.stringify(pack_desc),  pack_qty:JSON.stringify(pack_qty), pack_src_line_no:JSON.stringify(pack_src_line_no), pack_uom:JSON.stringify(pack_uom),
                          h_doc_no:h_doc_no,message:message, counter:counter, h_whs:h_whs, dest_no:dest_no, ship_to_name:ship_to_name, ship_to_contact:ship_to_contact, ship_to_addr:ship_to_addr, ship_to_addr2:ship_to_addr2, ship_to_city:ship_to_city, ship_to_post_code:ship_to_post_code, ship_to_county:ship_to_county, ship_to_ctry_region_code:ship_to_ctry_region_code, item_pack_code:JSON.stringify(item_pack_code), item_pack_qty:JSON.stringify(item_pack_qty) },
                          success: function(data){
                              var responsedata = $.parseJSON(data);

                              if(responsedata.status == 1){
                                    swal({
                                       title: responsedata.msg,
                                       type: "success", confirmButtonText: "OK",
                                    }).then(function(){
                                      setTimeout(function(){
                                        $('#myModalItemPack').modal("hide");
                                        f_refresh_list_outbound();
                                        f_load_list_pack();
                                        f_load_list_packed();

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
//--


function f_item_pack(){
  var link = 'wms/outbound/packing/v_item_pack';
  $('#modal_detail_item_pack').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail_item_pack').load(
      "<?php echo base_url();?>index.php/wms/outbound/packing/get_item_pack",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalItemPack').modal();
}
//----

function f_item_pack_zero(){
    var total_item_pack = $("#inp_total_item_pack").val();
    for(i=0;i<total_item_pack;i++){
        if(check_if_id_exist("#inp_pack_item_"+i)){
            $("#inp_pack_item_"+i).val("");
        }
    }
}
//----

$("#btn_process_packing").click(function(){

      // check if item packing is exist.. if not exist direct to process packing
      if(!f_check_item_pack_if_exist()){ f_create_packing(); }
      else if(f_check_if_all_item_pack_not_fill()){ f_create_packing(); }
      else{
          // check stock
          if(f_check_stock_item_pack() == true){
              f_create_packing();
          }
      }

});
//---

function f_check_item_pack_if_exist(){
    var check_item_pack = 0;
    var total_item_pack = $("#inp_total_item_pack").val();
    for(i=0;i<total_item_pack;i++){
        if(check_if_id_exist("#inp_pack_item_"+i)){
            check_item_pack = 1;
            break;
        }
    }

    return check_item_pack;
}
//---

function f_check_if_all_item_pack_not_fill(){
    var check_item_pack = 1;
    var total_item_pack = $("#inp_total_item_pack").val();
    for(i=0;i<total_item_pack;i++){
        if(check_if_id_exist("#inp_pack_item_"+i)){
            if($("#inp_pack_item_"+i).val() != ""){
                check_item_pack = 0;
                break;
            }
        }
    }

    return check_item_pack;
}
//---

function f_check_stock_item_pack(){
  var item_pack_code = [];
  var item_pack_qty = [];
  var counter = 0;
  var message_error = "";

  var total_item_pack = $("#inp_total_item_pack").val();
  for(i=0;i<total_item_pack;i++){
      if(check_if_id_exist("#inp_pack_item_"+i)){
          if($("#inp_pack_item_"+i).val() != ""){
              item_pack_code[counter] = $("#pack_item_code_"+i).text();
              item_pack_qty[counter] = $("#inp_pack_item_"+i).val();
              counter++;
          }
      }
  }

  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/outbound/packing/check_item_pack_stock",
      type : "post",
      dataType  : 'html',
      async : false,
      data : { item_pack_code:JSON.stringify(item_pack_code), item_pack_qty:JSON.stringify(item_pack_qty)},
      success: function(data){
          var responsedata = $.parseJSON(data);
          console.log(responsedata);
          if(responsedata.nostock != 0){
              for(i=0; i<responsedata.nostock.length; i++){
                  message_error = message_error + responsedata.nostock[i] + ",";
              }
              check = 0;
          }
          else{
              check = 1;
          }
      }
  })

  if(check == 0){
      show_error("No Stock = "+message_error);
      return false;
  }
  else{
      return true;
  }
}
//---


</script>
