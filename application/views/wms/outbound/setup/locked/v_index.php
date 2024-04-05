
<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Locked Document
</div>

<div class="container-fluid" style="margin-top:30px;">
  <button class="btn btn-outline-primary" id="btn_refresh"><i class="bi-arrow-clockwise"></i></button>
</div>

<div class="container" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="list_data"></div>
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
    $('#list_data').hide();
    $('#progress').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/setup/locked_doc",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
            $('#progress').hide();
            $('#list_data').fadeIn("5000");
            $("#list_data").html(respons);
        }
    });
}
//---

</script>
