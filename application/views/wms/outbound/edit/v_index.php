<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Outbound Edit
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-3">
      Warehouse Shipment
      <input type="text" value="TPM-WSHIP-" id="inp_whship" class="form-control">
    </div>
    <div class="col-1">
      Process
      <button class="btn btn-primary" id="btn_process">PROCESS</button>
    </div>
  </div>
</div>

<div class="container-fluid">
    <div id="whship_data"></div>
</div>

<?php echo loading_body_full(); ?>

<script>

$("#btn_process").click(function(){

  $("#whship_data").html("Loading, Please wait...");

  var inp_whship = $("#inp_whship").val();

  // check the user warehouse
  if(check_wh_user(inp_whship)){
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/edit/get_whship",
        type      : 'post',
        dataType  : 'html',
        data      :  {inp_whship:inp_whship},
        success   :  function(data){
            $('#whship_data').fadeIn("5000");
            $("#whship_data").html(data);
        }
    });
  }
  //--


})
//---

function check_wh_user(inp_whship){

  var status = "";
  var message = "";

  $.ajax({
      url       : "<?php echo base_url();?>index.php/wms/outbound/edit/check_wh_user",
      type      : 'post',
      dataType  : 'json',
      async : false,
      data      :  {inp_whship:inp_whship},
      success   :  function(data){
          status = data.status;
          message = data.msg;
      }
  });

  if(status == 1){ return true; }
  else{
      show_error(message);
      return false;
  }

}

</script>
