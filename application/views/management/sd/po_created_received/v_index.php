

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      PO Created - Received
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-1">
      <input type='text' name='datepicker_year' value="<?php echo date("Y"); ?>" id='datepicker_year' class='required form-control' placeholder='Year'>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div id="report_view"></div>
</div>

<script>
$("#btn_go").click(function(){
    var year = $("#datepicker_year").val();

    $("#report_view").html("Loading Report, Please wait...");
    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/po_created_received_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year},
        success   :  function(respons){
            $('#report_view').fadeIn("5000");
            $("#report_view").html(respons);
        }
    });

})
//---

</script>
