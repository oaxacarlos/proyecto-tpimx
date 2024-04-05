<script>
$(document).ready(function() {
    $("#datepicker_from_invc").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_to_invc").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_from_delv").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_to_delv").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>


<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Delivery OnTime Report
</div>

<div class="container-fluid" style="margin-top:20px;">
  <ul class="nav nav-tabs" id="myTab" role="tablist">

    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="ontimeinvc-tab" data-toggle="tab" href="#ontimeinvc" role="tab" aria-controls="ontimeinvc" aria-selected="false">By Invoices</a>
    </li>

    <li class="nav-item" role="presentation">
      <a class="nav-link" id="ontimedelv-tab" data-toggle="tab" href="#ontimedelv" role="tab" aria-controls="ontimedelv" aria-selected="false">By Delv Doc</a>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">
    <!-- by invoice -->
    <div class="tab-pane fade show active" id="ontimeinvc" role="tabpanel" aria-labelledby="ontimeinvc-tab">
      <div class="container-fluid" style="margin-top:20px;">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <button class="btn btn-outline-primary btn-sm" id="btn_last_month_invc">Last month</button>
            <button class="btn btn-outline-primary btn-sm" id="btn_this_month_invc">This month</button>
            <button class="btn btn-outline-primary btn-sm" id="btn_last_7days_invc">Last 7 days</button>
            <button class="btn btn-outline-primary btn-sm" id="btn_today_invc">Today</button>
            </div>
          </div>
        </div>

        <div class="container-fluid" style="margin-top:10px;">
          <div class="row">
            <div class="col-md-2">
              Invoice Date From
              <input type='text' name='datepicker_check' value="<?php echo date("Y-m-01"); ?>" id='datepicker_from_invc' class='required form-control' placeholder='Period From'>
            </div>
            <div class="col-md-2">
              Invoice Date From
              <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to_invc' class='required form-control' placeholder='Period To'>
            </div>
            <div class="col-md-2">
                Process<br>
                <button class="btn btn-primary" id="btn_go_invc">GO</button>
            </div>
          </div>
        </div>

        <div class="container-fluid" style="margin-top:30px;">
          <?php echo load_progress("progress_ontimeinvc"); ?>
          <div id="report_ontimeinvc_view"></div>
        </div>
      </div>
    </div>
    <!-- end of by invoices -->

    <!-- by delv doc -->
    <div class="tab-pane fade" id="ontimedelv" role="tabpanel" aria-labelledby="ontimedelv-tab">
      <div class="container-fluid" style="margin-top:20px;">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <button class="btn btn-outline-primary btn-sm" id="btn_last_month_delv">Last month</button>
            <button class="btn btn-outline-primary btn-sm" id="btn_this_month_delv">This month</button>
            <button class="btn btn-outline-primary btn-sm" id="btn_last_7days_delv">Last 7 days</button>
            <button class="btn btn-outline-primary btn-sm" id="btn_today_delv">Today</button>
            </div>
          </div>
        </div>

        <div class="container-fluid" style="margin-top:10px;">
          <div class="row">
            <div class="col-md-2">
              Delivery Date From
              <input type='text' name='datepicker_check' value="<?php echo date("Y-m-01"); ?>" id='datepicker_from_delv' class='required form-control' placeholder='Period From'>
            </div>
            <div class="col-md-2">
              Delivery Date From
              <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to_delv' class='required form-control' placeholder='Period To'>
            </div>
            <div class="col-md-2">
                Process<br>
                <button class="btn btn-primary" id="btn_go_delv">GO</button>
            </div>
          </div>
        </div>

        <div class="container-fluid" style="margin-top:30px;">
          <?php echo load_progress("progress_ontimedelv"); ?>
          <div id="report_ontimedelv_view"></div>
        </div>
      </div>
    </div>
    <!-- en of by delv doc -->

  </div>

</div>

<div class="container-fluid" style="margin-bottom:20px;">

</div>



<script>

// invcs
$("#btn_today_invc").click(function(){
    get_today("datepicker_from_invc","datepicker_to_invc");
    $("#btn_go_invc").click();
})
//---

$("#btn_this_month_invc").click(function(){
    get_this_month("datepicker_from_invc","datepicker_to_invc");
    $("#btn_go_invc").click();
})
//---

$("#btn_last_month_invc").click(function(){
    get_last_month("datepicker_from_invc","datepicker_to_invc");
    $("#btn_go_invc").click();
})
//---

$("#btn_last_7days_invc").click(function(){
    get_last_7days("datepicker_from_invc","datepicker_to_invc");
    $("#btn_go_invc").click();
})
//---

$("#btn_go_invc").click(function(){
      var date_from = $("#datepicker_from_invc").val();
      var date_to = $("#datepicker_to_invc").val();

      if(check_from_to(date_from,date_to)){
          $("#report_ontimeinvc_view").hide();
          $('#progress_ontimeinvc').show();

          $.ajax({
              url       : "<?php echo base_url();?>index.php/operacion/delivery/report/ontimeinvc_data",
              type      : 'post',
              dataType  : 'html',
              data      :  {date_from:date_from, date_to:date_to},
              success   :  function(respons){
                  $('#progress_ontimeinvc').hide();
                  $('#report_ontimeinvc_view').fadeIn("5000");
                  $("#report_ontimeinvc_view").html(respons);
              }
          });
      }
})
//---

// delv
$("#btn_today_delv").click(function(){
    get_today("datepicker_from_delv","datepicker_to_delv");
    $("#btn_go_delv").click();
})
//---

$("#btn_this_month_delv").click(function(){
    get_this_month("datepicker_from_delv","datepicker_to_delv");
    $("#btn_go_delv").click();
})
//---

$("#btn_last_month_delv").click(function(){
    get_last_month("datepicker_from_delv","datepicker_to_delv");
    $("#btn_go_delv").click();
})
//---

$("#btn_last_7days_delv").click(function(){
    get_last_7days("datepicker_from_delv","datepicker_to_delv");
    $("#btn_go_delv").click();
})
//---

$("#btn_go_delv").click(function(){
      var date_from = $("#datepicker_from_delv").val();
      var date_to = $("#datepicker_to_delv").val();

      if(check_from_to(date_from,date_to)){
          $("#report_ontimedelv_view").hide();
          $('#progress_ontimedelv').show();

          $.ajax({
              url       : "<?php echo base_url();?>index.php/operacion/delivery/report/ontimedelv_data",
              type      : 'post',
              dataType  : 'html',
              data      :  {date_from:date_from, date_to:date_to},
              success   :  function(respons){
                  $('#progress_ontimedelv').hide();
                  $('#report_ontimedelv_view').fadeIn("5000");
                  $("#report_ontimedelv_view").html(respons);
              }
          });
      }
})
//---

</script>
