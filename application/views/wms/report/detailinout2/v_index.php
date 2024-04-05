<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'DetailReport'
          }
        ],
    });

    $("#datepicker_from").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_to").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Detail In/Out Bound 2
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
    <div class="col-sm-1">
      <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_from' class='required form-control' placeholder='Period From'>
    </div>
    <div class="col-sm-1">
      <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to' class='required form-control' placeholder='Period To'>
    </div>
    <div class="col-sm-1">
      <select id="inp_doc_type" class='required form-control'>
          <option value="1">InBound</option>
          <option value="2">OutBound</option>
      </select>
    </div>
    <div class="col-sm-1">
      <select id="inp_loc" class='required form-control'>
          <option value="'WH2'">WH2</option>
          <option value="'WH3'">WH3</option>
          <option value="'WH2','WH3'">ALL</option>
      </select>
    </div>
    <div class="col-sm-1" style="font-size:12px;">
      included<br>canceled<br>
      <input type="checkbox" id="inp_included_canceled">
    </div>
    <div class="col-sm-1" style="font-size:12px;">
      included<br>internal shipment<br>
      <input type="checkbox" id="inp_included_internal_shipment">
    </div>
    <div class="col-sm-1">
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
    get_today("datepicker_from","datepicker_to");
    $("#btn_go").click();
})
//---

$("#btn_this_month").click(function(){
    get_this_month("datepicker_from","datepicker_to");
    $("#btn_go").click();
})
//---

$("#btn_last_month").click(function(){
    get_last_month("datepicker_from","datepicker_to");
    $("#btn_go").click();
})
//---

$("#btn_last_7days").click(function(){
    get_last_7days("datepicker_from","datepicker_to");
    $("#btn_go").click();
})
//---

$("#btn_go").click(function(){
      var date_from = $("#datepicker_from").val();
      var date_to = $("#datepicker_to").val();
      var doc_type = $("#inp_doc_type").val();
      var loc = $("#inp_loc").val();
      var canceled = $("#inp_included_canceled").is(":checked");
      var internal = $("#inp_included_internal_shipment").is(":checked");

      if(canceled == false) canceled = 0;
      else canceled = 1;

      if(internal == false) internal = 0;
      else internal = 1;

      if(check_from_to(date_from,date_to)){
          $("#report_view").hide();
          $('#progress').show();

          $.ajax({
              url       : "<?php echo base_url();?>index.php/wms/report/detailinout2/get_data",
              type      : 'post',
              dataType  : 'html',
              data      :  {date_from:date_from, date_to:date_to, doc_type:doc_type, loc:loc, canceled:canceled, internal:internal},
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
