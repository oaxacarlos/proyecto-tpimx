<style>
  tr{
    font-size:12px;
  }

  .fontsize{
    font-size:12px;
  }
</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Go To Packing
</div>

<div class="row fontsize" style="margin-left:5px;">
  <div class="col-6 border">
    <div class="row">
      <div class="col-4">
          Doc No
          <span><input type='text' class="form-control input-sm fontsize" value="<?php echo $doc_no; ?>" disabled id="h_doc_no"></span>
      </div>
      <div class="col-3">
        WHS
        <span><input type='text' class="form-control fontsize" value="<?php echo $whs; ?>" disabled id="h_whs"></span>
      </div>
      <div class="col-2 text-right">
        <!--<button class="btn btn-outline-primary btn-sm" style="margin-top:30px;" id="btn_refresh_list_outbound"><i class="bi-arrow-clockwise"></i></button>
        -->
      </div>
    </div>
    <div id="list_outbound"></div>
    <?php echo progress_bar("progr_list_outbound"); ?>
  </div>
  <div class="col-6 border">
      <div id="list_pack"></div>
  </div>
</div>


<script>

// first load
f_refresh_list_outbound();
//--

function f_refresh_list_outbound(){
    var doc_no = '<?php echo $doc_no; ?>';

    $('#list_outbound').hide();
    $('#progr_list_outbound').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/packing/list_outbound",
        type      : 'post',
        dataType  : 'html',
        data      :  {doc_no:doc_no},
        success   :  function(respons){
            $('#progr_list_outbound').hide();
            $('#list_outbound').fadeIn("3000");
            $("#list_outbound").html(respons);
        }
    });
}
//---

$("#btn_refresh_list_outbound").click(function(){
    f_refresh_list_outbound();
})
//---

function f_load_list_pack(){
    $('#list_pack').hide();
    //$('#progr_list_pick').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/packing/list_pack",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
            $('#progr_list_pack').hide();
            $('#list_pack').fadeIn("3000");
            $("#list_pack").html(respons);
        }
    });
}
//---

f_load_list_pack();
//---




</script>
