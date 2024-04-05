<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Outbound Warehouse
</div>

<?php
  if(isset($locked)){
    if($locked == 1){
      echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        <strong>The Document has been locked, because processing by another user
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>";
    }
  }
?>

<div class="container-fluid" style="margin-top:30px;">
  <button class="btn btn-outline-primary" id="btn_refresh"><i class="bi-arrow-clockwise"></i></button>
</div>

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="warehouse_data"></div>
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

<?php echo loading_body_full(); ?>

<script>

// first load
f_refresh();
//--

$('#btn_refresh').click(function(){
    f_refresh();
});
//---

function f_refresh(){
    $('#warehouse_data').hide();
    $('#progress').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/whship/get_warehouse",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
            $('#progress').hide();
            $('#warehouse_data').fadeIn("5000");
            $("#warehouse_data").html(respons);
        }
    });
}
//---


</script>
