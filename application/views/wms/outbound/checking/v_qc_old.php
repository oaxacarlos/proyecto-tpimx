
<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      QC
</div>

<div class="modal" id="myModalScan" tabindex="-1" role="dialog" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Scan</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_scan_detail">
        <div class="container-fluid" style="font-size:50px;">
          Item Code
          <input type="text" class="form-control" id="scan_item_code" onkeypress="f_check_scan_item_code(event)" style="font-size:50px;">
        </div>
        <div class="container-fluid" style="margin-top:10px; font-size:30px;">
          Serial Number
          <input type="text" class="form-control" id="scan_sn" onkeypress="f_check_scan_sn(event)" style="font-size:30px;">
        </div>
        <div class="container-fluid" style="margin-top:10px;">
          <button class="btn btn-danger" onclick=f_clear_fields()>CLEAR</button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  $disabled_finish = "";
  if($data_outbound_h["skip_scan"] == 0) $disabled_finish = "disabled";
?>

<div class="container-fluid" style="margin-bottom:20px;">
  <button class="btn btn-primary" id="btn_start_scan">START SCAN</button>
  <button class="btn btn-success" id="btn_qc_finish" <?php echo $disabled_finish; ?>>QC Finish</button>
  <button class="btn btn-danger" onclick=f_close('<?php echo $outbound_h; ?>')>Close</button>
</div>

<div class="container-fluid">
  <table id="DataTable" class="table table-bordered table-striped table-sm">
    <thead>
      <tr>
        <th>Item Code</th>
        <th>Qty</th>
        <th>S/N</th>
        <th>Loc Pick</th>
        <th>Zone Pick</th>
        <th>Area Pick</th>
        <th>Rack Pick</th>
        <th>Bin Pick</th>
        <th>S/N Scan</th>
        <th>Loc Scan</th>
        <th>Zone Scan</th>
        <th>Area Scan</th>
        <th>Rack Scan</th>
        <th>Bin Scan</th>
      </tr>
    </thead>
    <tbody>
        <?php
          $i=0;
          foreach($var_serial_number as $row){
              echo "<tr>";
                echo "<td id='check_item_code_".$i."'>".$row['item_code']."</td>";
                echo "<td id='check_qty_d2_".$i."'>".$row['qty_d2']."</td>";
                echo "<td id='check_serial_number_pick_".$i."'>".$row['serial_number_pick']."</td>";
                echo "<td id='check_location_code_pick_".$i."'>".$row['location_code_pick']."</td>";
                echo "<td id='check_zone_code_pick_".$i."'>".$row['zone_code_pick']."</td>";
                echo "<td id='check_area_code_pick_".$i."'>".$row['area_code_pick']."</td>";
                echo "<td id='check_rack_code_pick_".$i."'>".$row['rack_code_pick']."</td>";
                echo "<td id='check_bin_code_pick_".$i."'>".$row['bin_code_pick']."</td>";

              if($data_outbound_h["skip_scan"] == 0){
                echo "<td id='check_serial_number_scan_".$i."'></td>";
                echo "<td id='check_location_code_scan_".$i."'></td>";
                echo "<td id='check_zone_code_scan_".$i."'></td>";
                echo "<td id='check_area_code_scan_".$i."'></td>";
                echo "<td id='check_rack_code_scan_".$i."'></td>";
                echo "<td id='check_bin_code_scan_".$i."'></td>";
              }
              else if($data_outbound_h["skip_scan"] == 1){
                echo "<td id='check_serial_number_scan_".$i."'>".$row['serial_number_pick']."</td>";
                echo "<td id='check_location_code_scan_".$i."'>".$row['location_code_pick']."</td>";
                echo "<td id='check_zone_code_scan_".$i."'>".$row['zone_code_pick']."</td>";
                echo "<td id='check_area_code_scan_".$i."'>".$row['area_code_pick']."</td>";
                echo "<td id='check_rack_code_scan_".$i."'>".$row['rack_code_pick']."</td>";
                echo "<td id='check_bin_code_scan_".$i."'>".$row['bin_code_pick']."</td>";
              }



              echo "</tr>";
              $i++;
          }
        ?>
    </tbody>
  </table>
</div>

<?php echo loading_body_full() ?>


<input type="hidden" value="<?php echo $i; ?>" id="total_row">
<input type="hidden" value="<?php echo $outbound_h; ?>" id="doc_no">

<script>

$("#btn_start_scan").click(function(){
    $('#myModalScan').modal();
    clear_field()
    $("#scan_item_code").focus();
})
//--

function f_check_scan_item_code(event){
    if(event.keyCode == 13){
        $("#scan_sn").focus();
    }
}
//---

function f_check_scan_sn(event){
    if(event.keyCode == 13){
      var sn = $("#scan_sn").val();
      var item = $("#scan_item_code").val();

      $.ajax({
          url  : "<?php echo base_url();?>index.php/wms/outbound/checking/check_data_invt_with_sn",
          type : "post",
          dataType  : 'json',
          data : {sn:sn, item:item},
          success: function(data){
              if(data.item_code == ""){
                show_error("No Data from your input");
                clear_field();
                $("#scan_item_code").focus();
              }
              else{
                f_check_and_assign(data);
              }

          }
      })
    }
}
//---

function f_check_and_assign(data){
    var total_row = parseInt($("#total_row").val());

    // check if serial number is same
    var check_sn = 0;
    for(i=0;i<total_row;i++){
        if(data.serial_number == $('#check_serial_number_pick_'+i).text()){
            check_sn = 1;
            index = i;
            break;
        }
    }
    //----

    if(check_sn == 1){
        assign($('#check_serial_number_pick_'+index).text(), $('#check_location_code_pick_'+index).text(), $('#check_zone_code_pick_'+index).text(), $('#check_area_code_pick_'+index).text(), $('#check_rack_code_pick_'+index).text(), $('#check_bin_code_pick_'+index).text(), index);
        check_if_scan_filled();
    }
    else{
        var check_new = 0;
        for(i=0;i<total_row;i++){
            if( data.location_code == $('#check_location_code_pick_'+i).text() &&
                data.zone_code == $('#check_zone_code_pick_'+i).text() &&
                data.area_code == $('#check_area_code_pick_'+i).text() &&
                data.rack_code == $('#check_rack_code_pick_'+i).text() &&
                data.bin_code == $('#check_bin_code_pick_'+i).text() &&
                $('#check_serial_number_scan_'+i).text() == ""
            ){
                assign(data.serial_number,data.location_code, data.zone_code, data.area_code, data.rack_code, data.bin_code, i);
                check_new = 1;
                check_if_scan_filled();
                break;
            }
        }

        if(check_new == 0){
            show_error("The Serial Number not in your picklist");
        }
    }

    clear_field();
    $("#scan_item_code").focus();
}
//---

function assign(serial_number, location, zone, area, rack, bin, i){
    $('#check_serial_number_scan_'+i).text(serial_number);
    $('#check_location_code_scan_'+i).text(location);
    $('#check_zone_code_scan_'+i).text(zone);
    $('#check_area_code_scan_'+i).text(area);
    $('#check_rack_code_scan_'+i).text(rack);
    $('#check_bin_code_scan_'+i).text(bin);
}
//---

function clear_field(){
    $("#scan_item_code").val("");
    $("#scan_sn").val("");
}
//---

function check_if_scan_filled(){
    var total_row = parseInt($("#total_row").val());

    var check = 1;
    for(i=0;i<total_row;i++){
      if($('#check_serial_number_scan_'+i).text() == ""){
        check = 0;
        break;
      }
    }

    if(check == 1){ $("#btn_qc_finish").removeAttr("disabled"); }
    else if(check == 0){ $("#btn_qc_finish").attr("disabled", "disabled"); }
}
//--

$("#btn_qc_finish").click(function(){
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
              var message = result.value;

              swal({
                title: "Are you sure ?",
                html: "Proceed this QC and going to Packing",
                type: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
              }).then(function (result) {
                  if(result.value){
                      $("#loading_text").text("Processing Document, Please wait...");
                      $('#loading_body').show();

                      // get data
                      var total_row = parseInt($("#total_row").val());
                      var doc_no = $("#doc_no").val();
                      item_code = [];
                      loc_pick = []; zone_pick = []; area_pick = [];
                      rack_pick = []; bin_pick = []; sn_pick = [];

                      loc_scan = []; zone_scan = []; area_scan = [];
                      rack_scan = []; bin_scan = []; sn_scan = [];

                      counter = 0;
                      for(i=0;i<total_row;i++){
                          item_code[counter] = $('#check_item_code_'+i).text();
                          sn_pick[counter] = $('#check_serial_number_pick_'+i).text();
                          loc_pick[counter] = $('#check_location_code_pick_'+i).text();
                          zone_pick[counter] = $('#check_zone_code_pick_'+i).text();
                          area_pick[counter] = $('#check_area_code_pick_'+i).text();
                          rack_pick[counter] = $('#check_rack_code_pick_'+i).text();
                          bin_pick[counter] = $('#check_bin_code_pick_'+i).text();
                          sn_scan[counter] = $('#check_serial_number_scan_'+i).text();
                          loc_scan[counter] = $('#check_location_code_scan_'+i).text();
                          zone_scan[counter] = $('#check_zone_code_scan_'+i).text();
                          area_scan[counter] = $('#check_area_code_scan_'+i).text();
                          rack_scan[counter] = $('#check_rack_code_scan_'+i).text();
                          bin_scan[counter] = $('#check_bin_code_scan_'+i).text();

                          counter++;
                      }
                      //---

                      $.ajax({
                          url  : "<?php echo base_url();?>index.php/wms/outbound/checking/proceedpack",
                          type : "post",
                          dataType  : 'html',
                          data : {item_code:JSON.stringify(item_code), sn_pick:JSON.stringify(sn_pick), loc_pick:JSON.stringify(loc_pick), zone_pick:JSON.stringify(zone_pick), area_pick:JSON.stringify(area_pick), rack_pick:JSON.stringify(rack_pick), bin_pick:JSON.stringify(bin_pick), sn_scan:JSON.stringify(sn_scan), loc_scan:JSON.stringify(loc_scan), zone_scan:JSON.stringify(zone_scan), area_scan:JSON.stringify(area_scan), rack_scan:JSON.stringify(rack_scan), bin_scan:JSON.stringify(bin_scan),
                          doc_no:doc_no,
                          message:message, counter:counter},
                          success: function(data){
                              var responsedata = $.parseJSON(data);

                              if(responsedata.status == 1){
                                    swal({
                                       title: responsedata.msg,
                                       type: "success", confirmButtonText: "OK",
                                    }).then(function(){
                                      setTimeout(function(){
                                        $('#loading_body').hide();
                                        f_close(doc_no);
                                        //window.location.href = "<?php //echo base_url();?>index.php/wms/outbound/checking";
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

function f_close(id){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/outbound/whship/doc_unlocked",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {id:id},
        success: function(data){
            result = $.parseJSON(data);
        }
    })

    window.location.href = "<?php echo base_url();?>index.php/wms/outbound/checking";
}
//----

function checking_this_doc_not_locked(){

    var id = '<?php echo $outbound_h; ?>';
    if(check_doc_locked(id)==1){
        alert("This Document has been locked by another user");
        window.location.href = "<?php echo base_url();?>index.php/wms/outbound/checking";
    }
    else{
        // locked the document
        doc_locked(id);
    }
}
checking_this_doc_not_locked();

//---

function check_doc_locked(id){
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/outbound/whship/check_doc_locked",
      type : "post",
      dataType  : 'json',
      async: false,
      data : {id:id},
      success: function(data){
          result = $.parseJSON(data);
      }
  })

  return result;
}
//--

function doc_locked(id){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/outbound/whship/doc_locked",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {id:id},
        success: function(data){
            result = $.parseJSON(data);
        }
    })

    return result;
}
//---

function f_clear_fields(){
    $('#scan_item_code').val('');
    $('#scan_sn').val('');
    $('#scan_item_code').focus();
}
//---

</script>
