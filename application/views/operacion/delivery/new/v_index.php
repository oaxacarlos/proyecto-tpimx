<script>
$(document).ready(function() {
    $('#DataTable').DataTable();

    $("#inp_sending_date").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_from").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_to").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_from_consign").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_to_consign").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>

<style>
.modal {
  padding: 0 !important; // override inline padding-right added from js
}
.modal .modal-dialog {
  width: 100%;
  max-width: none;
  height: 100%;
  margin: 0;
}
.modal .modal-content {
  height: 100%;
  border: 0;
  border-radius: 0;
}
.modal .modal-body {
  overflow-y: auto;
}

</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      New Delivery
</div>

<div class="modal" id="myModalFactura">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Factura / Promotional Items<span id="modal_factura_title"></span></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_factura_detail" >
        <div class="row">
          <div class="col-md-2">
            <input type='text' name='datepicker_check' value="<?php echo date("Y-m-01"); ?>" id='datepicker_from' class='required form-control' placeholder='Period From'>
          </div>
          <div class="col-md-2">
            <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to' class='required form-control' placeholder='Period To'>
          </div>
          <div class="col-md-2">
              <button class="btn btn-primary" id="btn_go">GO</button>
          </div>
        </div>
        <div class="row" id="data_factura_promo" style="margin-top:20px;"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="myModalConsign">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Consigment<span id="modal_factura_title"></span></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_consign_detail" >
        <div class="row">
          <div class="col-md-2">
            <input type='text' name='datepicker_check_consign' value="<?php echo date("Y-m-01"); ?>" id='datepicker_from_consign' class='required form-control' placeholder='Period From'>
          </div>
          <div class="col-md-2">
            <input type='text' name='datepicker_check_consign' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to_consign' class='required form-control' placeholder='Period To'>
          </div>
          <div class="col-md-2">
              <button class="btn btn-primary" id="btn_go_cosign">GO</button>
          </div>
        </div>
        <div class="row" id="data_consign" style="margin-top:20px;"></div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-1">
      Doc Date
      <input type="text" class="form-control" disabled value="<?php echo date("Y-m-d"); ?>">
    </div>
    <div class="col-md-2">
      Sending Date (ENVIO)
      <input type="text" class="form-control" value="" id="inp_sending_date" readonly style="background-color:white;">
    </div>
    <div class="col-md-2">
      Destination (DESTINO)
      <input type="text" class="form-control" value="" id="inp_destination">
    </div>
    <div class="col-md-2">
      State (ESTADO)
      <select id="inp_state" class="form-control">
        <?php
          echo "<option value='-'>-</option>";
          foreach($var_state as $row){
              echo "<option value='".$row["name"]."'>".$row["name"]."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-md-2">
      Driver (CHOFER)
      <select id="inp_driver" class="form-control">
        <?php
          echo "<option value='-'>-</option>";
          foreach($var_driver as $row){
              echo "<option value='".$row["name"]."'>".$row["name"]."</option>";
          }
        ?>
      </select>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-3">
      Vendor (PAQ/CLIENTE)
      <select id="inp_vendor" class="form-control">
        <?php
          echo "<option value='-'>-</option>";
          foreach($var_vendor as $row){
              echo "<option value='".$row["vendor_code"]."'>".$row["vendor_code"]." | ".$row["vendor_name"]."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-md-2">
      Tracking No (REF DE GUIA)
      <input type="text" class="form-control" value="" id="inp_tracking_no">
    </div>
    <div class="col-md-2">
      DOMICILI
      <select id="inp_domicili" class="form-control">
        <?php
          echo "<option value='-'>-</option>";
          foreach($var_domilici as $row){
              echo "<option value='".$row["name"]."'>".$row["name"]."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-md-2">
      Payment Terms
      <select id="inp_payment_terms" class="form-control">
        <?php
          echo "<option value='-'>-</option>";
          foreach($var_payment_terms as $row){
              echo "<option value='".$row["name"]."'>".$row["name"]."</option>";
          }
        ?>
      </select>
    </div>

  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-3">
      REMARK HEADER
      <input type="text" class="form-control" value="" id="inp_remark" placeholder="Remark (Optional)">
    </div>
    <div class="col-md-2">
      BOX (CAJA)
      <input type="text" class="form-control" value="" id="inp_box" placeholder="BOX (Optional)" onkeypress='return isNumberKey(event)'>
    </div>
    <div class="col-md-2">
      PALLET
      <input type="text" class="form-control" value="" id="inp_pallet" placeholder="PALLET (Optional)" onkeypress='return isNumberKey(event)'>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-3">
      Delivery Status
      <select id="inp_delivery_status" class="form-control">
        <?php
          foreach($var_delv_status as $row){
              echo "<option value='".$row["name"]."'>".$row["name"]."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-md-2">
      SUBTOTAL (MXN)
      <input type="text" class="form-control" value="" id="inp_sub_total" onkeypress='return isNumberKey(event)'>
    </div>
    <div class="col-md-2">
      MONTO (MXN)
      <input type="text" class="form-control" value="" id="inp_total" onkeypress='return isNumberKey(event)'>
    </div>
    <!-- 2023-10-13 -->
    <div class="col-md-2">
      FOLIO (Optional)
      <input type="text" class="form-control" value="" id="inp_folio">
    </div>
    <!-- end -->
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-12">
      <div class="btn-group" role="group">
        <button id="btnGroupDropAdd" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Nuevo
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDropAdd">
          <button class="dropdown-item" data-toggle="modal" data-target="#myModalFactura">Factura / Promotional Items</button>
          <button class="dropdown-item" data-toggle="modal" data-target="#myModalConsign">Consign</button>
          <button class="dropdown-item" onclick=f_non_factura()>Non Factura / Promotional Items</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-12">
      <table class="table" id="table_detail">
        <thead>
          <tr class="table-info">
            <th>Doc Type</th>
            <th>Doc No</th>
            <th>Doc Date</th>
            <th>SO Ref</th>
            <th>Cust No</th>
            <th>SubTotal</th>
            <th>Total</th>
            <th>Remarks</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="7"></td>
            <td><button class="btn btn-success" id="btn_process">PROCESS</button></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<?php echo loading_body_full(); ?>

<?php

unset($autocomplete);
$i=0;
foreach($var_city as $row){
    $autocomplete[$i] = $row["name"];
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);

?>

<script>
var autocomplete = <?php echo $js_array_autocomplete; ?>;
var glb_idx = 0;

$( function() {
  $( "#inp_destination").autocomplete({ source: autocomplete});
})
//---

function f_non_factura(){
    table = "";
    table = table + "<tr id='table_result_"+glb_idx+"'>";
      table = table + "<td><input type='text' id='table_doc_type_"+glb_idx+"' class='form-control' value='2' disabled></td>";
      table = table + "<td><input type='text' id='table_doc_no_"+glb_idx+"' class='form-control' placeholder='Doc No'></td>";
      table = table + "<td><input type='text' id='table_doc_date_"+glb_idx+"' class='form-control' placeholder='Doc Date'></td>";
      table = table + "<td><input type='text' id='table_so_ref_"+glb_idx+"' class='form-control' placeholder='SO Ref'></td>";
      table = table + "<td><input type='text' id='table_cust_no_"+glb_idx+"' class='form-control' placeholder='Cust No'></td>";
      table = table + "<td><input type='text' id='table_subtotal_"+glb_idx+"' class='form-control' placeholder='SubTotal'></td>";
      table = table + "<td><input type='text' id='table_total_"+glb_idx+"' class='form-control' placeholder='Total'></td>";
      table = table + "<td><input type='text' id='table_remarks_"+glb_idx+"' class='form-control' placeholder='Remarks'><span id='table_required_remarks_"+glb_idx+"'></span></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_cust_name_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_address_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_address2_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_city_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_state_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_post_code_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_country_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_qty_"+glb_idx+"' value='1'></td>";
      table = table + "<td><button class='btn btn-sm btn-danger' id='btn_delete_table_"+glb_idx+"' onclick=f_delete_line("+glb_idx+")>X</button></td>";
    table = table + "</tr>";
    glb_idx++;

    $("#table_detail tbody").append(table);
}
//---

function f_delete_line(idx){
    $("#table_result_"+idx).remove();
}
//---

$("#btn_go").click(function(){
      var date_from = $("#datepicker_from").val();
      var date_to = $("#datepicker_to").val();

      if(check_from_to(date_from,date_to)){
          $("#data_factura_promo").html("Loading, Please wait...");

          $.ajax({
              url       : "<?php echo base_url();?>index.php/operacion/delivery/newdelivery/get_invoices_whship",
              type      : 'post',
              dataType  : 'html',
              data      :  {date_from:date_from, date_to:date_to},
              success   :  function(respons){
                  $('#data_factura_promo').fadeIn("5000");
                  $("#data_factura_promo").html(respons);
              }
          });
      }
})
//---

$("#btn_process").click(function(){

    if($("#inp_sending_date").val() == ""){
        show_error("You have to fill Sending Date (ENVIO)");
        return false;
    }

    if($("#inp_destination").val() == ""){
        show_error("You have to fill Destination (DESTINO)");
        return false;
    }

    if($("#inp_state").val() == ""){
        show_error("You have to fill State (ESTADO)");
        return false;
    }

    if($("#inp_driver").val() == ""){
        show_error("You have to fill Driver (CHOFER)");
        return false;
    }

    if($("#inp_vendor").val() == ""){
        show_error("You have to fill Vendor (PAQ/CLIENTE)");
        return false;
    }

    if($("#inp_tracking_no").val() == ""){
        show_error("You have to fill Tracking No (REF DE GUIA)");
        return false;
    }

    if($("#inp_domicili").val() == ""){
        show_error("You have to fill DOMICILI");
        return false;
    }

    if($("#inp_payment_terms").val() == ""){
        show_error("You have to fill Payment Terms");
        return false;
    }

    if($("#inp_delivery_status").val() == ""){
        show_error("You have to fill Delivery Status");
        return false;
    }

    if($("#inp_total").val() == ""){
        show_error("You have to fill MONTO(MXN)");
        return false;
    }

    if(isNaN($("#inp_total").val())){
        show_error("MONTO(MXN) must be only Number");
        return false;
    }

    // check if table has data
    if(!f_check_table_result_has_data()){
      show_error("No Data in Table, You must input");
      return false;
    }

    // check if remarks required is ok
    check_remarks = f_check_if_remarks_is_ok();
    if(check_remarks[0] == "0"){
      show_error("You must fill the remarks on Doc No "+$("#table_doc_no_"+check_remarks[1]).val());
      return false;
    }
    //--

    // check if total on limit
    var state = $("#inp_state").val();
    var remark_h = $("#inp_remark").val();
    check_limit = check_limit_amount(state);

    if(check_limit[0] == 0 && remark_h == ""){
        show_error("The Delivery Cost over than "+check_limit[1]+"%, You must put the REMARK HEADER");
        $("#inp_remark").focus();
        return false;
    }
    //----

    // is everything ok, proceed
    swal({
      title: "Are you sure ?",
      html: "Proceed this Delivery",
      type: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }).then(function (result) {
        if(result.value){

            $("#loading_text").text("Creating New Delivery Document, Please wait...");
            $('#loading_body').show();

            // get all variables
            var sending_date = $("#inp_sending_date").val();
            var destination = $("#inp_destination").val();
            var state = $("#inp_state").val();
            var driver = $("#inp_driver").val();
            var vendor = $("#inp_vendor").val();
            var tracking_no = $("#inp_tracking_no").val();
            var domicili = $("#inp_domicili").val();
            var payment_terms = $("#inp_payment_terms").val();
            var delivery_status = $("#inp_delivery_status").val();
            var total = $("#inp_total").val();
            var box = $("#inp_box").val();
            var pallet = $("#inp_pallet").val();
            var remark_h = $("#inp_remark").val();
            var sub_total_vendor = $("#inp_sub_total").val();
            var folio = $("#inp_folio").val(); // 2023-10-13

            var counter = 0;
            var doc_no=[]; var doc_date=[]; var so_ref=[]; var cust_no=[];
            var subtotal=[]; var total2=[]; var remarks=[]; var cust_name=[];
            var address=[]; var address2=[]; var city=[]; var state2=[];
            var post_code=[]; var country=[]; var qty=[]; var doc_type=[];
            var required_remarks = [];

            for(i=0; i<glb_idx; i++){
                if(check_if_id_exist("#table_result_"+i)){
                    doc_type[counter] = $("#table_doc_type_"+i).val();
                    doc_no[counter] = $("#table_doc_no_"+i).val();
                    doc_date[counter] = $("#table_doc_date_"+i).val();
                    so_ref[counter] = $("#table_so_ref_"+i).val();
                    cust_no[counter] = $("#table_cust_no_"+i).val();
                    subtotal[counter] = $("#table_subtotal_"+i).val();
                    total2[counter] = $("#table_total_"+i).val();
                    remarks[counter] = $("#table_remarks_"+i).val();
                    cust_name[counter] = $("#table_cust_name_"+i).val();
                    address[counter] = $("#table_address_"+i).val();
                    address2[counter] = $("#table_address2_"+i).val();
                    city[counter] = $("#table_city_"+i).val();
                    state2[counter] = $("#table_state_"+i).val();
                    post_code[counter] = $("#table_post_code_"+i).val();
                    country[counter] = $("#table_country_"+i).val();
                    qty[counter] = $("#table_qty_"+i).val();

                    if($("#table_required_remarks_"+i).text() == "*") required_remarks[counter] = 1;
                    else required_remarks[counter] = 0;

                    counter++;
                }
            }
            //--

            $.ajax({
                url  : "<?php echo base_url();?>index.php/operacion/delivery/newdelivery/create_new",
                type : "post",
                dataType  : 'html',
                data : {doc_type:JSON.stringify(doc_type),doc_no:JSON.stringify(doc_no),doc_date:JSON.stringify(doc_date),so_ref:JSON.stringify(so_ref),cust_no:JSON.stringify(cust_no),subtotal:JSON.stringify(subtotal), total2:JSON.stringify(total2),remarks:JSON.stringify(remarks),cust_name:JSON.stringify(cust_name),address:JSON.stringify(address),address2:JSON.stringify(address2),required_remarks:JSON.stringify(required_remarks),city:JSON.stringify(city),state2:JSON.stringify(state2),post_code:JSON.stringify(post_code),country:JSON.stringify(country),qty:JSON.stringify(qty),sending_date:sending_date,destination:destination,state:state,driver:driver,vendor:vendor,domicili:domicili,payment_terms:payment_terms,delivery_status:delivery_status,total:total,counter:counter,box:box,pallet:pallet,remark_h:remark_h,tracking_no:tracking_no, sub_total_vendor:sub_total_vendor, folio:folio},
                success: function(data){
                    var responsedata = $.parseJSON(data);

                    if(responsedata.status == 1){
                          swal({
                             title: responsedata.msg,
                             type: "success", confirmButtonText: "OK",
                          }).then(function(){
                            setTimeout(function(){
                              $('#loading_body').hide();
                              window.location.href = "<?php echo base_url();?>index.php/operacion/delivery/newdelivery";
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

})
//--


function f_check_table_result_has_data(){

    check = 0;

    for(i=0;i<glb_idx;i++){
        if(check_if_id_exist("#table_result_"+i)){
            check = 1;
            break;
        }
    }

    if(check == 1) return true;
    else return false;
}
//--

function f_check_if_remarks_is_ok(){
  check = 1;
  idx = "";
  for(i=0;i<glb_idx;i++){
      if(check_if_id_exist("#table_required_remarks_"+i)){
          if($('#table_required_remarks_'+i).text() == '*' && $("#table_remarks_"+i).val()==""){
            check = 0;
            idx = i;
            break;
          }
      }
  }

  var data=[];

  if(check == 1){
      data[0] = "1";
      data[1] = idx;
  }
  else{
    data[0] = "0";
    data[1] = idx;
  }

  return data;
}
//---

function check_limit_amount(state){
    var total_invc = 0;
    var total_delv = parseFloat($("#inp_total").val());

    for(i=0;i<glb_idx;i++){
      if(check_if_id_exist("#table_result_"+i)){
          total_invc = total_invc + parseFloat($("#table_subtotal_"+i).val());
      }
    }

    $.ajax({
        url  : "<?php echo base_url();?>index.php/operacion/delivery/newdelivery/get_limit_percentage",
        type : "post",
        dataType  : 'json',
        async : false,
        data : {state:state},
        success: function(data){
            percentage = data;
        }
    })

    // calculate
    percentage_new = total_delv / total_invc;
    var result = [];
    if(percentage_new < percentage){
        result[0] = 1;
        result[1] = percentage*100;
    }
    else{
        result[0] = 0;
        result[1] = percentage*100;
    }

    return result;
}
//--

// 2023-07-12
$("#btn_go_cosign").click(function(){
      var date_from = $("#datepicker_from_consign").val();
      var date_to = $("#datepicker_to_consign").val();

      if(check_from_to(date_from,date_to)){
          $("#data_consign").html("Loading, Please wait...");

          $.ajax({
              url       : "<?php echo base_url();?>index.php/operacion/delivery/newdelivery/get_wship_consign",
              type      : 'post',
              dataType  : 'html',
              data      :  {date_from:date_from, date_to:date_to},
              success   :  function(respons){
                  $('#data_consign').fadeIn("5000");
                  $("#data_consign").html(respons);
              }
          });
      }
})
//---

</script>
