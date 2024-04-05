<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Packing
</div>

<div class="container-fluid" style="margin-top:30px;">
  <button class="btn btn-outline-primary" id="btn_refresh"><i class="bi-arrow-clockwise"></i></button>
  <select id="status_packing">
      <option value='pack'>Packing</option>
      <option value='finish_pack'>Finished Packing</option>
      <option value='all'>ALL</option>
  </select>
</div>

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="packing_data"></div>
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
    var status_pack = $("#status_packing").val();

    $('#packing_data').hide();
    $('#progress').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/packing/get_list",
        type      : 'post',
        dataType  : 'html',
        data      :  {status_pack:status_pack},
        success   :  function(respons){
            $('#progress').hide();
            $('#packing_data').fadeIn("5000");
            $("#packing_data").html(respons);
        }
    });
}
//---

$("#status_packing").change(function(){
    f_refresh();
});

</script>
