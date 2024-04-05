<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      New Put Away
</div>

<div class="modal" id="myModalSourceDoc">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Doc Source List</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_source_doc"></div>
    </div>
  </div>
</div>

<div class="modal" id="myModalBin">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Loc-Zone-Area-Rack-Bin</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body ui-front" id="modal_bin"></div>
    </div>
  </div>
</div>

<div class='row' style='margin-left:10px;'>
    <div class="col-md-2">
      Doc Date
      <input type='text'  class="form-control" disabled value='<?php echo date("Y-m-d"); ?>' id="h_doc_date">
    </div>
    <div class="col-md-2">
      Warehouse
      <?php
          $user_plant = get_plant_code_user();
          $new_user_plant = explode(",",$user_plant);

          echo "<select id='h_whs' name='h_whs' class='form-control'>";
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

    <div class="col-md-3">
    User
    <select name='user_list' id='h_doc_user' class="form-control">
      <option value='-'>-</option>
        <?php
          foreach($user_list as $row){
            echo "<option value='".$row['user_id']."'>".$row['name']."</option>";
          }
        ?>
    </select>
  </div>

</div>


<div class='container-fluid text-right' style='margin-top:10px'>
  <button class='btn btn-danger' onclick="f_clear_all()">Clear All</button>
  <button class='btn btn-primary' onclick="f_get_source_doc()">Get from Received Doc</button>
</div>

<?php if(getBrowser()=="Firefox") $auto_disabled="";
      else $auto_disabled = "disabled";
?>
AUTO Only Run in FireFox<br>
<button class="btn btn-info" style="margin-left:20px;" onclick=f_auto() <?php echo $auto_disabled; ?>>AUTO</button>
<div class='container-fluid' style='margin-top:10px;'>
  Document
  <table id='tbl_detail' class="table table-bordered table-striped table-sm">
    <thead>
      <tr>
        <th>Doc Received</th>
        <th>Line</th>
        <th>Loc</th>
        <th>Item Code</th>
        <th>Desc</th>
        <th>Qty Total</th>
        <th>Qty Put</th>
        <th>Qty Rem</th>
        <th>Uom</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <tr class='table-success'>
        <th colspan='5'>Total</th>
        <th id='tbl_detail_qty_total'>0</th>
        <th id='tbl_detail_qty_put'>0</th>
        <th id='tbl_detail_qty_rem'>0</th>
        <th>PZA</th>
        <th></th>
      </tr>
    </tfoot>
  </table>
</div>

<div class='container-fluid' style='margin-top:30px;'>
  Loc-Zone-Area-Rack-bin
  <table id='tbl_detail2' class="table table-bordered table-striped table-sm">
    <thead>
      <tr>
        <th>Doc Received</th>
        <th>Line</th>
        <th>Item Code</th>
        <th>Desc</th>
        <th>Qty</th>
        <th>Uom</th>
        <th>Master<br>Barcode</th>
        <th>Loc</th>
        <th>Zone</th>
        <th>Area</th>
        <th>Rack</th>
        <th>Bin</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <tr class='table-success'>
        <th colspan='4'>Total</th>
        <th id='tbl_detail2_qty_total'>0</th>
        <th>PZA</th>
        <th colspan='7'></th>
      </tr>
    </tfoot>
  </table>
</div>

<div class='container-fluid text-right' style='margin-top:10px'>
  <button class='btn btn-primary'  onclick="f_create_new_put_away()">PROCESS</button>
</div>

<?php echo loading_body_full(); ?>

<script>

var glb_counter = 0;
var glb_counter2 = 0;
var glb_loc_zone_area_rack_bin = 0;

function f_get_source_doc(){

    var link = 'wms/inbound/putaway/v_putaway_source_list';
    var h_whs = $("#h_whs").val();

    data = {'link':link, 'h_whs':h_whs }
    $('#modal_source_doc').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_source_doc').load(
        "<?php echo base_url();?>index.php/wms/inbound/putaway/get_source_list",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalSourceDoc').modal();
}
//---

function f_clear_all(){
  swal({
    title: "Are you sure ?",
    html: "Clear All the data",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
    if(result.value){
        $('#tbl_detail tbody').empty();
        $('#tbl_detail2 tbody').empty();
        glb_counter = 0;
        glb_counter2 = 0;
        glb_loc_zone_area_rack_bin = 0;
        $('#tbl_detail_qty_total').text(0);
        $('#tbl_detail_qty_put').text(0);
        $('#tbl_detail_qty_rem').text(0);
        $('#tbl_detail2_qty_total').text(0);
    }
  })

}
//---

function f_get_loc_zone_area_rack_bin(i){
    var link = 'wms/inbound/v_putaway_bin';
    var total_qty_outstanding = $('#tbl_qty_outstanding_'+i).text();
    var total_qty_put = $('#tbl_qty_put_'+i).text();
    var doc_no = $('#tbl_doc_no_'+i).text();
    var line_no = $('#tbl_line_no_'+i).text();
    var item_code = $('#tbl_item_code_'+i).text();
    var desc = $('#tbl_desc_'+i).text();
    var uom = $('#tbl_uom_'+i).text();
    var h_whs = $("#h_whs").val() // 2023-03-06 WH3
    //counter = 0;

    // get data master barcode existing
    var master_barcode = [];
    var counter_master_barcode = 0;
    for(j=0;j<glb_loc_zone_area_rack_bin;j++){
      if(check_if_id_exist("#tbl_detail2_sn2_"+j)){
          master_barcode[counter_master_barcode] = $("#tbl_detail2_sn2_"+j).text();
          counter_master_barcode++;
      }
    }
    //--

    data = {'link':link,'total_qty_outstanding':total_qty_outstanding,'total_qty_put':total_qty_put, 'doc_no':doc_no, 'line_no':line_no, 'item_code' : item_code, 'desc':desc, 'uom':uom,'row_doc':i, 'counter_master_barcode':counter_master_barcode, 'master_barcode':JSON.stringify(master_barcode), 'h_whs':h_whs}
    $('#modal_bin').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_bin').load(
        //"<?php //echo base_url();?>index.php/wms/inbound/putaway/get_bin",
        "<?php echo base_url();?>index.php/wms/inbound/putaway/get_bin_master_barcode",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalBin').modal();
}
//----

function f_calculate_total_put(){
    var qty = 0;
    for(i=0;i<=glb_counter;i++){
      if(check_if_id_exist("#tbl_qty_put_"+i)){
          qty = qty +  convert_number($("#tbl_qty_put_"+i).text());
      }
    }
    return qty;
}
//---

function f_calculate_total_rem(){
    var qty = 0;
    for(i=0;i<=glb_counter;i++){
      if(check_if_id_exist("#tbl_qty_put_"+i)){
          qty = qty +  convert_number($("#tbl_qty_rem_"+i).text());
      }
    }

    return qty;
}
//---

function f_calculate_total_outstanding(){
    var qty = 0;
    for(i=0;i<=glb_counter;i++){
      if(check_if_id_exist("#tbl_qty_outstanding_"+i)){
          qty = qty +  convert_number($("#tbl_qty_outstanding_"+i).text());
      }
    }

    return qty;
}
//---

function f_calculate_total_bin(){
    var qty = 0;
    for(i=0;i<glb_loc_zone_area_rack_bin;i++){
      if(check_if_id_exist("#tbl_detail2_qty_"+i)){
          qty = qty +  convert_number($("#tbl_detail2_qty_"+i).text());
      }
    }

    return qty;
}
//---

function check_total_header_detail(){
    var detail_qty_total = parseInt($('#tbl_detail_qty_total').text());
    var detail2_qty_total = parseInt($('#tbl_detail2_qty_total').text());

    if(detail_qty_total > 0 && detail2_qty_total > 0){
        if(detail_qty_total==detail2_qty_total) return true;
        else return false;
    }

    return false;
}

//--
function f_create_new_put_away(){

    if($("#h_doc_user").val() == '-'){
        show_error("You haven't choosen the User");
    }
    else if(!check_total_header_detail()){ // check if total header and detail already same
        show_error("Your Data has not been completed");
    }
    else if(!check_rack_completed()){
        show_error("Pones ubicacion in correcto");
    }
    else if(!check_bin()){
        show_error("Hay datos del contenedor de ubicación que no están en el sistema");
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
            if(result.value == ""){
                show_error("You have to type message");
            }
            else{

              var message = result.value;

              swal({
                title: "Are you sure ?",
                html: "Proceed this Put Away Document",
                type: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
              }).then(function (result) {
                    if(result.value){
                        $("#loading_text").text("Creating New Put Away Document, Please wait...");
                        $('#loading_body').show();

                        // get variable
                        var h_doc_no = $("#h_doc_no").val();
                        var h_doc_date = $("#h_doc_date").val();
                        var h_doc_user = $("#h_doc_user").val();
                        //--

                        // get header
                        var h_doc_received = [];
                        var h_line = [];
                        var h_loc = [];
                        var h_item_code = [];
                        var h_desc = [];
                        var h_qty_total = [];
                        var h_qty_put = [];
                        var h_qty_rem = [];
                        var h_uom = [];
                        var counter_h = 0;

                        for(i=0;i<glb_counter;i++){
                            if(check_if_id_exist("#tbl_doc_no_"+i)){
                                h_doc_received[counter_h] = $('#tbl_doc_no_'+i).text();
                                h_line[counter_h] = $('#tbl_line_no_'+i).text();
                                h_loc[counter_h] = $('#tbl_location_code_'+i).text();
                                h_item_code[counter_h] = $('#tbl_item_code_'+i).text();
                                h_desc[counter_h] = $('#tbl_desc_'+i).text();
                                h_qty_total[counter_h] = $('#tbl_qty_outstanding_'+i).text();
                                h_qty_put[counter_h] = $('#tbl_qty_put_'+i).text();
                                h_qty_rem[counter_h] = $('#tbl_qty_rem_'+i).text();
                                h_uom[counter_h] = $('#tbl_uom_'+i).text();
                                counter_h++;
                            }
                        }
                        //---

                        // get detail
                        var d_doc_no = [];
                        var d_line_no = [];
                        var d_item_code = [];
                        var d_desc = [];
                        var d_qty = [];
                        var d_uom = [];
                        var d_loc = [];
                        var d_zone = [];
                        var d_area = [];
                        var d_rack = [];
                        var d_bin = [];
                        var d_sn2 = [];
                        var counter_d = 0;

                        for(i=0;i<glb_loc_zone_area_rack_bin ;i++){
                            if(check_if_id_exist("#tbl_detail2_doc_no_"+i)){
                                d_doc_no[counter_d] = $('#tbl_detail2_doc_no_'+i).text();
                                d_line_no[counter_d] = $('#tbl_detail2_line_no_'+i).text();
                                d_item_code[counter_d] = $('#tbl_detail2_item_code_'+i).text();
                                d_desc[counter_d] = $('#tbl_detail2_desc_'+i).text();
                                d_qty[counter_d] = $('#tbl_detail2_qty_'+i).text();
                                d_uom[counter_d] = $('#tbl_detail2_uom_'+i).text();
                                d_loc[counter_d] = $('#tbl_detail2_loc_'+i).text();
                                d_zone[counter_d] = $('#tbl_detail2_zone_'+i).text();
                                d_area[counter_d] = $('#tbl_detail2_area_'+i).text();
                                d_rack[counter_d] = $('#tbl_detail2_rack_'+i).text();
                                d_bin[counter_d] = $('#tbl_detail2_bin_'+i).text();
                                d_sn2[counter_d] = $('#tbl_detail2_sn2_'+i).text();
                                counter_d++;
                            }
                        }

                        //---

                        // ajax
                        $.ajax({
                            url  : "<?php echo base_url();?>index.php/wms/inbound/putaway/create_new2",
                            type : "post",
                            dataType  : 'html',
                            data : {h_doc_received:JSON.stringify(h_doc_received),h_line:JSON.stringify(h_line), h_loc:JSON.stringify(h_loc), h_item_code:JSON.stringify(h_item_code), h_desc:JSON.stringify(h_desc), h_qty_total:JSON.stringify(h_qty_total), h_qty_put:JSON.stringify(h_qty_put), h_qty_rem:JSON.stringify(h_qty_rem), h_uom:JSON.stringify(h_uom), d_doc_no:JSON.stringify(d_doc_no), d_line_no:JSON.stringify(d_line_no),
                            d_item_code:JSON.stringify(d_item_code), d_desc:JSON.stringify(d_desc), d_qty:JSON.stringify(d_qty), d_uom:JSON.stringify(d_uom), d_loc:JSON.stringify(d_loc), d_zone:JSON.stringify(d_zone), d_area:JSON.stringify(d_area), d_rack:JSON.stringify(d_rack), d_bin:JSON.stringify(d_bin),d_sn2:JSON.stringify(d_sn2) , h_doc_no:h_doc_no, h_doc_date:h_doc_date, counter_h:counter_h, counter_d:counter_d, h_doc_user:h_doc_user, message:message},
                            success: function(data){
                              var responsedata = $.parseJSON(data);

                              if(responsedata.status == 1){
                                    swal({
                                       title: responsedata.msg,
                                       type: "success", confirmButtonText: "OK",
                                    }).then(function(){
                                      setTimeout(function(){
                                        $('#myModalDetailReceived').modal('toggle');
                                        window.location.href = "<?php echo base_url();?>index.php/wms/inbound/putaway";
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
                      
                        //---
                    }
              })
            }
          }
      })
    }

}
//---

function check_rack_completed(){
  check = 1;
  for(i=0;i<glb_loc_zone_area_rack_bin;i++){
    if(check_if_id_exist("#tbl_detail2_qty_"+i)){
      if($("#tbl_detail2_loc_"+i).text()=='undefined' ||
         $("#tbl_detail2_zone_"+i).text()=='undefined' ||
         $("#tbl_detail2_area_"+i).text()=='undefined' ||
         $("#tbl_detail2_rack_"+i).text()=='undefined' ||
         $("#tbl_detail2_bin_"+i).text()=='undefined'
       ){
          check = 0;
          break;
      }
    }
  }

  if(check == 1) return true;
  else return false;
}
//---

function check_bin(){
    var location = [];
    var zone = [];
    var area = [];
    var rack = [];
    var bin = [];
    var counter = 0;
    var result = 1;

    for(i=0;i<glb_loc_zone_area_rack_bin;i++){
        if(check_if_id_exist("#tbl_detail2_qty_"+i)){
          location[counter] = $("#tbl_detail2_loc_"+i).text();
          zone[counter] = $("#tbl_detail2_zone_"+i).text();
          area[counter] = $("#tbl_detail2_area_"+i).text();
          rack[counter] = $("#tbl_detail2_rack_"+i).text();
          bin[counter] = $("#tbl_detail2_bin_"+i).text();
          counter++;
        }
    }

    // ajax
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/putaway/check_bin",
        type : "post",
        dataType  : 'html',
        async : false,
        data : {location:JSON.stringify(location),zone:JSON.stringify(zone),area:JSON.stringify(area),rack:JSON.stringify(rack), bin:JSON.stringify(bin)},
        success: function(data){
            var responsedata = $.parseJSON(data);

            if(responsedata.error == 1) result = 0;

        }
    })
    //---

    return result;
}
//---


//2023-09-22
function f_auto(){
    if(glb_counter == 0){
      show_error("No Data");
    }
    else{
        //--
        var check_if_any_data = 0;
        for(i=0;i<glb_counter;i++){
            if(check_if_id_exist("#tbl_doc_no_"+i)){
              check_if_any_data = 1;
              break;
            }
        }
        //---

        if(check_if_any_data == 0){
            show_error("No Data");
        }
        else{
            for(k=0;k<=glb_counter;k++){
                if(check_if_id_exist("#tbl_doc_no_"+k)){
                  f_get_loc_zone_area_rack_bin3(k);
                }
            }
        }

    }
}
//---

//2023-09-22
function f_get_loc_zone_area_rack_bin3(i){
    var link = 'wms/inbound/v_putaway_bin';
    var total_qty_outstanding = $('#tbl_qty_outstanding_'+i).text();
    var total_qty_put = $('#tbl_qty_put_'+i).text();
    var doc_no = $('#tbl_doc_no_'+i).text();
    var line_no = $('#tbl_line_no_'+i).text();
    var item_code = $('#tbl_item_code_'+i).text();
    var desc = $('#tbl_desc_'+i).text();
    var uom = $('#tbl_uom_'+i).text();
    var h_whs = $("#h_whs").val() // 2023-03-06 WH3
    //counter = 0;

    // get data master barcode existing
    var master_barcode = [];
    var counter_master_barcode = 0;
    for(j=0;j<glb_loc_zone_area_rack_bin;j++){
      if(check_if_id_exist("#tbl_detail2_sn2_"+j)){
          master_barcode[counter_master_barcode] = $("#tbl_detail2_sn2_"+j).text();
          counter_master_barcode++;
      }
    }
    //--

    data = {'link':link,'total_qty_outstanding':total_qty_outstanding,'total_qty_put':total_qty_put, 'doc_no':doc_no, 'line_no':line_no, 'item_code' : item_code, 'desc':desc, 'uom':uom,'row_doc':i, 'counter_master_barcode':counter_master_barcode, 'master_barcode':JSON.stringify(master_barcode), 'h_whs':h_whs}
    $('#modal_bin').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_bin').load(
        //"<?php //echo base_url();?>index.php/wms/inbound/putaway/get_bin",
        "<?php echo base_url();?>index.php/wms/inbound/putaway/get_bin_master_barcode",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalBin').modal();
    alert($('#tbl_item_code_'+i).text());
    $("#tbl_add_qty_bin_bin_0").val(get_location_rack(h_whs,item_code));
    $("#copy_to_all_item_per_master_barcode").click();
    $("#btn_process_add_qty_bin").click();
}
//----

function get_location_rack(whs,item_code){
  // WH4-X-X-X-X
  // WH3-X-X-X-X
  // WH2-36-X-X-X -> banda2
  // WH2-X-X-X-X -> filtro

  if(whs == "WH4") return "WH4-X-X-X-X";
  else if(whs == "WH3") return "WH3-X-X-X-X";
  else if(whs == "WH2"){
      if(item_code.includes("TYP-")) return "WH2-36-X-0-0";
      else return "WH2-X-X-X-X";
  }

  return "";
}



</script>
