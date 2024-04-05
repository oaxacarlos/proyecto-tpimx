<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Fixed Asset Barcode
</div>

<div class="container-fluid" style="margin-top:20px">
  <div class="row">
    <div class="col-1">
      <button class="btn btn-primary" id="btn_go" style="margin-top:18px;">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px">
  <?php echo load_progress("progr_list_fixedassetbarcode"); ?>
  <div id="list_fixedasset"></div>
</div>


<script>

$("#btn_go").click(function(){
  $('#progr_list_fixedassetbarcode').show();
  $('#list_fixedasset').hide();

  $.ajax({
      url       : "<?php echo base_url();?>index.php/finance/report/fixedassetbarcode_list",
      type      : 'post',
      dataType  : 'html',
      success   :  function(respons){
          $('#progr_list_fixedassetbarcode').hide();
          $('#list_fixedasset').show();
          $('#list_fixedasset').fadeIn("5000");
          $("#list_fixedasset").html(respons);
      }
  });
})
//---


</script>
