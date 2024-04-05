<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Outbound Cancel Doc
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-3">
      Warehouse Shipment
      <input type="text" value="" id="inp_whship" class="form-control">
    </div>
    <div class="col-1">
      Process
      <button class="btn btn-primary" id="btn_process">PROCESS</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
    <div id="whship_data"></div>
</div>

<?php echo loading_body_full(); ?>

<script>

$("#btn_process").click(function(){

  $("#whship_data").html("Loading, Please wait...");

  var inp_whship = $("#inp_whship").val();

  $.ajax({
      url       : "<?php echo base_url();?>index.php/wms/outbound/canceldoc/get_whship",
      type      : 'post',
      dataType  : 'html',
      data      :  {inp_whship:inp_whship},
      success   :  function(data){
          $('#whship_data').fadeIn("5000");
          $("#whship_data").html(data);
      }
  });
})

</script>
