<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      GoTo Transfer Bin
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

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="transferbin_data"></div>
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
    $('#transferbin_data').hide();
    $('#progress').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/inbound/transferbin/get_transferbin_goto_list",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
            $('#progress').hide();
            $('#transferbin_data').show();
            $("#transferbin_data").html(respons);
        }
    });
}
//---

function f_show_detail(id){
  var link = 'wms/inbound/transferbin/v_detail';
  data = {'id':id, 'link':link }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/inbound/transferbin/get_list_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---


</script>
