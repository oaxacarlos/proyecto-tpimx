<style>
.ui-autocomplete {
    position: absolute;
    z-index: 1000;
    top: 0;
    left: 0;
    cursor: default;
    background-color: #fff;
    padding:3px;
    border: 1px solid #ccc
}

.ui-autocomplete > li.ui-state-focus {
  background-color: #FF6C00;
}
</style>

<?php

unset($autocomplete);

$i=0;

foreach($var_bin as $row){
    $value2 = $row['location_code']."-".$row['zone_code']."-".$row['area_code']."-".$row['rack_code']."-".$row['code'];
    $autocomplete[$i] = $value2;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);

?>

<input type="text" id="inp_bin_dest" class="form-control" placeholder="type your destination" onkeypress='return getbindest(event)'>
<button class="btn btn-success" id="btn_transfer" style="margin-top:20px;">TRANSFER</button>

<script>
var autocomplete = <?php echo $js_array_autocomplete; ?>;

$( function() {
  $( "#inp_bin_dest").autocomplete({ source: autocomplete});

})
//---

function getbindest(event){
  if(event.keyCode == 13){
      if($("#inp_bin_dest").val() == "") return false;

      is_bin_exist = check_bin($("#inp_bin_dest").val()); // check bin if available
      if(is_bin_exist=="0"){
          show_error("Bin you inputted not on the system");
          $("#inp_bin_dest").val("");
          $("#inp_bin_dest").focus();
          return false;
      }
  }
}
//---

$("#btn_transfer").click(function(){

  var new_location = $("#inp_bin_dest").val();

  // check if the bin input correctly
  if(!check_destination_is_ok(new_location)){
      show_error("Location you inputted was wrong");
      return false;
  }
  else{
      swal({
        title: "Are you sure ?",
        html: "Transfer those Serial Number",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }).then(function (result) {
            if(result.value){
                $("#loading_text").text("Transfering Serial Numbers, Please wait...");
                $('#loading_body').show();

                var data_sn = [];
                var data_sn2 = [];
                var data_item = [];
                var data_item_name = [];
                var data_status = [];
                var data_status_name = [];
                var data_loc = [];
                var data_zone = [];
                var data_area = [];
                var data_rack = [];
                var data_bin = [];
                var data_uom=[];
                var counter = 0;

                for(i=0;i<glb_idx;i++){
                    if(check_if_id_exist($("#tbl_dtl_sn_"+i))){
                        data_sn[counter]  = $("#tbl_dtl_sn_"+i).text();
                        data_sn2[counter] = $("#tbl_dtl_sn2_"+i).text();
                        data_item[counter] = $("#tbl_dtl_item_"+i).text();
                        data_item_name[counter] = $("#tbl_dtl_itemname_"+i).text();
                        data_status[counter] = $("#tbl_dtl_status_"+i).text();
                        data_status_name[counter] = $("#tbl_dtl_status_name_"+i).text();
                        data_loc[counter] = $("#tbl_dtl_loc_"+i).text();
                        data_zone[counter] = $("#tbl_dtl_zone_"+i).text();
                        data_area[counter] = $("#tbl_dtl_area_"+i).text();
                        data_rack[counter] = $("#tbl_dtl_rack_"+i).text();
                        data_bin[counter] = $("#tbl_dtl_bin_"+i).text();
                        data_uom[counter] = "PZA";
                        counter++;
                    }
                }

                $.ajax({
                    url  : "<?php echo base_url();?>index.php/wms/inbound/transferbinsn/process_transfer",
                    type : "post",
                    dataType  : 'html',
                    data : {sn:JSON.stringify(data_sn), sn2:JSON.stringify(data_sn2), item:JSON.stringify(data_item), item_name:JSON.stringify(data_item_name), status:JSON.stringify(data_status), status_name:JSON.stringify(data_status_name), loc:JSON.stringify(data_loc), zone:JSON.stringify(data_zone), area:JSON.stringify(data_area), rack:JSON.stringify(data_rack), bin:JSON.stringify(data_bin), uom:JSON.stringify(data_uom), new_location:new_location},
                    success: function(response){
                        var responsedata = $.parseJSON(response);

                        if(responsedata.status == 1){
                              swal({
                                 title: responsedata.msg,
                                 type: "success", confirmButtonText: "OK",
                              }).then(function(){
                                setTimeout(function(){
                                  $('#loading_body').hide();
                                  window.location.href = "<?php echo base_url();?>index.php/wms/inbound/transferbinsn";
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


})
//--

function check_destination_is_ok(bin){
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/inbound/transferbinsn/check_bin",
      type : "post",
      dataType  : 'html',
      async : false,
      data : {bin:bin},
      success: function(response){
          check = response;
      }
  })

  if(check == 1) return true;
  else return false;
}

</script>
