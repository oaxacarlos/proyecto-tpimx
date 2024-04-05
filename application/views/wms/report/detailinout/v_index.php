<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Stock-Inventory'
          }
        ],
    });

    $("#datepicker_from_detailinout").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_to_detailinout").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>

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

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Detail In/Out Bound
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
    <button class="btn btn-outline-primary btn-sm" id="btn_last_month">Last month</button>
    <button class="btn btn-outline-primary btn-sm" id="btn_this_month">This month</button>
    <button class="btn btn-outline-primary btn-sm" id="btn_last_7days">Last 7 days</button>
    <button class="btn btn-outline-primary btn-sm" id="btn_today">Today</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_from_detailinout' class='required form-control' placeholder='Period From'>
    </div>
    <div class="col-md-2">
      <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to_detailinout' class='required form-control' placeholder='Period To'>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="report_view"></div>
</div>

<script>

$("#btn_today").click(function(){
    get_today("datepicker_from_detailinout","datepicker_to_detailinout");
    $("#btn_go").click();
})
//---

$("#btn_this_month").click(function(){
    get_this_month("datepicker_from_detailinout","datepicker_to_detailinout");
    $("#btn_go").click();
})
//---

$("#btn_last_month").click(function(){
    get_last_month("datepicker_from_detailinout","datepicker_to_detailinout");
    $("#btn_go").click();
})
//---

$("#btn_last_7days").click(function(){
    get_last_7days("datepicker_from_detailinout","datepicker_to_detailinout");
    $("#btn_go").click();
})
//---

$("#btn_go").click(function(){
      var date_from = $("#datepicker_from_detailinout").val();
      var date_to = $("#datepicker_to_detailinout").val();

      if(check_from_to(date_from,date_to)){
          $("#report_view").hide();
          $('#progress').show();

          $.ajax({
              url       : "<?php echo base_url();?>index.php/wms/report/detailinout/get_data",
              type      : 'post',
              dataType  : 'html',
              data      :  {date_from:date_from, date_to:date_to},
              success   :  function(respons){
                  $('#progress').hide();
                  $('#report_view').fadeIn("5000");
                  $("#report_view").html(respons);
              }
          });
      }
})
//---

</script>