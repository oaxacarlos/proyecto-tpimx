<script>
$(document).ready(function() {
    $("#inp_invc_vendor_date").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#inp_invc_payment_date").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>

<?php

// calculate total
$total_sub_total = 0;
$total_total = 0;
foreach($var_data_d as $row){
    $total_sub_total = $total_sub_total + $row["subtotal"];
    $total_total = $total_total + $row["total"];
}
//---

// calculate percentage
$percentage_cost = percentage($var_data_h["subtotal"], $total_sub_total );

?>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-1">
      Doc Date
      <input type="text" class="form-control" disabled value="<?php echo $var_data_h["doc_date"]; ?>">
    </div>
    <div class="col-md-2">
      Sending Date (ENVIO)
      <input type="text" class="form-control" id="inp_sending_date" value="<?php echo $var_data_h["delv_date"]; ?>" disabled>
    </div>
    <div class="col-md-2">
      Destination (DESTINO)
      <input type="text" class="form-control" id="inp_destination" value="<?php echo $var_data_h["destination"]; ?>" disabled>
    </div>
    <div class="col-md-2">
      State (ESTADO)
      <select id="inp_state" class="form-control" disabled>
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
      <select id="inp_driver" class="form-control" disabled>
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
      <select id="inp_vendor" class="form-control" disabled>
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
      <input type="text" class="form-control" id="inp_tracking_no" value="<?php echo $var_data_h["tracking_no"]; ?>" disabled>
    </div>
    <div class="col-md-2">
      DOMICILI
      <input type="text" class="form-control" id="inp_domicili" value="<?php echo $var_data_h["domicili"]; ?>" disabled>
    </div>
    <div class="col-md-2">
      Payment Terms
      <select id="inp_payment_terms" class="form-control" disabled>
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
      <input type="text" class="form-control" id="inp_remark" value="<?php echo $var_data_h["remark1"]; ?>" disabled>
    </div>
    <div class="col-md-1">
      BOX (CAJA)
      <input type="text" class="form-control" id="inp_box" onkeypress='return isNumberKey(event)' value="<?php echo $var_data_h["box"]; ?>" disabled>
    </div>
    <div class="col-md-1">
      PALLET
      <input type="text" class="form-control" id="inp_pallet" onkeypress='return isNumberKey(event)' value="<?php echo $var_data_h["pallet"]; ?>" disabled>
    </div>
    <div class="col-md-2">
      Document No
      <input type="text" class="form-control" value="<?php echo $doc_no; ?>" disabled>
    </div>
    <div class="col-md-2">
      Folio
      <input type="text" class="form-control" value="<?php echo $var_data_h["folio"]; ?>" disabled>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-1">
      SUBTOTAL (MXN)
      <input type="text" class="form-control" id="inp_total" onkeypress='return isNumberKey(event)' value="<?php echo sprintf('%0.2f', round($var_data_h["subtotal"],2)) ?>" disabled>
    </div>
    <div class="col-md-2">
      MONTO (MXN)
      <input type="text" class="form-control" id="inp_total" onkeypress='return isNumberKey(event)' value="<?php echo sprintf('%0.2f', round($var_data_h["total"],2)); ?>" disabled>
    </div>
    <div class="col-md-2">
      PERCENTAGE COST (%)
      <input type="text" class="form-control" value="<?php echo $percentage_cost; ?>" disabled>
    </div>
    <div class="col-md-2">
      Delivery Status
      <select id="inp_delivery_status" class="form-control" disabled>
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
      Received Date
      <input type="text" class="form-control" id="inp_received_date" value="<?php echo $var_data_h["receiv_date"]; ?>" disabled>
    </div>
    <div class="col-md-2">
      Received Person
      <input type="text" class="form-control" id="inp_received_person" max="45" value="<?php echo $var_data_h["receiv_person"]; ?>" disabled>
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
                  echo "<td><input type='text' class='form-control' value='".$row["doc_type"]."' disabled size='1'></td>";
                  echo "<td>".$row["line_no"]."</td>";
                  echo "<td><input type='text' class='form-control' value='".$row["invc_doc_no"]."' disabled></td>";
                  echo "<td><input type='text' class='form-control' value='".$row["invc_doc_date"]."' disabled></td>";
                  echo "<td><input type='text' class='form-control' value='".$row["so_ref"]."' disabled></td>";
                  echo "<td><input type='text' class='form-control' value='".$row["invc_cust_no"]."' disabled></td>";
                  echo "<td><input type='text' class='form-control' value='".sprintf('%0.2f',$row["subtotal"])."' disabled></td>";
                  echo "<td><input type='text' class='form-control' value='".sprintf('%0.2f',$row["total"])."' disabled></td>";
                  echo "<td><input type='text' value='".$row["remark1"]."' class='form-control' disabled>";

                  if($row["required_remark1"] == "1") echo "<span>*</span>";
                  else echo "<span></span>";
                  echo "</td>";

                  echo "<td><button class='btn btn-sm btn-danger' id='btn_delete_table_".$i."' onclick=f_delete_line(".$i.")>X</button></td>";

                echo "</tr>";

                $i++;
            }

            echo "<tr>
                    <td colspan='6' style='text-align:right;'><b>TOTAL</b></td>
                    <td style='text-align:right;'><b>".$total_sub_total."</b></td>
                    <td style='text-align:right;'><b>".$total_total."</b></td>
                    <td colspan='2'></td>
                  </tr>";
          ?>
          <tr><td colspan='10'></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>


<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-2">
      INVOICE VENDOR NO
      <input type="text" class="form-control" id="inp_invc_vendor_no" value="">
    </div>
    <div class="col-md-2">
      INVOICE VENDOR DATE
      <input type="text" class="form-control" id="inp_invc_vendor_date" value="" readonly style="background-color:white;">
    </div>
    <div class="col-md-2">
      INVOICE SUBTOTAL
      <input type="text" class="form-control" id="inp_invc_vendor_subtotal" value="<?php echo $var_data_h["subtotal"] ?>" onkeypress='return isNumberKey(event)'>
    </div>
    <div class="col-md-2">
      INVOICE TOTAL
      <input type="text" class="form-control" id="inp_invc_vendor_total" value="<?php echo $var_data_h["total"] ?>" onkeypress='return isNumberKey(event)'>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-2">
      PAYMENT DATE
      <input type="text" class="form-control" id="inp_invc_payment_date" value="" readonly style="background-color:white;">
    </div>
    <div class="col-md-3">
      UUID
      <input type="text" class="form-control" id="inp_invc_vendor_uuid" value="" placeholder="UUID">
    </div>
    <div class="col-md-3">
      REMARKS
      <input type="text" class="form-control" id="inp_invc_vendor_remarks" value="" placeholder="Remarks (Optional)">
    </div>
    <div class="col-md-4" style='margin-top:24px;'>
      <a href="<?php echo base_url()."index.php/finance/delivery/payment" ?>" class="btn btn-danger">CLOSE</a>
      <button class="btn btn-success" id="btn_update" style='margin-left:20px;' onclick=f_update('<?php echo $doc_no ?>')>UPDATE</button>
    </div>
  </div>
</div>

<input type="hidden" id="inp_total_row" value="<?php echo count($var_data_d); ?>">
<input type="hidden" id="inp_doc_no" value="<?php echo $doc_no; ?>">

<?php echo loading_body_full(); ?>

<script>


function f_update(id){

  if($("#inp_invc_vendor_no").val() == ""){
      show_error("You have to fill Vendor Invoice No");
      return false;
  }

  if($("#inp_invc_vendor_date").val() == ""){
      show_error("You have to fill Vendor Invoice Date");
      return false;
  }

  if($("#inp_invc_vendor_subtotal").val() == ""){
      show_error("You have to fill Vendor Invoice Subtotal");
      return false;
  }

  if($("#inp_invc_vendor_total").val() == ""){
      show_error("You have to fill Vendor Invoice Total");
      return false;
  }

  if($("#inp_invc_payment_date").val() == ""){
      show_error("You have to fill Payment Date");
      return false;
  }

  if($("#inp_invc_vendor_uuid").val() == ""){
      show_error("You have to fill UUID");
      return false;
  }

  swal({
    title: "Are you sure ?",
    html: "Update the PAYMENT information, after UPDATED you can not change anymore...",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
      if(result.value){
        $("#loading_text").text("Updating Payment this document, Please wait...");
        $('#loading_body').show();

        var doc_no = $("#inp_doc_no").val();
        var invc_vendor_no = $("#inp_invc_vendor_no").val();
        var invc_vendor_date = $("#inp_invc_vendor_date").val();
        var invc_vendor_subtotal = $("#inp_invc_vendor_subtotal").val();
        var invc_vendor_total = $("#inp_invc_vendor_total").val();
        var invc_vendor_remarks = $("#inp_invc_vendor_remarks").val();
        var invc_payment_date =  $("#inp_invc_payment_date").val();
        var invc_vendor_uuid =  $("#inp_invc_vendor_uuid").val();

        $.ajax({
            url  : "<?php echo base_url();?>index.php/finance/delivery/payment/update",
            type : "post",
            dataType  : 'html',
            data : {doc_no: doc_no, invc_vendor_no:invc_vendor_no, invc_vendor_date:invc_vendor_date, invc_vendor_subtotal:invc_vendor_subtotal, invc_vendor_total:invc_vendor_total, invc_vendor_remarks:invc_vendor_remarks, invc_payment_date:invc_payment_date, invc_vendor_uuid:invc_vendor_uuid},
            success: function(data){
                var responsedata = $.parseJSON(data);

                if(responsedata.status == 1){
                  swal({
                     title: responsedata.msg,
                     type: "success", confirmButtonText: "OK",
                  }).then(function(){
                    setTimeout(function(){
                      $('#loading_body').hide();
                      window.location.href = "<?php echo base_url();?>index.php/finance/delivery/payment";
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
