<style>
  tr{
    font-size: 12px;
  }

  .quantity{
   display:flex;
   width:160px;
}

/* it will support chrome and firefox */
.quantity input[type=number]::-webkit-inner-spin-button,
.quantity input[type=number]::-webkit-outer-spin-button{
   -webkit-appearance:none;
}

.quantity input,.quantity button{
   width:50px;
   padding:.5em;
   font-size:1.2rem;
   text-align:center;
   font-weight:900;
   background:white;
   border:1px solid #aaa;
}

.quantity input{
   border-left:none;
   border-right:none;
}

</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      GoTo Pick Process
</div>

<div class="modal" id="myModalDetail_put" style='font-size:12px;'>
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Put This Items</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_put">
          <div class="container text-center">
            <button class='btn btn-primary' id='btn_start_pick'>START</button>
          </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="myModalDetail_confirm" style='font-size:12px;'>
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Finish Put This Items</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_confirm">
          <div class="container text-center">
            <button class='btn btn-warning' id='btn_confirm_put'>FINISH</button>
          </div>
      </div>
    </div>
  </div>
</div>

<!-- 2023-06-16 -->
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


<div class="container">

<input type="hidden" value="<?php echo get_datetime_now(); ?>" id='inp_start_datetime'>
<input type="hidden" value="" id='inp_finished_datetime'>
<input type='hidden' value='' id='inp_item_code'>
<input type='hidden' value='' id='inp_desc'>
<input type='hidden' value='' id='inp_qty'>
<input type='hidden' value='' id='inp_loc'>
<input type='hidden' value='' id='inp_zone'>
<input type='hidden' value='' id='inp_area'>
<input type='hidden' value='' id='inp_rack'>
<input type='hidden' value='' id='inp_bin'>
<input type='hidden' value='' id='inp_index'>
<input type='hidden' value='<?php echo count($var_pick_goto_d); ?>' id='inp_total_row'>

<div class='row'>
  <div class='col-8'>
    Doc No
    <input type='text' value='<?php echo $doc_no; ?>' class='form-control' disabled id='inp_doc_no'>
  </div>
</div>

<div class='row' style='margin-bottom:20px;'>
  <div class='col-8'>
    <button class="btn btn-danger btn-sm" id='btn_cancel' style='margin-top:30px;'>Canceled</button>
    <button class="btn btn-success btn-sm" id='btn_all_finished' style='margin-top:30px; margin-left:2px;'>Finished</button>
  </div>
</div>

    <?php

    $i=1;
    foreach($var_pick_goto_d as $row){
        echo "<div class='card' style='margin-bottom:20px;'>";
            echo "<div class='card-header text-right' style='font-size:12px; font-weight:bold;'>";
              echo "<span style='margin-right:10px; font-size:20px;'><span id='tbl_item_code_".$i."'>".$row['item_code']."</span> | <span id='tbl_description_".$i."'>".$row['description']."</span></span>";

              if(is_null($row["completely_picked"]) or $row["completely_picked"]==""){
                  echo "<span><button class='btn btn-sm btn-primary text-right' id='tbl_btn_start_".$i."' onclick=f_pick_show(".$i.")>PICK</button></span>";
                  echo "<span><button class='btn btn-sm btn-secondary text-right' id='tbl_btn_edit_".$i."' onclick=f_edit_show(".$i.") style='margin-left:20px;'>EDIT</button></span>"; // 2023-06-13
              }

              echo "<span><button class='btn btn-sm btn-warning text-right' id='tbl_btn_confirm_".$i."' onclick=f_confirm_show(".$i.") style = 'display:none;'> CONFIRM</button></span>";

              if(!is_null($row["completely_picked"]) or $row["completely_picked"]!="")
                echo "<button class='btn btn-success btn-sm  text-right'  id='tbl_btn_finish_".$i."'>FINISHED</button>";
              else
                echo "<button class='btn btn-success btn-sm  text-right'  id='tbl_btn_finish_".$i."' style='display:none;'>FINISHED</button>";

            echo "</div>";

            echo "<div class='card-body'>";
              echo "<table class='table table-sm'>";

                if($row['area_code'] == "X") $message = "Por favor elija el artículo sin ticketa porque la ubicacion es 'X'";
                else $message = "";

                echo "<tr><td colspan='2' style='font-size:20px; color:red;'>".$message."</td></tr>";

                echo "<tr><th>Qty</th><td style='font-size:20px;'><span id='tbl_qty_".$i."'><b>".$row['qty_to_picked']."</span> <span id='tbl_uom_".$i."'>".$row['uom']."</b></span></td></tr>";

                // detail
                echo "<tr><th>Detail</th><td>";
                  echo "<table class='table table-bordered table-responsive table-striped' style='max-height:200px; width:100%;'>";
                    echo "<thead>";
                      echo "<tr>
                              <th>No</th>
                              <th>Master Barcode</th>
                              <th>SN</th>
                              <th>Qty</th>
                              <th>Caja</th>
                            </tr>";
                    echo "</thead>";
                    $no = 1;
                    $total_qty = 0;
                    $total_box = 0;
                  foreach($var_pick_goto_d2 as $row2){
                      if($row2["item_code"] == $row["item_code"] &&
                          $row['location_code'] == $row2['location_code_pick'] &&
                          $row['zone_code'] == $row2['zone_code_pick'] &&
                          $row['area_code'] == $row2['area_code_pick'] &&
                          $row['rack_code'] == $row2['rack_code_pick'] &&
                          $row['bin_code'] == $row2['bin_code_pick']
                      ){
                          echo "<tr style='font-size:20px;'>";
                            echo "<td >".$no."</td>";

                            if($row2["qty"] == $row2["pcs"] && $row2["qty"] > 1) echo "<td>".$row2["sn2_pick"]."</td>";
                            else echo "<td>-</td>";

                            if($row2["qty"]=='1' && $row2["pcs"]=='') echo "<td>".$row2["serial_number_pick"]."</td>";
                            else if($row2["qty"] == $row2["pcs"] && $row2["qty"]=="1") echo "<td>".$row2["serial_number_pick"]."</td>";
                            else if($row2["qty"] == $row2["pcs"]) echo "<td>-</td>";
                            else if($row2["qty"] != $row2["pcs"]) echo "<td>-</td>";
                            else echo "<td>-</td>";

                            echo "<td>".$row2["qty"]."</td>";

                            if($row2["qty"]=='' && $row2["pcs"]=='') echo "<td>-</td>";
                            else if($row2["qty"] == $row2["pcs"] && $row2["qty"]=="1") echo "<td>-</td>";
                            else if($row2["qty"] == $row2["pcs"]){
                              echo "<td>1</td>";
                              $total_box += 1;
                            }
                            else echo "<td>-</td>";

                          echo "</tr>";
                          $no++;
                          $total_qty += $row2["qty"];
                      }
                  }

                    echo "<tr style='font-size:20px;'>";
                      echo "<td colspan='3'>TOTAL</td>";
                      echo "<td>".$total_qty."</td>";
                      echo "<td>".$total_box."</td>";
                    echo "</tr>";

                  echo "</table>";
                echo "</td></tr>";
                //--

                echo "<tr><th>Loc</th><td id='tbl_loc_code_".$i."'>".$row['location_code']."</td></tr>";
                echo "<tr><th>Position</th>
                      <td style='font-size:25px;'>
                        <b><span id='tbl_zone_code_".$i."'>".$row['zone_code']."</span> -
                        <span id='tbl_area_code_".$i."'>".$row['area_code']."</span> -
                        <span id='tbl_rack_code_".$i."'>".$row['rack_code']."</span> -
                        <span id='tbl_bin_code_".$i."'>".$row['bin_code']."</span></b>
                      </td>
                      </tr>";

                //echo "<tr><th>Zone</th><td id='tbl_zone_code_".$i."'>".$row['zone_code']."</td></tr>";
                //echo "<tr><th>Area</th><td id='tbl_area_code_".$i."'>".$row['area_code']."</td></tr>";
                //echo "<tr><th>Rack</th><td id='tbl_rack_code_".$i."'>".$row['rack_code']."</td></tr>";
                //echo "<tr><th>Bin</th><td id='tbl_bin_code_".$i."'>".$row['bin_code']."</td></tr>";
                echo "<tr><th>Start</th><td id='tbl_start_time_".$i."'>".$row["picked_datetime"]."</td></tr>";
                echo "<tr><th>Finish</th><td id='tbl_finish_time_".$i."'>".$row["completely_picked"]."</td></tr>";
                echo "<td id='tbl_line_no_".$i."'>".$row['line_no']."</td>";
                echo "<td id='tbl_src_no_".$i."'>".$row['src_no']."</td>";
                echo "<td id='tbl_src_line_no_".$i."'>".$row['src_line_no']."</td>";
              echo "</table>";
            echo "</div>";
        echo "</div>";

        $i++;
    }
    ?>
  </tbody>
</table>
</div>
</div>

<?php echo loading_body_full(); ?>

<script>

function f_pick_show(id){
    $('#inp_qty').val($('#tbl_qty_'+id).text()+" "+$('#tbl_uom_'+id).text());
    $('#inp_item_code').val($('#tbl_item_code_'+id).text());
    $('#inp_desc').val($('#tbl_description_'+id).text());
    $('#inp_loc').val($('#tbl_loc_code_'+id).text());
    $('#inp_zone').val($('#tbl_zone_code_'+id).text());
    $('#inp_area').val($('#tbl_area_code_'+id).text());
    $('#inp_rack').val($('#tbl_rack_code_'+id).text());
    $('#inp_bin').val($('#tbl_bin_code_'+id).text());
    $('#inp_index').val(id);
    $('#myModalDetail_put').modal();
}
//---

function f_confirm_show(id){
    $('#inp_qty').val($('#tbl_qty_'+id).text()+" "+$('#tbl_uom_'+id).text());
    $('#inp_item_code').val($('#tbl_item_code_'+id).text());
    $('#inp_desc').val($('#tbl_description_'+id).text());
    $('#inp_loc').val($('#tbl_loc_code_'+id).text());
    $('#inp_zone').val($('#tbl_zone_code_'+id).text());
    $('#inp_area').val($('#tbl_area_code_'+id).text());
    $('#inp_rack').val($('#tbl_rack_code_'+id).text());
    $('#inp_bin').val($('#tbl_bin_code_'+id).text());
    $('#inp_index').val(id);

    $('#myModalDetail_confirm').modal();
}
//---

$('#btn_start_pick').click(function(){
    var id = $('#inp_index').val();
    $('#tbl_start_time_'+id).text(getfulldatetimenow());
    $('#myModalDetail_put').modal('hide');
    $('#tbl_btn_start_'+id).hide();
    $('#tbl_btn_confirm_'+id).show();
    $('#tbl_btn_edit_'+id).hide(); // 2023-06-13
})
//--

$('#btn_confirm_put').click(function(){
    var id = $('#inp_index').val();
    $('#tbl_finish_time_'+id).text(getfulldatetimenow());
    $('#myModalDetail_confirm').modal('hide');

    // UPDATE in database
    f_update_start_finish_time(id);

})
//---

$("#btn_cancel").click(function(){
  swal({
    title: "Are you sure ?",
    html: "Cancel this Process, all your Data would be lost",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
        if(result.value){
            window.location.href = "<?php echo base_url();?>index.php/wms/outbound/picking/goto";
        }
  })
})
//---

$("#btn_all_finished").click(function(){
    if(!check_all_finished()){
      show_error("You haven't finished the Picklist");
      return false;
    }

    swal({
      title: "Are you sure ?",
      html: "Finish this Picklist",
      type: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }).then(function (result) {
          if(result.value){

            $("#loading_text").text("Saving your data, Please wait...");
            $('#loading_body').show();

              // get all data
              var total_row = $("#inp_total_row").val();

              var d_line_no = [];
              var d_src_no = [];
              var d_item_code = [];
              var d_qty = [];
              var d_start_time = [];
              var d_finish_time = [];
              var h_doc_no = $("#inp_doc_no").val();
              var counter = 0;
              var start_all_datetime = $('#inp_start_datetime').val();
              var finish_all_datetime = getfulldatetimenow();

              for(i=1;i<=total_row;i++){
                d_line_no[counter] = $('#tbl_line_no_'+i).text();
                d_src_no[counter] = $('#tbl_src_no_'+i).text();
                d_item_code[counter] = $('#tbl_item_code_'+i).text();
                d_start_time[counter] = $('#tbl_start_time_'+i).text();
                d_finish_time[counter] = $('#tbl_finish_time_'+i).text();
                d_qty[counter] = $('#tbl_qty_'+i).text();
                counter++;
              }

              // ajax
              $.ajax({
                  url  : "<?php echo base_url();?>index.php/wms/outbound/picking/goto_finish",
                  type : "post",
                  dataType  : 'html',
                  data : { d_line_no:JSON.stringify(d_line_no), d_item_code:JSON.stringify(d_item_code), d_start_time:JSON.stringify(d_start_time), d_finish_time:JSON.stringify(d_finish_time),h_doc_no:h_doc_no, start_all_datetime:start_all_datetime, finish_all_datetime:finish_all_datetime, d_src_no:JSON.stringify(d_src_no), d_qty:JSON.stringify(d_qty) },
                  success: function(data){
                    var responsedata = $.parseJSON(data);

                    if(responsedata.status == 1){
                          swal({
                             title: responsedata.msg,
                             type: "success", confirmButtonText: "OK",
                          }).then(function(){
                            setTimeout(function(){
                              $('#loading_body').hide();
                              window.location.href = "<?php echo base_url();?>index.php/wms/outbound/picking/goto";
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

function check_all_finished(){
    var total_row = $("#inp_total_row").val();

    var check = 1;
    for(i=1;i<=total_row;i++){
        if($("#tbl_btn_finish_"+i).is(":hidden")){
          check = 0;
          break
        }
    }

    if(check == 1) return true; else return false;

}
//--

function f_update_start_finish_time(id){
    var start = $('#tbl_start_time_'+id).text();
    var finish = $('#tbl_finish_time_'+id).text();
    var doc_no = $("#inp_doc_no").val();
    var line_no = $("#tbl_line_no_"+id).text();

    // ajax
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/outbound/picking/update_start_finish_time_d_v2",
        type : "post",
        dataType  : 'html',
        data : { start:start, finish:finish, doc_no:doc_no, line_no:line_no },
        success: function(data){
            var responsedata = $.parseJSON(data);

            if(responsedata == 1){
                $('#tbl_btn_confirm_'+id).hide();
                $('#tbl_btn_finish_'+id).show();
            }
        }
    })
}
//---

// show modal edit 2023-06-13
function f_edit_show(id){
    $("#inp_edit_id").val(id);
    $("#inp_edit_item_code").val($("#tbl_item_code_"+id).text());
    $("#inp_edit_desc").val($("#tbl_description_"+id).text());
    $("#inp_edit_qty").val($("#tbl_qty_"+id).text());
    $("#inp_edit_loc").val($("#tbl_loc_code_"+id).text());
    $("#inp_edit_zone").val($("#tbl_zone_code_"+id).text());
    $("#inp_edit_area").val($("#tbl_area_code_"+id).text());
    $("#inp_edit_rack").val($("#tbl_rack_code_"+id).text());
    $("#inp_edit_bin").val($("#tbl_bin_code_"+id).text());
    $("#inp_edit_whsip_no").val($("#tbl_src_no_"+id).text());
    $("#inp_edit_src_line_no").val($("#tbl_src_line_no_"+id).text());

    var min = parseInt($("#tbl_qty_"+id).text())*-1;
    $("#modal_qty").attr({
       "max" : -1,        // substitute your own
       "min" : min          // values (or variables) here
    });
    $("#modal_qty").val("-1");

    $("#modal_qty_total").val($("#tbl_qty_"+id).text());

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
                        var pick_doc_no = $("#inp_doc_no").val();
                        var src_line_no = $("#inp_edit_src_line_no").val();
                        var type = "1";
                        message = "Picker Request - "+message;

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

          }
      })
})

</script>
