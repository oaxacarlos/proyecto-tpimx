<style>
  tr{
    font-size: 12px;
  }

</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      GoTo Put Away Process
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
            <button class='btn btn-primary' id='btn_start_put'>START</button>
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
<input type='hidden' value='<?php echo count($var_putaway_goto_d2); ?>' id='inp_total_row'>

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
    foreach($var_putaway_goto_d2 as $row){
        echo "<div class='card' style='margin-bottom:20px;'>";
            echo "<div class='card-header text-right' style='font-size:12px; font-weight:bold;'>";
              echo "<span style='margin-right:10px;'><span id='tbl_item_code_".$i."'>".$row['item_code']."</span> | <span id='tbl_description_".$i."'>".$row['description']."</span></span>
                    <span><button class='btn btn-sm btn-primary text-right' id='tbl_btn_start_".$i."' onclick=f_put_show(".$i.")>PUT</button></span>
                    <span><button class='btn btn-sm btn-warning text-right' id='tbl_btn_confirm_".$i."' onclick=f_confirm_show(".$i.") style='display:none;'>CONFIRM</button></span>
                    <button class='btn btn-success btn-sm  text-right'  id='tbl_btn_finish_".$i."' style='display:none;'>FINISHED</button>";
            echo "</div>";

            echo "<div class='card-body'>";
              echo "<table class='table table-sm'>";
                echo "<tr><th>Qty</th><td><span id='tbl_qty_".$i."'>".$row['qty']."</span> <span id='tbl_uom_".$i."'>".$row['uom']."</span></td></tr>";
                echo "<tr><th>Loc</th><td id='tbl_loc_code_".$i."'>".$row['location_code']."</td></tr>";
                echo "<tr><th>Position</th>
                      <td>
                        <span id='tbl_zone_code_".$i."'>".$row['zone_code']."</span> -
                        <span id='tbl_area_code_".$i."'>".$row['area_code']."</span> -
                        <span id='tbl_rack_code_".$i."'>".$row['rack_code']."</span> -
                        <span id='tbl_bin_code_".$i."'>".$row['bin_code']."</span>
                      </td>
                      </tr>";

                //echo "<tr><th>Zone</th><td id='tbl_zone_code_".$i."'>".$row['zone_code']."</td></tr>";
                //echo "<tr><th>Area</th><td id='tbl_area_code_".$i."'>".$row['area_code']."</td></tr>";
                //echo "<tr><th>Rack</th><td id='tbl_rack_code_".$i."'>".$row['rack_code']."</td></tr>";
                //echo "<tr><th>Bin</th><td id='tbl_bin_code_".$i."'>".$row['bin_code']."</td></tr>";
                echo "<tr><th>Start</th><td id='tbl_start_time_".$i."'></td></tr>";
                echo "<tr><th>Finish</th><td id='tbl_finish_time_".$i."'></td></tr>";
                echo "<td id='tbl_line_no_".$i."' style='display:none;'>".$row['line_no']."</td>";
                echo "<td id='tbl_src_line_no_".$i."' style='display:none;'>".$row['src_line_no']."</td>";
                echo "<td id='tbl_src_no_".$i."' style='display:none;'>".$row['src_no']."</td>";
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

function f_put_show(id){
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

$('#btn_start_put').click(function(){
    var id = $('#inp_index').val();
    $('#tbl_start_time_'+id).text(getfulldatetimenow());
    $('#myModalDetail_put').modal('hide');
    $('#tbl_btn_start_'+id).hide();
    $('#tbl_btn_confirm_'+id).show();
})
//--

$('#btn_confirm_put').click(function(){
    var id = $('#inp_index').val();
    $('#tbl_finish_time_'+id).text(getfulldatetimenow());
    $('#myModalDetail_confirm').modal('hide');
    $('#tbl_btn_confirm_'+id).hide();
    $('#tbl_btn_finish_'+id).show();
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
            window.location.href = "<?php echo base_url();?>index.php/wms/inbound/putaway/goto";
        }
  })
})
//---

$("#btn_all_finished").click(function(){
    if(!check_all_finished()){
      show_error("You haven't finished the Put Away");
      return false;
    }

    swal({
      title: "Are you sure ?",
      html: "Finish this Put Away",
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

              var d2_line_no = [];
              var d2_src_line_no = [];
              var d2_src_no = [];
              var d2_item_code = [];
              var d2_start_time = [];
              var d2_finish_time = [];
              var h_doc_no = $("#inp_doc_no").val();
              var counter = 0;
              var start_all_datetime = $('#inp_start_datetime').val();
              var finish_all_datetime = getfulldatetimenow();

              for(i=1;i<=total_row;i++){
                d2_line_no[counter] = $('#tbl_line_no_'+i).text();
                d2_src_line_no[counter] = $('#tbl_src_line_no_'+i).text();
                d2_src_no[counter] = $('#tbl_src_no_'+i).text();
                d2_item_code[counter] = $('#tbl_item_code_'+i).text();
                d2_start_time[counter] = $('#tbl_start_time_'+i).text();
                d2_finish_time[counter] = $('#tbl_finish_time_'+i).text();
                counter++;
              }

              // ajax
              $.ajax({
                  url  : "<?php echo base_url();?>index.php/wms/inbound/putaway/goto_finish",
                  type : "post",
                  dataType  : 'html',
                  data : { d2_line_no:JSON.stringify(d2_line_no), d2_src_line_no:JSON.stringify(d2_src_line_no), d2_item_code:JSON.stringify(d2_item_code), d2_start_time:JSON.stringify(d2_start_time), d2_finish_time:JSON.stringify(d2_finish_time),h_doc_no:h_doc_no, start_all_datetime:start_all_datetime, finish_all_datetime:finish_all_datetime, d2_src_no:JSON.stringify(d2_src_no) },
                  success: function(data){
                    var responsedata = $.parseJSON(data);

                    if(responsedata.status == 1){
                          swal({
                             title: responsedata.msg,
                             type: "success", confirmButtonText: "OK",
                          }).then(function(){
                            setTimeout(function(){
                              $('#loading_body').hide();
                              window.location.href = "<?php echo base_url();?>index.php/wms/inbound/putaway/goto";
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

</script>
