<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Transfer Bin New
</div>

<div class="modal" id="myModalBinDest" data-backdrop="static">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bin Destination</h5>
      </div>
      <div class="modal-body ui-front" id="modal_bin_dest">
        <input type="text" id="inp_bin_dest" class="form-control" placeholder="type your bin destination in here" onkeypress='return getbin(event)'>
        <a href="<?php echo base_url()?>index.php/wms/inbound/transferbin" class="btn btn-danger btn-sm text-right" style="margin-top:20px;">Cancel</a>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="myModalBinSrc">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bin Source = <span id="modal_bin_title"></span></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_bin_src" ></div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="container-fluid">
    Destination
    <input type="text" id="inp_bin_dest2" class="form-control" readonly>
  </div>

  <div class="container-fluid">
    Source
    <input type="text" id="inp_bin_src" class="form-control" placeholder="type your bin source in here" onkeypress='return getbinsrc(event)'>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <table class="table table-bordered table-sm" id="tbl_result">
    <thead>
      <tr>
        <th colspan='9' class="text-center table-secondary">From</th>
        <th class="text-center table-success" style='width:100px;'>To</th>
        <th style='width:50px;'></th>
      </tr>
      <tr>
        <th class="table-secondary">Loc</th>
        <th class="table-secondary">Zone</th>
        <th class="table-secondary">Area</th>
        <th class="table-secondary">Rack</th>
        <th class="table-secondary">Bin</th>
        <th class="table-secondary">Item Code</th>
        <th class="table-secondary">Desc</th>
        <th class="table-secondary">Uom</th>
        <th class="table-secondary">Qty</th>
        <th class="table-success">Qty</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-7">
    </div>
    <div class="col-3">
      <select name='user_list' id='doc_user' class="form-control">
        <option value='-'>-</option>
          <?php
            foreach($user_list as $row){
              echo "<option value='".$row['user_id']."'>".$row['name']."</option>";
            }
          ?>
      </select>
    </div>
    <div class="col-2">
      <button class="btn btn-primary" id="btn_save">SAVE</button>
    </div>
  </div>
</div>

<?php echo loading_body_full(); ?>

<?php

unset($autocomplete);
$i=0;
foreach($var_bin as $row){
    $value2 = $row['location_code']."-".$row['zone_code']."-".$row['area_code']."-".$row['rack_code']."-".$row['code'];
    $autocomplete[$i] = $value2;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);

?>

<script>
var autocomplete = <?php echo $js_array_autocomplete; ?>;
var glb_idx=0;

$( function() {
  $( "#inp_bin_dest").autocomplete({ source: autocomplete});
  $( "#inp_bin_src").autocomplete({ source: autocomplete});

  $('#myModalBinDest').modal();
  $( "#inp_bin_dest").val("");
  $( "#inp_bin_dest").focus();

})
//---

function getbin(event){
    if(event.keyCode == 13){
        if($("#inp_bin_dest").val() == "") return false;

        is_bin_exist = check_bin($("#inp_bin_dest").val()); // check bin if available
        if(is_bin_exist=="0"){
            show_error("Bin you inputted not on the system");
            $("#inp_bin_dest").val("");
            $("#inp_bin_dest").focus();
            return false;
        }

        // if bin exist
        $("#inp_bin_dest2").val($("#inp_bin_dest").val());
        $("#inp_bin_src").focus();
        $("#inp_bin_src").val("");
        $('#myModalBinDest').modal("toggle");
    }
}
//----

function check_bin(value){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/check_bin",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {id:value},
        success: function(data){
            result = $.parseJSON(data);
        }
    })

    return result;
}
//---

function getbinsrc(event){
  if(event.keyCode == 13){
      if($("#inp_bin_src").val() == "") return false;

      is_bin_exist = check_bin($("#inp_bin_src").val()); // check bin if available
      if(is_bin_exist=="0"){
          show_error("Bin you inputted not on the system");
          $("#inp_bin_src").val("");
          $("#inp_bin_src").focus();
          return false;
      }

      // if bin exist
      show_modal_src();
  }
}
//---
function show_modal_src(){
    var id = $("#inp_bin_src").val();

    data = {'id':id}
    $('#modal_bin_src').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_bin_src').load(
        "<?php echo base_url();?>index.php/wms/inbound/transferbin/getbinsrc",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#modal_bin_title').text(id);
    $('#myModalBinSrc').modal();
}
//---

function check_if_table_result_not_blank(){
    var tb = $('#tbl_result tbody');
    var row = tb.find("tr").length;
    if(row > 0) return 1; else return 0;
}
//---

function check_if_all_fields_is_filled(){
    for(i=0;i<glb_idx;i++){
        if(check_if_id_exist($("#tbl_result_inp_qty_"+i))){
            if($("#tbl_result_inp_qty_"+i).val()==0 || $("#tbl_result_inp_qty_"+i).val()=="") return 0;
        }
    }
    return 1;
}
//---

function check_if_qty_not_greater_then_system_have(){
    var line = "";
    for(i=0;i<=glb_idx;i++){
        if(check_if_id_exist($("#tbl_result_inp_qty_"+i))){
            if(convert_number($("#tbl_result_inp_qty_"+i).val()) > convert_number($("#tbl_result_qty_"+i).text())){
                line = i;
                break;
            }
        }
    }
    return line;
}
//---

$("#btn_save").click(function(){
    if(!check_if_table_result_not_blank()){
        show_error("The Data still blank");
        return false;
    }

    if(!check_if_all_fields_is_filled()){
        show_error("There is Qty you have not filled");
        return false;
    }

    line_check = check_if_qty_not_greater_then_system_have();
    if(line_check!='' || parseInt(line_check)>=0){
        msg_temp = "tiene un error en el artículo "+$("#tbl_result_item_"+line_check).text()+" porque la cantidad es mayor que el sistema";
        show_error(msg_temp);
        return false;
    }

    if($("#doc_user").val()=="-"){
        show_error("You have to choose the User");
        return false;
    }

    // if everything is ok
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
          $("#loading_text").text("Creating New Transfer Document, Please wait...");
          $('#loading_body').show();

            var message = result.value;

            swal({
              title: "Are you sure ?",
              html: "Proceed this Transfer Bin",
              type: "question",
              showCancelButton: true,
              confirmButtonText: "Yes",
              showLoaderOnConfirm: true,
              closeOnConfirm: false
            }).then(function (result) {
                if(result.value){

                    // get all data
                    var bin_dest = $("#inp_bin_dest2").val();
                    var doc_user = $("#doc_user").val();
                    var loc=[]; var zone=[]; var area=[]; var rack=[]; var bin=[];
                    var item=[]; var desc=[]; var uom=[]; var qty_max=[]; var qty_inp=[];
                    var ii = 0;

                    for(i=0;i<glb_idx;i++){
                        if(check_if_id_exist($("#tbl_result_inp_qty_"+i))){
                            loc[ii] = $("#tbl_result_loc_"+i).text();
                            zone[ii] = $("#tbl_result_zone_"+i).text();
                            area[ii] = $("#tbl_result_area_"+i).text();
                            rack[ii] = $("#tbl_result_rack_"+i).text();
                            bin[ii] = $("#tbl_result_bin_"+i).text();
                            item[ii] = $("#tbl_result_item_"+i).text();
                            desc[ii] = $("#tbl_result_desc_"+i).text();
                            uom[ii] = $("#tbl_result_uom_"+i).text();
                            qty_max[ii] = $("#tbl_result_qty_"+i).text();
                            qty_inp[ii] = $("#tbl_result_inp_qty_"+i).val();
                            ii++;
                        }
                    }
                    //---

                    $.ajax({
                        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/create_new",
                        type : "post",
                        dataType  : 'html',
                        data : {loc:JSON.stringify(loc), zone:JSON.stringify(zone), area:JSON.stringify(area), rack:JSON.stringify(rack), bin:JSON.stringify(bin), item:JSON.stringify(item) , desc:JSON.stringify(desc), uom:JSON.stringify(uom) , qty_max:JSON.stringify(qty_max), qty_inp:JSON.stringify(qty_inp), bin_dest:bin_dest, message:message, doc_user:doc_user},
                        success: function(data){
                            var responsedata = $.parseJSON(data);

                            if(responsedata.status == 1){
                                  swal({
                                     title: responsedata.msg,
                                     type: "success", confirmButtonText: "OK",
                                  }).then(function(){
                                    setTimeout(function(){
                                      $('#loading_body').hide();
                                      window.location.href = "<?php echo base_url();?>index.php/wms/inbound/transferbin";
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
})
//---

function f_delete_tbl_result(i){
    $("#tbl_result_id_"+i).remove();
}


</script>
