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

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-1">
      Doc Date
      <input type="text" class="form-control" disabled value="<?php echo $var_data_h["doc_date"]; ?>">
    </div>
    <div class="col-md-2">
      Sending Date (ENVIO)
      <input type="text" class="form-control" id="inp_sending_date" value="<?php echo $var_data_h["delv_date"]; ?>">
    </div>
    <div class="col-md-2">
      Destination (DESTINO)
      <input type="text" class="form-control" id="inp_destination" value="<?php echo $var_data_h["destination"]; ?>">
    </div>
    <div class="col-md-2">
      State (ESTADO)
      <select id="inp_state" class="form-control">
        <?php
          echo "<option value='-'>-</option>";
          foreach($var_state as $row){
              if($row["name"] == $var_data_h["state"]) $selected = "selected";
              else $selected = "";
              echo "<option value='".$row["name"]."' ".$selected.">".$row["name"]."</option>";
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
              if($row["name"] == $var_data_h["driver"]) $selected = "selected";
              else $selected = "";
              echo "<option value='".$row["name"]."' ".$selected.">".$row["name"]."</option>";
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
              if($row["vendor_code"] == $var_data_h["vendor_no"]) $selected = "selected";
              else $selected = "";
              echo "<option value='".$row["vendor_code"]."' ".$selected.">".$row["vendor_name"]."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-md-2">
      Tracking No (REF DE GUIA)
      <input type="text" class="form-control" id="inp_tracking_no" value="<?php echo $var_data_h["tracking_no"]; ?>">
    </div>
    <div class="col-md-2">
      DOMICILI
      <select id="inp_domicili" class="form-control">
        <?php
          echo "<option value='-'>-</option>";
          foreach($var_domilici as $row){
              if($row["name"] == $var_data_h["domicili"]) $selected = "selected";
              else $selected = "";
              echo "<option value='".$row["name"]."' ".$selected.">".$row["name"]."</option>";
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
              if($row["name"] == $var_data_h["payment_term"]) $selected = "selected";
              else $selected = "";
              echo "<option value='".$row["name"]."' ".$selected.">".$row["name"]."</option>";
          }
        ?>
      </select>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-3">
      REMARK
      <input type="text" class="form-control" id="inp_remark" value="<?php echo $var_data_h["remark1"]; ?>">
    </div>
    <div class="col-md-2">
      BOX (CAJA)
      <input type="text" class="form-control" id="inp_box" onkeypress='return isNumberKey(event)' value="<?php echo $var_data_h["box"]; ?>">
    </div>
    <div class="col-md-2">
      PALLET
      <input type="text" class="form-control" id="inp_pallet" onkeypress='return isNumberKey(event)' value="<?php echo $var_data_h["pallet"]; ?>">
    </div>
    <div class="col-md-2">
      FOLIO
      <input type="text" class="form-control" id="inp_folio" value="<?php echo $var_data_h["folio"]; ?>">
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-1">
      SUBTOTAL (MXN)
      <input type="text" class="form-control" id="inp_subtotal" onkeypress='return isNumberKey(event)' value="<?php echo sprintf('%0.2f', round($var_data_h["subtotal"],2)) ?>">
    </div>
    <div class="col-md-2">
      MONTO (MXN)
      <input type="text" class="form-control" id="inp_total" onkeypress='return isNumberKey(event)' value="<?php echo sprintf('%0.2f', round($var_data_h["total"],2)); ?>">
    </div>
    <div class="col-md-2">
      Delivery Status
      <select id="inp_delivery_status" class="form-control">
        <?php
          foreach($var_delv_status as $row){
              if($row["name"] == $var_data_h["delv_status"]) $selected = "selected";
              else $selected = "";

              echo "<option value='".$row["name"]."' ".$selected.">".$row["name"]."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-md-2">
      Document No
      <input type="text" class="form-control" value="<?php echo $doc_no; ?>" disabled>
    </div>
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
            <th>Line No</th>
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
          <?php
            $i=0;
            foreach($var_data_d as $row){
                echo "<tr id='table_result_".$i."'>";
                  echo "<td><input type='text' id='table_doc_type_".$i."' class='form-control' value='".$row["doc_type"]."' size='1' disabled></td>";
                  echo "<td id='table_line_no_".$i."'>".$row["line_no"]."</td>";
                  echo "<td><input type='text' id='table_doc_no_".$i."' class='form-control' value='".$row["invc_doc_no"]."' disabled></td>";
                  echo "<td><input type='text' id='table_doc_date_".$i."' class='form-control' value='".$row["invc_doc_date"]."' disabled></td>";
                  echo "<td><input type='text' id='table_so_ref_".$i."' class='form-control' value='".$row["so_ref"]."' disabled></td>";
                  echo "<td><input type='text' id='table_cust_no_".$i."' class='form-control' value='".$row["invc_cust_no"]."' disabled></td>";
                  echo "<td><input type='text' id='table_subtotal_".$i."' class='form-control' value='".$row["subtotal"]."' disabled></td>";
                  echo "<td><input type='text' id='table_total_".$i."' class='form-control' value='".$row["total"]."' disabled></td>";
                  echo "<td><input type='text' id='table_remarks_".$i."' value='".$row["remark1"]."' class='form-control'>";

                  if($row["required_remark1"] == "1") echo "<span id='table_required_remarks_".$i."'>*</span><span id='table_required_remarks_text_".$i."'>&nbsp;Mandatory</span>";
                  else echo "<span id='table_required_remarks_".$i."'></span><span id='table_required_remarks_text_".$i."'></span>";
                  echo "</td>";

                  echo "<td><button class='btn btn-sm btn-danger' id='btn_delete_table_".$i."' onclick=f_delete_line(".$i.")>X</button></td>";


                  echo "<td style='display:none;'><input type='text' id='table_cust_name_".$i."' value='".$row["invc_cust_name"]."'>";
                  echo "<td style='display:none;'><input type='text' id='table_address_".$i."' value='".$row["invc_address"]."'>";
                  echo "<td style='display:none;'><input type='text' id='table_address2_".$i."' value='".$row["invc_address2"]."'>";
                  echo "<td style='display:none;'><input type='text' id='table_city_".$i."' value='".$row["invc_city"]."'>";
                  echo "<td style='display:none;'><input type='text' id='table_state_".$i."' value='".$row["invc_state"]."'>";
                  echo "<td style='display:none;'><input type='text' id='table_post_code_".$i."' value='".$row["invc_post_code"]."'>";
                  echo "<td style='display:none;'><input type='text' id='table_country_".$i."' value='".$row["invc_country"]."'>";
                  echo "<td style='display:none;'><input type='text' id='table_qty_".$i."' value='".$row["qty"]."'>";

                echo "</tr>";

                $i++;
            }
          ?>
        </tbody>

        <tfoot>
          <tr>
            <td colspan="8"></td>
            <td>
              <a href="<?php echo base_url()."index.php/operacion/delivery/edit" ?>" class="btn btn-danger">CLOSE</a>
              &nbsp;&nbsp;&nbsp;
              <button class="btn btn-success" id="btn_process">EDIT</button>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<input type="hidden" id="inp_total_row" value="<?php echo count($var_data_d); ?>">
<input type="hidden" id="inp_doc_no" value="<?php echo $doc_no; ?>">

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
var glb_idx = $("#inp_total_row").val();

$( function() {
  $( "#inp_destination").autocomplete({ source: autocomplete});
})
//---

function f_non_factura(){
    table = "";
    table = table + "<tr id='table_result_"+glb_idx+"'>";
      table = table + "<td><input type='text' id='table_doc_type_"+glb_idx+"' class='form-control' value='2' disabled></td>";
      table = table + "<td><input type='text' id='table_line_no_"+glb_idx+"' class='form-control' value='-' disabled></td>";
      table = table + "<td><input type='text' id='table_doc_no_"+glb_idx+"' class='form-control' placeholder='Doc No'></td>";
      table = table + "<td><input type='text' id='table_doc_date_"+glb_idx+"' class='form-control' placeholder='Doc Date'></td>";
      table = table + "<td><input type='text' id='table_so_ref_"+glb_idx+"' class='form-control' placeholder='SO Ref'></td>";
      table = table + "<td><input type='text' id='table_cust_no_"+glb_idx+"' class='form-control' placeholder='Cust No'></td>";
      table = table + "<td><input type='text' id='table_subtotal_"+glb_idx+"' class='form-control' placeholder='SubTotal'></td>";
      table = table + "<td><input type='text' id='table_total_"+glb_idx+"' class='form-control' placeholder='Total'></td>";
      table = table + "<td><input type='text' id='table_remarks_"+glb_idx+"' class='form-control' placeholder='Remarks'><span id='table_required_remarks_"+glb_idx+"'></span><span id='table_required_remarks_text_"+glb_idx+"'></span></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_cust_name_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_address_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_address2_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_city_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_state_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_post_code_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_country_"+glb_idx+"'></td>";
      table = table + "<td style='display:none;'><input type='text' id='table_qty_"+glb_idx+"'></td>";
      table = table + "<td><button class='btn btn-sm btn-danger' id='btn_delete_table_"+glb_idx+"' onclick=f_delete_line("+glb_idx+")>X</button></td>";
    table = table + "</tr>";
    glb_idx++;

    $("#table_detail tbody").append(table);
}
//---

$("#btn_go").click(function(){
      var date_from = $("#datepicker_from").val();
      var date_to = $("#datepicker_to").val();

      if(check_from_to(date_from,date_to)){
          $("#data_factura_promo").html("Loading, Please wait...");

          $.ajax({
              url       : "<?php echo base_url();?>index.php/operacion/delivery/edit/get_invoices_whship",
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

function f_delete_line(idx){
    $("#table_result_"+idx).remove();
}
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

    // is everything ok, proceed
    swal({
      title: "Are you sure ?",
      html: "Edit this Delivery",
      type: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }).then(function (result) {
        if(result.value){

            $("#loading_text").text("Editing Delivery Document, Please wait...");
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
            var doc_no_h = $("#inp_doc_no").val();
            var folio = $("#inp_folio").val();
            var subtotal_h = $("#inp_subtotal").val();

            var counter = 0;
            var doc_no=[]; var doc_date=[]; var so_ref=[]; var cust_no=[];
            var subtotal=[]; var total2=[]; var remarks=[]; var cust_name=[];
            var address=[]; var address2=[]; var city=[]; var state2=[];
            var post_code=[]; var country=[]; var qty=[]; var doc_type=[];
            var required_remarks = []; var line_no=[];

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
                    line_no[counter] = $("#table_line_no_"+i).text();

                    if($("#table_required_remarks_"+i).text() == "*") required_remarks[counter] = 1;
                    else required_remarks[counter] = 0;

                    counter++;
                }
            }
            //--

            $.ajax({
                url  : "<?php echo base_url();?>index.php/operacion/delivery/edit/edit_process",
                type : "post",
                dataType  : 'html',
                data : {doc_type:JSON.stringify(doc_type),doc_no:JSON.stringify(doc_no),doc_date:JSON.stringify(doc_date),so_ref:JSON.stringify(so_ref),cust_no:JSON.stringify(cust_no),subtotal:JSON.stringify(subtotal), total2:JSON.stringify(total2),
                remarks:JSON.stringify(remarks),cust_name:JSON.stringify(cust_name),address:JSON.stringify(address),address2:JSON.stringify(address2),
                required_remarks:JSON.stringify(required_remarks),city:JSON.stringify(city),state2:JSON.stringify(state2),post_code:JSON.stringify(post_code),
                country:JSON.stringify(country),qty:JSON.stringify(qty),line_no:JSON.stringify(line_no),
                sending_date:sending_date,destination:destination,state:state,driver:driver,vendor:vendor,domicili:domicili,payment_terms:payment_terms,delivery_status:delivery_status,total:total,counter:counter,box:box,pallet:pallet,remark_h:remark_h,tracking_no:tracking_no,doc_no_h:doc_no_h, folio:folio, subtotal_h:subtotal_h},
                success: function(data){
                    var responsedata = $.parseJSON(data);

                    if(responsedata.status == 1){
                          swal({
                             title: responsedata.msg,
                             type: "success", confirmButtonText: "OK",
                          }).then(function(){
                            setTimeout(function(){
                              $('#loading_body').hide();
                              window.location.href = "<?php echo base_url();?>index.php/operacion/delivery/edit";
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

</script>
