<style>
.modal {
  padding: 0 !important; // override inline padding-right added from js
}
.modal .modal-dialog {
  width: 100%;
  max-width: none;
  height: 100%;
  margin: 0;
}
.modal .modal-content {
  height: 100%;
  border: 0;
  border-radius: 0;
}
.modal .modal-body {
  overflow-y: auto;
}

</style>


<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Picking List
</div>

<div class="modal" id="myModalAdd">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Whship can Pick</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_add" style="font-size:12px;"></div>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:30px;">
  <button class="btn btn-outline-primary" id="btn_refresh"><i class="bi-arrow-clockwise"></i></button>
  <button class="btn btn-primary" id="btn_add" onclick=f_show_add()><i class="bi-plus-lg"></i></button>
</div>

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="picking_data"></div>
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
    $('#picking_data').hide();
    $('#progress').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/picking/get_picking",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
            $('#progress').hide();
            $('#picking_data').fadeIn("5000");
            $("#picking_data").html(respons);
        }
    });
}
//---

function f_show_add(){

  $('#modal_detail_add').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail_add').load(
      "<?php echo base_url();?>index.php/wms/outbound/whship/get_warehouse",
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalAdd').modal();
}
//---

</script>
