<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Transfer Bin New
</div>

<div class="modal" id="myModalBinSrc">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bin Source = <span id="modal_bin_title"></span></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_bin_src" ></div>
    </div>
  </div>
</div>

<div class="container-fluid">
    <div class="col-2"> Location
      <?php
        $user_plant = get_plant_code_user();
        $new_user_plant = explode(",",$user_plant);

        echo "<select id='inp_location' class='form-control'>";
          $first = 1;
          foreach($var_location as $row){
              for($i=0; $i<count($new_user_plant); $i++){
                  $temp = trim($new_user_plant[$i],"\'");
                  if($row["code"] == $temp){
                      echo "<option value='".$row["code"]."'>".$row["name"]."</option>";
                      if($first == 1){
                        unset($var_bin2);
                        foreach($var_bin as $row_bin){
                            if($temp == $row_bin["location_code"]){
                                $var_bin2[] = array(
                                    "code" => $row_bin["code"],
                                    "name" => $row_bin["name"],
                                    "type" => $row_bin["type"],
                                    "location_code" => $row_bin["location_code"],
                                    "zone_code" => $row_bin["zone_code"],
                                    "area_code" => $row_bin["area_code"],
                                    "rack_code" => $row_bin["rack_code"],
                                    "active" => $row_bin["active"],
                                    "description" => $row_bin["description"],
                                );
                            }
                        }
                        $first++;
                      }
                  }
              }
          }
        echo "</select>";

      ?>
    </div>

</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="container-fluid">
    Source
    <input type="text" id="inp_bin_src" class="form-control" placeholder="type your bin source in here" onkeypress='return getbinsrc(event)'>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <table class="table table-bordered table-sm" id="tbl_result">
    <thead>
      <tr>
        <th colspan='11' class="text-center table-secondary">From</th>
        <th class="text-center table-success" style='width:100px;'>To</th>
        <th style='width:50px;'></th>
      </tr>
      <tr>
        <th class="table-secondary">Loc</th>
        <th class="table-secondary">Zone</th>
        <th class="table-secondary">Area</th>
        <th class="table-secondary">Rack</th>
        <th class="table-secondary">Bin</th>
        <th class="table-secondary">Item Code</th>
        <th class="table-secondary">Desc</th>
        <th class="table-secondary">Master<br>Barcode</th>
        <th class="table-secondary">SN</th>
        <th class="table-secondary">Qty</th>
        <th class="table-secondary">Uom</th>
        <th class="table-success" style="width:250px;">New Rack <button class="btn btn-primary btn-sm" style="margin-left:10px;" onclick=copy_all()>Copy All</button></th>
      </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
      <tr>
        <td colspan="9" style="text-align:right; font-weight:bold;">TOTAL</td>
        <td id="total_qty" style="font-weight:bold;"></td>
      </tr>
    </tfoot>
  </table>
</div>

<div class="container-fluid" style="margin-bottom:100px;">
  <div class="row">
    <div class="col-7">
    </div>
    <div class="col-3">
      <select name='user_list' id='doc_user' class="form-control">
        <option value='-'>-</option>
          <?php
            foreach($user_list as $row){
              echo "<option value='".$row['user_id']."'>".$row['name']."</option>";
            }
          ?>
      </select>
    </div>
    <div class="col-2">
      <button class="btn btn-primary" id="btn_save">SAVE</button>
    </div>
  </div>
</div>

<?php echo loading_body_full(); ?>

<?php

unset($autocomplete);
unset($autocomplete_all);
$i=0;

foreach($var_bin2 as $row){
    $value2 = $row['location_code']."-".$row['zone_code']."-".$row['area_code']."-".$row['rack_code']."-".$row['code'];
    $autocomplete[$i] = $value2;
    $i++;
}

$i=0;
foreach($var_bin as $row){
    $value2 = $row['location_code']."-".$row['zone_code']."-".$row['area_code']."-".$row['rack_code']."-".$row['code'];
    $autocomplete_all[$i] = $value2;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);
$js_array_autocomplete_all = json_encode($autocomplete_all);

?>

<script>
var autocomplete = <?php echo $js_array_autocomplete; ?>;
var autocompleteall = <?php echo $js_array_autocomplete_all; ?>;
var glb_idx=0;

$( function() {
  $( "#inp_bin_dest").autocomplete({ source: autocomplete});
  $( "#inp_bin_src").autocomplete({ source: autocomplete});

})
//---

function check_bin(value){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/check_bin",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {id:value},
        success: function(data){
            result = $.parseJSON(data);
        }
    })

    return result;
}
//---

function getbinsrc(event){
  if(event.keyCode == 13){
      if($("#inp_bin_src").val() == "") return false;

      is_bin_exist = check_bin($("#inp_bin_src").val()); // check bin if available
      if(is_bin_exist=="0"){
          show_error("Bin you inputted not on the system");
          $("#inp_bin_src").val("");
          $("#inp_bin_src").focus();
          return false;
      }

      // if bin exist
      show_modal_src();
  }
}
//---

function show_modal_src(){
    var id = $("#inp_bin_src").val();
    var inp_location = $("#inp_location").val();

    data = {'id':id, 'inp_location':inp_location}
    $('#modal_bin_src').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_bin_src').load(
        "<?php echo base_url();?>index.php/wms/inbound/transferbin/getbinsrc",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#modal_bin_title').text(id);
    $('#myModalBinSrc').modal();
}
//---

function copy_all(){
    // get the first data
    check = 0;
    first_data = 0;
    for(i=0;i<glb_idx;i++){
        if(check_if_id_exist($("#tbl_result_inp_rack_"+i))){
            check = 1;
            first_data = i;
            break;
        }
    }

    if(check == 1){
        var loc = $("#tbl_result_inp_rack_"+i).val();
        for(i=first_data+1;i<glb_idx;i++){
            $("#tbl_result_inp_rack_"+i).val(loc);
        }
    }
}
//---

function check_if_table_result_not_blank(){
    var tb = $('#tbl_result tbody');
    var row = tb.find("tr").length;
    if(row > 0) return 1; else return 0;
}
//---

function check_if_all_fields_is_filled(){
    for(i=0;i<glb_idx;i++){
        if(check_if_id_exist($("#tbl_result_inp_rack_"+i))){
            if($("#tbl_result_inp_rack_"+i).val()=="") return 0;
        }
    }
    return 1;
}
//---

function check_if_all_the_rack_is_ok(){
    var from_loc = [];
    var from_zone = [];
    var from_area = [];
    var from_rack = [];
    var from_bin = [];

    var to_loc = [];
    var to_zone = [];
    var to_area = [];
    var to_rack = [];
    var to_bin = [];

    var ii = 0;
    var iii = 0;

    for(i=0;i<glb_idx;i++){
      if(check_if_id_exist("#tbl_result_loc_"+i) ) {
          if(from_loc.length == 0){
              from_loc[ii] = $("#tbl_result_loc_"+i).text();
              from_zone[ii] = $("#tbl_result_zone_"+i).text();
              from_area[ii] = $("#tbl_result_area_"+i).text();
              from_rack[ii] = $("#tbl_result_rack_"+i).text();
              from_bin[ii] = $("#tbl_result_bin_"+i).text();

              to = $("#tbl_result_inp_rack_"+i).val();

              to = to.split("-");

              to_loc[iii] = to[0];
              to_zone[iii] = to[1];
              to_area[iii] = to[2];
              to_rack[iii] = to[3];
              to_bin[iii] = to[4];
              ii++;
              iii++;
          }
          else{
              // from
              check = 1;
              for(j=0;j<ii;j++){
                  if($("#tbl_result_loc_"+i).text() == from_loc[j]
                    && $("#tbl_result_zone_"+i).text() == from_zone[j]
                    && $("#tbl_result_area_"+i).text() == from_area[j]
                    && $("#tbl_result_rack_"+i).text() == from_rack[j]
                    && $("#tbl_result_bin_"+i).text() == from_bin[j]
                  ){}
                  else{
                     check = 0;
                     break;
                  }
              }

              if(check == 0){
                  from_loc[ii] = $("#tbl_result_loc_"+i).text();
                  from_zone[ii] = $("#tbl_result_zone_"+i).text();
                  from_area[ii] = $("#tbl_result_area_"+i).text();
                  from_rack[ii] = $("#tbl_result_rack_"+i).text();
                  from_bin[ii] = $("#tbl_result_bin_"+i).text();
                  ii++;
              }
              //---

              // to

              to = $("#tbl_result_inp_rack_"+i).val();
              to = to.split("-");

              check = 1;
              for(j=0;j<iii;j++){
                  if(to[0] == to_loc[j]
                    && to[1] == to_zone[j]
                    && to[2] == to_area[j]
                    && to[3] == to_rack[j]
                    && to[4] == to_bin[j]
                  ){

                  }
                  else{
                     check = 0;
                     break;
                  }
              }

              if(check == 0){
                  to_loc[iii] = to[0];
                  to_zone[iii] = to[1];
                  to_area[iii] = to[2];
                  to_rack[iii] = to[3];
                  to_bin[iii] = to[4];
                  iii++;
              }
              //---
          }
      }
    }

    // send to ajax
    var total_rack_from = ii;
    var total_rack_to = iii;

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/check_rack_from_to",
        type : "post",
        dataType  : 'html',
        async: false,
        data : {total_rack_from:total_rack_from, total_rack_to:total_rack_to,
          from_loc:JSON.stringify(from_loc), from_zone:JSON.stringify(from_zone), from_area:JSON.stringify(from_area), from_rack:JSON.stringify(from_rack), from_bin:JSON.stringify(from_bin),
          to_loc:JSON.stringify(to_loc), to_zone:JSON.stringify(to_zone), to_area:JSON.stringify(to_area), to_rack:JSON.stringify(to_rack), to_bin:JSON.stringify(to_bin)},
        success: function(data){
            responsedata = $.parseJSON(data);

            all_true = 0;
            message = "";

            if(responsedata.status_check_from == 1 && responsedata.status_check_to == 1){
                all_true = 1;
            }
            else if(responsedata.status_check_from == 0){
                message = "The Location = "+responsedata.error_loc_from+" no Tiene en sistema";
            }
            else if(responsedata.status_check_to == 0){
                message = "The Location = "+responsedata.error_loc_to+" no Tiene en sistema";
            }
        }
    })
    //--

    if(all_true == 1) return true;
    else{
        return message;
    }
}

//---
$("#btn_save").click(function(){
    if(!check_if_table_result_not_blank()){
        show_error("The Data still blank");
        return false;
    }

    if(!check_if_all_fields_is_filled()){
        show_error("There is Rack you have not filled");
        return false;
    }

    var check2 = check_if_all_the_rack_is_ok();
    if(check2 != '' && check2 == false){
        show_error(check2);
        return false;
    }

    /*line_check = check_if_qty_not_greater_then_system_have();
    if(line_check!='' || parseInt(line_check)>=0){
        msg_temp = "tiene un error en el artículo "+$("#tbl_result_item_"+line_check).text()+" porque la cantidad es mayor que el sistema";
        show_error(msg_temp);
        return false;
    }*/



    if($("#doc_user").val()=="-"){
        show_error("You have to choose the User");
        return false;
    }

    // if everything is ok
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
          $("#loading_text").text("Creating Transfer Document, Please wait...");
          $('#loading_body').show();

            var message = result.value;

            swal({
              title: "Are you sure ?",
              html: "Proceed this Transfer Bin",
              type: "question",
              showCancelButton: true,
              confirmButtonText: "Yes",
              showLoaderOnConfirm: true,
              closeOnConfirm: false
            }).then(function (result) {
                if(result.value){

                    // get all data
                    var bin_dest = $("#inp_bin_dest2").val();
                    var doc_user = $("#doc_user").val();
                    var whs = $("#inp_location").val();
                    var loc=[]; var zone=[]; var area=[]; var rack=[]; var bin=[];
                    var item=[]; var desc=[]; var uom=[]; var qty_max=[];
                    //var qty_inp=[];
                    var rack_inp=[]; var sn=[]; var sn2=[];
                    var ii = 0;

                    for(i=0;i<glb_idx;i++){
                        if(check_if_id_exist($("#tbl_result_inp_rack_"+i))){
                            loc[ii] = $("#tbl_result_loc_"+i).text();
                            zone[ii] = $("#tbl_result_zone_"+i).text();
                            area[ii] = $("#tbl_result_area_"+i).text();
                            rack[ii] = $("#tbl_result_rack_"+i).text();
                            bin[ii] = $("#tbl_result_bin_"+i).text();
                            item[ii] = $("#tbl_result_item_"+i).text();
                            desc[ii] = $("#tbl_result_desc_"+i).text();
                            uom[ii] = $("#tbl_result_uom_"+i).text();
                            qty_max[ii] = $("#tbl_result_qty_"+i).text();
                            //qty_inp[ii] = $("#tbl_result_inp_qty_"+i).val();
                            rack_inp[ii] = $("#tbl_result_inp_rack_"+i).val();
                            sn[ii] = $("#tbl_result_sn_"+i).text();
                            sn2[ii] = $("#tbl_result_sn2_"+i).text();
                            ii++;
                        }
                    }
                    //---

                    if(check_is_banda()){
                      // Banda
                      $.ajax({
                          url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/create_new3",
                          type : "post",
                          dataType  : 'html',
                          data : {loc:JSON.stringify(loc), zone:JSON.stringify(zone), area:JSON.stringify(area), rack:JSON.stringify(rack), bin:JSON.stringify(bin), item:JSON.stringify(item) , desc:JSON.stringify(desc), uom:JSON.stringify(uom) , qty_max:JSON.stringify(qty_max),
                          rack_inp:JSON.stringify(rack_inp), bin_dest:bin_dest, message:message, doc_user:doc_user, sn:JSON.stringify(sn), sn2:JSON.stringify(sn2),whs:whs},
                          success: function(data){
                              var responsedata = $.parseJSON(data);

                              if(responsedata.status == 1){
                                    swal({
                                       title: responsedata.msg,
                                       type: "success", confirmButtonText: "OK",
                                    }).then(function(){
                                      setTimeout(function(){
                                        $('#loading_body').hide();
                                        window.location.href = "<?php echo base_url();?>index.php/wms/inbound/transferbin";
                                      },100)
                                    });
                              }
                              else if(responsedata.status == 0){
                                  Swal('Error!',responsedata.msg,'error');
                                  $('#loading_body').hide();
                              }
                          }
                      })
                      //---
                    }
                    else{
                        // filter
                        $.ajax({
                            url  : "<?php echo base_url();?>index.php/wms/inbound/transferbin/create_new2",
                            type : "post",
                            dataType  : 'html',
                            data : {loc:JSON.stringify(loc), zone:JSON.stringify(zone), area:JSON.stringify(area), rack:JSON.stringify(rack), bin:JSON.stringify(bin), item:JSON.stringify(item) , desc:JSON.stringify(desc), uom:JSON.stringify(uom) , qty_max:JSON.stringify(qty_max),
                            rack_inp:JSON.stringify(rack_inp), bin_dest:bin_dest, message:message, doc_user:doc_user, sn:JSON.stringify(sn), sn2:JSON.stringify(sn2),whs:whs},
                            success: function(data){
                                var responsedata = $.parseJSON(data);

                                if(responsedata.status == 1){
                                      swal({
                                         title: responsedata.msg,
                                         type: "success", confirmButtonText: "OK",
                                      }).then(function(){
                                        setTimeout(function(){
                                          $('#loading_body').hide();
                                          window.location.href = "<?php echo base_url();?>index.php/wms/inbound/transferbin";
                                        },100)
                                      });
                                }
                                else if(responsedata.status == 0){
                                    Swal('Error!',responsedata.msg,'error');
                                    $('#loading_body').hide();
                                }
                            }
                        })
                        //---
                    }
                }
            })
        }
      }
    })
})
//---

function f_delete_tbl_result(i){

  //2023-08-02
  total = parseInt($("#total_qty").text());
  qty = parseInt($("#tbl_result_qty_"+i).text());

  remaining_qty = total - qty;

  $("#total_qty").text(remaining_qty);
  //----

  $("#tbl_result_id_"+i).remove();

}
//--

$("#inp_location").change(function(){

    $("#loading_text").text("Loading data, Please wait...");
    $('#loading_body').show();

      var new_autocomplete = [];
      var counter = 0;

      var loc = $("#inp_location").val();

      for(i=0;i<autocompleteall.length;i++){
          wh = autocompleteall[i].split('-');
          if(loc == wh[0]){
              new_autocomplete[counter] = autocompleteall[i];
              counter++;
          }
      }

      $("#inp_bin_dest").autocomplete({ source: new_autocomplete});
      $("#inp_bin_src").autocomplete({ source: new_autocomplete});

      glb_idx=0;

      setTimeout(() => {
        $('#loading_body').hide();
        $("#tbl_result tbody").empty();
        $( "#inp_bin_src").val("");
      }, "2000")

})
//--

// 2023-10-26
function check_is_banda(){
  var tb = $('#tbl_result tbody');
  var row = tb.find("tr").length;
  if(row == 0) return 0;
  else{
    first = 1;
    tb.find("tr").each(function(index, element) {
        index = 0;
        $(element).find('td').each(function(index, element) {
            var colVal = $(element).text();
            if(index == 5) item = colVal;
            index++;
        })

        if(first == 1){
          check_item = item.substring(0,3);
          if(check_item == "TYP"){
            isbanda = 1;
          }
          else{
            isbanda = 0;
          }
        }

        first++;
    })
  }

  return isbanda;
}
//--

</script>
