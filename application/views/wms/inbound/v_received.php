<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Received
</div>


<div class="container-fluid" style="margin-top:30px;">
  <button class="btn btn-outline-primary" id="btn_refresh"><i class="bi-arrow-clockwise"></i></button>
</div>

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="received_data"></div>
</div>

<div class="modal" id="myModalDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail"></div>
    </div>
  </div>
</div>



<script>

// first load
f_refresh();
//--

$('#btn_refresh').click(function(){
    f_refresh();
});
//---

function f_refresh(){
    $('#received_data').hide();
    $('#progress').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/inbound/received/get_received_list",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
            $('#progress').hide();
            $('#received_data').fadeIn();
            $("#received_data").html(respons);
        }
    });
}
//---


</script>
