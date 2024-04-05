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
    <div class="col-md-2">
      BOX (CAJA)
      <input type="text" class="form-control" id="inp_box" onkeypress='return isNumberKey(event)' value="<?php echo $var_data_h["box"]; ?>" disabled>
    </div>
    <div class="col-md-2">
      PALLET
      <input type="text" class="form-control" id="inp_pallet" onkeypress='return isNumberKey(event)' value="<?php echo $var_data_h["pallet"]; ?>" disabled>
    </div>
    <div class="col-md-2">
      FOLIO
      <input type="text" class="form-control" id="inp_folio" value="<?php echo $var_data_h["folio"]; ?>" disabled>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-1">
      SUBTOTAL (MXN)
      <input type="text" class="form-control" value="<?php echo sprintf('%0.2f', round($var_data_h["subtotal"],2)) ?>" disabled>
    </div>
    <div class="col-md-2">
      MONTO (MXN)
      <input type="text" class="form-control" id="inp_total"  value="<?php echo sprintf('%0.2f', round($var_data_h["total"],2)); ?>" disabled>
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
      Document No
      <input type="text" class="form-control" value="<?php echo $doc_no; ?>" disabled>
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
                  echo "<td><input type='text' class='form-control' value='".$row["subtotal"]."' disabled style='text-align:right;'></td>";
                  echo "<td><input type='text' class='form-control' value='".$row["total"]."' disabled style='text-align:right;'></td>";
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
        </tbody>

        <tfoot>
          <tr>
            <td colspan="7"></td>
            <td style="text-align:right;">
              <a href="<?php echo base_url()."index.php/operacion/delivery/edit/approve" ?>" class="btn btn-danger">CLOSE</a>
              &nbsp;&nbsp;
              <button class="btn btn-warning" id="btn_cancel" onclick=f_cancel('<?php echo $doc_no ?>')>CANCELED</button>
            </td>
            <td>
              <button class="btn btn-info" id="btn_open" onclick=f_reopen('<?php echo $doc_no ?>')>REOPEN</button>&nbsp;&nbsp;
              <button class="btn btn-success" id="btn_approve" onclick=f_approve('<?php echo $doc_no ?>')>APPROVE</button>
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

function f_reopen(id){
  swal({
    title: "Are you sure ?",
    html: "ReOpen this Document",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
      if(result.value){
        $("#loading_text").text("ReOpen this document, Please wait...");
        $('#loading_body').show();

        $.ajax({
            url  : "<?php echo base_url();?>index.php/operacion/delivery/edit/reopen",
            type : "post",
            dataType  : 'html',
            data : {doc_no: id,},
            success: function(data){
                var responsedata = $.parseJSON(data);

                if(responsedata.status == 1){
                  swal({
                     title: responsedata.msg,
                     type: "success", confirmButtonText: "OK",
                  }).then(function(){
                    setTimeout(function(){
                      $('#loading_body').hide();
                      window.location.href = "<?php echo base_url();?>index.php/operacion/delivery/edit/approve";
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

function f_approve(id){
  swal({
    title: "Are you sure ?",
    html: "Approve this Document",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
      if(result.value){
        $("#loading_text").text("ReOpen this document, Please wait...");
        $('#loading_body').show();

        $.ajax({
            url  : "<?php echo base_url();?>index.php/operacion/delivery/edit/approve_process",
            type : "post",
            dataType  : 'html',
            data : {doc_no: id},
            success: function(data){
                var responsedata = $.parseJSON(data);

                if(responsedata.status == 1){
                  swal({
                     title: responsedata.msg,
                     type: "success", confirmButtonText: "OK",
                  }).then(function(){
                    setTimeout(function(){
                      $('#loading_body').hide();
                      window.location.href = "<?php echo base_url();?>index.php/operacion/delivery/edit/approve";
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

function f_cancel(id){
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
            html: "Cancel this Document",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
          }).then(function (result) {
                if(result.value){
                    $("#loading_text").text("Canceling this document, Please wait...");
                    $('#loading_body').show();

                    $.ajax({
                        url  : "<?php echo base_url();?>index.php/operacion/delivery/edit/cancel_doc",
                        type : "post",
                        dataType  : 'html',
                        data : {doc_no:id, message:message},
                        success: function(data){
                          var responsedata = $.parseJSON(data);

                          if(responsedata.status == 1){
                                swal({
                                   title: responsedata.msg,
                                   type: "success", confirmButtonText: "OK",
                                }).then(function(){
                                  setTimeout(function(){
                                    $('#loading_body').hide();
                                    window.location.href = "<?php echo base_url();?>index.php/operacion/delivery/edit/approve";
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

</script>
