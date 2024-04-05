<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      GoTo Put Away
</div>

<div class="modal" id="myModalDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail Item</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail"></div>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="putaway_data"></div>
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
    $('#putaway_data').hide();
    $('#progress').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/inbound/putaway/get_putaway_goto_list2",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
            $('#progress').hide();
            $('#putaway_data').show();
            $("#putaway_data").html(respons);
        }
    });
}
//---


</script>
