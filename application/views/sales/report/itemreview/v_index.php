<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Item Review
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
    <div id="report_item_review"></div>
</div>

<script>

$("#btn_go").click(function(){
    var year = $("#datepicker_year").val();

    if(year == ""){
        show_error("You must fill YEAR");
        return false;
    }

    gen_report_item_review(year);

})
//---

function gen_report_item_review(year){
  $("#report_item_review").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/itemreview_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year},
      success   :  function(respons){
          $('#report_item_review').fadeIn("5000");
          $("#report_item_review").html(respons);
      }
  });
}

</script>
