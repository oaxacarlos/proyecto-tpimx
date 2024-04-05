<script>
$(document).ready(function() {
    $('#DataTable').DataTable();

    $("#inp_received_date").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>

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
    <div class="col-md-3">
      MONTO (MXN)
      <input type="text" class="form-control" id="inp_total" onkeypress='return isNumberKey(event)' value="<?php echo $var_data_h["total"]; ?>" disabled>
    </div>
    <div class="col-md-2">
      Delivery Status
      <select id="inp_delivery_status" class="form-control" disabled>
        <?php
          foreach($var_delv_status as $row){
              if($row["name"] == "TRANSITO") $selected = "selected";
              else $selected = "";
              echo "<option value='".$row["name"]."' ".$selected.">".$row["name"]."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-md-2">
      Received Date
      <input type="text" class="form-control" id="inp_received_date" value="<?php echo $var_data_h["receiv_date"]; ?>" readonly style="background-color:white;">
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
                  echo "<td><input type='text' class='form-control' value='".$row["subtotal"]."' disabled></td>";
                  echo "<td><input type='text' class='form-control' value='".$row["total"]."' disabled></td>";
                  echo "<td><input type='text' value='".$row["remark1"]."' class='form-control' disabled>";

                  if($row["required_remark1"] == "1") echo "<span>*</span>";
                  else echo "<span></span>";
                  echo "</td>";

                echo "</tr>";

                $i++;
            }
          ?>
        </tbody>

        <tfoot>
          <tr>
            <td colspan="8"></td>
            <td>
              <button class="btn btn-success" id="btn_update" onclick=f_update('<?php echo $doc_no ?>')>UPDATE</button>
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

<script>


function f_update(id){

  if($("#inp_received_date").val() == ""){
      show_error("You have to fill Received Date");
      return false;
  }

  swal({
    title: "Are you sure ?",
    html: "Update the RECEIVED information..",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
      if(result.value){
        $("#loading_text").text("Updating RECEIVED this document, Please wait...");
        $('#loading_body').show();

        var doc_no = $("#inp_doc_no").val();
        var received_date = $("#inp_received_date").val();

        $.ajax({
            url  : "<?php echo base_url();?>index.php/operacion/delivery/edit/receivedate_process",
            type : "post",
            dataType  : 'html',
            data : {doc_no: doc_no, received_date:received_date },
            success: function(data){
                var responsedata = $.parseJSON(data);

                if(responsedata.status == 1){
                      swal({
                         title: responsedata.msg,
                         type: "success", confirmButtonText: "OK",
                      }).then(function(){
                        setTimeout(function(){
                          $('#loading_body').hide();
                          location.reload();
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
