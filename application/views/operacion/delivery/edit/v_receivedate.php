<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Edit Received Date
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-3">
      Delivery No
      <input type="text" value="OPRC-DLV" id="inp_delv_no" class="form-control">
    </div>
    <div class="col-1">
      Process
      <button class="btn btn-primary" id="btn_process">PROCESS</button>
    </div>
  </div>
</div>

<div class="container-fluid">
    <div id="delv_data"></div>
</div>

<?php echo loading_body_full(); ?>

<script>

$("#btn_process").click(function(){

  $("#delv_data").html("Loading, Please wait...");

  var inp_delv_no = $("#inp_delv_no").val();

  // check the user warehouse
  $.ajax({
      url       : "<?php echo base_url();?>index.php/operacion/delivery/edit/receivedate_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {docno:inp_delv_no},
      success   :  function(data){
          $('#delv_data').fadeIn("5000");
          $("#delv_data").html(data);
      }
  });
  //--


})
//---

</script>
