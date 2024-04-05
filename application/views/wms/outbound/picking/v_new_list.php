<style>

</style>

<div class="modal" id="myModalPick" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Picking</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_pick"></div>
    </div>
  </div>
</div>

<!-- 2023-06-23 -->
<div class="modal" id="myModalDetail_edit" style='font-size:12px;'>
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Items</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_edit">
        <input type="text" id="inp_edit_id" value="" style="display:none;">
        <input type="text" id="inp_edit_item_code" value="" style="display:none;">
        <input type="text" id="inp_edit_qty" value="" style="display:none;">
        <input type="text" id="inp_edit_loc" value="" style="display:none;">
        <input type="text" id="inp_edit_zone" value="" style="display:none;">
        <input type="text" id="inp_edit_area" value="" style="display:none;">
        <input type="text" id="inp_edit_rack" value="" style="display:none;">
        <input type="text" id="inp_edit_bin" value="" style="display:none;">
        <input type="text" id="inp_edit_whsip_no" value="" style="display:none;">
        <input type="text" id="inp_edit_desc" value="" style="display:none;">
        <input type="text" id="inp_edit_src_line_no" value="" style="display:none;">
        <div class="container-fluid">
          Cantidad Ahorita <input type="TEXT" class="form-control" id="modal_qty_total" onchange=f_calculate() disabled style="font-size:40px;">
        </div>

        <div class="container-fluid" style="margin-top:20px;">
          Modificacion
          <div class="row">
            <div class="col-3">
              <button class="btn btn-danger" style="font-size:40px;" id="btn_minus">-</button>
            </div>
            <div class="col-5">
              <input type="text" class="form-control" id="modal_qty" disabled style="font-size:40px; background-color:white;">
            </div>
            <div class="col-3">
              <button class="btn btn-danger" style="font-size:40px;" id="btn_plus">+</button>
            </div>
          </div>
        </div>

        <div class="container-fluid" style="margin-top:20px;">
          Resultado<input type="text" class="form-control" id="modal_qty_result" disabled style="font-size:40px;">
        </div>
        <div class="container-fluid" style="margin-top:40px;">
          <button class="btn btn-primary form-control" id="btn_edit_detail">ENVIAR</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end here -->

  <div class="row">
    <div class="container" style="margin-top:10px;">
        <table class="table table-bordered table-sm table-striped">
          <thead>
            <tr>
              <th>Item Code</th>
              <th>Desc</th>
              <th>Qty</th>
              <th>Qty Picked</th>
              <th>Qty Remain</th>
              <th>Uom</th>
              <th>Action</th>
              <th>Available</th>
              <th>Avail Status</th>
              <th>Conversion Status</th>
              <th>Action</th>
              <th>AUTO</th>
            </tr>
          </thead>
          <?php
          $i=0;
          foreach($var_outbound_d as $row){
            $qty_rem = $row['qty_to_ship']-$row['qty_to_picked'];

            if($qty_rem == 0 || is_null($row['pcs'])) $btn_disabled = "disabled";
            else $btn_disabled = "";

            if($row['available'] >= $row['qty_to_ship']){
                $yes_label = "";
                $yes = "YES";
            }
            else{
              $yes_label = "danger";
              $yes = "NO";
            }
            //--

            if(is_null($row['pcs']) || $row['pcs'] <= 0){
              $yes_conversion = "NO";
              $yes_label_conversion = "danger";
            }
            else{
               $yes_conversion = "YES";
               $yes_label_conversion = "";
            }


            echo "<tr>";
              echo "<td id='tbl_list_item_".$i."'>".$row['item_code']."</td>";
              echo "<td id='tbl_list_desc_".$i."'>".$row['description']."</td>";
              echo "<td id='tbl_list_qty_".$i."'>".$row['qty_to_ship']."</td>";
              echo "<td id='tbl_list_qtypick_".$i."'>".convert_number2($row['qty_to_picked'])."</td>";
              echo "<td id='tbl_list_qtyrem_".$i."'>".convert_number2($qty_rem)."</td>";
              echo "<td id='tbl_list_uom_".$i."'>".$row['uom']."</td>";
              echo "<td><button class='btn btn-primary btn-sm' onclick=f_pick('".$row['item_code']."',".$i.") id='btn_tbl_list_pick_".$i."' ".$btn_disabled."><i class='bi bi-cart4'></button></td>";
              echo "<td>".$row['available']."</td>";
              echo "<td><div class='badge badge-".$yes_label."' id='tbl_list_available_".$i."'>".$yes."</td>";
              echo "<td><div class='badge badge-".$yes_label_conversion."' id='tbl_list_conversion_".$i."'>".$yes_conversion." (".$row['pcs'].")</td>";

              if($yes == "YES") echo "<td>-</td>";
              else echo "<td><button class='btn btn-sm btn-secondary text-right' id='tbl_btn_edit_".$i."' onclick=f_edit_show(".$i.") style='margin-left:20px;'>EDIT</button></td>";

              echo "<td id='tbl_list_auto_".$i."' ></td>"; 

              echo "<td id='tbl_list_line_no_".$i."' style='display:none;'>".$i."</td>";
              echo "<td id='tbl_list_src_line_no_".$i."' style='display:none;'>".$row['line_no']."</td>";
            echo "</tr>";
            $i++;
          }
          ?>
        </table>
    </div>
    </div>

<input type="hidden" id='total_outbound_d' value='<?php echo count($var_outbound_d);  ?>'>

<script>

function f_pick(id,i){
  var link = 'wms/outbound/v_pick_item';
  var desc = $("#tbl_list_desc_"+i).text();
  var qty_rem = $("#tbl_list_qtyrem_"+i).text();
  var src_line_no = $("#tbl_list_src_line_no_"+i).text();
  var uom = $("#tbl_list_uom_"+i).text();
  var h_whs = $("#h_whs").val(); // 2023-03-06 WH3

  data = {'id':id, 'link':link, 'qty_rem':qty_rem, 'desc':desc, 'line':i, 'src_line_no':src_line_no, 'uom':uom, 'h_whs':h_whs }
  $('#modal_detail_pick').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail_pick').load(
      "<?php echo base_url();?>index.php/wms/outbound/picking/get_pick_item",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalPick').modal();
}
//---

function disabled_enabled_btn_pick(line){
    if(convert_number($("#tbl_list_qtyrem_"+line).text()) <= 0) $("#btn_tbl_list_pick_"+line).attr("disabled", "disabled");
    else $("#btn_tbl_list_pick_"+line).removeAttr("disabled");
}
//---

// show modal edit 2023-06-13
function f_edit_show(id){
    $("#inp_edit_id").val($("#tbl_list_line_no_"+id).text());
    $("#inp_edit_item_code").val($("#tbl_list_item_"+id).text());
    $("#inp_edit_desc").val($("#tbl_list_desc_"+id).text());
    $("#inp_edit_qty").val($("#tbl_list_qtyrem_"+id).text());
    $("#inp_edit_loc").val("");
    $("#inp_edit_zone").val("");
    $("#inp_edit_area").val("");
    $("#inp_edit_rack").val("");
    $("#inp_edit_bin").val("");
    $("#inp_edit_whsip_no").val($("#h_doc_no").val());
    $("#inp_edit_src_line_no").val($("#tbl_list_src_line_no_"+id).text());

    var min = parseInt($("#tbl_list_qtyrem_"+id).text())*-1;
    $("#modal_qty").attr({
       "max" : -1,        // substitute your own
       "min" : min        // values (or variables) here
    });
    $("#modal_qty").val("-1");

    $("#modal_qty_total").val($("#tbl_list_qtyrem_"+id).text());

    f_calculate();

    $("#myModalDetail_edit").modal();
}

// show modal edit 2023-06-21
function f_calculate(){
    var qty_total = parseInt($("#inp_edit_qty").val());
    var qty_minus = parseInt($("#modal_qty").val());
    var qty_result = qty_total + qty_minus;

    $("#modal_qty_result").val(qty_result);
}
//---

// show modal edit 2023-06-21
$("#btn_minus").click(function(){
    var qty_result = parseInt($("#modal_qty_result").val());

    if(qty_result > 0){
      var qty = parseInt($("#modal_qty").val());
      qty = qty - 1;
      $("#modal_qty").val(qty);
      f_calculate();
    }
})
//---

// show modal edit 2023-06-21
$("#btn_plus").click(function(){
    var qty_result = parseInt($("#modal_qty_result").val());
    var qty_total = parseInt($("#inp_edit_qty").val());
    var qty = parseInt($("#modal_qty").val());

    if(qty < -1){
      qty = qty + 1;
      $("#modal_qty").val(qty);
      f_calculate();
    }
})
//---

// show modal edit 2023-06-21
$("#btn_edit_detail").click(function(){
      // get all the data

      swal({
        input: 'textarea',
        inputPlaceholder: 'Escribe tu mensaje aquí',
        showCancelButton: true,
        confirmButtonText: 'OK'
      }).then(function (result) {
          if(result.dismiss == "cancel"){}
          else{
            if(result.value == ""){ show_error("Tienes que escribir mensaje");}
            else{
                var message = result.value;
                swal({
                  title: "Estas seguro",
                  html: "Continuar con esto Editado",
                  type: "question",
                  showCancelButton: true,
                  confirmButtonText: "Yes",
                  showLoaderOnConfirm: true,
                  closeOnConfirm: false
                }).then(function (result) {
                    if(result.value){
                        $("#loading_text").text("Creando Modificar, Un momento...");
                        $('#loading_body').show();

                        var qty_before_edit = $("#inp_edit_qty").val();
                        var qty_minus = $("#modal_qty").val();
                        var qty_result = $("#modal_qty_result").val();
                        var id = $("#inp_edit_id").val();
                        var item_code = $("#inp_edit_item_code").val();
                        var loc = $("#inp_edit_loc").val();
                        var zone = $("#inp_edit_zone").val();
                        var area = $("#inp_edit_area").val();
                        var rack = $("#inp_edit_rack").val();
                        var bin = $("#inp_edit_bin").val();
                        var wship_no = $("#inp_edit_whsip_no").val();
                        var desc = $("#inp_edit_desc").val();
                        var pick_doc_no = "";
                        var src_line_no = $("#inp_edit_src_line_no").val();
                        var type = "2";
                        message = "Admin Picking - "+message;

                        $.ajax({
                            url  : "<?php echo base_url();?>index.php/wms/outbound/edit/create_new2",
                            type : "post",
                            dataType  : 'html',
                            data : {qty_before_edit:qty_before_edit, qty_minus:qty_minus, qty_result:qty_result, id:id, item_code: item_code, loc:loc, zone:zone, area:area, zone:zone, rack:rack, bin:bin, wship_no:wship_no, message:message, desc:desc, pick_doc_no:pick_doc_no, src_line_no:src_line_no,id:id, type:type},
                            success: function(data){
                              var responsedata = $.parseJSON(data);

                              if(responsedata.status == 1){
                                    swal({
                                       title: responsedata.msg,
                                       type: "success", confirmButtonText: "OK",
                                    }).then(function(){
                                      setTimeout(function(){
                                        $('#loading_body').hide();
                                        $("#myModalDetail_edit").modal('toggle');
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


</script>
