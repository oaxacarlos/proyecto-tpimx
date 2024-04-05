
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
          <input type="text" class="form-control" id="scan_item_code" onkeypress="f_check_scan_item_code(event)" style="font-size:50px;" disabled>
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
  <button class="btn btn-danger" onclick=f_close2('<?php echo $outbound_h; ?>')>Close</button>
</div>

<div class="container-fluid">
  <table id="DataTable" class="table table-bordered table-striped table-sm">
    <thead>
      <tr>
        <th>No</th>
        <th>No 2</th>
        <th>Item Code</th>
        <th>Qty</th>
        <th>Master Barcode Pick</th>
        <th>S/N</th>
        <th>Loc Pick</th>
        <th>Zone Pick</th>
        <th>Area Pick</th>
        <th>Rack Pick</th>
        <th>Bin Pick</th>
        <th>Master Barcode Scan</th>
        <th>S/N Scan</th>
        <th>Loc Scan</th>
        <th>Zone Scan</th>
        <th>Area Scan</th>
        <th>Rack Scan</th>
        <th>Bin Scan</th>
        <th style='display:none;'>Src No</th>
        <th style='display:none;'>Line no</th>
        <th style='display:none;'>Src Line no</th>
      </tr>
    </thead>
    <tbody>
        <?php
          $i=0;
          $j=1;
          $item_code_temp = "";
          foreach($var_serial_number as $row){
              if($item_code_temp!=$row['item_code']){
                  $j=1;
                  $item_code_temp = $row['item_code'];
              }

              echo "<tr>";
                echo "<td>".($i+1)."</td>";
                echo "<td>".$j."</td>";
                echo "<td id='check_item_code_".$i."'>".$row['item_code']."</td>";
                echo "<td id='check_qty_d2_".$i."'>".$row['qty_d2']."</td>";
                if($row["master_barcode"] == 0 || is_null($row["master_barcode"]) || $row["master_barcode"]=="")
                  echo "<td id='check_sn2_pick_".$i."'></td>";
                else echo "<td id='check_sn2_pick_".$i."'>".$row['sn2_pick']."</td>";

                echo "<td id='check_serial_number_pick_".$i."'>".$row['serial_number_pick']."</td>";
                echo "<td id='check_location_code_pick_".$i."'>".$row['location_code_pick']."</td>";
                echo "<td id='check_zone_code_pick_".$i."'>".$row['zone_code_pick']."</td>";
                echo "<td id='check_area_code_pick_".$i."'>".$row['area_code_pick']."</td>";
                echo "<td id='check_rack_code_pick_".$i."'>".$row['rack_code_pick']."</td>";
                echo "<td id='check_bin_code_pick_".$i."'>".$row['bin_code_pick']."</td>";

              if($data_outbound_h["skip_scan"] == 0){
                echo "<td id='check_sn2_scan_".$i."'></td>";
                echo "<td id='check_serial_number_scan_".$i."'></td>";
                echo "<td id='check_location_code_scan_".$i."'></td>";
                echo "<td id='check_zone_code_scan_".$i."'></td>";
                echo "<td id='check_area_code_scan_".$i."'></td>";
                echo "<td id='check_rack_code_scan_".$i."'></td>";
                echo "<td id='check_bin_code_scan_".$i."'></td>";
              }
              else if($data_outbound_h["skip_scan"] == 1){
                echo "<td id='check_sn2_scan_".$i."'>".$row['sn2_pick']."</td>";
                echo "<td id='check_serial_number_scan_".$i."'>".$row['serial_number_pick']."</td>";
                echo "<td id='check_location_code_scan_".$i."'>".$row['location_code_pick']."</td>";
                echo "<td id='check_zone_code_scan_".$i."'>".$row['zone_code_pick']."</td>";
                echo "<td id='check_area_code_scan_".$i."'>".$row['area_code_pick']."</td>";
                echo "<td id='check_rack_code_scan_".$i."'>".$row['rack_code_pick']."</td>";
                echo "<td id='check_bin_code_scan_".$i."'>".$row['bin_code_pick']."</td>";
              }

              echo "<td id='check_pick_doc_".$i."' style='display:none;'>".$row['pick_doc']."</td>";
              echo "<td id='check_line_no_".$i."' style='display:none;'>".$row['line_no']."</td>";
              echo "<td id='check_src_line_no_".$i."' style='display:none;'>".$row['src_line_no']."</td>";

              echo "</tr>";
              $i++;
              $j++;
          }
        ?>
    </tbody>
  </table>
</div>

<?php echo loading_body_full() ?>


<input type="hidden" value="<?php echo $i; ?>" id="total_row">
<input type="hidden" value="<?php echo $outbound_h; ?>" id="doc_no">

<script>

var glb_plant = "<?php echo $data_outbound_h["doc_location_code"]; ?>";
var glb_match_sn = "WH2";

$("#btn_start_scan").click(function(){
    $('#myModalScan').modal();
    clear_field()
    $("#scan_sn").focus();
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
          url  : "<?php echo base_url();?>index.php/wms/outbound/checking/check_data_invt_with_sn2",
          type : "post",
          dataType  : 'json',
          data : {sn:sn, item:item},
          success: function(data){
              if(data.item_code == "" ||  data[0].item_code == ""){
                show_error("No Data from your input");
                clear_field();
                $("#scan_sn").focus();
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
    var data_index_same = [];
    var check_sn = 0;

    var data_sn_temp = []; // 2023-09-01
    var same_and_scan = 0; // 2023-09-01

    for(i=0;i<total_row;i++){
      for(j=0;j<data.length;j++){
          if(data[j].serial_number == $('#check_serial_number_pick_'+i).text()
              && data[j].item_code == $('#check_item_code_'+i).text()
              && data[j].sn2 == $('#check_sn2_pick_'+i).text()){
                check_sn = 1;
                data_index_same.push({
                  index_array : j,
                  index_table : i
                });

                // 2023-09-01
                if($('#check_serial_number_scan_'+i).text() !="" ){
                    same_and_scan = 1;
                    data_sn_temp.push({
                        serial_number : data[j].serial_number,
                        item_code : data[j].item_code,
                        location_code : data[j].location_code,
                        zone_code : data[j].zone_code,
                        area_code : data[j].area_code,
                        rack_code : data[j].rack_code,
                        bin_code  : data[j].bin_code,
                        sn2 : data[j].sn2,
                    });
                }
                //--
          }
      }
    }
    //----

    // if SN and SN2 not same.. check only SN if same or not
    if(check_sn == 0){
        var check_snn = 0;
        for(i=0;i<total_row;i++){
          for(j=0;j<data.length;j++){
              if(data[j].serial_number == $('#check_serial_number_pick_'+i).text()
                  && data[j].item_code == $('#check_item_code_'+i).text()){
                    check_snn = 1;
                    data_index_same.push({
                      index_array : j,
                      index_table : i
                    });

                    // 2023-09-01
                    if($('#check_serial_number_scan_'+i).text() !="" ){
                        same_and_scan = 1;
                        data_sn_temp.push({
                            serial_number : data[j].serial_number,
                            item_code : data[j].item_code,
                            location_code : data[j].location_code,
                            zone_code : data[j].zone_code,
                            area_code : data[j].area_code,
                            rack_code : data[j].rack_code,
                            bin_code  : data[j].bin_code,
                            sn2 : data[j].sn2,
                        });
                    }
                    //--
              }
          }
        }
    }
    //----

    if(check_sn == 1 && glb_plant.includes(glb_match_sn)){ // if the same SN and SN2 assign it directly
        assign_same_sn(data,data_index_same,data_sn_temp);
        check_if_scan_filled();
    }
    else if(check_snn == 1 && glb_plant.includes(glb_match_sn)){ // if the same SN and SN2 assign it directly
        assign_same_sn(data,data_index_same,data_sn_temp);
        check_if_scan_filled();
    }
    else if(check_if_already_scanned(data)){ // check if already scanned
        show_error("Ha escaneado este número de serie. No se pudo duplicar el número de serie");
    }
    else{
        var check_new = 0;
        var item_code_exist = 0;

        for(j=0;j<data.length;j++){

            for(i=0;i<total_row;i++){
                if($('#check_item_code_'+i).text() == data[j].item_code){ item_code_exist = 1; }
            }

            for(i=0;i<total_row;i++){
                if( data[j].location_code == $('#check_location_code_pick_'+i).text() &&
                    data[j].zone_code == $('#check_zone_code_pick_'+i).text() &&
                    data[j].area_code == $('#check_area_code_pick_'+i).text() &&
                    data[j].rack_code == $('#check_rack_code_pick_'+i).text() &&
                    data[j].bin_code == $('#check_bin_code_pick_'+i).text() &&
                    $('#check_serial_number_scan_'+i).text() == "" &&
                    $('#check_sn2_scan_'+i).text() == "" &&
                    $('#check_item_code_'+i).text() == data[j].item_code
                ){
                    assign(data[j].serial_number,data[j].location_code, data[j].zone_code, data[j].area_code, data[j].rack_code, data[j].bin_code, i, data[j].sn2);
                    check_new = 1;
                    check_if_scan_filled();
                    break;
                }
            }
        }

        if(check_new == 0){
            if(item_code_exist == 1){
                var temp_bin = data[0].location_code+"-"+data[0].zone_code+"-"+data[0].area_code+"-"+data[0].rack_code+"-"+data[0].bin_code;
                show_error(data[0].item_code + " | "+ data[0].serial_number +" | "+temp_bin+", El Ubicacion que elegiste es incorrecta");
            }
            else show_error("El número de serie no está en su lista de selección");
        }
    }

    clear_field();
    $("#scan_sn").focus();
}
//---

function assign(serial_number, location, zone, area, rack, bin, i, sn2){
    $('#check_serial_number_scan_'+i).text(serial_number);
    $('#check_location_code_scan_'+i).text(location);
    $('#check_zone_code_scan_'+i).text(zone);
    $('#check_area_code_scan_'+i).text(area);
    $('#check_rack_code_scan_'+i).text(rack);
    $('#check_bin_code_scan_'+i).text(bin);
    $('#check_sn2_scan_'+i).text(sn2);
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

  // check if the status not open
  if(check_if_status_not_open($("#doc_no").val()) == false){
      show_error("Este Pedido ha sido realizado");
      return false;
  }

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

                      line_no=[]; src_line_no=[]; pick_doc=[];

                      sn2_pick = []; sn2_scan = [];

                      counter = 0;
                      for(i=0;i<total_row;i++){
                          item_code[counter] = $('#check_item_code_'+i).text();
                          sn_pick[counter] = $('#check_serial_number_pick_'+i).text();
                          loc_pick[counter] = $('#check_location_code_pick_'+i).text();
                          zone_pick[counter] = $('#check_zone_code_pick_'+i).text();
                          area_pick[counter] = $('#check_area_code_pick_'+i).text();
                          rack_pick[counter] = $('#check_rack_code_pick_'+i).text();
                          bin_pick[counter] = $('#check_bin_code_pick_'+i).text();
                          sn2_pick[counter] = $('#check_sn2_pick_'+i).text();

                          sn_scan[counter] = $('#check_serial_number_scan_'+i).text();
                          loc_scan[counter] = $('#check_location_code_scan_'+i).text();
                          zone_scan[counter] = $('#check_zone_code_scan_'+i).text();
                          area_scan[counter] = $('#check_area_code_scan_'+i).text();
                          rack_scan[counter] = $('#check_rack_code_scan_'+i).text();
                          bin_scan[counter] = $('#check_bin_code_scan_'+i).text();
                          line_no[counter] = $('#check_line_no_'+i).text();
                          src_line_no[counter] = $('#check_src_line_no_'+i).text();
                          pick_doc[counter] = $('#check_pick_doc_'+i).text();
                          sn2_scan[counter] = $('#check_sn2_scan_'+i).text();

                          counter++;
                      }
                      //---

                      $.ajax({
                          url  : "<?php echo base_url();?>index.php/wms/outbound/checking/proceedpack2",
                          type : "post",
                          dataType  : 'html',
                          data : {item_code:JSON.stringify(item_code), sn_pick:JSON.stringify(sn_pick), loc_pick:JSON.stringify(loc_pick), zone_pick:JSON.stringify(zone_pick), area_pick:JSON.stringify(area_pick), rack_pick:JSON.stringify(rack_pick), bin_pick:JSON.stringify(bin_pick), sn_scan:JSON.stringify(sn_scan), loc_scan:JSON.stringify(loc_scan), zone_scan:JSON.stringify(zone_scan), area_scan:JSON.stringify(area_scan),
                          rack_scan:JSON.stringify(rack_scan), bin_scan:JSON.stringify(bin_scan),line_no:JSON.stringify(line_no),
                          doc_no:doc_no, src_line_no:JSON.stringify(src_line_no),pick_doc:JSON.stringify(pick_doc),
                          message:message, counter:counter, sn2_pick:JSON.stringify(sn2_pick), sn2_scan:JSON.stringify(sn2_scan)},
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
//checking_this_doc_not_locked();

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
    $('#scan_sn').focus();
}
//---

function check_if_already_scanned(data){
    var total_row = parseInt($("#total_row").val());
    // check if serial number is same
    var check_sn = 0;
    for(i=0;i<total_row;i++){
        if($('#check_serial_number_scan_'+i).text()!=""){
          for(j=0;j<data.length;j++){
              if(data[j].serial_number == $('#check_serial_number_scan_'+i).text() && data[j].sn2 == $('#check_sn2_scan_'+i).text()){
                check_sn = 1;
                break;
              }
          }
        }
    }
    //----

    if(check_sn == 1) return 1;
    else return 0;
}
//---

function f_close2(id){

  swal({
    title: "Are you sure ?",
    html: "Quit this QC",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
    if(result.value){
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
    })
}
//----

function assign_same_sn(data,data_index_same, data_sn_temp){
    for(i=0;i<data_index_same.length;i++){
        assign(data[data_index_same[i].index_array].serial_number, data[data_index_same[i].index_array].location_code, data[data_index_same[i].index_array].zone_code, data[data_index_same[i].index_array].area_code, data[data_index_same[i].index_array].rack_code, data[data_index_same[i].index_array].bin_code, data_index_same[i].index_table, data[data_index_same[i].index_array].sn2)
    }

    // 2023-09-01
    console.log(data_sn_temp);
    if(data_sn_temp.length > 0){

      var check_new = 0;
      var item_code_exist = 0;

      for(j=0;j<data_sn_temp.length;j++){

          for(i=0;i<total_row;i++){
              if($('#check_item_code_'+i).text() == data_sn_temp[j].item_code){ item_code_exist = 1; }
          }

          for(i=0;i<total_row;i++){
              if( data_sn_temp[j].location_code == $('#check_location_code_pick_'+i).text() &&
                  data_sn_temp[j].zone_code == $('#check_zone_code_pick_'+i).text() &&
                  data_sn_temp[j].area_code == $('#check_area_code_pick_'+i).text() &&
                  data_sn_temp[j].rack_code == $('#check_rack_code_pick_'+i).text() &&
                  data_sn_temp[j].bin_code == $('#check_bin_code_pick_'+i).text() &&
                  $('#check_serial_number_scan_'+i).text() == "" &&
                  $('#check_sn2_scan_'+i).text() == "" &&
                  $('#check_item_code_'+i).text() == data[j].item_code
              ){
                  assign(data_sn_temp[j].serial_number,data_sn_temp[j].location_code, data_sn_temp[j].zone_code, data_sn_temp[j].area_code, data_sn_temp[j].rack_code, data_sn_temp[j].bin_code, i, data_sn_temp[j].sn2);
                  check_new = 1;
                  check_if_scan_filled();
                  break;
              }
          }
      }

      if(check_new == 0){
          if(item_code_exist == 1){
              var temp_bin = data_sn_temp[0].location_code+"-"+data_sn_temp[0].zone_code+"-"+data_sn_temp[0].area_code+"-"+data_sn_temp[0].rack_code+"-"+data_sn_temp[0].bin_code;
              show_error(data_sn_temp[0].item_code + " | "+ data_sn_temp[0].serial_number +" | "+temp_bin+", El Ubicacion que elegiste es incorrecta");
          }
          else show_error("El número de serie no está en su lista de selección");
      }
    }
    //--
}
//---

function check_if_status_not_open(id){
    status = "1";
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/outbound/whship/check_status",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {id:id,status:status},
        success: function(data){
            result = $.parseJSON(data);
        }
    })

    return result;
}

</script>
